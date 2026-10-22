<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Throwable;

class PaymentDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('role', 'admin')->first();
        if (! $admin) {
            $admin = User::query()->create([
                'name' => 'Admin FitCore',
                'email' => 'admin_dummy_'.uniqid().'@fitcore.test',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        $defaultMember = User::query()->where('role', 'member')->first();
        if (! $defaultMember) {
            $defaultMember = User::query()->create([
                'name' => 'Member FitCore',
                'email' => 'member_dummy_'.uniqid().'@fitcore.test',
                'password' => bcrypt('password'),
                'role' => 'member',
            ]);
        }

        $memberIds = User::query()->where('role', 'member')->pluck('id');

        $banks = ['BCA', 'Mandiri', 'BRI', 'BNI'];
        foreach ($banks as $bankName) {
            Bank::query()->firstOrCreate(
                ['name' => $bankName],
                [
                    'account_number' => '1234567890',
                    'account_holder' => 'FitCore Gym',
                    'status' => 'active',
                ]
            );
        }

        $createdCount = 0;

        for ($i = 0; $i < 600; $i++) {
            try {
                $memberId = $memberIds->isNotEmpty() ? $memberIds->random() : $defaultMember->id;
                $bankName = fake()->randomElement($banks);
                $bank = Bank::query()->where('name', $bankName)->first() ?? Bank::query()->first();

                // Nominal acak antara 250.000 hingga 3.500.000 (kelipatan 50.000)
                $amount = rand(5, 70) * 50_000;

                // Logika tanggal created_at tersebar acak selama 365 hari ke belakang
                $createdAt = now()->subDays(rand(0, 365))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

                // Probabilitas status: 85% verified, 10% pending, 5% rejected
                $statusRoll = rand(1, 100);
                if ($statusRoll <= 85) {
                    $status = 'verified';
                    $verifiedAt = $createdAt->copy()->addHours(rand(1, 24));
                    $verifiedBy = $admin->id;
                    $rejectionReason = null;
                } elseif ($statusRoll <= 95) {
                    $status = 'pending';
                    $verifiedAt = null;
                    $verifiedBy = null;
                    $rejectionReason = null;
                } else {
                    $status = 'rejected';
                    $verifiedAt = null;
                    $verifiedBy = $admin->id;
                    $rejectionReason = 'Bukti transfer tidak valid atau dana belum masuk ke rekening.';
                }

                do {
                    $invoiceId = 'INV-DUMMY-'.rand(1000, 9999).'-'.rand(100, 999);
                } while (Payment::query()->where('invoice_id', $invoiceId)->exists());

                Payment::query()->create([
                    'invoice_id' => $invoiceId,
                    'member_id' => $memberId,
                    'membership_id' => null,
                    'pt_package_id' => null,
                    'bank_id' => $bank->id,
                    'payment_type' => fake()->randomElement(['membership', 'pt_package']),
                    'package_id' => null,
                    'package_type' => 'membership',
                    'amount' => $amount,
                    'bank_sender' => $bankName,
                    'bank_destination' => 'FitCore '.$bankName,
                    'proof_image_path' => null,
                    'proof_image_url' => null,
                    'transfer_date' => $createdAt->copy()->subMinutes(rand(5, 30)),
                    'status' => $status,
                    'rejection_reason' => $rejectionReason,
                    'verified_by' => $verifiedBy,
                    'verified_at' => $verifiedAt,
                    'created_at' => $createdAt,
                    'updated_at' => $verifiedAt ?? $createdAt,
                ]);

                $createdCount++;
            } catch (Throwable $e) {
                // Ignore Foreign Key or constraint errors and continue
            }
        }

        $this->command?->info("PaymentDummySeeder selesai: {$createdCount} transaksi pembayaran berhasil dibuat.");
    }
}
