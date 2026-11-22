# 🎙️ Master Voice Over (VO) Script & Storyboard (Durasi 5 - 10 Menit)
## Proyek: FitCore - Platform Manajemen Gym & Ekosistem PT
*Estimasi Total Waktu: **~7 Menit 15 Detik (435 Detik)***  
*Target Format Video: Video Presentasi Akhir / Showcase PBL (Project Based Learning)*  
*Tone Presenter: Percaya diri, energik, profesional, artikulatif, dan bernada solutif.*

---

## ⏱️ Struktur & Pembagian Waktu (Timeline Breakdown)

Setiap bagian sekarang memiliki **script Playwright terpisah** yang dapat dijalankan sendiri-sendiri atau sekaligus:

| Modul | Bagian Demo | Perintah Script | Durasi | Resolusi | Folder Output |
|---|---|---|---|---|---|
| **Bagian 1** | **Landing Page & Publik** | `npm run demo:landing` | ~50s | Desktop & Mobile | `demo-recordings/1-landing/` |
| **Bagian 2** | **Ekosistem Member** | `npm run demo:member` | ~100s | Desktop & Mobile | `demo-recordings/2-member/` |
| **Bagian 3** | **Personal Trainer** | `npm run demo:trainer` | ~80s | Desktop & Mobile | `demo-recordings/3-trainer/` |
| **Bagian 4** | **Admin Backoffice** | `npm run demo:admin` | ~110s | Desktop (All Menus) | `demo-recordings/4-admin/` |
| **Master** | **Semua Sekaligus** | `npm run demo:all` | ~7 Menit | Lengkap | `demo-recordings/` |

---

## 🎬 Naskah Voice Over Lengkap per Detik

### 📌 MODUL 1: Pembukaan, Latar Belakang Masalah & Portal Publik (00:00 - 00:50)
*Visual: Landing Page Utama FitCore, Hero Banner, Katalog Paket & Profil Pelatih*

- **00:00 - 00:15**  
  **Visual Cue:** Browser membuka halaman beranda FitCore (`/`). Cursor scroll perlahan menampilkan Hero Section FitCore.  
  **Naskah VO:**  
  *"Halo semuanya! Selamat datang dalam presentasi dan demonstrasi sistem **FitCore** — platform manajemen gym dan pelatihan kebugaran terpadu yang dirancang untuk menjawab tantangan operasional gym modern saat ini."*

- **00:15 - 00:30**  
  **Visual Cue:** Scroll ke bawah melihat fitur unggulan, fasilitas 24 jam gym, dan kartu ringkasan program latihan.  
  **Naskah VO:**  
  *"Selama ini, banyak fasilitas kebugaran menghadapi kendala pelik: pencatatan kuota Personal Trainer yang masih manual, antrean check-in yang lambat di meja resepsionis, ketidakjelasan estimasi komisi bagi pelatih, hingga pengalaman digital member yang kaku. FitCore hadir menyatukan seluruh peran tersebut dalam satu kesatuan sistem yang mulus."*

- **00:30 - 00:50**  
  **Visual Cue:** Klik navigasi ke menu **Paket** (`/packages`), lalu lanjut ke menu **Trainer** (`/trainers`) menampilkan profil pelatih tersertifikasi seperti Coach Rama.  
  **Naskah VO:**  
  *"Melalui portal publik, calon member dapat dengan mudah mengeksplorasi ragam paket keanggotaan, transparansi harga, serta profil lengkap para personal trainer bersertifikasi internasional beserta keahlian spesialisasi mereka sebelum memutuskan bergabung."*

---

### 📌 MODUL 2: Pengalaman Member Desktop (00:50 - 02:30)
*Visual: Login Member, Dashboard Member, Kartu Digital, Sesi PT, Reservasi Jadwal & Riwayat Transaksi*

- **00:50 - 01:05**  
  **Visual Cue:** Membuka halaman login (`/login`), mengetik kredensial `member@gym.test` dan masuk ke sistem.  
  **Naskah VO:**  
  *"Sekarang, mari kita masuk ke modul pertama: **Pengalaman Member**. Kita login sebagai member terdaftar. Begitu berhasil masuk, kita langsung disambut oleh Member Dashboard yang bersih dan informatif."*

