# PANDUAN DIAGRAM SISTEM FITCORE GYM (.DRAWIO)

Direktori ini berisi berkas perancangan arsitektur sistem, basis data, dan alur proses bisnis untuk sistem **FitCore Gym Management & PT Booking Platform**. Seluruh diagram dirancang mengikuti standar baku **UML & ERD Bahasa Indonesia** (gaya laporan teknis/skripsi: hitam-putih, garis tegas, tanpa warna latar/fill dekoratif, dan **100% konsisten dengan kode sumber riil proyek**).

---

## 📁 Daftar Berkas Diagram

| No | Nama Berkas | Jenis Diagram | Cakupan & Format Pengelompokan |
|---|---|---|---|
| 1 | [`1_ERD_FitCore.drawio`](file:///d:/Project%20Laravel/fitcore/docs/diagrams/1_ERD_FitCore.drawio) | **Entity Relationship Diagram (ERD)** | 13 Tabel database riil (`users`, `member_profiles`, `trainer_profiles`, `memberships`, `membership_packages`, `payments`, `banks`, `pt_packages`, `member_pt_quotas`, `pt_sessions`, `check_ins`, `ratings`, `verification_logs`) lengkap dengan penanda Primary Key (`PK`), Foreign Key (`FK`), serta relasi *crow's foot* kardinalitas (`1`, `0..1`, `1..*`, `0..*`) dan label kata kerja bahasa Indonesia. |
| 2 | [`2_Class_Diagram_FitCore.drawio`](file:///d:/Project%20Laravel/fitcore/docs/diagrams/2_Class_Diagram_FitCore.drawio) | **Class Diagram (UML)** | Struktur 3 kompartemen lengkap: **(1) Nama Entitas Domain**, **(2) Atribut Kolom Data Lengkap**, dan **(3) Method/Fungsi Operasional yang HANYA bersumber dari Controller (`app/Http/Controllers/`) dan Service (`app/Services/`) riil** yang memproses entitas tersebut. |
| 3 | [`3_Use_Case_Diagram_FitCore.drawio`](file:///d:/Project%20Laravel/fitcore/docs/diagrams/3_Use_Case_Diagram_FitCore.drawio) | **Use Case Diagram** | Batas sistem *"Sistem Aplikasi Management GYM/FITCORE"*, interaksi 3 aktor sistem (**Member** di kiri, **Personal Trainer** dan **Admin** di kanan), use case *"Login Sistem"* di tengah terhubung ke seluruh aktor, serta 21 use case inti dengan relasi `<<include>>` terstandarisasi. |
| 4 | [`4_Activity_Diagram_FitCore.drawio`](file:///d:/Project%20Laravel/fitcore/docs/diagrams/4_Activity_Diagram_FitCore.drawio) | **Activity Diagram (1 Tab per Role)** | **Dikelompokkan menjadi 3 Tab Halaman per Peran (Role)**: Tab **Member** (memuat seluruh 10 use case Member), Tab **Personal Trainer** (memuat seluruh use case Pelatih), dan Tab **Admin** (memuat seluruh use case Admin). Disusun berdampingan dalam tata letak multi-kolom yang rapi. |
| ⭐️ | [`FitCore_All_Diagrams.drawio`](file:///d:/Project%20Laravel/fitcore/docs/diagrams/FitCore_All_Diagrams.drawio) | **Master File (6 Tabs)** | Menggabungkan seluruh diagram ke dalam **1 berkas terpadu dengan 6 tab navigasi**: Tab 1 ERD, Tab 2 Class Diagram, Tab 3 Use Case, Tab 4 Activity Member, Tab 5 Activity Trainer, dan Tab 6 Activity Admin. |

---

## 📑 2. Struktur 3 Tab Activity Diagram pada `4_Activity_Diagram_FitCore.drawio`

Setiap peran (*role*) pengguna memiliki tab halamannya masing-masing di Draw.io. Di dalam setiap tab, seluruh diagram alur aktivitas (*swimlane*) ditata berdampingan secara terstruktur:

### 🔹 Tab 1: `Activity Diagram - Member`
Memuat 10 alur proses operasional yang dapat dijalankan oleh Member gym:
1. **Alur Registrasi Akun**: Input data diri, validasi format & keunikan email, pembuatan record `User` & `MemberProfile`, dan pengiriman email verifikasi.
2. **Alur Login Sistem (Member)**: Autentikasi email & password, inisialisasi sesi, dan pengalihan ke dashboard member.
3. **Alur Memilih Paket Keanggotaan**: Eksplorasi katalog membership, pengecekan paket berjalan, dan persiapan tagihan checkout.
4. **Alur Pembayaran (Upload Bukti)**: Pemilihan bank transfer, validasi berkas gambar bukti, pembuatan record `Payment` (PENDING), dan notifikasi admin.
5. **Alur Membeli Paket Sesi PT**: Pilihan paket sesi, validasi status membership aktif (*pre-requisite*), dan pembuatan invoice transfer.
6. **Alur Presensi Check-in via QR Code**: Validasi membership aktif, rendering kode QR token dinamis pada layar kartu digital member.
7. **Alur Melihat Jadwal Personal Trainer**: Pemilihan instruktur, query kalender jam kerja, dan penampilan slot waktu kosong.
8. **Alur Booking Sesi Personal Trainer**: Pengecekan sisa kuota PT (>0), pencegahan bentrok slot ganda, pemotongan kuota sementara, konfirmasi pelatih, dan penguncian jadwal (CONFIRMED).
9. **Alur Melihat Riwayat Transaksi & Sesi**: Pengambilan tabulasi data invoice, masa aktif paket, histori latihan, dan log kehadiran gym.
10. **Alur Memberi Rating & Ulasan Pelatih**: Form bintang (1-5) dan ulasan pada sesi berstatus COMPLETED, validasi form, dan kalkulasi rata-rata rating instruktur.

### 🔹 Tab 2: `Activity Diagram - Personal Trainer`
Memuat alur proses operasional instruktur:
1. **Alur Login Sistem (Trainer)**: Otentikasi kredensial instruktur dan pengalihan ke panel Trainer Dashboard.
2. **Alur Melihat Dashboard & Jadwal**: Query sesi mendatang, kalender harian/mingguan, estimasi komisi, dan rating kepuasan.
3. **Alur Konfirmasi / Tolak Sesi Member**: Peninjauan permohonan sesi berstatus PENDING; persetujuan (CONFIRMED) atau penolakan dengan alasan (CANCELLED & kuota member dikembalikan otomatis).
4. **Alur Melihat Riwayat Sesi & Performa**: Tabulasi histori mengajar member, ulasan member per sesi, dan rekapitulasi performa.

### 🔹 Tab 3: `Activity Diagram - Admin`
Memuat alur proses operasional pengelola gym / resepsionis:
1. **Alur Login Sistem (Admin)**: Validasi kredensial administrator dan otorisasi akses ke modul back-office.
2. **Alur Verifikasi Pembayaran Transfer**: Pengecekan mutasi transfer rekening bank; verifikasi transaksi (VERIFIED & aktivasi paket/kuota) atau penolakan bukti (REJECTED dengan catatan revisi).
3. **Alur Scan QR Code Presensi Harian**: Pemindaian kode QR kartu member lewat scanner front desk, validasi unik harian, pencatatan record `check_ins` (method: QR), dan otorisasi masuk.
4. **Alur Manajemen Data Member & Trainer**: Penambahan, pembaruan profil, atau penonaktifan akun member/pelatih.
5. **Alur Manajemen Paket Membership & PT**: Konfigurasi harga, durasi, jumlah sesi, serta pengaktifan/penonaktifan (*toggle status*) paket layanan gym.

---

## 🏗️ 3. Struktur Kompartemen pada `2_Class_Diagram_FitCore.drawio`

Format 3 kompartemen berstandar UML akademik yang mempertahankan atribut data lengkap serta method riil:
1. **Header (Atas)**: Nama entitas/tabel domain (`users`, `memberships`, `pt_sessions`, `payments`, dll.).
2. **Kompartemen Atribut (Tengah)**: Kolom data riil dari skema basis data entitas lengkap beserta tipe datanya (mis. `+ id: int`, `+ name: String`, `+ price: double`, `+ status: String`).
3. **Kompartemen Method (Bawah)**: Fungsi/method operasional yang **HANYA diambil dari Controller dan Service riil** yang mengeksekusi operasi pada entitas tersebut:
   - `pt_sessions`: `createBooking()`, `reschedule()`, `cancel()`, `confirmSession()`, `rejectSession()`, `slots()`, `availability()`.
   - `payments`: `submitPaymentProof()`, `approvePayment()`, `rejectPayment()`, `generate()`, `invoice()`.
   - `memberships`: `checkout()`, `uploadProof()`, `processPayment()`, `paymentStatus()`.
   - `membership_packages`: `store()`, `update()`, `toggleStatus()`, `index()`.
   - `pt_packages`: `buy()`, `store()`, `update()`, `toggleStatus()`.
   - `users`: `register()`, `login()`, `logout()`, `handleGoogleCallback()`, `updateProfile()`, `deactivate()`, `export()`.
   - `check_ins`: `index()`, `store()`.
   - `member_profiles`: `showCard()`, `show()`, `update()`.

---

## 🛠️ 4. Cara Membuka & Mengedit Berkas

1. **Melalui Browser**:
   - Buka **[app.diagrams.net](https://app.diagrams.net)**.
   - Pilih menu **File** $\rightarrow$ **Open From** $\rightarrow$ **Device**.
   - Buka [`4_Activity_Diagram_FitCore.drawio`](file:///d:/Project%20Laravel/fitcore/docs/diagrams/4_Activity_Diagram_FitCore.drawio) atau [`FitCore_All_Diagrams.drawio`](file:///d:/Project%20Laravel/fitcore/docs/diagrams/FitCore_All_Diagrams.drawio).
   - Di bagian bawah kanvas, Anda akan melihat **tab per role** (`Activity Diagram - Member`, `Activity Diagram - Personal Trainer`, `Activity Diagram - Admin`). Klik tab untuk berpindah role dan melihat seluruh diagram alurnya.
2. **Melalui IDE (VS Code / Antigravity)**:
   - Pasang ekstensi **Draw.io Integration** (*Henning Dieterichs*).
   - Klik berkas `.drawio` di panel file explorer. Seluruh tab per role langsung dapat dibuka dan diedit visual.
