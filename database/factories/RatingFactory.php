<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\MemberProfile;
use App\Models\TrainerProfile;
use App\Models\PtSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'member_id' => MemberProfile::factory(),
            'trainer_id' => TrainerProfile::factory(),
            'pt_session_id' => PtSession::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'review' => fake()->optional()->paragraph(),
        ];
    }
}
