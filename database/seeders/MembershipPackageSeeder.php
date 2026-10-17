<?php

namespace Database\Seeders;

use App\Models\MembershipPackage;
use Illuminate\Database\Seeder;

class MembershipPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic 1 Bulan',
                'type' => 'basic',
                'duration_days' => 30,
                'price' => 250000,
                'facilities' => 'Akses alat gym, loker, area kardio',
                'pt_session_count' => 0,
            ],
            [
                'name' => 'Premium 3 Bulan',
                'type' => 'premium',
                'duration_days' => 90,
                'price' => 650000,
                'facilities' => 'Akses alat gym, loker, area kardio, kelas grup',
                'pt_session_count' => 2,
            ],
            [
                'name' => 'VIP 12 Bulan',
                'type' => 'vip',
                'duration_days' => 365,
                'price' => 2200000,
                'facilities' => 'Semua fasilitas, kelas grup, handuk, sauna',
                'pt_session_count' => 8,
            ],
        ];

        foreach ($packages as $package) {
            MembershipPackage::updateOrCreate(
                ['name' => $package['name']],
                $package + ['status' => 'active']
            );
        }
    }
}
