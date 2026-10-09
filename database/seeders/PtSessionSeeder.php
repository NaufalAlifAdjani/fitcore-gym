<?php

namespace Database\Seeders;

use App\Enums\PtSessionStatus;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class PtSessionSeeder extends Seeder
{
    public function run(): void
    {
        $trainer = User::where('role', 'trainer')->first() ?? User::factory()->create(['role' => 'trainer']);
        if (! $trainer->trainerProfile) {
            TrainerProfile::create(['user_id' => $trainer->id]);
        }

        $member = User::where('role', 'member')->first() ?? User::factory()->create(['role' => 'member']);
        $package = PtPackage::first() ?? PtPackage::factory()->create();

        $quota = MemberPtQuota::where('member_id', $member->id)->first() ?? MemberPtQuota::create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'source' => 'purchase',
            'total_sessions' => 12,
            'used_sessions' => 4,
            'remaining_sessions' => 8,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);

        $today = now()->toDateString();

        PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => $today,
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'status' => PtSessionStatus::Ongoing,
        ]);

        PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => $today,
            'start_time' => '19:00:00',
            'end_time' => '20:00:00',
            'status' => PtSessionStatus::PendingConfirmation,
        ]);

        PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => $today,
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'status' => PtSessionStatus::Done,
        ]);
    }
}
