# POLITEKNIK NEGERI MALANG — JURUSAN TEKNOLOGI INFORMASI
## Program Studi D4 Sistem Informasi Bisnis
### LAPORAN KONTRIBUSI INDIVIDU PROYEK
**Mata Kuliah: Manajemen Proyek · Milestone 2 (Minggu 12)**  
**Proyek: FitCore — Sistem Informasi Manajemen dan Reservasi Gym Berbasis Web**

---

### Lembar Identitas Mahasiswa
| Atribut | Keterangan |
| :--- | :--- |
| **Nama Mahasiswa** | **Siti Aisyah** |
| **NIM** | **244107060038** |
| **Kelompok** | **Kelompok 3 – SIB-3A** |
| **Peran di Proyek** | **Full-stack Developer & Security Specialist (Owner Fitur: Akses, Identitas, & Keamanan)** |
| **Periode Kerja yang Dilaporkan** | **Minggu 1 sampai Minggu 12** |
| **Tanggal Laporan** | **18 November 2026** |
| **Folder GDrive Bukti** | `https://drive.google.com/drive/folders/fitcore-bukti-siti-aisyah-m2` *(Akses: Siapa saja yang memiliki link dapat melihat)* |

---

> **PETUNJUK SINGKAT:**  
> Laporan ini ditulis sendiri oleh tiap mahasiswa dan menjadi dasar penilaian kontribusi individu, terpisah dari nilai kelompok. Tulis hanya pekerjaan yang benar-benar dikerjakan, dan setiap klaim wajib disertai bukti yang bisa diperiksa dosen. Klaim tanpa bukti tidak dihitung. Dosen dapat meminta penjelasan lisan atas isi laporan ini. Bagian A–H disusun proporsional dan lampiran bukti tertera pada Bagian I.

---

## A. RINGKASAN KONTRIBUSI
*(Maksimal 200 kata)*

Pada proyek FitCore Gym, peran utama saya adalah sebagai **Full-stack Developer penanggung jawab modul Akses & Identitas Pengguna (*Authentication, Authorization, & Security Specialist*)**. Tiga kontribusi terpenting yang saya selesaikan hingga Milestone 2 meliputi: (1) Perancangan dan implementasi sistem autentikasi multi-role (Member, Trainer, Admin) terpadu dengan integrasi Google OAuth serta mekanisme verifikasi email unik via SMTP; (2) Pengembangan otorisasi hak akses berbasis peran (*Role-Based Access Control / RBAC*) dan *security middleware* penangkal akses ilegal (HTTP 403 Forbidden) beserta modul manajemen pengguna (CRUD data member dan akun trainer sisi admin); serta (3) Pengimplementasian arsitektur notifikasi email asinkron (*Laravel Queue*) dan penyusunan rangkaian pengujian otomatis *End-to-End* (E2E Playwright Python) untuk memvalidasi integritas autentikasi dan keamanan sistem.

Saya mengestimasi porsi kontribusi kerja saya dalam tim sebesar **25%** (seimbang dari total 100% untuk 4 anggota). Estimasi ini didasarkan pada tanggung jawab kepemilikan 6 tugas WBS kritis berstatus *Ditinjau* yang menjadi fondasi utama sistem—di mana modul transaksi dan booking tidak dapat berjalan tanpa adanya sistem autentikasi yang stabil—didukung oleh rata-rata 11–14 jam kerja per minggu, aktivitas 18+ commit/merge di repositori GitHub, serta penyusunan skrip pengujian keamanan.

---

## B. DAFTAR PEKERJAAN YANG SAYA KLAIM

