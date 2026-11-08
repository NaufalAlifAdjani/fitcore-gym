<?php

namespace Tests\Feature;

use App\Models\TrainerAvailability;
use App\Models\TrainerDayOff;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function createTrainer(): User
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'email_verified_at' => now(),
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Strength & Conditioning',
            'experience' => 4,
        ]);

        return $trainer;
    }

    public function test_guest_cannot_access_availability_page(): void
    {
        $response = $this->get(route('trainer.availability.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_member_cannot_access_trainer_availability_page(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($member)->get(route('trainer.availability.index'));

        $response->assertStatus(403);
    }

    public function test_trainer_can_view_availability_page(): void
    {
        $trainer = $this->createTrainer();

        $response = $this->actingAs($trainer)->get(route('trainer.availability.index'));

        $response->assertStatus(200);
        $response->assertSee('Ketersediaan Waktu');
        $response->assertSee('Jadwal Mingguan');
        $response->assertSee('Cuti');
    }

    public function test_trainer_can_update_weekly_schedule(): void
    {
        $trainer = $this->createTrainer();

        $payload = [
            'days' => [
                1 => [ // Senin
                    'is_active' => '1',
                    'slots' => ['08:00', '09:00', '18:00'],
                ],
                2 => [ // Selasa
                    'is_active' => '1',
                    'slots' => ['13:00', '14:00'],
                ],
                0 => [ // Minggu
                    'is_active' => '0',
                    'slots' => [],
                ],
            ],
        ];

        $response = $this->actingAs($trainer)
            ->post(route('trainer.availability.schedule.update'), $payload);

        $response->assertRedirect(route('trainer.availability.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('trainer_availabilities', [
            'trainer_id' => $trainer->id,
            'day_of_week' => 1,
            'is_active' => true,
        ]);

        $senin = TrainerAvailability::where('trainer_id', $trainer->id)
            ->where('day_of_week', 1)
            ->first();

        $this->assertEquals(['08:00', '09:00', '18:00'], $senin->slots);
    }

    public function test_trainer_can_add_day_off(): void
    {
        $trainer = $this->createTrainer();
        $date = now()->addDays(3)->toDateString();

        $response = $this->actingAs($trainer)
            ->post(route('trainer.availability.day-offs.store'), [
                'date' => $date,
                'reason' => 'Cuti Tahunan',
            ]);

        $response->assertRedirect(route('trainer.availability.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('trainer_day_offs', [
            'trainer_id' => $trainer->id,
            'reason' => 'Cuti Tahunan',
        ]);

        $createdDayOff = TrainerDayOff::where('trainer_id', $trainer->id)->first();
        $this->assertNotNull($createdDayOff);
        $this->assertEquals($date, $createdDayOff->date->toDateString());
    }

    public function test_trainer_cannot_add_duplicate_day_off_for_same_date(): void
    {
        $trainer = $this->createTrainer();
        $date = now()->addDays(5)->toDateString();

        TrainerDayOff::create([
            'trainer_id' => $trainer->id,
            'date' => $date,
            'reason' => 'Libur Pertama',
        ]);

        $response = $this->actingAs($trainer)
            ->post(route('trainer.availability.day-offs.store'), [
                'date' => $date,
                'reason' => 'Libur Kedua',
            ]);

        $response->assertRedirect(route('trainer.availability.index'));
        $response->assertSessionHas('error');
    }

    public function test_trainer_can_delete_their_own_day_off(): void
    {
        $trainer = $this->createTrainer();
        $dayOff = TrainerDayOff::create([
            'trainer_id' => $trainer->id,
            'date' => now()->addDays(2)->toDateString(),
            'reason' => 'Cuti mendadak',
        ]);

        $response = $this->actingAs($trainer)
            ->delete(route('trainer.availability.day-offs.destroy', $dayOff->id));

        $response->assertRedirect(route('trainer.availability.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('trainer_day_offs', [
            'id' => $dayOff->id,
        ]);
    }

    public function test_trainer_cannot_delete_other_trainers_day_off(): void
    {
        $trainer1 = $this->createTrainer();
        $trainer2 = $this->createTrainer();

        $dayOff = TrainerDayOff::create([
            'trainer_id' => $trainer1->id,
            'date' => now()->addDays(4)->toDateString(),
            'reason' => 'Cuti Trainer 1',
        ]);

        $response = $this->actingAs($trainer2)
            ->delete(route('trainer.availability.day-offs.destroy', $dayOff->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('trainer_day_offs', [
            'id' => $dayOff->id,
        ]);
    }
}
