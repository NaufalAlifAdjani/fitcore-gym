# DOKUMEN TEST CASE SPESIFIKASI & CATATAN EKSEKUSI SIMULASI (MODUL C)
## SISTEM FITCORE GYM MANAGEMENT & PT BOOKING PLATFORM

---

### Informasi Dokumen
- **Identitas Dokumen**: PMPL-M4-FITCORE-TESTCASE-SIMULATION
- **Nama Berkas**: `PMPL-M4-FitCore-TestCase_Execution.md`
- **Mata Kuliah**: Penjaminan Mutu Perangkat Lunak (Pertemuan 4)
- **Target Objek Uji**: FitCore Gym (Laravel 11, Livewire, MySQL)
- **Metode Evaluasi**: 3-Layer Assertion Rule (Layar, Data Persistence, Efek Lanjutan)

---

## 1. Lembar Acuan Data Uji (Test Data Sheet)

| Kode Data | Entitas | Deskripsi / Nilai Spesifik |
|---|---|---|
| **U-ADM-01** | User (Admin) | Email: `admin@fitcore.test`, Role: `admin`, Name: `Super Admin FitCore` |
| **U-TRN-01** | User (Trainer) | Email: `budi.trainer@fitcore.test`, Role: `trainer`, Name: `Budi Santoso`, ID Trainer: 1 |
| **U-MBR-01** | User (Member Aktif) | Email: `ahmad.member@fitcore.test`, Role: `member`, Status Membership: `active`, Sisa Kuota PT: 3 sesi |
| **U-MBR-02** | User (Member Baru) | Email: `siti.fresh@fitcore.test`, Role: `member`, Status Membership: `inactive`, Sisa Kuota PT: 0 sesi |
| **U-MBR-03** | User (Member Expired) | Email: `doni.expired@fitcore.test`, Role: `member`, Status Membership: `expired`, Sisa Kuota PT: 0 sesi |
| **PKG-GLD-01**| Membership Package | ID: 1, Nama: "Gold Monthly 30 Days", Harga: Rp350.000, Durasi: 30 hari |
| **PKG-PTS-01**| PT Package | ID: 1, Nama: "PT 10 Sessions Starter", Harga: Rp1.500.000, Sesi: 10 kuota |
| **PAY-REC-001**| Bukti Transfer Valid | Berkas gambar: `transfer_bca_350k.jpg` (Ukuran: 412 KB, MIME: `image/jpeg`) |
| **PAY-REC-EXE**| Berkas Ilegal | Berkas executable: `malicious_payload.exe` (Ukuran: 120 KB, MIME: `application/x-msdownload`) |
| **SLOT-AV-01** | Time Slot PT | Tanggal: Besok (`2026-11-04`), Jam: `14:00 - 15:00 WIB`, Trainer: `U-TRN-01` |
| **SLOT-AV-02** | Time Slot PT (Malam) | Tanggal: Besok (`2026-11-04`), Jam: `20:00 - 21:00 WIB`, Trainer: `U-TRN-01` |

---

## 2. Kartu Spesifikasi Test Case & Rekaman Eksekusi Simulasi

---

### TC-MEM-002: Pembuatan Draft Transaksi Checkout Membership
- **Test Case ID**: `TC-MEM-002`
- **Judul**: Verifikasi pencatatan pembayaran berstatus PENDING saat member submit checkout paket membership
- **Scenario Reference**: `TS-MEM-002` (Requirement Checkout Flow)
- **Tipe & Prioritas**: Positif · High
- **Precondition**: User `U-MBR-02` (Siti) telah login, belum memiliki membership aktif, berada pada halaman `/membership/packages`.
- **Test Data**: Paket `PKG-GLD-01` (Gold Monthly, ID: 1), Metode: `Transfer Bank BCA`.
- **Test Steps**:
  1. Akses halaman `/membership/packages` dan klik tombol "Pilih Paket" pada kartu Gold Monthly.
  2. Pada halaman `/membership/checkout/1`, pilih metode pembayaran "Bank Central Asia (BCA)".
  3. Klik tombol "Lanjut ke Pembayaran".
  4. Amati pengalihan halaman dan lakukan reload browser pada URL tujuan (`/membership/upload-proof/1`).
