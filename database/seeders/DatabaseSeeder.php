<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@fitcore.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // User Trainer
        User::create([
            'name' => 'Trainer Utama',
            'email' => 'trainer@fitcore.test',
            'password' => Hash::make('password123'),
            'role' => 'trainer',
        ]);

        // User Member
        User::create([
            'name' => 'Member Utama',
            'email' => 'member@fitcore.test',
            'password' => Hash::make('password123'),
            'role' => 'member',
        ]);

        $this->call([
            MembershipPackageSeeder::class,
        ]);
    }
}