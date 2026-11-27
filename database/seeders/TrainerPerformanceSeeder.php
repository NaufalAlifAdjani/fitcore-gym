<?php

namespace Database\Seeders;

use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerPerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or update Trainer Coach Rian Ardiansyah and Coach Budi Santoso
        $rian = User::query()->updateOrCreate(
            ['email' => 'rian@gym.test'],
            [
                'name' => 'Rian Ardiansyah',
                'password' => Hash::make('password123'),
                'role' => 'trainer',
                'phone' => '081299900001',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        TrainerProfile::query()->updateOrCreate(
            ['user_id' => $rian->id],
            [
                'specialization' => 'Master Trainer',
                'bio' => 'Certified Master Strength & Conditioning Specialist with 8+ years experience.',
                'experience' => 8,
                'status' => 'active',
            ]
        );

        $budi = User::query()->where('email', 'budi@gym.test')->first();
        if ($budi) {
            TrainerProfile::query()->updateOrCreate(
                ['user_id' => $budi->id],
                [
                    'specialization' => 'Master Trainer',
                ]
            );
        }

        $trainers = array_filter([$rian, $budi]);

        // 2. Ensure PT Packages exist
        $pkg10 = PtPackage::query()->updateOrCreate(
            ['name' => 'Paket PT 10 Sesi'],
            [
                'pt_session_count' => 10,
                'price' => 650000,
                'validity_days' => 90,
                'status' => 'active',
            ]
        );

        $pkg12 = PtPackage::query()->updateOrCreate(
            ['name' => 'Paket PT 12 Sesi'],
            [
                'pt_session_count' => 12,
                'price' => 750000,
                'validity_days' => 90,
                'status' => 'active',
            ]
        );

        $pkg24 = PtPackage::query()->updateOrCreate(
            ['name' => 'Paket PT 24 Sesi'],
            [
                'pt_session_count' => 24,
                'price' => 1400000,
                'validity_days' => 180,
                'status' => 'active',
            ]
        );

        // 3. Define the 4 featured members from mockup
        $featuredMembersData = [
            [
                'name' => 'Kevin Kusuma',
                'email' => 'kevin.kusuma@member.test',
                'code' => 'MC-8821',
                'package' => $pkg10,
                'date' => Carbon::now()->subDays(1),
            ],
            [
                'name' => 'Amanda Valerie',
                'email' => 'amanda.valerie@member.test',
                'code' => 'MC-7740',
                'package' => $pkg12,
                'date' => Carbon::now()->subDays(2),
            ],
            [
                'name' => 'Dimas Pratama',
                'email' => 'dimas.pratama@member.test',
                'code' => 'MC-5412',
                'package' => $pkg24,
                'date' => Carbon::now()->subDays(4),
            ],
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah.jenkins@member.test',
                'code' => 'MC-3034',
                'package' => $pkg10,
                'date' => Carbon::now()->subDays(8),
            ],
        ];

        foreach ($trainers as $trainer) {
            // Seed 18 active members with quotas
            $allMembers = [];

            // Seed featured members first
            foreach ($featuredMembersData as $item) {
                $user = User::query()->updateOrCreate(
                    ['email' => $item['email']],
                    [
                        'name' => $item['name'],
                        'password' => Hash::make('password123'),
                        'role' => 'member',
                        'phone' => '0813'.rand(10000000, 99999999),
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ]
                );

                MemberProfile::query()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['member_code' => $item['code']]
                );

                $quota = MemberPtQuota::query()->updateOrCreate(
                    ['member_id' => $user->id, 'pt_package_id' => $item['package']->id],
                    [
                        'source' => 'purchase',
                        'total_sessions' => $item['package']->pt_session_count,
                        'used_sessions' => max(0, $item['package']->pt_session_count - 6),
                        'remaining_sessions' => 6,
                        'start_date' => Carbon::now()->startOfMonth(),
                        'end_date' => Carbon::now()->addMonths(3),
                        'status' => 'active',
                    ]
                );

                $allMembers[] = [
                    'user' => $user,
                    'quota' => $quota,
                    'package' => $item['package'],
                    'initial_review' => $item,
                ];
            }

            // Seed more members up to 18 active members
            $otherNames = [
                ['Rizky Fauzi', 'MC-9102'],
                ['Jessica Tan', 'MC-4419'],
                ['Bambang Gunawan', 'MC-6231'],
                ['Nadia Salsabila', 'MC-1108'],
                ['Daniel Wijaya', 'MC-7890'],
                ['Clarissa Putri', 'MC-2384'],
                ['Bayu Wicaksono', 'MC-5012'],
                ['Fiona Stephanie', 'MC-3349'],
                ['Hendra Kusuma', 'MC-8120'],
                ['Tiara Andini', 'MC-6734'],
                ['Michael Jordan', 'MC-9941'],
                ['Renata Kusumo', 'MC-1205'],
                ['Gilang Ramadhan', 'MC-4598'],
                ['Dewi Lestari', 'MC-3211'],
            ];

            foreach ($otherNames as $idx => [$name, $code]) {
                $slug = strtolower(str_replace(' ', '.', $name));
                $user = User::query()->updateOrCreate(
                    ['email' => "{$slug}@member.test"],
                    [
                        'name' => $name,
                        'password' => Hash::make('password123'),
                        'role' => 'member',
                        'phone' => '0812'.rand(10000000, 99999999),
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ]
                );

                MemberProfile::query()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['member_code' => $code]
                );

                $pkg = ($idx % 2 === 0) ? $pkg10 : $pkg12;
                $rem = rand(2, 8);
                $quota = MemberPtQuota::query()->updateOrCreate(
                    ['member_id' => $user->id, 'pt_package_id' => $pkg->id],
                    [
                        'source' => 'purchase',
                        'total_sessions' => $pkg->pt_session_count,
                        'used_sessions' => max(0, $pkg->pt_session_count - $rem),
                        'remaining_sessions' => $rem,
                        'start_date' => Carbon::now()->startOfMonth(),
                        'end_date' => Carbon::now()->addMonths(2),
                        'status' => 'active',
                    ]
                );

                $allMembers[] = [
                    'user' => $user,
                    'quota' => $quota,
                    'package' => $pkg,
                    'initial_review' => null,
                ];
            }

            // Create completed sessions up to 74 completed sessions
            $sessionCount = PtSession::query()
                ->where('trainer_id', $trainer->id)
                ->where('status', 'completed')
                ->count();

            // First ensure the featured sessions exist
            foreach ($featuredMembersData as $idx => $feat) {
                $memberObj = $allMembers[$idx];
                $sessionDate = $feat['date']->toDateString();

                PtSession::query()->firstOrCreate(
                    [
                        'trainer_id' => $trainer->id,
                        'session_date' => $sessionDate,
                        'start_time' => '09:00:00',
                    ],
                    [
                        'member_pt_quota_id' => $memberObj['quota']->id,
                        'member_id' => $memberObj['user']->id,
                        'end_time' => '10:00:00',
                        'status' => 'completed',
                    ]
                );
            }

            // Total sessions needed is 74.
            // Create remaining completed sessions with non-colliding time slots
            $slotHour = 10;
            $dayOffset = 1;
            while ($sessionCount < 74 && $dayOffset <= 60) {
                $memberPick = $allMembers[$sessionCount % count($allMembers)];
                $sessionDate = Carbon::now()->subDays($dayOffset)->toDateString();
                $startTime = sprintf('%02d:00:00', $slotHour);
                $endTime = sprintf('%02d:00:00', $slotHour + 1);

                $exists = PtSession::query()
                    ->where('trainer_id', $trainer->id)
                    ->where('session_date', $sessionDate)
                    ->where('start_time', $startTime)
                    ->exists();

                if (! $exists) {
                    PtSession::query()->create([
                        'member_pt_quota_id' => $memberPick['quota']->id,
                        'member_id' => $memberPick['user']->id,
                        'trainer_id' => $trainer->id,
                        'session_date' => $sessionDate,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => 'completed',
                    ]);
                    $sessionCount++;
                }

                $slotHour++;
                if ($slotHour > 20) {
                    $slotHour = 8;
                    $dayOffset++;
                }
            }
        }
    }
}
