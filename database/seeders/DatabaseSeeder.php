<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\CheckIn;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\Membership;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtPackage;
use App\Models\PtSession;
use App\Models\Rating;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Users (2)
        User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@fitcore.test',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);
        User::factory()->create([
            'name' => 'Admin Kedua',
            'email' => 'admin2@fitcore.test',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        // 2. Master Data
        $banks = Bank::factory(5)->create();

        $membershipPackages = [
            MembershipPackage::factory()->create(['name' => 'Basic Package', 'duration_days' => 30, 'price' => 200000]),
            MembershipPackage::factory()->create(['name' => 'Pro Package', 'duration_days' => 90, 'price' => 500000]),
            MembershipPackage::factory()->create(['name' => 'Elite Package', 'duration_days' => 365, 'price' => 1800000]),
        ];

        $ptPackages = [
            PtPackage::factory()->create(['name' => '5 Sessions', 'pt_session_count' => 5, 'price' => 750000]),
            PtPackage::factory()->create(['name' => '10 Sessions', 'pt_session_count' => 10, 'price' => 1400000]),
            PtPackage::factory()->create(['name' => '20 Sessions', 'pt_session_count' => 20, 'price' => 2500000]),
        ];

        // 3. Trainer Users (10)
        // Buat 1 akun trainer statis agar mudah digunakan untuk testing login
        $trainerUser = User::firstOrCreate(
            ['email' => 'trainer@fitcore.test'],
            [
                'name' => 'Trainer Utama',
                'role' => 'trainer',
                'password' => Hash::make('password123'),
                'phone' => '081299990005',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        TrainerProfile::firstOrCreate(['user_id' => $trainerUser->id]);

        // Sisa 9 trainer di-generate secara random
        $trainers = User::factory(9)->create(['role' => 'trainer'])->each(function ($user) {
            TrainerProfile::factory()->create(['user_id' => $user->id]);
        });

        $trainerProfiles = TrainerProfile::all();

        // 4. Member Users (40)
        // Buat 1 akun member statis agar mudah digunakan untuk testing login
        $memberUser = User::firstOrCreate(
            ['email' => 'member@fitcore.test'],
            [
                'name' => 'Member Utama',
                'role' => 'member',
                'password' => Hash::make('password123'),
                'phone' => '081299990015',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        MemberProfile::firstOrCreate(
            ['user_id' => $memberUser->id],
            [
                'member_code' => 'MEM000001',
                'gender' => 'male',
                'address' => 'Jakarta Selatan',
            ]
        );

        // Sisa 39 member di-generate secara random
        $members = User::factory(39)->create(['role' => 'member'])->each(function ($user) {
            MemberProfile::factory()->create(['user_id' => $user->id]);
        });

        $memberProfiles = MemberProfile::all();

        // 5. Memberships, Payments & Quotas
        foreach ($memberProfiles as $index => $member) {
            // Every member gets 1 membership history
            $package = $membershipPackages[array_rand($membershipPackages)];
            $membership = Membership::factory()->create([
                'member_id' => $member->id, // Assumes member_id is member_profiles.id based on factory
                'membership_package_id' => $package->id,
            ]);

            Payment::factory()->create([
                'member_id' => $member->id,
                'amount' => $package->price,
                'bank_id' => $banks->random()->id,
                'payment_type' => 'membership',
                'membership_id' => $membership->id,
            ]);

            // Only half of members get a PT quota
            if ($index % 2 === 0) {
                $ptPkg = $ptPackages[array_rand($ptPackages)];
                MemberPtQuota::factory()->create([
                    'member_id' => $member->id,
                    'pt_package_id' => $ptPkg->id,
                    'total_sessions' => $ptPkg->pt_session_count,
                    'used_sessions' => rand(0, $ptPkg->pt_session_count),
                ]);

                Payment::factory()->create([
                    'member_id' => $member->id,
                    'amount' => $ptPkg->price,
                    'bank_id' => $banks->random()->id,
                    'payment_type' => 'pt_package',
                    'pt_package_id' => $ptPkg->id,
                ]);
            }
        }

        // 6. PT Sessions (100)
        $quotas = MemberPtQuota::all();
        if ($quotas->count() > 0) {
            for ($i = 0; $i < 100; $i++) {
                $quota = $quotas->random();
                try {
                    PtSession::factory()->create([
                        'member_pt_quota_id' => $quota->id,
                        'member_id' => $quota->member_id,
                        'trainer_id' => $trainerProfiles->random()->user_id,
                    ]);
                } catch (UniqueConstraintViolationException $e) {
                    // Ignore duplicate schedule and try again for this iteration
                    $i--;
                }
            }
        }

        // 7. Ratings (50)
        $sessions = PtSession::all();
        if ($sessions->count() >= 50) {
            $randomSessions = $sessions->random(50);
            foreach ($randomSessions as $session) {
                Rating::factory()->create([
                    'member_id' => $session->member_id,
                    'trainer_id' => $session->trainer_id,
                    'pt_session_id' => $session->id,
                ]);
            }
        }

        // 8. Check-ins (150)
        $memberships = Membership::all();
        if ($memberships->count() > 0) {
            for ($i = 0; $i < 150; $i++) {
                $membership = $memberships->random();
                CheckIn::factory()->create([
                    'member_id' => $membership->member_id,
                    'membership_id' => $membership->id,
                ]);
            }
        }

        // 9. Booking Demo Sesi untuk Trainer (Jadwal Hari Ini, Minggu Ini, & Mendatang)
        $this->call(PtBookingDemoSeeder::class);
    }
}
