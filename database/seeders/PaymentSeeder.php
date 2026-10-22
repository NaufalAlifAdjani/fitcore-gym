<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $member = User::query()->where('role', 'member')->oldest('id')->firstOrFail();
        $admin = User::query()->where('role', 'admin')->oldest('id')->firstOrFail();
        $membershipPackage = MembershipPackage::query()->oldest('id')->firstOrFail();
        $ptPackageFiveSessions = PtPackage::query()
            ->where('name', 'Personal Trainer - 5 Sesi')
            ->firstOrFail();
        $ptPackageTenSessions = PtPackage::query()
            ->where('name', 'Personal Trainer - 10 Sesi')
            ->firstOrFail();

        $banks = collect(['BCA', 'BRI', 'BNI', 'Mandiri'])
            ->mapWithKeys(fn (string $name) => [
                $name => Bank::query()->firstOrCreate(
                    ['name' => $name],
                    [
                        'account_number' => '0000000000',
                        'account_holder' => 'Fitcore Gym',
                        'status' => 'active',
                    ],
                ),
            ]);

        $now = now();
        $payments = [
            [
                'invoice_id' => 'INV-GYM-2026-0001',
                'package_type' => 'membership',
                'package_id' => $membershipPackage->id,
                'payment_type' => 'membership',
                'amount' => 450000,
                'bank_sender' => 'BCA',
                'status' => 'pending',
                'rejection_reason' => null,
                'verified_by' => null,
                'verified_at' => null,
                'transfer_date' => $now->copy()->subDays(2),
            ],
            [
                'invoice_id' => 'INV-GYM-2026-0002',
                'package_type' => 'pt_session',
                'package_id' => $ptPackageFiveSessions->id,
                'pt_package_id' => $ptPackageFiveSessions->id,
                'payment_type' => 'pt_package',
                'amount' => 300000,
                'bank_sender' => 'BRI',
                'status' => 'verified',
                'rejection_reason' => null,
                'verified_by' => $admin->id,
                'verified_at' => $now->copy()->subDay(),
                'transfer_date' => $now->copy()->subDays(3),
            ],
            [
                'invoice_id' => 'INV-GYM-2026-0003',
                'package_type' => 'membership',
                'package_id' => $membershipPackage->id,
                'payment_type' => 'membership',
                'amount' => 450000,
                'bank_sender' => 'BNI',
                'status' => 'rejected',
                'rejection_reason' => 'Bukti transfer tidak menampilkan nominal dengan jelas.',
                'verified_by' => $admin->id,
                'verified_at' => $now->copy()->subHours(12),
                'transfer_date' => $now->copy()->subDays(4),
            ],
            [
                'invoice_id' => 'INV-GYM-2026-0004',
                'package_type' => 'pt_session',
                'package_id' => $ptPackageTenSessions->id,
                'pt_package_id' => $ptPackageTenSessions->id,
                'payment_type' => 'pt_package',
                'amount' => 600000,
                'bank_sender' => 'Mandiri',
                'status' => 'pending',
                'rejection_reason' => null,
                'verified_by' => null,
                'verified_at' => null,
                'transfer_date' => $now->copy()->subHours(6),
            ],
            [
                'invoice_id' => 'INV-GYM-2026-0005',
                'package_type' => 'membership',
                'package_id' => $membershipPackage->id,
                'payment_type' => 'membership',
                'amount' => 450000,
                'bank_sender' => 'BCA',
                'status' => 'verified',
                'rejection_reason' => null,
                'verified_by' => $admin->id,
                'verified_at' => $now->copy()->subHours(3),
                'transfer_date' => $now->copy()->subDays(5),
            ],
        ];

        foreach ($payments as $payment) {
            Payment::query()->updateOrCreate(
                ['invoice_id' => $payment['invoice_id']],
                [
                    ...$payment,
                    'member_id' => $member->id,
                    'bank_id' => $banks->get($payment['bank_sender'])->id,
                    'bank_destination' => 'Mandiri',
                    'proof_image_url' => 'https://example.test/receipts/'.$payment['invoice_id'].'.jpg',
                ],
            );
        }
    }
}
