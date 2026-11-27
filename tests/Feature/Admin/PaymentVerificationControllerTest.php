<?php

namespace Tests\Feature\Admin;

use App\Models\Bank;
use App\Models\MemberPtQuota;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\User;
use App\Models\VerificationLog;
use App\Notifications\ChoosePtScheduleNotification;
use App\Notifications\MembershipPaymentVerifiedNotification;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PaymentVerificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_render_the_blade_payment_verification_page(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $member = User::factory()->create(['role' => 'member', 'name' => 'Ayu Member']);
        $payment = $this->createPayment($member, ['status' => 'pending']);
        $verifiedPayment = $this->createPayment($member, ['status' => 'verified']);
        $rejectedPayment = $this->createPayment($member, ['status' => 'rejected']);

        $this->get(route('admin.payments.index'))
            ->assertOk()
            ->assertSee('Verifikasi Pembayaran')
            ->assertSee('Invoice ID')
            ->assertSee('Ayu Member')
            ->assertSee($payment->invoice_id)
            ->assertSee($verifiedPayment->invoice_id)
            ->assertSee($rejectedPayment->invoice_id)
            ->assertSee('PENDING')
            ->assertSee('TERVERIFIKASI')
            ->assertSee('DITOLAK')
            ->assertSee('bg-yellow-100 text-yellow-800', false)
            ->assertSee('bg-green-100 text-green-800', false)
            ->assertSee('bg-red-100 text-red-800', false)
            ->assertSee('Detail / Verifikasi')
            ->assertSee('paymentVerification', false);
    }

    public function test_payment_list_preserves_submitted_filter_values(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(route('admin.payments.index', [
            'search' => 'INV-2026-001',
            'status' => 'pending',
        ]))
            ->assertOk()
            ->assertSee('name="search"', false)
            ->assertSee('value="INV-2026-001"', false)
            ->assertSee('name="status"', false)
            ->assertSee('value="pending"', false);
    }

    public function test_admin_can_list_payments_using_search_status_and_date_filters(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $member = User::factory()->create(['role' => 'member', 'name' => 'Ayu Member']);
        $olderPayment = $this->createPayment($member, [
            'invoice_id' => 'INV-FILTER-001',
            'status' => 'pending',
            'transfer_date' => now()->subDays(3),
        ]);
        $newerPayment = $this->createPayment($member, [
            'invoice_id' => 'INV-FILTER-002',
            'status' => 'verified',
            'transfer_date' => now()->subDay(),
        ]);

        $this->getJson(route('admin.payments.index', [
            'search' => 'Ayu',
            'status' => 'pending',
            'start_date' => now()->subDays(4)->toDateString(),
            'end_date' => now()->subDays(2)->toDateString(),
        ]))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $olderPayment->id)
            ->assertJsonMissing(['invoice_id' => $newerPayment->invoice_id]);
    }

    public function test_admin_can_view_payment_details_with_member_package_and_log_data(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $member = User::factory()->create(['role' => 'member']);
        $payment = $this->createPayment($member);
        VerificationLog::create([
            'payment_id' => $payment->id,
            'admin_id' => auth()->id(),
            'action' => 'reject',
            'notes' => 'Bukti kurang jelas.',
        ]);

        $this->getJson(route('admin.payments.show', $payment))
            ->assertOk()
            ->assertJsonPath('data.invoice_id', $payment->invoice_id)
            ->assertJsonPath('data.member.id', $member->id)
            ->assertJsonPath('data.package.name', 'Monthly Membership')
            ->assertJsonPath('data.verification_logs.0.notes', 'Bukti kurang jelas.');
    }

    public function test_approving_membership_activates_entitlement_and_writes_audit_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);
        $payment = $this->createPayment($member);
        $this->actingAs($admin);

        $this->postJson(route('admin.payments.approve', $payment))
            ->assertOk()
            ->assertJsonPath('success', true);

        $membership = Membership::query()->firstOrFail();
        $this->assertSame($member->id, $membership->member_id);
        $this->assertSame($payment->id, $membership->payment_id);
        $this->assertSame(now()->toDateString(), $membership->start_date->toDateString());
        $this->assertSame(now()->addDays(29)->toDateString(), $membership->end_date->toDateString());
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'verified',
            'verified_by' => $admin->id,
        ]);
        $this->assertDatabaseHas('verification_logs', [
            'payment_id' => $payment->id,
            'admin_id' => $admin->id,
            'action' => 'approve',
        ]);
    }

    public function test_approving_pt_payment_creates_session_entitlement_and_database_notification(): void
    {
        Notification::fake();
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);
        $ptPackage = PtPackage::create([
            'name' => 'PT 8 Sesi',
            'pt_session_count' => 8,
            'price' => 800000,
            'validity_days' => 56,
            'status' => 'active',
        ]);
        $payment = $this->createPayment($member, [
            'package_type' => 'pt_session',
            'package_id' => $ptPackage->id,
        ]);
        $this->actingAs($admin);

        $this->postJson(route('admin.payments.approve', $payment))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('member_pt_quotas', [
            'member_id' => $member->id,
            'pt_package_id' => $ptPackage->id,
            'source' => 'purchase',
            'total_sessions' => 8,
            'remaining_sessions' => 8,
        ]);
        Notification::assertSentTo($member, ChoosePtScheduleNotification::class);
    }

    public function test_rejecting_payment_requires_reason_and_records_the_reason_and_log(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);
        $payment = $this->createPayment($member);
        $this->actingAs($admin);

        $this->postJson(route('admin.payments.reject', $payment), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('reason');

        $this->postJson(route('admin.payments.reject', $payment), [
            'reason' => '   Bukti transfer tidak jelas.   ',
        ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'rejected',
            'rejection_reason' => 'Bukti transfer tidak jelas.',
            'verified_by' => $admin->id,
        ]);
        $this->assertDatabaseHas('verification_logs', [
            'payment_id' => $payment->id,
            'action' => 'reject',
            'notes' => 'Bukti transfer tidak jelas.',
        ]);
    }

    public function test_payment_cannot_be_verified_more_than_once(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $member = User::factory()->create(['role' => 'member']);
        $payment = $this->createPayment($member);

        $this->postJson(route('admin.payments.approve', $payment))->assertOk();
        $this->postJson(route('admin.payments.approve', $payment))
            ->assertConflict()
            ->assertJsonPath('success', false);

        $this->assertDatabaseCount('memberships', 1);
        $this->assertDatabaseCount('verification_logs', 1);
    }

    public function test_guests_and_non_admin_users_cannot_access_payment_verification(): void
    {
        $payment = $this->createPayment(User::factory()->create(['role' => 'member']));

        $this->getJson(route('admin.payments.index'))->assertUnauthorized();

        $this->actingAs(User::factory()->create(['role' => 'member']))
            ->postJson(route('admin.payments.approve', $payment))
            ->assertForbidden();
    }

    public function test_approving_membership_does_not_create_member_pt_quota_record(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);
        $package = MembershipPackage::create([
            'name' => 'VIP Pure Membership',
            'badge' => 'VIP',
            'tier' => 'VIP',
            'description' => 'Paket membership murni akses gym tanpa bonus PT',
            'price' => 1200000,
            'duration_days' => 30,
            'duration_in_days' => 30,
            'facilities' => ['All Access'],
            'pt_session_count' => 0,
            'is_active' => true,
        ]);
        $payment = $this->createPayment($member, [
            'package_id' => $package->id,
            'amount' => 1200000,
        ]);
        $this->actingAs($admin);

        $this->postJson(route('admin.payments.approve', $payment))
            ->assertOk()
            ->assertJsonPath('success', true);

        $membership = Membership::query()->where('payment_id', $payment->id)->firstOrFail();
        $this->assertSame('active', $membership->status);

        $this->assertDatabaseMissing('member_pt_quotas', [
            'member_id' => $member->id,
        ]);
    }

    public function test_approving_upgraded_membership_activates_higher_tier_immediately_with_carry_over_days(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);

        $silverPackage = MembershipPackage::create([
            'name' => 'Silver Monthly Pass Test',
            'tier' => 'Basic',
            'price' => 450000,
            'duration_days' => 30,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);

        $goldPackage = MembershipPackage::create([
            'name' => 'Gold 6-Months Pass Test',
            'tier' => 'Standard',
            'price' => 2400000,
            'duration_days' => 180,
            'duration_in_days' => 180,
            'is_active' => true,
        ]);

        $today = CarbonImmutable::today();

        // 1. Existing active Silver membership (ends in 20 days)
        $silverMembership = Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $silverPackage->id,
            'start_date' => $today->subDays(10)->toDateString(),
            'end_date' => $today->addDays(19)->toDateString(),
            'status' => 'active',
        ]);

        // 2. Member pays for Gold 6-Months Pass
        $payment = $this->createPayment($member, [
            'package_id' => $goldPackage->id,
            'amount' => 2400000,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.payments.approve', $payment))
            ->assertOk();

        // Silver membership should be marked as expired/superseded
        $this->assertSame('expired', $silverMembership->fresh()->status);

        // Gold membership should start TODAY and include the 20 days carried over (180 + 20 = 200 days)
        $goldMembership = Membership::where('payment_id', $payment->id)->firstOrFail();
        $this->assertSame('active', $goldMembership->status);
        $this->assertSame($goldPackage->id, $goldMembership->membership_package_id);
        $this->assertSame($today->toDateString(), $goldMembership->start_date->toDateString());
        $this->assertSame($today->addDays(199)->toDateString(), $goldMembership->end_date->toDateString());
    }

    public function test_admin_approving_repeat_pt_purchase_stacks_validity_from_existing_active_quota(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);

        $ptPackage = PtPackage::create([
            'name' => 'PT 10 Sesi Test',
            'pt_session_count' => 10,
            'price' => 1000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $today = CarbonImmutable::today();

        // Existing active PT quota expiring in 20 days with 5 sessions remaining
        $existingQuota = MemberPtQuota::create([
            'member_id' => $member->id,
            'pt_package_id' => $ptPackage->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 5,
            'remaining_sessions' => 5,
            'start_date' => $today->subDays(10)->toDateString(),
            'end_date' => $today->addDays(20)->toDateString(),
            'status' => 'active',
        ]);

        $payment = $this->createPayment($member, [
            'package_type' => 'pt_session',
            'payment_type' => 'pt_package',
            'package_id' => $ptPackage->id,
            'pt_package_id' => $ptPackage->id,
            'amount' => 1000000,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.payments.approve', $payment))
            ->assertOk();

        // New quota should stack validity from existing quota's end date (+30 days)
        $newQuota = MemberPtQuota::where('member_id', $member->id)
            ->where('id', '!=', $existingQuota->id)
            ->firstOrFail();

        $this->assertSame('active', $newQuota->status);
        $this->assertSame(10, $newQuota->total_sessions);
        $this->assertSame(10, $newQuota->remaining_sessions);
        $this->assertSame($today->toDateString(), $newQuota->start_date->toDateString());
        $this->assertSame($today->addDays(50)->toDateString(), $newQuota->end_date->toDateString());
    }

    public function test_admin_approving_pt_purchase_marks_zero_session_quotas_as_completed_and_starts_fresh(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);

        $ptPackage = PtPackage::create([
            'name' => 'PT 10 Sesi Fresh',
            'pt_session_count' => 10,
            'price' => 1000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $today = CarbonImmutable::today();

        // Old quota which finished early (remaining_sessions = 0) but end_date is still in future
        $oldQuota = MemberPtQuota::create([
            'member_id' => $member->id,
            'pt_package_id' => $ptPackage->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 10,
            'remaining_sessions' => 0,
            'start_date' => $today->subDays(10)->toDateString(),
            'end_date' => $today->addDays(20)->toDateString(),
            'status' => 'active',
        ]);

        $payment = $this->createPayment($member, [
            'package_type' => 'pt_session',
            'payment_type' => 'pt_package',
            'package_id' => $ptPackage->id,
            'pt_package_id' => $ptPackage->id,
            'amount' => 1000000,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.payments.approve', $payment))
            ->assertOk();

        // Old quota should be marked as completed
        $this->assertSame('completed', $oldQuota->fresh()->status);

        // New quota starts fresh today with 30 days validity (today + 29 days)
        $newQuota = MemberPtQuota::where('member_id', $member->id)
            ->where('id', '!=', $oldQuota->id)
            ->firstOrFail();

        $this->assertSame('active', $newQuota->status);
        $this->assertSame($today->toDateString(), $newQuota->start_date->toDateString());
        $this->assertSame($today->addDays(29)->toDateString(), $newQuota->end_date->toDateString());
    }

    public function test_admin_approving_membership_payment_sends_membership_payment_verified_notification(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);
        $package = MembershipPackage::create([
            'name' => 'Silver Monthly Pass Test',
            'tier' => 'Basic',
            'price' => 450000,
            'duration_days' => 30,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);
        $payment = $this->createPayment($member, [
            'package_id' => $package->id,
            'amount' => 450000,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.payments.approve', $payment))
            ->assertOk();

        Notification::assertSentTo(
            $member,
            MembershipPaymentVerifiedNotification::class,
            function (MembershipPaymentVerifiedNotification $notification) use ($package) {
                return $notification->package->id === $package->id;
            }
        );
    }

    public function test_admin_approving_pt_purchase_assigns_trainer_id_to_member_pt_quota(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['role' => 'member']);
        $trainer = User::factory()->create(['role' => 'trainer', 'name' => 'Coach Sarah Test']);

        $ptPackage = PtPackage::create([
            'name' => 'PT 12 Sesi Test',
            'pt_session_count' => 12,
            'price' => 2000000,
            'validity_days' => 30,
            'status' => 'active',
        ]);

        $payment = $this->createPayment($member, [
            'package_type' => 'pt_session',
            'payment_type' => 'pt_package',
            'package_id' => $ptPackage->id,
            'pt_package_id' => $ptPackage->id,
            'trainer_id' => $trainer->id,
            'amount' => 2000000,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.payments.approve', $payment))
            ->assertOk();

        $quota = MemberPtQuota::where('member_id', $member->id)->latest()->firstOrFail();
        $this->assertSame($trainer->id, $quota->trainer_id);
    }

    private function createPayment(User $member, array $overrides = []): Payment
    {
        $package = MembershipPackage::query()->first() ?? MembershipPackage::create([
            'name' => 'Monthly Membership',
            'badge' => 'SILVER',
            'tier' => 'Basic',
            'description' => 'Monthly gym access',
            'price' => 450000,
            'promo_price' => null,
            'duration_value' => 1,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 30,
            'facilities' => ['Gym access'],
            'pt_sessions' => 0,
            'is_active' => true,
        ]);

        return Payment::create(array_merge([
            'invoice_id' => 'INV-'.fake()->unique()->numerify('########'),
            'member_id' => $member->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 450000,
            'bank_id' => Bank::factory()->create()->id,
            'bank_sender' => 'BCA',
            'bank_destination' => 'Mandiri',
            'proof_image_url' => 'https://example.test/receipt.jpg',
            'transfer_date' => now(),
            'status' => 'pending',
        ], $overrides));
    }
}
