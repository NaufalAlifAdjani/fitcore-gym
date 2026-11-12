PROPOSAL
PROJECT BASED LEARNING (PBL)
SISTEM INFORMASI MANGEMENT GYM
 




 A logo of a building

Description automatically generated 

 


Disusun Oleh:
Naufal Alif Adjani
|  | 244107060090 |
| --- | --- |
|  | Achmad Alfi Maksum |
|  | 244107060009 |
|  | Muhammad Rif’at Syamlan |
|  | 244107060150 |
|  | Siti Aisyah |
|  | 244107060038 |









Jurusan Teknologi Informasi 
D4 Sistem Informasi Bisnis
Politeknik Negeri Malang
2026
________________
DAFTAR ISI

# BAB I SAMPUL DAN IDENTITAS TIM        3


## 1.1 Judul Produk        3


## 1.2 Identitas Tim        3


## 1.3 Mata Kuliah dan Dosen Pengampu        3


# BAB II RINGKASAN EKSEKUTIF        4


# BAB III LATAR BELAKANG DAN RUMUSAN MASALAH        4


## 3.1 Latar Belakang        4


## 3.2 Rumusan Masalah        5


# BAB IV TUJUAN DAN RUANG LINGKUP PROYEK        5


## 4.1 Tujuan Proyek        5


## 4.2 Ruang Lingkup        5


## 4.3 Pemangku Kepentingan        6


## 4.4 Asumsi dan Batasan        6


### 4.4.1 Asumsi        6


### 4.4.2 Batasan        6


## 4.5 Kriteria Keberhasilan        6


# BAB V DESKRIPSI PRODUK YANG DIUSULKAN        7


## 5.1 Gambaran Umum Solusi        7


## 5.2 Use Case Diagram        8


## 5.3 Pengguna dan Skenario Penggunaan        9


### 5.3.1 Pengguna        9


### 5.3.2 Skenario Pengguna        9


## 5.4 Daftar Fitur Utama        29


### 5.4.1 Modul Member        29


### 5.4.2 Modul Personal Trainer        30


### 5.4.3 Modul Admin/Owner        31


## 5.5 Prioritas Fitur        31


# BAB VI RANCANGAN TEKNIS AWAL        32


## 6.1 Arsitektur Sistem        32


## 6.2 Rancangan Basis Data        33


## 6.3 Teknologi yang Digunakan        34


# BAB VII RENCANA PENGUJIAN AWAL        36


## 7.1 Pengujian Fungsional (Black-box)  Fitur Must Have        36


## 7.2 Pengujian Fungsional (Black-box) Fitur Should Have        38


## 7.3 Pengujian Non-Fungsional Usability dan Keamanan Dasar        39


## 7.4 Pengujian Struktural (White-box)        39


# BAB VIII RENCANA KERJA DAN ORGANISASI TIM        41


## 8.1 Jadwal dan Milestone        41


## 8.2 Pembagian Peran dan Tanggung Jawab        43


# BAB IX LAMPIRAN        45


# BAB I SAMPUL DAN IDENTITAS TIM


## 1.1 Judul Produk

FitCore: Sistem Informasi Manajemen dan Reservasi Tempat Fitness/Gym Berbasis Web.

## 1.2 Identitas Tim

Nama
|  | NIM |
| --- | --- |
|  | Peran |
|  | Naufal Alif Adjani |
|  | 244107060090 |
|  | Full-stack Developer |
|  | Achmad Alfi Maksum |
|  | 244107060009 |
|  | Full-stack Developer |
|  | Muhammad Rif’at Syamlan |
|  | 244107060150 |
|  | Full-stack Developer |
|  | Siti Aisyah |
|  | 244107060038 |
|  | Full-stack Developer |
|  | 1.3 Mata Kuliah dan Dosen Pengampu |

Mata Kuliah
|  | Dosen Pengampu |
| --- | --- |
|  | Kelas |
|  | Pemrograman Web Lanjut |
|  | Dian Hanifudin Subhi, S.Kom., M.Kom. |
|  | SIB - 3A |
|  | Penjaminan Mutu Perangkat Lunak |
|  | Dr. Rakhmat Arianto, S.ST., M.Kom. |
|  | SIB - 3A |
|  | Manajemen Proyek Sistem Informasi |
|  | Pramana Yoga Saputra, S.Kom., M.MT. |
|  | SIB - 3A |







# BAB II RINGKASAN EKSEKUTIF

Pengelolaan operasional pada tempat kebugaran (gym) berskala kecil hingga menengah saat ini masih banyak yang bergantung pada proses manual. Pencatatan keanggotaan menggunakan buku atau spreadsheet, penagihan tanpa notifikasi, serta pendataan kehadiran yang tidak terstruktur menjadi kendala utama. Hal ini mengakibatkan pemilik gym kesulitan dalam memantau performa operasional secara real-time, dan di sisi lain, member kerap melewatkan masa perpanjangan paket mereka karena tidak ada pengingat.
Untuk mengatasi masalah tersebut, FitCore hadir sebagai solusi sistem informasi berbasis web yang dikembangkan menggunakan framework Laravel. FitCore dirancang untuk mengintegrasikan seluruh alur operasional gym yang melibatkan tiga pengguna utama: Member (mendaftar paket, check-in via pemindai QR code, reservasi personal trainer, dan pembayaran online), Personal Trainer (mengelola jadwal sesi latihan harian), serta Admin/Owner (mengelola master data, stok produk, dan memantau status operasional harian). Produk ini ditargetkan rampung dalam masa pengembangan 16 minggu, menghasilkan sebuah sistem end-to-end yang fungsional, responsif, dan teruji secara komprehensif.

# BAB III LATAR BELAKANG DAN RUMUSAN MASALAH


## 3.1 Latar Belakang

Tren gaya hidup sehat di masyarakat urban mendorong pertumbuhan industri kebugaran di Indonesia secara masif. Sayangnya, kemunculan gym berskala kecil hingga menengah ini tidak selalu diimbangi dengan adopsi sistem manajemen yang modern. Sebagian besar masih menggunakan metode manual atau pembukuan sederhana untuk mencatat data member, absensi kehadiran, hingga riwayat operasional harian.
Metode konvensional ini menciptakan berbagai inefisiensi. Pemilik usaha sering kali kesulitan mengidentifikasi secara pasti member yang masih aktif dan yang sudah kedaluwarsa. Penagihan kerap terlambat, dan manajemen fasilitas menjadi kurang optimal. Berdasarkan berbagai riset operasional bisnis, digitalisasi manajemen gym menggunakan sistem informasi berbasis web dan teknologi presensi QR Code terbukti efektif meningkatkan kelancaran operasional serta meminimalisir keterlambatan pembayaran member. Oleh karena itu, pembangunan aplikasi FitCore menjadi solusi yang sangat relevan dan memiliki nilai guna tinggi.

## 3.2 Rumusan Masalah

Berdasarkan latar belakang tersebut, terdapat permasalahan operasional yang dapat dirumuskan dari dua sisi pengguna:
1. Sisi Member: Kesulitan memantau sisa masa aktif keanggotaan, proses reservasi Personal Trainer (PT) yang sering tidak pasti karena jadwal tidak diperbarui secara real-time, serta tidak adanya akses ke riwayat kehadiran dan transaksi pribadi mereka.
2. Sisi Pemilik/Admin: Pendataan status member secara manual berisiko tinggi memicu hilangnya potensi perpanjangan keanggotaan. Selain itu, rekapitulasi kehadiran yang tidak rapi menghambat analisis jam sibuk gym, dan pengelolaan inventaris produk ritel gym (seperti minuman/suplemen) sangat rentan terjadi selisih stok.

# BAB IV TUJUAN DAN RUANG LINGKUP PROYEK


## 4.1 Tujuan Proyek

Membangun dan mengimplementasikan sistem informasi manajemen dan reservasi gym terpadu berbasis web (FitCore) yang mendigitalisasi alur pendaftaran, pembayaran online, absensi QR Code, dan pengelolaan operasional. Sistem ini ditargetkan siap beroperasi secara lancar dan fungsional pada akhir minggu ke-16 masa pengembangan proyek. 

## 4.2 Ruang Lingkup

1. Digitalisasi manajemen keanggotaan (paket harian/bulanan/tahunan) dengan mekanisme auto-update status aktif/kedaluwarsa.
2. Sistem check-in kehadiran mandiri untuk member menggunakan teknologi QR code.
3. Fitur kalender dan reservasi sesi Personal Trainer secara real-time.
4. Pembayaran transaksi secara manual melalui transfer bank, dengan mekanisme unggah bukti transfer oleh Member dan verifikasi oleh Admin. 
5. Manajemen hak akses multitingkat (Member, personal trainer, Admin/Owner).
6. Dasbor khusus personal trainer untuk pemantauan jadwal sesi harian dan riwayat member yang dilatih.
7. Dasbor Admin untuk pengelolaan master data, pengaturan stok produk retail, pemantauan riwayat transaksi, dan validasi pembayaran.

## 4.3 Pemangku Kepentingan

