<?php

namespace Database\Factories;

use App\Models\CheckIn;
use App\Models\MemberProfile;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CheckInFactory extends Factory
{
    protected $model = CheckIn::class;

    public function definition(): array
    {
        $checkedIn = fake()->dateTimeBetween('-1 month', 'now');
        $checkedOut = (clone $checkedIn)->modify('+'.fake()->numberBetween(60, 180).' minutes');
        $checkedInCarbon = Carbon::instance($checkedIn)->timezone('Asia/Jakarta');

        return [
            'member_id'     => MemberProfile::factory(),
            'membership_id' => Membership::factory(),
            'check_in_date' => $checkedInCarbon->toDateString(),
            'checked_in_at' => $checkedIn,
            'checked_out_at' => fake()->randomElement([$checkedOut, null]),
            'method'        => fake()->randomElement(['manual', 'qr_code']),
        ];
    }
}