| No | Pekerjaan | Artefak / Deliverable Terkait | Minggu | Jenis | Porsi Saya (%) | Bukti |
| :-: | :--- | :--- | :-: | :-: | :-: | :-: |
| **1** | Implementasi Form Registrasi Member & Validasi Email Unik | WBS #31 (`Form registrasi + validasi email unik`) | Mgg 5–6 | Sendiri | 90% | B1 |
| **2** | Pengembangan Autentikasi Login & Logout Terpadu Multi-Role (Member, Trainer, Admin) | WBS #32 & #33 (`Login & logout Breeze/Fortify`, `Login PT dan Admin`) | Mgg 5–7, 11–12 | Sendiri | 85% | B2, B3 |
| **3** | Arsitektur Otorisasi Hak Akses (RBAC) & Middleware Proteksi 403 Forbidden | WBS #34 (`Role & permission + middleware 403`) | Mgg 6–7 | Sendiri | 85% | B4 |
| **4** | Pengembangan Halaman & Modul CRUD Data Member Sisi Admin | WBS #45 (`Daftar & CRUD data member`) | Mgg 7–9 | Sendiri | 80% | B5 |
| **5** | Implementasi Manajemen Akun Trainer (Pembuatan Akun & Penjadwalan Sisi Admin) | WBS #33 / Commit `468feb6` & `c09dd1c` | Mgg 8–10 | Bersama | 60% | B6 |
| **6** | Sistem Notifikasi Email Asinkron via Background Queue Worker & Reminder Masa Aktif | WBS #56 (`Notifikasi konfirmasi via email template & Queue`) | Mgg 9–11 | Sendiri | 80% | B7 |
| **7** | Otomasi Pengujian Keamanan & Autentikasi (E2E Test Suite Playwright Python) | WBS #62, #64 / Dokumen STR-E2E & TC-SEC | Mgg 11–12 | Sendiri | 75% | B8, B9 |
| **8** | Resolusi Cacat Uji E2E & Sinkronisasi Alur Login Tunggal (*Unified Role Redirection*) | WBS #65 (`Perbaikan bug mayor hasil testing tahap 1`) | Mgg 12 | Sendiri | 90% | B10 |

*Keterangan Jenis: Sendiri = Pengerjaan utama mandiri · Bersama = Kolaborasi tim · Mereview = Memeriksa dan memvalidasi pekerjaan anggota lain.*

---

## C. URAIAN DAN BUKTI PER PEKERJAAN

### C.1 Pekerjaan 1: Implementasi Form Registrasi Member & Validasi Email Unik (WBS #31)
| Komponen | Penjelasan Rinci |
| :--- | :--- |
| **Apa yang saya kerjakan** | Saya merancang antarmuka formulir registrasi member baru, menyusun *Form Request Validation* (`RegisterRequest.php`), dan mengimplementasikan aturan validasi ketat untuk memastikan alamat email unik, nomor WhatsApp valid, serta pembuatan entitas profil member awal secara otomatis. |
| **Kaitan dengan proyek** | Bagian dari deliverable *Akses & Identitas (M02)*. Merupakan pintu gerbang utama bagi pengguna luar untuk menjadi member resmi FitCore Gym sebelum dapat membeli paket membership atau booking personal trainer. |
| **Cara saya mengerjakan** | Menggunakan Laravel 11 Blade dan Tailwind CSS. Menambahkan validasi unik ke tabel `users`, enkripsi kata sandi menggunakan `Hash::make()` (Bcrypt 12 rounds), dan memanfaatkan transaksi basis data (`DB::transaction`) untuk memastikan akun `User` dan `MemberProfile` terbuat secara atomik. |
| **Hasil / output** | Halaman pendaftaran member (`/register`), Controller registrasi, dan data profil member tersimpan lengkap di database. |
| **Bukti (Lampiran I)** | **B1** — Commit GitHub `feat: implementasi form registrasi member` & Tangkapan layar form pendaftaran valid. |
| **Kendala dan cara mengatasi** | Input nomor WhatsApp sering menggunakan format lokal (`08...`) atau internasional (`+62...`). Saya menambahkan sanitasi regex untuk menyeragamkan format nomor telepon sebelum disimpan ke database. |
| **Bagian dikerjakan bersama** | – (Dikerjakan sendiri secara mandiri). |

```text
Cuplikan Bukti B1:
[Screenshot Formulir Registrasi Member FitCore Gym dengan Validasi Real-time & Email Verification Toast]
Tanggal: 14 Oktober 2026 | Berkas: resources/views/auth/register.blade.php & app/Http/Controllers/Auth/RegisteredUserController.php
```

---

