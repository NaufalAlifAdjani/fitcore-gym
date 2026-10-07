<?php

namespace Tests\Feature;

use App\Enums\PtSessionStatus;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtSessionBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_view_booking_page(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'end_date' => now()->addMonth(),
        ]);

        $response = $this->actingAs($member)->get(route('pt-sessions.create'));

        $response->assertStatus(200);
        $response->assertSee('Pilih Jadwal');
    }

    public function test_member_can_fetch_slots_json(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        $targetDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($member)->getJson(route('pt-sessions.slots', [
            'trainer_id' => $trainer->id,
            'date' => $targetDate,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'date',
            'formatted_date',
            'trainer' => ['id', 'name'],
            'groups' => ['morning', 'afternoon', 'evening'],
        ]);
    }

    public function test_member_can_book_session_successfully(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 2,
            'remaining_sessions' => 8,
            'end_date' => now()->addMonth(),
        ]);

        $targetDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($member)->post(route('pt-sessions.store'), [
            'trainer_id' => $trainer->id,
            'session_date' => $targetDate,
            'start_time' => '10:00',
        ]);

        $response->assertRedirect(route('pt-sessions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pt_sessions', [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => $targetDate,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => PtSessionStatus::Scheduled->value,
        ]);

        $quota->refresh();
        $this->assertEquals(3, $quota->used_sessions);
        $this->assertEquals(7, $quota->remaining_sessions);
    }

    public function test_booking_fails_when_member_has_zero_quota(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        MemberPtQuota::factory()->depleted()->create([
            'member_id' => $member->id,
        ]);

        $targetDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($member)->post(route('pt-sessions.store'), [
            'trainer_id' => $trainer->id,
            'session_date' => $targetDate,
            'start_time' => '14:00',
        ]);

        $response->assertSessionHasErrors('booking_error');
        $this->assertDatabaseCount('pt_sessions', 0);
    }

    public function test_booking_fails_when_quota_is_expired(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        MemberPtQuota::factory()->expired()->create([
            'member_id' => $member->id,
        ]);

        $targetDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($member)->post(route('pt-sessions.store'), [
            'trainer_id' => $trainer->id,
            'session_date' => $targetDate,
            'start_time' => '14:00',
        ]);

        $response->assertSessionHasErrors('booking_error');
        $this->assertDatabaseCount('pt_sessions', 0);
    }

    public function test_booking_fails_when_slot_is_already_booked(): void
    {
        $member1 = User::factory()->member()->create();
        $member2 = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();

        MemberPtQuota::factory()->create(['member_id' => $member1->id, 'remaining_sessions' => 5]);
        MemberPtQuota::factory()->create(['member_id' => $member2->id, 'remaining_sessions' => 5]);

        $targetDate = now()->addDays(2)->toDateString();

        // Existing booking by member1
        PtSession::factory()->create([
            'trainer_id' => $trainer->id,
            'member_id' => $member1->id,
            'session_date' => $targetDate,
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'status' => 'scheduled',
        ]);

        // Member2 tries to book the same slot
        $response = $this->actingAs($member2)->post(route('pt-sessions.store'), [
            'trainer_id' => $trainer->id,
            'session_date' => $targetDate,
            'start_time' => '15:00',
        ]);

        $response->assertSessionHasErrors('booking_error');
    }

    public function test_booking_fails_when_member_has_overlapping_session(): void
    {
        $member = User::factory()->member()->create();
        $trainer1 = User::factory()->trainer()->create();
        $trainer2 = User::factory()->trainer()->create();

        MemberPtQuota::factory()->create(['member_id' => $member->id, 'remaining_sessions' => 5]);

        $targetDate = now()->addDays(2)->toDateString();

        // Member has session with trainer1 at 10:00
        PtSession::factory()->create([
            'trainer_id' => $trainer1->id,
            'member_id' => $member->id,
            'session_date' => $targetDate,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'scheduled',
        ]);

        // Member tries to book same time with trainer2
        $response = $this->actingAs($member)->post(route('pt-sessions.store'), [
            'trainer_id' => $trainer2->id,
            'session_date' => $targetDate,
            'start_time' => '10:00',
        ]);

        $response->assertSessionHasErrors('booking_error');
    }

    public function test_booking_fails_for_past_date_or_time(): void
    {
        $member = User::factory()->member()->create();
        $trainer = User::factory()->trainer()->create();
        MemberPtQuota::factory()->create(['member_id' => $member->id, 'remaining_sessions' => 5]);

        $pastDate = now()->subDays(2)->toDateString();

        $response = $this->actingAs($member)->post(route('pt-sessions.store'), [
            'trainer_id' => $trainer->id,
            'session_date' => $pastDate,
            'start_time' => '10:00',
        ]);

        $response->assertSessionHasErrors('session_date');
    }
}
