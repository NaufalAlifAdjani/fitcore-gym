
# DOKUMEN TEST PLAN v1.0

Mata Kuliah: Penjaminan Mutu Perangkat Lunak (SIB216101)
Program Studi: D4 Sistem Informasi Bisnis — Politeknik Negeri Malang
Standar Dokumen: IEEE 829 (Test Plan Outline for Iterative/Limited Release)
________________


Lembar Informasi Dokumen
Test Plan Identifier
|  | TP-FITCORE-2026-V1.0 |
| --- | --- |
|  | Nama Proyek |
|  | FitCore Athletic Gym Management System |
|  | Versi Dokumen |
|  | 1.0 (Review Milestone Release) |
|  | Kelas / Kelompok |
|  | SIB-3A / Kelompok FitCore |




1. Pendahuluan & Tujuan Pengujian (Introduction & Objectives)

## 1.1 Latar Belakang

Sistem FitCore Gym telah menyelesaikan sejumlah deliverable kunci pada WBS (Work Breakdown Structure) yang kini berada pada fase Ditinjau (Under Review). Fitur-fitur ini meliputi modul transaksi keanggotaan (membership), pembelian paket & pengajuan sesi Personal Trainer (PT), validasi presensi berbasis QR scanner (html5-qrcode), keamanan hak akses (role & permission), serta subsistem notifikasi asinkron berbasis email queue.
Sebelum fitur-fitur tersebut diintegrasikan ke tahap Done dan rilis staging/production, diperlukan pengujian menyeluruh untuk menjamin fungsionalitas, keandalan data (data integrity), mitigasi celah keamanan (role escalation), serta kestabilan efek samping transaksi (side effects).

## 1.2 Tujuan Pengujian (Test Objectives)

1. Objektif 1 (Transaksi & Verifikasi): Memverifikasi bahwa setiap alur pemilihan paket, unggah bukti pembayaran transfer, dan verifikasi persetujuan (approval/rejection) admin memperbarui status transaksi secara atomik dan akurat tanpa anomali data.
2. Objektif 2 (Booking & Kuota PT): Memverifikasi bahwa pengajuan jadwal sesi latihan PT hanya dapat dilakukan bila kuota sesi aktif mencukupi, tidak terjadi tumpang tindih (overlapping slot) pada kalender ketersediaan, serta notifikasi terkirim tepat sasaran ke PT terkait.
3. Objektif 3 (Presensi & Anti-Duplikasi): Memverifikasi bahwa camera scanner QR mampu memvalidasi status keaktifan kartu member, mencatat presensi secara real-time, dan menolak upaya duplikasi check-in pada hari atau sesi yang sama.
4. Objektif 4 (Keamanan Akses): Memverifikasi bahwa implementasi Role & Permission dan Middleware 403 secara ketat memblokir akses tidak sah antar role (member tidak bisa mengakses halaman admin/trainer, dan sebaliknya).
5. Objektif 5 (Asynchronous Service): Memverifikasi bahwa worker queue dan layanan email SMTP (Mailtrap) memproses seluruh notifikasi status dan kredensial secara reliabel tanpa memblokir respon HTTP pengguna (non-blocking UI).


