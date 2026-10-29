<?php

namespace Database\Factories;

use App\Models\PtPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PtPackageFactory extends Factory
{
    protected $model = PtPackage::class;

    public function definition(): array
    {
        $sessions = fake()->randomElement([5, 10, 20]);

        return [
            'name' => $sessions.' PT Sessions',
            'pt_session_count' => $sessions,
            'price' => $sessions * 100000,
            'min_membership_days' => 0,
            'validity_days' => $sessions * 7, // 1 week per session
            'status' => 'active',
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
