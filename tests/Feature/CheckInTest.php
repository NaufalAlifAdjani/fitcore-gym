<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\CheckIn;
use App\Models\Membership;
use App\Models\MemberProfile;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Feature tests for QR Code Check-In.
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

    /** Create a member with a MemberProfile that has a qr_token */
    private function memberWithProfile(string $qrToken): array
    {
        $member = User::factory()->create(['role' => 'member']);
        $profile = MemberProfile::factory()->create([
            'user_id'   => $member->id,
            'qr_token'  => $qrToken,
        ]);
        return [$member, $profile];
    }

    /** Create an active membership with a verified payment for a member */
    private function activeMembership(User $member, array $overrides = []): Membership
    {
        $package = MembershipPackage::factory()->create([
            'name'          => 'Monthly',
            'duration_days' => 30,
            'price'         => 200000,
        ]);

        $membership = Membership::factory()->create(array_merge([
            'member_id'            => $member->id,
            'membership_package_id' => $package->id,
            'start_date'           => now('Asia/Jakarta')->subDays(5)->toDateString(),
            'end_date'             => now('Asia/Jakarta')->addDays(25)->toDateString(),
            'status'               => 'active',
        ], $overrides));

        $bank = Bank::factory()->create();

        // Verified payment
        Payment::factory()->create([
            'member_id'    => $member->id,
            'membership_id' => $membership->id,
            'payment_type' => 'membership',
            'amount'       => 200000,
            'bank_id'      => $bank->id,
            'status'       => 'verified',
        ]);

        return $membership;
    }

    // ── Tests ────────────────────────────────────────────────────────────────

    /**
     * TC-1: Valid QR + active membership → check-in succeeds.
     */
    public function test_valid_qr_with_active_membership_checks_in_successfully(): void
    {
        $admin = $this->adminUser();
        $token = Str::uuid()->toString();
        [$member] = $this->memberWithProfile($token);
        $this->activeMembership($member);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['member_name', 'membership', 'valid_until', 'checked_in_at']);

        $this->assertDatabaseHas('check_ins', [
            'member_id' => $member->id,
            'method'    => 'qr_code',
            'checked_by' => $admin->id,
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
     * TC-3: Member without any membership → rejected.
     */
    public function test_member_without_membership_is_rejected(): void
    {
        $admin = $this->adminUser();
        $token = Str::uuid()->toString();
        $this->memberWithProfile($token);

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
        $token = Str::uuid()->toString();
        [$member] = $this->memberWithProfile($token);

        $this->activeMembership($member, [
            'start_date' => now('Asia/Jakarta')->subDays(60)->toDateString(),
            'end_date'   => now('Asia/Jakarta')->subDays(30)->toDateString(),
            'status'     => 'expired',
        ]);

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
        $token = Str::uuid()->toString();
        [$member] = $this->memberWithProfile($token);

        $package = MembershipPackage::factory()->create();
        $membership = Membership::factory()->create([
            'member_id'             => $member->id,
            'membership_package_id' => $package->id,
            'start_date'            => now('Asia/Jakarta')->subDays(1)->toDateString(),
            'end_date'              => now('Asia/Jakarta')->addDays(29)->toDateString(),
            'status'                => 'active',
        ]);

        // Pending payment — NOT verified
        Payment::factory()->create([
            'member_id'    => $member->id,
            'membership_id' => $membership->id,
            'payment_type' => 'membership',
            'bank_id'      => Bank::factory()->create()->id,
            'status'       => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    /**
     * TC-6: Member who already checked in today → rejected.
     */
    public function test_duplicate_check_in_same_day_is_rejected(): void
    {
        $admin = $this->adminUser();
        $token = Str::uuid()->toString();
        [$member] = $this->memberWithProfile($token);
        $membership = $this->activeMembership($member);

        // Simulate a previous check-in for today
        CheckIn::create([
            'member_id'     => $member->id,
            'membership_id' => $membership->id,
            'check_in_date' => now('Asia/Jakarta')->toDateString(),
            'checked_in_at' => now('Asia/Jakarta'),
            'method'        => 'qr_code',
            'checked_by'    => $admin->id,
        ]);

        $response = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'Member sudah melakukan check-in hari ini.',
            ]);
    }

    /**
     * TC-7: Regular member cannot access the scanner endpoint.
     */
    public function test_member_role_cannot_access_checkin_endpoint(): void
    {
        $memberUser = User::factory()->create(['role' => 'member']);
        $token = Str::uuid()->toString();

        $response = $this->actingAs($memberUser)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);

        // Should be 403 Forbidden
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
     * TC-8: Two concurrent requests with same token only create one check-in.
     * (Tests the unique constraint safeguard.)
     */
    public function test_concurrent_requests_only_create_one_check_in(): void
    {
        $admin = $this->adminUser();
        $token = Str::uuid()->toString();
        [$member] = $this->memberWithProfile($token);
        $this->activeMembership($member);

        // First request succeeds
        $first = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);
        $first->assertStatus(200)->assertJson(['success' => true]);

        // Second request (simulating near-simultaneous submission) is rejected
        $second = $this->actingAs($admin)
            ->postJson(route('admin.checkin.store'), ['qr_token' => $token]);
        $second->assertStatus(409)->assertJson(['success' => false]);

        // Only one record in the database
        $this->assertDatabaseCount('check_ins', 1);
    }
}
