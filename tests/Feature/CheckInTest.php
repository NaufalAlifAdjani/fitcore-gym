<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\CheckIn;
use App\Models\MemberProfile;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\QrToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for Dynamic QR Code Check-In.
 *
 * Uses SQLite in-memory (configured in phpunit.xml) — safe for dev environment.
 * Does NOT touch the PostgreSQL development database.
 */
class CheckInTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ─────────────────────────────────────────────────────────────

    /** Create an admin user */
    private function adminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    /** Create a member with a MemberProfile */
    private function memberWithProfile(): User
    {
        $member = User::factory()->create(['role' => 'member']);
        MemberProfile::factory()->create(['user_id' => $member->id]);

        return $member;
    }

    /**
     * Create a valid (unused, non-expired) QrToken for a member.
     * Returns the plain token string to submit in the request.
     */
    private function validQrToken(User $member): string
    {
        $token = bin2hex(random_bytes(32));

        QrToken::create([
            'member_id' => $member->id,
            'token' => $token,
            'expires_at' => now()->addSeconds(30),
            'used_at' => null,
        ]);

        return $token;
    }

    /** Create an active membership with a verified payment for a member */
    private function activeMembership(User $member, array $overrides = []): Membership
    {
        $package = MembershipPackage::factory()->create([
            'name' => 'Monthly',
            'duration_days' => 30,
            'price' => 200000,
        ]);

        $membership = Membership::factory()->create(array_merge([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'start_date' => now('Asia/Jakarta')->subDays(5)->toDateString(),
            'end_date' => now('Asia/Jakarta')->addDays(25)->toDateString(),
            'status' => 'active',
        ], $overrides));

        $bank = Bank::factory()->create();

        // Verified payment
        Payment::factory()->create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'payment_type' => 'membership',
            'amount' => 200000,
            'bank_id' => $bank->id,
            'status' => 'verified',
        ]);

        return $membership;
    }

    // ── Tests ────────────────────────────────────────────────────────────────

    /**
     * TC-1: Valid dynamic QR + active membership → check-in succeeds.
     */
    public function test_valid_qr_with_active_membership_checks_in_successfully(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();
        $this->activeMembership($member);

        $token = $this->validQrToken($member);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['member_name', 'membership', 'valid_until', 'checked_in_at']);

        $this->assertDatabaseHas('check_ins', [
            'member_id' => $member->id,
            'method' => 'qr_code',
            'checked_by' => $admin->id,
        ]);

        // Token must be marked as used
        $this->assertDatabaseMissing('qr_tokens', [
            'token' => $token,
            'used_at' => null,
        ]);
    }

    /**
     * TC-2: Unregistered token → rejected with 404.
     */
    public function test_unregistered_qr_token_is_rejected(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => 'nonexistent-token-xyz']);

        $response->assertStatus(404)
            ->assertJson(['success' => false])
            ->assertJsonFragment(['message' => 'QR Code tidak valid atau tidak terdaftar.']);
    }

    /**
     * TC-2b: Expired token → rejected with 422.
     */
    public function test_expired_qr_token_is_rejected(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();
        $this->activeMembership($member);

        $token = bin2hex(random_bytes(32));
        QrToken::create([
            'member_id' => $member->id,
            'token' => $token,
            'expires_at' => now()->subSeconds(5), // already expired
            'used_at' => null,
        ]);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(422)
            ->assertJson(['success' => false])
            ->assertJsonFragment(['message' => 'QR Code sudah kedaluwarsa. Minta member untuk menampilkan QR baru.']);
    }

    /**
     * TC-2c: Already-used token → rejected with 422.
     */
    public function test_used_qr_token_is_rejected(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();
        $this->activeMembership($member);

        $token = bin2hex(random_bytes(32));
        QrToken::create([
            'member_id' => $member->id,
            'token' => $token,
            'expires_at' => now()->addSeconds(30),
            'used_at' => now()->subSeconds(2), // already used
        ]);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(422)
            ->assertJson(['success' => false])
            ->assertJsonFragment(['message' => 'QR Code sudah digunakan. Minta member untuk menampilkan QR baru.']);
    }

    /**
     * TC-3: Member without any membership → rejected.
     */
    public function test_member_without_membership_is_rejected(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();

        $token = $this->validQrToken($member);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    /**
     * TC-4: Expired membership → rejected.
     */
    public function test_expired_membership_is_rejected(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();

        $this->activeMembership($member, [
            'start_date' => now('Asia/Jakarta')->subDays(60)->toDateString(),
            'end_date' => now('Asia/Jakarta')->subDays(30)->toDateString(),
            'status' => 'expired',
        ]);

        $token = $this->validQrToken($member);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    /**
     * TC-5: Membership with unverified payment → rejected.
     */
    public function test_membership_with_unverified_payment_is_rejected(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();

        $package = MembershipPackage::factory()->create();
        $membership = Membership::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'start_date' => now('Asia/Jakarta')->subDays(1)->toDateString(),
            'end_date' => now('Asia/Jakarta')->addDays(29)->toDateString(),
            'status' => 'active',
        ]);

        // Pending payment — NOT verified
        Payment::factory()->create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'payment_type' => 'membership',
            'bank_id' => Bank::factory()->create()->id,
            'status' => 'pending',
        ]);

        $token = $this->validQrToken($member);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    /**
     * TC-6: Check-in within 10-minute cooldown → rejected with 429.
     */
    public function test_check_in_within_cooldown_is_rejected(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();
        $membership = $this->activeMembership($member);

        // Simulate a check-in 5 minutes ago (within the 10-minute cooldown)
        CheckIn::create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'check_in_date' => now('Asia/Jakarta')->toDateString(),
            'checked_in_at' => now('Asia/Jakarta')->subMinutes(5),
            'method' => 'qr_code',
            'checked_by' => $admin->id,
        ]);

        $token = $this->validQrToken($member);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(429)
            ->assertJson(['success' => false]);
    }

    /**
     * TC-6b: Second check-in after cooldown (>= 10 min) → succeeds.
     */
    public function test_check_in_after_cooldown_succeeds(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();
        $membership = $this->activeMembership($member);

        // Simulate a check-in 11 minutes ago (outside cooldown)
        CheckIn::create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'check_in_date' => now('Asia/Jakarta')->toDateString(),
            'checked_in_at' => now('Asia/Jakarta')->subMinutes(11),
            'method' => 'qr_code',
            'checked_by' => $admin->id,
        ]);

        $token = $this->validQrToken($member);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseCount('check_ins', 2);
    }

    /**
     * TC-7: Regular member cannot access the scanner endpoint.
     */
    public function test_member_role_cannot_access_checkin_endpoint(): void
    {
        $memberUser = User::factory()->create(['role' => 'member']);
        $token = bin2hex(random_bytes(32));

        $response = $this->actingAs($memberUser)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(403);
    }

    /**
     * TC-7b: Unauthenticated user cannot access the scanner page.
     */
    public function test_unauthenticated_user_cannot_access_scanner_page(): void
    {
        $response = $this->get(route('admin.checkin.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * TC-7c: Admin can access the scanner page.
     */
    public function test_admin_can_access_scanner_page(): void
    {
        $admin = $this->adminUser();
        $response = $this->actingAs($admin)->get(route('admin.checkin.index'));
        $response->assertStatus(200);
    }

    /**
     * TC-8: Two concurrent requests with same token only process one check-in
     * (the second sees the token already used).
     */
    public function test_concurrent_requests_only_create_one_check_in(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();
        $this->activeMembership($member);

        $token = $this->validQrToken($member);

        // First request succeeds
        $first = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);
        $first->assertStatus(200)->assertJson(['success' => true]);

        // Second request with the same (now-used) token is rejected
        $second = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);
        $second->assertStatus(422)->assertJson(['success' => false]);

        // Only one record in the database
        $this->assertDatabaseCount('check_ins', 1);
    }

    /**
     * TC-9: Valid QR + active membership linked via memberships.payment_id → check-in succeeds.
     */
    public function test_valid_qr_with_active_membership_linked_via_payment_id_checks_in_successfully(): void
    {
        $admin = $this->adminUser();
        $member = $this->memberWithProfile();

        $package = MembershipPackage::factory()->create([
            'name' => 'Monthly',
            'duration_days' => 30,
            'price' => 200000,
        ]);

        $bank = Bank::factory()->create();

        $payment = Payment::factory()->create([
            'member_id' => $member->id,
            'membership_id' => null,
            'payment_type' => 'membership',
            'amount' => 200000,
            'bank_id' => $bank->id,
            'status' => 'verified',
        ]);

        Membership::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $package->id,
            'payment_id' => $payment->id,
            'start_date' => now('Asia/Jakarta')->subDays(2)->toDateString(),
            'end_date' => now('Asia/Jakarta')->addDays(28)->toDateString(),
            'status' => 'active',
        ]);

        $token = $this->validQrToken($member);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['member_name', 'membership', 'valid_until', 'checked_in_at']);

        $this->assertDatabaseHas('check_ins', [
            'member_id' => $member->id,
            'method' => 'qr_code',
            'checked_by' => $admin->id,
        ]);
    }
}