- **01:05 - 01:25**  
  **Visual Cue:** Dashboard Member terbuka. Cursor menyorot status paket aktif, statistik latihan, dan banner pemberitahuan. Scroll santai ke bawah dan kembali ke atas.  
  **Naskah VO:**  
  *"Di dashboard ini, member memperoleh gambaran menyeluruh tentang perjalanan kebugaran mereka: mulai dari masa aktif membership, kuota sesi PT yang tersedia, hingga ringkasan sesi latihan mendatang. Semua data tersaji real-time tanpa jeda sinkronisasi."*

- **01:25 - 01:45**  
  **Visual Cue:** Navigasi ke menu **Kartu Member Digital** (`/member/card`). Layar menampilkan kartu keanggotaan dengan Dynamic QR Code yang berkedip segar.  
  **Naskah VO:**  
  *"FitCore mengeliminasi kartu fisik plastik. Member memiliki **Kartu Digital** dengan kode QR dinamis. QR code ini dilengkapi time-to-live 30 detik untuk mencegah kecurangan pemindaian duplikat saat memasuki area gym."*

- **01:45 - 02:10**  
  **Visual Cue:** Buka menu **Sesi Latihan PT** (`/pt-sessions`). Indikator kuota 8 / 10 Sesi aktif tersorot. Klik tombol **+ Booking Sesi Baru** (`/pt-sessions/create`), memilih slot jadwal dan mengisi catatan latihan.  
  **Naskah VO:**  
  *"Pada halaman Sesi PT, member dapat melihat transparansi kuota latihan mereka. Ketika ingin berlatih, cukup klik 'Booking Sesi Baru'. Member bebas memilih tanggal, slot jam kerja pelatih yang tidak bertabrakan, serta mengisi catatan spesifik seperti target latihan otot hari itu. Reservasi langsung terkirim seketika ke pelatih yang bersangkutan."*

- **02:10 - 02:30**  
  **Visual Cue:** Navigasi ke **Riwayat Transaksi** (`/member/riwayat-transaksi`), menampilkan daftar pembayaran yang terverifikasi dan tombol unduh invoice. Kemudian logout.  
  **Naskah VO:**  
  *"Member juga dapat mengakses seluruh riwayat pembayaran paket membership dan sesi PT, lengkap dengan bukti transfer dan unduhan invoice digital resmi sebagai bukti transaksi yang sah. Sekarang kita logout dan berpindah ke peran pelatih."*

---

### 📌 MODUL 3: Pengalaman Personal Trainer (02:30 - 03:50)
*Visual: Login Coach Rama, Dashboard Trainer, Estimasi Komisi Sesi, Kalender Jadwal & Manajemen Klien*

- **02:30 - 02:45**  
  **Visual Cue:** Login sebagai `rama@gym.test` (Coach Rama Prasetya, CSCS).  
  **Naskah VO:**  
  *"Kini kita berada pada modul kedua: **Personal Trainer Ecosystem**. Pelatih adalah ujung tombak pelayanan gym, dan FitCore dirancang untuk memudahkan manajemen jadwal serta apresiasi finansial mereka."*

- **02:45 - 03:10**  
  **Visual Cue:** Dashboard Trainer terbuka. Cursor menyorot 3 kartu metrik: Sesi Hari Ini, Permintaan Baru, dan Total Estimasi Pendapatan Komisi. Scroll melihat detailnya.  
  **Naskah VO:**  
  *"Pada dashboard Coach Rama, pelatih dapat melihat shift kerjanya, jadwal sesi hari ini, dan yang paling revolusioner: **Transparansi Komisi**. Setiap sesi yang terlaksana langsung mengkalkulasi rate komisi pelatih secara otomatis, menghapus keraguan atau selisih hitung gaji di akhir bulan."*

- **03:10 - 03:30**  
  **Visual Cue:** Masuk ke menu **Jadwal Sesi PT** (`/trainer/jadwal-sesi`). Tampilan kalender FullCalendar interaktif dan daftar status reservasi (Menunggu, Terkonfirmasi, Selesai).  
  **Naskah VO:**  
  *"Melalui kalender interaktif, pelatih memiliki wewenang penuh untuk meninjau permintaan latihan yang masuk dari member. Pelatih dapat menerima atau menolak dengan alasan jelas, sehingga jadwal latihan selalu terkoordinasi rapi tanpa bentrok dengan kesibukan lain."*