Pemangku Kepentingan
|  | Kepentingan |
| --- | --- |
|  | Member |
|  | Mengelola status keanggotaan, melakukan check-in tanpa antre, dan memesan sesi latihan secara pasti |
|  | Personal Trainer |
|  | Mengetahui jadwal sesi kerja dan daftar member yang harus dilayani setiap hari |
|  | Admin/Owner Gym |
|  | Memantau operasional, transaksi, stok produk, dan pendapatan usaha secara terpusat |
|  | Dosen Pengampu |
|  | Memastikan sistem web yang dibangun benar-benar terintegrasi dan teruji |
|  | Tim Pengembang |
|  | Menyelesaikan proyek tepat waktu sesuai kriteria keberhasilan |



## 4.4 Asumsi dan Batasan


### 4.4.1 Asumsi

* Gym mitra bersedia menyediakan rancangan harga paket dan data jadwal personal trainer awal.
* Jadwal ketersediaan personal trainer relatif terstruktur untuk di-booking.
* Mayoritas member memiliki smartphone untuk menggunakan fitur web dan QR code.



### 4.4.2 Batasan

* Waktu pengembangan dibatasi maksimal 16 minggu.
* Sistem difokuskan untuk satu cabang/outlet gym (single-tenant).
* Platform berbasis Responsive Web App, tidak dibangun dalam bentuk Mobile Native App (Android/iOS).
* Verifikasi pembayaran dilakukan secara manual oleh Admin berdasarkan bukti transfer yang diunggah Member.

## 4.5 Kriteria Keberhasilan

1. Aplikasi dapat diakses secara publik dengan performa yang stabil.
2. User journey sentral (Registrasi akun → Pilih paket → Bayar → Member aktif) dapat berjalan sempurna tanpa kegagalan sistem.
3. Fungsionalitas scanner QR Code berhasil memvalidasi status member secara real-time.
4. Algoritma reservasi sukses mencegah terjadinya duplikasi atau bentrok pemesanan jadwal pada satu personal trainer di jam yang sama.
5. Modul dasbor sukses menampilkan rekapitulasi data member aktif, jadwal sesi personal trainer, dan status operasional secara akurat.

# BAB V DESKRIPSI PRODUK YANG DIUSULKAN


## 5.1 Gambaran Umum Solusi

FitCore beroperasi sebagai pusat komando operasional sebuah gym yang terintegrasi. Calon member mengakses situs FitCore untuk melakukan registrasi dan memilih jenis membership. Setelah pembayaran terverifikasi, akun member akan langsung berstatus aktif. Setiap tiba di fasilitas gym, member melakukan check-in mandiri dengan cara membuka fitur kamera pemindai (scanner) di dalam aplikasi dan memindai QR Code yang telah disediakan oleh pihak gym di meja resepsionis, sistem kemudian akan mencatat waktu kehadiran dan memvalidasi sisa masa aktif mereka dalam hitungan detik. 
Selain itu, sistem menjembatani interaksi antara member dan personal trainer secara langsung. Member cukup mengisi preferensi hari, jam, dan personal trainer pilihan untuk sesi Personal Trainer, dan sistem akan menjadwalkan seluruh sesi secara otomatis sesuai ketersediaan personal trainer (auto-plotting). Di saat yang bersamaan, personal trainer dibekali dasbor khusus untuk melihat susunan agenda kerjanya hari ini.  Pada tingkat manajerial, Owner/Admin dibekali visibilitas menyeluruh melalui dasbor terpusat untuk memantau sirkulasi stok produk, riwayat aktivitas pembayaran, serta grafik pertumbuhan jumlah member aktif secara faktual.
________________

## 5.2 Use Case Diagram

  
     

## 5.3 Pengguna dan Skenario Penggunaan


### 5.3.1 Pengguna

FitCore dirancang untuk digunakan oleh tiga jenis pengguna utama dalam satu outlet gym, yaitu Member, Personal Trainer, dan Admin/Owner. Setiap pengguna memiliki peran dan tanggung jawab yang berbeda sesuai dengan kebutuhan dalam penggunaan dan pengelolaan sistem. 
1. Member:
Merupakan pelanggan gym yang menggunakan FitCore untuk mengelola keanggotaan, melakukan pembayaran, melakukan check-in menggunakan QR code, serta menetapkan preferensi sesi personal trainer. Member juga dapat melihat riwayat aktivitas dan transaksi, menerima notifikasi melalui email, serta memberikan rating terhadap personal trainer yang telah diikuti.  
2. Personal Trainer:
Merupakan instruktur gym yang bertanggung jawab dalam melayani sesi personal trainer. personal trainer menggunakan FitCore untuk melihat dan mengelola jadwal sesi, mengetahui member yang terdaftar pada setiap sesi, memperbarui status sesi, serta memantau riwayat dan performa pribadi.  
3. Admin/Owner:
Merupakan pengelola atau pemilik outlet gym yang bertanggung jawab terhadap pengelolaan operasional gym melalui sistem. Admin/Owner mengelola data member, paket dan harga, ketersediaan personal trainer, jadwal sesi, pembayaran, akun personal trainer, serta memantau performa gym secara keseluruhan.

### 5.3.2 Skenario Pengguna

1. Login
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Login |
|  | Tujuan |
|  | Masuk ke dalam sistem |
|  | Deskripsi |
|  | Proses login ini sebagai awal bagi user untuk dapat menjalankan sistem |
|  | Aktor |
|  | User (Member, Personal Trainer, Admin) |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Form Login ditampilkan |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Mengisi Form Login |



|  | 2) Memeriksa Field Kosong Pada Form Login |
| --- | --- |


|  | 3) Mencocokan Data Login dengan Database |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan username atau password salah |
| --- | --- |


|  | 2) Menampilkan Form Login |
| --- | --- |
|  | 3) Mengisi Kembali Form Login |



|  | 4) Mencocokkan data login dengan data user pada basis data |
| --- | --- |


|  | 5) Bila cocok sistem menampilkan halaman utama untuk user |
| --- | --- |
|  | Kondisi Akhir |
|  | User dapat melakukan kegiatan pada sistem sesuai kewenangannya. |


2. Registrasi Akun (Member)
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Registrasi Akun (Member) |
|  | Tujuan |
|  | Membuat akun baru sebagai member |
|  | Deskripsi |
|  | Proses registrasi digunakan oleh calon member untuk mendaftarkan diri agar dapat menggunakan sistem FitCore. |
|  | Aktor |
|  | Member (calon member) |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Form Registrasi ditampilkan |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Mengisi form registrasi (nama, email, no. HP, password) |



|  | 2) Memeriksa kelengkapan data pada form registrasi |
| --- | --- |


|  | 3) Memeriksa apakah email sudah terdaftar pada database |
| --- | --- |


|  | 4) Menyimpan data akun baru ke database |
| --- | --- |


|  | 5) Mengirim email konfirmasi pendaftaran ke Member |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Email sudah terdaftar" atau "Data belum lengkap" |
| --- | --- |


|  | 2) Menampilkan kembali form registrasi |
| --- | --- |
|  | 3) Mengisi ulang form registrasi |



|  | 4) Memeriksa kembali data yang diinput |
| --- | --- |
|  | Kondisi Akhir |
|  | Akun member berhasil dibuat dan Member dapat login ke sistem. |


3. Memilih Paket Keanggotaan
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Memilih Paket Keanggotaan |
|  | Tujuan |
|  | Memilih jenis paket membership yang akan dibeli |
|  | Deskripsi |
|  | Member memilih paket keanggotaan (harian/bulanan/tahunan) sesuai kebutuhan sebelum melakukan pembayaran. |
|  | Aktor |
|  | Member |
|  | Usecase Yang Berkaitan |
|  | Melakukan Pembayaran (Upload Bukti Transfer) <<include>> |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Member telah login dan halaman daftar paket ditampilkan |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Melihat daftar paket keanggotaan beserta harga |



|  | 2) Menampilkan detail paket (durasi, harga, fasilitas) |
| --- | --- |
|  | 3) Memilih salah satu paket keanggotaan |



|  | 4) Menyimpan pilihan paket dan mengarahkan ke halaman pembayaran |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Silakan pilih paket terlebih dahulu" jika belum ada paket dipilih |
| --- | --- |


|  | 2) Menampilkan kembali daftar paket keanggotaan |
| --- | --- |
|  | Kondisi Akhir |
|  | Paket keanggotaan yang dipilih tersimpan dan Member melanjutkan ke proses pembayaran. |


4. Melakukan Pembayaran (Upload Bukti Transfer)
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Melakukan Pembayaran (Upload Bukti Transfer) |
|  | Tujuan |
|  | Menyelesaikan pembayaran paket keanggotaan atau paket sesi PT tambahan |
|  | Deskripsi |
|  | Member melakukan pembayaran secara manual melalui transfer dan mengunggah bukti transfer sebagai bukti pembayaran untuk diverifikasi oleh Admin. |
|  | Aktor |
|  | Member |
|  | Usecase Yang Berkaitan |
|  | Memilih Paket Keanggotaan, Membeli Paket Sesi PT Tambahan, Verifikasi Pembayaran |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Halaman pembayaran ditampilkan beserta rincian tagihan |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Melakukan transfer sesuai nominal tagihan |


|  | 2) Mengunggah foto/scan bukti transfer |
| --- | --- |



|  | 3) Memeriksa kelengkapan dan format file bukti transfer yang diunggah |
| --- | --- |


|  | 4) Menyimpan data pembayaran dengan status "Menunggu Verifikasi" |
| --- | --- |


