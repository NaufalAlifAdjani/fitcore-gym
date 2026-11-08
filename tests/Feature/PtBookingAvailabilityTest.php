<?php

namespace Tests\Feature;

use App\Exceptions\SlotUnavailableException;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\TrainerAvailability;
use App\Models\TrainerDayOff;
use App\Models\TrainerProfile;
use App\Models\User;
use App\Services\PtSessionService;
use Carbon\Carbon;
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

    protected function createTrainer(string $name = 'Coach Budi'): User
    {
        $trainer = User::factory()->create([
            'name' => $name,
            'role' => 'trainer',
            'email_verified_at' => now(),
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Hypertrophy & Strength',
            'experience' => 5,
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

    public function test_slots_are_marked_off_when_date_is_trainer_day_off(): void
    {
        $trainer = $this->createTrainer();
        $targetDate = now()->addDays(5)->toDateString();

        TrainerDayOff::create([
            'trainer_id' => $trainer->id,
            'date' => $targetDate,
            'reason' => 'Cuti Tahunan',
        ]);

        $groups = $this->service->getAvailableSlots($trainer, $targetDate);

        foreach ($groups as $slots) {
            foreach ($slots as $slot) {
                $this->assertEquals('off', $slot['status']);
                $this->assertEquals('Libur PT', $slot['label']);
            }
        }
    }

    public function test_slots_respect_trainer_weekly_schedule_and_inactive_days(): void
    {
        $trainer = $this->createTrainer();

        // Cari tanggal hari Senin dan Minggu mendatang (ambil minggu depan agar aman dari batas 24 jam notice)
        $nextMonday = now()->addDays(7)->next(Carbon::MONDAY);
        $nextSunday = now()->addDays(7)->next(Carbon::SUNDAY);

        // Set Senin: hanya buka jam 08:00 dan 09:00
        TrainerAvailability::create([
            'trainer_id' => $trainer->id,
            'day_of_week' => 1, // Senin
            'is_active' => true,
            'slots' => ['08:00', '09:00'],
        ]);

        // Set Minggu: Libur rutin
        TrainerAvailability::create([
            'trainer_id' => $trainer->id,
            'day_of_week' => 0, // Minggu
            'is_active' => false,
            'slots' => [],
        ]);

        // Cek Hari Senin: 08:00 & 09:00 tersedia, jam lain off
        $mondayGroups = $this->service->getAvailableSlots($trainer, $nextMonday);
        $flatMondaySlots = collect($mondayGroups)->flatten(1)->keyBy('time');

        $this->assertEquals('available', $flatMondaySlots['08:00']['status']);
        $this->assertEquals('available', $flatMondaySlots['09:00']['status']);
        $this->assertEquals('off', $flatMondaySlots['10:00']['status']);
        $this->assertEquals('off', $flatMondaySlots['18:00']['status']);

        // Cek Hari Minggu: semua slot off
        $sundayGroups = $this->service->getAvailableSlots($trainer, $nextSunday);
        $flatSundaySlots = collect($sundayGroups)->flatten(1);

        foreach ($flatSundaySlots as $slot) {
            $this->assertEquals('off', $slot['status']);
        }
    }

    public function test_validate_booking_throws_exception_on_trainer_day_off(): void
    {
        $trainer = $this->createTrainer();
        [$member, $quota] = $this->createMemberWithQuota();

        $targetDate = now()->addDays(3);
        TrainerDayOff::create([
            'trainer_id' => $trainer->id,
            'date' => $targetDate->toDateString(),
            'reason' => 'Sakit',
        ]);

        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('Pelatih sedang libur/cuti pada tanggal tersebut.');

        $this->service->validateBooking($member, $trainer, $targetDate, '09:00:00');
    }

    public function test_validate_booking_throws_exception_on_unopened_trainer_slot(): void
    {
        $trainer = $this->createTrainer();
        [$member, $quota] = $this->createMemberWithQuota();

        $nextTuesday = now()->next(Carbon::TUESDAY);

        // Hanya buka slot jam 18:00 di hari Selasa
        TrainerAvailability::create([
            'trainer_id' => $trainer->id,
            'day_of_week' => 2, // Selasa
            'is_active' => true,
            'slots' => ['18:00'],
        ]);

        $this->expectException(SlotUnavailableException::class);
        $this->expectExceptionMessage('Pelatih tidak membuka sesi latihan pada jam yang dipilih.');

        $this->service->validateBooking($member, $trainer, $nextTuesday, '08:00:00');
    }

    public function test_member_select_trainer_page_shows_availability_summary(): void
    {
        [$member] = $this->createMemberWithQuota();

        $response = $this->actingAs($member)
            ->get(route('member.pt-packages.select-trainer'));

        $response->assertStatus(200);
        $response->assertSee('PILIH PERSONAL TRAINER ANDA');
        $response->assertSee('Preferensi Waktu:');
        $response->assertSee('Sedia Pagi');
        $response->assertSee('Sedia Malam');
    }
}