2. Item Uji / Fitur Objek Uji (Test Items)
Berdasarkan daftar tugas yang berada pada status "Ditinjau", berikut adalah matriks modul dan fitur yang menjadi target uji:
NO
|  | Kode WBS |
| --- | --- |
|  | ID Item (Test Plan) |
|  | Nama Fitur / Tugas (Objek Uji) |
|  | Role / Modul Terkait |
|  | Ref. Skenario Uji Nanti |
|  | 1. |
|  | 3.1.1, 3.1.2 |
|  | TP-MEM-01 |
|  | Pemilihan paket keanggotaan (Membership Selection) |
|  | Member / Membership |
|  | TS-MEM-001 |
|  | 2. |
|  | 3.4.1 |
|  | TP-MEM-02 |
|  | Manajemen paket & harga membership (CRUD & Toggle status) |
|  | Admin / Package Master |
|  | TS-MEM-002 |
|  | 3. |
|  | 5.1.1 |
|  | TP-MEM-03 |
|  | Riwayat pribadi member (Kartu digital, hadir, sesi PT, transaksi) |
|  | Member / Portal Profil |
|  | TS-MEM-003 |
|  | 4. |
|  | 3.2.1, 3.2.2, 3.2.3 |
|  | TP-PAY-01 |
|  | Pembayaran online (upload bukti transfer & nominal) |
|  | Member / Payment |
|  | TS-PAY-001 |
|  | 5. |
|  | 3.3.1, 3.3.2, 3.3.3 |
|  | TP-PAY-02 |
|  | Verifikasi pembayaran (Approve / Reject) |
|  | Admin / Finance |
|  | TS-PAY-002 |
|  | 6. |
|  | 3.5.1, 3.5.2 |
|  | TP-PT-01 |
|  | Pembelian paket sesi PT (Checkout & Kuota) |
|  | Member / PT Package |
|  | TS-PT-001 |
|  | 7. |
|  | 3.6.1 |
|  | TP-PT-02 |
|  | Manajemen paket sesi PT (Pengaturan kuota & tarif resmi) |
|  | Admin / PT Master |
|  | TS-PT-002 |
|  | 8. |
|  | 4.3.1 |
|  | TP-PT-03 |
|  | Tampilan Kalender & Filter Ketersediaan PT per Slot Jam (FullCalendar) |
|  | Member & Trainer / Jadwal |
|  | TS-PT-003 |
|  | 9. |
|  | 4.4.1, 4.4.2 |
|  | TP-PT-04 |
|  | Pengajuan sesi PT oleh Member (Slot Booking Form) |
|  | Member / PT Booking |
|  | TS-PT-04 |
|  | 10. |
|  | 4.4.3 |
|  | TP-PT-05 |
|  | Notifikasi pengajuan sesi baru ke PT terkait |
|  | System / Notification |
|  | TS-PT-005 |
|  | 11. |
|  | 2.5.2 |
|  | TP-ATT-01 |
|  | Scanner kamera browser (html5-qrcode library integration) |
|  | Admin & Trainer / Scanner |
|  | TS-ATT-001 |
|  | 12. |
|  | 2.5.3, 2.5.4 |
|  | TP-ATT-02 |
|  | Validasi status member, pencatatan kehadiran & anti-duplikasi check-in |
|  | Core System / Check-in |
|  | TS-ATT-002 |
|  | 13. |
|  | 2.2.2 |
|  | TP-SEC-01 |
|  | Role & permission (Spatie) + middleware 403 Forbidden |
|  | Security / Auth Core |
|  | TS-SEC-001 |
|  | 14. |
|  | 5.2.1, 5.2.2 |
|  | TP-NOTIF-01 |
|  | Notifikasi konfirmasi via email (Mailable template & Asynchronous Queue) |
|  | Email Engine / Queue |
|  | TS-NOTIF-001 |
|  | 15. |
|  | 1.3.3 |
|  | TP-NOTIF-02 |
|  | Setup email service (Konektivitas SMTP Mailtrap & konfigurasi .env) |
|  | Infrastructure / Mail |
|  | TS-NOTIF-002 |


3. Cakupan Pengujian (Test Scope)

## 3.1 In Scope (Fitur yang Diuji)

Pengujian difokuskan penuh pada 5 pilar fungsionalitas kritis yang mencakup seluruh 15 tugas berstatus Ditinjau:
1. Siklus Hidup Membership: Registrasi paket → Unggah bukti bayar →Verifikasi oleh Admin →Aktivasi status membership.
2. Siklus Hidup Personal Trainer: Pemesanan paket kuota PT → Cek kalender slot →Pengajuan jadwal →Notifikasi PT.
3. Gerbang Presensi QR: Pindai kode QR digital pass → Cek status aktif/kedaluwarsa →Validasi duplikasi harian.
4. Sistem Keamanan & Otorisasi: Pengujian batas otorisasi URL traversal, pengujian status user aktif/inaktif, dan validasi response 403 Forbidden.
5. Infrastruktur Asinkron & Email: Pengiriman email kredensial, konfirmasi pembayaran, dan notifikasi booking via queue worker.

## 3.2 Out of Scope (Fitur di Luar Cakupan Pengujian Tahap Ini)

