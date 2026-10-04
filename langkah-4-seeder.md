# Langkah 4: Seeder Data Awal

Seeder untuk data wajib sistem gym. Tanpa data ini, tabel kosong: tidak ada akun admin, tidak ada pilihan bank, dan tidak ada paket yang bisa dibeli.

## Seeder yang Dibuat

| Seeder | Mengisi tabel |
|---|---|
| `UserSeeder` | `users` (admin + trainer) dan `trainer_profiles` |
| `BankSeeder` | `banks` |
| `MembershipPackageSeeder` | `membership_packages` |
| `PtPackageSeeder` | `pt_packages` |

> `DatabaseSeeder` **sudah ada bawaan Laravel**, jadi tidak perlu dibuat ulang. Cukup edit isinya (langkah 5).

## 1. Buat File Seeder

```bash
php artisan make:seeder UserSeeder
php artisan make:seeder BankSeeder
php artisan make:seeder MembershipPackageSeeder
php artisan make:seeder PtPackageSeeder
```

File dibuat di `database/seeders/`.

## 2. Isi Seeder

Semua seeder memakai `updateOrCreate` / `firstOrCreate`, jadi **aman dijalankan berulang** tanpa membuat data dobel (selama bukan `migrate:fresh`, yang memang mengosongkan semuanya).

### UserSeeder

Membuat 1 admin dan 2 trainer. Karena `trainer_profiles` butuh `user_id`, profil trainer dibuat tepat setelah user-nya.

```php
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
```

> Ganti email dan password sesuai kebutuhan. Jangan pakai password ini di server produksi.

### BankSeeder

```php
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
```

> Ganti nomor rekening dan nama pemilik dengan data asli gym kamu.

### MembershipPackageSeeder

```php
<?php

namespace Database\Seeders;

use App\Models\MembershipPackage;
use Illuminate\Database\Seeder;

class MembershipPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'             => 'Basic 1 Bulan',
                'type'             => 'basic',
                'duration_days'    => 30,
                'price'            => 250000,
                'facilities'       => 'Akses alat gym, loker, area kardio',
                'pt_session_count' => 0,
            ],
            [
                'name'             => 'Premium 3 Bulan',
                'type'             => 'premium',
                'duration_days'    => 90,
                'price'            => 650000,
                'facilities'       => 'Akses alat gym, loker, area kardio, kelas grup',
                'pt_session_count' => 2,
            ],
            [
                'name'             => 'VIP 12 Bulan',
                'type'             => 'vip',
                'duration_days'    => 365,
                'price'            => 2200000,
                'facilities'       => 'Semua fasilitas, kelas grup, handuk, sauna',
                'pt_session_count' => 8,
            ],
        ];

        foreach ($packages as $package) {
            MembershipPackage::updateOrCreate(
                ['name' => $package['name']],
                $package + ['status' => 'active']
            );
        }
    }
}
```

> `pt_session_count` adalah jatah PT gratis yang didapat member saat membeli paket (nantinya masuk ke `member_pt_quotas` dengan `source = bonus_membership`).

### PtPackageSeeder

```php
<?php

namespace Database\Seeders;

use App\Models\PtPackage;
use Illuminate\Database\Seeder;

class PtPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'                => 'PT 5 Sesi',
                'pt_session_count'    => 5,
                'price'               => 500000,
                'min_membership_days' => 30,
                'validity_days'       => 60,
            ],
            [
                'name'                => 'PT 10 Sesi',
                'pt_session_count'    => 10,
                'price'               => 900000,
                'min_membership_days' => 30,
                'validity_days'       => 90,
            ],
            [
                'name'                => 'PT 20 Sesi',
                'pt_session_count'    => 20,
                'price'               => 1600000,
                'min_membership_days' => 60,
                'validity_days'       => 180,
            ],
        ];

        foreach ($packages as $package) {
            PtPackage::updateOrCreate(
                ['name' => $package['name']],
                $package + ['status' => 'active']
            );
        }
    }
}
```

> `min_membership_days` adalah sisa masa aktif membership minimal agar member boleh membeli paket ini. `validity_days` adalah masa berlaku paket PT setelah dibeli.

## 3. Daftarkan di DatabaseSeeder

Buka `database/seeders/DatabaseSeeder.php` (file bawaan), **hapus** kode bawaan `User::factory()->create(...)`, lalu ganti isi `run()`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
    }
}
```

Urutan: `UserSeeder` harus sebelum yang lain hanya karena logikanya (akun dulu). Tiga seeder katalog tidak punya foreign key, jadi urutannya bebas.

## 4. Jalankan

```bash
php artisan db:seed                              # jalankan semua seeder
php artisan db:seed --class=BankSeeder           # jalankan satu seeder saja
php artisan migrate:fresh --seed                 # ulang semua tabel + isi data awal
```

> `migrate:fresh --seed` menghapus **semua tabel dan datanya**. Pakai hanya saat development.

## 5. Cek Hasilnya

Lewat phpMyAdmin, atau lewat Tinker:

```bash
php artisan tinker
```

```php
User::where('role', 'admin')->first();
User::where('role', 'trainer')->with('trainerProfile')->get();
App\Models\Bank::count();            // 3
App\Models\MembershipPackage::count(); // 3
App\Models\PtPackage::count();       // 3
```

## Kesalahan yang Sering Terjadi

| Gejala | Penyebab | Solusi |
|---|---|---|
| `Class "Database\Seeders\XSeeder" does not exist` | Nama class dan file tidak sama, atau belum didaftarkan | Cek nama file, namespace, dan `$this->call([...])` |
| `Add [name] to fillable property` | Model belum punya `$guarded = []` / `$fillable` | Lihat langkah 3 |
| `Integrity constraint violation` pada `trainer_profiles` | `user_id` tidak ada | Pastikan user dibuat dulu, seperti di `UserSeeder` |
| Data dobel setelah `db:seed` berulang | Memakai `create()` biasa | Gunakan `updateOrCreate` seperti contoh di atas |
| Tidak bisa login dengan password seeder | Password di-hash dua kali atau salah ketik | Pakai `Hash::make('password123')` sekali saja |

## Checklist

- [ ] 4 file seeder dibuat dan diisi
- [ ] `DatabaseSeeder` memanggil keempat seeder, kode factory bawaan dihapus
- [ ] `php artisan db:seed` berjalan tanpa error
- [ ] Akun `admin@gym.test` berhasil dibuat
- [ ] Tabel `banks`, `membership_packages`, `pt_packages` terisi

Langkah berikutnya: autentikasi (login dan pembagian role admin/trainer/member), lalu route, controller, dan view.
