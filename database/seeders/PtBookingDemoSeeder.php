<?php

namespace Database\Seeders;

use App\Models\MemberPtQuota;
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
        // 1. Coach Rama Prasetya, CSCS
        $trainer = User::updateOrCreate(
            ['email' => 'rama@gym.test'],
            [
                'name' => 'Coach Rama Prasetya, CSCS',
                'password' => Hash::make('password123'),
                'role' => 'trainer',
                'phone' => '081299990001',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        TrainerProfile::updateOrCreate(
            ['user_id' => $trainer->id],
            [
                'specialization' => 'Hypertrophy & Strength Conditioning',
                'bio' => 'Head Coach di Fitcore dengan sertifikasi NSCA CSCS. Membimbing 100+ atlet dan member.',
                'experience' => 8,
                'tier' => 'Senior PT Tier III',
                'studio' => 'SCBD Studio',
                'rating' => 4.98,
                'review_count' => 184,
                'photo_path' => null,
                'status' => 'active',
            ]
        );

        // 2. Demo Member
        $member = User::updateOrCreate(
            ['email' => 'member@gym.test'],
            [
                'name' => 'Demo Member',
                'password' => Hash::make('password123'),
                'role' => 'member',
                'phone' => '081299990002',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 3. Member Quota (12 Sesi: 4 Digunakan, 8 Sisa)
        $quota = MemberPtQuota::updateOrCreate(
            [
                'member_id' => $member->id,
                'source' => 'purchase',
            ],
            [
                'total_sessions' => 12,
                'used_sessions' => 4,
                'remaining_sessions' => 8,
                'start_date' => Carbon::now()->startOfMonth()->toDateString(),
                'end_date' => Carbon::now()->addMonths(2)->endOfMonth()->toDateString(),
                'status' => 'active',
            ]
        );

        // 4. Sesi Contoh
        // Sesi Mendatang 1 (Besok jam 14:00)
        PtSession::updateOrCreate(
            [
                'member_id' => $member->id,
                'trainer_id' => $trainer->id,
                'session_date' => Carbon::now()->addDay()->toDateString(),
                'start_time' => '14:00:00',
            ],
            [
                'member_pt_quota_id' => $quota->id,
                'end_time' => '15:00:00',
                'status' => 'scheduled',
            ]
        );

        // Sesi Mendatang 2 (3 hari lagi jam 10:00)
        PtSession::updateOrCreate(
            [
                'member_id' => $member->id,
                'trainer_id' => $trainer->id,
                'session_date' => Carbon::now()->addDays(3)->toDateString(),
                'start_time' => '10:00:00',
            ],
            [
                'member_pt_quota_id' => $quota->id,
                'end_time' => '11:00:00',
                'status' => 'scheduled',
            ]
        );

        // Sesi Selesai (5 hari yang lalu jam 09:00)
        PtSession::updateOrCreate(
            [
                'member_id' => $member->id,
                'trainer_id' => $trainer->id,
                'session_date' => Carbon::now()->subDays(5)->toDateString(),
                'start_time' => '09:00:00',
            ],
            [
                'member_pt_quota_id' => $quota->id,
                'end_time' => '10:00:00',
                'status' => 'done',
            ]
        );

        // Sesi Selesai (10 hari yang lalu jam 16:00)
        PtSession::updateOrCreate(
            [
                'member_id' => $member->id,
                'trainer_id' => $trainer->id,
                'session_date' => Carbon::now()->subDays(10)->toDateString(),
                'start_time' => '16:00:00',
            ],
            [
                'member_pt_quota_id' => $quota->id,
                'end_time' => '17:00:00',
                'status' => 'done',
            ]
        );
    }
}
