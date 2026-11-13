<?php

namespace Database\Factories;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'member',
            'phone' => fake()->phoneNumber(),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }

    public function member(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'member',
        ]);
    }

    public function trainer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'trainer',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function completeProfile(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => '081234567890',
        ])->afterCreating(function (User $user) {
            if ($user->role === 'member') {
                MemberProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'member_code' => 'MEM'.fake()->unique()->numerify('######'),
                        'address' => 'Jl. Jenderal Sudirman No. 1, Jakarta',
                        'gender' => 'male',
                    ]
                );
            }
        });
    }

    public function incompleteProfile(): static
    {
        return $this->state(fn (array $attributes) => [
            'phone' => null,
        ])->afterCreating(function (User $user) {
            if ($user->memberProfile) {
                $user->memberProfile->update(['address' => null]);
            }
        });
    }
}
