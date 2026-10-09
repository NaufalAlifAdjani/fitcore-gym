<?php

namespace Database\Seeders;

use App\Models\PtPackage;
use Illuminate\Database\Seeder;

class PtPackageSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        foreach ([
            [
                'name' => 'Personal Trainer - 5 Sesi',
                'description' => 'Paket latihan personal lima sesi.',
                'sessions_count' => 5,
                'price' => 300000,
                'is_active' => true,
            ],
            [
                'name' => 'Personal Trainer - 10 Sesi',
                'description' => 'Paket latihan personal sepuluh sesi.',
                'sessions_count' => 10,
                'price' => 600000,
                'is_active' => true,
            ],
        ] as $package) {
            PtPackage::query()->updateOrCreate(
                ['name' => $package['name']],
                $package,
=======
        $packages = [
            [
                'name' => 'PT 5 Sesi',
                'pt_session_count' => 5,
                'price' => 500000,
                'min_membership_days' => 30,
                'validity_days' => 60,
            ],
            [
                'name' => 'PT 10 Sesi',
                'pt_session_count' => 10,
                'price' => 900000,
                'min_membership_days' => 30,
                'validity_days' => 90,
            ],
            [
                'name' => 'PT 20 Sesi',
                'pt_session_count' => 20,
                'price' => 1600000,
                'min_membership_days' => 60,
                'validity_days' => 180,
            ],
        ];

        foreach ($packages as $package) {
            PtPackage::updateOrCreate(
                ['name' => $package['name']],
                $package + ['status' => 'active']
>>>>>>> origin/develop
            );
        }
    }
}
