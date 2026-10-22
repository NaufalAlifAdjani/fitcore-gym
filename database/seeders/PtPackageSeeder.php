<?php

namespace Database\Seeders;

use App\Models\PtPackage;
use Illuminate\Database\Seeder;

class PtPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Personal Trainer - 5 Sesi',
                'pt_session_count' => 5,
                'price' => 300000,
                'min_membership_days' => 0,
                'validity_days' => 60,
                'status' => 'active',
            ],
            [
                'name' => 'Personal Trainer - 10 Sesi',
                'pt_session_count' => 10,
                'price' => 600000,
                'min_membership_days' => 0,
                'validity_days' => 90,
                'status' => 'active',
            ],
            [
                'name' => 'Personal Trainer - 20 Sesi',
                'pt_session_count' => 20,
                'price' => 1100000,
                'min_membership_days' => 0,
                'validity_days' => 180,
                'status' => 'active',
            ],
        ];

        foreach ($packages as $package) {
            PtPackage::query()->updateOrCreate(
                ['name' => $package['name']],
                $package,
            );
        }
    }
}
