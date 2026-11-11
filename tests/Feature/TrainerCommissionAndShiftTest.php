<?php

namespace Tests\Feature;

use App\Enums\PtSessionStatus;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerCommissionAndShiftTest extends TestCase
{
    use RefreshDatabase;

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    protected function createTrainer(string $name = 'Coach Doni', string $shift = 'pagi', int $rate = 60000): User
    {
        $trainer = User::factory()->create([
            'name' => $name,
            'role' => 'trainer',
            'shift' => $shift,
            'email_verified_at' => now(),
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Hypertrophy',
            'experience' => 4,
            'commission_rate' => $rate,
        ]);

        return $trainer;
    }

    public function test_admin_can_update_trainer_shift_and_commission_rate(): void
    {
        $admin = $this->createAdmin();
        $trainer = $this->createTrainer('Coach Doni', 'pagi', 60000);

        $response = $this->actingAs($admin)
            ->put(route('admin.trainers.update', $trainer), [
                'name' => 'Coach Doni Updated',
                'email' => $trainer->email,
                'phone' => '081299998888',
                'shift' => 'sore',
                'specialization' => 'Powerlifting',
                'experience' => 5,
                'commission_rate' => 85000,
                'status' => 'active',
            ]);

        $response->assertRedirect(route('admin.trainers.index'));
        $this->assertEquals('sore', $trainer->fresh()->shift);
        $this->assertEquals(85000, $trainer->trainerProfile->fresh()->commission_rate);
    }

    public function test_admin_can_create_trainer_with_shift_and_commission_rate(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->post(route('admin.trainers.store'), [
                'name' => 'Coach Baru',
                'email' => 'baru@fitcore.my.id',
                'phone' => '081234567890',
                'shift' => 'sore',
                'specialization' => 'Fat Loss',
                'experience' => 3,
                'commission_rate' => 70000,
                'send_email' => 0,
            ]);

        $response->assertRedirect(route('admin.trainers.index'));

        $newTrainer = User::where('email', 'baru@fitcore.my.id')->first();
        $this->assertNotNull($newTrainer);
        $this->assertEquals('sore', $newTrainer->shift);
        $this->assertEquals(70000, $newTrainer->trainerProfile->commission_rate);
    }

    public function test_trainer_can_view_own_commissions_page(): void
    {
        $trainer = $this->createTrainer('Coach Mandiri', 'sore', 75000);

        $response = $this->actingAs($trainer)
            ->get(route('trainer.commissions.index'));

        $response->assertStatus(200);
        $response->assertSee('Pendapatan &amp; Komisi Sesi Latihan', false);
        $response->assertSee('Tarif: Rp 75.000 / Sesi');
        $response->assertSee('Shift: Sore (14:00 – 22:00 WIB)');
    }

    public function test_session_completion_snapshots_trainer_commission_earnings(): void
    {
        $trainer = $this->createTrainer('Coach Doni', 'pagi', 72000);
        $member = User::factory()->create(['role' => 'member', 'email_verified_at' => now()]);

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 5,
            'used_sessions' => 0,
            'remaining_sessions' => 5,
            'end_date' => now()->addMonth(),
        ]);

        $session = PtSession::create([
            'trainer_id' => $trainer->id,
            'member_id' => $member->id,
            'member_pt_quota_id' => $quota->id,
            'session_date' => now()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'status' => PtSessionStatus::Scheduled->value,
            'trainer_earnings' => 0,
        ]);

        $session->markAsCompleted('Latihan dada dan trisep berjalan lancar');

        $session->refresh();
        $this->assertEquals(PtSessionStatus::Completed, $session->status);
        $this->assertEquals(72000, $session->trainer_earnings);
    }
}
