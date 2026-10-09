<?php

namespace Database\Factories;

use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\PtPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberPtQuotaFactory extends Factory
{
    protected $model = MemberPtQuota::class;

    public function definition(): array
    {
        $total = fake()->randomElement([5, 10, 20]);
        $used = fake()->numberBetween(0, $total);
        $startDate = fake()->dateTimeBetween('-3 months', 'now');

        return [
            'member_id' => MemberProfile::factory(),
            'membership_id' => null,
            'pt_package_id' => PtPackage::factory(),
            'source' => 'package',
            'total_sessions' => $total,
            'used_sessions' => $used,
            'remaining_sessions' => $total - $used,
            'start_date' => $startDate,
            'end_date' => fake()->dateTimeBetween($startDate, '+3 months'),
            'status' => 'active',
        ];
    }
}
