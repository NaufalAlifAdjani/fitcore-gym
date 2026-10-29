<?php

namespace Database\Seeders;

use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\Rating;
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
                'min_membership_days' => 0,
                'validity_days' => 90,
                'status' => 'active',
            ]
        );

        $pkg12 = PtPackage::query()->updateOrCreate(
            ['name' => 'Paket PT 12 Sesi'],
            [
                'pt_session_count' => 12,
                'price' => 750000,
                'min_membership_days' => 0,
                'validity_days' => 90,
                'status' => 'active',
            ]
        );

        $pkg24 = PtPackage::query()->updateOrCreate(
            ['name' => 'Paket PT 24 Sesi'],
            [
                'pt_session_count' => 24,
                'price' => 1400000,
                'min_membership_days' => 0,
                'validity_days' => 180,
                'status' => 'active',
            ]
        );

        // 3. Define the 4 featured review members from mockup
        $featuredMembersData = [
            [
                'name' => 'Kevin Kusuma',
                'email' => 'kevin.kusuma@member.test',
                'code' => 'MC-8821',
                'package' => $pkg10,
                'rating' => 5,
                'date' => Carbon::now()->subDays(1),
                'comment' => 'Coach Rian teliti banget koreksi form bench press dan squat. Beban naik konsisten tanpa cedera. Program latihan terukur dan motivasinya luar biasa!',
            ],
            [
                'name' => 'Amanda Valerie',
                'email' => 'amanda.valerie@member.test',
                'code' => 'MC-7740',
                'package' => $pkg12,
                'rating' => 5,
                'date' => Carbon::now()->subDays(2),
                'comment' => 'Latihan core & strength terasa banget perkembangannya. Jam sesi selalu on-time dan fleksibel kalau ada reschedule dinas luar kota. Recommended!',
            ],
            [
                'name' => 'Dimas Pratama',
                'email' => 'dimas.pratama@member.test',
                'code' => 'MC-5412',
                'package' => $pkg24,
                'rating' => 5,
                'date' => Carbon::now()->subDays(4),
                'comment' => 'Program hypertrophy pro-nya mantap. Otot dada dan bahu makin padat terdefinisi. Pengecekan progres mingguan sangat membantu.',
            ],
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah.jenkins@member.test',
                'code' => 'MC-3034',
                'package' => $pkg10,
                'rating' => 5,
                'date' => Carbon::now()->subDays(8),
                'comment' => 'Sesi kickoff dan functional training sangat seru, ga ngebosenin. Coach sangat komunikatif dan informatif!',
            ],
        ];

        // 4. Additional feedback comments for remaining 34 reviews (31 of 5-star, 3 of 4-star)
        $additionalComments = [
            5 => [
                'Penjelasan gerakan anatomi otot sangat jelas. Hasil fat loss terlihat dalam 4 minggu pertama.',
                'Disiplin waktu dan menu latihan bervariasi setiap sesi. Selalu bersemangat datang gym.',
                'Teknik pernapasan dan tempo repetisi diajarkan dengan detail. Cidera bahu lama tidak kambuh lagi.',
                'Pendampingan gizi dan asupan protein harian sangat membantu target cutting saya.',
                'Push workout hari ini gila banget pump-nya. Coach selalu tahu batas kemampuan dan memotivasi.',
                'Progress squat dari 40kg sekarang sudah tembus 90kg dengan form sempurna. Top coach!',
                'Sangat sabar membimbing pemula yang baru pertama kali kenal barbel dan dumbell.',
                'Program mobilitas sendi sebelum compound movement membuat badan jauh lebih lentur.',
                'Sesi latihan efisien 60 menit padat tanpa buang-buang waktu mengobrol.',
                'Metode progressive overload-nya terstruktur rapi di catatan sesi. Sangat profesional.',
            ],
            4 => [
                'Program latihan sangat menantang dan memuaskan. Kadang jadwal malam agak padat jadi perlu booking lebih awal.',
                'Coach sangat kompeten dan ramah. Fasilitas gym di jam peak hour agak ramai tapi sesi tetap optimal.',
                'Penjelasan form bagus sekali. Sedikit masukan untuk variasi pendinginan/stretching di akhir sesi.',
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
            $sessionCount = 0;
            $createdSessions = [];

            // First create the featured sessions with ratings
            foreach ($featuredMembersData as $idx => $feat) {
                $memberObj = $allMembers[$idx];
                $session = PtSession::query()->create([
                    'member_pt_quota_id' => $memberObj['quota']->id,
                    'member_id' => $memberObj['user']->id,
                    'trainer_id' => $trainer->id,
                    'session_date' => $feat['date']->toDateString(),
                    'start_time' => '09:00:00',
                    'end_time' => '10:00:00',
                    'status' => 'completed',
                ]);

                Rating::query()->updateOrCreate(
                    ['pt_session_id' => $session->id],
                    [
                        'member_id' => $memberObj['user']->id,
                        'trainer_id' => $trainer->id,
                        'rating' => $feat['rating'],
                        'comment' => $feat['comment'],
                        'created_at' => $feat['date'],
                        'updated_at' => $feat['date'],
                    ]
                );

                $sessionCount++;
                $createdSessions[] = $session;
            }

            // Create remaining ratings: we need total 38 ratings (35 five-stars, 3 four-stars)
            // We already created 4 five-star ratings, so 31 five-star and 3 four-star remain.
            $ratingsToCreate = [];
            for ($i = 0; $i < 31; $i++) {
                $ratingsToCreate[] = 5;
            }
            for ($i = 0; $i < 3; $i++) {
                $ratingsToCreate[] = 4;
            }
            shuffle($ratingsToCreate);

            foreach ($ratingsToCreate as $rIndex => $star) {
                $memberPick = $allMembers[($rIndex + 4) % count($allMembers)];
                $date = Carbon::now()->subDays(rand(3, 28));

                $session = PtSession::query()->create([
                    'member_pt_quota_id' => $memberPick['quota']->id,
                    'member_id' => $memberPick['user']->id,
                    'trainer_id' => $trainer->id,
                    'session_date' => $date->toDateString(),
                    'start_time' => '14:00:00',
                    'end_time' => '15:00:00',
                    'status' => 'completed',
                ]);

                $commentList = $additionalComments[$star];
                $comment = $commentList[$rIndex % count($commentList)];

                Rating::query()->updateOrCreate(
                    ['pt_session_id' => $session->id],
                    [
                        'member_id' => $memberPick['user']->id,
                        'trainer_id' => $trainer->id,
                        'rating' => $star,
                        'comment' => $comment,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]
                );

                $sessionCount++;
                $createdSessions[] = $session;
            }

            // Total sessions needed is 74. We currently have 4 + 34 = 38 sessions.
            // Create remaining 36 completed sessions (without rating, or standard completed)
            while ($sessionCount < 74) {
                $memberPick = $allMembers[$sessionCount % count($allMembers)];
                $date = Carbon::now()->subDays(rand(1, 29));

                PtSession::query()->create([
                    'member_pt_quota_id' => $memberPick['quota']->id,
                    'member_id' => $memberPick['user']->id,
                    'trainer_id' => $trainer->id,
                    'session_date' => $date->toDateString(),
                    'start_time' => sprintf('%02d:00:00', rand(8, 19)),
                    'end_time' => sprintf('%02d:00:00', rand(9, 20)),
                    'status' => 'completed',
                ]);

                $sessionCount++;
            }
        }
    }
}
