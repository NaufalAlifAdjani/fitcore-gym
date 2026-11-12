# Logbook Perkembangan Proyek FitCore — Milestone 2: Minggu 9 – 12 (19 Oktober – 15 November 2026)

**Nama Sistem**: FitCore (Sistem Informasi Manajemen dan Reservasi Gym Berbasis Web)  
**Repositori**: [NaufalAlifAdjani/fitcore-gym](https://github.com/NaufalAlifAdjani/fitcore-gym)  
**Fase Milestone 2**: Transaksi Pembayaran Manual, Verifikasi Admin, Kalender Sesi & Ketersediaan PT, Auto-Plotting, Rating PT, Cetak Invoice, UI Mobile-First Light Mode, Shift Trainer 24 Jam & Komisi PT, CI/CD, dan Pengujian PMPL  
**Tim Pengembang (SIB-3A)**:
- **Naufal Alif Adjani** (244107060090) — Full-stack Developer (Booking & Penjadwalan)
- **Achmad Alfi Maksum** (244107060009) — Full-stack Developer (Pembayaran & Paket)
- **Muhammad Rif’at Syamlan** (244107060150) — Full-stack Developer (Riwayat, Notifikasi & Feedback)
- **Siti Aisyah** (244107060038) — Full-stack Developer (Akses & Identitas)

---

## 📊 I. Rangkuman Tabel Logbook Mingguan — Milestone 2 (Minggu 9 – 12)

| Minggu | Pekerjaan Selesai | Dikerjakan Oleh | Kendala | Rencana Berikutnya |
| :--- | :--- | :--- | :--- | :--- |
| **Minggu 9**<br>(19–25 Okt 2026) | Alur pembayaran transfer manual & upload bukti bayar, verifikasi pembayaran admin (approve/reject), manajemen master paket PT admin, integrasi booking sesi PT & auto-plotting awal. | Achmad Alfi Maksum, Siti Aisyah, Naufal Alif Adjani | Mencegah race condition ketika 2 member memilih slot jam pelatih yang sama, dan validasi berkas gambar bukti bayar. | Mengembangkan modul rating PT, cetak invoice kwitansi, dan notifikasi email queue. |
| **Minggu 10**<br>(26 Okt–01 Nov 2026) | Fitur rating & ulasan bintang PT, perubahan alur auto-plotting booking, migrasi tabel bank dinamis, fitur cetak invoice PDF/HTML, background queue email reminder masa aktif, penyusunan RAB M10. | Muhammad Rif’at Syamlan, Achmad Alfi Maksum, Naufal, Aisyah | Sinkronisasi rekening bank multi-tujuan dan penyesuaian query filter riwayat transaksi. | Integrasi CI/CD workflow GitHub Actions dan optimasi antarmuka mobile responsive. |
| **Minggu 11**<br>(02–08 Nov 2026) | Setup CI/CD GitHub Actions, dashboard trainer (estimasi komisi, jadwal harian), riwayat member PT, notifikasi sesi, hardware QR scanner, tema Blaze Orange, mobile light theme, pengujian PMPL E2E. | Seluruh Tim (Naufal, Alfi, Rif’at, Aisyah) | Perbedaan rendering Livewire morphing saat perubahan DOM dan deteksi mobile device yang ringan tanpa library berat. | Integrasi fitur shift trainer 24 jam gym, pembaruan metrik dashboard admin, dan finalisasi dokumentasi. |
| **Minggu 12**<br>(09–15 Nov 2026) | Fitur shift trainer 24 jam gym & komisi sesi PT di manajemen trainer, pembaruan 4 card dashboard admin & sinkronisasi filter bar chart, sinkronisasi proposal dan dokumen test plan ke GitHub Project. | Naufal Alif Adjani, Achmad Alfi Maksum | Penyesuaian query limit riwayat transaksi dengan komponen badge notifikasi navbar. | Finalisasi dokumentasi rilis dan persiapan demo evaluasi akhir semester. |

---

## 📅 II. Logbook Harian Detail per Minggu — Milestone 2 (Minggu 9 – 12)
*(Menjabarkan tanggal, commit, author, detail teknis yang berubah, dan alasan perubahannya secara mendalam)*

### 🔹 MINGGU 9 (19–25 Oktober 2026) — Modul Pembayaran Manual, Verifikasi Transfer & Kalender PT (43 Commits)

#### 📅 Tanggal 2026-10-20 — Manajemen Paket Sesi PT Admin & Standardisasi Form Input (8 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Implementasi antarmuka CRUD paket sesi Personal Trainer pada sisi Admin (Controller `AdminPtPackageController`, form input nama paket, jumlah kuota sesi, masa berlaku, dan tarif resmi). Penambahan validasi server-side dan penyesuaian layout reusable blade components.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Sebelumnya paket sesi PT belum memiliki manajemen terpusat di dashboard admin dan hanya mengandalkan data dummy statis. Admin memerlukan kendali penuh untuk membuat, memperbarui harga, dan mengaktifkan/menonaktifkan paket sesuai kebijakan gym tanpa perlu manipulasi database manual.
- **Daftar Commit Terkait**:
  - [`1ac82f4`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1ac82f4) (**Muhammad Rif’at Syamlan**): buat halaman upload bukti transfer pembayaran
  - [`a5930f0`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a5930f0) (**Muhammad Rif’at Syamlan**): buat halaman rincian pesanan dan checkout pembayaran
  - [`84fa3d6`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/84fa3d6) (**Muhammad Rif’at Syamlan**): buat tampilan pilih personal trainer
  - [`3f8cc39`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3f8cc39) (**Muhammad Rif’at Syamlan**): perbarui tampilan pilih paket sesi personal trainer
  - [`7092eab`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7092eab) (**Muhammad Rif’at Syamlan**): buat layout utama fitcore dengan navbar dan footer sesuai figma
  - [`d285bf7`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d285bf7) (**Muhammad Rif’at Syamlan**): tambahkan rute dan controller alur pemesanan personal trainer
  - [`d86f114`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d86f114) (**Achmad Alfi Maksum**): refactor: menggunakan laravel socialite untuk login google dan bypass verifikasi email
  - [`3f9b3fc`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3f9b3fc) (**Achmad Alfi Maksum**): fix: resolve merge conflict dengan fitur login-google

#### 📅 Tanggal 2026-10-21 — Alur Pembayaran Transfer Manual & Modul Verifikasi Admin (6 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pembangunan alur checkout member dengan metode pembayaran transfer manual (unggah bukti transfer gambar JPEG/PNG/WebP, validasi ukuran berkas maksimal 2MB), pembuatan antarmuka verifikasi pembayaran sisi Admin (daftar transaksi berstatus pending, modal pratinjau bukti bayar, tombol Approve dan Reject dengan catatan alasan penolakan), serta pembaruan otomatis status membership menjadi aktif saat disetujui.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Sistem FitCore dirancang tanpa gateway pihak ketiga berbayar untuk memangkas biaya operasional gym skala menengah. Oleh karena itu, diperlukan alur verifikasi human-in-the-loop yang ketat agar kasir/admin dapat memvalidasi mutasi bank secara manual sebelum hak akses fasilitas gym diberikan kepada member.
- **Daftar Commit Terkait**:
  - [`ef49bb9`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ef49bb9) (**Muhammad Rif’at Syamlan**): tambah test case alur navigasi, checkout, dan validasi bukti transfer
  - [`5b39755`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5b39755) (**Muhammad Rif’at Syamlan**): perbarui kalkulasi checkout dan validasi upload bukti pembayaran
  - [`2165b3e`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2165b3e) (**Muhammad Rif’at Syamlan**): tambah tombol kembali, preview file dinamis, dan tombol kirim pudar saat disabled
  - [`cfb317e`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/cfb317e) (**Muhammad Rif’at Syamlan**): hapus kode promo, default uncheck pilihan bank, dan tombol beli pudar saat disabled
  - [`94c909e`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/94c909e) (**Muhammad Rif’at Syamlan**): tambah tombol kembali, pencarian real-time, dan hapus filter rating
  - [`822d985`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/822d985) (**Muhammad Rif’at Syamlan**): sesuaikan padding dan jarak kontainer hero dan daftar sesi

#### 📅 Tanggal 2026-10-22 — Dashboard Sesi Latihan PT, Manajemen Trainer & Transisi Status Sesi (24 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Penyelesaian dashboard personal trainer untuk menampilkan ringkasan jadwal sesi harian dan mingguan, implementasi aksi konfirmasi (Setujui / Tolak permohonan reservasi member), penambahan modul CRUD data pelatih pada portal Admin (`TrainerController`), dan pembuatan scheduler transisi status sesi dari *Terkonfirmasi* menjadi *Selesai* berdasarkan batas waktu jam sesi.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Pelatih memerlukan visibilitas jadwal yang jelas agar tidak terjadi kebingungan di gym. Transisi status otomatis diperlukan agar kuota sesi member berkurang secara tepat waktu dan perhitungan komisi pelatih dapat terakumulasi tanpa intervensi manual.
- **Daftar Commit Terkait**:
  - [`c09dd1c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c09dd1c) (**Siti Aisyah**): fix(trainer-sessions): unify schedule page with dashboard FitCore PT Portal layout and sidebar
  - [`2f71972`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2f71972) (**Siti Aisyah**): refactor(trainer-sessions): use Livewire 3 Layout attribute for Index component
  - [`57797b4`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/57797b4) (**Muhammad Rif’at Syamlan**): chore: update composer.lock for livewire and socialite
  - [`defc634`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/defc634) (**Siti Aisyah**): fix(migrations): deduplicate pre-existing sessions before creating unique index
  - [`4bcf12f`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4bcf12f) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/fix/menu-dashboard-trainer'
  - [`f87f7a8`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f87f7a8) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/pt-session-management'
  - [`51bf009`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/51bf009) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/pt-session-booking'
  - [`8e75440`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8e75440) (**Muhammad Rif’at Syamlan**): Merge remote-tracking branch 'origin/feature/dashboard-pt' into feature/admin-manajemen-paket-sesi-pt
  - [`cd52d77`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/cd52d77) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/buy-pt-package'
  - [`d185fb0`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d185fb0) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/admin-package-management'
  - [`255714a`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/255714a) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/qr-code-check-in'
  - [`db9f979`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/db9f979) (**Siti Aisyah**): merge: resolve conflicts and merge origin/feature/trainer-session-schedule-dashboard
  - [`8757f40`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8757f40) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/role-permision'
  - [`8e56580`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8e56580) (**Muhammad Rif’at Syamlan**): Merge branch 'origin/feature/auth-admin'
  - [`f153d94`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f153d94) (**Naufal Alif Adjani**): chore: sync composer.lock with laravel/socialite
  - [`7bba589`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7bba589) (**Siti Aisyah**): chore: setup livewire and trainer schedule route
  - [`74db95d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/74db95d) (**Naufal Alif Adjani**): Merge branch 'feature/member-membership-checkout' into integration/pt-session-and-checkout
  - [`0c82627`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0c82627) (**Siti Aisyah**): fix: perbaikan navigasi dan tampilan menu di dashboard trainer
  - [`63b70f6`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/63b70f6) (**Siti Aisyah**): feat: implementasi dashboard personal trainer
  - [`6a1a68f`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6a1a68f) (**Siti Aisyah**): feat: tambah role & permision serta middleware error 403
  - [`0001509`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0001509) (**Siti Aisyah**): Merge branch 'feature/member-membership-checkout' into develop
  - [`6492f92`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6492f92) (**Siti Aisyah**): chore: update seeders with email verification and UserSeeder call
  - [`883318e`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/883318e) (**Achmad Alfi Maksum**): feat: integrasi data real-time dashboard admin, penambahan seeder massal, dan perbaikan auth test
  - [`4846158`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4846158) (**Achmad Alfi Maksum**): refactor: perbaikan UI/UX dashboard admin, paket membership, dan verifikasi pembayaran sesuai Design System

#### 📅 Tanggal 2026-10-23 — Refactoring Arsitektur Kalender & Pencegahan Overlapping Sesi (2 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pemisahan logika pengecekan jadwal ke dalam `BookingService`, integrasi kalender interaktif FullCalendar JS, serta penambahan validasi backend untuk mencegah tumpang tindih waktu (*double-booking prevention*) pada trainer yang sama di jam yang sama.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Uji coba awal menemukan potensi race condition ketika dua member melakukan booking pada slot jam pelatih yang bersamaan. Logika bentrok diisolasi dengan database locking dan validasi atomik demi integritas data jadwal.
- **Daftar Commit Terkait**:
  - [`b0b9a5b`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b0b9a5b) (**Naufal Alif Adjani**): merge membershi checkout and pt-session
  - [`43a33ac`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/43a33ac) (**Muhammad Rif’at Syamlan**): feat(admin): implement personal trainer package management

#### 📅 Tanggal 2026-10-24 — Implementasi Algoritma Auto-Plotting Sesi Preferensi Member (3 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pembuatan alur penetapan preferensi hari dan jam latihan oleh member, implementasi algoritma pencocokan otomatis (*auto-plotting algorithm*) yang mencari slot kosong trainer bimbingan, serta unit testing khusus algoritma plotting.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Member yang membeli paket 8 atau 12 sesi merasa repot jika harus memilih tanggal satu per satu secara manual. Fitur auto-plotting memetakan seluruh sesi bulanan secara instan sesuai jam luang member dan jadwal pelatih.
- **Daftar Commit Terkait**:
  - [`a403124`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a403124) (**Achmad Alfi Maksum**): feat(member): implementasi riwayat transaksi, komponen UI kondisional, grouping bulan, dan unduh PDF invoice
  - [`fdd27e6`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/fdd27e6) (**Naufal Alif Adjani**): Merge pull request #11 from NaufalAlifAdjani/integration/pt-session-and-checkout
  - [`f5246d4`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f5246d4) (**Naufal Alif Adjani**): integrate pt session and checkout

### 🔹 MINGGU 10 (26 Oktober – 01 November 2026) — Rating PT, Alur Auto-Plotting, Invoice & Email Reminder (49 Commits)

#### 📅 Tanggal 2026-10-26 — Modul Rating & Ulasan Personal Trainer (2 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Implementasi komponen feedback pasca-latihan (rating bintang 1–5 dan ulasan teks) yang hanya muncul setelah status sesi berubah menjadi *Selesai*. Rata-rata rating dihitung secara agregat dan ditampilkan pada profil kartu pelatih.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Sistem memerlukan metrik evaluasi kepuasan pelanggan secara objektif. Rating ini digunakan sebagai dasar pembagian insentif dan evaluasi kualitas pengajaran trainer oleh manajemen gym.
- **Daftar Commit Terkait**:
  - [`4ed3099`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4ed3099) (**Naufal Alif Adjani**): Merge pull request #12 from NaufalAlifAdjani/integration/add-role-permision
  - [`f04c84c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f04c84c) (**Naufal Alif Adjani**): integration add-role-permision

#### 📅 Tanggal 2026-10-28 — Perubahan Alur Auto-Plotting & Migrasi Master Tabel Bank Dinamis (15 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pencatatan Change Log resmi (Issue #71 & #72): penambahan tabel `banks` untuk menampung rekening tujuan transfer gym secara dinamis (nama bank, nomor rekening, atas nama, logo), penghapusan rekening hardcoded pada view Blade, serta penyesuaian alur auto-plotting agar member dapat merevisi jadwal jika plotting awal tidak sesuai.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Gym mitra Mas K dan Mas S memiliki lebih dari satu rekening operasional (BCA dan Mandiri) yang sering berganti jika ada pergantian rekening kasir. Pemindahan ke tabel dinamis memungkinkan admin mengubah nomor rekening kapan saja dari dashboard tanpa menyentuh kode.
- **Daftar Commit Terkait**:
  - [`8913275`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8913275) (**Achmad Alfi Maksum**): Merge pull request #16 from NaufalAlifAdjani/revert-15-feature/member-transaction-history
  - [`bb83d45`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/bb83d45) (**Achmad Alfi Maksum**): Revert "Feature/member transaction history"
  - [`4510670`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4510670) (**Muhammad Rif’at Syamlan**): fix(admin): remove stat cards and fix horizontal scrolling on pt packages management
  - [`29dacb4`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/29dacb4) (**Achmad Alfi Maksum**): Merge pull request #15 from NaufalAlifAdjani/feature/member-transaction-history
  - [`e8c0bb7`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e8c0bb7) (**Naufal Alif Adjani**): fix: sync logout modal text with tests and configure testing locale in phpunit.xml
  - [`f865772`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f865772) (**Naufal Alif Adjani**): style: apply Laravel Pint styling fixes
  - [`46b9546`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/46b9546) (**Naufal Alif Adjani**): chore: add default en language files
  - [`d8ea88d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d8ea88d) (**Naufal Alif Adjani**): fix: align payment seeder with active pt packages and restore logout confirmation message
  - [`31cf63c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/31cf63c) (**Muhammad Rif’at Syamlan**): fix(admin): order pt packages and payment verifications by latest created
  - [`e0298b9`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e0298b9) (**Muhammad Rif’at Syamlan**): fix(pt-packages): integrate pt package purchase with admin payment verification and fix dead links
  - [`77fb4a7`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/77fb4a7) (**Muhammad Rif’at Syamlan**): fix: add scheduled and done values to PtSessionStatus enum to prevent ValueError
  - [`528b49c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/528b49c) (**Muhammad Rif’at Syamlan**): chore: align conflicted files with develop branch as requested
  - [`865f296`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/865f296) (**Muhammad Rif’at Syamlan**): merge: integrate feature/buy-pt-package and feature/admin-manajemen-paket-sesi-pt with latest develop
  - [`c1c24a9`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c1c24a9) (**Siti Aisyah**): feat: implementsasi halaman manajemen data member
  - [`5f4d4d7`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5f4d4d7) (**Siti Aisyah**): merge: integrate trainer dashboard and role permissions with pt session and checkout

#### 📅 Tanggal 2026-10-29 — Fitur Invoice & Unduh Bukti Pembayaran Digital (18 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pembuatan halaman invoice resmi dengan nomor referensi unik, rincian paket, rincian biaya, status lunas, cap verifikasi admin, serta tombol cetak/unduh kwitansi transaksi.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Member memerlukan bukti pembayaran sah untuk keperluan arsip pribadi maupun reimbursement fasilitas kesehatan kantor, serta memudahkan akuntansi gym saat rekapitulasi pembukuan akhir bulan.
- **Daftar Commit Terkait**:
  - [`27cb631`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/27cb631) (**Siti Aisyah**): feat: halaman manajemen trainer role admin
  - [`8ec4090`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8ec4090) (**Siti Aisyah**): merge: integrate feature/manage-members into feature/admin-manage-trainers
  - [`e336be7`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e336be7) (**Naufal Alif Adjani**): merge: branch feature/member-qr-code-integration into develop
  - [`468feb6`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/468feb6) (**Siti Aisyah**): feat: halaman manajemen trainer role admin
  - [`3207ce6`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3207ce6) (**Naufal Alif Adjani**): merge: branch fix/smtp-verification into develop
  - [`270c7e6`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/270c7e6) (**Naufal Alif Adjani**): feat: redirect halaman utama ke halaman login
  - [`a514533`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a514533) (**Naufal Alif Adjani**): fix: alur verifikasi email untuk tamu dan pop-up sukses di dashboard
  - [`c15806c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c15806c) (**Naufal Alif Adjani**): add notification system and session notifications
  - [`ea3321d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ea3321d) (**Siti Aisyah**): fix: Memperbaiki konflik merge dengan branch develop
  - [`8688ed8`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8688ed8) (**Siti Aisyah**): fix: perbaikan controller login google dan kongigurasi app
  - [`24ccfbe`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/24ccfbe) (**Muhammad Rif’at Syamlan**): selaraskan navbar dan hero header halaman booking sesi pt
  - [`467111d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/467111d) (**Muhammad Rif’at Syamlan**): feat(qr-code): integrate member digital card and admin scanner with FitCore UI design system
  - [`6459f43`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6459f43) (**Siti Aisyah**): fix: perbaikan konfigurasi dan proses verifikasi email via smtp
  - [`b605bbc`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b605bbc) (**Siti Aisyah**): merge: integrate origin/develop into feature/manage-members
  - [`1c26042`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1c26042) (**Muhammad Rif’at Syamlan**): fix(admin): resolve input padding overlap with Rp prefix in pt package modal
  - [`8e850b7`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8e850b7) (**Achmad Alfi Maksum**): fix(admin): sederhanakan ui tabel package dengan warna statis abu-abu
  - [`6761df9`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6761df9) (**Achmad Alfi Maksum**): fix(admin): hapus summary widget dan implementasi pewarnaan dinamis tabel membership berdasarkan tier
  - [`598928a`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/598928a) (**Achmad Alfi Maksum**): perf(member): implementasi kompresi otomatis max 800px format WebP pada bukti pembayaran

#### 📅 Tanggal 2026-10-30 — Sistem Notifikasi Asinkron & Email Reminder Masa Aktif (Queue Worker) (8 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Konfigurasi Laravel Queue (`QUEUE_CONNECTION=database`), implementasi Mailable class untuk notifikasi persetujuan pembayaran, pendaftaran akun baru, dan notifikasi jadwal sesi PT, serta scheduler pengingat email otomatis H-7 dan H-3 sebelum membership habis.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Pengiriman email SMTP langsung pada request HTTP menyebabkan respon halaman lambat (*freezing*). Dengan pemindahan ke database queue, UI tetap instan (non-blocking) dan member mendapatkan pengingat perpanjangan secara otomatis.
- **Daftar Commit Terkait**:
  - [`3c949df`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/3c949df) (**Naufal Alif Adjani**): merge: integrasikan perbaikan email verification dari branch fix/email-verification ke develop
  - [`a489e98`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a489e98) (**Siti Aisyah**): merge: selesaikan konflik develop ke fix/email-verification
  - [`ad652ce`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ad652ce) (**Naufal Alif Adjani**): fix(navbar): resolve dual active button highlighting between membership and pt packages
  - [`a5ff4da`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a5ff4da) (**Naufal Alif Adjani**): fix(riwayat-transaksi): remove finance verification estimate pill from pending transaction card
  - [`6a0d4c0`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/6a0d4c0) (**Siti Aisyah**): fix: perbaikan halaman verifikasi email login
  - [`14e4687`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/14e4687) (**Naufal Alif Adjani**): fix(member): randomize unique verification code, unify member navbar, retain purchased pt cards, and extract reusable payment components
  - [`f7de0b4`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f7de0b4) (**Naufal Alif Adjani**): merge: branch feature/member-transaction-history into develop
  - [`a44baf9`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/a44baf9) (**Naufal Alif Adjani**): style(member-card): update layout and styling to app-layout

#### 📅 Tanggal 2026-10-31 — Wireframe Final 3 Role & Evaluasi Sprint 2 (4 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Penyelarasan mockup antarmuka 3 role pengguna (Member, Trainer, Admin), penutupan Issue #70 (Wireframe seluruh fitur 3 role), refactoring styling tombol dan kartu profil.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Memastikan konsistensi komponen UI (padding, typography, color palette) sebelum memasuki tahapan integrasi akhir dan pengujian fungsional.
- **Daftar Commit Terkait**:
  - [`d9f8d0f`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d9f8d0f) (**Naufal Alif Adjani**): Merge pull request #73 from NaufalAlifAdjani/integration/ci-cd
  - [`c4b2513`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c4b2513) (**Naufal Alif Adjani**): integrate cicd
  - [`03ab867`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/03ab867) (**Naufal Alif Adjani**): fix(admin): perbarui layout manajemen paket sesi pt pada admin/pt-sessions dari fix/admin-pt-packages-layout
  - [`19a61e6`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/19a61e6) (**Naufal Alif Adjani**): merge: integrasikan perbaikan member navbar dan payment refactor ke develop

#### 📅 Tanggal 2026-11-01 — Penyusunan Rencana Anggaran Biaya (RAB) & Budget Baseline M10 (2 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pencatatan Issue #75: Rencana Anggaran Biaya (RAB) dan budget baseline proyek minggu ke-10, evaluasi penggunaan resource hosting lokal dan Mailtrap.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Memenuhi standar tata kelola manajemen proyek sistem informasi untuk memantau variansi biaya implementasi perangkat lunak.
- **Daftar Commit Terkait**:
  - [`5c5fede`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5c5fede) (**Naufal Alif Adjani**): Merge pull request #74 from NaufalAlifAdjani/feature/trainer-dashboard-enhancement
  - [`27393cd`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/27393cd) (**Naufal Alif Adjani**): feat(trainer): perbarui dashboard dengan estimasi komisi, jadwal harian, dan approval sesi

### 🔹 MINGGU 11 (02–08 November 2026) — CI/CD Actions, UI Mobile-First Light Mode & Pengujian PMPL E2E (31 Commits)

#### 📅 Tanggal 2026-11-03 — Integrasi CI/CD Workflow GitHub Actions (2 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pembuatan pipeline CI/CD pada `.github/workflows/laravel.yml` (Issue #73), konfigurasi runner otomatis untuk menjalankan linting code style, migrasi database sqlite in-memory, dan suite pengujian PHPUnit pada setiap pull request ke branch `develop` dan `main`.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Mencegah merge conflict dan error regresi saat banyak anggota tim menggabungkan fitur secara bersamaan menjelang rilis akhir.
- **Daftar Commit Terkait**:
  - [`49a63a8`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/49a63a8) (**Naufal Alif Adjani**): docs: add ALL diagram (Use case, activity, ERD, class diagram)
  - [`c4e31db`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c4e31db) (**Naufal Alif Adjani**): docs: add PMPL testing suite (Modul A - D)

#### 📅 Tanggal 2026-11-05 — Peningkatan Dashboard Trainer & Pengujian E2E PMPL (4 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pembaruan dashboard trainer dengan penambahan kartu estimasi komisi bulanan, daftar sesi hari ini dengan tombol quick-approval, penambahan fitur daftar riwayat klien per sesi sisi trainer, serta implementasi rangkaian uji E2E FitCore (PMPL Test Suite).
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Trainer membutuhkan transparansi nominal honor komisi yang akan mereka terima berdasarkan akumulasi sesi yang berstatus selesai. Rangkaian uji E2E ditambahkan untuk memenuhi kepatuhan standar penjaminan mutu perangkat lunak.
- **Daftar Commit Terkait**:
  - [`baeb426`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/baeb426) (**Siti Aisyah**): Merge branch 'feature/admin-manage-trainers' into test/pmpl-e2e-fitcore
  - [`45eb974`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/45eb974) (**Siti Aisyah**): fix(admin): hapus tombol tambah member pada halaman manajemen member
  - [`fc1c4c5`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/fc1c4c5) (**Siti Aisyah**): test: menambahkan rangkaian uji e2e fitcore
  - [`5061d6a`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5061d6a) (**Naufal Alif Adjani**): feat(trainer): implement daftar dan riwayat member per sesi sisi PT

#### 📅 Tanggal 2026-11-06 — Arsitektur UI/UX Mobile-First & Middleware Auto-Redirect (1 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Implementasi arsitektur mobile-first (`AutoRedirectMobile` middleware) yang mendeteksi User-Agent perangkat seluler, penyediaan routing dan view khusus mobile (`/m/*`) untuk member dan trainer dengan komponen bottom navigation.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Hasil observasi lapangan membuktikan bahwa 90% member mengakses sistem dari smartphone saat di lokasi gym untuk scan QR dan cek sisa sesi. Pengalaman seluler native jauh lebih ergonomis dibanding desktop view biasa.
- **Daftar Commit Terkait**:
  - [`8418feb`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/8418feb) (**Achmad Alfi Maksum**): feat: implement mobile-first UI/UX architecture, role views, and auto-redirect middleware

#### 📅 Tanggal 2026-11-07 — Master Hari Kerja PT, Layout Publik & Navigasi Mobile (7 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pembuatan fitur pengaturan hari kerja dan ketersediaan waktu per trainer (trainer dapat memilih hari aktif Senin-Minggu dan rentang jam kerja), penataan ulang landing page publik dan funnel pendaftaran, serta penyelesaian mobile trainer views.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Setiap personal trainer memiliki jadwal kuliah atau kesibukan yang berbeda. Fitur master hari kerja memastikan member tidak dapat memesan sesi di hari libur pelatih.
- **Daftar Commit Terkait**:
  - [`562f355`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/562f355) (**Achmad Alfi Maksum**): feat: register public layout component, landing controller, and auth navigation cleanups
  - [`4ed2c1f`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4ed2c1f) (**Achmad Alfi Maksum**): fix: resolve public-to-member conversion funnel, preserve query redirects, and standardize mobile light theme layout
  - [`b2888ac`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b2888ac) (**Achmad Alfi Maksum**): feat: complete trainer mobile views in light theme, auto-redirect, and test suite
  - [`1e85e63`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/1e85e63) (**Achmad Alfi Maksum**): fix: resolve mobile bottom nav active tab matching and overhaul PT sessions UI layout
  - [`0932d84`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0932d84) (**Naufal Alif Adjani**): style(trainer): perbaiki layout tombol simpan ketersediaan jadwal agar menyatu dan tidak menutupi slot
  - [`64c40c0`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/64c40c0) (**Naufal Alif Adjani**): feat(trainer): implement master hari kerja ketersediaan waktu PT dan penyelarasan jadwal booking
  - [`47856ed`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/47856ed) (**Achmad Alfi Maksum**): feat: integrate mobile package checkout flow, pt session detail view, and refactor light theme UI

#### 📅 Tanggal 2026-11-08 — Dukungan Hardware QR Scanner Fisik, Tema Blaze Orange & Light Mode (17 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Penambahan dukungan alat pemindai barcode/QR fisik berbasis hardware (USB/Bluetooth HID input) dengan auto-stop scanner dan proteksi double scan interval 10 menit, standardisasi identitas visual aplikasi ke tema *Blaze Orange Athletic Core*, perbaikan error penanganan login Google OAuth dinamis, dan refactoring `develop` branch.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Resepsionis gym lebih cepat memindai kartu digital member menggunakan scanner tembak meja kasir dibanding menyalakan webcam laptop. Tema visual Blaze Orange diimplementasikan untuk memberikan kesan sporty, bertenaga, dan modern sesuai identitas FitCore.
- **Daftar Commit Terkait**:
  - [`542e2f1`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/542e2f1) (**Muhammad Rif’at Syamlan**): feat(checkin): add support for physical hardware qr scanner device
  - [`2320617`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2320617) (**Naufal Alif Adjani**): fix(pt-sessions): fix livewire morph issue by stripping BOM and adding wire keys
  - [`0f14cb5`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0f14cb5) (**Naufal Alif Adjani**): feat(branding): implement new FitCore flame logo with transparent background and favicon across layouts
  - [`5f8e056`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5f8e056) (**Achmad Alfi Maksum**): fix: resolve invoice download route and scale down mobile package typography for 2-column grid
  - [`e8174bd`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/e8174bd) (**Naufal Alif Adjani**): feat(theme): switch to vibrant light athletic palette with Blaze Orange accents
  - [`2a26917`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/2a26917) (**Naufal Alif Adjani**): feat(theme): apply Blaze Orange athletic color palette
  - [`d277b4d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d277b4d) (**Muhammad Rif’at Syamlan**): feat(checkin): add support for physical hardware qr scanner device
  - [`671a3ec`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/671a3ec) (**Achmad Alfi Maksum**): fix: implement reverse auto-redirect from mobile routes to desktop view based on user agent and roles
  - [`d7bf2f0`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d7bf2f0) (**Naufal Alif Adjani**): Merge branch 'fix/scanner-qr-checkin' into develop
  - [`246ea7c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/246ea7c) (**Muhammad Rif’at Syamlan**): feat(checkin): auto-stop scanner, display today check-in table and add next scan button
  - [`0e7405d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0e7405d) (**Naufal Alif Adjani**): fix(auth): perbaiki penanganan error state login google dan konfigurasi redirect dinamis
  - [`dcf61c3`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/dcf61c3) (**Muhammad Rif’at Syamlan**): fix(scanner): allow qr check-in for memberships linked via payment_id
  - [`154282c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/154282c) (**Achmad Alfi Maksum**): perf: replace agent package with lightweight native regex for device detection in AutoRedirectMobile
  - [`090ec7e`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/090ec7e) (**Naufal Alif Adjani**): merge: integrasikan fitur ketersediaan waktu PT ke develop
  - [`39f97ae`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/39f97ae) (**Naufal Alif Adjani**): merge: integrasikan fitur riwayat sesi klien PT ke develop
  - [`7d99a11`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/7d99a11) (**Naufal Alif Adjani**): refactor(trainer): pisahkan fitur ketersediaan waktu PT ke dedicated branch terpisah
  - [`b4b75e2`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b4b75e2) (**Naufal Alif Adjani**): feat(trainer): implement master hari kerja ketersediaan waktu PT dan penyelarasan jadwal booking

### 🔹 MINGGU 12 (09–15 November 2026) — Shift Trainer 24 Jam Gym, Dashboard Admin & Finalisasi Dokumentasi (14 Commits)

#### 📅 Tanggal 2026-11-09 — Penyelarasan Warna Palette & Naming Label Beli Paket (1 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pembaruan label navigasi dan tombol CTA menjadi `Beli Paket`, penyelarasan shade warna Blaze Orange pada kartu membership dan kartu sesi trainer.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Uji coba pengguna menunjukkan beberapa member bingung membedakan paket gym dan paket instruktur. Penamaan dan aksen warna diperjelas agar alur konversi lebih intuitif.
- **Daftar Commit Terkait**:
  - [`ab9c326`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ab9c326) (**Naufal Alif Adjani**): feat(pt-packages): update title to Beli Paket and align colors with athletic core palette

#### 📅 Tanggal 2026-11-10 — Penyatuan Sidebar Portal Trainer & Standardisasi Light Theme Desktop (5 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Refactoring layout sidebar FitCore PT Portal agar konsisten dengan admin layout, pengaplikasian tema light mode menyeluruh pada member area web desktop, dan perbaikan konflik merge styling.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Tampilan antarmuka trainer sebelumnya memiliki struktur navigasi yang berbeda dengan bagian member. Penyatuan ini menghasilkan kode Blade yang modular dan pengalaman pengguna yang terpadu.
- **Daftar Commit Terkait**:
  - [`f06ec1a`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f06ec1a) (**Naufal Alif Adjani**): Merge branch 'feature/theme-blaze-orange' into develop
  - [`0980e8c`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/0980e8c) (**Naufal Alif Adjani**): fix(trainer-sessions): unify sidebar and layout with FitCore PT Portal
  - [`83047e8`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/83047e8) (**Achmad Alfi Maksum**): style: standardize blaze orange theme, resolve merge conflicts, and fix typo assertions
  - [`b8ccfde`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/b8ccfde) (**Achmad Alfi Maksum**): Merge branch 'feature/theme-blaze-orange' into feature/ui-ux-web-light-mode
  - [`ade2d87`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/ade2d87) (**Achmad Alfi Maksum**): style: apply complete light theme to member area web desktop views

#### 📅 Tanggal 2026-11-11 — Fitur Shift Trainer 24 Jam Gym, Komisi PT & Pembaruan Dashboard Admin (6 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Pengembangan fitur shift kerja pelatih untuk gym operasional 24 jam (pembagian shift pagi, sore, dan malam), modul pengaturan dan rekapitulasi komisi sesi PT di halaman manajemen trainer Admin, pembaruan 4 card indikator utama pada dashboard admin (Total Member Aktif, Pendapatan Bulan Ini, Sesi PT Terlaksana, Transaksi Pending), sinkronisasi filter rentang tanggal pada bar chart analitik, serta penyesuaian limit riwayat transaksi pada komponen lonceng notifikasi navbar.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Gym mitra merencanakan perluasan operasional menjadi gym 24 jam sehingga pembagian shift trainer sangat krusial. Pemilik gym juga menuntut laporan keuangan dan aktivitas latihan real-time yang dapat difilter per minggu atau per bulan pada halaman depan dashboard.
- **Daftar Commit Terkait**:
  - [`321ddd7`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/321ddd7) (**Naufal Alif Adjani**): merge: integrasikan fitur shift trainer, gym 24 jam, dan komisi sesi pt ke develop
  - [`508335d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/508335d) (**Naufal Alif Adjani**): feat(pt): implementasi shift trainer, gym 24 jam, dan pengelolaan komisi pt di manajemen trainer
  - [`70e761e`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/70e761e) (**Naufal Alif Adjani**): feat(dashboard): perbarui 4 card dashboard admin dan sinkronkan filter tanggal pada bar chart
  - [`c126f31`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/c126f31) (**Naufal Alif Adjani**): test: sesuaikan query count limit riwayat transaksi dengan komponen notifikasi navbar
  - [`5216f78`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/5216f78) (**Naufal Alif Adjani**): merge: integrasikan mobile UI/UX dan web light mode ke develop
  - [`d14a3d4`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/d14a3d4) (**Naufal Alif Adjani**): merge: integrasikan sistem notifikasi sesi PT ke develop dengan tema Blaze Orange

#### 📅 Tanggal 2026-11-12 — Sinkronisasi Dokumentasi Lengkap Proposal PBL & Dokumen Pengujian PMPL (2 Commits)
- **Apa Saja yang Berubah (Teknis & Arsitektur)**:
  Penyusunan dan pengarsipan Proposal Lengkap PBL (BAB I – IX), Dokumen Test Plan v1.0 standar IEEE 829, Dokumen Test Scenario & Test Case Execution Record (25 Test Cases 100% Pass) ke dalam folder `docs/` repositori, publikasi dokumen ke papan GitHub Project PBL FITCORE (Issue #90, #91, #92), serta pembagian logbook Milestone 1 (#93) dan Milestone 2.
- **Alasan Perubahan Secara Detail (Rasional Bisnis & Masalah yang Diselesaikan)**:
  Memastikan seluruh artefak luaran proyek terdokumentasi secara transparan, memiliki ketertelusuran (*traceability*) ke kode WBS, dan siap dipresentasikan pada evaluasi akhir mata kuliah Penjaminan Mutu Perangkat Lunak dan Manajemen Proyek.
- **Daftar Commit Terkait**:
  - [`f9ef72b`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/f9ef72b) (**Naufal Alif Adjani**): docs: add project logbook weeks 1 to 12
  - [`4f1637d`](https://github.com/NaufalAlifAdjani/fitcore-gym/commit/4f1637d) (**Naufal Alif Adjani**): docs: add full proposal PBL, Test Plan v1.0, and Test Execution Record