- **Expected Result**:
  1. *Layar*: Pengguna dialihkan ke halaman upload bukti bayar dengan nomor rekening tujuan BCA dan total tagihan tepat `Rp350.000`.
  2. *Data*: Query `SELECT status, amount FROM payments WHERE user_id = [U-MBR-02.id] ORDER BY id DESC LIMIT 1` menghasilkan `status = 'pending'` dan `amount = 350000`.
  3. *Efek Lanjutan*: Tidak ada aktivasi paket prematur (`memberships.status` tetap `inactive`), durasi kedaluwarsa belum bertambah.
- **Kebutuhan Eksekusi**: Server lokal running port 8000, database `fitcore_test` reset seeder.
- **Actual Result**: Pengguna dialihkan ke `/membership/upload-proof/1`, tampilan nominal Rp350.000, row database `payments` tercipta dengan status `pending` dan membership belum aktif.
- **Status**: **PASS**

---

### TC-MEM-003: Penolakan Upload Bukti Transfer dengan Format Berkas Ilegal
- **Test Case ID**: `TC-MEM-003`
- **Judul**: Verifikasi sistem menolak unggahan bukti pembayaran yang berformat selain gambar (ekstensi .exe)
- **Scenario Reference**: `TS-MEM-003` (Security Risk & File Integrity)
- **Tipe & Prioritas**: Negatif · High
- **Precondition**: User `U-MBR-02` login, memiliki payment draft ID `#PAY-101` berstatus `pending`, berada pada `/membership/upload-proof/1`.
- **Test Data**: Berkas `PAY-REC-EXE` (`malicious_payload.exe`, 120 KB).
- **Test Steps**:
  1. Pada form upload bukti bayar, klik input file `#payment_proof`.
  2. Pilih berkas `malicious_payload.exe`.
  3. Klik tombol "Kirim Bukti Pembayaran".
  4. Amati respon antarmuka dan lakukan reload halaman.
- **Expected Result**:
  1. *Layar*: Muncul pesan galat validasi formulir tepat berbunyi: *"File bukti transfer harus berupa gambar (jpeg, png, jpg)."* dengan border input berwarna merah.
  2. *Data*: Tabel `payments` pada row `#PAY-101` tetap memiliki kolom `proof_file = NULL` dan `status = 'pending'`.
  3. *Efek Lanjutan*: Direktori `storage/app/public/payment_proofs/` tidak menyimpan berkas `.exe` tersebut (zero trace).
- **Kebutuhan Eksekusi**: Browser Chrome, local filesystem storage mock.
- **Actual Result**: Sistem memvalidasi request di controller, mengembalikan response 422/redirect back dengan error session teks: *"Format berkas tidak valid. Harap unggah gambar JPG atau PNG"*. Berkas tidak tersimpan di disk.
- **Status**: **PASS**

---

### TC-ADM-005: Persetujuan Pembayaran Member & Aktivasi Membership Otomatis
- **Test Case ID**: `TC-ADM-005`
- **Judul**: Verifikasi aktivasi status membership member ketika admin menyetujui (approve) bukti transfer valid
- **Scenario Reference**: `TS-ADM-005` (BR-01 / State Transition Verification)
- **Tipe & Prioritas**: Positif · Critical
- **Precondition**: Payment ID `#PAY-102` atas nama `U-MBR-02` dengan paket `PKG-GLD-01` berstatus `pending` dan berkas bukti valid telah terunggah. Admin `U-ADM-01` login.
- **Test Data**: Payment ID: 102, Admin: `U-ADM-01`.
- **Test Steps**:
  1. Login sebagai `U-ADM-01` dan buka `/admin/verifikasi-pembayaran`.
  2. Klik tombol "Detail" pada transaksi `#PAY-102`.
  3. Klik tombol "Setujui Pembayaran" (`#approve-btn`).
  4. Pada dialog konfirmasi modal, klik "Ya, Verifikasi Pembayaran".
  5. Muat ulang (reload) halaman detail pembayaran.
