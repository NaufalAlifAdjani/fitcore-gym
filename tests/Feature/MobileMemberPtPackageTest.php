<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MobileMemberPtPackageTest extends TestCase
{
    use RefreshDatabase;

    private const IPHONE_USER_AGENT = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';

    private const DESKTOP_USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    protected function setUp(): void
    {
        parent::setUp();

        PtPackage::create([
            'name' => 'PT 16 Sesi',
            'pt_session_count' => 16,
            'price' => 3582000,
            'validity_days' => 60,
            'status' => 'active',
        ]);
    }

    public function test_member_can_view_mobile_pt_packages_catalog(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get('/m/pt-packages');

        $response->assertStatus(200);
        $response->assertSee('Pilih Paket');
        $response->assertSee('Personal Trainer');
        $response->assertSee('16');
        $response->assertSee('Sesi');
        $response->assertSee('Paket Gym');
        $response->assertSee('Sesi PT');
        $response->assertSee("currentNavTab: 'packages'", false);
    }

    public function test_mobile_device_redirects_member_pt_packages_to_m_pt_packages(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get('/member/pt-packages');

        $response->assertRedirect('/m/pt-packages');
    }

    public function test_desktop_device_redirects_m_pt_packages_to_member_pt_packages(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])
            ->get('/m/pt-packages');

        $response->assertRedirect('/member/pt-packages');
    }

    public function test_mobile_packages_catalog_has_tab_switcher_to_pt_packages(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get('/m/packages');

        $response->assertStatus(200);
        $response->assertSee(route('mobile.member.pt-packages.index'));
        $response->assertSee('Sesi PT');
    }

    public function test_mobile_pt_sessions_has_cta_to_pt_packages(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get('/m/pt-sessions');

        $response->assertStatus(200);
        $response->assertSee(route('mobile.member.pt-packages.index'));
    }

    public function test_mobile_dashboard_has_pt_package_purchase_card(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get('/m/dashboard');

        $response->assertStatus(200);
        $response->assertSee(route('mobile.member.pt-packages.index'));
        $response->assertSee('Paket Sesi PT');
    }

    public function test_mobile_member_can_access_select_trainer_page(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get('/m/pt-packages/select-trainer');

        $response->assertStatus(200);
        $response->assertSee('Pilih Personal Trainer');
        $response->assertSee('Coach Rama');
        $response->assertSee(route('mobile.member.pt-packages.index'));
    }

    public function test_mobile_member_can_access_checkout_page(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        $this->createActiveMembership($member, 90);

        $pkg = PtPackage::first();

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get('/m/pt-packages/checkout?package_id='.$pkg->id.'&trainer_id=1');

        $response->assertStatus(200);
        $response->assertSee('Rincian & Pembayaran');
        $response->assertSee('Total Pembayaran');
        $response->assertSee(route('mobile.member.pt-packages.process-checkout'));
    }

    public function test_mobile_member_can_process_checkout_and_redirect_to_upload_payment(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        $this->createActiveMembership($member, 90);

        $pkg = PtPackage::first();
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'PT FitCore',
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->post('/m/pt-packages/checkout', [
                'package_id' => $pkg->id,
                'trainer_id' => 1,
                'bank_id' => $bank->id,
                'unique_code' => 123,
                'total_amount' => 3582123,
            ]);

        $response->assertRedirect();
        $this->assertStringContainsString('/m/pt-packages/upload-payment', $response->headers->get('Location'));
    }

    private function createActiveMembership(User $member, int $days = 90): void
    {
        $pkg = MembershipPackage::firstOrCreate(
            ['name' => 'Monthly Test'],
            [
                'duration_days' => $days,
                'price' => 450000,
                'is_active' => true,
            ]
        );

        Membership::create([
            'member_id' => $member->id,
            'membership_package_id' => $pkg->id,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDays($days)->toDateString(),
            'status' => 'active',
        ]);
    }

    public function test_mobile_member_can_upload_payment_proof(): void
    {
        Storage::fake('public');

        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $pkg = PtPackage::first();
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'PT FitCore',
            'status' => 'active',
        ]);

        $payment = Payment::create([
            'invoice_id' => 'INV-TEST-001',
            'member_id' => $member->id,
            'package_id' => $pkg->id,
            'pt_package_id' => $pkg->id,
            'bank_id' => $bank->id,
            'amount' => 3582123,
            'status' => 'pending',
            'package_type' => 'pt_session',
            'payment_type' => 'pt_package',
        ]);

        $file = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->post('/m/pt-packages/upload-payment/'.$payment->id, [
                'proof_image' => $file,
                'sender_name' => 'John Doe',
                'sender_bank' => 'BCA',
            ]);

        $response->assertRedirect(route('mobile.member.packages.payment-status', $payment->id));
        $payment->refresh();
        $this->assertNotNull($payment->proof_image_path);

        $statusResponse = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get(route('mobile.member.packages.payment-status', $payment->id));
        $statusResponse->assertStatus(200);
        $statusResponse->assertSee('Lihat Riwayat Transaksi');
        $statusResponse->assertSee(route('mobile.member.riwayat-transaksi.index'));
        $statusResponse->assertDontSee('Kembali ke Beranda');
    }

    public function test_mobile_device_redirects_desktop_pt_package_subroutes(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $responseSelect = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get('/member/pt-packages/select-trainer');
        $responseSelect->assertRedirect('/m/pt-packages/select-trainer');

        $responseCheckout = $this->actingAs($member)
            ->withHeaders(['User-Agent' => self::IPHONE_USER_AGENT])
            ->get('/member/pt-packages/checkout');
        $responseCheckout->assertRedirect('/m/pt-packages/checkout');
    }
}
