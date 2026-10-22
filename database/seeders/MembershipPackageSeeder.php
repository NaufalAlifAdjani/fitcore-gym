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
                'name' => 'Silver Monthly Pass',
                'badge' => 'SILVER',
                'tier' => 'Basic',
                'description' => 'Akses fleksibel hemat untuk pemula',
                'price' => 450000,
                'promo_price' => null,
                'duration_value' => 1,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 30,
                'duration_days' => 30,
                'facilities' => ['Akses gym jam tertentu', 'Konsultasi fitness awal'],
                'pt_sessions' => 0,
                'pt_session_count' => 0,
                'type' => 'basic',
                'status' => 'active',
                'is_active' => true,
            ],
            [
                'name' => 'Gold 6-Months Pass',
                'badge' => 'GOLD STANDARD',
                'tier' => 'Standard',
                'description' => 'Komitmen setengah tahun paling seimbang',
                'price' => 2400000,
                'promo_price' => null,
                'duration_value' => 6,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 180,
                'duration_days' => 180,
                'facilities' => ['Akses gym 24 jam', 'Kelas studio', '2 sesi PT gratis'],
                'pt_sessions' => 2,
                'pt_session_count' => 2,
                'type' => 'standard',
                'status' => 'active',
                'is_active' => true,
            ],
            [
                'name' => 'Gold Annual Pass',
                'badge' => 'BEST SELLER',
                'tier' => 'Premium & VIP',
                'description' => 'Pilihan favorit atlet & komitmen penuh',
                'price' => 5400000,
                'promo_price' => 4200000,
                'duration_value' => 12,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 365,
                'duration_days' => 365,
                'facilities' => ['Akses gym semua cabang', 'Kelas studio', '5 sesi PT gratis'],
                'pt_sessions' => 5,
                'pt_session_count' => 5,
                'type' => 'premium',
                'status' => 'active',
                'is_active' => true,
            ],
            [
                'name' => 'Platinum All-Access VIP',
                'badge' => 'PLATINUM VIP',
                'tier' => 'Premium & VIP',
                'description' => 'Pengalaman premium kelas eksekutif',
                'price' => 7500000,
                'promo_price' => null,
                'duration_value' => 12,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 365,
                'duration_days' => 365,
                'facilities' => ['Akses semua cabang', 'Kelas prioritas', '12 sesi PT eksklusif'],
                'pt_sessions' => 12,
                'pt_session_count' => 12,
                'type' => 'vip',
                'status' => 'active',
                'is_active' => true,
            ],
            [
                'name' => 'Student Semester Pass',
                'badge' => 'STUDENT TIER',
                'tier' => 'Basic',
                'description' => 'Khusus pelajar & mahasiswa terverifikasi',
                'price' => 1600000,
                'promo_price' => null,
                'duration_value' => 5,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 150,
                'duration_days' => 150,
                'facilities' => ['Akses gym weekdays', 'Kartu mahasiswa aktif wajib'],
                'pt_sessions' => 0,
                'pt_session_count' => 0,
                'type' => 'student',
                'status' => 'inactive',
                'is_active' => false,
            ],
        ];

        foreach ($packages as $package) {
            MembershipPackage::query()->updateOrCreate(
                ['name' => $package['name']],
                $package,
            );
        }
    }
}
