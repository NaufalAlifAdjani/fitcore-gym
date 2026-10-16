<?php

namespace Tests\Feature\Livewire\Trainer\Sessions;

use App\Enums\PtSessionStatus;
use App\Livewire\Trainer\Sessions\Index;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_the_component_successfully()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->assertStatus(200)
            ->assertSee('Jadwal Sesi Personal Trainer');
    }

    public function test_shows_only_trainer_own_sessions()
    {
        $trainer1 = User::factory()->create(['role' => 'trainer']);
        $trainer2 = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);
        TrainerProfile::factory()->create(['user_id' => $trainer1->id]);
        TrainerProfile::factory()->create(['user_id' => $trainer2->id]);

        $package = PtPackage::factory()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'membership_id' => null,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        // Session for trainer 1
        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer1->id,
            'session_date' => now(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        // Session for trainer 2
        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer2->id,
            'session_date' => now(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $this->actingAs($trainer1);

        Livewire::test(Index::class)
            ->call('setView', 'day')
            ->assertSee('08:00 - 09:00 WIB')
            ->assertDontSee('10:00 - 11:00 WIB');
    }

    public function test_restricts_access_for_non_trainer_roles()
    {
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($member);

        $this->get(route('trainer.sessions.index'))->assertStatus(403);
    }

    public function test_orders_sessions_by_start_time_chronologically()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $package = PtPackage::factory()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        $cancelledSession = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => PtSessionStatus::Cancelled,
        ]);

        $pendingSession = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending,
        ]);

        $this->actingAs($trainer);

        $test = Livewire::test(Index::class);
        $sessions = $test->get('sessions');

        $this->assertEquals($cancelledSession->id, $sessions->first()->id);
        $this->assertEquals($pendingSession->id, $sessions->last()->id);
    }

    public function test_pending_session_shows_terima_and_tolak_buttons()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['name' => 'Budi Santoso', 'role' => 'member']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $package = PtPackage::factory()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 1,
            'remaining_sessions' => 9,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->call('setView', 'day')
            ->assertSee('Menunggu')
            ->assertSee('Terima')
            ->assertSee('Tolak');
    }

    public function test_trainer_can_accept_session_changing_status_to_confirmed()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $package = PtPackage::factory()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 1,
            'remaining_sessions' => 9,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        $session = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->call('confirmSession', $session->id)
            ->assertSee('Terkonfirmasi')
            ->assertDontSee('Terima');

        $session->refresh();
        $this->assertEquals(PtSessionStatus::Confirmed, $session->status);
    }

    public function test_trainer_can_reject_session_changing_status_to_cancelled_and_refunding_quota()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $package = PtPackage::factory()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 1,
            'remaining_sessions' => 9,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        $session = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Pending,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->call('rejectSession', $session->id)
            ->assertSee('Dibatalkan')
            ->assertDontSee('Tolak');

        $session->refresh();
        $this->assertEquals(PtSessionStatus::Cancelled, $session->status);

        $quota->refresh();
        $this->assertEquals(0, $quota->used_sessions);
        $this->assertEquals(10, $quota->remaining_sessions);
    }

    public function test_confirmed_session_does_not_show_terima_or_tolak_buttons()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['role' => 'member']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $package = PtPackage::factory()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 1,
            'remaining_sessions' => 9,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->call('setView', 'day')
            ->assertSee('Terkonfirmasi')
            ->assertDontSee('Terima')
            ->assertDontSee('Tolak');
    }

    public function test_can_switch_views_between_day_week_and_month()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->assertSet('view', 'month')
            ->call('setView', 'week')
            ->assertSet('view', 'week')
            ->call('setView', 'day')
            ->assertSet('view', 'day');
    }

    public function test_can_switch_to_tasks_mode_and_see_tasks_list()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $member = User::factory()->create(['name' => 'Jessica Tan', 'role' => 'member']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $package = PtPackage::factory()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->assertSet('mode', 'calendar')
            ->call('setMode', 'tasks')
            ->assertSet('mode', 'tasks')
            ->assertSee('Daftar Sesi Latihan')
            ->assertDontSee('Add a task')
            ->assertSee('Jessica Tan');
    }

    public function test_tasks_list_adjusts_content_based_on_day_week_and_month_filters_and_today()
    {
        Carbon::setTestNow('2026-10-14 10:00:00'); // Wednesday

        $trainer = User::factory()->create(['role' => 'trainer']);
        $member1 = User::factory()->create(['name' => 'Member Today', 'role' => 'member']);
        $member2 = User::factory()->create(['name' => 'Member Next Week', 'role' => 'member']);
        $member3 = User::factory()->create(['name' => 'Member Next Month', 'role' => 'member']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $package = PtPackage::factory()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member1->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        // Today: 2026-10-14
        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member1->id,
            'trainer_id' => $trainer->id,
            'session_date' => '2026-10-14',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        // Next Week: 2026-10-21
        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member2->id,
            'trainer_id' => $trainer->id,
            'session_date' => '2026-10-21',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        // Next Month: 2026-11-15
        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member3->id,
            'trainer_id' => $trainer->id,
            'session_date' => '2026-11-15',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->call('setMode', 'tasks')
            ->call('setView', 'week')
            ->assertSet('mode', 'tasks')
            ->assertSet('view', 'week')
            ->assertSee('Member Today')
            ->assertDontSee('Member Next Week')
            ->assertDontSee('Member Next Month')
            ->call('nextPeriod')
            ->assertSee('Member Next Week')
            ->assertDontSee('Member Today')
            ->assertDontSee('Member Next Month')
            ->call('goToToday')
            ->assertSee('Member Today')
            ->assertDontSee('Member Next Week')
            ->call('setView', 'month')
            ->assertSee('Member Today')
            ->assertSee('Member Next Week')
            ->assertDontSee('Member Next Month')
            ->call('setView', 'day')
            ->assertSee('Member Today')
            ->assertDontSee('Member Next Week')
            ->assertDontSee('Member Next Month');

        Carbon::setTestNow();
    }

    public function test_trainer_portal_renders_navbar_and_sidebar_correctly()
    {
        $trainer = User::factory()->create([
            'name' => 'Coach Rama Prasetya, CSCS',
            'role' => 'trainer',
        ]);
        TrainerProfile::factory()->create([
            'user_id' => $trainer->id,
            'tier' => 'Senior PT Tier III',
            'specialization' => 'CSCS - Head Trainer',
        ]);

        $response = $this->actingAs($trainer)->get(route('trainer.sessions.index'));

        $response->assertStatus(200);
        // Brand & Portal
        $response->assertSee('MAXFIT');
        $response->assertSee('TRAINER PORTAL');
        // Trainer Name & Subtitle
        $response->assertSee('Coach Rama Prasetya');
        $response->assertSee('CSCS - Head Trainer');
        // Sidebar Main Menu items
        $response->assertSee('MAIN MENU');
        $response->assertSee('Dashboard');
        $response->assertSee('Jadwal Sesi PT');
        $response->assertSee('Klien Saya');
        $response->assertSee('Ketersediaan Waktu');
        $response->assertSee('Pendapatan / Komisi');
    }

    public function test_tasks_mode_filters_by_status()
    {
        Carbon::setTestNow('2026-10-16 10:00:00');

        $trainer = User::factory()->create(['role' => 'trainer']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $memberPending = User::factory()->create(['name' => 'Member Menunggu', 'role' => 'member']);
        $memberConfirmed = User::factory()->create(['name' => 'Member Terkonfirmasi', 'role' => 'member']);
        $memberCancelled = User::factory()->create(['name' => 'Member Dibatalkan', 'role' => 'member']);

        $package = PtPackage::factory()->create();

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $memberPending->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $memberPending->id,
            'trainer_id' => $trainer->id,
            'session_date' => '2026-10-16',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => PtSessionStatus::Pending,
        ]);

        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $memberConfirmed->id,
            'trainer_id' => $trainer->id,
            'session_date' => '2026-10-16',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $memberCancelled->id,
            'trainer_id' => $trainer->id,
            'session_date' => '2026-10-16',
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'status' => PtSessionStatus::Cancelled,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->call('setMode', 'tasks')
            ->call('setView', 'day')
            ->assertSet('taskStatusFilter', 'all')
            ->assertSee('Menunggu')
            ->assertSee('Terkonfirmasi')
            ->assertSee('Dibatalkan')
            ->assertSee('Member Menunggu')
            ->assertSee('Member Terkonfirmasi')
            ->assertSee('Member Dibatalkan')
            // Filter: Pending
            ->call('setTaskStatusFilter', 'pending')
            ->assertSet('taskStatusFilter', 'pending')
            ->assertSee('Member Menunggu')
            ->assertDontSee('Member Terkonfirmasi')
            ->assertDontSee('Member Dibatalkan')
            // Filter: Confirmed
            ->call('setTaskStatusFilter', 'confirmed')
            ->assertSet('taskStatusFilter', 'confirmed')
            ->assertSee('Member Terkonfirmasi')
            ->assertDontSee('Member Menunggu')
            ->assertDontSee('Member Dibatalkan')
            // Filter: Cancelled
            ->call('setTaskStatusFilter', 'cancelled')
            ->assertSet('taskStatusFilter', 'cancelled')
            ->assertSee('Member Dibatalkan')
            ->assertDontSee('Member Menunggu')
            ->assertDontSee('Member Terkonfirmasi')
            // Back to All
            ->call('setTaskStatusFilter', 'all')
            ->assertSee('Member Menunggu')
            ->assertSee('Member Terkonfirmasi')
            ->assertSee('Member Dibatalkan');

        Carbon::setTestNow();
    }
}
