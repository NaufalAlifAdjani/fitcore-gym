<?php

namespace Tests\Feature;

use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtSessionRescheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_view_reschedule_page_more_than_4_hours_before(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $quota = MemberPtQuota::factory()->create(['member_id' => $member->id]);

        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '14:00:00',
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($member)->get(route('pt-sessions.edit', $session));

        $response->assertStatus(200);
        $response->assertSee('Ubah Jadwal');
    }

    public function test_member_cannot_view_reschedule_page_within_4_hours(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $quota = MemberPtQuota::factory()->create(['member_id' => $member->id]);

        // Session 2 hours from now
        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota->id,
            'session_date' => now()->toDateString(),
            'start_time' => now()->addHours(2)->format('H:i:00'),
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($member)->get(route('pt-sessions.edit', $session));

        $response->assertRedirect(route('pt-sessions.index'));
        $response->assertSessionHasErrors('error');
    }

    public function test_member_can_reschedule_session_successfully(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'used_sessions' => 3,
            'remaining_sessions' => 7,
        ]);

        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota->id,
            'session_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'scheduled',
        ]);

        $newDate = now()->addDays(4)->toDateString();

        $response = $this->actingAs($member)->put(route('pt-sessions.update', $session), [
            'session_date' => $newDate,
            'start_time' => '14:00',
        ]);

        $response->assertRedirect(route('pt-sessions.index'));
        $response->assertSessionHas('success');

        $session->refresh();
        $this->assertEquals($newDate, $session->session_date->toDateString());
        $this->assertEquals('14:00:00', $session->start_time);
        $this->assertEquals('15:00:00', $session->end_time);

        // Quota should remain completely untouched
        $quota->refresh();
        $this->assertEquals(3, $quota->used_sessions);
        $this->assertEquals(7, $quota->remaining_sessions);
    }

    public function test_member_cannot_reschedule_within_4_hours(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        $quota = MemberPtQuota::factory()->create(['member_id' => $member->id]);

        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota->id,
            'session_date' => now()->toDateString(),
            'start_time' => now()->addHours(2)->format('H:i:00'),
            'status' => 'scheduled',
        ]);

        $newDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($member)->put(route('pt-sessions.update', $session), [
            'session_date' => $newDate,
            'start_time' => '14:00',
        ]);

        $response->assertSessionHasErrors('reschedule_error');
    }

    public function test_reschedule_fails_if_target_slot_is_already_booked(): void
    {
        $member1 = User::factory()->member()->create();
        $member2 = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        $quota1 = MemberPtQuota::factory()->create(['member_id' => $member1->id]);
        $quota2 = MemberPtQuota::factory()->create(['member_id' => $member2->id]);

        $session1 = PtSession::factory()->create([
            'member_id' => $member1->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota1->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00:00',
            'status' => 'scheduled',
        ]);

        // Busy slot by member2
        $targetDate = now()->addDays(3)->toDateString();
        PtSession::factory()->create([
            'member_id' => $member2->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota2->id,
            'session_date' => $targetDate,
            'start_time' => '14:00:00',
            'status' => 'scheduled',
        ]);

        // Member1 attempts to reschedule to member2's slot
        $response = $this->actingAs($member1)->put(route('pt-sessions.update', $session1), [
            'session_date' => $targetDate,
            'start_time' => '14:00',
        ]);

        $response->assertSessionHasErrors('reschedule_error');
    }
}
