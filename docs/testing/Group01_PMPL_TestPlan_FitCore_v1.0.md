# DOKUMEN TEST PLAN v1.0 (IEEE 829)
## SISTEM FITCORE GYM MANAGEMENT & PT BOOKING PLATFORM

---

### Informasi Dokumen
- **Nama Proyek**: FitCore Gym Management & Personal Trainer Booking Platform
- **Versi Dokumen**: 1.0 (Limited Release Edition)
- **Kelompok**: Group 01 - PMPL
- **Mata Kuliah**: Penjaminan Mutu Perangkat Lunak (PMPL)
- **Distribusi Peran Anggota**:
  - **Anggota 1**: Test Lead & Quality Governance *(Lead & Test Plan Owner)*
  - **Anggota 2**: Test Analyst & Scenario Designer *(Scenario & Test Case Architect)*
  - **Anggota 3**: Test Environment, Automation & Defect Analyst *(Automation & Simulation Engineer)*
- **Tanggal Rilis Rencana**: 3 November 2026
- **Durasi Siklus Pengujian**: 3 Hari Kerja (*Time-Boxed Sprint*)

---

## 1. Pendahuluan & Tujuan Pengujian
### 1.1 Latar Belakang
FitCore adalah aplikasi berbasis Laravel 11/12 yang memfasilitasi operasional pusat kebugaran (gym), mencakup manajemen paket langganan membership, verifikasi pembayaran manual, pemesanan sesi Personal Trainer (PT) beserta kuotanya, serta sistem check-in presensi member menggunakan scan QR Code unik harian.

Rilis terbatas (*limited release*) ini difokuskan untuk menguji keandalan alur transaksi membership, verifikasi manual oleh admin, alur booking sesi PT tanpa terjadinya tabrakan jadwal (*race condition / double-booking*), serta pencegahan check-in ganda member di hari yang sama.

### 1.2 Test Objectives (Tujuan Terukur)
1. **Verifikasi Keutuhan Transaksi**: Memastikan 100% alur checkout membership dan upload bukti transfer tersimpan dengan status `pending` dan dapat diverifikasi oleh admin dengan status `approved` atau `rejected` tanpa anomali data.
2. **Pencegahan Double Booking Sesi PT**: Memastikan sistem secara konsisten menolak pemesanan sesi PT pada trainer yang sama, tanggal yang sama, dan rentang jam yang saling tumpang tindih (*time-slot overlap*).
3. **Integritas Kuota & Pembatalan Sesi**: Memastikan kuota sesi PT member berkurang saat booking dibuat dan dikembalikan (*restocked*) secara atomik ketika pembatalan sesi yang sah dilakukan sebelum batas waktu minimum pembatalan.
4. **Validasi Check-in Presensi Unik**: Memastikan token QR dinamis pada kartu digital member hanya dapat digunakan untuk check-in berhasil sebanyak 1 kali per hari kerja per member.
5. **Penegakan Hak Akses (Role-Based Access Control - RBAC)**: Memastikan member biasa dan trainer tidak dapat mengakses rute manajemen admin (seperti verifikasi pembayaran dan manajemen paket).

---

## 2. Test Items (Item yang Diuji)
Objek uji dalam pengujian limited release ini mengacu pada modul FitCore:
- **F1**: Modul Autentikasi & Otorisasi RBAC (`EnsureUserHasRole` & Policies)
- **F2**: Modul Katalog & Checkout Paket Membership (`MemberMembershipPackageController`)
- **F3**: Modul Verifikasi Bukti Pembayaran oleh Admin (`PaymentVerificationController`)
- **F4**: Modul Booking Sesi Personal Trainer & Alokasi Slot (`PtSessionController`, `PtSessionSlotController`)
- **F5**: Modul Pembatalan & Manajemen Status Sesi PT (`PtSessionController@cancel`, `TrainerDashboardController`)
- **F6**: Modul Digital Card & Scanner QR Check-in (`MemberCardController`, `CheckInController`)
- **F7**: Modul Quota Tracking & Histori Transaksi Member (`TransactionHistoryController`, `MemberPtQuota`)

---