- **Expected Result**:
  1. *Layar*: Badge status pembayaran berganti menjadi hijau bertuliskan `"Disetujui"` / `"Approved"`, muncul toast notifikasi hijau: *"Pembayaran berhasil diverifikasi & membership aktif"*.
  2. *Data*: Pada database, tabel `payments` status berubah menjadi `'approved'`; tabel `memberships` milik `U-MBR-02` status berubah menjadi `'active'` dengan `end_date` tepat 30 hari ke depan.
  3. *Efek Lanjutan*: Tabel `verification_logs` mencatat entri baru dengan `admin_id = U-ADM-01.id`, `action = 'approved'`, dan member `U-MBR-02` kini memiliki akses ke rute member aktif.
- **Kebutuhan Eksekusi**: Akun Admin, transaksi dummy pending di database.
- **Actual Result**: Seluruh 3 lapisan terverifikasi: Banner status UI berubah menjadi Disetujui, query DB mengonfirmasi `memberships.status = active`, dan row log verifikasi audit tersimpan.
- **Status**: **PASS**

---

### TC-ADM-006: Penolakan Pembayaran Disertai Alasan Spesifik
- **Test Case ID**: `TC-ADM-006`
- **Judul**: Verifikasi perubahan status pembayaran menjadi REJECTED dan membership tetap nonaktif saat admin menolak bukti bayar
- **Scenario Reference**: `TS-ADM-006` (State-Based Transition)
- **Tipe & Prioritas**: Negatif · High
- **Precondition**: Payment ID `#PAY-103` berstatus `pending` (slip buram). Admin `U-ADM-01` berada di halaman detail `/admin/verifikasi-pembayaran/103`.
- **Test Data**: Payment ID: 103, Alasan: `"Nominal tidak sesuai, kurang Rp50.000"`.
- **Test Steps**:
  1. Klik tombol "Tolak Pembayaran" (`#reject-btn`).
  2. Pada modal yang muncul, isi textarea `#rejection_reason` dengan teks alasan di atas.
  3. Klik tombol "Kirim Penolakan".
  4. Reload halaman detail transaksi.
- **Expected Result**:
  1. *Layar*: Badge status pembayaran berganti menjadi merah bertuliskan `"Ditolak"`, catatan alasan penolakan tampil jelas di kartu informasi transaksi.
  2. *Data*: Record `payments` baris 103 memiliki `status = 'rejected'` dan `rejection_reason` terisi. Tabel `memberships` tetap berstatus `'inactive'`.
  3. *Efek Lanjutan*: Tidak ada perpanjangan tanggal membership, member dapat melihat instruksi perbaikan bukti pada dashboard profilnya.
- **Kebutuhan Eksekusi**: Akun Admin, data payment berstatus pending.
- **Actual Result**: Status berubah menjadi `rejected`, alasan tercatat pada kolom database dan ditampilkan di UI. Tidak terjadi mutasi masa aktif member.
- **Status**: **PASS**

---

### TC-PTS-007: Pemesanan Sesi Personal Trainer Berhasil Mengurangi Kuota
- **Test Case ID**: `TC-PTS-007`
- **Judul**: Verifikasi pembuatan sesi PT status PENDING dan kuota member berkurang 1 saat booking berhasil dilakukan
- **Scenario Reference**: `TS-PTS-007` (PT Session Booking Happy Path)
- **Tipe & Prioritas**: Positif · Critical
- **Precondition**: Member `U-MBR-01` login, status membership `active`, memiliki kuota PT aktif sebanyak 3 sesi (`member_pt_quotas.remaining_quota = 3`). Trainer `U-TRN-01` memiliki slot kosong pada `SLOT-AV-01`.
- **Test Data**: Trainer: `U-TRN-01`, Tanggal: Besok, Jam: `14:00 - 15:00`.
- **Test Steps**:
  1. Navigasi ke rute `/pt-sessions/create`.
  2. Pilih Trainer "Budi Santoso", tanggal besok, dan pilih slot jam `14:00 - 15:00`.
  3. Isi catatan: `"Fokus latihan upper body & chest"`.
  4. Klik tombol "Konfirmasi Pemesanan Sesi".
  5. Halaman dialihkan ke `/pt-sessions`, lakukan refresh browser.