Area yang sengaja dikecualikan beserta alasannya:
* Rating Personal Trainer (5.3.1): Modul masih dalam status Sedang berlangsung.
* Dashboard performa gym eksekutif (3.8.1): Bergantung pada agregasi data jangka panjang yang belum lengkap.
* Payment Gateway Otomatis (Midtrans/Xendit): Sesuai spesifikasi saat ini, aplikasi menggunakan mekanisme manual transfer bank dan verifikasi bukti transfer.
* Aplikasi Mobile Native (Android APK / iOS IPA): Aplikasi saat ini berbasis Progressive Web Responsive yang diakses melalui browser modern.
* Stress & Load Testing Skala Besar (> 10.000 concurrent users): Dibatasi pada fase limited release staging dengan kapasitas uji 50–100 pengguna serentak.
4. Analisis & Prioritas Risiko Produk (Product Risk Matrix)
Penilaian risiko produk disusun secara kualitatif (Probability, Impact, Priority) untuk menentukan bobot alokasi pengujian:
ID Risiko 
|  | Area Fitur |
| --- | --- |
|  | Deskripsi Potensi Kegagalan |
|  | Probabilitas |
|  | Dampak |
|  | Prioritas Pengujian |
|  | Rencana Mitigasi Pengujian |
|  | RSK-01 |
|  | Verifikasi Pembayara |
|  | Bukti transfer palsu/buram disetujui, atau nominal verifikasi tidak mengupdate membership secara tepat. |
|  | Sedang (M) |
|  | Kritis (H) |
|  | CRITICAL |
|  | Uji boundary nominal, validasi format gambar WebP/JPEG, dan verifikasi status mutasi database. |
|  | RSK-02 |
|  | Booking Sesi PT |
|  | Terjadi race condition atau double-booking pada jam dan pelatih yang sama oleh 2 member berbeda. |
|  | Tinggi (H) |
|  | Kritis (H) |
|  | CRITICAL |
|  | Uji konkurensi booking dengan timestamp bersamaan; pastikan database locking/transaction aktif. |
|  | RSK-03 |
|  | Scanner Check-in |
|  | Member yang sudah expired atau bukan member gym lolos check-in, atau 1 kode QR di-scan berkali-kali dalam 1 jam. |
|  | Sedang (M) |
|  | Tinggi (H) |
|  | HIGH |
|  | Uji skenario kartu expired, QR string palsu, dan double tap scan interval < 10 menit |