|  | 5) Mengirim notifikasi ke Admin bahwa terdapat pembayaran baru |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Format file tidak didukung / ukuran file terlalu besar" |
| --- | --- |


|  | 2) Menampilkan kembali form upload bukti transfer |
| --- | --- |
|  | 3) Mengunggah ulang bukti transfer |


|  | Kondisi Akhir |
| --- | --- |
|  | Bukti transfer tersimpan dengan status menunggu verifikasi Admin. |


5. Check-in via QR Code
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Check-in via QR Code |
|  | Tujuan |
|  | Mencatat kehadiran Member secara otomatis |
|  | Deskripsi |
|  | Member memindai QR code yang disediakan pihak gym untuk mencatat kehadiran secara otomatis saat datang berlatih. |
|  | Aktor |
|  | Member |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Member berada di lokasi gym dan QR code check-in ditampilkan pada area gym |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Membuka fitur scan QR pada aplikasi |


|  | 2) Memindai QR code yang tersedia |
| --- | --- |



|  | 3) Memvalidasi status keanggotaan Member (aktif/tidak aktif) |
| --- | --- |


|  | 4) Mencatat waktu dan tanggal kehadiran Member |
| --- | --- |


|  | 5) Menampilkan notifikasi "Check-in berhasil" |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Keanggotaan Anda telah berakhir, silakan perpanjang paket" jika status tidak aktif |
| --- | --- |


|  | 2) Mengarahkan Member ke halaman pemilihan paket keanggotaan |
| --- | --- |
|  | Kondisi Akhir |
|  | Data kehadiran Member tercatat pada sistem. |


6. Melihat Jadwal personal trainer
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Melihat Jadwal personal trainer |
|  | Tujuan |
|  | Melihat gambaran umum ketersediaan personal trainer sebagai referensi sebelum menetapkan preferensi sesi PT |
|  | Deskripsi |
|  | Member dapat melihat jam kerja/ketersediaan tiap personal trainer sebagai bahan pertimbangan sebelum mengisi preferensi hari, jam, dan personal trainer pada fitur penetapan preferensi sesi PT. |
|  | Aktor |
|  | Member |
|  | Usecase Yang Berkaitan |
|  | Menetapkan Preferensi Sesi Personal Trainer |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Member telah login dan memilih menu Jadwal personal trainer |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Membuka menu Jadwal personal trainer |



|  | 2) Menampilkan daftar personal trainer beserta hari dan jam kerja masing-masing |
| --- | --- |
|  | 3) Memilih filter hari/jam/personal trainer tertentu (opsional) |



|  | 4) Menampilkan jadwal sesuai filter yang dipilih |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Tidak ada jadwal tersedia untuk filter ini" |
| --- | --- |
|  | Kondisi Akhir |
|  | Member memperoleh informasi untuk membantu pengisian preferensi sesi PT. |


7. Menetapkan Preferensi Sesi Personal Trainer         
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Menetapkan Preferensi Sesi Personal Trainer |
|  | Tujuan |
|  | Menjadwalkan seluruh sesi PT yang menjadi hak Member secara otomatis sesuai preferensi |
|  | Deskripsi |
|  | Member mengisi preferensi hari, rentang jam, dan personal trainer pilihan untuk seluruh jatah sesi PT miliknya (baik dari bundling paket membership maupun dari pembelian paket PT tambahan). Sistem secara otomatis mencocokkan preferensi dengan ketersediaan personal trainer dan menjadwalkan seluruh sesi sekaligus (auto-plotting). |
|  | Aktor |
|  | Member |
|  | Usecase Yang Berkaitan |
|  | Melihat Jadwal personal trainer |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Membership Member aktif dan memiliki jatah sesi PT (pt_session_packages) yang belum sepenuhnya terjadwal |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih hari (weekday/weekend atau hari tertentu) |


|  | 2) Memilih rentang jam yang diinginkan |
| --- | --- |


|  | 3) Memilih personal trainer dari daftar personal trainer yang tersedia |
| --- | --- |



|  | 4) Mencocokkan preferensi dengan ketersediaan personal trainer dan slot yang belum terisi |
| --- | --- |


|  | 5) Membuat jadwal seluruh sesi PT sekaligus dengan status "Terjadwal" |
| --- | --- |


|  | 6) Mengirim notifikasi email berisi rincian jadwal lengkap ke Member dan personal trainer terkait |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Sebagian sesi belum dapat dijadwalkan karena slot tidak tersedia" jika slot cocok kurang dari total sesi |
| --- | --- |


|  | 2) Menampilkan sisa sesi yang belum terjadwal, mengarahkan Member menyesuaikan preferensi |
| --- | --- |
|  | 3) Menyesuaikan preferensi untuk sesi tersisa |



|  | 4) Mengulangi pencocokan untuk sisa sesi |
| --- | --- |
|  | Kondisi Akhir |
|  | Seluruh (atau sebagian) sesi PT Member terjadwal otomatis sesuai preferensi dan ketersediaan personal trainer. |


8. Membeli Paket Sesi Personal Trainer 
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Membeli Paket Sesi Personal Trainer |
|  | Tujuan |
|  | Membeli jatah sesi PT secara terpisah dari bundling paket membership baik bagi Member yang paketnya tidak mencakup PT sama sekali maupun yang ingin menambah sesi di luar jatah bundling-nya. |
|  | Deskripsi |
|  | Member dengan membership aktif, baik yang paketnya belum memiliki jatah PT maupun yang sudah punya dan ingin menambah dapat membeli paket sesi PT (Per-Visit, 8x, 12x, 16x, atau 20x sesi/bulan), sepanjang sisa masa aktif membership-nya memenuhi syarat minimum hari yang ditentukan untuk paket tersebut. |
|  | Aktor |
|  | Member |
|  | Usecase Yang Berkaitan |
|  | Melakukan Pembayaran (Upload Bukti Transfer) |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Membership Member berstatus aktif |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Membuka menu "Sewa PT" |



|  | 2) Menghitung sisa hari membership Member (end_date − hari ini) |
| --- | --- |


|  | 3) Menampilkan katalog paket PT tambahan, hanya opsi yang syarat minimum harinya terpenuhi |
| --- | --- |
|  | 4) Memilih salah satu paket PT |



|  | 5) Mengarahkan Member ke halaman pembayaran (lihat Melakukan Pembayaran) |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Sisa masa membership tidak mencukupi untuk paket PT manapun" jika tidak ada paket yang syaratnya terpenuhi |
| --- | --- |


|  | 2) Mengarahkan Member ke halaman perpanjangan membership |
| --- | --- |
|  | Kondisi Akhir |
|  | Member memperoleh paket PT tambahan yang eligible, siap melanjutkan ke pembayaran. |


9. Manajemen Paket Sesi Personal Trainer 
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Manajemen Paket Sesi Personal Trainer |
|  | Tujuan |
|  | Mengatur jenis, harga, dan syarat paket sesi PT |
|  | Deskripsi |
|  | Admin dapat menambah, mengubah, atau menonaktifkan jenis paket sesi PT (baik untuk pembelian pertama kali maupun tambahan) beserta jumlah sesi, harga, syarat minimum sisa hari membership, dan masa berlaku sesi. |
|  | Aktor |
|  | Admin |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Admin membuka menu Manajemen Paket Sesi PT |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih tambah/ubah/nonaktifkan paket PT |


|  | 2) Mengisi data paket (nama, jumlah sesi, harga, syarat minimum hari, masa berlaku) |
| --- | --- |



|  | 3) Memeriksa kelengkapan dan validitas data (tidak boleh kosong/negatif) |
| --- | --- |


|  | 4) Menyimpan data paket PT tambahan ke database |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Data paket belum lengkap atau tidak valid" |
| --- | --- |


|  | 2) Menampilkan kembali form paket PT |
| --- | --- |
|  | Kondisi Akhir |
|  | Data paket PT tambahan tersimpan dan tersedia untuk dipilih Member yang eligible. |


10. Melihat Riwayat Pribadi
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Melihat Riwayat Pribadi |
|  | Tujuan |
|  | Melihat riwayat aktivitas Member |
|  | Deskripsi |
|  | Member dapat melihat riwayat kehadiran, riwayat booking sesi, dan riwayat transaksi miliknya sendiri. |
|  | Aktor |
|  | Member |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Member telah login dan membuka menu Riwayat Pribadi |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih kategori riwayat (kehadiran/booking sesi/transaksi) |



|  | 2) Mengambil data riwayat sesuai kategori dari database |
| --- | --- |


|  | 3) Menampilkan daftar riwayat secara kronologis |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Belum ada riwayat" jika data tidak ditemukan |
| --- | --- |
|  | Kondisi Akhir |
|  | Member memperoleh informasi riwayat aktivitasnya secara lengkap. |


11. Memberi Rating personal trainer
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Memberi Rating personal trainer |
|  | Tujuan |
|  | Memberikan penilaian terhadap personal trainer yang telah diikuti |
|  | Deskripsi |
|  | Member memberikan rating dan ulasan terhadap personal trainer setelah sesi selesai dilaksanakan. |
|  | Aktor |
|  | Member |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Sesi yang diikuti Member berstatus "Selesai" |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Membuka menu rating pada riwayat sesi yang telah selesai |


|  | 2) Memberikan nilai (bintang) dan ulasan (opsional) |
| --- | --- |



|  | 3) Memeriksa kelengkapan data rating yang diisi |
| --- | --- |