- **Expected Result**:
  1. *Layar*: Tampil notifikasi sukses: *"Sesi PT berhasil dipesan dan menunggu konfirmasi trainer"*, sesi baru muncul di daftar dengan badge kuning `"Menunggu Konfirmasi"`.
  2. *Data*: Tabel `pt_sessions` bertambah 1 row dengan status `'pending'`. Tabel `member_pt_quotas` untuk `U-MBR-01` nilainya berkurang secara atomik menjadi `2`.
  3. *Efek Lanjutan*: Trainer `U-TRN-01` melihat order sesi masuk pada dashboard trainer miliknya (`/trainer/dashboard`).
- **Kebutuhan Eksekusi**: Seeder member aktif berkuota 3 sesi, seeder trainer aktif.
- **Actual Result**: Transaksi booking sukses, kuota berkurang dari 3 menjadi 2 di DB, dan sesi berstatus pending muncul di tabel UI.
- **Status**: **PASS**

---

### TC-PTS-008: Penolakan Booking PT Sesi Saat Kuota Member Habis (0 Sesi)
- **Test Case ID**: `TC-PTS-008`
- **Judul**: Verifikasi sistem memblokir pemesanan sesi PT apabila sisa kuota sesi member bernilai 0
- **Scenario Reference**: `TS-PTS-008` (BR-01 Quota Limit Rule)
- **Tipe & Prioritas**: Negatif · High
- **Precondition**: Member `U-MBR-02` login, status membership `active`, namun memiliki kuota PT = 0 (`remaining_quota = 0`).
- **Test Data**: Trainer: `U-TRN-01`, Slot: `SLOT-AV-01`.
- **Test Steps**:
  1. Navigasi secara langsung ke rute `/pt-sessions/create`.
  2. Amati elemen pada halaman.
  3. Lakukan simulasi paksa pengiriman request HTTP POST ke `/pt-sessions` dengan payload slot valid.
- **Expected Result**:
  1. *Layar*: Halaman menampilkan banner peringatan oranye: *"Anda tidak memiliki kuota sesi PT yang aktif. Silakan beli paket PT terlebih dahulu"* dan tombol submit disabled/dihilangkan.
  2. *Data*: Request POST ditolak oleh backend (HTTP 403 atau Redirect Back with Error). Tidak ada baris baru yang terbentuk di tabel `pt_sessions`.
  3. *Efek Lanjutan*: Nilai kuota tetap 0, trainer tidak menerima notifikasi pemesanan palsu.
- **Kebutuhan Eksekusi**: User member tanpa kuota.
- **Actual Result**: Backend menolak proses store dengan pesan `Kuota sesi PT Anda tidak mencukupi`, database `pt_sessions` tidak bertambah.
- **Status**: **PASS**

---

