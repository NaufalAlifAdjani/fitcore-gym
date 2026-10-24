<?php

namespace Database\Seeders;

use App\Models\MembershipPackage;
use Illuminate\Database\Seeder;

class MembershipPackageExtensionSeeder extends Seeder
{
    /**
     * Run the database seeds to add 10 extension membership packages.
     */
    public function run(): void
    {
        $packages = [
            // Paket 1: 14-Day Trial Pass
            [
                'name' => '14-Day Trial Pass',
                'description' => 'Paket uji coba akses 14 hari penuh',
                'tier' => 'Basic',
                'badge' => 'TRIAL',
                'duration_days' => 14,
                'duration_value' => 14,
                'duration_unit' => 'Hari',
                'duration_in_days' => 14,
                'price' => 150000,
                'promo_price' => null,
                'pt_sessions' => 0,
                'pt_session_count' => 0,
                'is_active' => true,
                'status' => 'active',
                'type' => 'basic',
                'facilities' => [
                    'Akses 14 hari penuh',
                    'Loker harian',
                ],
            ],

            // Paket 2: Off-Peak Monthly
            [
                'name' => 'Off-Peak Monthly',
                'description' => 'Akses jam 10:00 - 15:00 dengan harga hemat',
                'tier' => 'Basic',
                'badge' => 'OFF-PEAK',
                'duration_days' => 30,
                'duration_value' => 1,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 30,
                'price' => 300000,
                'promo_price' => null,
                'pt_sessions' => 0,
                'pt_session_count' => 0,
                'is_active' => true,
                'status' => 'active',
                'type' => 'basic',
                'facilities' => [
                    'Akses jam 10:00 - 15:00',
                    'Harga lebih hemat',
                ],
            ],

            // Paket 3: 3-Months Transformation
            [
                'name' => '3-Months Transformation',
                'description' => 'Program transformasi fisik 3 bulan bersama pelatih',
                'tier' => 'Standard',
                'badge' => 'TRANSFORMATION',
                'duration_days' => 90,
                'duration_value' => 3,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 90,
                'price' => 1350000,
                'promo_price' => null,
                'pt_sessions' => 3,
                'pt_session_count' => 3,
                'is_active' => true,
                'status' => 'active',
                'type' => 'standard',
                'facilities' => [
                    'Akses gym 24 jam',
                    '3 Sesi PT gratis',
                    'Body measurement',
                ],
            ],

            // Paket 4: Duo/Couple 6-Months
            [
                'name' => 'Duo/Couple 6-Months',
                'description' => 'Paket keanggotaan hemat berdua selama 6 bulan',
                'tier' => 'Standard',
                'badge' => 'COUPLE',
                'duration_days' => 180,
                'duration_value' => 6,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 180,
                'price' => 4000000,
                'promo_price' => null,
                'pt_sessions' => 4,
                'pt_session_count' => 4,
                'is_active' => true,
                'status' => 'active',
                'type' => 'standard',
                'facilities' => [
                    'Harga khusus berdua',
                    'Akses gym 24 jam',
                    '4 Sesi PT gratis',
                ],
            ],

            // Paket 5: Corporate Annual Pass
            [
                'name' => 'Corporate Annual Pass',
                'description' => 'Keanggotaan tahunan khusus staf & karyawan perusahaan',
                'tier' => 'Premium & VIP',
                'badge' => 'CORPORATE',
                'duration_days' => 365,
                'duration_value' => 12,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 365,
                'price' => 6000000,
                'promo_price' => null,
                'pt_sessions' => 10,
                'pt_session_count' => 10,
                'is_active' => true,
                'status' => 'active',
                'type' => 'vip',
                'facilities' => [
                    'ID Card Perusahaan Wajib',
                    'Akses VIP',
                    '10 Sesi PT',
                ],
            ],

            // Paket 6: Weekend Warrior
            [
                'name' => 'Weekend Warrior',
                'description' => 'Khusus latihan Sabtu & Minggu dan kelas studio weekend',
                'tier' => 'Basic',
                'badge' => 'WEEKEND',
                'duration_days' => 30,
                'duration_value' => 1,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 30,
                'price' => 250000,
                'promo_price' => null,
                'pt_sessions' => 0,
                'pt_session_count' => 0,
                'is_active' => true,
                'status' => 'active',
                'type' => 'basic',
                'facilities' => [
                    'Akses khusus Sabtu & Minggu',
                    'Kelas studio weekend',
                ],
            ],

            // Paket 7: Student Annual Pass
            [
                'name' => 'Student Annual Pass',
                'description' => 'Akses setahun penuh untuk pelajar & mahasiswa aktif',
                'tier' => 'Student Tier',
                'badge' => 'STUDENT',
                'duration_days' => 365,
                'duration_value' => 12,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 365,
                'price' => 3000000,
                'promo_price' => null,
                'pt_sessions' => 2,
                'pt_session_count' => 2,
                'is_active' => true,
                'status' => 'active',
                'type' => 'student',
                'facilities' => [
                    'Kartu Mahasiswa Wajib',
                    'Bebas jam akses',
                    '2 Sesi PT',
                ],
            ],

            // Paket 8: Yoga & Studio Only
            [
                'name' => 'Yoga & Studio Only',
                'description' => 'Akses tak terbatas ke semua kelas yoga dan studio',
                'tier' => 'Basic',
                'badge' => 'STUDIO ONLY',
                'duration_days' => 30,
                'duration_value' => 1,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 30,
                'price' => 350000,
                'promo_price' => null,
                'pt_sessions' => 0,
                'pt_session_count' => 0,
                'is_active' => true,
                'status' => 'active',
                'type' => 'basic',
                'facilities' => [
                    'Akses semua kelas studio',
                    'Tanpa weight-lifting',
                ],
            ],

            // Paket 9: Ultimate 2-Year VIP
            [
                'name' => 'Ultimate 2-Year VIP',
                'description' => 'Akses eksklusif 2 tahun penuh dengan pendampingan intensif',
                'tier' => 'Premium & VIP',
                'badge' => 'LIFETIME',
                'duration_days' => 730,
                'duration_value' => 24,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 730,
                'price' => 12000000,
                'promo_price' => null,
                'pt_sessions' => 24,
                'pt_session_count' => 24,
                'is_active' => true,
                'status' => 'active',
                'type' => 'vip',
                'facilities' => [
                    'Akses 2 tahun penuh',
                    '24 Sesi PT',
                    'Loker VIP',
                ],
            ],

            // Paket 10: Senior Health Pass
            [
                'name' => 'Senior Health Pass',
                'description' => 'Program kesehatan khusus lansia dengan bimbingan senam',
                'tier' => 'Basic',
                'badge' => 'AGE 55+',
                'duration_days' => 30,
                'duration_value' => 1,
                'duration_unit' => 'Bulan',
                'duration_in_days' => 30,
                'price' => 200000,
                'promo_price' => null,
                'pt_sessions' => 1,
                'pt_session_count' => 1,
                'is_active' => true,
                'status' => 'active',
                'type' => 'basic',
                'facilities' => [
                    'Wajib KTP (Usia 55+)',
                    'Program senam lansia',
                    '1 Sesi PT',
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
