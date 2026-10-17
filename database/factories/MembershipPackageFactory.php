<?php

namespace Database\Factories;

use App\Models\MembershipPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipPackageFactory extends Factory
{
    protected $model = MembershipPackage::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Package',
            'type' => fake()->randomElement(['monthly', 'yearly', 'daily']),
            'duration_days' => fake()->randomElement([30, 90, 365]),
            'price' => fake()->randomElement([150000, 300000, 500000, 1000000]),
            'facilities' => fake()->sentence(),
            'status' => 'active',
        ];
    }
}
