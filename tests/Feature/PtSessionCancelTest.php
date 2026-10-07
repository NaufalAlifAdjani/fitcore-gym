<?php

namespace Tests\Feature;

use App\Enums\PtSessionStatus;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtSessionCancelTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_cancel_session_more_than_4_hours_before(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 4,
            'remaining_sessions' => 6,
        ]);

        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '15:00:00',
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($member)->patch(route('pt-sessions.cancel', $session), [
            'reason' => 'Ada jadwal kuliah bentrok',
        ]);

        $response->assertRedirect(route('pt-sessions.index'));
        $response->assertSessionHas('success');

        $session->refresh();
        $this->assertEquals(PtSessionStatus::Cancelled, $session->status);

        // Quota refunded: used -1, remaining +1
        $quota->refresh();
        $this->assertEquals(3, $quota->used_sessions);
        $this->assertEquals(7, $quota->remaining_sessions);
    }

    public function test_member_cannot_cancel_session_within_4_hours(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 4,
            'remaining_sessions' => 6,
        ]);

        // Session in 2 hours
        $session = PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota->id,
            'session_date' => now()->toDateString(),
            'start_time' => now()->addHours(2)->format('H:i:00'),
            'status' => 'scheduled',
        ]);

        $response = $this->actingAs($member)->patch(route('pt-sessions.cancel', $session), [
            'reason' => 'Batal mendadak',
        ]);

        $response->assertSessionHasErrors('cancel_error');

        $session->refresh();
        $this->assertEquals(PtSessionStatus::Scheduled, $session->status);

        // Quota NOT refunded
        $quota->refresh();
        $this->assertEquals(4, $quota->used_sessions);
        $this->assertEquals(6, $quota->remaining_sessions);
    }
}
