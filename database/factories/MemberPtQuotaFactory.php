<?php

namespace Database\Factories;

use App\Models\MemberPtQuota;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberPtQuota>
 */
class MemberPtQuotaFactory extends Factory
{
    protected $model = MemberPtQuota::class;

    public function definition(): array
    {
        return [
            'member_id' => User::factory()->member(),
            'membership_id' => null,
            'pt_package_id' => null,
            'source' => 'purchase',
            'total_sessions' => 12,
            'used_sessions' => 0,
            'remaining_sessions' => 12,
            'start_date' => now()->startOfDay()->toDateString(),
            'end_date' => now()->addMonths(2)->endOfDay()->toDateString(),
            'status' => 'active',
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => now()->subMonths(2)->toDateString(),
            'end_date' => now()->subDay()->toDateString(),
            'status' => 'expired',
        ]);
    }

    public function depleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_sessions' => 12,
            'used_sessions' => 12,
            'remaining_sessions' => 0,
        ]);
    }
}
