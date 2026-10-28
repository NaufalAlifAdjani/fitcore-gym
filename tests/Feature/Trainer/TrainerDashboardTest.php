<?php

namespace Tests\Feature\Trainer;

use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\Rating;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/trainer/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_member_gets_forbidden_on_trainer_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $response = $this->actingAs($member)->get('/trainer/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_gets_forbidden_on_trainer_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/trainer/dashboard');

        $response->assertStatus(403);
    }

    public function test_trainer_can_view_performance_dashboard_with_metrics_and_reviews(): void
    {
        $trainer = User::factory()->create([
            'name' => 'Rian Ardiansyah',
            'role' => 'trainer',
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Master Trainer',
            'bio' => 'Coach Master',
            'experience' => 8,
            'status' => 'active',
        ]);

        $member = User::factory()->create([
            'name' => 'Kevin Kusuma',
            'role' => 'member',
        ]);

        MemberProfile::create([
            'user_id' => $member->id,
            'member_code' => 'MC-8821',
        ]);

        $package = PtPackage::create([
            'name' => 'Paket PT 10 Sesi',
            'pt_session_count' => 10,
            'price' => 600000,
            'min_membership_days' => 0,
            'validity_days' => 90,
            'status' => 'active',
        ]);

        $quota = MemberPtQuota::create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 1,
            'remaining_sessions' => 9,
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(2),
            'status' => 'active',
        ]);

        $session = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'completed',
        ]);

        Rating::create([
            'member_id' => $member->id,
            'pt_session_id' => $session->id,
            'trainer_id' => $trainer->id,
            'rating' => 5,
            'comment' => 'Coach Rian teliti banget koreksi form bench press.',
        ]);

        $response = $this->actingAs($trainer)->get('/trainer/dashboard');

        $response->assertOk();
        $response->assertSee('Performa Saya');
        $response->assertSee('Total Sesi');
        $response->assertSee('Rating Rata-rata');
        $response->assertSee('Total Member Aktif');
        $response->assertSee('Kevin Kusuma');
        $response->assertSee('#MC-8821');
        $response->assertSee('Paket PT 10 Sesi');
        $response->assertSee('Coach Rian teliti banget koreksi form bench press.');
        $response->assertSee('Coach Rian Ardiansyah');
        $response->assertSee('Master Trainer');
    }

    public function test_trainer_can_filter_reviews_by_star_rating(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);

        $member1 = User::factory()->create(['name' => 'Client Five Star', 'role' => 'member']);
        $member2 = User::factory()->create(['name' => 'Client Four Star', 'role' => 'member']);

        $pkg = PtPackage::create([
            'name' => 'Paket PT 10 Sesi',
            'pt_session_count' => 10,
            'price' => 600000,
            'min_membership_days' => 0,
            'validity_days' => 90,
            'status' => 'active',
        ]);

        $quota1 = MemberPtQuota::create([
            'member_id' => $member1->id,
            'pt_package_id' => $pkg->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'remaining_sessions' => 9,
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(2),
        ]);

        $quota2 = MemberPtQuota::create([
            'member_id' => $member2->id,
            'pt_package_id' => $pkg->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'remaining_sessions' => 9,
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(2),
        ]);

        $s1 = PtSession::create([
            'member_pt_quota_id' => $quota1->id,
            'member_id' => $member1->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => 'completed',
        ]);

        $s2 = PtSession::create([
            'member_pt_quota_id' => $quota2->id,
            'member_id' => $member2->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'completed',
        ]);

        Rating::create([
            'member_id' => $member1->id,
            'pt_session_id' => $s1->id,
            'trainer_id' => $trainer->id,
            'rating' => 5,
            'comment' => 'Luar biasa bintang lima!',
        ]);

        Rating::create([
            'member_id' => $member2->id,
            'pt_session_id' => $s2->id,
            'trainer_id' => $trainer->id,
            'rating' => 4,
            'comment' => 'Bagus bintang empat.',
        ]);

        // Filter 5 stars
        $res5 = $this->actingAs($trainer)->get('/trainer/dashboard?star=5');
        $res5->assertOk();
        $res5->assertSee('Luar biasa bintang lima!');
        $res5->assertDontSee('Bagus bintang empat.');

        // Filter 4 stars
        $res4 = $this->actingAs($trainer)->get('/trainer/dashboard?star=4');
        $res4->assertOk();
        $res4->assertSee('Bagus bintang empat.');
        $res4->assertDontSee('Luar biasa bintang lima!');
    }

    public function test_trainer_dashboard_displays_all_main_menu_items(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);

        $response = $this->actingAs($trainer)->get('/trainer/dashboard');

        $response->assertOk();
        $response->assertSee('MAIN MENU');
        $response->assertSee('Dashboard');
        $response->assertSee('Jadwal Sesi PT');
        $response->assertSee('Klien Saya');
        $response->assertSee('Ketersediaan Waktu');
        $response->assertSee('Pendapatan / Komisi');
    }

    public function test_trainer_can_access_submenu_routes(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);

        $routes = [
            '/trainer/jadwal-sesi' => 'Jadwal Sesi PT',
            '/trainer/klien-saya' => 'Klien Saya',
            '/trainer/ketersediaan-waktu' => 'Ketersediaan Waktu',
            '/trainer/pendapatan-komisi' => 'Pendapatan / Komisi',
        ];

        foreach ($routes as $url => $title) {
            $response = $this->actingAs($trainer)->get($url);
            $response->assertOk();
            $response->assertSee($title);
        }
    }

    public function test_member_cannot_access_trainer_submenu_routes(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $urls = [
            '/trainer/jadwal-sesi',
            '/trainer/klien-saya',
            '/trainer/ketersediaan-waktu',
            '/trainer/pendapatan-komisi',
        ];

        foreach ($urls as $url) {
            $response = $this->actingAs($member)->get($url);
            $response->assertStatus(403);
        }
    }
}
