<?php

namespace Database\Factories;

use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipFactory extends Factory
{
    protected $model = Membership::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'member_id' => User::factory(),
            'membership_package_id' => MembershipPackage::factory(),
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, '+6 months'),
            'status' => fake()->randomElement(['active', 'expired', 'cancelled']),
        ];
    }
}