- **03:30 - 03:50**  
  **Visual Cue:** Masuk ke menu **Klien Saya** (`/trainer/klien-saya`), melihat daftar member bimbingan aktif dan progres latihan mereka. Logout dari akun Trainer.  
  **Naskah VO:**  
  *"Menu 'Klien Saya' memungkinkan pelatih memantau rekam jejak setiap member bimbingannya, riwayat kehadiran, serta memvalidasi penyelesaian sesi latihan di lapangan. Kerja pelatih menjadi jauh lebih terstruktur dan profesional."*

---

### 📌 MODUL 4: Backoffice Admin - Navigasi Runtut Menu Sidebar (03:50 - 05:40)
*Visual: Login Admin di `/admin/login`, lalu menjelajah seluruh menu sidebar secara berurutan*

- **03:50 - 04:05**  
  **Visual Cue:** Login sebagai Admin Utama di portal khusus `/admin/login` (`admin@fitcore.test`).  
  **Naskah VO:**  
  *"Selanjutnya adalah modul ketiga: **Admin Backoffice**. Pusat kendali operasional dan finansial gym untuk staf manajemen dan pemilik bisnis. Mari kita lihat setiap menu pada sidebar navigasi secara runtut."*

- **04:05 - 04:20**  
  **Visual Cue:** **Menu 1: Dashboard Performa Gym**. Menampilkan 4 kartu utama (Total Member, Pendapatan, Sesi Aktif, Check-in Hari Ini) dan grafik Bar Chart pendapatan.  
  **Naskah VO:**  
  *"Di menu pertama, **Dashboard**, manajemen disajikan metrik kesehatan bisnis secara komprehensif: jumlah member aktif, arus kas pendapatan, performa sesi PT, dan volume kunjungan harian yang dapat difilter secara fleksibel."*

- **04:20 - 04:35**  
  **Visual Cue:** Klik menu sidebar **Paket & Harga** (`/admin/packages`). Scroll melihat tabel paket membership, harga, durasi, dan toggle status aktif.  
  **Naskah VO:**  
  *"Tepat di bawah Dashboard, kita klik menu **Paket & Harga**. Di sini admin dapat mengonfigurasi master paket keanggotaan gym — mengatur biaya langganan bulanan, triwulan, hingga tahunan, serta mengaktifkan atau menonaktifkan paket promo."*

- **04:35 - 04:50**  
  **Visual Cue:** Klik menu sidebar **Paket Sesi & Jadwal PT** (`/admin/pt-sessions`). Scroll melihat paket sesi latihan PT, tarif per sesi, dan masa aktif.  
  **Naskah VO:**  
  *"Menu berikutnya adalah **Paket Sesi & Jadwal PT**. Halaman ini mengelola paket bundling latihan privat bersama Personal Trainer, menentukan jumlah kuota sesi per paket, dan mengatur kebijakan masa berlaku paket latihan."*

- **04:50 - 05:05**  
  **Visual Cue:** Klik menu sidebar **Verifikasi Pembayaran** (`/admin/verifikasi-pembayaran`). Menampilkan antrean verifikasi bukti transfer pembayaran manual member.  
  **Naskah VO:**  
  *"Selanjutnya, menu **Verifikasi Pembayaran**. Staf keuangan dapat meninjau bukti transfer yang diunggah oleh member, memvalidasi nomor rekening dan nominal, lalu menyetujui transaksi dengan satu klik agar paket member langsung aktif otomatis."*

- **05:05 - 05:25**  
  **Visual Cue:** Klik menu sidebar **Scanner Check-in** (`/admin/checkin`). Tampilan kamera scanner barcode/QR meja resepsionis dan tabel live log kehadiran hari ini.  
  **Naskah VO:**  
  *"Menu kelima adalah **Scanner Check-in**. Fitur meja resepsionis ini menggunakan kamera scanner cerdas. Ketika member menunjukkan QR dari smartphone mereka, sistem memvalidasi keaktifan paket dalam hitungan milidetik dan mencatat log kehadiran secara instan."*

