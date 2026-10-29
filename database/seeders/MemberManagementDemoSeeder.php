<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\CheckIn;
use App\Models\MemberProfile;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberManagementDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan paket-paket membership tersedia
        $premiumAnnual = MembershipPackage::firstOrCreate(
            ['name' => 'Premium Annual'],
            [
                'badge' => 'ALL-ACCESS',
                'tier' => 'Premium & VIP',
                'description' => 'Akses penuh seluruh cabang 24/7',
                'price' => 3200000,
                'duration_days' => 365,
                'duration_in_days' => 365,
                'is_active' => true,
            ]
        );

        $standard6Bln = MembershipPackage::firstOrCreate(
            ['name' => 'Standard 6 Bln'],
            [
                'badge' => 'STANDARD',
                'tier' => 'Standard',
                'description' => 'Akses 6 bulan fasilitas gym',
                'price' => 1800000,
                'duration_days' => 180,
                'duration_in_days' => 180,
                'is_active' => true,
            ]
        );

        $studentPass = MembershipPackage::firstOrCreate(
            ['name' => 'Student Pass'],
            [
                'badge' => 'STUDENT',
                'tier' => 'Basic',
                'description' => 'Paket hemat pelajar/mahasiswa',
                'price' => 300000,
                'duration_days' => 30,
                'duration_in_days' => 30,
                'is_active' => true,
            ]
        );

        $basicMonthly = MembershipPackage::firstOrCreate(
            ['name' => 'Basic Monthly'],
            [
                'badge' => 'BASIC',
                'tier' => 'Basic',
                'description' => 'Akses bulanan reguler',
                'price' => 450000,
                'duration_days' => 30,
                'duration_in_days' => 30,
                'is_active' => true,
            ]
        );

        $bank = Bank::first() ?? Bank::create([
            'name' => 'BCA',
            'code' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT Fitcore Gym Indonesia',
            'is_active' => true,
        ]);

        $membersData = [
            [
                'name' => 'Amanda Putri',
                'email' => 'amanda.putri@gmail.com',
                'phone' => '081298765432',
                'gender' => 'Perempuan',
                'member_code' => '#FC-8921',
                'package' => $premiumAnnual,
                'start_date' => now()->subDays(361)->toDateString(),
                'end_date' => now()->addDays(4)->toDateString(),
                'status' => 'active',
                'checkins' => [
                    now()->subDays(1)->setTime(18, 45),
                    now()->subDays(3)->setTime(7, 15),
                    now()->subDays(5)->setTime(19, 10),
                ],
                'payments' => [
                    [
                        'invoice_id' => 'INV-2024-0091',
                        'amount' => 3200000,
                        'payment_type' => 'membership',
                        'bank_destination' => 'BCA Virtual Account',
                        'status' => 'verified',
                        'created_at' => now()->subDays(361),
                    ],
                    [
                        'invoice_id' => 'INV-2024-0312',
                        'amount' => 1200000,
                        'payment_type' => 'pt_package',
                        'bank_destination' => 'QRIS Instant Pay',
                        'status' => 'verified',
                        'created_at' => now()->subDays(180),
                    ],
                ],
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@corporate.id',
                'phone' => '081211112222',
                'gender' => 'Laki-laki',
                'member_code' => '#FC-1042',
                'package' => $standard6Bln,
                'start_date' => now()->subDays(60)->toDateString(),
                'end_date' => now()->addDays(120)->toDateString(),
                'status' => 'active',
                'checkins' => [
                    now()->subDays(2)->setTime(17, 30),
                ],
                'payments' => [],
            ],
            [
                'name' => 'Jessica Wong',
                'email' => 'jessica.wong@yahoo.com',
                'phone' => '081233334444',
                'gender' => 'Perempuan',
                'member_code' => '#FC-3391',
                'package' => $premiumAnnual,
                'start_date' => now()->subDays(90)->toDateString(),
                'end_date' => now()->addDays(275)->toDateString(),
                'status' => 'active',
                'checkins' => [
                    now()->subDays(1)->setTime(19, 00),
                ],
                'payments' => [],
            ],
            [
                'name' => 'Dimas Arya',
                'email' => 'dimas.arya@outlook.com',
                'phone' => '081255556666',
                'gender' => 'Laki-laki',
                'member_code' => '#FC-5520',
                'package' => $studentPass,
                'start_date' => now()->subDays(60)->toDateString(),
                'end_date' => now()->subDays(30)->toDateString(),
                'status' => 'inactive',
                'checkins' => [],
                'payments' => [],
            ],
            [
                'name' => 'Farhan Maulana',
                'email' => 'farhan.m@gmail.com',
                'phone' => '081277778888',
                'gender' => 'Laki-laki',
                'member_code' => '#FC-7714',
                'package' => $basicMonthly,
                'start_date' => now()->subDays(10)->toDateString(),
                'end_date' => now()->addDays(20)->toDateString(),
                'status' => 'active',
                'checkins' => [
                    now()->subDays(3)->setTime(18, 00),
                ],
                'payments' => [],
            ],
        ];

        foreach ($membersData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'member',
                    'phone' => $data['phone'],
                    'status' => $data['status'],
                    'email_verified_at' => now(),
                ]
            );

            MemberProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'member_code' => $data['member_code'],
                    'gender' => $data['gender'],
                    'address' => 'Jl. Jenderal Sudirman No. 45, Jakarta Selatan',
                ]
            );

            $membership = Membership::updateOrCreate(
                [
                    'member_id' => $user->id,
                    'membership_package_id' => $data['package']->id,
                ],
                [
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'status' => $data['status'],
                ]
            );

            foreach ($data['checkins'] as $checkinTime) {
                CheckIn::firstOrCreate(
                    [
                        'member_id' => $user->id,
                        'membership_id' => $membership->id,
                        'checked_in_at' => $checkinTime,
                    ],
                    [
                        'method' => 'qr',
                        'checked_out_at' => (clone $checkinTime)->addHours(2),
                    ]
                );
            }

            foreach ($data['payments'] as $pData) {
                Payment::firstOrCreate(
                    ['invoice_id' => $pData['invoice_id']],
                    [
                        'member_id' => $user->id,
                        'membership_id' => $membership->id,
                        'bank_id' => $bank->id,
                        'payment_type' => $pData['payment_type'],
                        'amount' => $pData['amount'],
                        'bank_destination' => $pData['bank_destination'],
                        'status' => $pData['status'],
                        'verified_at' => $pData['created_at'],
                        'created_at' => $pData['created_at'],
                    ]
                );
            }
        }
    }
}
