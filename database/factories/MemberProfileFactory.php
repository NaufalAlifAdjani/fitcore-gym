<?php

namespace Database\Factories;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberProfileFactory extends Factory
{
    protected $model = MemberProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'member_code' => 'MEM'.fake()->unique()->numerify('######'),
            'gender' => fake()->randomElement(['male', 'female']),
            'address' => fake()->address(),
        ];
    }
}
