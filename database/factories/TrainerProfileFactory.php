<?php

namespace Database\Factories;

use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerProfileFactory extends Factory
{
    protected $model = TrainerProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'specialization' => fake()->randomElement(['Weight Loss', 'Muscle Gain', 'Cardio', 'Strength Training', 'CrossFit']),
            'bio' => fake()->paragraph(),
            'experience' => fake()->numberBetween(1, 10),
            'status' => 'active',
        ];
    }
}
