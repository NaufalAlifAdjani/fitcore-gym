<?php

namespace Database\Seeders;

use App\Models\CheckIn;
use App\Models\MemberPtQuota;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\User;
use Illuminate\Database\Seeder;
use Throwable;

class DashboardMetricsDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Ensure base master data exists
        $memberIds = User::query()->where('role', 'member')->pluck('id');
        if ($memberIds->isEmpty()) {
            $createdMember = User::query()->create([
                'name' => 'Member Dummy FitCore',
                'email' => 'member_dummy_'.uniqid().'@fitcore.test',
                'password' => bcrypt('password'),
                'role' => 'member',
            ]);
            $memberIds = collect([$createdMember->id]);
        }

        $packageIds = MembershipPackage::query()->pluck('id');
        if ($packageIds->isEmpty()) {
            $createdPackage = MembershipPackage::query()->create([
                'name' => 'Standard Membership',
                'tier' => 'Standard',
                'price' => 350000,
                'duration_in_days' => 30,
                'facilities' => ['Gym Access'],
                'is_active' => true,
            ]);
            $packageIds = collect([$createdPackage->id]);
        }

        $trainerIds = User::query()->where('role', 'trainer')->pluck('id');
        if ($trainerIds->isEmpty()) {
            $createdTrainer = User::query()->create([
                'name' => 'Trainer FitCore',
                'email' => 'trainer_dummy_'.uniqid().'@fitcore.test',
                'password' => bcrypt('password'),
                'role' => 'trainer',
            ]);
            $trainerIds = collect([$createdTrainer->id]);
        }

        $ptPackage = PtPackage::query()->first();
        if (! $ptPackage) {
            $ptPackage = PtPackage::query()->create([
                'name' => 'PT Regular Session',
                'price' => 200000,
                'session_count' => 10,
                'is_active' => true,
            ]);
        }

        $defaultQuota = MemberPtQuota::query()->first();
        if (! $defaultQuota) {
            $defaultQuota = MemberPtQuota::query()->create([
                'member_id' => $memberIds->first(),
                'membership_id' => null,
                'pt_package_id' => $ptPackage->id,
                'source' => 'purchase',
                'total_sessions' => 200,
                'used_sessions' => 10,
                'remaining_sessions' => 190,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(6),
                'status' => 'active',
            ]);
        }

        // A. Dummy "MEMBER AKTIF" (Tabel memberships) - 200 kali
        $createdMembershipCount = 0;
        for ($i = 0; $i < 200; $i++) {
            try {
                $memberId = $memberIds->random();
                $packageId = $packageIds->random();

                // start_date: 1 hingga 3 bulan ke belakang
                $startDate = now()->subDays(rand(30, 90))->toDateString();
                // end_date: 1 hingga 12 bulan ke depan
                $endDate = now()->addDays(rand(30, 365))->toDateString();

                Membership::query()->create([
                    'member_id' => $memberId,
                    'membership_package_id' => $packageId,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                ]);
                $createdMembershipCount++;
            } catch (Throwable $e) {
                // Continue execution if foreign key or constraint fails
            }
        }

        // Retrieve existing memberships for check-in relations
        $existingMemberships = Membership::query()->get(['id', 'member_id']);

        // B. Dummy "CHECK-IN HARI INI" (Tabel check_ins) - 150 kali
        $createdCheckInCount = 0;
        for ($i = 0; $i < 150; $i++) {
            try {
                if ($existingMemberships->isEmpty()) {
                    break;
                }

                $membership = $existingMemberships->random();
                $checkedInAt = today()->addHours(rand(6, 20))->addMinutes(rand(0, 59));

                CheckIn::query()->create([
                    'member_id' => $membership->member_id,
                    'membership_id' => $membership->id,
                    'checked_in_at' => $checkedInAt,
                    'checked_out_at' => (rand(0, 1) === 1) ? $checkedInAt->copy()->addMinutes(rand(30, 120)) : null,
                    'method' => fake()->randomElement(['manual', 'qr']),
                    'created_at' => $checkedInAt,
                    'updated_at' => $checkedInAt,
                ]);
                $createdCheckInCount++;
            } catch (Throwable $e) {
                // Continue execution if foreign key or constraint fails
            }
        }

        // C. Dummy "SESI PT BERLANGSUNG" (Tabel pt_sessions) - 45 kali
        $createdPtSessionCount = 0;
        $sessionDate = today()->toDateString();
        $startTime = now()->subHour()->format('H:i:s');
        $endTime = now()->addHour()->format('H:i:s');

        for ($i = 0; $i < 45; $i++) {
            try {
                $memberId = $memberIds->random();
                $trainerId = $trainerIds->random();

                // Ensure a quota exists for this member or use the default fallback quota
                $quota = MemberPtQuota::query()->firstOrCreate(
                    ['member_id' => $memberId],
                    [
                        'pt_package_id' => $ptPackage->id,
                        'source' => 'purchase',
                        'total_sessions' => 50,
                        'used_sessions' => 5,
                        'remaining_sessions' => 45,
                        'start_date' => now()->subMonth(),
                        'end_date' => now()->addMonths(6),
                        'status' => 'active',
                    ]
                );

                PtSession::query()->create([
                    'member_pt_quota_id' => $quota->id ?? $defaultQuota->id,
                    'member_id' => $memberId,
                    'trainer_id' => $trainerId,
                    'session_date' => $sessionDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => 'in_progress',
                ]);
                $createdPtSessionCount++;
            } catch (Throwable $e) {
                // Continue execution if foreign key or constraint fails
            }
        }

        $this->command?->info("DashboardMetricsDummySeeder selesai: {$createdMembershipCount} memberships, {$createdCheckInCount} check-ins, {$createdPtSessionCount} sesi PT aktif.");
    }
}
