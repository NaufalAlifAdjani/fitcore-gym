<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\MemberProfile;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'member_id' => MemberProfile::factory(),
            'amount' => fake()->randomElement([150000, 300000, 500000]),
            'bank_id' => Bank::factory(),
            'status' => fake()->randomElement(['pending', 'verified', 'rejected']),
            'payment_type' => fake()->randomElement(['membership', 'pt_package']),
        ];
    }
}
