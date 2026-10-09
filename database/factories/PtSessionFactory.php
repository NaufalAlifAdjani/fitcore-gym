<?php

namespace Database\Factories;

use App\Models\PtSession;
use App\Models\MemberProfile;
use App\Models\TrainerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class PtSessionFactory extends Factory
{
    protected $model = PtSession::class;

    public function definition(): array
    {
        $startTime = fake()->numberBetween(8, 20);
        return [
            'member_pt_quota_id' => \App\Models\MemberPtQuota::factory(),
            'member_id' => MemberProfile::factory(),
            'trainer_id' => TrainerProfile::factory(),
            'session_date' => fake()->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'start_time' => sprintf('%02d:00:00', $startTime),
            'end_time' => sprintf('%02d:00:00', $startTime + 1),
            'status' => fake()->randomElement(['scheduled', 'done', 'cancelled']),
        ];
    }
}
