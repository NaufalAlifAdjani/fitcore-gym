<?php

namespace Database\Factories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankFactory extends Factory
{
    protected $model = Bank::class;

    public function definition(): array
    {
        $banks = ['BCA', 'BNI', 'Mandiri', 'BRI', 'CIMB Niaga'];
        return [
            'name' => fake()->randomElement($banks),
            'account_number' => fake()->numerify('##########'),
            'account_holder' => fake()->name(),
            'status' => 'active',
        ];
    }
}
