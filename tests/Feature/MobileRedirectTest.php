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

    public function test_logged_in_member_on_mobile_is_redirected_from_profile_to_m_profile(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/profile');

        $response->assertRedirect('/m/profile');
    }

    public function test_developer_can_force_mobile_redirect_via_query_parameter(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => self::DESKTOP_USER_AGENT,
        ])->get('/login?device=mobile');

        $response->assertRedirect('/m/login');
    }

    public function test_android_phone_is_automatically_redirected_to_mobile(): void
    {
        $androidPhoneUserAgent = 'Mozilla/5.0 (Linux; Android 13; SM-S918B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.6099.43 Mobile Safari/537.36';

        $response = $this->withHeaders([
            'User-Agent' => $androidPhoneUserAgent,
        ])->get('/login');

        $response->assertRedirect('/m/login');
    }

    public function test_tablet_device_stays_on_desktop_view(): void
    {
        $ipadUserAgent = 'Mozilla/5.0 (iPad; CPU OS 16_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1';

        $response = $this->withHeaders([
            'User-Agent' => $ipadUserAgent,
        ])->get('/login');

        $response->assertStatus(200);
    }

    public function test_mobile_device_can_force_desktop_via_query_parameter(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => self::IPHONE_USER_AGENT,
        ])->get('/login?device=desktop');

        $response->assertStatus(200);
    }

    public function test_desktop_guest_visiting_m_root_is_redirected_to_desktop_root(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => self::DESKTOP_USER_AGENT,
        ])->get('/m');

        $response->assertRedirect('/');
    }

    public function test_desktop_guest_visiting_m_public_pages_is_redirected_to_desktop_equivalents(): void
    {
        $responsePackages = $this->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/packages-info');
        $responsePackages->assertRedirect('/packages');

        $responseTrainers = $this->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/trainers');
        $responseTrainers->assertRedirect('/trainers');

        $responseAbout = $this->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/about');
        $responseAbout->assertRedirect('/about');

        $responseLogin = $this->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/login');
        $responseLogin->assertRedirect('/login');

        $responseRegister = $this->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/register');
        $responseRegister->assertRedirect('/register');
    }

    public function test_desktop_logged_in_member_visiting_m_dashboard_is_redirected_to_member_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)->withHeaders([
            'User-Agent' => self::DESKTOP_USER_AGENT,
        ])->get('/m/dashboard');

        $response->assertRedirect('/member/dashboard');
    }

    public function test_desktop_logged_in_member_visiting_m_member_routes_is_redirected_to_desktop_equivalents(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $package = MembershipPackage::create([
            'name' => 'Paket Silver',
            'type' => 'membership',
            'duration_days' => 30,
            'price' => 200000,
            'status' => 'active',
            'is_active' => true,
        ]);

        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1122334455',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $payment = Payment::create([
            'invoice_id' => 'INV-TEST123',
            'member_id' => $member->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 200500,
            'bank_id' => $bank->id,
            'status' => 'pending',
        ]);

        // /m/card -> /member/card
        $resCard = $this->actingAs($member)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/card');
        $resCard->assertRedirect('/member/card');

        // /m/packages -> /membership/packages
        $resPackages = $this->actingAs($member)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/packages');
        $resPackages->assertRedirect('/membership/packages');

        // /m/packages/{id}/checkout -> /membership/checkout/{id}
        $resCheckout = $this->actingAs($member)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get("/m/packages/{$package->id}/checkout");
        $resCheckout->assertRedirect("/membership/checkout/{$package->id}");

        // /m/payment-status/{id} -> /membership/payment-status/{id}
        $resPayment = $this->actingAs($member)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get("/m/payment-status/{$payment->id}");
        $resPayment->assertRedirect("/membership/payment-status/{$payment->id}");

        // /m/pt-sessions -> /pt-sessions
        $resSessions = $this->actingAs($member)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/pt-sessions');
        $resSessions->assertRedirect('/pt-sessions');

        // /m/pt-sessions/booking -> /pt-sessions/create
        $resBooking = $this->actingAs($member)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/pt-sessions/booking');
        $resBooking->assertRedirect('/pt-sessions/create');

        // /m/profile -> /profile
        $resProfile = $this->actingAs($member)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/profile');
        $resProfile->assertRedirect('/profile');
    }

    public function test_desktop_logged_in_trainer_visiting_m_trainer_routes_is_redirected_to_desktop_equivalents(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'email_verified_at' => now(),
        ]);

        // /m/trainer/dashboard -> /trainer/dashboard
        $resDash = $this->actingAs($trainer)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/trainer/dashboard');
        $resDash->assertRedirect('/trainer/dashboard');

        // /m/trainer/sessions -> /trainer/jadwal-sesi
        $resSessions = $this->actingAs($trainer)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/trainer/sessions');
        $resSessions->assertRedirect('/trainer/jadwal-sesi');

        // /m/trainer/clients -> /trainer/klien-saya
        $resClients = $this->actingAs($trainer)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/trainer/clients');
        $resClients->assertRedirect('/trainer/klien-saya');

        // /m/trainer/profile -> /profile
        $resProfile = $this->actingAs($trainer)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/trainer/profile');
        $resProfile->assertRedirect('/profile');
    }

    public function test_desktop_device_with_device_mobile_override_can_stay_on_m_routes(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => self::DESKTOP_USER_AGENT,
        ])->get('/m?device=mobile');

        $response->assertStatus(200);
    }

    public function test_desktop_unknown_m_route_falls_back_appropriately(): void
    {
        // Guest fallback -> /
        $resGuest = $this->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/random-nonexistent-route');
        $resGuest->assertRedirect('/');

        // Member fallback -> /member/dashboard
        $member = User::factory()->create(['role' => 'member']);
        $resMember = $this->actingAs($member)->withHeaders(['User-Agent' => self::DESKTOP_USER_AGENT])->get('/m/random-nonexistent-route');
        $resMember->assertRedirect('/member/dashboard');
    }
}
