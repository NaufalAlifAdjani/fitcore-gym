<?php

namespace Database\Factories;

use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainerProfile>
 */
class TrainerProfileFactory extends Factory
{
    protected $model = TrainerProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->trainer(),
            'specialization' => 'Hypertrophy & Strength',
            'bio' => 'Certified strength and conditioning specialist with 6+ years experience.',
            'experience' => 6,
            'tier' => 'Senior PT Tier III',
            'studio' => 'SCBD Studio',
            'rating' => 4.98,
            'review_count' => 184,
            'photo_path' => null,
            'status' => 'active',
        ];
    }
}