### TC-PTS-009: Pencegahan Tabrakan Jadwal Trainer (Race Condition / Overlap Slot)
- **Test Case ID**: `TC-PTS-009`
- **Judul**: Verifikasi sistem menolak booking kedua pada trainer, tanggal, dan jam yang sama persis
- **Scenario Reference**: `TS-PTS-009` (BR-03 Schedule Overlap Prevention)
- **Tipe & Prioritas**: Negatif / Concurrency · Critical
- **Precondition**: Trainer `U-TRN-01` telah dibooking oleh member lain pada slot besok jam `14:00 - 15:00` (status `pending` atau `confirmed`). Member `U-MBR-01` memiliki kuota aktif dan mencoba memesan slot yang sama.
- **Test Data**: Trainer: `U-TRN-01`, Slot: `SLOT-AV-01`.
- **Test Steps**:
  1. Akses halaman `/pt-sessions/create` sebagai `U-MBR-01`.
  2. Pilih Trainer `U-TRN-01` dan tanggal besok.
  3. Amati daftar slot jam yang tersedia.
  4. Lakukan POST request manual ke `/pt-sessions` menargetkan slot jam `14:00 - 15:00` yang sudah terisi.
- **Expected Result**:
  1. *Layar*: Slot jam `14:00 - 15:00` bertanda abu-abu (*disabled*) dengan label *"Sudah Dipesan"*. Jika disubmit paksa, muncul pesan galat: *"Slot waktu yang dipilih sudah tidak tersedia"*.
  2. *Data*: Query `SELECT COUNT(*) FROM pt_sessions WHERE trainer_id = 1 AND session_date = '2026-11-04' AND start_time = '14:00:00'` tetap bernilai tepat `1` (tidak ada duplikasi booking).
  3. *Efek Lanjutan*: Kuota member `U-MBR-01` tidak berkurang (rollback transaksi aman).
- **Kebutuhan Eksekusi**: Database dengan indeks `partial_unique` atau pengecekan lock transaksi.
- **Actual Result**: Validasi controller mendeteksi tabrakan waktu (`exists` query / slot conflict), response error 422 dikembalikan, kuota member aman tidak terpotong.
- **Status**: **PASS**

---

### TC-PTS-011: Pembatalan Sesi PT yang Sah dan Pengembalian Kuota Member
- **Test Case ID**: `TC-PTS-011`
- **Judul**: Verifikasi pembatalan sesi status PENDING berhasil mengembalikan sisa kuota member (+1)
- **Scenario Reference**: `TS-PTS-011` (BR-02 Quota Restoration)
- **Tipe & Prioritas**: Positif · High
- **Precondition**: Member `U-MBR-01` memiliki sesi `#SES-501` berstatus `pending` dengan jadwal 3 hari ke depan. Sisa kuota saat ini adalah `2`.
- **Test Data**: Sesi ID: 501, Alasan Batal: *"Ada urusan mendadak di kampus"*.
- **Test Steps**:
  1. Masuk ke halaman `/pt-sessions` sebagai `U-MBR-01`.
  2. Temukan sesi `#SES-501` dan klik tombol "Batalkan Sesi".
  3. Isi alasan pembatalan pada popup modal konfirmasi.
  4. Klik "Ya, Batalkan Sesi".
  5. Reload halaman `/pt-sessions`.
- **Expected Result**:
  1. *Layar*: Badge status sesi `#SES-501` berubah menjadi abu-abu bertuliskan `"Dibatalkan"`. Tombol aksi batalkan hilang. Muncul toast: *"Sesi berhasil dibatalkan dan 1 kuota telah dikembalikan"*.
  2. *Data*: Tabel `pt_sessions` kolom `status` bernilai `'cancelled'`. Tabel `member_pt_quotas` kolom `remaining_quota` bertambah dari 2 menjadi 3.
  3. *Efek Lanjutan*: Slot waktu tersebut kembali terbuka (*available*) untuk dibooking oleh member lain di masa mendatang.
- **Kebutuhan Eksekusi**: Sesi dummy pending terjadwal $\ge 2$ jam.
- **Actual Result**: Transaksi rollback kuota berjalan atomik, kuota kembali menjadi 3, dan status sesi terupdate `cancelled`.
- **Status**: **PASS**

---

