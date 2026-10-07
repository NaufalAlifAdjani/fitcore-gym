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
        return [
            'member_id' => MemberProfile::factory(),
            'trainer_id' => TrainerProfile::factory(),
            'scheduled_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'completed_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'status' => fake()->randomElement(['scheduled', 'completed', 'cancelled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
