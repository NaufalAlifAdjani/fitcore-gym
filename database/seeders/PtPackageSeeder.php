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
                'name' => 'PT 4 Sesi',
                'pt_session_count' => 4,
                'price' => 1155000,
                'min_membership_days' => 0,
                'validity_days' => 30,
            ],
            [
                'name' => 'PT 8 Sesi',
                'pt_session_count' => 8,
                'price' => 2072700,
                'min_membership_days' => 14,
                'validity_days' => 45,
            ],
            [
                'name' => 'PT 16 Sesi',
                'pt_session_count' => 16,
                'price' => 3582000,
                'min_membership_days' => 30,
                'validity_days' => 60,
            ],
            [
                'name' => 'PT 24 Sesi',
                'pt_session_count' => 24,
                'price' => 5040000,
                'min_membership_days' => 30,
                'validity_days' => 90,
            ],
            [
                'name' => 'PT 36 Sesi',
                'pt_session_count' => 36,
                'price' => 6804000,
                'min_membership_days' => 30,
                'validity_days' => 120,
            ],
            [
                'name' => 'PT 48 Sesi',
                'pt_session_count' => 48,
                'price' => 8316000,
                'min_membership_days' => 30,
                'validity_days' => 150,
            ],
            [
                'name' => 'PT 72 Sesi',
                'pt_session_count' => 72,
                'price' => 12168000,
                'min_membership_days' => 30,
                'validity_days' => 180,
            ],
            [
                'name' => 'PT 96 Sesi',
                'pt_session_count' => 96,
                'price' => 15840000,
                'min_membership_days' => 30,
                'validity_days' => 240,
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