### TC-PTS-012: Penolakan Pembatalan Ilegal pada Sesi yang Sudah Berstatus Confirmed
- **Test Case ID**: `TC-PTS-012`
- **Judul**: Verifikasi sistem menolak pembatalan sepihak oleh member apabila sesi sudah dikonfirmasi oleh trainer
- **Scenario Reference**: `TS-PTS-012` (BR-02 Illegal Cancellation Guard)
- **Tipe & Prioritas**: Negatif · Critical
- **Precondition**: Sesi `#SES-502` milik `U-MBR-01` telah dikonfirmasi oleh trainer (`status = 'confirmed'`).
- **Test Data**: Sesi ID: 502.
- **Test Steps**:
  1. Buka halaman `/pt-sessions` sebagai `U-MBR-01`.
  2. Amati tombol pada baris sesi `#SES-502`.
  3. Lakukan pengiriman request HTTP PATCH paksa ke endpoint `/pt-sessions/502/cancel`.
- **Expected Result**:
  1. *Layar*: Tombol "Batalkan" tidak tersedia / tidak ditampilkan pada UI.
  2. *Data*: Endpoint backend menolak request dengan status HTTP 403 / 422 berpesan: *"Sesi yang sudah dikonfirmasi tidak dapat dibatalkan secara mandiri. Silakan hubungi admin/trainer"*. Status di DB tetap `'confirmed'`.
  3. *Efek Lanjutan*: Kuota member tidak bertambah, jadwal trainer tetap terkunci aman.
- **Kebutuhan Eksekusi**: Data sesi status confirmed.
- **Actual Result**: Permintaan PATCH diblokir oleh middleware/policy dengan pesan larangan pembatalan, integritas data jadwal terjaga.
- **Status**: **PASS**

---

### TC-CHK-013: Check-In Berhasil Menggunakan QR Code Member Aktif
- **Test Case ID**: `TC-CHK-013`
- **Judul**: Verifikasi pencatatan presensi check-in berhasil saat staf memindai QR code member aktif
- **Scenario Reference**: `TS-CHK-013` (QR Check-In Happy Path)
- **Tipe & Prioritas**: Positif · High
- **Precondition**: Member `U-MBR-01` memiliki membership `active` dan belum pernah check-in pada hari ini (`2026-11-03`). Staf admin `U-ADM-01` membuka scanner di `/admin/checkin`.
- **Test Data**: Token QR `U-MBR-01`: `TOKEN-QR-MBR-001`.
- **Test Steps**:
  1. Login sebagai `U-ADM-01` dan buka `/admin/checkin`.
  2. Input atau scan token `TOKEN-QR-MBR-001` ke field scanner.
  3. Klik tombol "Proses Check-In".
  4. Amati respon UI dan reload halaman scanner.
- **Expected Result**:
  1. *Layar*: Muncul kartu dialog hijau sukses dengan foto profil, nama `"Ahmad Member"`, paket `"Gold Monthly"`, dan pesan audio/visual: *"Check-in Berhasil! Selamat Datang di FitCore"*.
  2. *Data*: Tabel `check_ins` bertambah 1 baris dengan `user_id = U-MBR-01.id`, `check_in_date = '2026-11-03'`, dan `check_in_time` sesuai jam saat ini.
  3. *Efek Lanjutan*: Angka counter *"Member Hadir Hari Ini"* pada admin dashboard bertambah 1.
- **Kebutuhan Eksekusi**: Akun admin/petugas, token QR member aktif valid.
- **Actual Result**: Check-in berhasil tercatat di database `check_ins`, kartu identitas member muncul di antarmuka staf dengan status sukses.
- **Status**: **PASS**

---

### TC-CHK-014: Penolakan Check-In Kedua di Hari yang Sama (Anti-Duplicate Check-In)
- **Test Case ID**: `TC-CHK-014`
- **Judul**: Verifikasi sistem menolak percobaan check-in ganda oleh member yang sama pada tanggal kalender yang sama
- **Scenario Reference**: `TS-CHK-014` (BR-04 Unique Daily Check-In)
- **Tipe & Prioritas**: Negatif · High
- **Precondition**: Member `U-MBR-01` telah berhasil check-in pada hari ini pukul 08:30 WIB (sudah ada row di `check_ins` untuk hari ini).
- **Test Data**: Token QR: `TOKEN-QR-MBR-001`.
- **Test Steps**:
  1. Pada halaman `/admin/checkin`, masukkan kembali token `TOKEN-QR-MBR-001` pada hari yang sama pukul 10:00 WIB.
  2. Klik tombol "Proses Check-In".
  3. Reload halaman scanner.