|  | 4) Menyimpan data rating ke database |
| --- | --- |


|  | 5) Menampilkan notifikasi "Terima kasih atas penilaian Anda" |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Silakan berikan nilai rating" jika rating belum diisi |
| --- | --- |


|  | 2) Menampilkan kembali form rating |
| --- | --- |
|  | Kondisi Akhir |
|  | Rating tersimpan dan dapat digunakan sebagai bahan evaluasi personal trainer. |


12. Melihat Dashboard Jadwal Sesi
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Melihat Dashboard Jadwal Sesi |
|  | Tujuan |
|  | Melihat jadwal sesi harian/mingguan milik personal trainer |
|  | Deskripsi |
|  | personal trainer memantau jadwal sesi yang telah dijadwalkan melalui dashboard jadwal sesi. |
|  | Aktor |
|  | Personal Trainer |
|  | Usecase Yang Berkaitan |
|  | Mengedit Jadwal Member |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | personal trainer telah login ke sistem |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Membuka menu Dashboard Jadwal Sesi |



|  | 2) Mengambil data jadwal sesi milik personal trainer dari database |
| --- | --- |


|  | 3) Menampilkan jadwal dalam tampilan harian/mingguan |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Tidak ada sesi pada periode ini" jika jadwal kosong |
| --- | --- |
|  | Kondisi Akhir |
|  | personal trainer memperoleh informasi jadwal sesi yang harus dijalani. |


13. Mengedit Jadwal Member
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Mengedit Jadwal Member |
|  | Tujuan |
|  | Mengubah atau membuat jadwal sesi Member yang sudah terdaftar |
|  | Deskripsi |
|  | personal trainer dapat membuat atau mengubah jadwal sesi Member sesuai kebutuhan melalui dashboard jadwal sesi. |
|  | Aktor |
|  | Personal Trainer |
|  | Usecase Yang Berkaitan |
|  | Melihat Dashboard Jadwal Sesi |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Dashboard Jadwal Sesi ditampilkan |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih jadwal sesi Member yang akan diubah |


|  | 2) Mengubah hari/jam sesi |
| --- | --- |



|  | 3) Memeriksa bentrok jadwal dengan sesi lain milik personal trainer |
| --- | --- |


|  | 4) Menyimpan perubahan jadwal ke database |
| --- | --- |


|  | 5) Mengirim notifikasi perubahan jadwal ke Member terkait |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Jadwal bentrok dengan sesi lain" jika jadwal baru bertabrakan |
| --- | --- |


|  | 2) Menampilkan kembali form edit jadwal |
| --- | --- |
|  | Kondisi Akhir |
|  | Jadwal sesi Member berhasil diperbarui dan Member menerima notifikasi perubahan. |


14. Melihat Riwayat Member per Sesi
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Melihat Riwayat Member per Sesi |
|  | Tujuan |
|  | Melihat daftar dan riwayat Member pada setiap sesi |
|  | Deskripsi |
|  | personal trainer dapat melihat daftar Member yang terdaftar pada tiap sesi beserta riwayat kehadirannya. |
|  | Aktor |
|  | Personal Trainer |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | personal trainer membuka detail salah satu sesi pada dashboard |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih sesi tertentu pada dashboard |



|  | 2) Menampilkan daftar Member yang terdaftar pada sesi tersebut |
| --- | --- |
|  | 3) Memilih salah satu Member untuk melihat riwayatnya |



|  | 4) Menampilkan riwayat kehadiran dan progres Member tersebut |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Belum ada Member pada sesi ini" jika daftar kosong |
| --- | --- |
|  | Kondisi Akhir |
|  | personal trainer memperoleh informasi Member dan riwayatnya pada setiap sesi. |


15. Memantau Performa Pribadi
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Memantau Performa Pribadi |
|  | Tujuan |
|  | Memantau performa kerja personal trainer |
|  | Deskripsi |
|  | personal trainer dapat memantau performanya sendiri, seperti jumlah sesi yang sudah dijalani dan jumlah Member yang ditangani. |
|  | Aktor |
|  | Personal Trainer |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | personal trainer membuka menu Performa Pribadi |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih periode performa yang ingin dilihat (mingguan/bulanan) |



|  | 2) Menghitung jumlah sesi yang telah dijalani pada periode tersebut |
| --- | --- |


|  | 3) Menghitung jumlah Member yang ditangani beserta rata-rata rating |
| --- | --- |


|  | 4) Menampilkan ringkasan performa dalam bentuk grafik/angka |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Belum ada data performa" jika data tidak ditemukan |
| --- | --- |
|  | Kondisi Akhir |
|  | personal trainer memperoleh gambaran performa kerjanya pada periode yang dipilih. |


16. Manajemen Data Member
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Manajemen Data Member |
|  | Tujuan |
|  | Mengelola data dan status keanggotaan Member |
|  | Deskripsi |
|  | Admin dapat melihat, menambah, mengubah, atau menonaktifkan data Member serta memantau status keanggotaan yang ter-update otomatis. |
|  | Aktor |
|  | Admin |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Admin membuka menu Manajemen Data Member |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Melihat daftar Member beserta status keanggotaan (aktif/expired) |



|  | 2) Menampilkan daftar Member dan memperbarui status keanggotaan secara otomatis berdasarkan masa aktif |
| --- | --- |
|  | 3) Memilih data Member untuk diubah/dihapus |



|  | 4) Menyimpan perubahan data Member ke database |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan kesalahan jika data yang diinput tidak valid |
| --- | --- |


|  | 2) Menampilkan kembali form data Member |
| --- | --- |
|  | Kondisi Akhir |
|  | Data Member tersimpan dan status keanggotaan sesuai kondisi terbaru. |


17. Manajemen Paket & Harga
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Manajemen Paket & Harga |
|  | Tujuan |
|  | Mengatur paket keanggotaan, layanan personal trainer, dan harga |
|  | Deskripsi |
|  | Admin dapat menambah, mengubah, atau menghapus paket keanggotaan beserta harganya. |
|  | Aktor |
|  | Admin |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Admin membuka menu Manajemen Paket & Harga |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih tambah/ubah/hapus paket |


|  | 2) Mengisi data paket (nama paket, durasi, harga, fasilitas) |
| --- | --- |



|  | 3) Memeriksa kelengkapan data paket yang diisi |
| --- | --- |


|  | 4) Menyimpan data paket ke database |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Data paket belum lengkap" |
| --- | --- |


|  | 2) Menampilkan kembali form paket |
| --- | --- |
|  | Kondisi Akhir |
|  | Data paket dan harga berhasil diperbarui dan dapat dipilih oleh Member. |


18. Manajemen Ketersediaan personal trainer
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Manajemen Ketersediaan personal trainer |
|  | Tujuan |
|  | Menentukan jam kerja/ketersediaan tiap personal trainer |
|  | Deskripsi |
|  | Admin menentukan jam kerja/ketersediaan tiap personal trainer yang menjadi dasar slot sesi personal training yang bisa dibuka personal trainer untuk booking oleh Member. |
|  | Aktor |
|  | Admin |
|  | Usecase Yang Berkaitan |
|  | Manajemen Jadwal Sesi |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Admin membuka menu Manajemen Ketersediaan personal trainer |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih personal trainer yang akan diatur ketersediaannya |


|  | 2) Mengisi hari dan jam kerja personal trainer |
| --- | --- |



|  | 3) Memeriksa bentrok dengan jadwal ketersediaan yang sudah ada |
| --- | --- |


|  | 4) Menyimpan data ketersediaan personal trainer ke database |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Jadwal bentrok dengan ketersediaan yang sudah ada" |
| --- | --- |


|  | 2) Menampilkan kembali form ketersediaan personal trainer |
| --- | --- |
|  | Kondisi Akhir |
|  | Ketersediaan personal trainer tersimpan dan menjadi dasar slot booking sesi personal trainer. |


19. Manajemen Jadwal sesi
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Manajemen Jadwal sesi |
|  | Tujuan |
|  | Mengatur jadwal slot sesi personal trainer |
|  | Deskripsi |
|  | Admin mengatur jadwal slot sesi personal trainer yang tersedia bagi Member. |
|  | Aktor |
|  | Admin |
|  | Usecase Yang Berkaitan |
|  | Manajemen Ketersediaan personal trainer |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Admin membuka menu Manajemen Jadwal Sesi |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih tambah/ubah/hapus jadwal atau sesi |


|  | 2) Mengisi data jadwal (personal trainer, hari, jam, kapasitas) |
| --- | --- |



|  | 3) Memeriksa kelengkapan data dan bentrok jadwal |
| --- | --- |


|  | 4) Menyimpan jadwal ke database |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan kesalahan jika jadwal bentrok/data tidak lengkap |
| --- | --- |


|  | 2) Menampilkan kembali form jadwal |
| --- | --- |
|  | Kondisi Akhir |
|  | Jadwal sesi berhasil diperbarui dan dapat dilihat oleh Member/personal trainer. |


