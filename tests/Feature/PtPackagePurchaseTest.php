<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PtPackagePurchaseTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ─────────────────────────────────────────────────────────────

    /** Create a member user */
    private function createMember(array $attributes = []): User
    {
        $member = User::factory()->create(array_merge(['role' => 'member'], $attributes));
        MemberProfile::factory()->create(['user_id' => $member->id]);

        return $member;
    }

    /** Create an admin user */
    private function createAdmin(array $attributes = []): User
    {
        /** @var User $admin */
        $admin = User::factory()->create(array_merge(['role' => 'admin'], $attributes));

        return $admin;
    }

    /** Create an active membership for a member with specific remaining days */
    private function createActiveMembership(User $member, int $remainingDays = 30): Membership
    {
        $pkg = MembershipPackage::factory()->create([
            'name' => 'Gold Membership',
            'duration_days' => 90,
            'price' => 500000,
        ]);

        $today = now('Asia/Jakarta')->startOfDay();

        $membership = Membership::factory()->create([
            'member_id' => $member->id,
            'membership_package_id' => $pkg->id,
            'start_date' => $today->copy()->subDays(10)->toDateString(),
            'end_date' => $today->copy()->addDays($remainingDays)->toDateString(),
            'status' => 'active',
        ]);

        $bank = Bank::factory()->create(['status' => 'active']);

        Payment::factory()->create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'payment_type' => 'membership',
            'amount' => 500000,
            'bank_id' => $bank->id,
            'status' => 'verified',
        ]);

        return $membership;
    }

    // ── Tests ────────────────────────────────────────────────────────────────

    /** Test 1: Member can view PT packages page */
    public function test_member_can_view_pt_packages_page(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 45);

        PtPackage::factory()->create([
            'name' => 'PT 10 Sesi',
            'pt_session_count' => 10,
            'price' => 900000,
            'min_membership_days' => 30,
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.index'));

        $response->assertStatus(200);
        $response->assertSee('PT 10 Sesi');
        $response->assertSee('900.000');
    }

    /** Test 2: Guest is redirected to login */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('member.pt-packages.index'));
        $response->assertRedirect(route('login'));
    }

    /** Test 3: Admin or Trainer cannot access member purchase endpoint */
    public function test_non_member_cannot_access_member_pt_packages(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('member.pt-packages.index'));
        $response->assertStatus(403);

        $postResponse = $this->actingAs($admin)->post(route('member.pt-packages.buy'), [
            'pt_package_id' => 1,
            'bank_id' => 1,
        ]);
        $postResponse->assertStatus(403);
    }

    /** Test 4: Member with sufficient membership days can successfully purchase PT package */
    public function test_member_with_sufficient_membership_days_can_successfully_purchase_pt_package(): void
    {
        $member = $this->createMember();
        $membership = $this->createActiveMembership($member, 40); // 40 days remaining

        $package = PtPackage::factory()->create([
            'name' => 'PT 10 Sesi',
            'pt_session_count' => 10,
            'price' => 900000,
            'min_membership_days' => 30, // Requires 30 days (40 >= 30, OK)
            'validity_days' => 90,
            'status' => 'active',
        ]);

        $bank = Bank::factory()->create(['status' => 'active']);

        $response = $this->actingAs($member)
            ->postJson(route('member.pt-packages.buy'), [
                'pt_package_id' => $package->id,
                'bank_id' => $bank->id,
            ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);

        // Verify Payment is stored
        $this->assertDatabaseHas('payments', [
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'pt_package_id' => $package->id,
            'bank_id' => $bank->id,
            'payment_type' => 'pt_package',
            'amount' => 900000,
            'status' => 'pending',
        ]);

        // Verify MemberPtQuota is stored
        $this->assertDatabaseHas('member_pt_quotas', [
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'status' => 'active',
        ]);
    }

    /** Test 5: Member with insufficient membership days is rejected */
    public function test_member_with_insufficient_membership_days_is_rejected(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 15); // Only 15 days remaining

        $package = PtPackage::factory()->create([
            'name' => 'PT 10 Sesi',
            'pt_session_count' => 10,
            'price' => 900000,
            'min_membership_days' => 30, // Requires 30 days (15 < 30, REJECT)
            'status' => 'active',
        ]);

        $bank = Bank::factory()->create(['status' => 'active']);

        $response = $this->actingAs($member)
            ->postJson(route('member.pt-packages.buy'), [
                'pt_package_id' => $package->id,
                'bank_id' => $bank->id,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'required_days' => 30,
            'remaining_days' => 15,
        ]);
        $this->assertStringContainsString('kurang dari syarat minimum', $response->json('message'));

        // Ensure no payment or quota was created
        $this->assertDatabaseMissing('payments', [
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
        ]);
        $this->assertDatabaseMissing('member_pt_quotas', [
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
        ]);
    }

    /** Test 6: Member without active membership is rejected for package with min_days */
    public function test_member_without_active_membership_is_rejected_for_package_requiring_min_days(): void
    {
        $member = $this->createMember(); // No active membership

        $package = PtPackage::factory()->create([
            'name' => 'PT 5 Sesi',
            'pt_session_count' => 5,
            'price' => 500000,
            'min_membership_days' => 30,
            'status' => 'active',
        ]);

        $bank = Bank::factory()->create(['status' => 'active']);

        $response = $this->actingAs($member)
            ->postJson(route('member.pt-packages.buy'), [
                'pt_package_id' => $package->id,
                'bank_id' => $bank->id,
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'required_days' => 30,
            'remaining_days' => 0,
        ]);
        $this->assertStringContainsString('belum memiliki membership aktif', $response->json('message'));

        $this->assertDatabaseMissing('member_pt_quotas', [
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
        ]);
    }

    /** Test 7: Package with zero min_membership_days can be purchased */
    public function test_member_can_purchase_package_with_zero_min_membership_days(): void
    {
        $member = $this->createMember();

        $package = PtPackage::factory()->create([
            'name' => 'PT Trial Session',
            'pt_session_count' => 1,
            'price' => 100000,
            'min_membership_days' => 0, // No minimum days requirement
            'validity_days' => 14,
            'status' => 'active',
        ]);

        $bank = Bank::factory()->create(['status' => 'active']);

        $response = $this->actingAs($member)
            ->postJson(route('member.pt-packages.buy'), [
                'pt_package_id' => $package->id,
                'bank_id' => $bank->id,
            ]);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('member_pt_quotas', [
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'total_sessions' => 1,
            'remaining_sessions' => 1,
        ]);
    }

    /** Test 8: Purchase fails if package is inactive */
    public function test_purchase_fails_if_package_is_inactive(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 50);

        $package = PtPackage::factory()->create([
            'status' => 'inactive',
        ]);

        $bank = Bank::factory()->create(['status' => 'active']);

        $response = $this->actingAs($member)
            ->postJson(route('member.pt-packages.buy'), [
                'pt_package_id' => $package->id,
                'bank_id' => $bank->id,
            ]);

        $response->assertStatus(404);
    }

    /** Test 9: Packages requiring more days than remaining are hidden from catalog */
    public function test_ineligible_packages_are_hidden_from_catalog(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 20); // 20 days remaining

        // Eligible package (requires 15 days <= 20)
        PtPackage::factory()->create([
            'name' => 'PT 8 Sesi',
            'pt_session_count' => 8,
            'price' => 700000,
            'min_membership_days' => 15,
            'status' => 'active',
        ]);

        // Ineligible package (requires 30 days > 20)
        PtPackage::factory()->create([
            'name' => 'PT 20 Sesi',
            'pt_session_count' => 20,
            'price' => 1500000,
            'min_membership_days' => 30,
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.index'));

        $response->assertStatus(200);
        $response->assertSee('PT 8 Sesi');
        $response->assertDontSee('PT 20 Sesi');
    }

    /** Test 10: Message and renewal link shown when no packages are eligible */
    public function test_message_and_renewal_link_shown_when_no_packages_eligible(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 5); // Only 5 days remaining

        PtPackage::factory()->create([
            'name' => 'PT 10 Sesi',
            'pt_session_count' => 10,
            'price' => 900000,
            'min_membership_days' => 30,
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.index'));

        $response->assertStatus(200);
        $response->assertDontSee('PT 10 Sesi');
        $response->assertSee('Sisa Masa Aktif Membership Tidak Mencukupi');
        $response->assertSee('Perpanjang Membership Sekarang');
    }

    /** Test 11: Member can purchase package with proof image upload */
    public function test_member_can_purchase_pt_package_with_proof_image(): void
    {
        $storage = Storage::fake('public');

        $member = $this->createMember();
        $membership = $this->createActiveMembership($member, 40);

        $package = PtPackage::factory()->create([
            'pt_session_count' => 10,
            'price' => 900000,
            'min_membership_days' => 30,
            'validity_days' => 90,
            'status' => 'active',
        ]);

        $bank = Bank::factory()->create(['status' => 'active']);
        $file = UploadedFile::fake()->image('transfer_proof.jpg');

        $response = $this->actingAs($member)
            ->post(route('member.pt-packages.buy'), [
                'pt_package_id' => $package->id,
                'bank_id' => $bank->id,
                'proof_image' => $file,
            ]);

        $response->assertSessionHas('success');

        $payment = Payment::where('member_id', $member->id)->where('pt_package_id', $package->id)->latest()->first();
        $this->assertNotNull($payment);
        $this->assertNotNull($payment->proof_image_path);
        $storage->assertExists($payment->proof_image_path);
    }
}