## 3. Test Scope (Cakupan Pengujian)
### 3.1 In-Scope (Fitur Kritis Terpilih)
Mengingat durasi rilis terbatas selama 3 hari, pengujian difokuskan pada 5 area fungsional berisiko tinggi:
1. **F2 & F3 - Checkout & Verifikasi Pembayaran**: Area finansial berisiko tinggi terhadap kecurangan transfer palsu dan keterlambatan aktivasi paket.
2. **F4 - Booking Sesi PT & Konflik Slot**: Area dengan potensi tinggi terjadinya *race condition* pemesanan bersamaan.
3. **F5 - Pembatalan Sesi PT**: Area yang berdampak langsung pada hak kuota member dan komisi trainer.
4. **F6 - QR Check-in Harian**: Area operasional gerbang masuk gym guna mencegah penyalahgunaan keanggotaan.
5. **F1 - Role Isolation**: Menjamin proteksi rute finansial dari akses yang tidak berwenang.

### 3.2 Out-of-Scope (Di Luar Cakupan)
Fitur dan area berikut dikeluarkan dari pengujian limited release kali ini:
1. **Payment Gateway Otomatis (Midtrans / Xendit Live Callback)**: Sistem saat ini menggunakan verifikasi bukti transfer manual via slip mutasi bank lokal. Integrasi webhook live di luar cakupan rilis 3 hari.
2. **Performance / Stress Load Testing**: Tidak dilakukan uji beban ribuan *concurrent users* karena keterbatasan server *sandbox* lokal.
3. **Aplikasi Native Mobile (iOS / Android Swift / Kotlin)**: Akses kartu member diuji via Responsive Web Browser (Chrome Mobile Emulation), bukan aplikasi native store.
4. **Modul Penggajian & Perhitungan Komisi Trainer**: Fitur pencairan komisi masih berupa modul placeholder (*view-only*).

---

## 4. Prioritas Risiko Produk (Product Risk Matrix)

| Risk ID | Deskripsi Risiko | Kemungkinan (P) | Dampak (I) | Tingkat Risiko | Mitigasi Pengujian |
|---|---|---|---|---|---|
| **PR-01** | Transaksi gantung: Bukti transfer diunggah tapi status payment tidak berubah atau gagal memicu aktivasi membership saat diapprove. | M (Medium) | H (High) | **CRITICAL** | Uji transisi status State-Machine `pending` $\rightarrow$ `approved` dan verifikasi field `memberships.status` menjadi `active`. |
| **PR-02** | *Race Condition Double-Booking*: Dua member melakukan booking sesi PT pada trainer dan jam yang sama secara bersamaan. | M (Medium) | H (High) | **CRITICAL** | Uji isolasi transaksi database, partial unique index, dan negative test untuk slot yang sudah diambil. |
| **PR-03** | Pembatalan Ilegal: Member membatalkan sesi PT yang sudah berstatus `confirmed` atau saat sesi sedang berlangsung untuk mencurangi kuota. | H (High) | M (Medium) | **HIGH** | Uji aturan bisnis pembatalan sesi (hanya status `pending`/`scheduled` yang diizinkan sesuai SOP). |
| **PR-04** | Duplikasi QR Check-in: Member menggunakan screenshot QR token yang sama untuk memasukkan temannya di hari yang sama. | H (High) | M (Medium) | **HIGH** | Uji database constraint `unique_checkin_per_day` dan verifikasi pesan penolakan jika sudah check-in hari ini. |
| **PR-05** | *Privilege Escalation*: Member biasa mengakses URL `admin/verifikasi-pembayaran` dengan memanipulasi path URL. | L (Low) | H (High) | **HIGH** | Uji negatif RBAC middleware: pastikan HTTP 403 Forbidden atau redirect login. |
| **PR-06** | Upload Berkas Berbahaya / Ukuran Berlebih: Pengguna mengunggah berkas selain gambar (misal `.exe`, `.php`) atau berkas $> 2$MB pada bukti bayar. | M (Medium) | M (Medium) | **MEDIUM** | Uji validasi MIME type (`image/jpeg,image/png,image/jpg`) dan batas ukuran berkas Laravel validator. |

---