### C.2 Pekerjaan 2: Autentikasi Login & Logout Terpadu Multi-Role (WBS #32 & #33)
| Komponen | Penjelasan Rinci |
| :--- | :--- |
| **Apa yang saya kerjakan** | Saya membangun alur login dan logout terpusat. Awalnya menggunakan pemisahan tab role, kemudian saya lakukan refactoring menjadi **Sistem Login Tunggal Satu Pintu (*Unified Login*)**, di mana pengguna cukup memasukkan kredensial, dan sistem secara cerdas memeriksa role pengguna di database untuk melakukan *redirection* ke dashboard yang tepat (Member, Trainer, atau Admin). |
| **Kaitan dengan proyek** | Masuk ke deliverable *Akses & Identitas (M02)*. Menjamin gerbang masuk sistem memiliki keamanan tinggi, mencegah session fixation, dan memberikan navigasi instan sesuai hak pengguna. |
| **Cara saya mengerjakan** | Memodifikasi `AuthenticatedSessionController.php` dan `login.blade.php`. Mengimplementasikan `Auth::attempt()`, regenerasi ID sesi (`$request->session()->regenerate()`), penanganan remember me token, fallback controller untuk login Google OAuth via Laravel Socialite, serta proteksi error timeout koneksi. |
| **Hasil / output** | Rute `/login`, `/logout`, komponen Blade `auth-input`, dan controller autentikasi yang mendukung redirect otomatis ke `/admin/dashboard`, `/trainer/dashboard`, atau `/member/dashboard`. |
| **Bukti (Lampiran I)** | **B2, B3** — Commit `c007d8d` (*fix: update and fix login page*) dan Commit `8688ed8` (*fix: perbaikan controller login google*). |
| **Kendala dan cara mengatasi** | Terdapat kendala batas waktu eksekusi (timeout 30 detik) akibat konfigurasi driver sesi database. Saya menyelesaikannya dengan memastikan fallback session stabil dan menyederhanakan komponen form login. |
| **Bagian dikerjakan bersama** | Kolaborasi dengan Naufal Alif Adjani dalam pengujian integrasi peramban dan penyesuaian styling tema light mode. |

```text
Cuplikan Bukti B2 & B3:
[Screenshot Halaman Login Satu Pintu FitCore Gym & Potongan Kode Controller Role Redirection]
Tanggal: 02 November – 18 November 2026 | Berkas: app/Http/Controllers/Auth/AuthenticatedSessionController.php
```

---

### C.3 Pekerjaan 3: Arsitektur Otorisasi Hak Akses (RBAC) & Middleware Proteksi 403 (WBS #34)
| Komponen | Penjelasan Rinci |
| :--- | :--- |
| **Apa yang saya kerjakan** | Saya merancang arsitektur keamanan *Role-Based Access Control* (RBAC) pada level model dan middleware HTTP, memastikan bahwa pengguna dengan role tertentu tidak dapat mengakses URL milik role lain secara paksa (*URL tampering*). |
| **Kaitan dengan proyek** | Deliverable *Akses & Identitas / Keamanan Sistem (M02)*. Sangat penting untuk menjamin data privasi keuangan member dan kontrol data operasional gym admin tidak bocor. |
| **Cara saya mengerjakan** | Menulis helper method otorisasi pada `User.php` (`isAdmin()`, `isTrainer()`, `isMember()`, `hasRole()`), membuat middleware `RoleMiddleware.php`, mendaftarkannya pada `bootstrap/app.php`, serta merancang halaman respons HTTP 403 Forbidden kustom dengan UI informatif. |
| **Hasil / output** | Middleware proteksi rute `role:admin`, `role:trainer`, `role:member`, dan halaman `errors/403.blade.php`. |
| **Bukti (Lampiran I)** | **B4** — Commit GitHub `feat: tambah role & permision serta middleware error 403` & Tangkapan layar penolakan akses 403 saat member membuka `/admin/member-management`. |
| **Kendala dan cara mengatasi** | Menghindari ketergantungan berlebih pada package pihak ketiga (Spatie) yang kompleks; saya membangun otorisasi native Laravel yang bersih, cepat, dan mudah dipelihara oleh tim. |
| **Bagian dikerjakan bersama** | – (Dikerjakan sendiri secara mandiri). |

```text
Cuplikan Bukti B4:
[Screenshot Tampilan Resmi Layanan HTTP 403 Forbidden & Pendaftaran Middleware pada bootstrap/app.php]
Tanggal: 23 Oktober 2026 | Berkas: app/Http/Middleware/RoleMiddleware.php & resources/views/errors/403.blade.php
```

---

