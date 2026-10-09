<?php

namespace Database\Seeders;

use App\Enums\PtSessionStatus;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PtBookingDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. PT Package default untuk kuota
        $ptPackage = PtPackage::firstOrCreate(
            ['name' => '10 Sessions'],
            [
                'pt_session_count' => 10,
                'price' => 1400000,
                'validity_days' => 90,
            ]
        );

        // 2. Trainer Accounts
        $trainersData = [
            [
                'email' => 'rama@gym.test',
                'name' => 'Coach Rama Prasetya, CSCS',
                'phone' => '081299990001',
                'profile' => [
                    'specialization' => 'Hypertrophy & Strength Conditioning',
                    'bio' => 'Head Coach di Fitcore dengan sertifikasi NSCA CSCS. Membimbing 100+ atlet dan member.',
                    'experience' => 8,
                    'tier' => 'Senior PT Tier III',
                    'studio' => 'SCBD Studio',
                    'rating' => 4.98,
                    'review_count' => 184,
                ],
            ],
            [
                'email' => 'trainer@fitcore.test',
                'name' => 'Trainer Utama',
                'phone' => '081299990005',
                'profile' => [
                    'specialization' => 'Fat Loss & Functional Fitness',
                    'bio' => 'Senior Personal Trainer spesialisasi program Fat Loss dan mobilitas atletik.',
                    'experience' => 5,
                    'tier' => 'Master Trainer Tier II',
                    'studio' => 'Kemang Studio',
                    'rating' => 4.92,
                    'review_count' => 96,
                ],
            ],
        ];

        $trainers = [];
        foreach ($trainersData as $tData) {
            $trainer = User::updateOrCreate(
                ['email' => $tData['email']],
                [
                    'name' => $tData['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'trainer',
                    'phone' => $tData['phone'],
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );

            TrainerProfile::updateOrCreate(
                ['user_id' => $trainer->id],
                array_merge($tData['profile'], ['status' => 'active'])
            );

            $trainers[] = $trainer;
        }

        // 3. Member Accounts
        $membersData = [
            ['name' => 'Demo Member', 'email' => 'member@gym.test', 'phone' => '081299990002', 'gender' => 'male'],
            ['name' => 'Budi Santoso', 'email' => 'budi@gym.test', 'phone' => '081288880001', 'gender' => 'male'],
            ['name' => 'Jessica Tan', 'email' => 'jessica@gym.test', 'phone' => '081288880002', 'gender' => 'female'],
            ['name' => 'Dimas Pratama', 'email' => 'dimas@gym.test', 'phone' => '081288880003', 'gender' => 'male'],
            ['name' => 'Siti Rahmah', 'email' => 'siti@gym.test', 'phone' => '081288880004', 'gender' => 'female'],
        ];

        $members = [];
        $quotas = [];
        foreach ($membersData as $idx => $mData) {
            $member = User::updateOrCreate(
                ['email' => $mData['email']],
                [
                    'name' => $mData['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'member',
                    'phone' => $mData['phone'],
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );

            MemberProfile::updateOrCreate(
                ['user_id' => $member->id],
                [
                    'member_code' => 'DEMO'.str_pad((string) ($idx + 1), 4, '0', STR_PAD_LEFT),
                    'gender' => $mData['gender'],
                    'address' => 'Jakarta Selatan',
                ]
            );

            $quota = MemberPtQuota::updateOrCreate(
                [
                    'member_id' => $member->id,
                    'source' => 'purchase',
                ],
                [
                    'pt_package_id' => $ptPackage->id,
                    'total_sessions' => 12,
                    'used_sessions' => rand(2, 6),
                    'remaining_sessions' => 6,
                    'start_date' => Carbon::now()->startOfMonth()->toDateString(),
                    'end_date' => Carbon::now()->addMonths(3)->endOfMonth()->toDateString(),
                    'status' => 'active',
                ]
            );

            $members[] = $member;
            $quotas[$member->id] = $quota;
        }

        // 4. Jadwal Sesi Booking
        $today = Carbon::now();
        $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);

        foreach ($trainers as $trainer) {
            // Skenario sesi hari ini ($today)
            $todaySessions = [
                [
                    'member' => $members[0], // Demo Member
                    'date' => $today->toDateString(),
                    'start' => '08:00:00',
                    'end' => '09:00:00',
                    'status' => PtSessionStatus::Done,
                ],
                [
                    'member' => $members[2], // Jessica Tan
                    'date' => $today->toDateString(),
                    'start' => '10:00:00',
                    'end' => '11:00:00',
                    'status' => PtSessionStatus::Ongoing,
                ],
                [
                    'member' => $members[1], // Budi Santoso
                    'date' => $today->toDateString(),
                    'start' => '14:00:00',
                    'end' => '15:00:00',
                    'status' => PtSessionStatus::Scheduled,
                ],
                [
                    'member' => $members[3], // Dimas Pratama
                    'date' => $today->toDateString(),
                    'start' => '16:30:00',
                    'end' => '17:30:00',
                    'status' => PtSessionStatus::PendingConfirmation,
                ],
                [
                    'member' => $members[4], // Siti Rahmah
                    'date' => $today->toDateString(),
                    'start' => '19:00:00',
                    'end' => '20:00:00',
                    'status' => PtSessionStatus::Scheduled,
                ],
            ];

            // Sesi hari-hari lain di minggu ini
            $weekSessions = [
                // Senin
                [
                    'member' => $members[1],
                    'date' => $startOfWeek->copy()->addDays(0)->toDateString(),
                    'start' => '09:00:00',
                    'end' => '10:00:00',
                    'status' => PtSessionStatus::Done,
                ],
                [
                    'member' => $members[3],
                    'date' => $startOfWeek->copy()->addDays(0)->toDateString(),
                    'start' => '15:00:00',
                    'end' => '16:00:00',
                    'status' => PtSessionStatus::Done,
                ],
                // Selasa
                [
                    'member' => $members[2],
                    'date' => $startOfWeek->copy()->addDays(1)->toDateString(),
                    'start' => '10:00:00',
                    'end' => '11:00:00',
                    'status' => PtSessionStatus::Done,
                ],
                // Rabu
                [
                    'member' => $members[4],
                    'date' => $startOfWeek->copy()->addDays(2)->toDateString(),
                    'start' => '08:30:00',
                    'end' => '09:30:00',
                    'status' => PtSessionStatus::Done,
                ],
                [
                    'member' => $members[0],
                    'date' => $startOfWeek->copy()->addDays(2)->toDateString(),
                    'start' => '14:00:00',
                    'end' => '15:00:00',
                    'status' => PtSessionStatus::Done,
                ],
                // Jumat
                [
                    'member' => $members[1],
                    'date' => $startOfWeek->copy()->addDays(4)->toDateString(),
                    'start' => '09:00:00',
                    'end' => '10:00:00',
                    'status' => PtSessionStatus::Scheduled,
                ],
                [
                    'member' => $members[2],
                    'date' => $startOfWeek->copy()->addDays(4)->toDateString(),
                    'start' => '15:00:00',
                    'end' => '16:00:00',
                    'status' => PtSessionStatus::PendingConfirmation,
                ],
                // Sabtu
                [
                    'member' => $members[3],
                    'date' => $startOfWeek->copy()->addDays(5)->toDateString(),
                    'start' => '10:00:00',
                    'end' => '11:00:00',
                    'status' => PtSessionStatus::Scheduled,
                ],
                [
                    'member' => $members[4],
                    'date' => $startOfWeek->copy()->addDays(5)->toDateString(),
                    'start' => '13:00:00',
                    'end' => '14:00:00',
                    'status' => PtSessionStatus::Scheduled,
                ],
            ];

            // Sesi minggu depan (termasuk 14 Okt & 20 Okt)
            $futureSessions = [
                [
                    'member' => $members[0],
                    'date' => $today->copy()->addDays(6)->toDateString(),
                    'start' => '10:00:00',
                    'end' => '11:00:00',
                    'status' => PtSessionStatus::Scheduled,
                ],
                [
                    'member' => $members[1],
                    'date' => $today->copy()->addDays(6)->toDateString(),
                    'start' => '14:00:00',
                    'end' => '15:00:00',
                    'status' => PtSessionStatus::PendingConfirmation,
                ],
                [
                    'member' => $members[2],
                    'date' => $today->copy()->addDays(12)->toDateString(),
                    'start' => '09:00:00',
                    'end' => '10:00:00',
                    'status' => PtSessionStatus::Scheduled,
                ],
                [
                    'member' => $members[3],
                    'date' => $today->copy()->addDays(12)->toDateString(),
                    'start' => '16:00:00',
                    'end' => '17:00:00',
                    'status' => PtSessionStatus::Scheduled,
                ],
            ];

            $allSessions = array_merge($todaySessions, $weekSessions, $futureSessions);

            foreach ($allSessions as $s) {
                PtSession::updateOrCreate(
                    [
                        'trainer_id' => $trainer->id,
                        'session_date' => $s['date'],
                        'start_time' => $s['start'],
                    ],
                    [
                        'member_id' => $s['member']->id,
                        'member_pt_quota_id' => $quotas[$s['member']->id]->id,
                        'end_time' => $s['end'],
                        'status' => $s['status'],
                    ]
                );
            }
        }
    }
}