## 5. Test Approach (Pendekatan Pengujian)
Pengujian menggunakan kombinasi teknik penjaminan mutu:
1. **Black-Box Functional Testing**:
   - *Equivalence Partitioning (EP)*: Pemilahan input valid/invalid pada upload gambar dan nominal.
   - *Boundary Value Analysis (BVA)*: Pengujian batas ukuran gambar (tepat 2.048 KB, 2.049 KB) serta jam booking sesi gym (08:00 WIB, 21:00 WIB).
   - *State Transition Testing*: Pengujian siklus hidup pembayaran (`pending` $\rightarrow$ `approved`/`rejected`) dan status sesi PT (`pending` $\rightarrow$ `confirmed` $\rightarrow$ `completed`/`cancelled`).
2. **Integration Testing**: Memvalidasi integrasi antara modul pembayaran, relasi kuota PT (`member_pt_quotas`), dan tabel riwayat transaksi (`payments`).
3. **Automated E2E Testing (Playwright Python)**: Menjalankan skenario kritis *end-to-end* secara deterministik menggunakan fixture API dan asersi 3 lapisan.

---

## 6. Test Environment & Data Requirements
### 6.1 Spesifikasi Lingkungan Uji
- **Web Server / Backend**: PHP 8.3 CLI / `php artisan serve` (Port: 8000), Laravel Framework 11.x.
- **Database**: MySQL 8.0 / MariaDB 10.11 (Database dedicated: `fitcore_test`).
- **Storage Driver**: Local Disk Driver (`storage/app/public`) dengan `php artisan storage:link`.
- **Target Browser**: Google Chrome (Chromium Engine v124+) & Firefox (Gecko Engine v125+).
- **Automation Runner**: Python 3.11+, `pytest`, `pytest-playwright`.
- **Justifikasi Absennya Browser iOS**: Pengujian native Safari iOS ditiadakan karena keterbatasan infrastruktur macOS di lingkungan runner lokal; digantikan oleh Chromium Mobile Emulation (WebKit User-Agent).

### 6.2 Data Sheet Requirements (Standarisasi Kode Uji)
- **Akun Uji Terdaftar**:
  - `U-ADM-01`: Administrator (`admin@fitcore.test` / password)
  - `U-TRN-01`: Personal Trainer Senior (`trainer1@fitcore.test` / password)
  - `U-MBR-01`: Member Aktif Berkuota (`member.active@fitcore.test` / password)
  - `U-MBR-02`: Member Baru Tanpa Paket (`member.fresh@fitcore.test` / password)
  - `U-MBR-03`: Member Nonaktif / Expired (`member.expired@fitcore.test` / password)
- **Paket Membership & PT**:
  - `PKG-MEM-01`: Paket Bulanan "Gold Monthly" (Rp350.000, durasi 30 hari)
  - `PKG-PT-01`: Paket PT "10 Sessions Pack" (Rp1.500.000, 10 kuota sesi)
- **Berkas Dummy Uji**:
  - `FILE-VALID-01`: `sample_receipt.jpg` (350 KB, MIME: `image/jpeg`)
  - `FILE-INVALID-EXT`: `exploit_script.php` (4 KB)
  - `FILE-OVERSIZED`: `large_receipt.png` (3.2 MB)

---

## 7. Testing Criteria (Kriteria Pengujian)
### 7.1 Pass / Fail Criteria
- **PASS**: Suatu item uji dinyatakan Lulus apabila **ketiga lapisan** (UI, Data Persistence di Database, dan Side Effects) terpenuhi 100% sesuai spesifikasi *expected result*.
- **FAIL**: Dinyatakan Gagal bila terdapat minimal 1 lapisan yang tidak sesuai, terjadi crash HTTP 500, data korup, atau kebocoran otorisasi.

### 7.2 Entry Criteria (Syarat Memulai Pengujian)
1. Source code branch `develop` berhasil dibuild (`composer install`, `npm run build`).
2. Database test migration dan seeder dasar berhasil dijalankan tanpa error (`php artisan migrate:fresh --seed`).
3. Konfigurasi kredensial uji (`.env.testing`) terpasang dan server lokal merespon HTTP 200 pada halaman login.

### 7.3 Exit Criteria (Syarat Selesai Pengujian)
1. 100% skenario pengujian kategori *High* dan *Critical* telah dieksekusi.
2. Tidak ada cacat (*defect*) berstatus **Blocker** atau **Critical** yang masih terbuka (*unresolved*).
3. Minimal 90% dari seluruh Test Case berstatus **PASS**.
4. Semua artefak pengujian (Test Plan, Test Scenario, Test Case & Simulation, E2E Code) terdokumentasi lengkap.

