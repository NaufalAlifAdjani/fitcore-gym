<?php

namespace Tests\Feature;

use App\Enums\PtSessionStatus;
use App\Exceptions\SlotUnavailableException;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use App\Services\PtSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtBookingAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected PtSessionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PtSessionService::class);
    }

    protected function createTrainer(string $name = 'Coach Budi', string $shift = 'pagi', int $commissionRate = 60000): User
    {
        $trainer = User::factory()->create([
            'name' => $name,
            'role' => 'trainer',
            'shift' => $shift,
            'email_verified_at' => now(),
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Hypertrophy & Strength',
            'experience' => 5,
            'commission_rate' => $commissionRate,
        ]);

        return $trainer;
    }

    protected function createMemberWithQuota(int $remainingSessions = 5): array
    {
        $member = User::factory()->create([
            'role' => 'member',
            'email_verified_at' => now(),
        ]);

        MemberProfile::create([
            'user_id' => $member->id,
            'member_code' => 'FC-999001',
        ]);

        $quota = MemberPtQuota::create([
            'member_id' => $member->id,
            'source' => 'purchase',
            'total_sessions' => 10,
            'used_sessions' => 10 - $remainingSessions,
            'remaining_sessions' => $remainingSessions,
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'active',
        ]);

        return [$member, $quota];
    }

    public function test_slots_respect_trainer_shift_pagi(): void
    {
        $trainer = $this->createTrainer('Coach Pagi', 'pagi');
        $targetDate = now()->addDays(3)->toDateString();

        $groups = $this->service->getAvailableSlots($trainer, $targetDate);
        $flatSlots = collect($groups)->flatten(1)->keyBy('time');

        // Jam pagi (06:00 - 13:00) tersedia
        $this->assertEquals('available', $flatSlots['08:00']['status']);
        $this->assertEquals('available', $flatSlots['12:00']['status']);

        // Jam sore/malam (14:00 - 21:00) di luar shift
        $this->assertEquals('off', $flatSlots['15:00']['status']);
        $this->assertEquals('Di Luar Shift', $flatSlots['15:00']['label']);
        $this->assertEquals('off', $flatSlots['19:00']['status']);
        $this->assertEquals('Di Luar Shift', $flatSlots['19:00']['label']);
    }

    public function test_slots_respect_trainer_shift_sore(): void
    {
        $trainer = $this->createTrainer('Coach Sore', 'sore');
        $targetDate = now()->addDays(3)->toDateString();

        $groups = $this->service->getAvailableSlots($trainer, $targetDate);
        $flatSlots = collect($groups)->flatten(1)->keyBy('time');

        // Jam pagi di luar shift
        $this->assertEquals('off', $flatSlots['08:00']['status']);
        $this->assertEquals('Di Luar Shift', $flatSlots['08:00']['label']);
        $this->assertEquals('off', $flatSlots['12:00']['status']);
        $this->assertEquals('Di Luar Shift', $flatSlots['12:00']['label']);

        // Jam sore/malam tersedia
        $this->assertEquals('available', $flatSlots['15:00']['status']);
        $this->assertEquals('available', $flatSlots['19:00']['status']);
    }

    public function test_slots_respect_trainer_shift_fleksibel(): void
    {
        $trainer = $this->createTrainer('Coach Fleksibel', 'fleksibel');
        $targetDate = now()->addDays(3)->toDateString();

        $groups = $this->service->getAvailableSlots($trainer, $targetDate);

        // Baik pagi maupun sore harus tersedia
        foreach ($groups['morning'] as $slot) {
            $this->assertEquals('available', $slot['status']);
        }

        foreach ($groups['afternoon'] as $slot) {
            $this->assertEquals('available', $slot['status']);
        }
    }

    public function test_validate_booking_throws_exception_when_booking_outside_trainer_shift(): void
    {
        $trainer = $this->createTrainer('Coach Pagi', 'pagi');
        [$member, $quota] = $this->createMemberWithQuota();

        $targetDate = now()->addDays(3);

        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('Jam yang dipilih berada di luar jam shift bertugas pelatih (Shift Pagi: 06:00 – 14:00 WIB).');

        $this->service->validateBooking($member, $trainer, $targetDate, '18:00:00');
    }

    public function test_session_completion_records_trainer_earnings_snapshot(): void
    {
        $trainer = $this->createTrainer('Coach Rama', 'sore', 75000);
        [$member, $quota] = $this->createMemberWithQuota();

        $session = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => PtSessionStatus::Confirmed,
        ]);

        $session->markAsCompleted('Latihan squat dan lunges berjalan lancar.');

        $this->assertEquals(PtSessionStatus::Completed, $session->fresh()->status);
        $this->assertEquals(75000, $session->fresh()->trainer_earnings);
        $this->assertEquals('Latihan squat dan lunges berjalan lancar.', $session->fresh()->notes);
    }
}