|  | RSK-04. |
| --- | --- |
|  | Role & Permission |
|  | Member biasa dapat menembak rute /admin/* atau /trainer/* via modifikasi URL langsung. |
|  | Rendah (L) |
|  | Kritis (H) |
|  | CRITICAL |
|  | Black-box penetration test pada seluruh endpoint route admin menggunakan token/session role member. |
|  | RSK-05 |
|  | Email Queue |
|  | Worker queue mati (failed jobs) sehingga member tidak menerima notifikasi aktivasi atau kredensial PT tidak terkirim. |
|  | Sedang (M) |
|  | Sedang (M) |
|  | HIGH |
|  | Uji mock SMTP failure, inspeksi tabel failed_jobs, dan validasi pengiriman via Mailtrap sandbox. |


5. Pendekatan Pengujian (Test Approach & Methodologies)
Pengujian akan mengombinasikan beberapa metodologi untuk menjamin ketahanan sistem:
1. Black-box Functional Testing:
   * Equivalence Partitioning & Boundary Value Analysis (BVA): Menguji batas masa berlaku paket, nominal pembayaran, kuota booking (misal batas maksimal 3 sesi aktif), dan durasi sesi.
   * State Transition Testing: Memetakan alur transisi status transaksi (pending → verified / rejected) dan sesi PT (pending →confirmed / cancelled / completed).
2. White-box Testing (PHPUnit Feature & Unit Test):
   * Menjalankan test suite internal Laravel untuk menguji validasi FormRequest, mutasi model Eloquent, policy/middleware, dan artisan scheduler.
3. Automated End-to-End Testing (Playwright Python):
   * Menguji skenario lintas modul (Member ↔ Admin ↔ Trainer) di lingkungan browser Chromium headless/headed dengan aturan auto-waiting tanpa sleep buatan.
4. Three-Layer Assertion Verification (Aturan Emas PMPL): Setiap test case wajib divalidasi pada 3 Lapisan:
   * Lapisan 1 (Layar / UI): Teks toast flash message, status badge warna, modal pop-up, state tombol disabled.
   * Lapisan 2 (Data Persistence): Pengecekan status pada basis data setelah dilakukan page.reload() untuk mematahkan ilusi manipulasi DOM lokal.
   * Lapisan 3 (Side Effects / Efek Lanjutan): Verifikasi berkurangnya kuota sesi, catatan log kehadiran, atau tersedianya rekaman pada antrean email.
6. Lingkungan Pengujian (Test Environment)
        
Komponen 
|  | Spesifikasi Lingkungan Uji |
| --- | --- |
|  | Catatan / Konfigurasi Khusus |
|  | Operating System |
|  | Windows 11 64-bit / Linux Ubuntu 22.04 LTS |
|  | Lingkungan development tim |
|  | Web Server |
|  | PHP 8.3 Built-in Server / Nginx |
|  | Sesuai requirement Laravel 11 |
|  | Database |
|  | PostgreSQL / SQLite (In-Memory Testing) |
|  | Driver pdo_pgsql & pdo_sqlite aktif |
|  | Framework Backend |
|  | Laravel 11.x, Spatie Permission |
|  | Konfigurasi cache & session aktif |
|  | Frontend Stack |
|  | Blade, Alpine.js, Tailwind CSS, FullCalendar, html5-qrcode |
|  | Asset ter-bundle via Vite (npm run build) |
|  | Testing Tools |
|  | PHPUnit 11+, Playwright Python, Chromium Engine |
|  | Browser otomatisasi E2E |
|  | Mail Service Mock |
|  | Mailtrap Sandbox (SMTP Host: sandbox.smtp.mailtrap.io) |
|  | Pengujian email tanpa mengirim ke user riil |
|  | Camera Emulation |
|  | Chrome Fake Device Video Stream / Physical Webcam |
|  | Untuk memvalidasi pembacaan QR code scanner |


7. Kebutuhan Data Uji (Test Data Requirements)
Data uji terstruktur disiapkan melalui database seeders (UserSeeder, PtPackageSeeder, MemberManagementDemoSeeder):
1. Akun Pengguna (Actors):
   * ADM-01: Akun Admin Aktif (admin@gym.test / password123)
   * TRN-01: Akun Trainer 1 (budi@gym.test, Spesialisasi: Muscle Building)
   * TRN-02: Akun Trainer 2 (sari@gym.test, Spesialisasi: Weight Loss)
   * MEM-01: Akun Member dengan Membership Aktif & Kuota PT > 0 (amanda@test.com)
   * MEM-02: Akun Member Baru Belum Bayar (dimas@test.com)
   * MEM-03: Akun Member Kedaluwarsa/Expired (siti.expired@test.com)
2. Data Paket & Harga:
   * Paket Membership Gold: Rp3.000.000 (Durasi 365 hari)
   * Paket Membership Monthly: Rp350.000 (Durasi 30 hari)
   * Paket Sesi PT: 4 Sesi (Rp600.000), 8 Sesi (Rp1.100.000)
3. Data File & Token:
   * Berkas bukti transfer valid: sample_transfer_valid.webp (resolusi < 800px, 150KB)
   * Berkas bukti tidak valid: sample_executable.exe, large_image.png (> 5MB)
   * QR Code String: Hash payload terenkripsi FC-PASS-MEM01-VALID dan FC-PASS-MEM03-EXPIRED.
8. Kriteria Pengujian (Testing Criteria)

## 8.1 Pass / Fail Criteria

* PASS: Item pengujian dinyatakan LULUS jika dan hanya jika seluruh 3 lapisan Expected Result (UI, Data Persistence, dan Efek Samping) terverifikasi 100% cocok. Pada pengujian negatif, sistem wajib memberikan pesan error yang manusiawi dan menolak transaksi tanpa unhandled 500 error.
* FAIL: Item dinyatakan GAGAL jika salah satu lapisan assertion tidak sesuai (misal: UI menampilkan pesan sukses tetapi data database tidak berubah, atau sebaliknya).

## 8.2 Entry Criteria (Syarat Mulai)

1. Repository berada pada branch kerja yang stabil dan seluruh dependensi (composer install, npm install) terpasang.
2. Migrasi basis data dan seeder data awal selesai dieksekusi tanpa error.
3. Kredensial Mailtrap terkonfigurasi pada file .env.testing.

## 8.3 Exit Criteria (Syarat Selesai)

1. 100% test case berprioritas CRITICAL dan HIGH telah dieksekusi.
2. Tidak ada Blocker atau Critical Bug yang masih terbuka (0 Open Critical Defect).
3. Seluruh suite unit/feature test PHPUnit lulus 100%.
4. Dokumentasi Test Summary Report siap diserahkan.

## 8.4 Suspension & Resumption Criteria

* Suspensi: Pengujian ditangguhkan jika terjadi kerusakan fatal pada database engine, kegagalan kompilasi aset frontend Vite, atau pemblokiran rute yang menyebabkan halaman utama crash (500 Server Error).
* Resumsi: Pengujian dilanjutkan kembali setelah tim pengembang merilis commit perbaikan dan pipeline build kembali berstatus hijau (Passed).
9. Pembagian Peran & Tanggung Jawab (Roles & Responsibilities)
10. Jadwal Pengujian (Schedule & Timeline — 3 Days Cycle)
[Hari 1: Persiapan & Environment] 
[Hari 2: Eksekusi Utama & Logging] 
[Hari 3: Retest & Summary Report]
 - Finalisasi Test Data Sheet               
- Eksekusi Black-box 15 Fitur Ditinjau    
 - Verifikasi Bug Fixes (Retest)
 - Setup Mailtrap & DB SQLite/MySQL         
 - Eksekusi Automated Playwright E2E        
- Regression Testing
 - Smoke Test Rute Kritis                    
- Pencatatan Defect Log (Severity H/M/L)   
- Penyusunan Test Summary Report
* Hari 1 (Persiapan & Validasi Environment):
   * Setup database seeder untuk 3 role (Admin, Member, Trainer).
   * Validasi rute dan perizinan hak akses awal (Smoke Test).
   * Pengecekan kesiapan script otomatis dan Mailtrap sandbox.
* Hari 2 (Eksekusi Pengujian Utama):
   * Eksekusi manual & otomatis seluruh 15 fitur berstatus Ditinjau.
   * Pengujian skenario negatif dan edge cases (duplikasi booking, bypass role, upload berkas terlarang).
   * Dokumentasi temuan bug ke dalam lembar Defect Report.
* Hari 3 (Retesting, Regresi, & Penutupan):
   * Pengujian ulang (re-test) pada bug yang telah diperbaiki pengembang.
   * Eksekusi regression testing untuk memastikan perbaikan tidak merusak fitur lain.
   * Penyusunan dan penandatanganan dokumen Test Summary Report.
11. Produk Kerja Pengujian (Test Deliverables)
1. Dokumen Test Plan v1.0: Dokumen acuan strategi dan ruang lingkup pengujian (dokumen ini).
2. Dokumen Test Scenario (TS Sheet): Daftar skenario bisnis komprehensif (Verify [behavior] when [condition]).
3. Dokumen Test Case & Execution Record (TC Sheet): Prosedur langkah uji atomik dengan 3 lapisan Expected vs Actual Result.
4. Script Otomasi E2E (Playwright Python): Berkas tests/e2e/*.py beserta conftest.py yang siap dijalankan via CI/CD.
5. Defect Log & Test Summary Report: Rekapitulasi status eksekusi, persentase kelulusan, dan status Defect Severity.


12. Risiko Pengujian & Rencana Kontinjensi (Testing Risks & Contingencies)


Risiko Pengujian / Manajerial 
|  | Dampak |
| --- | --- |
|  | Rencana Kontinjensi (Mitigasi) |
|  | Kamera browser tidak dapat diakses pada pengujian lokal (isu SSL / non-HTTPS). |
|  | Sedang |
|  | Gunakan flag browser --use-fake-ui-for-media-stream dan inject mock QR string langsung ke method pemrosesan presensi. |
|  | Kuota bulanan SMTP Mailtrap habis di tengah pengujian notifikasi queue. |
|  | Sedang |
|  | Ubah driver mail di .env.testing ke mode log (MAIL_MAILER=log) dan validasi konten email melalui pembacaan storage/logs/laravel.log. |
|  | Inkonsistensi data state akibat eksekusi test sebelumnya yang tidak bersih (dirty state). |
|  | Tinggi |
|  | Wajib menggunakan trait RefreshDatabase pada PHPUnit dan menerapkan fixture teardown/cleanup API pada Playwright. |
|  | Sedang |
|  | Sedang |
|  | Seluruh test case dan skenario telah didokumentasikan secara terstandardisasi sehingga tugas eksekusi dapat langsung diambil alih oleh rekan kelompok lain (peer-testing). |


Pengesahan Dokumen (Sign-off)
Dipersiapkan dan disetujui untuk dijadikan pedoman pengujian resmi modul Fitur Ditinjau FitCore Gym:
* Quality Lead: Naufal Alif Adjani
* Tanggal: 8 November 2026
* Status: APPROVED FOR EXECUTION