20. Verifikasi Pembayaran
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Verifikasi Pembayaran |
|  | Tujuan |
|  | Memverifikasi bukti pembayaran yang diunggah Member |
|  | Deskripsi |
|  | Admin memverifikasi dan mengonfirmasi bukti pembayaran yang diunggah Member untuk mengaktifkan paket keanggotaan atau paket sesi PT tambahan. Jika pembayaran terkait mencakup jatah sesi PT (dari bundling maupun addon), sistem otomatis membuat entri jatah sesi PT dan mengarahkan Member ke fitur penetapan preferensi. |
|  | Aktor |
|  | Admin |
|  | Usecase Yang Berkaitan |
|  | Melakukan Pembayaran (Upload Bukti Transfer) |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Admin membuka menu Verifikasi Pembayaran dan daftar pembayaran berstatus "Menunggu Verifikasi" ditampilkan |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih salah satu data pembayaran |


|  | 2) Melihat detail dan bukti transfer yang diunggah Member |
| --- | --- |


|  | 3) Melakukan konfirmasi (menyetujui) pembayaran |
| --- | --- |



|  | 4) Memperbarui status pembayaran menjadi "Terverifikasi" dan mengaktifkan paket/booking terkait |
| --- | --- |


|  | 5) Jika pembayaran mencakup jatah sesi PT, sistem membuat entri pt_session_packages dan mengirim notifikasi ke Member untuk mengisi preferensi penjadwalan |
| --- | --- |


|  | 6) Mengirim notifikasi email konfirmasi ke Member |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Menolak pembayaran karena bukti transfer tidak valid |



|  | 2) Mengubah status pembayaran menjadi "Ditolak" |
| --- | --- |


|  | 3) Mengirim notifikasi ke Member untuk mengunggah ulang bukti transfer |
| --- | --- |
|  | Kondisi Akhir |
|  | Status pembayaran terverifikasi/ditolak dan paket atau sesi Member diperbarui sesuai hasil verifikasi. |


21. Dashboard Performa Gym
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Dashboard Performa Gym |
|  | Tujuan |
|  | Melihat performa gym secara keseluruhan |
|  | Deskripsi |
|  | Admin memantau performa gym meliputi jumlah Member aktif, tingkat kehadiran, dan pendapatan melalui dashboard. |
|  | Aktor |
|  | Admin |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Admin membuka menu Dashboard Performa Gym |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih periode laporan (harian/bulanan/tahunan) |



|  | 2) Menghitung jumlah Member aktif, tingkat kehadiran, dan pendapatan pada periode tersebut |
| --- | --- |


|  | 3) Menampilkan data dalam bentuk grafik dan ringkasan angka |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Belum ada data pada periode ini" jika data tidak ditemukan |
| --- | --- |
|  | Kondisi Akhir |
|  | Admin memperoleh gambaran performa gym secara keseluruhan. |


22. Manajemen Akun personal trainer
Identifikasi Masalah
|  | Nama |
| --- | --- |
|  | Manajemen Akun personal trainer |
|  | Tujuan |
|  | Mengelola akun personal trainer |
|  | Deskripsi |
|  | Admin membuat dan mengedit akun personal trainer (bukan personal trainer mendaftar sendiri) agar personal trainer dapat login ke sistem. |
|  | Aktor |
|  | Admin |
|  | Usecase Yang Berkaitan |
|  | - |
|  | Skenario Utama |
|  | Kondisi Awal |
|  | Admin membuka menu Manajemen Akun personal trainer |
|  | Aksi Aktor |
|  | Reaksi Sistem |
|  | 1) Memilih tambah/ubah/nonaktifkan akun personal trainer |


|  | 2) Mengisi data akun personal trainer (nama, email, password, spesialisasi) |
| --- | --- |



|  | 3) Memeriksa kelengkapan dan validitas data yang diisi |
| --- | --- |


|  | 4) Menyimpan akun personal trainer ke database |
| --- | --- |


|  | 5) Mengirim email berisi informasi akun ke personal trainer |
| --- | --- |
|  | Skenario Alternatif (Jika Gagal) |
|  | Aksi Aktor |
|  | Reaksi Sistem |


|  | 1) Menampilkan pesan "Email sudah terdaftar" jika email sudah digunakan |
| --- | --- |


|  | 2) Menampilkan kembali form akun personal trainer |
| --- | --- |
|  | Kondisi Akhir |
|  | Akun personal trainer berhasil dibuat/diperbarui dan personal trainer dapat login ke sistem. |
|  | 5.4 Daftar Fitur Utama |


### 5.4.1 Modul Member

1. Registrasi dan login: Member bisa membuat akun baru dan login ke sistem.
2. Pemilihan paket keanggotaan: Member memilih paket membership (harian/bulanan/tahunan) yang ingin dibeli.
3. Pembayaran online dengan upload bukti transfer: Member melakukan pembayaran manual dan mengunggah bukti transfer sebagai bukti pembayaran, menunggu verifikasi dari Admin.
4. Check-in otomatis via QR code: Kehadiran member tercatat otomatis saat memindai QR code yang disediakan pihak gym.
5. Menetapkan preferensi sesi personal trainer: Member mengisi preferensi hari, rentang jam, dan pilihan personal trainer untuk seluruh jatah sesi PT yang dimilikinya (dari bundling paket membership maupun paket PT tambahan). Sistem secara otomatis menjadwalkan seluruh sesi berdasarkan preferensi dan ketersediaan personal trainer (auto-plotting). 
6. Membeli paket sesi Personal Trainer: Member dengan membership aktif dapat membeli paket sesi PT (Per-Visit, 8x, 12x, 16x, atau 20x sesi/bulan) secara terpisah dari bundling paket membership — baik untuk Member yang paketnya belum mencakup PT sama sekali maupun yang ingin menambah jatah sesi — sepanjang sisa masa aktif membership memenuhi syarat minimum hari untuk paket tersebut. 
7. Riwayat pribadi: Member bisa melihat riwayat kehadiran, riwayat booking sesi, dan riwayat transaksi miliknya sendiri.
8. Reminder masa aktif membership: Sistem otomatis mengirim email pengingat sebelum masa keanggotaan member habis.
9. Notifikasi konfirmasi via email: Sistem mengirim email konfirmasi setiap kali ada booking sesi berhasil atau pembelian membership berhasil.
10. Rating personal trainer: Member bisa memberi penilaian (rating) terhadap personal trainer yang sudah diikuti.
11. Melihat Jadwal Personal Trainer: Member dapat melihat jam kerja/ketersediaan tiap personal trainer sebagai bahan pertimbangan sebelum mengisi preferensi hari, jam, dan personal trainer pada fitur penetapan preferensi sesi PT. 

### 5.4.2 Modul Personal Trainer

1. Login ke sistem: personal trainer login menggunakan akun yang telah dibuatkan oleh Admin.
2. Dashboard jadwal sesi: personal trainer memiliki dashboard untuk melihat jadwal sesi harian/mingguan, serta dapat membuat atau mengubah jadwal member yang sudah terdaftar.
3. Update status sesi otomatis: Status pengerjaan sesi ter-update otomatis (menunggu, berlangsung, atau selesai).
4. Daftar dan riwayat member per sesi: personal trainer bisa melihat daftar member yang terdaftar pada tiap sesi beserta riwayatnya.
5. Monitor performa pribadi: personal trainer bisa memantau performa sendiri, seperti jumlah sesi yang sudah dijalani dan jumlah member yang ditangani.

### 5.4.3 Modul Admin/Owner

1. Login sebagai administrator: Admin login ke sistem dengan akun administrator.
2. Manajemen data member: Admin bisa mengelola data member serta status keanggotaan (aktif/expired) yang ter-update otomatis.
3. Manajemen paket dan harga membership: Admin bisa mengatur paket keanggotaan beserta harganya. 
4. Manajemen paket sesi Personal Trainer: Admin bisa mengatur jenis paket sesi PT yang dapat dibeli terpisah dari membership (jumlah sesi, harga, syarat minimum hari, masa berlaku). 
5. Manajemen ketersediaan personal trainer: Admin menentukan jam kerja/ketersediaan tiap personal trainer, yang menjadi dasar slot sesi personal training yang bisa dibuka personal trainer untuk booking oleh member. 
6. Manajemen jadwal dan sesi: Admin bisa mengatur jadwal dan slot sesi personal trainer.
7. Verifikasi pembayaran: Admin memverifikasi dan mengonfirmasi bukti pembayaran yang diunggah member.
8. Dashboard performa gym: Admin memiliki dashboard untuk melihat performa gym secara keseluruhan (jumlah member aktif, tingkat kehadiran, dan pendapatan).
9. Manajemen akun personal trainer: Admin bisa membuat dan mengedit akun personal trainer (bukan personal trainer daftar sendiri), supaya personal trainer bisa login ke sistem.

## 5.5 Prioritas Fitur

Prioritas
|  | Fitur |
| --- | --- |
|  | Alasan |
|  | Must Have |
|  | 1. Registrasi dan login (Member) |

2. Login sebagai administrator
3. Manajemen akun personal trainer
4. Manajemen ketersediaan personal trainer
5. Login ke sistem (personal trainer) 
|  | Fitur pondasi sistem — tanpa akses masuk (login) dan akun personal trainer yang valid, seluruh modul lain tidak bisa berfungsi sama sekali. Ini bukan fitur "nilai tambah", tapi syarat mutlak agar aplikasi bisa dipakai |
| --- | --- |
|  | Must Have |
|  | 1. Pemilihan paket keanggotaan |