### C.4 Pekerjaan 4: Modul Manajemen Data Member Sisi Admin (WBS #45)
| Komponen | Penjelasan Rinci |
| :--- | :--- |
| **Apa yang saya kerjakan** | Saya membangun panel antarmuka dan backend untuk Admin dalam mengelola seluruh data member gym (melihat daftar member aktif/expired, detail kontak WhatsApp, riwayat tanggal bergabung, filter status, dan fungsi edit data). |
| **Kaitan dengan proyek** | Deliverable *Manajemen Member & Langganan (M04)*. Memudahkan staf resepsionis dan manajer gym dalam memantau keaktifan anggota dan membantu proses perpanjangan offline. |
| **Cara saya mengerjakan** | Mengembangkan controller `MemberManagementController.php`, menyusun query Eloquent dengan *eager loading* (`with(['memberships', 'memberProfile'])`) untuk mencegah masalah *N+1 Query*, serta membersihkan elemen UI yang redundan (seperti tombol manual add member yang tidak sesuai aturan bisnis). |
| **Hasil / output** | Halaman web `/admin/member-management`, tabel data member interaktif dengan pagination, modal detail member, dan badge status keanggotaan. |
| **Bukti (Lampiran I)** | **B5** — Commit `c1c24a9` (*feat: implementasi halaman manajemen data member*) dan Commit `45eb974` (*fix(admin): hapus tombol tambah member pada halaman manajemen member*). |
| **Kendala dan cara mengatasi** | Terjadi konflik tombol aksi ganda pada view Blade saat integrasi merge. Saya melakukan resolusi kode (*conflict resolution*) dan menyelaraskan struktur tabel. |
| **Bagian dikerjakan bersama** | Bersama Achmad Alfi Maksum dalam sinkronisasi status keaktifan paket membership. |

```text
Cuplikan Bukti B5:
[Screenshot Dasbor Admin Manajemen Member: Tabel Anggota, Filter Status Aktif/Expired, dan Modal Informasi]
Tanggal: 27 Oktober – 04 November 2026 | Berkas: resources/views/admin/member-management/index.blade.php
```

---

### C.5 Pekerjaan 5: Notifikasi Email Asinkron via Background Queue Worker (WBS #56)
| Komponen | Penjelasan Rinci |
| :--- | :--- |
| **Apa yang saya kerjakan** | Saya mengonfigurasi sistem pengiriman email transaksi secara asinkron menggunakan Laravel Queue (`database driver`), membuat template email HTML responsif untuk kredensial trainer dan verifikasi akun member, serta memastikan pengiriman tidak memblokir antarmuka pengguna. |
| **Kaitan dengan proyek** | Deliverable *Notifikasi & Integrasi Sistem (M05)*. Memastikan pengguna menerima notifikasi konfirmasi dan kredensial secara otomatis, cepat, dan profesional. |
| **Cara saya mengerjakan** | Mengimplementasikan class Mailable `TrainerCredentialsMail.php`, mengonfigurasi driver SMTP (Mailtrap untuk pengujian & SMTP server produksi), membuat migrasi tabel `jobs` dan `failed_jobs`, serta menguji eksekusi antrean via `php artisan queue:work`. |
| **Hasil / output** | Template email notifikasi HTML profesional di `resources/views/emails/`, konfigurasi queue di `.env`, dan mekanisme pengiriman email non-blocking (< 1.5 detik respons layar). |
| **Bukti (Lampiran I)** | **B7** — Commit `6459f43` (*fix: perbaikan konfigurasi dan proses verifikasi email via smtp*) dan log Mailtrap inbox. |
| **Kendala dan cara mengatasi** | Timeout server SMTP saat pengujian lokal sempat menyebabkan transaksi approval tertahan. Saya mengatasinya dengan memasukkan proses pengiriman email ke dalam antrean *database queue* (`implements ShouldQueue`). |
| **Bagian dikerjakan bersama** | – (Dikerjakan mandiri). |

```text
Cuplikan Bukti B7:
[Screenshot Inbox Mailtrap Menerima Email Notifikasi Kredensial Trainer & Template Email HTML FitCore]
Tanggal: 30 Oktober 2026 | Berkas: app/Mail/TrainerCredentialsMail.php & config/mail.php
```

---

## D. KONTRIBUSI DALAM KERJA TIM