- **05:25 - 05:40**  
  **Visual Cue:** Klik menu sidebar **Manajemen Member** (`/admin/member-management`). Scroll melihat database seluruh anggota gym, filter status keanggotaan, dan detail profil.  
  **Naskah VO:**  
  *"Dan pada menu **Manajemen Member**, staf dapat memantau seluruh basis data anggota, melihat masa berlaku keanggotaan, riwayat check-in, hingga mengelola status keaktifan akun setiap member secara terpusat."*

---

### 📌 MODUL 5: Ekosistem Mobile App (Light Theme) (05:40 - 06:55)
*Visual: Tampilan Mobile iPhone 14 Pro, Mobile Login, Mobile Dashboard, Mobile QR Card, Mobile PT Booking & Mobile Trainer View*

- **05:40 - 06:00**  
  **Visual Cue:** Tampilan beralih ke mobile viewport responsif (iPhone 14 Pro). Login member di `/m/login` dan masuk ke Mobile Dashboard bernuansa *Light Theme*.  
  **Naskah VO:**  
  *"Dan inilah inovasi terbaru FitCore: **Integrasi Mobile Web App**. Didesain dengan filosofi *Light Theme* yang bersih, segar, dan hemat baterai, aplikasi mobile ini memberikan kemudahan portabel saat member sedang beraktivitas di dalam gym."*

- **06:00 - 06:20**  
  **Visual Cue:** Layar membuka **Kartu Member Digital Mobile** (`/m/card`). QR code dinamis tampil tajam dan siap di-scan pada gate masuk.  
  **Naskah VO:**  
  *"Cukup buka smartphone, sentuh tab kartu digital, dan QR code siap dipindai. Navigasi bawah atau bottom bar yang ergonomis membuat seluruh fitur utama dapat dijangkau dengan mudah menggunakan satu ibu jari."*

- **06:20 - 06:40**  
  **Visual Cue:** Buka menu **Sesi PT Mobile** (`/m/pt-sessions`), melihat kartu kuota, lalu membuka **Katalog Paket PT Mobile** (`/m/pt-packages`).  
  **Naskah VO:**  
  *"Member dapat mengecek sisa kuota sesi PT dan melakukan reservasi langsung dari saku celana mereka. Jika kuota latihan habis, member dapat langsung membeli paket sesi baru melalui alur checkout mobile yang ringkas dan intuitif."*

- **06:40 - 06:55**  
  **Visual Cue:** Membuka tampilan **Mobile Trainer Portal** (`/m/trainer/dashboard`). Pelatih memantau jadwal sesi saat berada di gym floor.  
  **Naskah VO:**  
  *"Bahkan bagi pelatih, portal mobile ini memungkinkan mereka memantau jadwal sesi dan menyetujui permintaan latihan tepat saat sedang mendampingi member di area gym tanpa harus membuka laptop."*

---

### 📌 MODUL 6: Kesimpulan & Penutup (06:55 - 07:15)
*Visual: Logo FitCore & Ringkasan Nilai Tambah Platform*

- **06:55 - 07:15**  
  **Visual Cue:** Halaman utama dengan logo FitCore.  
  **Naskah VO:**  
  *"Secara keseluruhan, FitCore bukan sekadar aplikasi pencatatan, melainkan solusi digital end-to-end yang meningkatkan efisiensi operasional manajemen, memberdayakan para pelatih, dan memberikan kenyamanan maksimal bagi para member kebugaran.  
  Terima kasih atas perhatian Anda, dan mari melangkah ke masa depan kebugaran bersama FitCore!"*

---

## 🛠️ Tips Rekaman & Eksekusi Praktis
1. **Jalankan Otomasi Playwright**:
   ```bash
   npm run demo:walkthrough
   ```
2. **Sinkronisasi**:
   Script Playwright sudah mengklik setiap menu sidebar admin satu per satu secara berurutan dan memberikan waktu scroll santai yang pas dengan naskah pembacaan.
3. **Hasil Video**:
   Tersimpan di:
   - `demo-recordings/desktop-extended/` (Modul 1 s/d Modul 4)
   - `demo-recordings/mobile-extended/` (Modul 5)