2. Pembayaran online dengan upload bukti transfer
3. Verifikasi pembayaran
4. Manajemen data member
5. Check-in otomatis via QR code 
6. Membeli paket sesi Personal Trainer 
|  | Fitur ini mendefinisikan model bisnis inti gym: member membayar untuk jadi anggota aktif dan hadir ke gym. Tanpa alur ini, aplikasi kehilangan fungsi utamanya sebagai sistem keanggotaan |
| --- | --- |
|  | Must Have |
|  | 1. Menetapkan preferensi sesi personal trainer |

2. Manajemen jadwal dan sesi
3. Manajemen paket sesi Personal Trainer  
|  | Personal training adalah salah satu nilai jual utama gym, sehingga alur penjadwalan sesi PT wajib ada di rilis pertama, bukan fitur opsional |
| --- | --- |
|  | Should Have |
|  | 1. Riwayat pribadi |

2. Dashboard jadwal sesi
3. Update status sesi otomatis
4. Daftar dan riwayat member per sesi
5. Manajemen paket dan harga membership 
6. Dashboard performa gym
7. Melihat Jadwal Personal Trainer 
|  | Fitur ini meningkatkan kualitas pengalaman & pengambilan keputusan (member bisa cek histori, admin bisa pantau performa), tapi transaksi inti tetap bisa berjalan secara manual/basic tanpa fitur ini di tahap awal |
| --- | --- |
|  | Could Have |
|  | 1. Reminder masa aktif membership |

2. Notifikasi konfirmasi via email
3. Rating personal trainer
4. Monitor performa pribadi 
|  | Fitur bersifat komplementer yang menambah kenyamanan dan engagement, namun ketidakhadirannya tidak mengganggu jalannya proses bisnis utama — cocok ditunda jika waktu pengembangan terbatas |
| --- | --- |
|  | BAB VI RANCANGAN TEKNIS AWAL |


## 6.1 Arsitektur Sistem

Sistem dibangun mengikuti pola arsitektur MVC (Model-View-Controller) bawaan Laravel. Permintaan dari browser member/personal trainer/admin diterima oleh Route dan diteruskan ke Controller sesuai peran pengguna (dibatasi melalui middleware role-based access control), Controller berinteraksi dengan Model (Eloquent ORM) untuk mengakses basis data MySQL, kemudian hasilnya dirender melalui View (Blade, dapat dikombinasikan dengan Livewire/Alpine.js untuk interaktivitas seperti pemilihan slot jadwal dan pemindaian QR code secara dinamis). Untuk proses pembayaran, sistem menyediakan alur transfer manual: Member mengunggah bukti transfer melalui fitur upload file, yang kemudian diverifikasi secara manual oleh Admin melalui dashboard verifikasi pembayaran. Untuk pembuatan dan pemindaian QR code sistem memanfaatkan library QR code (mis. simple-qrcode), dan untuk notifikasi sistem memanfaatkan layanan pengiriman email (mis. SMTP/Mailtrap pada tahap pengembangan).
Gambaran komponen: Browser (Blade/Livewire) ⇄ Laravel App (Route → Middleware Role → Controller → Eloquent Model) ⇄ Database MySQL, dengan integrasi eksternal QR Code Generator/Scanner, dan Mail Service.



## 6.2 Rancangan Basis Data

No
|  | Nama Tabel |
| --- | --- |
|  | Kolom yang Dibutuhkan |
|  | Keterangan |
|  | 1 |
|  | users |
|  | id, name, email, password, role, phone, status |
|  | Menyimpan akun Member, Personal Trainer, dan Admin. |
|  | 2 |
|  | member_profiles |
|  | id, user_id, member_code, gender, address |
|  | Menyimpan data khusus Member. |
|  | 3 |
|  | trainer_profiles |
|  | id, user_id, specialization, bio, experience, status |
|  | Menyimpan data khusus Personal Trainer. |
|  | 4 |
|  | membership_packages |
|  | id, name, type, duration, price, facilities, pt_sessions, status |
|  | Menyimpan paket membership harian/bulanan/tahunan serta jumlah sesi PT yang termasuk dalam paket. |
|  | 5 |
|  | memberships |
|  | id, member_id, package_id, start_date, end_date, status |
|  | Mencatat membership yang dibeli Member dan masa berlakunya. |
|  | 6 |
|  | pt_session_packages |
|  | id, member_id, membership_id, pt_package_id, source, total_sessions, used_sessions, remaining_sessions, start_date, end_date, status |
|  | Menyimpan jatah sesi PT milik Member, baik dari bundling membership maupun pembelian paket PT tambahan. |
|  | 7 |
|  | pt_packages |
|  | id, name, session_count, price, min_membership_days, validity_period, status |
|  | Katalog paket PT tambahan seperti Per-Visit, 8x, 12x, 16x, dan 20x. |
|  | 8 |
|  | trainer_availabilities |
|  | id, trainer_id, day_of_week, start_time, end_time, status |
|  | Menyimpan hari dan jam kerja/ketersediaan masing-masing PT. |
|  | 9 |
|  | pt_sessions |
|  | id, pt_session_package_id, member_id, trainer_id, session_date, start_time, end_time, status |
|  | Menyimpan setiap sesi PT yang dijadwalkan, termasuk hasil auto-plotting berdasarkan preferensi Member. |
|  | 10 |
|  | pt_preferences |
|  | id, member_id, pt_session_package_id, preferred_days, preferred_start_time, preferred_end_time, preferred_trainer_id, status |
|  | Menyimpan preferensi Member untuk penjadwalan seluruh sesi PT. |
|  | 11 |
|  | payments |
|  | id, member_id, membership_id, pt_package_id, amount, proof_image, status, verified_by, verified_at |
|  | Menyimpan pembayaran transfer beserta bukti transfer dan hasil verifikasi Admin. |
|  | 12 |
|  | check_ins |
|  | id, member_id, membership_id, check_in_time, check_out_time, method |
|  | Mencatat kehadiran Member melalui QR Code. |
|  | 13 |
|  | ratings |
|  | id, member_id, pt_session_id, trainer_id, rating, comment |
|  | Menyimpan rating dan ulasan Member terhadap PT setelah sesi selesai. |



## 6.3 Teknologi yang Digunakan

No
|  | Kategori |
| --- | --- |
|  | Teknologi |
|  | Versi |
|  | Fitur yang Didukung |
|  | 1 |
|  | Backend Framework |
|  | Laravel (PHP) |
|  | Laravel 13.x, PHP ≥ 8.3 |
|  | Seluruh fitur (fondasi aplikasi) |
|  | 2 |
|  | Autentikasi & Otorisasi |
|  | Laravel Breeze/Fortify + Spatie Laravel-Permission (RBAC) |
|  | Breeze v2.x, spatie/laravel-permission v6.x |
|  | Login/registrasi 3 role, pembatasan akses dashboard Member/personal trainer/Admin |
|  | 3 |
|  | Frontend Templating & Styling |
|  | Blade + TailwindCSS |
|  | TailwindCSS v4.x |
|  | Seluruh tampilan antarmuka |
|  | 4 |
|  | Interaktivitas Frontend |
|  | Livewire + Alpine.js |
|  | Livewire v3.x, Alpine.js v3.x |
|  | Pemilihan slot jadwal, form dinamis tanpa reload, pemindaian QR real-time |
|  | 5 |
|  | Basis Data |
|  | MySQL |
|  | MySQL 8.0.x |
|  | Penyimpanan seluruh data (users, membership, booking, payment, dll) |
|  | 6 |
|  | QR Code Generator |
|  | simple-qrcode |
|  | v4.x |
|  | Generate QR code check-in di meja resepsionis |
|  | 7 |
|  | QR Code Scanner (kamera browser) |
|  | html5-qrcode |
|  | v2.3.x |
|  | Fitur "Check-in via QR Code" oleh Member (butuh HTTPS saat deploy agar akses kamera browser diizinkan) |
|  | 8 |
|  | Upload & Penyimpanan File |
|  | Laravel Filesystem (Storage) + Intervention Image (opsional, untuk resize/compress) |
|  | Bawaan Laravel 13.x / Intervention Image v3.x |
|  | Upload bukti transfer pembayaran, validasi format & ukuran file |
|  | 9 |
|  | Kalender/Jadwal Interaktif |
|  | FullCalendar.js |
|  | v6.x |
|  | Fitur "kalender" (4.2 no.3), tampilan jadwal personal trainer & booking sesi PT |
|  | 10 |
|  | Visualisasi Data/Grafik |
|  | Chart.js |
|  | v4.x |
|  | Dashboard Performa Gym & Monitor Performa Pribadi personal trainer (grafik pendapatan, member aktif, dll) |
|  | 11 |
|  | Notifikasi Email |
|  | Laravel Mail (SMTP/Mailtrap) |
|  | Bawaan Laravel 13.x |
|  | Notifikasi konfirmasi booking/pembayaran, reminder masa aktif |
|  | 12 |
|  | Penjadwalan Otomatis (Cron) |
|  | Laravel Task Scheduling |
|  | Bawaan Laravel 13.x |
|  | Auto-update status membership (aktif/expired), auto-update status sesi, trigger reminder email harian |
|  | 13 |
|  | Antrian Latar Belakang (Queue) |
|  | Laravel Queue (driver: database, atau Redis jika tersedia) |
|  | Bawaan Laravel 13.x |
|  | Mengirim email secara asinkron agar tidak memblokir proses utama (registrasi, verifikasi pembayaran) |
|  | 14 |
|  | Version Control |
|  | Git & GitHub/GitLab |
|  | Git ≥ 2.4x |
|  | Kolaborasi tim & manajemen source code |
|  | 15 |
|  | Runtime Build Asset |
|  | Node.js |
|  | Node.js 24.x (LTS "Krypton") |
|  | Build asset TailwindCSS, Livewire, FullCalendar, Chart.js |
|  | BAB VII RENCANA PENGUJIAN AWAL |

