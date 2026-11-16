<?php

namespace Tests\Feature;

use App\Models\PtPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'min_membership_days' => 0,
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
}
