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
<<<<<<< HEAD
                'name' => 'Silver Monthly Pass',
                'badge' => 'SILVER',
                'tier' => 'Basic',
                'description' => 'Akses fleksibel hemat untuk pemula',
                'price' => 450000,
                'promo_price' => null,
                'duration_value' => 1,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 30,
                'facilities' => [
                    'Akses Gym Jam Tertentu (06.00 - 16.00)',
                    '1x Konsultasi Fitness Awal',
                ],
                'pt_sessions' => 0,
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
                'facilities' => [
                    'Akses Gym All-Hours Penuh',
                    'Free All Regular Studio Classes',
                    '2x Sesi Personal Trainer Gratis',
                    'Free Locker Harian',
                ],
                'pt_sessions' => 2,
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
                'facilities' => [
                    'Akses Gym Tanpa Batas 24/7 di Semua Cabang',
                    'Akses Semua Studio Class & HIIT',
                    '5x Sesi 1-on-1 PT Gratis',
                    'Locker Permanen + Free Towel Service',
                    'Akses Sauna & Ice Bath Recovery',
                ],
                'pt_sessions' => 5,
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
                'facilities' => [
                    'All-Branch VIP Priority Access',
                    'Unlimited Class Priority Fast-Pass',
                    '12x Sesi Personal Trainer (Exclusive)',
                    'Private VIP Lounge & Valet Parking',
                    'Suplemen Starter Pack & Towel Premium',
                ],
                'pt_sessions' => 12,
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
                'facilities' => [
                    'Akses Gym Weekdays (09:00 - 17:00)',
                    'Kartu Mahasiswa Aktif Wajib',
                ],
                'pt_sessions' => 0,
                'is_active' => false,
            ],
        ];

        foreach ($packages as $pkg) {
            MembershipPackage::create($pkg);
=======
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
>>>>>>> origin/develop
        }
    }
}
