<?php

namespace Tests\Feature\Trainer;

use App\Enums\PtSessionStatus;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
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

    public function test_trainer_can_view_operational_dashboard_with_metrics_today_sessions_and_commission(): void
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
            'remaining_sessions' => 1, // <= 2 to trigger low quota alert
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(2),
            'status' => 'active',
        ]);

        // Today's completed session
        PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Completed->value,
        ]);

        // Pending session
        PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->addDay()->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => PtSessionStatus::Pending->value,
        ]);

        $response = $this->actingAs($trainer)->get('/trainer/dashboard');

        $response->assertOk();
        $response->assertSee('Rian Ardiansyah');
        $response->assertSee('Sesi Hari Ini');
        $response->assertSee('Total Sesi Selesai');
        $response->assertSee('Estimasi Komisi');
        $response->assertSee('Total Member Aktif');
        $response->assertSee('Jadwal Sesi Hari Ini');
        $response->assertSee('Permintaan Booking');
        $response->assertSee('Peringatan Sisa Kuota');
        $response->assertSee('Kevin Kusuma');
        $response->assertSee('#MC-8821');
        $response->assertSee('Paket PT 10 Sesi');
        $response->assertDontSee('/ 80 Sesi');
        $response->assertDontSee('/ 20 Member');
    }

    public function test_trainer_can_confirm_pending_session_from_dashboard(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);

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
            'session_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending->value,
        ]);

        $response = $this->actingAs($trainer)->post(route('trainer.sessions.confirm', $session));

        $response->assertRedirect(route('trainer.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pt_sessions', [
            'id' => $session->id,
            'status' => PtSessionStatus::Confirmed->value,
        ]);
    }

    public function test_trainer_can_reject_pending_session_and_refund_quota(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);

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
            'session_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending->value,
        ]);

        $response = $this->actingAs($trainer)->post(route('trainer.sessions.reject', $session));

        $response->assertRedirect(route('trainer.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pt_sessions', [
            'id' => $session->id,
            'status' => PtSessionStatus::Cancelled->value,
        ]);

        $this->assertDatabaseHas('member_pt_quotas', [
            'id' => $quota->id,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
        ]);
    }

    public function test_trainer_cannot_confirm_or_reject_other_trainers_session(): void
    {
        $trainerA = User::factory()->create(['role' => 'trainer']);
        $trainerB = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);

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
            'trainer_id' => $trainerA->id,
            'session_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending->value,
        ]);

        // Trainer B attempts to confirm Trainer A's session
        $responseConfirm = $this->actingAs($trainerB)->post(route('trainer.sessions.confirm', $session));
        $responseConfirm->assertStatus(403);

        // Trainer B attempts to reject Trainer A's session
        $responseReject = $this->actingAs($trainerB)->post(route('trainer.sessions.reject', $session));
        $responseReject->assertStatus(403);
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
        $response->assertSee('Pendapatan / Komisi');
    }

    public function test_trainer_can_access_submenu_routes(): void
    {
        $trainer = User::factory()->create(['role' => 'trainer']);

        $routes = [
            '/trainer/jadwal-sesi' => 'Jadwal Sesi PT',
            '/trainer/klien-saya' => 'Klien Saya',
            '/trainer/pendapatan-komisi' => 'Pendapatan &amp; Komisi',
        ];

        foreach ($routes as $url => $title) {
            $response = $this->actingAs($trainer)->get($url);
            $response->assertOk();
            $response->assertSee($title, false);
        }
    }

    public function test_member_cannot_access_trainer_submenu_routes(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $urls = [
            '/trainer/jadwal-sesi',
            '/trainer/klien-saya',
            '/trainer/pendapatan-komisi',
        ];

        foreach ($urls as $url) {
            $response = $this->actingAs($member)->get($url);
            $response->assertStatus(403);
        }
    }
}
