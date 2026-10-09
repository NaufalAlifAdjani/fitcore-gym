<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            ['name' => 'BCA',     'account_number' => '1234567890', 'account_holder' => 'PT Gym Sehat'],
            ['name' => 'Mandiri', 'account_number' => '9876543210', 'account_holder' => 'PT Gym Sehat'],
            ['name' => 'BRI',     'account_number' => '1122334455', 'account_holder' => 'PT Gym Sehat'],
        ];

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['name' => $bank['name'], 'account_number' => $bank['account_number']],
                $bank + ['status' => 'active']
            );
        }
    }
}
