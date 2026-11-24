<?php

namespace Database\Factories;

use App\Models\QrToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QrToken>
 */
class QrTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => User::factory()->create(['role' => 'member'])->id,
            'token' => bin2hex(random_bytes(32)),
            'expires_at' => now()->addSeconds(30),
            'used_at' => null,
        ];
    }

    /**
     * Create an already-expired token.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subSeconds(10),
        ]);
    }

    /**
     * Create an already-used token.
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'used_at' => now()->subSeconds(5),
        ]);
    }
}
