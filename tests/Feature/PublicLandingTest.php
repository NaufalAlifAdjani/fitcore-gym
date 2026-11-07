<?php

namespace Tests\Feature;

use App\Models\MembershipPackage;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLandingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed paket & trainer untuk pengujian tampilan publik
        MembershipPackage::create([
            'name' => 'Silver Athletic',
            'type' => 'Reguler',
            'duration_days' => 30,
            'price' => 350000,
            'is_active' => true,
            'status' => 'active',
            'facilities' => ['Gym Access', 'Locker'],
        ]);

        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Budi',
            'email' => 'budi@fitcore.test',
            'status' => 'active',
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Hypertrophy & Strength',
            'experience' => 5,
            'rating' => 4.95,
            'review_count' => 120,
            'status' => 'active',
        ]);
    }

    public function test_guest_can_access_desktop_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('FIT');
        $response->assertSee('CORE');
        $response->assertSee('TRANSFORMASI TOTAL');
        $response->assertSee('Silver Athletic');
        $response->assertSee('Coach Budi');
    }

    public function test_guest_can_access_desktop_packages_catalog(): void
    {
        $response = $this->get('/packages');

        $response->assertStatus(200);
        $response->assertSee('Katalog Paket Membership');
        $response->assertSee('Silver Athletic');
        $response->assertSee('350.000');
    }

    public function test_guest_can_access_desktop_trainers_list(): void
    {
        $response = $this->get('/trainers');

        $response->assertStatus(200);
        $response->assertSee('Coach Budi');
        $response->assertSee('Hypertrophy & Strength');
    }

    public function test_guest_can_access_desktop_about_page(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('Visi Kami');
        $response->assertSee('Misi Kami');
        $response->assertSee('SCBD');
    }

    public function test_guest_can_access_mobile_landing_page(): void
    {
        $response = $this->get('/m');

        $response->assertStatus(200);
        $response->assertSee('FIT');
        $response->assertSee('CORE');
        $response->assertSee('Silver Athletic');
    }

    public function test_guest_can_access_mobile_packages_catalog(): void
    {
        $response = $this->get('/m/packages-info');

        $response->assertStatus(200);
        $response->assertSee('Paket Keanggotaan');
        $response->assertSee('Silver Athletic');
    }

    public function test_guest_can_access_mobile_trainers_list(): void
    {
        $response = $this->get('/m/trainers');

        $response->assertStatus(200);
        $response->assertSee('Coach Budi');
        $response->assertSee('Hypertrophy & Strength');
    }

    public function test_guest_can_access_mobile_about_page(): void
    {
        $response = $this->get('/m/about');

        $response->assertStatus(200);
        $response->assertSee('Tentang FitCore');
        $response->assertSee('SCBD');
    }

    public function test_authenticated_member_visiting_root_redirects_to_member_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get('/');

        $response->assertRedirect(route('member.dashboard'));
    }

    public function test_authenticated_trainer_visiting_root_redirects_to_trainer_dashboard(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'status' => 'active',
        ]);

        $response = $this->actingAs($trainer)->get('/');

        $response->assertRedirect(route('trainer.dashboard'));
    }

    public function test_authenticated_member_visiting_mobile_root_redirects_to_mobile_member_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get('/m');

        $response->assertRedirect(route('mobile.member.dashboard'));
    }

    public function test_authenticated_trainer_visiting_mobile_root_redirects_to_mobile_trainer_dashboard(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'status' => 'active',
        ]);

        $response = $this->actingAs($trainer)->get('/m');

        $response->assertRedirect(route('mobile.trainer.dashboard'));
    }

    public function test_auto_redirect_mobile_redirects_guest_on_mobile_to_mobile_pages(): void
    {
        $mobileHeader = [
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
        ];

        $responseHome = $this->withHeaders($mobileHeader)->get('/');
        $responseHome->assertRedirect(route('mobile.landing.index'));

        $responsePackages = $this->withHeaders($mobileHeader)->get('/packages');
        $responsePackages->assertRedirect(route('mobile.landing.packages'));

        $responseTrainers = $this->withHeaders($mobileHeader)->get('/trainers');
        $responseTrainers->assertRedirect(route('mobile.landing.trainers'));

        $responseAbout = $this->withHeaders($mobileHeader)->get('/about');
        $responseAbout->assertRedirect(route('mobile.landing.about'));
    }

    public function test_mobile_guest_chips_active_state_matches_current_page(): void
    {
        // 1. Pada /m/trainers, chip Pelatih PT harus aktif (memuat kelas active chip Maxfit Red)
        $responseTrainers = $this->get('/m/trainers');
        $responseTrainers->assertStatus(200);
        $responseTrainers->assertSee('bg-[#ED1B45] text-white shadow-sm', false);
        $responseTrainers->assertSee('Pelatih PT');

        // 2. Pada /m/packages-info, chip Paket Gym harus aktif
        $responsePackages = $this->get('/m/packages-info');
        $responsePackages->assertStatus(200);
        $responsePackages->assertSee('bg-[#ED1B45] text-white shadow-sm', false);
        $responsePackages->assertSee('Paket Gym');

        // 3. Pada /m, chip Beranda harus aktif
        $responseHome = $this->get('/m');
        $responseHome->assertStatus(200);
        $responseHome->assertSee('bg-[#ED1B45] text-white shadow-sm', false);
        $responseHome->assertSee('Beranda');

        // 4. Pada /m/about, chip Tentang Kami harus aktif
        $responseAbout = $this->get('/m/about');
        $responseAbout->assertStatus(200);
        $responseAbout->assertSee('bg-[#ED1B45] text-white shadow-sm', false);
        $responseAbout->assertSee('Tentang Kami');
    }

    public function test_desktop_login_navbar_does_not_contain_redundant_login_action(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        // Memastikan logo mengarah ke landing index
        $response->assertSee(route('landing.index'));
        // Memastikan tidak ada link aksi "Masuk" pada navbar guest di halaman /login
        $response->assertDontSee('px-3 sm:px-4 py-2 text-xs font-bold text-slate-300 hover:text-white transition', false);
    }

    public function test_guest_clicking_package_has_login_redirect_to_checkout(): void
    {
        $pkg = MembershipPackage::first();

        // Desktop
        $responseDesktop = $this->get('/packages');
        $responseDesktop->assertStatus(200);
        $responseDesktop->assertSee(e(route('login', ['redirect' => route('member.membership.checkout', $pkg->id)])), false);

        // Mobile
        $responseMobile = $this->get('/m/packages-info');
        $responseMobile->assertStatus(200);
        $responseMobile->assertSee(e(route('mobile.login', ['redirect' => route('mobile.member.packages.checkout', $pkg->id)])), false);
    }

    public function test_guest_clicking_booking_has_login_redirect_with_trainer_id(): void
    {
        $trainer = User::where('role', 'trainer')->first();

        // Desktop
        $responseDesktop = $this->get('/trainers');
        $responseDesktop->assertStatus(200);
        $responseDesktop->assertSee(e(route('login', ['redirect' => route('pt-sessions.create', ['trainer_id' => $trainer->id])])), false);

        // Mobile
        $responseMobile = $this->get('/m/trainers');
        $responseMobile->assertStatus(200);
        $responseMobile->assertSee(e(route('mobile.login', ['redirect' => route('mobile.member.pt-sessions.create', ['trainer_id' => $trainer->id])])), false);
    }
}
