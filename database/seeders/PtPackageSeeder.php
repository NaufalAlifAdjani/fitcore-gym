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
                'name' => 'PT Per-Visit',
                'pt_session_count' => 1,
                'price' => 150000,
                'min_membership_days' => 0,
                'validity_days' => 14,
            ],
            [
                'name' => 'PT 5 Sesi',
                'pt_session_count' => 5,
                'price' => 500000,
                'min_membership_days' => 30,
                'validity_days' => 60,
            ],
            [
                'name' => 'PT 8 Sesi',
                'pt_session_count' => 8,
                'price' => 900000,
                'min_membership_days' => 14,
                'validity_days' => 30,
            ],
            [
                'name' => 'PT 10 Sesi',
                'pt_session_count' => 10,
                'price' => 900000,
                'min_membership_days' => 30,
                'validity_days' => 90,
            ],
            [
                'name' => 'PT 12 Sesi',
                'pt_session_count' => 12,
                'price' => 1300000,
                'min_membership_days' => 20,
                'validity_days' => 45,
            ],
            [
                'name' => 'PT 16 Sesi',
                'pt_session_count' => 16,
                'price' => 1700000,
                'min_membership_days' => 30,
                'validity_days' => 60,
            ],
            [
                'name' => 'PT 20 Sesi',
                'pt_session_count' => 20,
                'price' => 2000000,
                'min_membership_days' => 30,
                'validity_days' => 60,
            ],
        ];

        foreach ($packages as $package) {
            PtPackage::updateOrCreate(
                ['name' => $package['name']],
                $package + ['status' => 'active']
            );
        }
    }
}