Ruang lingkup pengujian mencakup dua metode yang saling melengkapi: pengujian black-box untuk memvalidasi bahwa seluruh alur bisnis (registrasi/login, pembelian membership dengan upload bukti transfer, verifikasi pembayaran, check-in QR, booking sesi personal trainer, dan dashboard trainer/admin) berjalan sesuai skenario penggunaan nyata; dan pengujian white-box dalam bentuk unit testing untuk memvalidasi fungsi-fungsi logic kritis yang risikonya tidak selalu terlihat lewat pengujian antarmuka saja, seperti perhitungan masa aktif keanggotaan dan deteksi bentrok jadwal sesi personal trainer.  

## 7.1 Pengujian Fungsional (Black-box)  Fitur Must Have

Fitur pada kategori Must Have diuji dengan cakupan paling ketat karena kegagalannya langsung menghentikan alur bisnis inti sistem.
No.
|  | Fitur yang Diuji |
| --- | --- |
|  | Kondisi/Input |
|  | Expected Behaviour |
|  | 1 |
|  | Registrasi & Login (semua role) |
|  | Data valid dan tidak valid pada masing-masing role (member/trainer/admin) |
|  | Sistem menerima data valid dan mengarahkan ke dashboard sesuai role; menolak data tidak valid dengan pesan yang jelas |
|  | 2 |
|  | Manajemen akun & ketersediaan personal trainer |
|  | Admin membuat akun trainer baru dan mengatur jam ketersediaan |
|  | Akun trainer aktif dan dapat login; data ketersediaan tersimpan dan menjadi dasar slot sesi PT yang bisa dibuka |
|  | 3 |
|  | Pemilihan paket & upload bukti transfer |
|  | Member memilih paket, mengunggah bukti transfer dengan format/ukuran file yang valid dan tidak valid |
|  | Status pembayaran berubah menjadi “menunggu verifikasi” untuk file valid; file dengan format atau ukuran tidak sesuai ditolak sistem |
|  | 4 |
|  | Verifikasi pembayaran oleh Admin |
|  | Admin menyetujui atau menolak bukti transfer yang diunggah member |
|  | Jika disetujui: status membership aktif, tanggal mulai dan tanggal berakhir tercatat sesuai jenis paket. Jika ditolak: member menerima notifikasi, status ditolak |
|  | 5 |
|  | Check-in via QR Code |
|  | Memindai QR code member dengan status aktif, expired, dan pending |
|  | Status aktif: absensi tercatat otomatis dengan waktu check-in. Status expired/pending: sistem menolak check-in dan menampilkan notifikasi alasan |
|  | 6 |
|  | Check-in via QR Code (duplikasi) |
|  | Member yang sudah berhasil check-in memindai ulang QR code yang sama dalam rentang waktu berdekatan (misal dalam 10 menit setelah check-in pertama) baik karena scan tidak sengaja terulang, kamera scanner membaca QR yang sama lebih dari sekali, maupun koneksi lambat yang membuat member mengulang pemindaian. |
|  | Sistem mendeteksi bahwa sudah ada catatan check-in aktif untuk member tersebut dalam rentang waktu tersebut, sehingga pemindaian kedua tidak menghasilkan baris data absensi baru satu kedatangan fisik tetap tercatat sebagai satu data kehadiran, bukan pembatasan jumlah kunjungan per hari. |
|  | 7 |
|  | Menetapkan preferensi sesi PT (auto-plotting) |
|  | Member dengan jatah sesi PT (dari bundling membership atau paket PT tambahan) mengisi preferensi hari, rentang jam, dan personal trainer pilihan untuk seluruh jatah sesinya sekaligus |
|  | Sistem menjadwalkan otomatis (auto-plot) seluruh sesi PT sesuai preferensi dan ketersediaan personal trainer; jika sebagian preferensi tidak dapat dipenuhi, sistem menampilkan slot alternatif atau notifikasi jadwal yang tidak terpenuhi |
|  | 8 |
|  | Aktivasi jatah sesi PT setelah verifikasi pembayaran |
|  | Admin memverifikasi pembayaran membership (yang mencakup bundling sesi PT) atau pembayaran paket PT |
|  | Jatah sesi PT member menjadi aktif dan proses auto-plotting jadwal berjalan berdasarkan preferensi yang sudah diisi member |
|  | 9 |
|  | Auto-plotting deteksi bentrok & keterbatasan slot |
|  | Beberapa member memiliki preferensi hari/jam yang tumpang tindih pada personal trainer yang sama hingga kapasitas penuh |
|  | Sistem mengalokasikan slot tanpa menempatkan dua sesi berbeda pada jam dan personal trainer yang sama melebihi kapasitas, dan memberi notifikasi ke member yang preferensinya tidak sepenuhnya terpenuhi |
|  | 10 |
|  | Manajemen jadwal sesi |
|  | Admin membuat atau mengubah jadwal sesi |
|  | Perubahan tersimpan dan langsung terlihat pada sisi trainer maupun member terkait |
|  | 7.2 Pengujian Fungsional (Black-box) Fitur Should Have |

No.
|  | Fitur yang Diuji |
| --- | --- |
|  | Kondisi/Input |
|  | Expected Behaviour |
|  | 11 |
|  | Riwayat pribadi member |
|  | Member mengakses riwayat kehadiran, booking, dan transaksi |
|  | Hanya menampilkan data milik member yang sedang login |
|  | 12 |
|  | Dashboard jadwal trainer |
|  | Trainer melihat dan membuat/mengubah jadwal member terdaftar |
|  | Perubahan tersimpan dan tersinkron ke akun member terkait |
|  | 13 |
|  | Update status sesi otomatis |
|  | Waktu pelaksanaan sesi berjalan atau telah lewat |
|  | Status berubah otomatis: menunggu → berlangsung → selesai, sesuai waktu sistem |
|  | 14 |
|  | Dashboard performa gym |
|  | Admin mengakses ringkasan jumlah member aktif, tingkat kehadiran, dan pendapatan |
|  | Data yang ditampilkan sesuai dengan data transaksi dan absensi aktual |
|  | 15 |
|  | Manajemen paket & harga membership (Admin) |
|  | Admin membuat, mengubah, atau menonaktifkan paket keanggotaan beserta harganya |
|  | Perubahan tersimpan dan langsung tercermin pada pilihan paket yang tampil ke member |
|  | 16 |
|  | Pembelian paket sesi PT |
|  | Member dengan membership aktif membeli paket PT (Per-Visit/8x/12x/16x/20x) dengan sisa masa aktif membership yang mencukupi maupun tidak mencukupi syarat minimum hari paket tersebut |
|  | Sisa hari mencukupi: pembelian berhasil dan menunggu verifikasi pembayaran. Sisa hari tidak mencukupi: sistem menolak pembelian dan menampilkan notifikasi syarat minimum hari |
|  | 17 |
|  | Manajemen paket sesi PT  (Admin) |
|  | Admin membuat atau mengubah jenis paket PT tambahan (jumlah sesi, harga, syarat minimum hari, masa berlaku) |
|  | Data tersimpan dan langsung muncul sebagai opsi pembelian bagi member yang memenuhi syarat |
|  | 7.3 Pengujian Non-Fungsional Usability dan Keamanan Dasar |

No.
|  | Fitur yang Diuji |
| --- | --- |
|  | Kondisi/Input |
|  | Expected Behaviour |
|  | 1 |
|  | Usability |
|  | Member menyelesaikan proses pendaftaran membership |
|  | Proses mudah dipahami dan dapat diselesaikan tanpa kendala |
|  | 2 |
|  | Usability |
|  | Member melakukan check-in dan booking sesi PT |
|  | Proses mudah dipahami dan dapat diselesaikan tanpa kendala |
|  | 3 |
|  | Keamanan Dasar (RBAC) |
|  | Member mencoba mengakses dashboard/endpoint admin |
|  | Sistem menolak akses (403), bukan hanya disembunyikan pada antarmuka |
|  | 4 |
|  | Keamanan Dasar (RBAC) |
|  | Trainer mencoba mengakses fitur khusus admin (manajemen paket/harga) |
|  | Sistem menolak akses |
|  | 5 |
|  | Keamanan Dasar (Otorisasi Data) |
|  | Member A mencoba mengakses atau mengubah data booking/riwayat milik Member B |
|  | Sistem menolak akses |
|  | 7.4 Pengujian Struktural (White-box) |

