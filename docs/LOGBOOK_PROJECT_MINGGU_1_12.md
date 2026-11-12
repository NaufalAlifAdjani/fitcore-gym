# Logbook Perkembangan Proyek FitCore: Minggu 1 – 12 (24 Agustus – 15 November 2026)

**Nama Sistem**: FitCore (Sistem Informasi Manajemen dan Reservasi Gym Berbasis Web)  
**Repositori**: [NaufalAlifAdjani/fitcore-gym](https://github.com/NaufalAlifAdjani/fitcore-gym)  
**Total Branch**: 84 Branch (Feature, Fix, Integration, Docs)  
**Total Commit**: 201 Commits (Terdata per 12 November 2026)  
**Tim Pengembang (SIB-3A)**:
- **Naufal Alif Adjani** (244107060090) — Full-stack Developer (Booking & Penjadwalan)
- **Achmad Alfi Maksum** (244107060009) — Full-stack Developer (Pembayaran & Paket)
- **Muhammad Rif’at Syamlan** (244107060150) — Full-stack Developer (Riwayat, Notifikasi & Feedback)
- **Siti Aisyah** (244107060038) — Full-stack Developer (Akses & Identitas)

---

## 📊 I. Rangkuman Tabel Logbook Mingguan (Minggu 1 – 12)

| Minggu | Pekerjaan Selesai | Dikerjakan Oleh | Kendala | Rencana Berikutnya |
| :--- | :--- | :--- | :--- | :--- |
| **Minggu 1**<br>(24–30 Agt 2026) | Riset kompetitor aplikasi gym sejenis, evaluasi tech stack (Laravel, Livewire/Alpine, MySQL), dan perumusan ide awal produk FitCore. | Seluruh Tim (Naufal, Alfi, Rif’at, Aisyah) | Menentukan batasan ruang lingkup antara aplikasi gym enterprise vs skala menengah. | Menyusun problem statement dan business case formal. |
| **Minggu 2**<br>(31 Agt–06 Sep 2026) | Perumusan Problem Statement & Business Case (M02), penentuan target nilai bisnis, perumusan 3 role aktor utama. | Seluruh Tim | Mengidentifikasi titik kritis inefisiensi pencatatan kartu member manual dan penagihan. | Menyiapkan rencana wawancara dan stakeholder register. |
| **Minggu 3**<br>(07–13 Sep 2026) | Wawancara mendalam narasumber (Member & PT Gym Mas K dan Gym Mas S), penyusunan Stakeholder Matrix & Engagement Plan (M03). | Naufal Alif Adjani, Siti Aisyah | Menyelaraskan perbedaan kebijakan sesi PT, batas waktu reservasi (24 jam), dan sistem scan QR. | Penyusunan Project Charter dan struktur pembagian peran tim. |
| **Minggu 4**<br>(14–20 Sep 2026) | Penyusunan Project Charter (M05), penyusunan draf proposal lengkap, submit & review Checkpoint 1. | Seluruh Tim | Pembagian modul agar adil dan memiliki dependensi fungsional yang kohesif. | Merinci scope statement dan dekomposisi WBS level 0-3. |
| **Minggu 5**<br>(21–27 Sep 2026) | Penyusunan Scope Statement (M06a), Work Breakdown Structure (M06b), Model Kebutuhan & Matriks Use Case (M06c), Test Plan awal (M07). | Seluruh Tim | Memastikan rincian 22+ fitur use case saling terkait tanpa alur logika yang menggantung. | Inisialisasi repositori Git dan perancangan skema basis data fisik. |
| **Minggu 6**<br>(28 Sep–04 Okt 2026) | Inisialisasi repo Git, setup arsitektur dasar Laravel, konfigurasi database MySQL, migrasi awal tabel users & membership. | Naufal Alif Adjani | Penyesuaian versi dependensi PHP 8.3 dan konfigurasi awal Tailwind CSS. | Membangun layout Blade master, seeder, dan modul autentikasi 3 role. |
| **Minggu 7**<br>(05–11 Okt 2026) | Layout dasar Blade + Tailwind, form register member & validasi email, alur login admin/PT/member, modul katalog paket membership, seeder master bank & akun dummy. | Siti Aisyah, Achmad Alfi Maksum, Naufal, Rif’at | Merge conflict awal antara branch register-membership dan auth-login. | Membangun alur pembayaran manual dan scanner presensi QR. |
| **Minggu 8**<br>(12–18 Okt 2026) | Fitur beli paket sesi PT, scanner QR camera browser (html5-qrcode), proteksi duplikasi check-in 1x/hari, scheduler auto-expired status, form booking sesi PT. | Muhammad Rif’at Syamlan, Siti Aisyah, Naufal | Integrasi library html5-qrcode memerlukan penanganan permission kamera browser dan context HTTPS. | Mengembangkan modul verifikasi transfer admin dan kalender PT. |
| **Minggu 9**<br>(19–25 Okt 2026) | Modul pembayaran transfer bank & upload bukti, verifikasi pembayaran admin (approve/reject), manajemen paket PT admin, integrasi booking sesi PT & auto-plotting. | Achmad Alfi Maksum, Siti Aisyah, Naufal Alif Adjani | Penanganan race condition slot jadwal booking dan validasi bukti transfer buram. | Membangun modul rating review PT, cetak kwitansi invoice, dan integrasi notifikasi email. |
| **Minggu 10**<br>(26 Okt–01 Nov 2026) | Rating & ulasan PT, perubahan alur auto-plotting booking, migrasi tabel bank, fitur invoice download kwitansi pembayaran, email reminder masa aktif membership, RAB M10. | Muhammad Rif’at Syamlan, Achmad Alfi Maksum, Naufal, Aisyah | Sinkronisasi alur booking preferensi member agar tidak bentrok dengan jadwal pelatih. | Integrasi CI/CD workflow GitHub Actions dan responsivitas mobile. |
| **Minggu 11**<br>(02–08 Nov 2026) | Setup CI/CD GitHub Actions, dashboard trainer (estimasi komisi, jadwal harian), riwayat member PT, notifikasi sesi, hardware QR scanner, tema Blaze Orange, mobile light theme, pengujian PMPL E2E. | Seluruh Tim (Naufal, Alfi, Rif’at, Aisyah) | Perbedaan rendering Livewire morphing saat perubahan DOM dan deteksi mobile device ringan. | Integrasi fitur shift trainer 24 jam gym, perbaikan dashboard admin, dan finalisasi dokumen. |
| **Minggu 12**<br>(09–15 Nov 2026) | Fitur shift trainer, gym 24 jam & komisi sesi PT di manajemen trainer, pembaruan 4 card dashboard admin & filter bar chart, sinkronisasi proposal dan dokumen test plan ke GitHub Project. | Naufal Alif Adjani, Achmad Alfi Maksum | Penyesuaian query limit riwayat transaksi dengan komponen badge notifikasi navbar. | Finalisasi dokumentasi rilis dan persiapan demo evaluasi akhir semester. |

---

## 📅 II. Logbook Harian Detail per Minggu (Minggu 1 – 12)

### 🔹 MINGGU 1 (24–30 Agustus 2026) — Fase Inisiasi & Studi Kelayakan

- **2026-08-25** — **Diskusi Tim** (*Seluruh Tim*):
  Brainstorming ide proyek sistem informasi gym berbasis web, identifikasi masalah penagihan dan reservasi.
- **2026-08-27** — **Riset Pasar** (*Seluruh Tim*):
  Benchmarking fitur kompetitor aplikasi kebugaran (GymMaster, Glofox) serta perumusan fitur diferensiasi (QR scanner lokal & auto-plotting).
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
- [5f19c44](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5f19c44) (**Naufal Alif Adjani**): chore: initial Laravel project

#### 📅 Tanggal 2026-10-03 (1 Commits)
- [4c6f6e3](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4c6f6e3) (**Naufal Alif Adjani**): install depedencies

#### 📅 Tanggal 2026-10-04 (1 Commits)
- [8e34c56](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8e34c56) (**Naufal Alif Adjani**): inisialisasi database

### 🔹 MINGGU 7 (05–11 Oktober 2026) — Fondasi UI Layout, Autentikasi 3 Role & Master Data (36 Commits)

#### 📅 Tanggal 2026-10-07 (6 Commits)
- [dbfe501](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/dbfe501) (**Naufal Alif Adjani**): fix seeder
- [db21a1d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/db21a1d) (**Naufal Alif Adjani**): fix error in bank seeder
- [daef7d1](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/daef7d1) (**Naufal Alif Adjani**): update seeder
- [96859d5](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/96859d5) (**Naufal Alif Adjani**): hapus file sampah
- [6cf713f](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6cf713f) (**Naufal Alif Adjani**): add seeder
- [1827268](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1827268) (**Naufal Alif Adjani**): booking-sessions

#### 📅 Tanggal 2026-10-08 (2 Commits)
- [5ba3e5f](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5ba3e5f) (**Achmad Alfi Maksum**): feat: implement membership package management module
- [5c2aae8](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5c2aae8) (**Naufal Alif Adjani**): FE dan BE masih berantakan

#### 📅 Tanggal 2026-10-09 (20 Commits)
- [9c39163](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/9c39163) (**Siti Aisyah**): fix: perbaikan halaman  login admin
- [80f0019](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/80f0019) (**Siti Aisyah**): feat: menyelesaikan halaman dan alur login admin
- [12b85ba](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/12b85ba) (**Siti Aisyah**): feat: menambahkan halaman dan alur register membership
- [1c37bf7](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1c37bf7) (**Achmad Alfi Maksum**): feat(member): implementasi katalog paket membership, checkout, dan upload bukti bayar
- [ad49f87](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ad49f87) (**Achmad Alfi Maksum**): feat(admin): refactor tampilan manajemen paket dan harga menggunakan reusable components
- [fc4eb06](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/fc4eb06) (**Achmad Alfi Maksum**): feat(admin): implementasi verifikasi pembayaran dan reusable blade components
- [e1892c1](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e1892c1) (**Achmad Alfi Maksum**): fix: add unverified state to UserFactory and resolve auth merge conflicts
- [d1ab51b](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d1ab51b) (**Achmad Alfi Maksum**): fix: resolve conflict with register-membership
- [0db1957](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0db1957) (**Achmad Alfi Maksum**): Merge remote-tracking branch 'origin/feature/register-membership' into feat/membership-package
- [6f9b00d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6f9b00d) (**Achmad Alfi Maksum**): fix: resolve conflict with auth-login
- [f3f6294](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f3f6294) (**Achmad Alfi Maksum**): feat: simpan verifikasi pembayaran sebelum merge auth
- [b3b1fa3](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b3b1fa3) (**Achmad Alfi Maksum**): fix: resolve merge conflicts and align with develop
- [74b5b83](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/74b5b83) (**Achmad Alfi Maksum**): fix: resolve merge conflicts with develop
- [4bb3761](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4bb3761) (**Achmad Alfi Maksum**): feat: simpan fitur verifikasi pembayaran
- [c2f697d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c2f697d) (**Naufal Alif Adjani**): fix card layout session
- [ce35f3d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ce35f3d) (**Siti Aisyah**): fix: perbaikan halaman  login admin
- [cea2ff1](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/cea2ff1) (**Siti Aisyah**): feat: menyelesaikan halaman dan alur login admin
- [835c6c0](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/835c6c0) (**Siti Aisyah**): Merge branch 'feature/register-membership' into feature/auth-login
- [93fbb4f](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/93fbb4f) (**Siti Aisyah**): feat: menambahkan halaman dan alur register membership
- [211da72](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/211da72) (**Siti Aisyah**): feat: membuat tampilan halaman login member dan trainer

#### 📅 Tanggal 2026-10-10 (2 Commits)
- [7724bbb](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7724bbb) (**Achmad Alfi Maksum**): feat(member): tambahkan dashboard controller, gate otorisasi, dan auth test
- [696c84d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/696c84d) (**Achmad Alfi Maksum**): fix(member): perbaikan alur login redirect dan styling checkout sesuai mockup figma

#### 📅 Tanggal 2026-10-11 (6 Commits)
- [eb1626c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/eb1626c) (**Naufal Alif Adjani**): add button detail latihan, fix layouting, filter
- [0c3b040](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0c3b040) (**Muhammad Rif’at Syamlan**): tambah pengujian fitur check in qr
- [5cf9e66](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5cf9e66) (**Muhammad Rif’at Syamlan**): buat halaman kartu member dan scanner qr admin
- [f93249b](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f93249b) (**Muhammad Rif’at Syamlan**): tambah route kartu member dan scan check in
- [e554f94](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e554f94) (**Muhammad Rif’at Syamlan**): buat controller dan validasi check in qr code
- [e6e8013](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e6e8013) (**Muhammad Rif’at Syamlan**): tambah kolom qr token dan batasan check in 1x per hari

### 🔹 MINGGU 8 (12–18 Oktober 2026) — Presensi Mandiri QR Code, Beli Paket PT & Scheduler (26 Commits)

#### 📅 Tanggal 2026-10-12 (5 Commits)
- [110d232](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/110d232) (**Muhammad Rif’at Syamlan**): tambah pengujian fitur beli paket pt dan validasi minimum hari
- [485a8dc](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/485a8dc) (**Muhammad Rif’at Syamlan**): buat halaman beli paket pt dan update menu navbar
- [d2977c0](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d2977c0) (**Muhammad Rif’at Syamlan**): daftarkan route halaman dan pembelian paket pt
- [82e9f67](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/82e9f67) (**Muhammad Rif’at Syamlan**): buat controller dan validasi beli paket sesi pt
- [95e4f4c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/95e4f4c) (**Muhammad Rif’at Syamlan**): tambah casts dan default min membership days di pt package

#### 📅 Tanggal 2026-10-16 (5 Commits)
- [a0a07d8](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a0a07d8) (**Naufal Alif Adjani**): Merge branch 'feature/pt-session-booking' into feature/pt-session-management
- [7677404](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7677404) (**Naufal Alif Adjani**): hapus tanda + di navbar pada tampilan mobile
- [c03a258](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c03a258) (**Naufal Alif Adjani**): implementasi tampilan kalendar & daftar sesi PT dan konfirmasi  permintaan sesi
- [4096ac4](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4096ac4) (**Achmad Alfi Maksum**): fix(membership): perbaiki filter paket non-aktif, sinkronisasi kolom ERD, dan kalkulasi total pembayaran
- [2a9dd51](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2a9dd51) (**Achmad Alfi Maksum**): fix(membership): perbaiki filter paket non-aktif, sinkronisasi kolom ERD, dan kalkulasi total pembayaran

#### 📅 Tanggal 2026-10-17 (12 Commits)
- [9e093bd](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/9e093bd) (**Achmad Alfi Maksum**): fix(admin): perbaiki fungsi tombol approve/reject dan integrasi form alpine.js pada modal verifikasi
- [552e352](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/552e352) (**Achmad Alfi Maksum**): Merge branch 'feature/admin-payment-notification' into feature/member-membership-checkout
- [45f0db3](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/45f0db3) (**Achmad Alfi Maksum**): Merge branch 'feature/pt-quota-automation' into feature/member-membership-checkout
- [92f5cea](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/92f5cea) (**Achmad Alfi Maksum**): Merge branch 'feature/membership-scheduler' into feature/member-membership-checkout
- [4da0db6](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4da0db6) (**Achmad Alfi Maksum**): Merge branch 'feature/admin-dashboard' into feature/member-membership-checkout
- [8f65641](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8f65641) (**Achmad Alfi Maksum**): fix(admin): perbaiki broken link gambar bukti transfer dan integrasi symlink storage
- [8158a16](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8158a16) (**Naufal Alif Adjani**): merge pt-session and trainer-session-schedule-dashboard
- [3160c11](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3160c11) (**Achmad Alfi Maksum**): style: format kode keseluruhan menggunakan laravel pint
- [67f0f89](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/67f0f89) (**Achmad Alfi Maksum**): feat(admin): buat dashboard performa dan grafik mingguan chart.js
- [8bc7eaa](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8bc7eaa) (**Achmad Alfi Maksum**): feat(scheduler): tambahkan auto-expired dan email reminder membership
- [89f3857](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/89f3857) (**Achmad Alfi Maksum**): feat(payment): otomatisasi pembuatan kuota PT saat verifikasi disetujui
- [72ec9b8](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/72ec9b8) (**Achmad Alfi Maksum**): feat(notification): tambahkan notifikasi pembayaran baru ke admin

#### 📅 Tanggal 2026-10-18 (4 Commits)
- [f63a1d7](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f63a1d7) (**Muhammad Rif’at Syamlan**): update tampilan paket pt dan ubah menu jadi Sewa P
- [7633b5f](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7633b5f) (**Muhammad Rif’at Syamlan**): filter paket pt berdasarkan sisa masa aktif membership
- [33b4be1](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/33b4be1) (**Muhammad Rif’at Syamlan**): perbaiki tipe auth di test dan tambah skenario paket pt
- [599c5ac](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/599c5ac) (**Siti Aisyah**): feat: tambah login google, perbaiki verifikasi email dan reset passwordd

### 🔹 MINGGU 9 (19–25 Oktober 2026) — Modul Pembayaran Manual, Verifikasi Transfer & Kalender PT (43 Commits)

#### 📅 Tanggal 2026-10-20 (8 Commits)
- [1ac82f4](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1ac82f4) (**Muhammad Rif’at Syamlan**): buat halaman upload bukti transfer pembayaran
- [a5930f0](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a5930f0) (**Muhammad Rif’at Syamlan**): buat halaman rincian pesanan dan checkout pembayaran
- [84fa3d6](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/84fa3d6) (**Muhammad Rif’at Syamlan**): buat tampilan pilih personal trainer
- [3f8cc39](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3f8cc39) (**Muhammad Rif’at Syamlan**): perbarui tampilan pilih paket sesi personal trainer
- [7092eab](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7092eab) (**Muhammad Rif’at Syamlan**): buat layout utama fitcore dengan navbar dan footer sesuai figma
- [d285bf7](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d285bf7) (**Muhammad Rif’at Syamlan**): tambahkan rute dan controller alur pemesanan personal trainer
- [d86f114](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d86f114) (**Achmad Alfi Maksum**): refactor: menggunakan laravel socialite untuk login google dan bypass verifikasi email
- [3f9b3fc](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3f9b3fc) (**Achmad Alfi Maksum**): fix: resolve merge conflict dengan fitur login-google

#### 📅 Tanggal 2026-10-21 (6 Commits)
- [ef49bb9](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ef49bb9) (**Muhammad Rif’at Syamlan**): tambah test case alur navigasi, checkout, dan validasi bukti transfer
- [5b39755](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5b39755) (**Muhammad Rif’at Syamlan**): perbarui kalkulasi checkout dan validasi upload bukti pembayaran
- [2165b3e](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2165b3e) (**Muhammad Rif’at Syamlan**): tambah tombol kembali, preview file dinamis, dan tombol kirim pudar saat disabled
- [cfb317e](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/cfb317e) (**Muhammad Rif’at Syamlan**): hapus kode promo, default uncheck pilihan bank, dan tombol beli pudar saat disabled
- [94c909e](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/94c909e) (**Muhammad Rif’at Syamlan**): tambah tombol kembali, pencarian real-time, dan hapus filter rating
- [822d985](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/822d985) (**Muhammad Rif’at Syamlan**): sesuaikan padding dan jarak kontainer hero dan daftar sesi

#### 📅 Tanggal 2026-10-22 (24 Commits)
- [c09dd1c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c09dd1c) (**Siti Aisyah**): fix(trainer-sessions): unify schedule page with dashboard FitCore PT Portal layout and sidebar
- [2f71972](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2f71972) (**Siti Aisyah**): refactor(trainer-sessions): use Livewire 3 Layout attribute for Index component
- [57797b4](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/57797b4) (**Muhammad Rif’at Syamlan**): chore: update composer.lock for livewire and socialite
- [defc634](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/defc634) (**Siti Aisyah**): fix(migrations): deduplicate pre-existing sessions before creating unique index
- [4bcf12f](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4bcf12f) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/fix/menu-dashboard-trainer'
- [f87f7a8](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f87f7a8) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/pt-session-management'
- [51bf009](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/51bf009) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/pt-session-booking'
- [8e75440](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8e75440) (**Muhammad Rif’at Syamlan**): Merge remote-tracking branch 'origin/feature/dashboard-pt' into feature/admin-manajemen-paket-sesi-pt
- [cd52d77](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/cd52d77) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/buy-pt-package'
- [d185fb0](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d185fb0) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/admin-package-management'
- [255714a](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/255714a) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/qr-code-check-in'
- [db9f979](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/db9f979) (**Siti Aisyah**): merge: resolve conflicts and merge origin/feature/trainer-session-schedule-dashboard
- [8757f40](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8757f40) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/role-permision'
- [8e56580](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8e56580) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/auth-admin'
- [f153d94](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f153d94) (**Naufal Alif Adjani**): chore: sync composer.lock with laravel/socialite
- [7bba589](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7bba589) (**Siti Aisyah**): chore: setup livewire and trainer schedule route
- [74db95d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/74db95d) (**Naufal Alif Adjani**): Merge branch 'feature/member-membership-checkout' into integration/pt-session-and-checkout
- [0c82627](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0c82627) (**Siti Aisyah**): fix: perbaikan navigasi dan tampilan menu di dashboard trainer
- [63b70f6](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/63b70f6) (**Siti Aisyah**): feat: implementasi dashboard personal trainer
- [6a1a68f](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6a1a68f) (**Siti Aisyah**): feat: tambah role & permision serta middleware error 403
- [0001509](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0001509) (**Siti Aisyah**): Merge branch 'feature/member-membership-checkout' into develop
- [6492f92](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6492f92) (**Siti Aisyah**): chore: update seeders with email verification and UserSeeder call
- [883318e](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/883318e) (**Achmad Alfi Maksum**): feat: integrasi data real-time dashboard admin, penambahan seeder massal, dan perbaikan auth test
- [4846158](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4846158) (**Achmad Alfi Maksum**): refactor: perbaikan UI/UX dashboard admin, paket membership, dan verifikasi pembayaran sesuai Design System

#### 📅 Tanggal 2026-10-23 (2 Commits)
- [b0b9a5b](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b0b9a5b) (**Naufal Alif Adjani**): merge membershi checkout and pt-session
- [43a33ac](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/43a33ac) (**Muhammad Rif’at Syamlan**): feat(admin): implement personal trainer package management

#### 📅 Tanggal 2026-10-24 (3 Commits)
- [a403124](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a403124) (**Achmad Alfi Maksum**): feat(member): implementasi riwayat transaksi, komponen UI kondisional, grouping bulan, dan unduh PDF invoice
- [fdd27e6](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/fdd27e6) (**Naufal Alif Adjani**): Merge pull request #11 from NaufalAlifAdjani/integration/pt-session-and-checkout
- [f5246d4](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f5246d4) (**Naufal Alif Adjani**): integrate pt session and checkout

### 🔹 MINGGU 10 (26 Oktober – 01 November 2026) — Rating PT, Alur Auto-Plotting, Invoice & Email Reminder (49 Commits)

#### 📅 Tanggal 2026-10-26 (2 Commits)
- [4ed3099](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4ed3099) (**Naufal Alif Adjani**): Merge pull request #12 from NaufalAlifAdjani/integration/add-role-permision
- [f04c84c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f04c84c) (**Naufal Alif Adjani**): integration add-role-permision

#### 📅 Tanggal 2026-10-28 (15 Commits)
- [8913275](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8913275) (**Achmad Alfi Maksum**): Merge pull request #16 from NaufalAlifAdjani/revert-15-feature/member-transaction-history
- [bb83d45](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/bb83d45) (**Achmad Alfi Maksum**): Revert "Feature/member transaction history"
- [4510670](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4510670) (**Muhammad Rif’at Syamlan**): fix(admin): remove stat cards and fix horizontal scrolling on pt packages management
- [29dacb4](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/29dacb4) (**Achmad Alfi Maksum**): Merge pull request #15 from NaufalAlifAdjani/feature/member-transaction-history
- [e8c0bb7](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e8c0bb7) (**Naufal Alif Adjani**): fix: sync logout modal text with tests and configure testing locale in phpunit.xml
- [f865772](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f865772) (**Naufal Alif Adjani**): style: apply Laravel Pint styling fixes
- [46b9546](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/46b9546) (**Naufal Alif Adjani**): chore: add default en language files
- [d8ea88d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d8ea88d) (**Naufal Alif Adjani**): fix: align payment seeder with active pt packages and restore logout confirmation message
- [31cf63c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/31cf63c) (**Muhammad Rif’at Syamlan**): fix(admin): order pt packages and payment verifications by latest created
- [e0298b9](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e0298b9) (**Muhammad Rif’at Syamlan**): fix(pt-packages): integrate pt package purchase with admin payment verification and fix dead links
- [77fb4a7](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/77fb4a7) (**Muhammad Rif’at Syamlan**): fix: add scheduled and done values to PtSessionStatus enum to prevent ValueError
- [528b49c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/528b49c) (**Muhammad Rif’at Syamlan**): chore: align conflicted files with develop branch as requested
- [865f296](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/865f296) (**Muhammad Rif’at Syamlan**): merge: integrate feature/buy-pt-package and feature/admin-manajemen-paket-sesi-pt with latest develop
- [c1c24a9](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c1c24a9) (**Siti Aisyah**): feat: implementsasi halaman manajemen data member
- [5f4d4d7](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5f4d4d7) (**Siti Aisyah**): merge: integrate trainer dashboard and role permissions with pt session and checkout

#### 📅 Tanggal 2026-10-29 (18 Commits)
- [27cb631](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/27cb631) (**Siti Aisyah**): feat: halaman manajemen trainer role admin
- [8ec4090](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8ec4090) (**Siti Aisyah**): merge: integrate feature/manage-members into feature/admin-manage-trainers
- [e336be7](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e336be7) (**Naufal Alif Adjani**): merge: branch feature/member-qr-code-integration into develop
- [468feb6](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/468feb6) (**Siti Aisyah**): feat: halaman manajemen trainer role admin
- [3207ce6](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3207ce6) (**Naufal Alif Adjani**): merge: branch fix/smtp-verification into develop
- [270c7e6](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/270c7e6) (**Naufal Alif Adjani**): feat: redirect halaman utama ke halaman login
- [a514533](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a514533) (**Naufal Alif Adjani**): fix: alur verifikasi email untuk tamu dan pop-up sukses di dashboard
- [c15806c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c15806c) (**Naufal Alif Adjani**): add notification system and session notifications
- [ea3321d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ea3321d) (**Siti Aisyah**): fix: Memperbaiki konflik merge dengan branch develop
- [8688ed8](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8688ed8) (**Siti Aisyah**): fix: perbaikan controller login google dan kongigurasi app
- [24ccfbe](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/24ccfbe) (**Muhammad Rif’at Syamlan**): selaraskan navbar dan hero header halaman booking sesi pt
- [467111d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/467111d) (**Muhammad Rif’at Syamlan**): feat(qr-code): integrate member digital card and admin scanner with FitCore UI design system
- [6459f43](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6459f43) (**Siti Aisyah**): fix: perbaikan konfigurasi dan proses verifikasi email via smtp
- [b605bbc](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b605bbc) (**Siti Aisyah**): merge: integrate origin/develop into feature/manage-members
- [1c26042](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1c26042) (**Muhammad Rif’at Syamlan**): fix(admin): resolve input padding overlap with Rp prefix in pt package modal
- [8e850b7](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8e850b7) (**Achmad Alfi Maksum**): fix(admin): sederhanakan ui tabel package dengan warna statis abu-abu
- [6761df9](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6761df9) (**Achmad Alfi Maksum**): fix(admin): hapus summary widget dan implementasi pewarnaan dinamis tabel membership berdasarkan tier
- [598928a](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/598928a) (**Achmad Alfi Maksum**): perf(member): implementasi kompresi otomatis max 800px format WebP pada bukti pembayaran

#### 📅 Tanggal 2026-10-30 (8 Commits)
- [3c949df](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3c949df) (**Naufal Alif Adjani**): merge: integrasikan perbaikan email verification dari branch fix/email-verification ke develop
- [a489e98](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a489e98) (**Siti Aisyah**): merge: selesaikan konflik develop ke fix/email-verification
- [ad652ce](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ad652ce) (**Naufal Alif Adjani**): fix(navbar): resolve dual active button highlighting between membership and pt packages
- [a5ff4da](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a5ff4da) (**Naufal Alif Adjani**): fix(riwayat-transaksi): remove finance verification estimate pill from pending transaction card
- [6a0d4c0](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6a0d4c0) (**Siti Aisyah**): fix: perbaikan halaman verifikasi email login
- [14e4687](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/14e4687) (**Naufal Alif Adjani**): fix(member): randomize unique verification code, unify member navbar, retain purchased pt cards, and extract reusable payment components
- [f7de0b4](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f7de0b4) (**Naufal Alif Adjani**): merge: branch feature/member-transaction-history into develop
- [a44baf9](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a44baf9) (**Naufal Alif Adjani**): style(member-card): update layout and styling to app-layout

#### 📅 Tanggal 2026-10-31 (4 Commits)
- [d9f8d0f](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d9f8d0f) (**Naufal Alif Adjani**): Merge pull request #73 from NaufalAlifAdjani/integration/ci-cd
- [c4b2513](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c4b2513) (**Naufal Alif Adjani**): integrate cicd
- [03ab867](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/03ab867) (**Naufal Alif Adjani**): fix(admin): perbarui layout manajemen paket sesi pt pada admin/pt-sessions dari fix/admin-pt-packages-layout
- [19a61e6](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/19a61e6) (**Naufal Alif Adjani**): merge: integrasikan perbaikan member navbar dan payment refactor ke develop

#### 📅 Tanggal 2026-11-01 (2 Commits)
- [5c5fede](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5c5fede) (**Naufal Alif Adjani**): Merge pull request #74 from NaufalAlifAdjani/feature/trainer-dashboard-enhancement
- [27393cd](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/27393cd) (**Naufal Alif Adjani**): feat(trainer): perbarui dashboard dengan estimasi komisi, jadwal harian, dan approval sesi

### 🔹 MINGGU 11 (02–08 November 2026) — CI/CD Actions, UI Mobile-First Light Mode & Pengujian PMPL E2E (31 Commits)

#### 📅 Tanggal 2026-11-03 (2 Commits)
- [49a63a8](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/49a63a8) (**Naufal Alif Adjani**): docs: add ALL diagram (Use case, activity, ERD, class diagram)
- [c4e31db](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c4e31db) (**Naufal Alif Adjani**): docs: add PMPL testing suite (Modul A - D)

#### 📅 Tanggal 2026-11-05 (4 Commits)
- [baeb426](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/baeb426) (**Siti Aisyah**): Merge branch 'feature/admin-manage-trainers' into test/pmpl-e2e-fitcore
- [45eb974](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/45eb974) (**Siti Aisyah**): fix(admin): hapus tombol tambah member pada halaman manajemen member
- [fc1c4c5](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/fc1c4c5) (**Siti Aisyah**): test: menambahkan rangkaian uji e2e fitcore
- [5061d6a](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5061d6a) (**Naufal Alif Adjani**): feat(trainer): implement daftar dan riwayat member per sesi sisi PT

#### 📅 Tanggal 2026-11-06 (1 Commits)
- [8418feb](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8418feb) (**Achmad Alfi Maksum**): feat: implement mobile-first UI/UX architecture, role views, and auto-redirect middleware

#### 📅 Tanggal 2026-11-07 (7 Commits)
- [562f355](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/562f355) (**Achmad Alfi Maksum**): feat: register public layout component, landing controller, and auth navigation cleanups
- [4ed2c1f](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4ed2c1f) (**Achmad Alfi Maksum**): fix: resolve public-to-member conversion funnel, preserve query redirects, and standardize mobile light theme layout
- [b2888ac](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b2888ac) (**Achmad Alfi Maksum**): feat: complete trainer mobile views in light theme, auto-redirect, and test suite
- [1e85e63](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1e85e63) (**Achmad Alfi Maksum**): fix: resolve mobile bottom nav active tab matching and overhaul PT sessions UI layout
- [0932d84](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0932d84) (**Naufal Alif Adjani**): style(trainer): perbaiki layout tombol simpan ketersediaan jadwal agar menyatu dan tidak menutupi slot
- [64c40c0](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/64c40c0) (**Naufal Alif Adjani**): feat(trainer): implement master hari kerja ketersediaan waktu PT dan penyelarasan jadwal booking
- [47856ed](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/47856ed) (**Achmad Alfi Maksum**): feat: integrate mobile package checkout flow, pt session detail view, and refactor light theme UI

#### 📅 Tanggal 2026-11-08 (17 Commits)
- [542e2f1](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/542e2f1) (**Muhammad Rif’at Syamlan**): feat(checkin): add support for physical hardware qr scanner device
- [2320617](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2320617) (**Naufal Alif Adjani**): fix(pt-sessions): fix livewire morph issue by stripping BOM and adding wire keys
- [0f14cb5](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0f14cb5) (**Naufal Alif Adjani**): feat(branding): implement new FitCore flame logo with transparent background and favicon across layouts
- [5f8e056](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5f8e056) (**Achmad Alfi Maksum**): fix: resolve invoice download route and scale down mobile package typography for 2-column grid
- [e8174bd](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e8174bd) (**Naufal Alif Adjani**): feat(theme): switch to vibrant light athletic palette with Blaze Orange accents
- [2a26917](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2a26917) (**Naufal Alif Adjani**): feat(theme): apply Blaze Orange athletic color palette
- [d277b4d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d277b4d) (**Muhammad Rif’at Syamlan**): feat(checkin): add support for physical hardware qr scanner device
- [671a3ec](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/671a3ec) (**Achmad Alfi Maksum**): fix: implement reverse auto-redirect from mobile routes to desktop view based on user agent and roles
- [d7bf2f0](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d7bf2f0) (**Naufal Alif Adjani**): Merge branch 'fix/scanner-qr-checkin' into develop
- [246ea7c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/246ea7c) (**Muhammad Rif’at Syamlan**): feat(checkin): auto-stop scanner, display today check-in table and add next scan button
- [0e7405d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0e7405d) (**Naufal Alif Adjani**): fix(auth): perbaiki penanganan error state login google dan konfigurasi redirect dinamis
- [dcf61c3](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/dcf61c3) (**Muhammad Rif’at Syamlan**): fix(scanner): allow qr check-in for memberships linked via payment_id
- [154282c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/154282c) (**Achmad Alfi Maksum**): perf: replace agent package with lightweight native regex for device detection in AutoRedirectMobile
- [090ec7e](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/090ec7e) (**Naufal Alif Adjani**): merge: integrasikan fitur ketersediaan waktu PT ke develop
- [39f97ae](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/39f97ae) (**Naufal Alif Adjani**): merge: integrasikan fitur riwayat sesi klien PT ke develop
- [7d99a11](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7d99a11) (**Naufal Alif Adjani**): refactor(trainer): pisahkan fitur ketersediaan waktu PT ke dedicated branch terpisah
- [b4b75e2](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b4b75e2) (**Naufal Alif Adjani**): feat(trainer): implement master hari kerja ketersediaan waktu PT dan penyelarasan jadwal booking

### 🔹 MINGGU 12 (09–15 November 2026) — Shift Trainer 24 Jam Gym, Dashboard Admin & Finalisasi Dokumentasi (13 Commits)

#### 📅 Tanggal 2026-11-09 (1 Commits)
- [ab9c326](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ab9c326) (**Naufal Alif Adjani**): feat(pt-packages): update title to Beli Paket and align colors with athletic core palette

#### 📅 Tanggal 2026-11-10 (5 Commits)
- [f06ec1a](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f06ec1a) (**Naufal Alif Adjani**): Merge branch 'feature/theme-blaze-orange' into develop
- [0980e8c](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0980e8c) (**Naufal Alif Adjani**): fix(trainer-sessions): unify sidebar and layout with FitCore PT Portal
- [83047e8](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/83047e8) (**Achmad Alfi Maksum**): style: standardize blaze orange theme, resolve merge conflicts, and fix typo assertions
- [b8ccfde](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b8ccfde) (**Achmad Alfi Maksum**): Merge branch 'feature/theme-blaze-orange' into feature/ui-ux-web-light-mode
- [ade2d87](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ade2d87) (**Achmad Alfi Maksum**): style: apply complete light theme to member area web desktop views

#### 📅 Tanggal 2026-11-11 (6 Commits)
- [321ddd7](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/321ddd7) (**Naufal Alif Adjani**): merge: integrasikan fitur shift trainer, gym 24 jam, dan komisi sesi pt ke develop
- [508335d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/508335d) (**Naufal Alif Adjani**): feat(pt): implementasi shift trainer, gym 24 jam, dan pengelolaan komisi pt di manajemen trainer
- [70e761e](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/70e761e) (**Naufal Alif Adjani**): feat(dashboard): perbarui 4 card dashboard admin dan sinkronkan filter tanggal pada bar chart
- [c126f31](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c126f31) (**Naufal Alif Adjani**): test: sesuaikan query count limit riwayat transaksi dengan komponen notifikasi navbar
- [5216f78](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5216f78) (**Naufal Alif Adjani**): merge: integrasikan mobile UI/UX dan web light mode ke develop
- [d14a3d4](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d14a3d4) (**Naufal Alif Adjani**): merge: integrasikan sistem notifikasi sesi PT ke develop dengan tema Blaze Orange

#### 📅 Tanggal 2026-11-12 (1 Commits)
- [4f1637d](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4f1637d) (**Naufal Alif Adjani**): docs: add full proposal PBL, Test Plan v1.0, and Test Execution Record