| Tanggal | Kegiatan | Peran Saya | Hasil / Dampak bagi Tim | Bukti |
| :---: | :--- | :--- | :--- | :---: |
| **10 Okt 2026** | Rapat Perancangan Skema Database Awal & Penentuan Struktur Tabel Pengguna | Peserta aktif & Desainer tabel `users` | Berhasil menyepakati skema relasi tabel `users`, `member_profiles`, dan `roles` tanpa redundansi data. | Notulen Rapat M02 |
| **24 Okt 2026** | Sinkronisasi Arsitektur Keamanan & Middleware Multi-Role | Inisiator & Lead Otorisasi | Tim pengembang lain (Alfi dan Rif’at) dapat menerapkan middleware `role:*` pada modul pembayaran dan booking dengan standar seragam. | Pull Request #34 |
| **04 Nov 2026** | Resolusi Konflik Git Merge Branch `feature/manage-members` ke `develop` | Resolutor Git Konflik | Menyelesaikan 9 konflik merge file Blade dan Controller secara bersih tanpa merusak fitur anggota lain. | Commit `baeb426` & `8ec4090` |
| **10 Nov 2026** | Kolaborasi Integrasi Desain Antarmuka Light Mode (Blaze Orange Theme) | Kontributor Styling | Menyelaraskan seluruh komponen guest layout, navbar, dan form otentikasi agar konsisten dengan tema warna FitCore Gym. | Commit `21ed4d8` |
| **12 Nov 2026** | Validasi Pengujian Otomatis End-to-End PMPL & Evaluasi Quality Gate | Test Analyst & Security Tester | Membantu eksekusi pengujian Playwright E2E pada skenario login, penolakan akses, dan verifikasi bukti jejak rekaman *Trace Viewer*. | Dokumen STR-E2E & Laporan FAIL to PASS |

---

## E. LOGBOOK MINGGUAN (Minggu 1 s/d Minggu 12)

| Minggu | Kegiatan Utama Saya | Perkiraan Jam | Kode Bukti |
| :-: | :--- | :-: | :-: |
| **Minggu 1** | Mengikuti kick-off meeting proyek PBL, analisis kebutuhan awal sistem FitCore Gym, dan studi literatur arsitektur sistem gym. | 8 Jam | B11 |
| **Minggu 2** | Menyusun kebutuhan fungsional modul Akses & Identitas, perancangan use case diagram dan user persona member/trainer. | 10 Jam | B11 |
| **Minggu 3** | Merancang Entity Relationship Diagram (ERD) untuk entitas pengguna, role, dan profil keanggotaan bersama tim database. | 12 Jam | B12 |
| **Minggu 4** | Setup repositori Git kelompok, instalasi Laravel 11, konfigurasi awal Tailwind CSS, dan inisialisasi basis data. | 12 Jam | B12 |
| **Minggu 5** | Mengembangkan antarmuka dan logika form registrasi member baru serta validasi nomor telepon/email unik. | 14 Jam | B1 |
| **Minggu 6** | Mengimplementasikan sistem autentikasi login, logout, enkripsi password, dan pengujian alur verifikasi email via SMTP. | 14 Jam | B2, B7 |
| **Minggu 7** | Merancang dan menerapkan arsitektur otorisasi multi-role (RBAC), pembuatan middleware penangkal akses ilegal, dan view error 403. | 14 Jam | B4 |
| **Minggu 8** | Membangun halaman dashboard admin untuk manajemen data member (CRUD) dan penggabungan dengan data transaksi pembayaran. | 12 Jam | B5 |
| **Minggu 9** | Mengembangkan fitur manajemen akun trainer sisi admin (penambahan akun instruktur gym dan pembagian jadwal kerja). | 14 Jam | B6 |
| **Minggu 10** | Konfigurasi sistem pengiriman email asinkron dengan Laravel Queue Worker dan penyusunan template email notifikasi resmi. | 12 Jam | B7 |
| **Minggu 11** | Melakukan refactoring UI ke tema Light Mode (Blaze Orange), resolusi merge conflict develop, dan persiapan suite testing otomatis. | 14 Jam | B8, B10 |
| **Minggu 12** | Menyusun dan mengeksekusi skrip pengujian otomatis Playwright E2E, penyempurnaan alur login satu pintu, dan dokumentasi Milestone 2. | 16 Jam | B3, B9, B10 |
| **Total** | **Akumulasi Jam Kerja Kontribusi Mandiri Sepanjang Semester (M1 – M12)** | **~152 Jam** | – |

