<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\MemberPtQuota;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileRedirectTest extends TestCase
{
    use RefreshDatabase;

    private const IPHONE_USER_AGENT = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';

    private const DESKTOP_USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    public function test_mobile_device_is_automatically_redirected_from_login_to_m_login(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/login');

        $response->assertRedirect('/m/login');
    }

    public function test_mobile_device_is_automatically_redirected_from_register_to_m_register(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/register');

        $response->assertRedirect('/m/register');
    }

    public function test_desktop_device_is_not_redirected_when_accessing_login(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => self::DESKTOP_USER_AGENT,
        ])->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('PORTAL'); // Mobile exclusive badge
    }

    public function test_logged_in_member_on_mobile_is_redirected_from_member_dashboard_to_m_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/member/dashboard');

        $response->assertRedirect('/m/dashboard');
    }

    public function test_logged_in_member_on_mobile_is_redirected_from_membership_packages_to_m_packages(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/membership/packages');

        $response->assertRedirect('/m/packages');
    }

    public function test_logged_in_member_on_mobile_is_redirected_from_membership_checkout_to_m_checkout(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $package = MembershipPackage::create([
            'name' => 'Paket Basic',
            'type' => 'membership',
            'duration_days' => 30,
            'price' => 150000,
            'status' => 'active',
            'is_active' => true,
        ]);

        $response = $this->actingAs($member)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get("/membership/checkout/{$package->id}");

        $response->assertRedirect("/m/packages/{$package->id}/checkout");
    }

    public function test_logged_in_member_on_mobile_is_redirected_from_payment_status_to_m_payment_status(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $package = MembershipPackage::create([
            'name' => 'Paket Basic',
            'type' => 'membership',
            'duration_days' => 30,
            'price' => 150000,
            'status' => 'active',
            'is_active' => true,
        ]);

        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Indonesia',
            'status' => 'active',
        ]);

        $payment = Payment::create([
            'invoice_id' => 'INV-MOB123',
            'member_id' => $member->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 150200,
            'bank_id' => $bank->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($member)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get("/membership/payment-status/{$payment->id}");

        $response->assertRedirect("/m/payment-status/{$payment->id}");
    }

    public function test_logged_in_member_on_mobile_is_redirected_from_pt_sessions_show_to_m_pt_sessions_show(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $trainer = User::factory()->create([
            'role' => 'trainer',
        ]);

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
        ]);

        $session = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($member)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get("/pt-sessions/{$session->id}");

        $response->assertRedirect("/m/pt-sessions/{$session->id}");
    }

    public function test_developer_can_force_mobile_redirect_via_query_parameter(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => self::DESKTOP_USER_AGENT,
        ])->get('/login?device=mobile');

        $response->assertRedirect('/m/login');
    }
}
