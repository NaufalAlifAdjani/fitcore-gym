<?php

namespace Tests\Feature;

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

    public function test_developer_can_force_mobile_redirect_via_query_parameter(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => self::DESKTOP_USER_AGENT,
        ])->get('/login?device=mobile');

        $response->assertRedirect('/m/login');
    }
}
