<?php

namespace Database\Seeders;

use App\Models\MembershipPackage;
use Illuminate\Database\Seeder;
use Throwable;

class MembershipPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel (opsional) sebelum insert jika tidak ada foreign key constraints
        try {
            MembershipPackage::query()->delete();
        } catch (Throwable) {
            // Jika ada foreign key yang mengikat (misal pembayaran aktif),
            // seeder akan memperbarui data via updateOrCreate agar tidak ganda.
        }

        // 5 Data Paket Keanggotaan Sesuai Spesifikasi Portal Admin FitCore
        $packages = [
            // Paket 1: Silver Monthly Pass
            [
                'name' => 'Silver Monthly Pass',
                'description' => 'Akses fleksibel hemat untuk pemula',
                'tier' => 'Basic',
                'badge' => 'SILVER',
                'duration_days' => 30,
                'duration_value' => 1,
                'duration_unit' => 'Bulan',
                'price' => 450000,
                'promo_price' => null,
                'pt_session_count' => 0,
                'is_active' => true,
                'type' => 'basic',
                'facilities' => [
                    'Akses gym jam tertentu',
                    'Konsultasi fitness awal',
                ],
            ],

            // Paket 2: Gold 6-Months Pass
            [
                'name' => 'Gold 6-Months Pass',
                'description' => 'Komitmen setengah tahun paling seimbang',
                'tier' => 'Standard',
                'badge' => 'GOLD STANDARD',
                'duration_days' => 180,
                'duration_value' => 6,
                'duration_unit' => 'Bulan',
                'price' => 2400000,
                'promo_price' => null,
                'pt_session_count' => 0,
                'is_active' => true,
                'type' => 'standard',
                'facilities' => [
                    'Akses gym 24 jam',
                    'Kelas studio',
                    'Loker harian & shower panas',
                ],
            ],

            // Paket 3: Gold Annual Pass (Best Seller & Promo)
            [
                'name' => 'Gold Annual Pass',
                'description' => 'Pilihan favorit atlet & komitmen penuh',
                'tier' => 'Standard',
                'badge' => 'BEST SELLER',
                'duration_days' => 365,
                'duration_value' => 12,
                'duration_unit' => 'Bulan',
                'price' => 5400000, // Harga Asli (Original Price)
                'promo_price' => 4200000, // Harga Promo (Diskon)
                'pt_session_count' => 0,
                'is_active' => true,
                'type' => 'premium',
                'facilities' => [
                    'Akses gym semua cabang',
                    'Kelas studio',
                    'Loker VIP & handuk latihan',
                ],
            ],

            // Paket 4: Platinum All-Access VIP
            [
                'name' => 'Platinum All-Access VIP',
                'description' => 'Pengalaman premium kelas eksekutif',
                'tier' => 'Premium & VIP',
                'badge' => 'PLATINUM VIP',
                'duration_days' => 365,
                'duration_value' => 12,
                'duration_unit' => 'Bulan',
                'price' => 7500000,
                'promo_price' => null,
                'pt_session_count' => 0,
                'is_active' => true,
                'type' => 'vip',
                'facilities' => [
                    'Akses semua cabang',
                    'Kelas prioritas',
                    'Akses sauna & ice bath',
                ],
            ],

            // Paket 5: Student Semester Pass
            [
                'name' => 'Student Semester Pass',
                'description' => 'Khusus pelajar & mahasiswa terverifikasi',
                'tier' => 'Student Tier',
                'badge' => 'STUDENT TIER',
                'duration_days' => 150,
                'duration_value' => 5,
                'duration_unit' => 'Bulan',
                'price' => 1600000,
                'promo_price' => null,
                'pt_session_count' => 0,
                'is_active' => false,
                'type' => 'student',
                'facilities' => [
                    'Akses gym weekdays',
                    'Kartu mahasiswa aktif wajib',
                ],
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