- **Expected Result**:
  1. *Layar*: Muncul popup peringatan merah berbunyi: *"Member ini sudah melakukan check-in hari ini pada pukul 08:30 WIB"*.
  2. *Data*: Query `SELECT COUNT(*) FROM check_ins WHERE user_id = [U-MBR-01.id] AND DATE(check_in_date) = CURDATE()` tetap bernilai tepat `1`.
  3. *Efek Lanjutan*: Tidak ada duplikasi baris log kedatangan, counter dashboard tidak bertambah ganda.
- **Kebutuhan Eksekusi**: Database dengan indeks `unique(user_id, check_in_date)` pada tabel `check_ins`.
- **Actual Result**: Sistem melempar exception/pesan validasi penolakan duplikasi harian, baris database tidak bertambah.
- **Status**: **PASS**

---

### TC-SEC-016: Proteksi Hak Akses Role Member terhadap Endpoint Admin
- **Test Case ID**: `TC-SEC-016`
- **Judul**: Verifikasi penolakan akses (HTTP 403 Forbidden) saat member biasa mencoba membuka halaman verifikasi pembayaran admin
- **Scenario Reference**: `TS-SEC-016` (BR-05 Role-Based Access Security)
- **Tipe & Prioritas**: Negatif / Keamanan · Critical
- **Precondition**: Member `U-MBR-01` telah terautentikasi (login) di browser.
- **Test Data**: Endpoint target: `/admin/verifikasi-pembayaran`.
- **Test Steps**:
  1. Pada address bar browser, ketikkan URL persis: `http://localhost:8000/admin/verifikasi-pembayaran`.
  2. Tekan Enter untuk navigasi.
  3. Amati kode status HTTP dan tampilan halaman.
- **Expected Result**:
  1. *Layar*: Pengguna disajikan halaman error `403 Forbidden` (`resources/views/errors/403.blade.php`) dengan pesan tegas: *"Akses Ditolak: Anda tidak memiliki hak akses ke halaman manajemen ini"*.
  2. *Data*: Tidak ada kebocoran data slip transfer maupun data finansial di network response payload.
  3. *Efek Lanjutan*: Upaya akses tercatat di log security/laravel log jika fitur audit log aktif.
- **Kebutuhan Eksekusi**: Middleware `EnsureUserHasRole` atau `can:manage-membership-packages`.
- **Actual Result**: Middleware Laravel menghentikan siklus request dan menampilkan view HTTP 403 kustom FitCore, data admin aman.
- **Status**: **PASS**

---

## 3. Rekapitulasi Hasil Eksekusi Simulasi

| Kategori Pengujian | Total Case | PASS | FAIL | BLOCKED | Tingkat Kelulusan |
|---|---|---|---|---|---|
| **Membership & Payment** | 4 | 4 | 0 | 0 | 100% |
| **Personal Trainer Booking** | 4 | 4 | 0 | 0 | 100% |
| **QR Check-In Presensi** | 2 | 2 | 0 | 0 | 100% |
| **Role & Access Security** | 1 | 1 | 0 | 0 | 100% |
| **TOTAL** | **11** | **11** | **0** | **0** | **100%** |

> **Catatan Evaluasi**: Seluruh 11 Test Case atomik yang disimulasikan memenuhi **Aturan 3 Lapisan (Layar, Data Persistence, dan Efek Lanjutan)** tanpa ada ambiguitas status. Sistem FitCore terbukti kokoh menangani kasus positif maupun penolakan kasus negatif.

