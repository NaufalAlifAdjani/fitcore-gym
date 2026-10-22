<?php

namespace Tests\Feature;

use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtSessionPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_cannot_edit_or_reschedule_another_members_session(): void
    {
        $memberA = User::factory()->member()->create();
        $memberB = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        $quotaB = MemberPtQuota::factory()->create(['member_id' => $memberB->id]);

        $sessionB = PtSession::factory()->create([
            'member_id' => $memberB->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quotaB->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00:00',
            'status' => 'scheduled',
        ]);

        // Member A tries to view edit page of Member B
        $responseEdit = $this->actingAs($memberA)->get(route('pt-sessions.edit', $sessionB));
        $responseEdit->assertStatus(403);

        // Member A tries to update Member B's session
        $responseUpdate = $this->actingAs($memberA)->put(route('pt-sessions.update', $sessionB), [
            'session_date' => now()->addDays(3)->toDateString(),
            'start_time' => '14:00',
        ]);
        $responseUpdate->assertStatus(403);
    }

    public function test_member_cannot_cancel_another_members_session(): void
    {
        $memberA = User::factory()->member()->create();
        $memberB = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        $quotaB = MemberPtQuota::factory()->create(['member_id' => $memberB->id]);

        $sessionB = PtSession::factory()->create([
            'member_id' => $memberB->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quotaB->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00:00',
            'status' => 'scheduled',
        ]);

        // Member A tries to cancel Member B's session
        $responseCancel = $this->actingAs($memberA)->patch(route('pt-sessions.cancel', $sessionB));
        $responseCancel->assertStatus(403);
    }

    public function test_guest_cannot_access_pt_session_routes(): void
    {
        $responseIndex = $this->get(route('pt-sessions.index'));
        $responseIndex->assertRedirect(route('login'));

        $responseCreate = $this->get(route('pt-sessions.create'));
        $responseCreate->assertRedirect(route('login'));

        $responseStore = $this->post(route('pt-sessions.store'), []);
        $responseStore->assertRedirect(route('login'));
    }
}
