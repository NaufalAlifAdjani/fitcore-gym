<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Filament\Pages\Settings;
use App\Filament\Tables\Contracts\HasTable;
use App\Models\Bank;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\User;
use App\Services\PaymentVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PtIncomeCommissionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $trainer;

    private User $member;

    private PtPackage $ptPackage;

    private Bank $bank;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Budi',
            'status' => 'active',
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
            'name' => 'Klien John',
            'status' => 'active',
        ]);

        $this->bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '8271 0812 3456 7890',
            'account_holder' => 'PT FITCORE INDONESIA',
            'status' => 'active',
        ]);

        $this->ptPackage = PtPackage::create([
            'name' => 'Paket Platinum 12 Sesi',
            'pt_session_count' => 12,
            'price' => 3000000,
            'min_membership_days' => 0,
            'validity_days' => 60,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_access_settings_page_and_see_commission_table(): void
    {
        $this->actingAs($this->admin);

        Payment::create([
            'invoice_id' => 'INV-PT-TEST01',
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'pt_package_id' => $this->ptPackage->id,
            'bank_id' => $this->bank->id,
            'payment_type' => 'pt_package',
            'package_type' => 'pt_session',
            'amount' => 3000000,
            'trainer_revenue' => 2100000,
            'gym_revenue' => 900000,
            'status' => 'verified',
            'payout_status' => 'pending',
        ]);

        $response = $this->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertDontSee('Halaman Pengaturan akan tersedia di sini.');
        $response->assertSee('Manajemen Komisi PT (Skema 70/30)');
        $response->assertSee('Nama Trainer');
        $response->assertSee('Komisi PT (70%)');
        $response->assertSee('Pendapatan Gym (30%)');
        $response->assertSee('Coach Budi');
        $response->assertSee('2.100.000');
        $response->assertSee('900.000');
        $response->assertSee('Cairkan Komisi');
    }

    public function test_payment_approval_automatically_calculates_70_30_commission(): void
    {
        $payment = Payment::create([
            'invoice_id' => 'INV-PT-VERIFY01',
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'pt_package_id' => $this->ptPackage->id,
            'bank_id' => $this->bank->id,
            'payment_type' => 'pt_package',
            'package_type' => 'pt_session',
            'amount' => 2000000,
            'status' => 'pending',
        ]);

        $service = app(PaymentVerificationService::class);
        $approvedPayment = $service->approvePayment($payment, $this->admin->id);

        $this->assertEquals('verified', $approvedPayment->status);
        $this->assertEquals(1400000, (float) $approvedPayment->trainer_revenue); // 70% dari 2jt
        $this->assertEquals(600000, (float) $approvedPayment->gym_revenue); // 30% dari 2jt
        $this->assertEquals('pending', $approvedPayment->payout_status);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'verified',
            'trainer_revenue' => 1400000.00,
            'gym_revenue' => 600000.00,
            'payout_status' => 'pending',
        ]);
    }

    public function test_admin_can_cairkan_komisi_mark_as_paid_via_livewire(): void
    {
        $this->actingAs($this->admin);

        $payment = Payment::create([
            'invoice_id' => 'INV-PT-PAYOUT01',
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'pt_package_id' => $this->ptPackage->id,
            'bank_id' => $this->bank->id,
            'payment_type' => 'pt_package',
            'package_type' => 'pt_session',
            'amount' => 1000000,
            'trainer_revenue' => 700000,
            'gym_revenue' => 300000,
            'status' => 'verified',
            'payout_status' => 'pending',
        ]);

        Livewire::test(Settings::class)
            ->call('cairkanKomisi', $payment->id)
            ->assertDispatched('toast');

        $payment->refresh();
        $this->assertEquals('paid', $payment->payout_status);
        $this->assertNotNull($payment->payout_date);
    }

    public function test_admin_can_cairkan_komisi_mark_as_paid_via_post_route(): void
    {
        $this->actingAs($this->admin);

        $payment = Payment::create([
            'invoice_id' => 'INV-PT-POST01',
            'member_id' => $this->member->id,
            'trainer_id' => $this->trainer->id,
            'pt_package_id' => $this->ptPackage->id,
            'bank_id' => $this->bank->id,
            'payment_type' => 'pt_package',
            'package_type' => 'pt_session',
            'amount' => 1000000,
            'trainer_revenue' => 700000,
            'gym_revenue' => 300000,
            'status' => 'verified',
            'payout_status' => 'pending',
        ]);

        $response = $this->post(route('admin.settings.payout', $payment->id));

        $response->assertRedirect();
        $payment->refresh();
        $this->assertEquals('paid', $payment->payout_status);
        $this->assertNotNull($payment->payout_date);
    }

    public function test_settings_page_implements_has_table_interface(): void
    {
        $settings = new Settings;

        $this->assertInstanceOf(HasTable::class, $settings);
        $this->assertEquals('settings', Settings::getSlug());
        $this->assertEquals('Pengaturan', Settings::getNavigationLabel());
    }
}