---

## F. REFLEKSI PRIBADI
*(Maksimal 250 kata)*

Selama 12 minggu pelaksanaan proyek FitCore Gym, pembelajaran terbesar yang saya peroleh berkaitan erat dengan domain **Development Approach and Lifecycle** serta **Team Performance** dalam panduan **PMBOK 8th Edition**. Mengembangkan modul *Akses & Identitas* menyadarkan saya bahwa keamanan dan arsitektur otorisasi bukan sekadar menulis logika login, melainkan fondasi kritis (*critical dependency*) yang menentukan kelancaran deliverable anggota tim lainnya. Pendekatan iteratif yang adaptif menuntut komunikasi intensif agar dependensi hak akses tidak menghambat modul pembayaran dan reservasi.

Kendala terbesar yang saya hadapi sebagai individu adalah penanganan *merge conflict* yang cukup rumit ketika branch fitur saya digabungkan dengan branch develop yang memuat pembaruan UI masif dari anggota lain, ditambah kendala timeout pengujian browser. Saya mengatasinya dengan mendalami mekanisme percabangan Git berbasis *feature-branch workflow*, melakukan komunikasi aktif sebelum proses *merge*, serta mengimplementasikan *resilient locators* dan *auto-waiting* pada skrip pengujian Playwright untuk mencegah kegagalan *flaky*.

Pada tahap pengerjaan berikutnya (Milestone 3), hal yang akan saya perbaiki adalah meningkatkan otomatisasi *Continuous Integration* (CI) agar pengujian keamanan dan validasi coding standard (Laravel Pint) dapat berjalan otomatis di GitHub Actions sebelum kode di-merge, sehingga potensi regresi dapat dideteksi jauh lebih dini.

---

## G. RENCANA KONTRIBUSI TAHAP BERIKUTNYA (Minggu 13–16)

| No | Kegiatan | Minggu | Output yang Diharapkan | Terkait Deliverable / Artefak |
| :-: | :--- | :-: | :--- | :--- |
| **1** | Penggabungan 4 modul inti & perbaikan alur integrasi menyeluruh Member-PT-Admin | Mgg 13 | Alur navigasi lintas role terhubung mulus tanpa celah otorisasi | WBS #61 (Integrasi Modul Inti) |
| **2** | Pelaksanaan Testing Non-Fungsional (Usability, Kinerja Kecepatan, & Audit RBAC) | Mgg 13–14 | Laporan pengujian non-fungsional dan log audit keamanan | WBS #64 (Non-Functional Testing) |
| **3** | Penyusunan Unit Test Kritis Backend (PHPUnit Test untuk Otentikasi & Guard Role) | Mgg 14–15 | Test suite PHPUnit lulus 100% dengan cakupan fungsi kritis | WBS #66 (White-box Unit Testing) |
| **4** | Pelaksanaan Regression Testing Tahap 2 & Penutupan Cacat Uji Akhir | Mgg 15 | Tidak ada bug blocker/mayor yang terbuka sebelum deployment | WBS #67 (Regression Testing) |
| **5** | Penyusunan Test Summary Report Akhir & Persiapan Presentasi Demo Proyek | Mgg 16 | Dokumen laporan akhir proyek dan materi paparan evaluasi | WBS #68 (Test Summary Report) |

---

## H. PERNYATAAN DAN KONFIRMASI REKAN

### Pernyataan Keaslian Mahasiswa
Saya menyatakan bahwa seluruh pekerjaan yang saya klaim dalam laporan ini adalah benar-benar hasil kerja mandiri dan kontribusi nyata saya dalam tim, bukti yang saya lampirkan adalah otentik dan dapat diverifikasi, serta saya bersedia memberikan penjelasan lisan maupun demonstrasi teknis bila diminta oleh dosen penilai. Saya memahami sepenuhnya bahwa klaim yang tidak benar atau plagiasi atas pekerjaan anggota lain akan berdampak langsung pada nilai individual saya.

**Malang, 18 November 2026**  
*Mahasiswa yang menyatakan,*

