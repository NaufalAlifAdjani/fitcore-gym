<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\MemberProfile;
use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'member_id' => MemberProfile::factory(),
            'invoice_number' => 'INV-' . strtoupper(fake()->unique()->bothify('?????#####')),
            'amount' => fake()->randomElement([150000, 300000, 500000]),
            'payment_method' => fake()->randomElement(['bank_transfer', 'cash', 'credit_card']),
            'bank_id' => Bank::factory(),
            'status' => fake()->randomElement(['pending', 'completed', 'failed']),
            'payment_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'type' => fake()->randomElement(['membership', 'pt_package']),
        ];
    }
}
