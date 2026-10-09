<?php

namespace Tests\Feature\Livewire\Trainer\Sessions;

use App\Enums\PtSessionStatus;
use App\Livewire\Trainer\Sessions\Index;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\Rating;
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
            'status' => PtSessionStatus::Scheduled,
        ]);

        // Session for trainer 2
        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer2->id,
            'session_date' => now(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Scheduled,
        ]);

        $this->actingAs($trainer1);

        Livewire::test(Index::class)
            ->assertSee('08:00 - 09:00 WIB')
            ->assertDontSee('10:00 - 11:00 WIB');
    }

    public function test_restricts_access_for_non_trainer_roles()
    {
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($member);

        $this->get(route('trainer.sessions.index'))->assertStatus(403);
    }

    public function test_orders_ongoing_session_at_the_front_on_today()
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

        $pastSession = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => PtSessionStatus::Done,
        ]);

        $ongoingSession = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Ongoing,
        ]);

        $this->actingAs($trainer);

        $test = Livewire::test(Index::class);
        $sessions = $test->get('sessions');

        $this->assertEquals($ongoingSession->id, $sessions->first()->id);
    }

    public function test_orders_closest_upcoming_session_at_the_front_when_no_ongoing_session()
    {
        Carbon::setTestNow('2026-10-09 12:00:00');

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

        $pastSession = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => PtSessionStatus::Done,
        ]);

        $closestSession = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => PtSessionStatus::Scheduled,
        ]);

        $laterSession = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '18:00:00',
            'end_time' => '19:00:00',
            'status' => PtSessionStatus::Scheduled,
        ]);

        $this->actingAs($trainer);

        $test = Livewire::test(Index::class);
        $sessions = $test->get('sessions');

        $this->assertEquals($closestSession->id, $sessions->first()->id);

        Carbon::setTestNow();
    }

    public function test_ongoing_session_shows_only_catatan_and_no_timer_or_selesai_button()
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

        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Ongoing,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->assertSee('Catatan')
            ->assertDontSee('Timer')
            ->assertDontSee('Selesai');
    }

    public function test_completed_session_shows_catatan_and_rating_button()
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

        $session = PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => PtSessionStatus::Done,
        ]);

        Rating::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'pt_session_id' => $session->id,
            'rating' => 5,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->assertSee('Catatan')
            ->assertSee('Rating 5.0');
    }

    public function test_scheduled_session_does_not_show_nanti_sore_text()
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

        PtSession::factory()->create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'status' => PtSessionStatus::Scheduled,
        ]);

        $this->actingAs($trainer);

        Livewire::test(Index::class)
            ->assertDontSee('Nanti Sore');
    }
}
