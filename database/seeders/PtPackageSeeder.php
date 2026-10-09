<?php

namespace Database\Seeders;

use App\Models\PtPackage;
use Illuminate\Database\Seeder;

class PtPackageSeeder extends Seeder
{
    public function run(): void
    {
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
            );
        }
    }
}
