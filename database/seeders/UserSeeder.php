<?php

namespace Database\Seeders;

use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gym.test'],
            [
                'name'     => 'Admin Gym',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'phone'    => '081200000001',
                'status'   => 'active',
            ]
        );

        // Trainer
        $trainers = [
            [
                'name'           => 'Budi Santoso',
                'email'          => 'budi@gym.test',
                'phone'          => '081200000002',
                'specialization' => 'Muscle Building',
                'bio'            => 'Berpengalaman melatih program hipertrofi dan strength.',
                'experience'     => 5,
            ],
            [
                'name'           => 'Sari Wulandari',
                'email'          => 'sari@gym.test',
                'phone'          => '081200000003',
                'specialization' => 'Weight Loss & Cardio',
                'bio'            => 'Fokus pada program penurunan berat badan dan kebugaran.',
                'experience'     => 3,
            ],
        ];

        foreach ($trainers as $t) {
            $user = User::updateOrCreate(
                ['email' => $t['email']],
                [
                    'name'     => $t['name'],
                    'password' => Hash::make('password123'),
                    'role'     => 'trainer',
                    'phone'    => $t['phone'],
                    'status'   => 'active',
                ]
            );

            TrainerProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $t['specialization'],
                    'bio'            => $t['bio'],
                    'experience'     => $t['experience'],
                    'status'         => 'active',
                ]
            );
        }
    }
}