*(Tanda Tangan Digital)*  
**Siti Aisyah**  
NIM: 244107060038

---

### Tabel Konfirmasi Rekan Kelompok

| No | Nama Rekan | NIM | Pekerjaan yang Dikonfirmasi (Nomor di Tabel B) | Sesuai? (Ya / Sebagian / Tidak) | Catatan Singkat Rekan Tim |
| :-: | :--- | :---: | :---: | :-: | :--- |
| **1** | **Naufal Alif Adjani** *(Project Lead)* | 244107060090 | Pekerjaan No. 1, 2, 3, 4, 5, 6, 7, 8 | **Ya** | Kontribusi Aisyah sangat jelas pada fondasi autentikasi, middleware 403, dan perbaikan alur login terpadu. |
| **2** | **Achmad Alfi Maksum** *(Developer Transaksi)* | 244107060009 | Pekerjaan No. 2, 3, 4, 5, 6 | **Ya** | Modul data member dan sistem notifikasi email queue bekerja selaras dengan modul pembayaran saya. |
| **3** | **Muhammad Rif’at Syamlan** *(Developer PT)* | 244107060150 | Pekerjaan No. 2, 3, 5, 7 | **Ya** | Hak akses portal trainer dan pengujian Playwright E2E terverifikasi berfungsi baik sesuai standar. |

---

## I. LAMPIRAN BUKTI

| Kode | Jenis Bukti | Keterangan (Apa yang Ditunjukkan & Tanggal) | Link / Nama File di GDrive / GitHub |
| :-: | :--- | :--- | :--- |
| **B1** | Commit & View Blade | Implementasi form registrasi member dan validasi nomor telepon/email unik (14 Okt 2026) | `resources/views/auth/register.blade.php` / Commit `c1c24a9` |
| **B2** | Commit & Controller | Pembuatan logika login, logout, dan remember token multi-role (18 Okt 2026) | `app/Http/Controllers/Auth/AuthenticatedSessionController.php` |
| **B3** | Commit & Refactoring UI | Pembaruan antarmuka login terpadu satu pintu (*unified login*) dan perbaikan form timeout (18 Nov 2026) | Commit `c007d8d` (*fix: update and fix login page*) |
| **B4** | Commit & Middleware | Implementasi `RoleMiddleware.php` dan halaman proteksi HTTP 403 Forbidden (23 Okt 2026) | `app/Http/Middleware/RoleMiddleware.php` / Commit `6a1a68f` |
| **B5** | Commit & View Admin | Implementasi dasbor manajemen data member sisi admin dan penyesuaian layout (04 Nov 2026) | Commit `45eb974` & `c1c24a9` |
| **B6** | Commit & Controller | Pembuatan fitur penambahan akun trainer dan manajemen shift instruktur gym (04 Nov 2026) | Commit `468feb6` & `baeb426` |
| **B7** | Screenshot & Config | Konfigurasi background Queue Worker dan inbox Mailtrap penerimaan email kredensial (30 Okt 2026) | `app/Mail/TrainerCredentialsMail.php` / Commit `6459f43` |
| **B8** | Skrip Uji Python | Berkas otomasi pengujian Playwright `tests/e2e/test_login.py` (Autentikasi & Negatif Sandi Salah) | `tests/e2e/test_login.py` / Commit `fc1c4c5` |
| **B9** | Dokumen STR & Trace | Hasil eksekusi pengujian Playwright 100% PASS dan rekaman inspeksi `trace.zip` di Trace Viewer | `STR-E2E-FITCORE-2026-V1.0` / Berkas `trace.zip` |
| **B10** | Laporan Defect Resolution | Dokumen laporan penelusuran dan resolusi cacat uji (*FAIL to PASS Report*) FitCore Gym | `Laporan_Perubahan_Status_Pengujian_FAIL_to_PASS.md` |
| **B11** | Dokumen Proposal PBL | Bagian 8.2 Struktur Pembagian Peran dan Tugas Fitur Owner Anggota Kelompok | `docs/PROPOSAL_PBL_FITCORE.md` (Halaman 35–38) |
| **B12** | Logbook Proyek M1–M12 | Catatan logbook mingguan aktivitas pengerjaan proyek semester ganjil | `docs/LOGBOOK_PROJECT_MINGGU_1_12.md` |
