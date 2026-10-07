<?php

namespace Database\Factories;

use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PtSession>
 */
class PtSessionFactory extends Factory
{
    protected $model = PtSession::class;

    public function definition(): array
    {
        return [
            'member_pt_quota_id' => MemberPtQuota::factory(),
            'member_id' => function (array $attributes) {
                return MemberPtQuota::find($attributes['member_pt_quota_id'])->member_id ?? User::factory()->member();
            },
            'trainer_id' => User::factory()->trainer(),
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'scheduled',
        ];
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
        ]);
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'session_date' => now()->subDays(2)->toDateString(),
            'status' => 'done',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