### 7.4 Suspension & Resumption Criteria
- **Suspension (Penghentian Sementara)**: Pengujian dihentikan apabila server crash terus menerus, kegagalan fatal pada migrasi database yang menghapus skema, atau runner E2E mengalami kegagalan global di luar aplikasi.
- **Resumption (Pelanjutan Kembali)**: Pengujian dilanjutkan setelah tim memperbaiki defect lingkungan, memverifikasi ulang koneksi DB dengan migrasi ulang, dan memastikan endpoint login kembali merespon HTTP 200.

---

## 8. Roles & Responsibilities (Pembagian Tugas Tim)

| Peran | Penanggung Jawab | Tanggung Jawab Utama |
|---|---|---|
| **Test Lead** | Anggota 1 | Menentukan strategi uji, menyusun Test Plan v1.0, mengelola prioritas risiko, mengawasi kepatuhan jadwal, dan menyetujui kriteria Exit. |
| **Test Analyst** | Anggota 2 | Merancang matriks Test Scenario (WHAT), menyusun kartu atomik Test Case (HOW), menyiapkan test data sheet, dan melakukan verifikasi Business Rules (BR-01 s.d BR-05). |
| **Test Environment & Doc Analyst** | Anggota 3 | Menyiapkan konfigurasi database uji, mengimplementasikan script otomatisasi Playwright Python, merekam log eksekusi simulasi, dan menyusun laporan defect. |

---

## 9. Jadwal Eksekusi (3-Day Limited Release Timeline)

```
Hari 1: Persiapan & Penyelarasan
├── Setup Test Environment & Seeder DB FitCore
├── Finalisasi Test Plan & Matriks Skenario Uji (Modul A & B)
└── Validasi Ketersediaan Test Data Sheet & Mock File

Hari 2: Eksekusi Utama & Simulasi
├── Eksekusi Test Case Fungsional Manual/Simulasi (Modul C)
├── Pengujian Transisi State (Checkout -> Approval -> Booking)
└── Pencatatan Temuan Cacat (Defect Logging) & Triage Awal

Hari 3: Automasi, Retest & Pelaporan
├── Eksekusi Otomasi E2E Playwright Python (Modul D)
├── Verifikasi Ulang Perbaikan Cacat (Regression Testing)
└── Evaluasi Exit Criteria & Penyerahan Dokumen PMPL v1.0
```

---

## 10. Deliverables (Artefak Pengujian)
1. **Dokumen Test Plan v1.0** (`Group01_PMPL_TestPlan_FitCore_v1.0.md`)
2. **Dokumen Test Scenario Konsolidasi v1.0** (`Group01_PMPL_TestScenario_FitCore_v1.0.md`)
3. **Dokumen Test Case & Catatan Eksekusi Simulasi** (`PMPL-M4-FitCore-TestCase_Execution.md`)
4. **Dokumen Spesifikasi & Suite E2E Playwright Python** (`Group01_PMPL_Playwright_Automation_Suite.md`)

---

## 11. Risiko Manajerial & Rencana Kontinjensi (Testing Risks & Contingencies)
1. **Risiko**: Database terkontaminasi oleh data eksekusi uji sebelumnya sehingga menyebabkan asersi duplikasi gagal.
   - *Kontinjensi*: Mengimplementasikan database reset hook (`DatabaseTransactions` atau `migrate:fresh --seed`) sebelum setiap siklus pengujian.
2. **Risiko**: Salah satu anggota tim sakit atau berhalangan pada Hari ke-2.
   - *Kontinjensi*: Seluruh Test Case didokumentasikan dengan format kartu atomik presisi sehingga anggota lain dapat langsung melanjutkan eksekusi tanpa jeda transfer pengetahuan.
3. **Risiko**: Waktu tidak mencukupi untuk membuat script otomasi Playwright seluruh modul.
   - *Kontinjensi*: Menerapkan prioritas selektif; script Playwright difokuskan secara ketat pada skenario berisiko tertinggi (Double booking PT dan Verifikasi pembayaran).

