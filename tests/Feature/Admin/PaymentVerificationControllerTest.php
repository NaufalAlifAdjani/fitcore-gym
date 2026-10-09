<?php

namespace Tests\Feature\Admin;

use App\Models\Bank;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\User;
use App\Models\VerificationLog;
use App\Notifications\ChoosePtScheduleNotification;
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
        $payment = $this->createPayment($member);

        $this->get(route('admin.payments.index'))
            ->assertOk()
            ->assertSee('Verifikasi Pembayaran')
            ->assertSee('Invoice ID')
            ->assertSee('Ayu Member')
            ->assertSee($payment->invoice_id)
            ->assertSee('Detail / Verifikasi')
            ->assertSee('paymentVerification', false);
    }

    public function test_payment_list_preserves_submitted_filter_values(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->get(route('admin.payments.index', [
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-09',
        ]))
            ->assertOk()
            ->assertSee('Dari Tanggal')
            ->assertSee('Sampai Tanggal')
            ->assertSee('name="start_date"', false)
            ->assertSee('value="2026-10-01"', false)
            ->assertSee('name="end_date"', false)
            ->assertSee('value="2026-10-09"', false);
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
            'min_membership_days' => 0,
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
