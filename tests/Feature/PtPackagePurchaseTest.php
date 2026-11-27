<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\TrainerProfile;
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
            'validity_days' => 30,
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
            'validity_days' => 30,
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
            'validity_days' => 30,
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
            'remaining_days' => 16,
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
            'validity_days' => 30,
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
            'validity_days' => 0,
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

    /** Test 9: All packages appear, but ineligible packages show disabled button with min membership requirement */
    public function test_ineligible_packages_are_displayed_with_disabled_button(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 20); // 20 days remaining

        // Eligible package (requires 15 days <= 20)
        PtPackage::factory()->create([
            'name' => 'PT 8 Sesi',
            'pt_session_count' => 8,
            'price' => 700000,
            'validity_days' => 15,
            'status' => 'active',
        ]);

        // Ineligible package (requires 30 days > 20)
        PtPackage::factory()->create([
            'name' => 'PT 20 Sesi',
            'pt_session_count' => 20,
            'price' => 1500000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.index'));

        $response->assertStatus(200);
        $response->assertSee('PT 8 Sesi');
        $response->assertSee('PT 20 Sesi');
        $response->assertSee('Min. Membership 30 Hari');
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
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.index'));

        $response->assertStatus(200);
        $response->assertSee('PT 10 Sesi');
        $response->assertSee('Min. Membership 30 Hari');
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
            'validity_days' => 30,
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

    /** Test 12: Select trainer page renders with back button and real-time search */
    public function test_select_trainer_page_renders_with_back_button(): void
    {
        $member = $this->createMember();
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Rama Prasetya',
            'shift' => 'pagi',
        ]);
        TrainerProfile::factory()->create([
            'user_id' => $trainer->id,
            'specialization' => 'Hypertrophy',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.select-trainer'));

        $response->assertStatus(200);
        $response->assertSee('Kembali ke Pilih Paket Sesi');
        $response->assertSee('Cari nama atau spesialis...');
        $response->assertDontSee('Rating Tertinggi');
    }

    /** Test 13: Checkout page renders without promo/voucher and has back button */
    public function test_checkout_page_renders_without_promo_and_has_back_button(): void
    {
        $member = $this->createMember();
        $response = $this->actingAs($member)->get(route('member.pt-packages.checkout'));

        $response->assertStatus(200);
        $response->assertSee('Kembali ke Pilih Pelatih');
        $response->assertDontSee('PUNYA KODE PROMO ATAU REFERRAL?');
        $response->assertDontSee('Diskon Voucher');
    }

    /** Test 14: Submit payment proof requires proof image and sender name */
    public function test_submit_payment_proof_validation_rules(): void
    {
        $member = $this->createMember();
        $response = $this->actingAs($member)
            ->post(route('member.pt-packages.submit-proof'), [
                'proof_image' => null,
                'sender_name' => '',
            ]);

        $response->assertSessionHasErrors(['proof_image', 'sender_name']);
    }

    /** Test 14b: Submit payment proof rejects files larger than 2MB */
    public function test_submit_payment_proof_rejects_files_larger_than_2mb(): void
    {
        $member = $this->createMember();
        $file = UploadedFile::fake()->create('large_image.jpg', 3000, 'image/jpeg');

        $response = $this->actingAs($member)
            ->post(route('member.pt-packages.submit-proof'), [
                'proof_image' => $file,
                'sender_name' => 'John Doe',
                'sender_bank' => 'BCA',
            ]);

        $response->assertSessionHasErrors(['proof_image']);
    }

    /** Test 14c: Submit payment proof rejects non-image files such as PDF */
    public function test_submit_payment_proof_rejects_non_image_files(): void
    {
        $member = $this->createMember();
        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($member)
            ->post(route('member.pt-packages.submit-proof'), [
                'proof_image' => $file,
                'sender_name' => 'John Doe',
                'sender_bank' => 'BCA',
            ]);

        $response->assertSessionHasErrors(['proof_image']);
    }

    /** Test 15: Member can submit payment proof successfully and redirects to reusable payment-status */
    public function test_member_can_submit_payment_proof_successfully(): void
    {
        $storage = Storage::fake('public');
        $member = $this->createMember();
        $bank = Bank::factory()->create(['status' => 'active']);
        $payment = Payment::factory()->create([
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'payment_type' => 'pt_package',
            'amount' => 3000421,
            'status' => 'pending',
        ]);

        $file = UploadedFile::fake()->image('my_proof.jpg');

        $response = $this->actingAs($member)
            ->post(route('member.pt-packages.submit-proof', ['payment' => $payment->id]), [
                'proof_image' => $file,
                'sender_name' => 'John Doe',
                'sender_bank' => 'BCA',
            ]);

        $response->assertRedirect(route('member.membership.payment-status', $payment->id));
        $response->assertSessionHas('success');

        $payment->refresh();
        $this->assertNotNull($payment->proof_image_path);
        $storage->assertExists($payment->proof_image_path);
    }

    /** Test 15b: Member submitting payment proof from mobile redirects to mobile payment status */
    public function test_submit_payment_proof_from_mobile_redirects_to_mobile_payment_status(): void
    {
        Storage::fake('public');
        $member = $this->createMember();
        $bank = Bank::factory()->create(['status' => 'active']);
        $payment = Payment::factory()->create([
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'payment_type' => 'pt_package',
            'amount' => 3000421,
            'status' => 'pending',
        ]);

        $file = UploadedFile::fake()->image('my_proof.jpg');

        $response = $this->actingAs($member)
            ->post(route('mobile.member.pt-packages.submit-proof', ['payment' => $payment->id]), [
                'proof_image' => $file,
                'sender_name' => 'John Doe',
                'sender_bank' => 'BCA',
            ]);

        $response->assertRedirect(route('mobile.member.packages.payment-status', $payment->id));
        $response->assertSessionHas('success');
    }

    /** Test 16: PT package checkout and admin verification end-to-end integration */
    public function test_pt_package_checkout_and_admin_approval_end_to_end_integration(): void
    {
        $storage = Storage::fake('public');
        $member = $this->createMember();
        $this->createActiveMembership($member, 60);

        $admin = $this->createAdmin();

        $package = PtPackage::factory()->create([
            'name' => 'PT Pro 12 Sesi',
            'pt_session_count' => 12,
            'price' => 3000000,
            'validity_days' => 60,
            'status' => 'active',
        ]);

        $bank = Bank::factory()->create(['status' => 'active', 'name' => 'BCA']);

        // 1. Member melakukan checkout
        $checkoutResponse = $this->actingAs($member)
            ->post(route('member.pt-packages.process-checkout'), [
                'package_id' => $package->id,
                'trainer_id' => 1,
                'bank_id' => $bank->id,
            ]);

        $checkoutResponse->assertRedirect();
        $payment = Payment::where('member_id', $member->id)
            ->where('package_id', $package->id)
            ->first();

        $this->assertNotNull($payment);
        $this->assertStringStartsWith('INV-PT-', $payment->invoice_id);
        $this->assertSame('pending', $payment->status);
        $this->assertSame('pt_session', $payment->package_type);

        // 2. Member mengunggah bukti pembayaran
        $file = UploadedFile::fake()->image('transfer_receipt.jpg');
        $uploadResponse = $this->actingAs($member)
            ->post(route('member.pt-packages.submit-proof', ['payment' => $payment->id]), [
                'proof_image' => $file,
                'sender_name' => 'Budi Member',
                'sender_bank' => 'BCA',
            ]);

        $uploadResponse->assertRedirect(route('member.membership.payment-status', $payment->id));
        $uploadResponse->assertSessionHas('success');

        // Verifikasi halaman status pembayaran menampilkan tombol tunggal ke Riwayat Transaksi
        $statusPageResponse = $this->actingAs($member)
            ->get(route('member.membership.payment-status', $payment->id));
        $statusPageResponse->assertStatus(200);
        $statusPageResponse->assertSee('Lihat Riwayat Transaksi');
        $statusPageResponse->assertSee(route('member.riwayat-transaksi.index'));
        $statusPageResponse->assertDontSee('Ke Dashboard Member');
        $statusPageResponse->assertDontSee('Kembali ke Paket PT');

        $payment->refresh();
        $this->assertNotNull($payment->proof_image_path);
        $this->assertNotNull($payment->proof_image_url);
        $this->assertNotNull($payment->transfer_date);
        $this->assertStringContainsString('Budi Member', $payment->bank_sender);

        // 3. Admin memverifikasi dan menyetujui pembayaran
        $approveResponse = $this->actingAs($admin)
            ->postJson(route('admin.payments.approve', $payment->id));

        $approveResponse->assertOk();
        $approveResponse->assertJson(['success' => true]);

        $payment->refresh();
        $this->assertSame('verified', $payment->status);
        $this->assertSame($admin->id, $payment->verified_by);

        // 4. Kuota sesi PT member aktif
        $this->assertDatabaseHas('member_pt_quotas', [
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 12,
            'remaining_sessions' => 12,
            'status' => 'active',
        ]);

        // 5. Member dapat mengakses halaman booking sesi
        $bookingPageResponse = $this->actingAs($member)
            ->get(route('pt-sessions.create'));

        $bookingPageResponse->assertOk();
        $bookingPageResponse->assertSee('Pilih Jadwal');
        $bookingPageResponse->assertSee('12 Sesi');
    }

    /** Test 17: Purchased PT package card remains visible and shows quota indicator */
    public function test_purchased_pt_package_card_does_not_disappear_from_packages_page(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 180);

        $package = PtPackage::factory()->create([
            'name' => 'PT 16 Sesi Khusus',
            'pt_session_count' => 16,
            'price' => 2500000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'total_sessions' => 16,
            'remaining_sessions' => 16,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(30),
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.index'));

        $response->assertOk();
        $response->assertSee('PT 16 Sesi Khusus');
        $response->assertSee('Sisa 16 Sesi');
        $response->assertSee('Sisa Kuota Masih 16 Sesi');
    }

    /** Test 18: PT checkout renders random 3-digit unique code */
    public function test_pt_checkout_renders_random_unique_code(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 90);

        $package = PtPackage::factory()->create([
            'name' => 'PT 8 Sesi Pro',
            'pt_session_count' => 8,
            'price' => 2000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.checkout', [
            'package_id' => $package->id,
        ]));

        $response->assertOk();
        $uniqueCode = $response->viewData('uniqueCode');
        $this->assertIsInt($uniqueCode);
        $this->assertGreaterThanOrEqual(100, $uniqueCode);
        $this->assertLessThanOrEqual(999, $uniqueCode);
    }

    /** Test 19: Member cannot select PT package when remaining membership days are less than min_membership_days */
    public function test_member_cannot_select_pt_package_when_membership_duration_is_less_than_min_membership_days(): void
    {
        $member = $this->createMember();
        // Member has 10 days membership remaining
        $this->createActiveMembership($member, 10);

        $package = PtPackage::factory()->create([
            'name' => 'PT 16 Sesi Minimum Check',
            'pt_session_count' => 16,
            'price' => 3000000,
            'validity_days' => 20,
            'status' => 'active',
        ]);

        // In index page: package is disabled with uniform gray button showing its minimum days
        $indexResponse = $this->actingAs($member)->get(route('member.pt-packages.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Min. Membership 20 Hari');

        // Trying to access select-trainer should redirect back with error
        $selectTrainerResponse = $this->actingAs($member)->get(route('member.pt-packages.select-trainer', [
            'package_id' => $package->id,
            'sessions' => 16,
        ]));

        $selectTrainerResponse->assertRedirect(route('member.pt-packages.index'));
        $selectTrainerResponse->assertSessionHas('error');
        $this->assertStringContainsString('tidak mencukupi untuk paket ini', session('error'));

        // Trying to access checkout should also redirect back with error
        $checkoutResponse = $this->actingAs($member)->get(route('member.pt-packages.checkout', [
            'package_id' => $package->id,
        ]));

        $checkoutResponse->assertRedirect(route('member.pt-packages.index'));
        $checkoutResponse->assertSessionHas('error');
    }

    /** Test 19b: Member with 30 days membership can select PT package with 30 days min membership */
    public function test_member_with_30_days_membership_can_select_pt_package_with_30_days_requirement(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 30);

        $package = PtPackage::factory()->create([
            'name' => 'PT 16 Sesi 30 Days Eligible',
            'pt_session_count' => 16,
            'price' => 3000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $selectTrainerResponse = $this->actingAs($member)->get(route('member.pt-packages.select-trainer', [
            'package_id' => $package->id,
            'sessions' => 16,
        ]));

        $selectTrainerResponse->assertOk();
        $selectTrainerResponse->assertSee('PILIH PERSONAL TRAINER ANDA');
    }

    /** Test 20: Option A allows selecting repeat PT package when membership covers stacked duration */
    public function test_member_can_select_repeat_pt_package_when_membership_covers_stacked_duration(): void
    {
        $member = $this->createMember();
        // Member has 90 days membership remaining (covers 25 + 30 = 55 days)
        $this->createActiveMembership($member, 90);

        $package = PtPackage::factory()->create([
            'name' => 'PT 16 Sesi Stacking OK',
            'pt_session_count' => 16,
            'price' => 3000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $today = now('Asia/Jakarta')->startOfDay();

        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'total_sessions' => 16,
            'remaining_sessions' => 2,
            'start_date' => $today->copy()->subDays(5)->toDateString(),
            'end_date' => $today->copy()->addDays(25)->toDateString(),
            'status' => 'active',
        ]);

        $indexResponse = $this->actingAs($member)->get(route('member.pt-packages.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Beli Sesi Tambahan');

        $selectTrainerResponse = $this->actingAs($member)->get(route('member.pt-packages.select-trainer', [
            'package_id' => $package->id,
            'sessions' => 16,
        ]));

        $selectTrainerResponse->assertOk();
        $selectTrainerResponse->assertSee('Pilih Personal Trainer');
    }

    /** Test 21: Option A allows fresh start when previous PT quota has zero sessions remaining */
    public function test_member_can_start_fresh_pt_package_when_previous_quota_has_zero_sessions(): void
    {
        $member = $this->createMember();
        // Member has 45 days membership remaining (covers 30 days fresh start)
        $this->createActiveMembership($member, 45);

        $package = PtPackage::factory()->create([
            'name' => 'PT Fresh Start',
            'pt_session_count' => 10,
            'price' => 1500000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $today = now('Asia/Jakarta')->startOfDay();

        // Old quota finished early (0 sessions remaining)
        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'total_sessions' => 10,
            'used_sessions' => 10,
            'remaining_sessions' => 0,
            'start_date' => $today->copy()->subDays(10)->toDateString(),
            'end_date' => $today->copy()->addDays(20)->toDateString(),
            'status' => 'active',
        ]);

        $selectTrainerResponse = $this->actingAs($member)->get(route('member.pt-packages.select-trainer', [
            'package_id' => $package->id,
            'sessions' => 10,
        ]));

        $selectTrainerResponse->assertOk();
        $selectTrainerResponse->assertSee('Pilih Personal Trainer');
    }

    /** Test 26: Member with existing trainer sees Smart Renewal banner & quick checkout button on packages page */
    public function test_member_with_existing_trainer_sees_smart_renewal_on_packages_page(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 90);

        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Hendra Gunawan',
            'email' => 'hendra@gym.test',
        ]);
        TrainerProfile::factory()->create([
            'user_id' => $trainer->id,
            'specialization' => 'Powerlifting & Athletic Conditioning',
        ]);

        $package = PtPackage::factory()->create([
            'name' => 'PT 12 Sesi Pro',
            'pt_session_count' => 12,
            'price' => 2000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'trainer_id' => $trainer->id,
            'total_sessions' => 12,
            'remaining_sessions' => 2,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(30),
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.index'));

        $response->assertOk();
        $response->assertSee('Pelatih Anda');
        $response->assertSee('Coach Hendra Gunawan');
        $response->assertSee('Lanjut dengan Coach Hendra Gunawan');
        $response->assertSee('atau pilih pelatih lain');
    }

    /** Test 27: Member with existing trainer sees highlighted card on select trainer page */
    public function test_member_with_existing_trainer_sees_highlighted_card_on_select_trainer_page(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 90);

        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Hendra Gunawan',
            'email' => 'hendra2@gym.test',
        ]);
        TrainerProfile::factory()->create([
            'user_id' => $trainer->id,
            'specialization' => 'Powerlifting & Athletic Conditioning',
        ]);

        $package = PtPackage::factory()->create([
            'name' => 'PT 12 Sesi Pro',
            'pt_session_count' => 12,
            'price' => 2000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'trainer_id' => $trainer->id,
            'total_sessions' => 12,
            'remaining_sessions' => 2,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addDays(30),
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('member.pt-packages.select-trainer', [
            'package_id' => $package->id,
            'sessions' => 12,
        ]));

        $response->assertOk();
        $response->assertSee('Pelatih Anda Saat Ini');
        $response->assertSee('Coach Hendra Gunawan');
        $response->assertSee('Lanjut bersama Coach Hendra Gunawan');
    }

    /** Test 28: Checkout automatically defaults to current trainer if trainer_id parameter is omitted */
    public function test_checkout_automatically_defaults_to_current_trainer_if_no_trainer_id_is_passed(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 90);

        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Hendra Gunawan',
            'email' => 'hendra3@gym.test',
        ]);
        TrainerProfile::factory()->create([
            'user_id' => $trainer->id,
            'specialization' => 'Powerlifting & Athletic Conditioning',
        ]);

        $package = PtPackage::factory()->create([
            'name' => 'PT 12 Sesi Pro',
            'pt_session_count' => 12,
            'price' => 2000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'trainer_id' => $trainer->id,
            'total_sessions' => 12,
            'remaining_sessions' => 1,
            'status' => 'active',
        ]);

        // Do not pass trainer_id in query
        $response = $this->actingAs($member)->get(route('member.pt-packages.checkout', [
            'package_id' => $package->id,
        ]));

        $response->assertOk();
        $response->assertSee('Coach Hendra Gunawan');
    }

    /** Test 29: Member with more than 2 remaining sessions is blocked from purchasing new PT package (Solusi A) */
    public function test_member_with_more_than_two_remaining_sessions_is_blocked_from_purchasing_new_pt_package(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 90);

        $package = PtPackage::factory()->create([
            'name' => 'PT 12 Sesi Pro',
            'pt_session_count' => 12,
            'price' => 2000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $bank = Bank::factory()->create(['status' => 'active']);

        // Kuota aktif masih tersisa 5 sesi (> 2)
        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'total_sessions' => 12,
            'remaining_sessions' => 5,
            'status' => 'active',
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(25)->toDateString(),
        ]);

        // 1. Pada halaman katalog paket: paket dinonaktifkan
        $indexResponse = $this->actingAs($member)->get(route('member.pt-packages.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Sisa Kuota Sesi Latihan Anda Masih Tersedia (5 Sesi)');
        $indexResponse->assertSee('Sisa Kuota Masih 5 Sesi');

        // 2. Pada halaman pilih trainer: diredirect kembali dengan error
        $selectTrainerResponse = $this->actingAs($member)->get(route('member.pt-packages.select-trainer', [
            'package_id' => $package->id,
        ]));
        $selectTrainerResponse->assertRedirect(route('member.pt-packages.index'));
        $selectTrainerResponse->assertSessionHas('error');

        // 3. Pada halaman checkout: diredirect kembali dengan error
        $checkoutResponse = $this->actingAs($member)->get(route('member.pt-packages.checkout', [
            'package_id' => $package->id,
        ]));
        $checkoutResponse->assertRedirect(route('member.pt-packages.index'));
        $checkoutResponse->assertSessionHas('error');

        // 4. Pada API / buy request: ditolak dengan status 422
        $buyResponse = $this->actingAs($member)->postJson(route('member.pt-packages.buy'), [
            'pt_package_id' => $package->id,
            'bank_id' => $bank->id,
        ]);
        $buyResponse->assertStatus(422);
        $this->assertStringContainsString('maksimal 2 sesi', $buyResponse->json('message'));
    }

    /** Test 30: Member with exactly 2 or fewer remaining sessions is allowed to purchase (Solusi A) */
    public function test_member_with_two_or_less_remaining_sessions_is_allowed_to_purchase_new_pt_package(): void
    {
        $member = $this->createMember();
        $this->createActiveMembership($member, 90);

        $package = PtPackage::factory()->create([
            'name' => 'PT 12 Sesi Pro',
            'pt_session_count' => 12,
            'price' => 2000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $bank = Bank::factory()->create(['status' => 'active']);

        // Kuota aktif tersisa 2 sesi (<= 2)
        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'total_sessions' => 12,
            'remaining_sessions' => 2,
            'status' => 'active',
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->addDays(20)->toDateString(),
        ]);

        // 1. Pada halaman katalog paket: paket eligible
        $indexResponse = $this->actingAs($member)->get(route('member.pt-packages.index'));
        $indexResponse->assertOk();
        $indexResponse->assertDontSee('Sisa Kuota Masih 2 Sesi');

        // 2. Pada halaman pilih trainer: berhasil dibuka
        $selectTrainerResponse = $this->actingAs($member)->get(route('member.pt-packages.select-trainer', [
            'package_id' => $package->id,
        ]));
        $selectTrainerResponse->assertOk();

        // 3. Pada API buy request: berhasil diproses (status 201)
        $buyResponse = $this->actingAs($member)->postJson(route('member.pt-packages.buy'), [
            'pt_package_id' => $package->id,
            'bank_id' => $bank->id,
        ]);
        $buyResponse->assertStatus(201);
        $this->assertTrue($buyResponse->json('success'));
    }
}
