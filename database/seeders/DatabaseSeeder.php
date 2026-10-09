<?php

namespace Database\Seeders;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BankSeeder::class,
            MembershipPackageSeeder::class,
            PtPackageSeeder::class,
        ]);

        User::query()->updateOrCreate(
            ['email' => 'admin@fitcore.test'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081200000001',
                'status' => 'active',
            ],
        );

        $member = User::query()->updateOrCreate(
            ['email' => 'member@fitcore.test'],
            [
                'name' => 'Member Utama',
                'password' => Hash::make('password123'),
                'role' => 'member',
                'phone' => '081200000002',
                'status' => 'active',
            ],
        );

        MemberProfile::query()->updateOrCreate(
            ['user_id' => $member->id],
            ['member_code' => 'FC-'.str_pad((string) $member->id, 6, '0', STR_PAD_LEFT)],
        );

        $this->call(PaymentSeeder::class);
    }
}
