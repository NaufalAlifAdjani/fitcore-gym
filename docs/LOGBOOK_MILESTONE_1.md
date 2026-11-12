# Logbook Perkembangan Proyek FitCore — Milestone 1: Minggu 1 – 8 (24 Agustus – 18 Oktober 2026)

**Nama Sistem**: FitCore (Sistem Informasi Manajemen dan Reservasi Gym Berbasis Web)  
**Repositori**: [NaufalAlifAdjani/fitcore-gym](https://github.com/NaufalAlifAdjani/fitcore-gym)  
**Fase Milestone 1**: Fondasi Proyek, Analisis Kebutuhan, Autentikasi 3 Role, Katalog Membership, dan Presensi QR Scanner  
**Tim Pengembang (SIB-3A)**:
- **Naufal Alif Adjani** (244107060090) — Full-stack Developer (Booking & Penjadwalan)
- **Achmad Alfi Maksum** (244107060009) — Full-stack Developer (Pembayaran & Paket)
- **Muhammad Rif’at Syamlan** (244107060150) — Full-stack Developer (Riwayat, Notifikasi & Feedback)
- **Siti Aisyah** (244107060038) — Full-stack Developer (Akses & Identitas)

---

## 📊 I. Rangkuman Tabel Logbook Mingguan — Milestone 1 (Minggu 1 – 8)

| Minggu | Pekerjaan Selesai | Dikerjakan Oleh | Kendala | Rencana Berikutnya |
| :--- | :--- | :--- | :--- | :--- |
| **Minggu 1**<br>(24–30 Agt 2026) | Riset kompetitor aplikasi gym sejenis, evaluasi tech stack (Laravel, Livewire/Alpine, MySQL), dan perumusan ide awal produk FitCore. | Seluruh Tim (Naufal, Alfi, Rif’at, Aisyah) | Menentukan batasan ruang lingkup antara aplikasi gym enterprise vs skala menengah. | Menyusun problem statement dan business case formal. |
| **Minggu 2**<br>(31 Agt–06 Sep 2026) | Perumusan Problem Statement & Business Case (M02), penentuan target nilai bisnis, perumusan 3 role aktor utama. | Seluruh Tim | Mengidentifikasi titik kritis inefisiensi pencatatan kartu member manual dan penagihan. | Menyiapkan rencana wawancara dan stakeholder register. |
| **Minggu 3**<br>(07–13 Sep 2026) | Wawancara mendalam narasumber (Member & PT Gym Mas K dan Gym Mas S), penyusunan Stakeholder Matrix & Engagement Plan (M03). | Naufal Alif Adjani, Siti Aisyah | Menyelaraskan perbedaan kebijakan sesi PT, batas waktu reservasi (24 jam), dan sistem scan QR. | Penyusunan Project Charter dan struktur pembagian peran tim. |
| **Minggu 4**<br>(14–20 Sep 2026) | Penyusunan Project Charter (M05), penyusunan draf proposal lengkap, submit & review Checkpoint 1. | Seluruh Tim | Pembagian modul agar adil dan memiliki dependensi fungsional yang kohesif. | Merinci scope statement dan dekomposisi WBS level 0-3. |
| **Minggu 5**<br>(21–27 Sep 2026) | Penyusunan Scope Statement (M06a), Work Breakdown Structure (M06b), Model Kebutuhan & Matriks Use Case (M06c), Test Plan awal (M07). | Seluruh Tim | Memastikan rincian 22+ fitur use case saling terkait tanpa alur logika yang menggantung. | Inisialisasi repositori Git dan perancangan skema basis data fisik. |
| **Minggu 6**<br>(28 Sep–04 Okt 2026) | Inisialisasi repo Git, setup arsitektur dasar Laravel, konfigurasi database MySQL, migrasi awal tabel users & membership. | Naufal Alif Adjani | Penyesuaian versi dependensi PHP 8.3 dan konfigurasi awal Tailwind CSS. | Membangun layout Blade master, seeder, dan modul autentikasi 3 role. |
| **Minggu 7**<br>(05–11 Okt 2026) | Layout dasar Blade + Tailwind, form register member & validasi email, alur login admin/PT/member, modul katalog paket membership, seeder master bank & akun dummy. | Siti Aisyah, Achmad Alfi Maksum, Naufal, Rif’at | Merge conflict awal antara branch register-membership dan auth-login. | Membangun alur pembayaran manual dan scanner presensi QR. |
| **Minggu 8**<br>(12–18 Okt 2026) | Fitur beli paket sesi PT, scanner QR camera browser (html5-qrcode), proteksi duplikasi check-in 1x/hari, scheduler auto-expired status, form booking sesi PT. | Muhammad Rif’at Syamlan, Siti Aisyah, Naufal | Integrasi library html5-qrcode memerlukan penanganan permission kamera browser dan context HTTPS. | Mengembangkan modul verifikasi transfer admin dan kalender PT pada Milestone 2. |

---

## 📅 II. Logbook Harian Detail per Minggu — Milestone 1 (Minggu 1 – 8)

### 🔹 MINGGU 1 (24–30 Agustus 2026) — Fase Inisiasi & Studi Kelayakan

- **2026-08-25** — **Diskusi Tim** (*Seluruh Tim*):
  Brainstorming ide proyek sistem informasi gym berbasis web, identifikasi masalah penagihan dan reservasi manual.
- **2026-08-27** — **Riset Pasar** (*Seluruh Tim*):
  Benchmarking fitur kompetitor aplikasi kebugaran (GymMaster, Glofox) serta perumusan fitur diferensiasi (QR scanner lokal & auto-plotting jadwal PT).
- **2026-08-29** — **Evaluasi Teknis** (*Seluruh Tim*):
  Menentukan tech stack utama: Laravel 11/12, PHP 8.3, MySQL 8.0, Tailwind CSS, Alpine.js / Livewire.

### 🔹 MINGGU 2 (31 Agustus – 06 September 2026) — Fase Analisis Masalah & Business Case (M02)

- **2026-09-01** — **Analisis Masalah** (*Seluruh Tim*):
  Merumuskan problem statement dari perspektif member (jadwal PT tidak pasti) dan owner (member expired tidak terpantau).
- **2026-09-03** — **Penyusunan M02** (*Seluruh Tim*):
  Menyusun dokumen Problem Statement & Business Case (M02) mengenai estimasi efisiensi operasional gym sebesar 60%.
- **2026-09-05** — **Penetapan Sasaran** (*Seluruh Tim*):
  Menentukan 3 aktor utama sistem: Member, Personal Trainer, dan Admin/Owner.

### 🔹 MINGGU 3 (07–13 September 2026) — Fase Wawancara & Analisis Stakeholder (M03)

- **2026-09-08** — **Wawancara Mitra 1** (*Naufal Alif Adjani, Siti Aisyah*):
  Wawancara narasumber Member Gym Mas S terkait kendala antrean check-in fisik dan fleksibilitas booking paket.
- **2026-09-09** — **Wawancara Mitra 2** (*Naufal Alif Adjani, Siti Aisyah*):
  Wawancara Member & Personal Trainer Gym Mas K mengenai batas reservasi (max 24 jam sebelum sesi) dan komisi trainer.
- **2026-09-11** — **Penyusunan M03** (*Seluruh Tim*):
  Membuat Stakeholder Register & Power-Interest Grid (M03) untuk memetakan strategi komunikasi dengan mitra dan dosen pengampu.

### 🔹 MINGGU 4 (14–20 September 2026) — Fase Project Charter & Checkpoint 1 (M05)

- **2026-09-15** — **Project Charter M05** (*Seluruh Tim*):
  Menyusun Project Charter (M05) mencakup tata kelola tim, sasaran linimasa 16 minggu, serta aturan kerja kelompok.
- **2026-09-17** — **Penyusunan Proposal** (*Seluruh Tim*):
  Menyusun BAB I s/d BAB IV proposal PBL: latar belakang, rumusan masalah, batasan asumsi, dan kriteria keberhasilan.
- **2026-09-19** — **Submit Checkpoint 1** (*Seluruh Tim*):
  Finalisasi proposal dan pengumpulan dokumen Checkpoint 1 kepada dosen pengampu.

### 🔹 MINGGU 5 (21–27 September 2026) — Fase Ruang Lingkup, WBS & Model Kebutuhan (M06-M07)

- **2026-09-22** — **Scope Statement M06a** (*Seluruh Tim*):
  Mendefinisikan deliverable kunci produk, batasan in-scope (transaksi manual, QR check-in) dan out-scope (payment gateway otomatis, native mobile app).
- **2026-09-24** — **WBS Dekomposisi M06b** (*Seluruh Tim*):
  Memecah pekerjaan ke dalam Work Breakdown Structure (WBS Level 0–3) sebanyak 6 work package utama.
- **2026-09-26** — **Model Use Case & Test Plan** (*Seluruh Tim*):
  Menyusun Matriks Use Case per aktor (M06c) dan dokumen Test Plan awal (M07) sebelum implementasi kode.

### 🔹 MINGGU 6 (28 September – 04 Oktober 2026) — Setup Repositori & Inisialisasi Proyek (3 Commits)

#### 📅 Tanggal 2026-10-01 (1 Commits)
- [`5f19c44`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5f19c44) (**Naufal Alif Adjani**): chore: initial Laravel project

#### 📅 Tanggal 2026-10-03 (1 Commits)
- [`4c6f6e3`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4c6f6e3) (**Naufal Alif Adjani**): install depedencies

#### 📅 Tanggal 2026-10-04 (1 Commits)
- [`8e34c56`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8e34c56) (**Naufal Alif Adjani**): inisialisasi database

### 🔹 MINGGU 7 (05–11 Oktober 2026) — Fondasi UI Layout, Autentikasi 3 Role & Master Data (36 Commits)

#### 📅 Tanggal 2026-10-07 (6 Commits)
- [`dbfe501`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/dbfe501) (**Naufal Alif Adjani**): fix seeder
- [`db21a1d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/db21a1d) (**Naufal Alif Adjani**): fix error in bank seeder
- [`daef7d1`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/daef7d1) (**Naufal Alif Adjani**): update seeder
- [`96859d5`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/96859d5) (**Naufal Alif Adjani**): hapus file sampah
- [`6cf713f`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6cf713f) (**Naufal Alif Adjani**): add seeder
- [`1827268`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1827268) (**Naufal Alif Adjani**): booking-sessions

#### 📅 Tanggal 2026-10-08 (2 Commits)
- [`5ba3e5f`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5ba3e5f) (**Achmad Alfi Maksum**): feat: implement membership package management module
- [`5c2aae8`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5c2aae8) (**Naufal Alif Adjani**): FE dan BE masih berantakan

#### 📅 Tanggal 2026-10-09 (20 Commits)
- [`9c39163`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/9c39163) (**Siti Aisyah**): fix: perbaikan halaman  login admin
- [`80f0019`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/80f0019) (**Siti Aisyah**): feat: menyelesaikan halaman dan alur login admin
- [`12b85ba`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/12b85ba) (**Siti Aisyah**): feat: menambahkan halaman dan alur register membership
- [`1c37bf7`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1c37bf7) (**Achmad Alfi Maksum**): feat(member): implementasi katalog paket membership, checkout, dan upload bukti bayar
- [`ad49f87`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ad49f87) (**Achmad Alfi Maksum**): feat(admin): refactor tampilan manajemen paket dan harga menggunakan reusable components
- [`fc4eb06`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/fc4eb06) (**Achmad Alfi Maksum**): feat(admin): implementasi verifikasi pembayaran dan reusable blade components
- [`e1892c1`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e1892c1) (**Achmad Alfi Maksum**): fix: add unverified state to UserFactory and resolve auth merge conflicts
- [`d1ab51b`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d1ab51b) (**Achmad Alfi Maksum**): fix: resolve conflict with register-membership
- [`0db1957`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0db1957) (**Achmad Alfi Maksum**): Merge remote-tracking branch 'origin/feature/register-membership' into feat/membership-package
- [`6f9b00d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6f9b00d) (**Achmad Alfi Maksum**): fix: resolve conflict with auth-login
- [`f3f6294`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f3f6294) (**Achmad Alfi Maksum**): feat: simpan verifikasi pembayaran sebelum merge auth
- [`b3b1fa3`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b3b1fa3) (**Achmad Alfi Maksum**): fix: resolve merge conflicts and align with develop
- [`74b5b83`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/74b5b83) (**Achmad Alfi Maksum**): fix: resolve merge conflicts with develop
- [`4bb3761`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4bb3761) (**Achmad Alfi Maksum**): feat: simpan fitur verifikasi pembayaran
- [`c2f697d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c2f697d) (**Naufal Alif Adjani**): fix card layout session
- [`ce35f3d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ce35f3d) (**Siti Aisyah**): fix: perbaikan halaman  login admin
- [`cea2ff1`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/cea2ff1) (**Siti Aisyah**): feat: menyelesaikan halaman dan alur login admin
- [`835c6c0`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/835c6c0) (**Siti Aisyah**): Merge branch 'feature/register-membership' into feature/auth-login
- [`93fbb4f`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/93fbb4f) (**Siti Aisyah**): feat: menambahkan halaman dan alur register membership
- [`211da72`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/211da72) (**Siti Aisyah**): feat: membuat tampilan halaman login member dan trainer

#### 📅 Tanggal 2026-10-10 (2 Commits)
- [`7724bbb`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7724bbb) (**Achmad Alfi Maksum**): feat(member): tambahkan dashboard controller, gate otorisasi, dan auth test
- [`696c84d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/696c84d) (**Achmad Alfi Maksum**): fix(member): perbaikan alur login redirect dan styling checkout sesuai mockup figma

#### 📅 Tanggal 2026-10-11 (6 Commits)
- [`eb1626c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/eb1626c) (**Naufal Alif Adjani**): add button detail latihan, fix layouting, filter
- [`0c3b040`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0c3b040) (**Muhammad Rif’at Syamlan**): tambah pengujian fitur check in qr
- [`5cf9e66`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5cf9e66) (**Muhammad Rif’at Syamlan**): buat halaman kartu member dan scanner qr admin
- [`f93249b`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f93249b) (**Muhammad Rif’at Syamlan**): tambah route kartu member dan scan check in
- [`e554f94`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e554f94) (**Muhammad Rif’at Syamlan**): buat controller dan validasi check in qr code
- [`e6e8013`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e6e8013) (**Muhammad Rif’at Syamlan**): tambah kolom qr token dan batasan check in 1x per hari

### 🔹 MINGGU 8 (12–18 Oktober 2026) — Presensi Mandiri QR Code, Beli Paket PT & Scheduler (26 Commits)

#### 📅 Tanggal 2026-10-12 (5 Commits)
- [`110d232`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/110d232) (**Muhammad Rif’at Syamlan**): tambah pengujian fitur beli paket pt dan validasi minimum hari
- [`485a8dc`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/485a8dc) (**Muhammad Rif’at Syamlan**): buat halaman beli paket pt dan update menu navbar
- [`d2977c0`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d2977c0) (**Muhammad Rif’at Syamlan**): daftarkan route halaman dan pembelian paket pt
- [`82e9f67`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/82e9f67) (**Muhammad Rif’at Syamlan**): buat controller dan validasi beli paket sesi pt
- [`95e4f4c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/95e4f4c) (**Muhammad Rif’at Syamlan**): tambah casts dan default min membership days di pt package

#### 📅 Tanggal 2026-10-16 (5 Commits)
- [`a0a07d8`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a0a07d8) (**Naufal Alif Adjani**): Merge branch 'feature/pt-session-booking' into feature/pt-session-management
- [`7677404`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7677404) (**Naufal Alif Adjani**): hapus tanda + di navbar pada tampilan mobile
- [`c03a258`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c03a258) (**Naufal Alif Adjani**): implementasi tampilan kalendar & daftar sesi PT dan konfirmasi  permintaan sesi
- [`4096ac4`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4096ac4) (**Achmad Alfi Maksum**): fix(membership): perbaiki filter paket non-aktif, sinkronisasi kolom ERD, dan kalkulasi total pembayaran
- [`2a9dd51`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2a9dd51) (**Achmad Alfi Maksum**): fix(membership): perbaiki filter paket non-aktif, sinkronisasi kolom ERD, dan kalkulasi total pembayaran

#### 📅 Tanggal 2026-10-17 (12 Commits)
- [`9e093bd`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/9e093bd) (**Achmad Alfi Maksum**): fix(admin): perbaiki fungsi tombol approve/reject dan integrasi form alpine.js pada modal verifikasi
- [`552e352`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/552e352) (**Achmad Alfi Maksum**): Merge branch 'feature/admin-payment-notification' into feature/member-membership-checkout
- [`45f0db3`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/45f0db3) (**Achmad Alfi Maksum**): Merge branch 'feature/pt-quota-automation' into feature/member-membership-checkout
- [`92f5cea`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/92f5cea) (**Achmad Alfi Maksum**): Merge branch 'feature/membership-scheduler' into feature/member-membership-checkout
- [`4da0db6`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4da0db6) (**Achmad Alfi Maksum**): Merge branch 'feature/admin-dashboard' into feature/member-membership-checkout
- [`8f65641`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8f65641) (**Achmad Alfi Maksum**): fix(admin): perbaiki broken link gambar bukti transfer dan integrasi symlink storage
- [`8158a16`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8158a16) (**Naufal Alif Adjani**): merge pt-session and trainer-session-schedule-dashboard
- [`3160c11`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3160c11) (**Achmad Alfi Maksum**): style: format kode keseluruhan menggunakan laravel pint
- [`67f0f89`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/67f0f89) (**Achmad Alfi Maksum**): feat(admin): buat dashboard performa dan grafik mingguan chart.js
- [`8bc7eaa`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8bc7eaa) (**Achmad Alfi Maksum**): feat(scheduler): tambahkan auto-expired dan email reminder membership
- [`89f3857`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/89f3857) (**Achmad Alfi Maksum**): feat(payment): otomatisasi pembuatan kuota PT saat verifikasi disetujui
- [`72ec9b8`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/72ec9b8) (**Achmad Alfi Maksum**): feat(notification): tambahkan notifikasi pembayaran baru ke admin

#### 📅 Tanggal 2026-10-18 (4 Commits)
- [`f63a1d7`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f63a1d7) (**Muhammad Rif’at Syamlan**): update tampilan paket pt dan ubah menu jadi Sewa P
- [`7633b5f`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7633b5f) (**Muhammad Rif’at Syamlan**): filter paket pt berdasarkan sisa masa aktif membership
- [`33b4be1`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/33b4be1) (**Muhammad Rif’at Syamlan**): perbaiki tipe auth di test dan tambah skenario paket pt
- [`599c5ac`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/599c5ac) (**Siti Aisyah**): feat: tambah login google, perbaiki verifikasi email dan reset passwordd