Selain pengujian black-box di atas, dilakukan pengujian white-box dalam bentuk unit testing (menggunakan PHPUnit bawaan Laravel) pada fungsi-fungsi dengan logic kompleks yang berisiko tinggi terhadap kesalahan perhitungan atau kondisi, yaitu: 
No.
|  | Fungsi yang Diuji |
| --- | --- |
|  | Tujuan Pengujian |
|  | 1 |
|  | hitungTanggalBerakhirMembership() |
|  | Memastikan perhitungan tanggal berakhir benar untuk setiap kombinasi jenis paket dan tanggal mulai |
|  | 2 |
|  | autoPlotJadwalSesiPT() |
|  | Memastikan algoritma auto-plotting menempatkan seluruh sesi PT sesuai preferensi dan ketersediaan personal trainer tanpa bentrok, termasuk menangani kasus preferensi yang tidak sepenuhnya bisa dipenuhi |
|  | 3 |
|  | cekBentrokJadwalPT() |
|  | Memastikan sistem mendeteksi dengan benar kondisi bentrok maupun tidak bentrok pada dua input jadwal |
|  | 4 |
|  | cekSyaratMinimumHariPaketPT() |
|  | Memastikan validasi sisa masa aktif membership terhadap syarat minimum hari tiap jenis paket PT tambahan berjalan benar untuk berbagai kombinasi sisa hari dan jenis paket |
|  | 5 |
|  | updateStatusSesiOtomatis() |
|  | Memastikan transisi status sesi (menunggu → berlangsung → selesai) berjalan sesuai kondisi waktu sistem |




________________

# BAB VIII RENCANA KERJA DAN ORGANISASI TIM


## 8.1 Jadwal dan Milestone

Minggu
|  | Fase |
| --- | --- |
|  | Kegiatan Utama |
|  | Milestone |
|  | 1–3 |
|  | Eksplorasi Dataset & Studi Kelayakan |
|  | Riset kompetitor aplikasi gym sejenis, evaluasi kelayakan teknis (tech stack, manual transfer, QR code library), diskusi awal ruang lingkup 3 modul (Member, personal trainer, Admin) |
|  | Studi kelayakan selesai, gambaran fitur awal & batasan proyek terbentuk |
|  | 4 |
|  | Finalisasi Proposal & CP 1 |
|  | Susun proposal lengkap (latar belakang, tujuan, metodologi, fitur, timeline), review internal tim, submit/presentasi CP 1 |
|  | Checkpoint 1: Proposal disetujui |
|  | 5–6 |
|  | Planning & Arsitektur |
|  | Rancang ERD & API contract berdasarkan 22 fitur, desain alur QR code check-in & verifikasi bukti transfer, susun wireframe tiap fitur, susun test plan awal |
|  | Arsitektur & desain fix, task breakdown selesai, environment/repo siap |
|  | 7 |
|  | Setup & Fondasi |
|  | Setup project structure, komponen dasar (navbar, layout, koneksi DB), boilerplate API tiap modul, setup email service (reminder & notifikasi) |
|  | Fondasi teknis siap, tiap owner bisa mulai ngoding fiturnya |
|  | 8–10 |
|  | Development Sprint 1 |
|  | Tiap owner bangun fiturnya (FE+BE): auth & QR check-in, pemilihan paket & upload bukti transfer, dashboard personal trainer, manajemen data member; mentor check-in 2x/minggu |
|  | Skeleton semua fitur jalan, FE-BE nyambung minimal |
|  | 11–12 |
|  | Development Sprint 2 |
|  | Owner sempurnakan fitur: booking PT, verifikasi pembayaran Admin, rating & riwayat, update status sesi otomatis; edge case, styling, error handling |
|  | Semua fitur fungsional penuh secara individual |
|  | 13 |
|  | Integrasi |
|  | Gabungan semua fitur jadi 1 aplikasi, benerin bug integrasi antar modul (Member-personal trainer-Admin) |
|  | Aplikasi jalan end-to-end |
|  | 14 |
|  | Testing Tahap 1 & Bug Fixing |
|  | QA memimpin functional & UI testing menyeluruh, tiap owner perbaiki bug di fiturnya |
|  | Bug mayor selesai, fitur stabil |
|  | 15 |
|  | Testing Tahap 2 (Regression) |
|  | QA testing ulang fokus ke regresi + performance check (terutama QR check-in & upload bukti transfer) |
|  | Aplikasi stabil, siap deploy |
|  | 16 |
|  | Deployment & Dokumentasi Final |
|  | Setup deployment production, semua selesaikan dokumentasi & materi presentasi |
|  | Aplikasi live + dokumentasi lengkap + siap demo |


________________

## 8.2 Pembagian Peran dan Tanggung Jawab

Owner Fitur
|  | Fitur yang Dipegang |
| --- | --- |
|  | Alasan Pembagian |
|  | Siti Aisyah |



Akses & Identitas
|  | 1. Registrasi & login member |
| --- | --- |

2. Login personal trainer
3. Login admin
4. Manajemen role & akun pengguna (tambah/edit akun personal trainer)
5. Check-in otomatis via QR code
6. Manajemen data member & status keanggotaan
|  | Semua fitur ini berkaitan dengan autentikasi & identitas pengguna. Fitur ini jadi fondasi paling awal (harus selesai duluan) karena modul lain bergantung padanya. Cocok dikerjakan bareng dalam satu alur |
| --- | --- |
|  | Achmad Alfi Maksum |



Pembayaran & Paket
|  | 1. Pemilihan paket keanggotaan |
| --- | --- |

2. Pembayaran online + upload bukti transfer
3. Verifikasi pembayaran (Admin)
4. Manajemen paket & harga membership
5. Membeli paket sesi Personal Trainer
6. Manajemen paket sesi Personal Trainer (Admin)
7. Reminder masa aktif membership (email)
8. Dashboard performa gym 
|  | Satu alur bisnis utuh seputar transaksi: member memilih & membayar paket (baik membership maupun paket PT tambahan), admin memverifikasi dan mengelola katalog harga, sampai data itu terlihat di dashboard performa & reminder. Menambahkan pembelian/manajemen paket PT ke sini logis karena sama-sama alur "katalog produk → beli → bayar" seperti paket membership |
| --- | --- |
|  | Naufal Alif Adjani |



Booking & Penjadwalan
|  | 1. Booking sesi personal trainer |
| --- | --- |

2. Manajemen ketersediaan personal trainer
3. Manajemen jadwal dan sesi
4. Dashboard jadwal sesi personal trainer
5. Update status sesi otomatis
6. Melihat Jadwal Personal Trainer
7. Menetapkan preferensi sesi Personal Trainer (auto-plotting) 
|  | Satu alur penjadwalan penuh: admin atur ketersediaan personal trainer → member melihat jadwal trainer → member menetapkan preferensi (auto-plotting) → personal trainer lihat & kelola jadwalnya. Auto-plotting adalah fitur paling kompleks secara algoritma (butuh unit test khusus), jadi diletakkan bersama pemilik alur penjadwalan yang paling memahami struktur data ketersediaan & sesi trainer |
| --- | --- |
|  | Muhammad Rif’at Syamlan |

 
Riwayat, Notifikasi & Feedback
|  | 1. Riwayat pribadi (kehadiran, booking, transaksi) |
| --- | --- |

2. Notifikasi konfirmasi via email
3. Rating personal trainer
4. Daftar & riwayat member per sesi (sisi personal trainer)
5. Monitor performa pribadi personal trainer
|  | Fitur-fitur ini sifatnya "pelengkap", menampilkan data historis dan feedback dari alur-alur di atas. Cocok dikerjakan belakangan setelah data dari 3 alur lain mulai tersedia. |
| --- | --- |


































________________

# BAB IX LAMPIRAN

Referensi yang menjadi acuan penyusunan latar belakang dan gambaran fitur produk ini antara lain:
•        Irawan, Z. H., Lestanti, S., & Mawaddah, U. (2024). Rancang Bangun Sistem Informasi Manajemen Administrasi pada Gym Berbasis Web Menggunakan Metode Pengembangan Agile. JATI (Jurnal Mahasiswa Teknik Informatika),https://www.ejournal.itn.ac.id/jati/article/view/11105
•        Prananda, R., Hanggara, B. T., & Wicaksono, S. A. (2026). Pengembangan Sistem Informasi Manajemen Pendaftaran dan Keanggotaan Gym Berbasis Web Menggunakan Pendekatan Metode Waterfall (Studi Pada: Gym 51 Jakarta). Jurnal Pengembangan Teknologi Informasi dan Ilmu Komputer, Universitas Brawijaya.https://j-ptiik.ub.ac.id/index.php/j-ptiik/article/view/16888
•        Januartika, C., Rosmiati, R., & Sartana, S. (2023). Analisis dan Perancangan Sistem Informasi Absensi Berbasis Web Menggunakan QR Code Studi Kasus: STMIK Palangkaraya. Jurnal Sistem Informasi, Manajemen dan Teknologi Informasi.  https://www.ojs.stmikplk.ac.id/index.php/simtek/article/view/385
•        Putra, I. P., & Kustiyono. (2026). Sistem Manajemen Ketertiban dan Pencatatan Keuangan Berbasis Website untuk Layanan Jasa Produk Digital di Startup. Jurnal Sistem Informasi Akuntansi(JUSTIAN).  https://jurnal.bsi.ac.id/https:/jurnal.bsi.ac.id/index.php/justian/article/view/12194
•        Paulina, H. P., Zahari, D., Meliyanti, A., & Hati, K. (2026). Pengembangan Sistem Informasi Pengelolaan Membership Gym King Fitness Berbasis Web Menggunakan Metode USDP. Jurnal Rekayasa Sistem Informasi dan Teknologi.  https://journal.ppmi.web.id/index.php/jrsit/article/view/3203




  
