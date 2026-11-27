<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Bank;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\Setting;
use App\Models\User;
use App\Services\PaymentVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtIncomeCommissionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $member;

    private Bank $bank;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
            'status' => 'active',
        ]);

        $this->bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '8271 0812 3456 7890',
            'account_holder' => 'PT FITCORE INDONESIA',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_access_settings_page(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('Pengaturan Sistem dan Operasional');
        $response->assertSee('Aturan Sesi');
        $response->assertSee('Profil');
        $response->assertSee('Rekening Bank');
        $response->assertSee('BCA');
    }

    public function test_admin_can_update_operational_settings(): void
    {
        $this->actingAs($this->admin);

        $response = $this->put(route('admin.settings.operational.update'), [
            'change_deadline_hours' => 48,
            'min_booking_notice_hours' => 36,
            'session_duration_minutes' => 90,
            'auto_cancel_pending_hours_before' => 24,
            'qr_token_ttl' => 60,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('48', Setting::get('change_deadline_hours'));
        $this->assertEquals('36', Setting::get('min_booking_notice_hours'));
        $this->assertEquals('90', Setting::get('session_duration_minutes'));
        $this->assertEquals('24', Setting::get('auto_cancel_pending_hours_before'));
        $this->assertEquals('60', Setting::get('qr_token_ttl'));
    }

    public function test_admin_can_update_gym_profile_settings(): void
    {
        $this->actingAs($this->admin);

        $response = $this->put(route('admin.settings.profile.update'), [
            'gym_name' => 'FitCore Elite Center',
            'gym_phone' => '081987654321',
            'gym_email' => 'cs@fitcore-elite.com',
            'gym_address' => 'Gedung FitCore Lantai 3, SCBD Jakarta',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('FitCore Elite Center', Setting::get('gym_name'));
        $this->assertEquals('081987654321', Setting::get('gym_phone'));
        $this->assertEquals('cs@fitcore-elite.com', Setting::get('gym_email'));
        $this->assertEquals('Gedung FitCore Lantai 3, SCBD Jakarta', Setting::get('gym_address'));
    }

    public function test_admin_can_create_update_and_delete_bank_account(): void
    {
        $this->actingAs($this->admin);

        // 1. Create bank
        $responseCreate = $this->post(route('admin.settings.banks.store'), [
            'name' => 'Mandiri',
            'account_number' => '1370 0012 3456',
            'account_holder' => 'PT FITCORE INDONESIA',
            'status' => 'active',
        ]);
        $responseCreate->assertRedirect();
        $this->assertDatabaseHas('banks', ['name' => 'Mandiri', 'account_number' => '1370 0012 3456']);

        $mandiri = Bank::where('name', 'Mandiri')->firstOrFail();

        // 2. Update bank
        $responseUpdate = $this->put(route('admin.settings.banks.update', $mandiri), [
            'name' => 'Bank Mandiri Syariah',
            'account_number' => '1370 0012 9999',
            'account_holder' => 'PT FITCORE INDONESIA',
            'status' => 'inactive',
        ]);
        $responseUpdate->assertRedirect();
        $this->assertDatabaseHas('banks', ['name' => 'Bank Mandiri Syariah', 'status' => 'inactive']);

        // 3. Delete bank
        $responseDelete = $this->delete(route('admin.settings.banks.destroy', $mandiri));
        $responseDelete->assertRedirect();
        $this->assertDatabaseMissing('banks', ['id' => $mandiri->id]);
    }

    public function test_operational_settings_validation_rules(): void
    {
        $this->actingAs($this->admin);

        $response = $this->from(route('admin.settings.index'))
            ->put(route('admin.settings.operational.update'), [
                'change_deadline_hours' => 0, // min is 1
                'min_booking_notice_hours' => -1,
                'session_duration_minutes' => 5, // min is 15
                'auto_cancel_pending_hours_before' => 0,
                'qr_token_ttl' => 5, // min is 10
            ]);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHasErrors([
            'change_deadline_hours',
            'min_booking_notice_hours',
            'session_duration_minutes',
            'auto_cancel_pending_hours_before',
            'qr_token_ttl',
        ]);
    }

    public function test_non_admin_cannot_access_or_update_settings(): void
    {
        $this->actingAs($this->member);

        $this->get(route('admin.settings.index'))->assertForbidden();
        $this->put(route('admin.settings.operational.update'), [])->assertForbidden();
        $this->put(route('admin.settings.profile.update'), [])->assertForbidden();
        $this->post(route('admin.settings.banks.store'), [])->assertForbidden();
    }

    public function test_payment_approval_automatically_calculates_70_30_commission(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Budi',
            'status' => 'active',
        ]);

        $ptPackage = PtPackage::create([
            'name' => 'Paket Platinum 12 Sesi',
            'pt_session_count' => 12,
            'price' => 2000000,
            'validity_days' => 60,
            'status' => 'active',
        ]);

        $payment = Payment::create([
            'invoice_id' => 'INV-PT-VERIFY01',
            'member_id' => $this->member->id,
            'trainer_id' => $trainer->id,
            'pt_package_id' => $ptPackage->id,
            'bank_id' => $this->bank->id,
            'payment_type' => 'pt_package',
            'package_type' => 'pt_session',
            'amount' => 2000000,
            'status' => 'pending',
        ]);

        $service = app(PaymentVerificationService::class);
        $approvedPayment = $service->approvePayment($payment, $this->admin->id);

        $this->assertEquals('verified', $approvedPayment->status);
        $this->assertEquals(1400000, (float) $approvedPayment->trainer_revenue);
        $this->assertEquals(600000, (float) $approvedPayment->gym_revenue);
    }
}
