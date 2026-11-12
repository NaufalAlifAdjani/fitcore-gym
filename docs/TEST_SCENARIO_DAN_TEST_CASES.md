
# DOKUMEN TEST SCENARIO (SKENARIO PENGUJIAN)



Sistem: FitCore Athletic — Sistem Informasi Manajemen dan Reservasi Gym Berbasis Web
Mata Kuliah: Penjaminan Mutu Perangkat Lunak (SIB225008 / SIB216101)
Program Studi: D4 Sistem Informasi Bisnis — Politeknik Negeri Malang
Standar Dokumen: PMPL Bab 3 (Buku Ajar SIB225008) & IEEE 829 / ISO 29119


Lembar Informasi Dokumen
Identifier Dokumen
|  | TS-FITCORE-2026-V1.0 |
| --- | --- |
|  | Acuan Test Plan |
|  | TP-FITCORE-2026-V1.0 |
|  | Versi Dokumen |
|  | 1.0 (Review Milestone Release) |
|  | Nama Sistem |
|  | FitCore Gym Management & Personal Trainer Booking System |
|  | Penyusun (Tim SIB-3A) |
|  | 1. Naufal Alif Adjani (NIM: 244107060090) |

2. Achmad Alfi Maksum (NIM: 244107060009)
3. Muhammad Rif’at Syamlan (NIM: 244107060150)
4. Siti Aisyah (NIM: 244107060038)
|  | Tanggal Terbit |
| --- | --- |
|  | 8 November 2026 |

1. Pendahuluan & Prinsip Penyusunan

## 1.1 Fungsi Dokumen

Dokumen Test Scenario ini merupakan artefak perencanaan tingkat kedua dalam siklus Software Testing Life Cycle (STLC). Dokumen ini menjembatani arahan umum pada Test Plan (TP-FITCORE-2026-V1.0) menuju situasi-situasi konkret yang wajib diperiksa sebelum pengujian rinci pada Test Case (TC) ditulis.


Test Plan (TP)⟶Test Scenario (TS)⟶Test Case 



## 1.2 Prinsip Granularitas (Granularity Principle)

Sesuai materi Buku Ajar Bab 3 (Subbab 3.18), seluruh skenario di dalam dokumen ini dirancang dengan tingkat kedetailan yang tepat (appropriate granularity):
* Bukan Terlalu Umum: Menghindari instruksi abstrak seperti "Test payment" atau "Test login".
* Bukan Terlalu Rinci (Bukan Test Case): Tidak memuat langkah eksekusi click-by-click (seperti "klik tombol X, masukkan PIN, reload").
* Format Baku: Seluruh skenario menggunakan pola baku industri: Verify [behavior] when [condition].atauVerifikasi [perilaku] ketika [kondisi].\mathbf{Verify\ [behavior]\ when\ [condition].}\quad\text{atau}\quad\mathbf{Verifikasi\ [perilaku]\ ketika\ [kondisi].}Verify [behavior] when [condition].atauVerifikasi [perilaku] ketika [kondisi].

## 1.3 Delapan Sumber Penurunan Skenario

Seluruh skenario diturunkan dari 8 sumber yang telah didefinisikan dalam silabus:
1. Functional Requirement: Kemampuan yang harus disediakan oleh sistem.
2. Business Rule (BR): Batasan, syarat operasional, dan logika bisnis.
3. User Workflow: Jalur ideal (happy path) maupun penyimpangan alur (interrupted/abandoned flow).
4. Product Risk: Skenario untuk memitigasi kegagalan kritis yang telah diidentifikasi pada Test Plan.
5. User Role: Perbedaan perilaku sistem berdasarkan peran pengguna (Admin, Trainer, Member).
6. System State: Perilaku sistem yang dipengaruhi status entitas (pending, active, expired, cancelled).
7. External Dependency & Environment: Penanganan integrasi eksternal (camera scanner, SMTP server, queue worker).
8. Edge Condition: Kondisi batas ekstrem (boundary value) seperti kuota habis atau masa aktif hari terakhir.


2. Rantai Ketertelusuran (Traceability Matrix)
Dokumen ini mencakup seluruh 15 Fitur Objek Uji berstatus "Ditinjau" yang dikelompokkan ke dalam 6 modul utama:
* MEM : Modul Membership & Profil Member (TP-MEM-01 s/d TP-MEM-03)
* PAY : Modul Pembayaran Online & Verifikasi Admin (TP-PAY-01 & TP-PAY-02)
* PT : Modul Layanan Personal Trainer, Kuota & Jadwal (TP-PT-01 s/d TP-PT-05)
* ATT : Modul Presensi & Scanner QR Code (TP-ATT-01 & TP-ATT-02)
* SEC : Modul Keamanan Otorisasi Spatie RBAC & 403 (TP-SEC-01)
* NOTIF: Modul Infrastruktur Email Queue & SMTP (TP-NOTIF-01 & TP-NOTIF-02)


3. Tabel Konsolidasi Test Scenario (Tabel TS)
Keterangan Kolom:
* Scenario ID: Kode unik skenario (TS-[MODUL]-[NO]).
* Source: Sumber penurunan skenario (Requirement, Business Rule, Risk, Role, State, Dependency, Edge, Workflow).
* Ref. Test Plan: Hubungan ketertelusuran ke objek uji Test Plan (TP-FITCORE-2026-V1.0).
* Feature: Nama fitur spesifik yang diuji.
* Scenario Description: Deskripsi situasi berformat Verify [behavior] when [condition].
* Scenario Type: Kategori skenario (Happy Path, Negative, Edge, Role-based, State-based, Dependency, Risk-based).
* Priority: Tingkat prioritas (High, Medium, Low berdasarkan matriks risiko Test Plan).
* Expected Behavior (High Level): Gambaran umum respons sistem yang diharapkan.



## 3.1 Modul Pembayaran Online & Verifikasi Admin (PAY)

Area Risiko Kritis: Verifikasi manual, mutasi nominal, aktivasi status, dan penolakan bukti palsu.
Scenario ID
|  | Source |
| --- | --- |
|  | Ref. Test Plan |
|  | Feature |
|  | Scenario Description |
|  | Scenario Type |
|  | Priority |
|  | Expected Behavior (High Level) |
|  | TS-PAY-001 |
|  | Workflow |
|  | TP-PAY-01 (WBS 3.2.1) |
|  | Upload Bukti Bayar |
|  | Verify transaction moves to WAITING_VERIFICATION when member uploads a valid payment receipt. |
|  | Happy Path |
|  | High |
|  | Data transaksi tersimpan dengan status 'pending', file bukti tersimpan terkompresi, dan member diarahkan ke halaman invoice menunggu verifikasi. |
|  | TS-PAY-002 |
|  | Business Rule |
|  | TP-PAY-01 (WBS 3.2.2) |
|  | Upload Bukti Bayar |
|  | Verify upload is rejected when file format is unsupported or file size exceeds 2MB limit. |
|  | Negative |
|  | High |
|  | Form menolak submit, menampilkan pesan validasi error yang ramah, dan tidak ada file ilegal yang tersimpan di server. |
|  | TS-PAY-003 |
|  | Business Rule |
|  | TP-PAY-02 (WBS 3.3.1) |
|  | Verifikasi Admin |
|  | Verify membership is activated with exact start and end dates when admin approves valid payment. |
|  | State-based |
|  | High |
|  | Status transaksi menjadi 'verified', status keanggotaan member berubah dari 'inactive' menjadi 'active', dan durasi masa aktif terhitung tepat di database. |
|  | TS-PAY-004 |
|  | State |
|  | TP-PAY-02 (WBS 3.3.2) |
|  | Verifikasi Admin |
|  | Verify membership remains inactive and rejection reason is logged when admin rejects payment. |
|  | Negative |
|  | High |
|  | Status transaksi menjadi 'rejected', membership tetap tidak aktif, dan alasan penolakan dapat dilihat oleh member di halaman riwayat. |
|  | TS-PAY-005 |
|  | Product Risk |
|  | TP-PAY-02 (RSK-01) |
|  | Verifikasi Admin |
|  | Verify system blocks repeated approval attempts on an already verified payment transaction. |
|  | Risk-based |
|  | High |
|  | Sistem mencegah eksekusi ulang pada transaksi yang sudah berstatus 'verified' sehingga tidak terjadi duplikasi masa aktif (no double activation). |



## 3.2 Modul Keanggotaan & Profil Member (MEM)

Fokus: Katalog paket aktif, pencegahan pemilihan paket nonaktif, dan isolasi riwayat data pribadi.
Scenario ID
|  | Source |
| --- | --- |
|  | Ref. Test Plan |
|  | Feature |
|  | Scenario Description |
|  | Scenario Type |
|  | Priority |
|  | Expected Behavior (High Level) |
|  | TS-MEM-001 |
|  | Requirement |
|  | TP-MEM-01 (WBS 3.1.1) |
|  | Pemilihan Paket |
|  | Verify member can select available membership tier and proceed to checkout invoice page. |
|  | Happy Path |
|  | High |
|  | Sistem menampilkan kalkulasi harga, durasi hari, dan rincian fasilitas paket secara transparan pada halaman checkout. |
|  | TS-MEM-002 |
|  | State |
|  | TP-MEM-02 (WBS 3.4.1) |
|  | Manajemen Paket |
|  | Verify inactive membership packages are hidden from member purchasing catalog. |
|  | State-based |
|  | Medium |
|  | Paket yang statusnya di-toggle nonaktif oleh Admin tidak lagi muncul di halaman member, namun transaksi terdahulu tetap terjaga konsistensinya. |
|  | TS-MEM-003 |
|  | User Role |
|  | TP-MEM-03 (WBS 5.1.1) |
|  | Riwayat Member |
|  | Verify logged-in member can only view their own identity pass, attendance history, and invoices. |
|  | Role-based |
|  | Medium |
|  | Sistem menerapkan filter data berbasis user ID yang login; member tidak dapat melihat data presensi atau transaksi member lain via manipulasi URL. |
|  | 3.3 Modul Personal Trainer (PT) Package, Kalender & Booking (PT) |

Area Risiko Kritis: Batas kuota sesi, kalkulasi syarat sisa hari membership, konkurensi jadwal trainer, dan batas reservasi.
Scenario ID
|  | Source |
| --- | --- |
|  | Ref. Test Plan |
|  | Feature |
|  | Scenario Description |
|  | Scenario Type |
|  | Priority |
|  | Expected Behavior (High Level) |
|  | TS-PT-001 |
|  | Business Rule |
|  | TP-PT-01 (WBS 3.5.1) |
|  | Beli Paket PT |
|  | Verify member can purchase PT package when remaining active membership meets minimum days required. |
|  | Happy Path |
|  | High |
|  | Pembelian paket kuota PT berhasil diproses karena sisa masa aktif membership member lebih lama atau sama dengan masa berlaku paket PT. |
|  | TS-PT-002 |
|  | Business Rule |
|  | TP-PT-01 (WBS 3.5.2) |
|  | Beli Paket PT |
|  | Verify purchase is rejected when member's remaining membership days are below minimum requirement. |
|  | Negative |
|  | High |
|  | Form pembelian menolak transaksi dan memberikan saran untuk memperpanjang paket keanggotaan terlebih dahulu. |
|  | TS-PT-003 |
|  | Requirement |
|  | TP-PT-03 (WBS 4.3.1) |
|  | Kalender Jadwal |
|  | Verify FullCalendar dynamically renders only available time slots when a trainer is selected. |
|  | Happy Path |
|  | Medium |
|  | Kalender memuat ketersediaan slot jam pelatih secara visual; slot yang telah terisi ditandai nonaktif/terkunci. |
|  | TS-PT-004 |
|  | Workflow |
|  | TP-PT-04 (WBS 4.4.1) |
|  | Booking Sesi PT |
|  | Verify booking is created and member PT quota is decremented by 1 when booking an open slot. |
|  | Happy Path |
|  | High |
|  | Sesi baru tersimpan dengan status 'pending/booked', kuota sesi member berkurang tepat 1, dan jadwal tersemat di portal member serta trainer. |
|  | TS-PT-005 |
|  | Product Risk |
|  | TP-PT-04 (RSK-02) |
|  | Booking Sesi PT |
|  | Verify second booking attempt is rejected when two members select the exact same slot simultaneously. |
|  | Edge |
|  | High |
|  | Mekanisme concurrency locking database meloloskan pemesan pertama dan menolak pemesan kedua dengan pesan bahwa slot baru saja diambil. |
|  | TS-PT-006 |
|  | Business Rule |
|  | TP-PT-04 (WBS 4.4.2) |
|  | Batas Reservasi |
|  | Verify booking is rejected when a member attempts to exceed the limit of 3 active pending sessions. |
|  | Negative |
|  | High |
|  | Sistem menolak pembuatan sesi ke-4 jika member masih memiliki 3 sesi berstatus aktif/menunggu pelaksanaan (business rule compliance). |
|  | TS-PT-007 |
|  | Dependency |
|  | TP-PT-05 (WBS 4.4.3) |
|  | Notifikasi Sesi |
|  | Verify incoming session alert is visible on trainer dashboard when member submits new booking. |
|  | Dependency |
|  | Medium |
|  | Trainer menerima notifikasi pengajuan sesi pada dasbornya untuk segera dikonfirmasi (confirmed) atau dijadwalkan ulang. |


## 3.4 Modul Presensi & Scanner QR Code Digital Pass (ATT)

Area Risiko Kritis: Pemindaian kamera browser, validasi status kartu aktif/expired, dan pencegahan duplikasi scan harian.
Scenario ID
|  | Source |
| --- | --- |
|  | Ref. Test Plan |
|  | Feature |
|  | Scenario Description |
|  | Scenario Type |
|  | Priority |
|  | Expected Behavior (High Level) |
|  | TS-ATT-001 |
|  | Requirement |
|  | TP-ATT-01 (WBS 2.5.2) |
|  | Scanner Kamera |
|  | Verify camera scanner decodes encrypted QR payload and sends check-in request to server. |
|  | Happy Path |
|  | High |
|  | Library html5-qrcode mendeteksi frame kode QR digital pass member dan mengekstrak data string tanpa crash di browser. |
|  | TS-ATT-002 |
|  | State |
|  | TP-ATT-02 (WBS 2.5.3) |
|  | Presensi Member |
|  | Verify check-in is accepted and attendance is logged when active member scans digital QR pass. |
|  | State-based |
|  | High |
|  | Muncul indikator sukses hijau pada antarmuka resepsionis, nama member terpampang, dan 1 baris kehadiran tercatat dengan timestamp akurat. |
|  | TS-ATT-003 |
|  | State |
|  | TP-ATT-02 (WBS 2.5.3) |
|  | Presensi Member |
|  | Verify check-in is rejected when scanned QR pass belongs to an expired or inactive member. |
|  | Negative |
|  | High |
|  | Muncul indikator penolakan merah bahwa keanggotaan sudah habis masa berlakunya; tidak ada catatan kehadiran baru yang ditambahkan ke database. |
|  | TS-ATT-004 |
|  | Product Risk |
|  | TP-ATT-02 (RSK-03) |
|  | Anti-Duplikasi |
|  | Verify repeated scan attempt within cooldown window (< 15 mins) is rejected without duplicate logging. |
|  | Edge |
|  | High |
|  | Pemindaian kedua dalam rentang waktu singkat ditolak dengan pesan peringatan "Anda sudah melakukan presensi" guna mencegah manipulasi kehadiran. |



## 3.5 Modul Keamanan, RBAC Spatie & Proteksi Rute (SEC)

Area Risiko Kritis: Penolakan akses tidak sah antar-role dan perlindungan rute administratif.
Scenario ID
|  | Source |
| --- | --- |
|  | Ref. Test Plan |
|  | Feature |
|  | Scenario Description |
|  | Scenario Type |
|  | Priority |
|  | Expected Behavior (High Level) |
|  | TS-SEC-001 |
|  | Business Rule |
|  | TP-SEC-01 (WBS 2.2.2) |
|  | RBAC 403 |

 
|  | Verify member or trainer accounts are blocked with 403 Forbidden when accessing admin URLs. |
| --- | --- |
|  | Role-based |
|  | High |
|  | Upaya akses langsung ke prefix /admin/* oleh non-admin menghasilkan status HTTP 403 Forbidden tanpa membocorkan antarmuka administratif. |
|  | TS-SEC-002 |
|  | User Role |
|  | TP-SEC-01 (WBS 2.2.2) |
|  | Autentikasi Tamu |
|  | Verify unauthenticated guest requests to protected member or admin pages redirect to login. |
|  | Negative |
|  | High |
|  | Pengguna yang belum login otomatis diarahkan (redirect) ke rute /login saat mencoba mengakses halaman portal privat. |



## 3.6 Modul Infrastruktur Notifikasi Email & Antrean Queue (NOTIF)

Fokus: Pengiriman email asinkron dan ketahanan sistem saat layanan pihak ketiga mengalami gangguan.
Scenario ID
|  | Source |
| --- | --- |
|  | Ref. Test Plan |
|  | Feature |
|  | Scenario Description |
|  | Scenario Type |
|  | Priority |
|  | Expected Behavior (High Level) |
|  | TS-NOTIF-001 |
|  | Dependency |
|  | TP-NOTIF-01 (WBS 5.2.1) |
|  | Email Queue |
|  | Verify confirmation email is queued and delivered to Mailtrap inbox when payment is verified. |
|  | Dependency |
|  | Medium |
|  | Event verifikasi memasukkan job email ke antrean database dan pesan HTML diterima di Mailtrap tanpa memblokir respon browser pengguna. |
|  | TS-NOTIF-002 |
|  | Product Risk |
|  | TP-NOTIF-02 (RSK-05) |
|  | SMTP Failure |
|  | Verify payment verification still completes in database when external email service times out. |
|  | Risk-based |
|  | Medium |
|  | Transaksi aktivasi membership tetap berhasil disetujui, kegagalan email dicatat di tabel failed_jobs, dan sistem tidak melempar error 500 ke admin. |


4. Evaluasi Cakupan Skenario (Scenario Coverage Evaluation)
Sesuai materi Buku Ajar Bab 3 (Subbab 3.24 Meaningful Coverage), evaluasi kecukupan skenario dinilai berdasarkan matriks berikut:
1. Sebaran Tipe Skenario (Scenario Type Distribution):
   * Happy Path: 6 Skenario (26%) — Menguji alur utama pemesanan, pembayaran, booking, dan check-in.
   * Negative Condition: 6 Skenario (26%) — Menguji penolakan file ilegal, penolakan kuota habis, penolakan kartu expired, dan penolakan tamu tanpa login.
   * State-based: 4 Skenario (17%) — Menguji transisi status transaksi dan status keaktifan paket.
   * Role-based: 2 Skenario (9%) — Menguji isolasi data pribadi dan proteksi middleware 403.
   * Edge Condition: 2 Skenario (9%) — Menguji kondisi batas konkurensi booking simultan dan anti-duplikasi scan interval singkat.
   * Dependency & Risk-based: 3 Skenario (13%) — Menguji integrasi kamera, email queue, dan ketahanan terhadap kegagalan SMTP.
2. Keterwakilan Risiko Kritis (Risk Coverage):
   * 100% risiko produk berstatus CRITICAL dan HIGH pada Test Plan (RSK-01 s/d RSK-05) telah terwakili oleh skenario uji yang berprioritas High.
3. Non-Redundancy:
   * Setiap skenario menguji kondisi dan perilaku bisnis yang unik tanpa pengulangan situasi yang sia-sia (meaningful testing).


5. Rencana Transisi Menuju Test Case
Seluruh 23 skenario pada dokumen ini siap diturunkan menjadi Test Case (TC) atomik pada pertemuan berikutnya dengan format penomoran langsung:
* Skenario TS-PAY-001 ⟶\longrightarrow⟶ Kasus Uji TC-PAY-001
* Skenario TS-PT-005 ⟶\longrightarrow⟶ Kasus Uji TC-PT-005
* Skenario TS-ATT-002 ⟶\longrightarrow⟶ Kasus Uji TC-ATT-002
Setiap Test Case nantinya akan dilengkapi dengan:
1. Precondition & Test Data spesifik (akun login, nominal riil, file upload).
2. Atomic Steps (urutan klik dan pengisian form).
3. Three-Layer Expected Result (Layar, Data Persistence di DB, dan Efek Samping).


Lembar Pengesahan (Sign-off)
Dokumen Test Scenario ini disahkan sebagai pedoman penyusunan Test Case rilis peninjauan FitCore Gym:
* Disusun Oleh (Test Lead): Naufal Alif Adjani
* Reviewer (Test Analyst): Achmad Alfi Maksum
* Automation Reviewer: Muhammad Rif’at Syamlan
* Environment Reviewer: Siti Aisyah
* Status: APPROVED FOR TEST CASE DEVELOPMENT
 





# DOKUMEN TEST CASE & EXECUTION RECORD

Sistem: FitCore Athletic — Sistem Informasi Manajemen dan Reservasi Gym Berbasis Web
Mata Kuliah: Penjaminan Mutu Perangkat Lunak (SIB225008 / SIB216101)
Program Studi: D4 Sistem Informasi Bisnis — Politeknik Negeri Malang
Standar Dokumen: PMPL Bab 4 (Buku Ajar SIB225008) & IEEE Std 829-2008 / ISO 29119
________________


 
Lembar Informasi Dokumen
Atribut
|  | Keterangan |
| --- | --- |
|  | Identifier Dokumen |
|  | TC-FITCORE-2026-V1.0 |
|  | Acuan Test Scenario |
|  | TS-FITCORE-2026-V1.0 |
|  | Acuan Test Plan |
|  | TP-FITCORE-2026-V1.0 |
|  | Versi Dokumen |
|  | 1.0 (Review Milestone Release) |
|  | Nama Sistem |
|  | FitCore Gym Management & Personal Trainer Booking System |
|  | Penyusun (Tim SIB-3A) |
|  | 1. Naufal Alif Adjani (NIM: 244107060090) |

2. Achmad Alfi Maksum (NIM: 244107060009)
3. Muhammad Rif’at Syamlan (NIM: 244107060150)
4. Siti Aisyah (NIM: 244107060038)
|  | Tanggal Penyusunan |
| --- | --- |
|  | 8 November 2026 |

 
1.         Pendahuluan & Prinsip Penyusunan
 

## 1.1 Posisi Test Case dalam STLC

Dokumen ini merupakan artefak operasional tingkat ketiga yang menerjemahkan situasi uji pada Test Scenario (TS-FITCORE-2026-V1.0) menjadi prosedur pengujian rinci yang dapat dieksekusi oleh siapa pun di tim penguji.
Test Plan (TP)⟶Test Scenario (TS)⟶Test Case (TC)⟶Eksekusi & Otomasi
 

## 1.2 Tiga Sifat Kunci Test Case (Subbab 4.3 Buku Ajar)

Setiap Test Case di dalam dokumen ini wajib memenuhi tiga karakteristik dasar:
1.     Executable (Dapat Dieksekusi): Langkah pengujian ditulis atomik dan presisi sehingga penguji dapat menjalankannya tanpa bertanya atau menebak-nebak.
2.     Repeatable (Dapat Diulang): Pengujian yang dijalankan berulang kali pada kondisi data yang sama akan selalu menghasilkan kesimpulan yang konsisten.
3.     Verifiable (Dapat Diverifikasi): Kriteria kelulusan didefinisikan secara objektif tanpa interpretasi subjektif seperti "berjalan lancar".

## 1.3 Aturan Tiga Lapisan Hasil Uji (Three-Layer Expected Result)

Sesuai materi Buku Ajar Bab 4 (Subbab 4.9 Tabel 4.9), seluruh kriteria Expected Result mencakup 3 lapisan:
·       Lapisan 1 (Tampilan / Layar): Pesan teks error/sukses, toast alert, indikator warna badge, elemen disabled, dan pengalihan halaman (redirect).
·       Lapisan 2 (Data Persistence): Status basis data permanen setelah dilakukan muat ulang halaman (page.reload()) atau kueri database (bukan ilusi manipulasi DOM sesaat).
·       Lapisan 3 (Efek Lanjutan / Side Effects): Mutasi saldo/kuota sesi, log kehadiran, antrean email pada tabel jobs, dan notifikasi antar-aktor.
2.         Test Data Sheet Terpusat (Data Dummy Uji)
Sesuai materi Bab 4 Subbab 4.7 (Tabel 4.7), seluruh data uji didefinisikan secara terpusat untuk menjaga konsistensi pengujian dan etika privasi data:

## 2.1  Data Akun Pengguna & Role (Actors)

Kode Data
|  | Akun Email / Username |
| --- | --- |
|  | Role / Hak Akses |
|  | Kondisi Awal & Atribut |
|  | U-01 |
|  | admin@gym.test |
|  | Admin |
|  | Password: password123, memiliki wewenang verifikasi & kelola paket. |
|  | U-02 |
|  | budi@gym.test |
|  | Trainer |
|  | Password: password123, Trainer aktif, spesialisasi: Muscle Building. |
|  | U-03 |
|  | sari@gym.test |
|  | Trainer |
|  | Password: password123, Trainer aktif, spesialisasi: Weight Loss. |
|  | U-04 |
|  | amanda@test.com |
|  | Member |
|  | Password: password123, Member Aktif (Paket Gold, sisa masa aktif 120 hari), Kuota Sesi PT: 4 sesi. |
|  | U-05 |
|  | dimas@test.com |
|  | Member |
|  | Password: password123, Member Baru, belum memiliki transaksi aktif. |
|  | U-06 |
|  | siti.expired@test.com |
|  | Member |
|  | Password: password123, Status: expired (masa aktif berakhir kemarin). |
|  | U-07 |
|  | farhan.pt3@test.com |
|  | Member |
|  | Password: password123, Member Aktif, memiliki tepat 3 reservasi sesi PT aktif (RES-001, RES-002, RES-003). |



## 2.2  Data Paket & Kuota Layanan

Kode Paket
|  | Nama Paket Layanan |
| --- | --- |
|  | Tipe Layanan |
|  | Harga Resmi |
|  | Durasi / Kuota |
|  | Syarat Min. Membership |
|  | PKG-01 |
|  | Gold Annual Pass |
|  | Membership |
|  | Rp3.000.000 |
|  | 365 Hari |
|  | 0 Hari |
|  | PKG-02 |
|  | Basic Monthly Pass |
|  | Membership |
|  | Rp350.000 |
|  | 30 Hari |
|  | 0 Hari |
|  | PT-01 |
|  | Personal Trainer 4 Sesi |
|  | PT Service |
|  | Rp600.000 |
|  | 4 Sesi (30 Hari) |
|  | Min. 30 Hari Aktif |
|  | PT-02 |
|  | Personal Trainer 8 Sesi |
|  | PT Service |
|  | Rp1.100.000 |
|  | 8 Sesi (45 Hari) |
|  | Min. 45 Hari Aktif |



## 2.3  Data Berkas & Digital Pass Token

Kode Berkas
|  | Nama File / Token Payload |
| --- | --- |
|  | Tipe / Format |
|  | Ukuran / Keterangan |
|  | F-01 |
|  | bukti_transfer_valid.webp |
|  | Gambar WebP |
|  | 120 KB, struk transfer bank sah nominal Rp350.000. |
|  | F-02 |
|  | bukti_transfer_large.png |
|  | Gambar PNG |
|  | 2.5 MB (Melebihi batas sistem 2048 KB). |
|  | F-03 |
|  | dokumen_terlarang.exe |
|  | Binary Executable |
|  | 500 KB (Format ilegal / tidak didukung). |
|  | QR-01 |
|  | FC-PASS-U04-ACTIVE |
|  | QR Hash String |
|  | Digital Pass milik amanda@test.com (Member Aktif). |
|  | QR-02 |
|  | FC-PASS-U06-EXPIRED |
|  | QR Hash String |
|  | Digital Pass milik siti.expired@test.com (Member Expired). |

3.     Rekapitulasi Multi Test Case (Format Spreadsheet)
Test Case ID
|  | Scenario Ref. |
| --- | --- |
|  | Judul / Objective Uji |
|  | Tipe & Prioritas |
|  | Precondition & Test Data |
|  | Langkah Pengujian (Atomik) |
|  | Hasil yang Diharapkan (3 Lapisan) |
|  | Status |
|  | TC-PAY-001 |
|  | TS-PAY-001 |
|  | Upload bukti transfer valid berhasil membuat transaksi pending |
|  | Positif · High |
|  | Precondition: Login Member U-05. Membuka checkout PKG-02. |

Data: File F-01.
|  | 1. Buka halaman pembayaran. |
| --- | --- |

2. Upload berkas F-01.
3. Pilih bank transfer 'BCA'.
4. Tekan tombol "Kirim Bukti Pembayaran".
|  | Layar: Toast sukses "Bukti pembayaran berhasil diunggah". |
| --- | --- |

Data: Status payment di DB = pending.
Efek: Notifikasi verifikasi muncul di panel admin.
|  | Pass |
| --- | --- |
|  | TC-PAY-002 |
|  | TS-PAY-002 |
|  | Upload berkas bukti transfer > 2MB ditolak sistem |
|  | Negatif · High |
|  | Precondition: Login Member U-05. Membuka checkout PKG-02. |

Data: File F-02 (2.5MB).
|  | 1. Buka halaman upload pembayaran. |
| --- | --- |

2. Pilih berkas F-02.
3. Tekan tombol "Kirim Bukti Pembayaran".
|  | Layar: Pesan error "Ukuran berkas maksimal 2048 KB". |
| --- | --- |

Data: Tidak ada record transaksi baru di tabel payments.
Efek: Tidak ada file yang tersimpan di server.
|  | Pass |
| --- | --- |
|  | TC-PAY-003 |
|  | TS-PAY-002 |
|  | Upload berkas bukti transfer berformat .exe ditolak |
|  | Negatif · High |
|  | Precondition: Login Member U-05. |

Data: File F-03.
|  | 1. Buka form upload bukti bayar. |
| --- | --- |

2. Pilih file F-03.
3. Tekan "Kirim Bukti Pembayaran".
|  | Layar: Pesan error "Format berkas harus berupa JPG, PNG, atau WebP". |
| --- | --- |

Data: Status database tidak berubah.
Efek: Akses eksekusi ditolak.
|  | Pass |
| --- | --- |
|  | TC-PAY-004 |
|  | TS-PAY-003 |
|  | Admin menyetujui bukti transfer dan mengaktifkan membership |
|  | Positif · High |
|  | Precondition: Login Admin U-01. Ada transaksi ID TX-01 status pending milik U-05. |

Data: TX-01, PKG-02.
|  | 1. Buka menu Verifikasi Pembayaran. |
| --- | --- |

2. Klik detail transaksi TX-01.
3. Tekan tombol "Setujui Pembayaran".
4. Muat ulang halaman detail transaksi.
|  | Layar: Badge status berubah hijau "Disetujui". |
| --- | --- |

Data: Transaksi = verified, membership U-05 = active (durasi +30 hari).
Efek: Kartu pass digital U-05 aktif & email terkirim.
|  | Pass |
| --- | --- |
|  | TC-PAY-005 |
|  | TS-PAY-004 |
|  | Admin menolak bukti transfer palsu dengan alasan penolakan |
|  | Negatif · High |
|  | Precondition: Login Admin U-01. Ada transaksi TX-02 status pending milik U-05. |

Data: TX-02, Alasan: "Bukti transfer buram".
|  | 1. Buka halaman verifikasi pembayaran. |
| --- | --- |

2. Klik transaksi TX-02.
3. Masukkan alasan penolakan.
4. Tekan tombol "Tolak Pembayaran".
|  | Layar: Badge status menjadi merah "Ditolak". |
| --- | --- |

Data: Status transaksi = rejected, membership tetap inaktif.
Efek: Catatan alasan penolakan tersimpan di log riwayat.
|  | Pass |
| --- | --- |
|  | TC-PAY-006 |
|  | TS-PAY-005 |
|  | Persetujuan ganda pada transaksi yang sudah verified diblokir |
|  | Negatif · High |
|  | Precondition: Transaksi TX-01 sudah berstatus verified. Login Admin U-01. |

Data: TX-01.
|  | 1. Buka detail transaksi TX-01. |
| --- | --- |

2. Tekan tombol approve (via shortcut/API request langsung).
|  | Layar: Muncul peringatan "Transaksi telah diverifikasi sebelumnya". |
| --- | --- |

Data: Durasi membership tidak bertambah ganda.
Efek: Log audit tidak mencatat persetujuan baru.
|  | Pass |
| --- | --- |
|  | TC-MEM-001 |
|  | TS-MEM-001 |
|  | Member memilih paket membership aktif dan lanjut ke invoice |
|  | Positif · High |
|  | Precondition: Login Member U-05. Membuka katalog paket. |

Data: Paket PKG-02.
|  | 1. Buka menu Paket Keanggotaan. |
| --- | --- |

2. Pilih paket PKG-02.
3. Tekan tombol "Pilih Paket".
|  | Layar: Tampil halaman invoice checkout dengan nominal Rp350.000. |
| --- | --- |

Data: Draft checkout tersimpan di session.
Efek: Tombol upload bukti bayar siap diakses.
|  | Pass |
| --- | --- |
|  | TC-MEM-002 |
|  | TS-MEM-002 |
|  | Admin mengubah tarif paket membership dan data tersinkronisasi |
|  | Positif · Med |
|  | Precondition: Login Admin U-01. |

Data: PKG-02 diubah menjadi Rp375.000.
|  | 1. Buka menu Paket & Harga. |
| --- | --- |

2. Klik Edit pada PKG-02.
3. Ubah harga menjadi 375000.
4. Tekan Simpan Perubahan.
|  | Layar: Pesan toast "Paket berhasil diperbarui". |
| --- | --- |

Data: Kolom price pada paket berubah menjadi Rp375.000.
Efek: Transaksi lama tidak terpengaruh, katalog baru terupdate.
|  | Pass |
| --- | --- |
|  | TC-MEM-003 |
|  | TS-MEM-002 |
|  | Paket membership nonaktif disembunyikan dari katalog member |
|  | Positif · Med |
|  | Precondition: Paket PKG-02 diubah statusnya menjadi nonaktif oleh Admin. |

Data: PKG-02.
|  | 1. Login sebagai Member U-05. |
| --- | --- |

2. Buka menu Paket Keanggotaan.
|  | Layar: Paket PKG-02 tidak muncul dalam daftar pilihan. |
| --- | --- |

Data: Query DB memfilter where status = 'active'.
Efek: Member tidak dapat mengklik atau memilih paket nonaktif.
|  | Pass |
| --- | --- |
|  | TC-MEM-004 |
|  | TS-MEM-003 |
|  | Member hanya dapat melihat riwayat dan kartu miliknya sendiri |
|  | Negatif · Med |
|  | Precondition: Login Member U-05 (ID 5). Mengetahui URL invoice milik U-04 (ID 4). |

Data: URL /member/riwayat-transaksi/4/invoice.
|  | 1. Ketikkan URL invoice ID 4 langsung di address bar browser. |
| --- | --- |

2. Tekan Enter.
|  | Layar: Muncul pesan penolakan 403 Forbidden atau 404 Not Found. |
| --- | --- |

Data: Data pembayaran milik U-04 tidak ditampilkan.
Efek: Tidak ada kebocoran informasi finansial antar member.
|  | Pass |
| --- | --- |
|  | TC-PT-001 |
|  | TS-PT-001 |
|  | Pembelian paket sesi PT berhasil bila sisa masa aktif member cukup |
|  | Positif · High |
|  | Precondition: Login Member U-04 (Masa aktif 120 hari). Membuka paket PT. |

Data: Paket PT-01 (Syarat min: 30 hari).
|  | 1. Buka menu Paket Sesi PT. |
| --- | --- |

2. Pilih paket PT-01.
3. Tekan tombol "Beli Paket Sesi".
|  | Layar: Berhasil masuk ke halaman checkout paket sesi PT. |
| --- | --- |

Data: Data pemesanan kuota dibuat dengan status pending.
Efek: Member dapat melanjutkan ke upload bukti bayar PT.
|  | Pass |
| --- | --- |
|  | TC-PT-002 |
|  | TS-PT-002 |
|  | Pembelian paket PT ditolak bila sisa masa aktif member < syarat |
|  | Negatif · High |
|  | Precondition: Login Member U-06 (Masa aktif expired/0 hari). |

Data: Paket PT-01.
|  | 1. Buka menu Paket Sesi PT. |
| --- | --- |

2. Klik tombol "Beli Paket Sesi" pada PT-01.
|  | Layar: Peringatan "Sisa masa aktif keanggotaan Anda tidak mencukupi". |
| --- | --- |

Data: Tidak ada data pemesanan yang disimpan di database.
Efek: Transaksi dihentikan seketika.
|  | Pass |
| --- | --- |
|  | TC-PT-003 |
|  | TS-PT-003 |
|  | Kalender FullCalendar menampilkan slot waktu trainer dinamis |
|  | Positif · Med |
|  | Precondition: Login Member U-04. Membuka form booking PT. |

Data: Trainer U-02 (Budi).
|  | 1. Buka menu Booking PT. |
| --- | --- |

2. Pilih Trainer Budi Santoso.
3. Amati kalender jadwal.
|  | Layar: FullCalendar merender slot hijau (tersedia) dan abu-abu (terisi). |
| --- | --- |

Data: Request AJAX mengembalikan daftar slot jadwal U-02.
Efek: Pengguna hanya dapat mengklik slot yang terbuka.
|  | Pass |
| --- | --- |
|  | TC-PT-004 |
|  | TS-PT-004 |
|  | Member berhasil mengajukan booking sesi PT dan kuota berkurang 1 |
|  | Positif · High |
|  | Precondition: Member U-04 memiliki kuota 4 sesi. Slot 10:00 kosong. |

Data: Trainer U-02, Tanggal besok 10:00.
|  | 1. Pilih Trainer U-02. |
| --- | --- |

2. Pilih jam 10:00.
3. Tekan "Konfirmasi Booking".
4. Muat ulang halaman jadwal saya.
|  | Layar: Toast "Booking berhasil dikonfirmasi". |
| --- | --- |

Data: Sesi tersimpan status booked, kuota sesi U-04 menjadi 3.
Efek: Notifikasi booking terkirim ke trainer U-02.
|  | Pass |
| --- | --- |
|  | TC-PT-005 |
|  | TS-PT-005 |
|  | Booking simultan pada slot yang sama ditolak untuk orang kedua |
|  | Negatif · High |
|  | Precondition: Dua sesi browser (Member A dan Member B) memilih slot jam yang sama persis. |

Data: Slot 14:00 Trainer U-02.
|  | 1. Member A tekan "Konfirmasi Booking". |
| --- | --- |

2. Tepat bersamaan, Member B tekan "Konfirmasi Booking".
|  | Layar: Member A sukses; Member B menerima pesan "Slot telah terisi". |
| --- | --- |

Data: Hanya 1 sesi tercatat di database untuk slot tersebut.
Efek: Tidak ada jadwal bentrok (race condition prevented).
|  | Pass |
| --- | --- |
|  | TC-PT-006 |
|  | TS-PT-006 |
|  | Pengajuan booking sesi PT ke-4 ditolak saat ada 3 sesi aktif |
|  | Negatif · High |
|  | Precondition: Member U-07 telah memiliki 3 reservasi aktif (RES-001 s/d 003). |

Data: Trainer U-02, Jam 16:00.
|  | 1. Buka form booking PT. |
| --- | --- |

2. Pilih Trainer dan slot waktu.
3. Tekan "Konfirmasi Booking".
|  | Layar: Error "Batas maksimal 3 reservasi aktif telah tercapai". |
| --- | --- |

Data: Jumlah reservasi aktif U-07 tetap 3.
Efek: Kuota sesi tidak berkurang.
|  | Pass |
| --- | --- |
|  | TC-PT-007 |
|  | TS-PT-007 |
|  | Notifikasi pengajuan sesi muncul di dashboard trainer terkait |
|  | Positif · Med |
|  | Precondition: Member U-04 baru saja mem-booking sesi dengan Trainer U-02. |

Data: U-02.
|  | 1. Login sebagai Trainer U-02. |
| --- | --- |

2. Buka dashboard jadwal trainer.
|  | Layar: Tampil kartu sesi baru atas nama Amanda Putri jam 10:00. |
| --- | --- |

Data: Query sesi trainer memuat status pending/booked.
Efek: Tombol "Konfirmasi Sesi" aktif di sisi trainer.
|  | Pass |
| --- | --- |
|  | TC-ATT-001 |
|  | TS-ATT-001 |
|  | Scanner kamera browser berhasil mendeteksi dan mendekode QR pass |
|  | Positif · High |
|  | Precondition: Resepsionis membuka /admin/check-in dengan izin kamera aktif. |

Data: Kartu pass QR-01.
|  | 1. Buka halaman Scanner Check-in. |
| --- | --- |

2. Arahkan kode QR QR-01 ke webcam.
|  | Layar: Frame kamera menampilkan kotak hijau pendeteksian QR. |
| --- | --- |

Data: Library html5-qrcode mengirim payload string QR-01.
Efek: Request POST check-in otomatis dikirim ke backend.
|  | Pass |
| --- | --- |
|  | TC-ATT-002 |
|  | TS-ATT-002 |
|  | Presensi member aktif berhasil dan tercatat di database |
|  | Positif · High |
|  | Precondition: Halaman scanner aktif. Member U-04 berstatus aktif. |

Data: QR-01.
|  | 1. Pindai kode QR QR-01. |
| --- | --- |

2. Periksa modal hasil pemindaian.
|  | Layar: Indikator pop-up hijau "Check-in Berhasil: Amanda Putri". |
| --- | --- |

Data: Tabel check_ins bertambah 1 baris dengan waktu presensi riil.
Efek: Log kehadiran bertambah pada riwayat member.
|  | Pass |
| --- | --- |
|  | TC-ATT-003 |
|  | TS-ATT-003 |
|  | Presensi kartu member expired ditolak dengan peringatan merah |
|  | Negatif · High |
|  | Precondition: Halaman scanner aktif. Member U-06 berstatus expired. |

Data: QR-02.
|  | 1. Pindai kode QR QR-02. |
| --- | --- |
|  | Layar: Indikator merah "Check-in Ditolak: Keanggotaan Kedaluwarsa". |

Data: Tidak ada baris baru yang tersimpan di tabel check_ins.
Efek: Resepsionis diarahkan untuk meminta member perpanjang paket.
|  | Pass |
| --- | --- |
|  | TC-ATT-004 |
|  | TS-ATT-004 |
|  | Scan kedua dalam rentang < 15 menit ditolak (anti-duplikasi) |
|  | Negatif · High |
|  | Precondition: Member U-04 baru saja sukses check-in 2 menit yang lalu. |

Data: QR-01.
|  | 1. Pindai kembali kode QR QR-01 milik U-04. |
| --- | --- |
|  | Layar: Peringatan kuning "Member sudah melakukan presensi hari ini". |

Data: Jumlah baris presensi U-04 hari ini tetap 1.
Efek: Mencegah manipulasi metrik kehadiran gym.
|  | Pass |
| --- | --- |
|  | TC-SEC-001 |
|  | TS-SEC-001 |
|  | Akses langsung member ke URL admin diblokir dengan 403 Forbidden |
|  | Negatif · High |
|  | Precondition: Login sebagai Member U-04. |

Data: URL /admin/member-management.
|  | 1. Ketikkan URL /admin/member-management di address bar browser. |
| --- | --- |

2. Tekan Enter.
|  | Layar: Menampilkan halaman error resmi "403 Forbidden / Akses Ditolak". |
| --- | --- |

Data: Tidak ada data administratif yang bocor.
Efek: Session log mencatat upaya akses tidak berhak.
|  | Pass |
| --- | --- |
|  | TC-SEC-002 |
|  | TS-SEC-002 |
|  | Permintaan akses tamu tanpa login dialihkan ke halaman login |
|  | Negatif · High |
|  | Precondition: Browser dalam mode Incognito (belum terautentikasi). |

Data: URL /member/dashboard.
|  | 1. Buka alamat /member/dashboard langsung di address bar. |
| --- | --- |
|  | Layar: Dialihkan (redirect) ke /login dengan flash error. |

Data: Sesi pengguna bernilai null.
Efek: Halaman dashboard terproteksi penuh.
|  | Pass |
| --- | --- |
|  | TC-NOTIF-001 |
|  | TS-NOTIF-001 |
|  | Email konfirmasi aktivasi diproses asinkron dan diterima di Mailtrap |
|  | Positif · Med |
|  | Precondition: Transaksi TX-01 disetujui Admin. Worker queue berjalan. |

Data: Email dimas@test.com.
|  | 1. Admin menyetujui transaksi TX-01. |
| --- | --- |

2. Buka inbox Mailtrap akun pengujian.
|  | Layar: Admin menerima pesan sukses tanpa delay loading (< 1.5 detik). |
| --- | --- |

Data: Record job di tabel jobs diproses dan terhapus.
Efek: Kotak masuk Mailtrap menerima email subjek "Aktivasi Membership".
|  | Pass |
| --- | --- |
|  | TC-NOTIF-002 |
|  | TS-NOTIF-002 |
|  | Transaksi approval tetap berhasil jika layanan SMTP timeout |
|  | Negatif · Med |
|  | Precondition: Konfigurasi SMTP Mailtrap dimatikan/port diblokir sementara. |

Data: Transaksi TX-03.
|  | 1. Admin menekan tombol "Setujui Pembayaran" pada TX-03. |
| --- | --- |
|  | Layar: UI tetap menampilkan pesan sukses verifikasi. |

Data: Status pembayaran tetap verified dan keanggotaan aktif di DB.
Efek: Kegagalan pengiriman email dicatat di tabel failed_jobs.
|  | Pass |
| --- | --- |

4.     Tabel Spesifikasi Test Case Rinci (Format Kartu)
Seluruh 25 kasus uji dari 15 tugas WBS berstatus Ditinjau dijabarkan secara rinci ke dalam format kartu spesifikasi pengujian.

## 4.1  odul Pembayaran & Verifikasi Transfer (PAY)

Kartu Uji 1: Upload Bukti Transfer Valid
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PAY-001 |
|  | Scenario Reference |
|  | TS-PAY-001 (Ref: WBS 3.2.1, 3.2.2, User Story US-04) |
|  | Judul / Objective |
|  | Membuktikan bahwa member baru berhasil mengunggah berkas bukti transfer yang valid dan sistem membuat catatan transaksi berstatus pending. |
|  | Tipe & Prioritas |
|  | Positif · High |
|  | Precondition |
|  | 1. Pengguna login sebagai Member Dimas (U-05). |

2. Telah memilih paket Basic Monthly Pass (PKG-02) dan berada di laman konfirmasi pembayaran.
|  | Test Data |
| --- | --- |
|  | User: dimas@test.com (U-05), Paket: PKG-02 (Rp350.000), Berkas: F-01 (bukti_transfer_valid.webp, 120 KB), Bank Tujuan: BCA. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka formulir unggah bukti pembayaran pada URL transaksi. |

2. Pilih bank tujuan transfer: "BCA FitCore".
3. Klik input file dan pilih berkas F-01.
4. Masukkan nama pengirim: "Dimas Anggara".
5. Tekan tombol Kirim Bukti Pembayaran.
6. Muat ulang halaman riwayat transaksi.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Tampil pesan toast hijau "Bukti pembayaran berhasil diunggah dan sedang ditinjau"; status transaksi berlabel badge kuning "Pending / Menunggu Verifikasi". |

2) Lapisan Data Persistence: Record baru tercipta pada tabel payments dengan status = 'pending', amount = 350000, dan proof_file_path valid di storage.
3) Lapisan Efek Lanjutan: Antrean notifikasi verifikasi baru dikirimkan ke panel dasbor Admin.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Browser desktop/mobile, storage lokal Laravel tersambung (storage:link), sesi login member baru. |
|  | Hasil Aktual (Actual Result) |
|  | Berkas terunggah ke direktori storage, transaksi berstatus pending tersimpan di DB, dan UI menampilkan badge status kuning. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 2: Penolakan Bukti Transfer Melebihi Batas Ukuran (Max 2MB)
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PAY-002 |
|  | Scenario Reference |
|  | TS-PAY-002 (Ref: WBS 3.2.3, Validation Rule VR-Upload-01) |
|  | Judul / Objective |
|  | Membuktikan bahwa sistem menolak pengunggahan berkas bukti transfer dengan ukuran > 2048 KB dengan pesan validasi yang jelas. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | Pengguna login sebagai Member Dimas (U-05) dan berada pada form upload pembayaran. |
|  | Test Data |
|  | User: dimas@test.com, Berkas: F-02 (bukti_transfer_large.png, ukuran 2.5 MB). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka form upload bukti pembayaran. |

2. Pilih berkas F-02 yang berukuran 2.5 MB.
3. Tekan tombol Kirim Bukti Pembayaran.
4. Periksa respons antarmuka dan periksa storage server.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Form tidak terkirim; muncul teks error merah di bawah input berkas: "Ukuran berkas bukti transfer tidak boleh melebihi 2048 KB". |

2) Lapisan Data Persistence: Tidak ada baris transaksi baru yang tersimpan di tabel payments.
3) Lapisan Efek Lanjutan: Penyimpanan storage server tidak bertambah (file sampah tidak tertinggal di server).
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | File dummy pengujian berukuran 2.5 MB, browser modern. |
|  | Hasil Aktual (Actual Result) |
|  | Validasi form menolak submit, pesan error 2048 KB tampil di layar, dan DB tidak mencatat transaksi baru. |
|  | Status Eksekusi |
|  | PASS |

Kartu Uji 3: Penolakan Ekstensi Berkas Terlarang (.exe)
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PAY-003 |
|  | Scenario Reference |
|  | TS-PAY-002 (Ref: WBS 3.2.3, Security Rule SR-File-01) |
|  | Judul / Objective |
|  | Membuktikan bahwa sistem menolak berkas non-gambar (format biner .exe) demi mencegah serangan malware/arbitrary upload. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | Pengguna login sebagai Member Dimas (U-05) pada form upload pembayaran. |
|  | Test Data |
|  | User: dimas@test.com, Berkas: F-03 (dokumen_terlarang.exe, MIME: application/x-msdownload). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka form upload bukti pembayaran. |

2. Unggah file biner terlarang F-03.
3. Tekan tombol Kirim Bukti Pembayaran.
4. Amati respons validasi MIME-type backend.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Muncul pesan galat merah: "Format berkas harus berupa JPG, JPEG, PNG, atau WebP". |

2) Lapisan Data Persistence: Status database tidak berubah, transaksi gagal dibuat.
3) Lapisan Efek Lanjutan: Berkas biner di-reject langsung oleh middleware validasi sebelum tersentuh file system.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | File binary mock .exe, aturan validasi mimes:jpg,jpeg,png,webp. |
|  | Hasil Aktual (Actual Result) |
|  | Sistem menolak unggahan secara ketat dan memberikan peringatan format yang diperbolehkan. |
|  | Status Eksekusi |
|  | PASS |

Kartu Uji 4: Verifikasi Persetujuan Pembayaran & Aktivasi Member
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PAY-004 |
|  | Scenario Reference |
|  | TS-PAY-003 (Ref: WBS 3.3.1, 3.3.2, Business Rule BR-Payment-01) |
|  | Judul / Objective |
|  | Membuktikan bahwa persetujuan pembayaran oleh Admin mengaktifkan keanggotaan member dengan kalkulasi tanggal yang tepat. |
|  | Tipe & Prioritas |
|  | Positif · High |
|  | Precondition |
|  | 1. Pengguna login sebagai Admin (admin@gym.test). |

2. Terdapat data transaksi ID TX-01 milik Member Dimas (U-05) berstatus pending.
3. Paket yang dibeli adalah Basic Monthly Pass (PKG-02) dengan durasi 30 hari.
4. Member Dimas saat ini berstatus keanggotaan inactive.
|  | Test Data |
| --- | --- |
|  | User Admin: U-01, Member: U-05, Transaksi ID: TX-01, Paket: PKG-02 (Rp350.000). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka navigasi menu Verifikasi Pembayaran di portal admin. |

2. Klik tombol Detail / Periksa pada baris transaksi ID TX-01.
3. Periksa foto bukti transfer pada modal pop-up.
4. Tekan tombol Setujui Pembayaran.
5. Muat ulang (reload) halaman transaksi untuk verifikasi data permanen.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Tampil pesan alert hijau "Pembayaran berhasil diverifikasi", badge status transaksi berubah menjadi hijau "Disetujui", tombol Setujui dinonaktifkan. |

2) Lapisan Data Persistence: Status pada tabel payments tersimpan verified; status tabel memberships berubah menjadi active; kolom start_date tercatat hari ini dan end_date bertambah tepat +30 hari.
3) Lapisan Efek Lanjutan: Kartu pass digital member Dimas aktif; sistem memasukkan job pengiriman email aktivasi ke antrean queue.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Web browser Chrome desktop, database seeding aktif, role akun admin, driver queue lokal database. |
|  | Hasil Aktual (Actual Result) |
|  | Status transaksi berhasil diverifikasi, masa aktif member bertambah tepat 30 hari di database, dan badge UI hijau. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 5: Penolakan Pembayaran Bukti Transfer Tidak Valid
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PAY-005 |
|  | Scenario Reference |
|  | TS-PAY-004 (Ref: WBS 3.3.3, Business Rule BR-Payment-02) |
|  | Judul / Objective |
|  | Membuktikan bahwa Admin dapat menolak transaksi dengan mencantumkan alasan penolakan, serta keanggotaan member tetap nonaktif. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | Login sebagai Admin U-01. Ada transaksi TX-02 status pending milik Member Dimas (U-05). |
|  | Test Data |
|  | Admin: U-01, Transaksi: TX-02, Alasan Penolakan: "Nominal transfer tidak sesuai (kurang Rp50.000)". |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka menu Verifikasi Pembayaran Admin. |

2. Klik tombol Detail pada transaksi TX-02.
3. Tekan tombol Tolak Pembayaran.
4. Pada modal konfirmasi, ketikkan alasan: "Nominal transfer tidak sesuai (kurang Rp50.000)".
5. Tekan tombol Kirim Penolakan.
6. Muat ulang halaman detail transaksi.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Toast alert merah "Transaksi berhasil ditolak"; badge status berubah merah "Ditolak" dengan catatan alasan tampil di bawahnya. |

2) Lapisan Data Persistence: Tabel payments mencatat status = 'rejected', kolom rejection_reason terisi alasan; status membership U-05 tetap inactive.
3) Lapisan Efek Lanjutan: QR digital pass member tetap terblokir; notifikasi penolakan tercatat di riwayat member.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Akun admin aktif, modal dialog penolakan dengan field alasan wajib (required). |
|  | Hasil Aktual (Actual Result) |
|  | Transaksi berstatus rejected, alasan penolakan tersimpan di database, dan membership tidak aktif. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 6: Pencegahan Persetujuan Ganda (Idempotency Guard)
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PAY-006 |
|  | Scenario Reference |
|  | TS-PAY-005 (Ref: WBS 3.3.1, Risk RSK-02) |
|  | Judul / Objective |
|  | Membuktikan bahwa request verifikasi ganda pada transaksi yang sudah berstatus verified diblokir dan tidak menambah durasi membership ganda. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | Transaksi TX-01 sudah berstatus verified dan masa aktif U-05 sudah terhitung 30 hari. |
|  | Test Data |
|  | Admin: U-01, Target: TX-01 (Verified). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Login sebagai Admin U-01. |

2. Buka URL endpoint verifikasi transaksi TX-01 secara langsung melalui request POST ulang.
3. Amati pesan penanganan sistem.
4. Periksa durasi masa aktif member pada tabel memberships.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Tampil pesan peringatan "Transaksi ini telah diverifikasi sebelumnya" atau tombol approve dinonaktifkan (disabled). |

2) Lapisan Data Persistence: Kolom end_date member U-05 tidak mengalami penambahan 30 hari kedua (tetap 30 hari, bukan 60 hari).
3) Lapisan Efek Lanjutan: Tidak ada pengiriman email ganda ke member.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Database transaction lock / guard condition pada method controller verifikasi. |
|  | Hasil Aktual (Actual Result) |
|  | Sistem menolak aksi verifikasi ulang dan durasi paket membership member tidak bertambah ganda. |
|  | Status Eksekusi |
|  | PASS |
|  | 4.2  Modul Keanggotaan & Paket Membership (MEM) |

 
Kartu Uji 7: Pemilihan Paket Membership dan Checkout Invoice
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-MEM-001 |
|  | Scenario Reference |
|  | TS-MEM-001 (Ref: WBS 3.1.1, 3.1.2, US-03) |
|  | Judul / Objective |
|  | Membuktikan bahwa member dapat memilih paket keanggotaan aktif dari katalog dan diarahkan ke invoice checkout dengan rincian biaya yang tepat. |
|  | Tipe & Prioritas |
|  | Positif · High |
|  | Precondition |
|  | Member Dimas (U-05) login dan berada di halaman Katalog Paket Membership. |
|  | Test Data |
|  | User: dimas@test.com, Paket Dipilih: PKG-02 (Basic Monthly Pass, Rp350.000). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka menu Paket Membership. |

2. Temukan kartu paket "Basic Monthly Pass" (PKG-02).
3. Tekan tombol Pilih Paket.
4. Amati halaman checkout dan rincian harga.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Pengguna dialihkan ke halaman /member/checkout; tampil rincian nama paket "Basic Monthly Pass", durasi "30 Hari", dan total bayar "Rp 350.000". |

2) Lapisan Data Persistence: Data draft order tersimpan sementara di session/tabel transaksi dengan referensi package_id = PKG-02.
3) Lapisan Efek Lanjutan: Rekening pembayaran gym (BCA/Mandiri) ditampilkan dengan benar.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Seeder paket membership aktif. |
|  | Hasil Aktual (Actual Result) |
|  | Pengguna berhasil diarahkan ke laman checkout dengan nominal tepat Rp350.000 tanpa manipulasi harga. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 8: Perubahan Tarif Paket oleh Admin dan Sinkronisasi Data
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-MEM-002 |
|  | Scenario Reference |
|  | TS-MEM-002 (Ref: WBS 3.4.1, Task Admin) |
|  | Judul / Objective |
|  | Membuktikan bahwa Admin dapat mengubah harga paket dan perubahan tersebut tersinkronisasi langsung pada katalog member. |
|  | Tipe & Prioritas |
|  | Positif · Medium |
|  | Precondition |
|  | Login sebagai Admin U-01 pada menu Manajemen Paket Membership. |
|  | Test Data |
|  | Admin: U-01, Paket: PKG-02, Harga Baru: Rp375.000 (Harga awal: Rp350.000). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka menu Manajemen Paket di panel Admin. |

2. Klik tombol Edit pada paket PKG-02.
3. Ubah kolom input harga menjadi 375000.
4. Tekan tombol Simpan Perubahan.
5. Buka tab browser baru, login sebagai Member U-05, lalu buka katalog paket.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Panel admin memunculkan toast "Data paket berhasil diperbarui"; pada portal member harga kartu PKG-02 tampil "Rp 375.000". |

2) Lapisan Data Persistence: Kolom price pada tabel membership_packages untuk ID PKG-02 tersimpan 375000.
3) Lapisan Efek Lanjutan: Transaksi lama yang sudah dibuat sebelum perubahan tarif tidak terpengaruh nominalnya.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Hak akses kelola paket admin. |
|  | Hasil Aktual (Actual Result) |
|  | Harga paket sukses diperbarui di DB dan langsung tercermin di tampilan katalog publik member. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 9: Penyembunyian Paket Nonaktif dari Katalog Member
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-MEM-003 |
|  | Scenario Reference |
|  | TS-MEM-002 (Ref: WBS 3.4.1, BR-Package-02) |
|  | Judul / Objective |
|  | Membuktikan bahwa paket membership dengan status nonaktif disembunyikan dari katalog pilihan member. |
|  | Tipe & Prioritas |
|  | Positif · Medium |
|  | Precondition |
|  | Admin telah menonaktifkan status paket Promo Spesial (status = 'inactive'). |
|  | Test Data |
|  | Member: U-05, Paket Nonaktif: PKG-PROMO. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Login sebagai Member Dimas (U-05). |

2. Buka menu Paket Membership.
3. Lakukan pencarian nama paket PKG-PROMO.
4. Coba akses langsung URL checkout /member/checkout?package=PKG-PROMO.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Kartu PKG-PROMO tidak ditampilkan di daftar katalog; akses URL langsung menghasilkan alert error "Paket ini sedang tidak tersedia". Ini mencegah checkout paket kedaluwarsa. |

2) Lapisan Data Persistence: Kueri katalog mengeksekusi where('is_active', true).
3) Lapisan Efek Lanjutan: Tidak ada transaksi baru yang dapat dibuat untuk paket nonaktif tersebut.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Fixture database paket nonaktif. |
|  | Hasil Aktual (Actual Result) |
|  | Paket nonaktif tidak muncul pada UI member dan upaya URL bypass berhasil dicegah sistem. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 10: Proteksi Isolasi Data Riwayat Member (Anti-IDOR)
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-MEM-004 |
|  | Scenario Reference |
|  | TS-MEM-003 (Ref: WBS 5.1.1, Security Rule SR-Data-01) |
|  | Judul / Objective |
|  | Membuktikan bahwa Member A ditolak dan dilarang melihat berkas faktur/invoice riwayat pembayaran milik Member B (Insecure Direct Object References). |
|  | Tipe & Prioritas |
|  | Negatif · Medium |
|  | Precondition |
|  | Pengguna login sebagai Member Dimas (U-05 / ID: 5). Terdapat faktur transaksi milik Amanda (U-04 / ID: 4) dengan URL /member/invoices/4. |
|  | Test Data |
|  | User: dimas@test.com (ID 5), Target URL Ilegal: /member/invoices/4. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Login sebagai Member Dimas (U-05). |

2. Ketik URL /member/invoices/4 langsung di address bar browser.
3. Tekan Enter.
4. Amati respons halaman.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Sistem menampilkan halaman error "403 Forbidden / Anda tidak memiliki akses ke faktur ini" atau 404 Not Found. |

2) Lapisan Data Persistence: Informasi data sensitif (nama, riwayat transfer, alamat Amanda) tidak bocor.
3) Lapisan Efek Lanjutan: Log security mencatat upaya akses data lintas entitas pengguna.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Policy otorisasi Eloquent: Gate::authorize('view', $invoice). |
|  | Hasil Aktual (Actual Result) |
|  | Akses diblokir dengan kode status HTTP 403 Forbidden dan tidak menampilkan data invoice Amanda. |
|  | Status Eksekusi |
|  | PASS |
|  | 4.3 Modul Layanan Personal Trainer & Booking (PT) |

Kartu Uji 11: Pembelian Paket Sesi PT dengan Sisa Masa Aktif Memadai
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PT-001 |
|  | Scenario Reference |
|  | TS-PT-001 (Ref: WBS 3.5.1, 3.5.2, BR-PT-01) |
|  | Judul / Objective |
|  | Membuktikan bahwa member dengan masa aktif membership ≥\ge≥ 30 hari berhasil memesan paket sesi latihan PT. |
|  | Tipe & Prioritas |
|  | Positif · High |
|  | Precondition |
|  | Member Amanda (U-04) login, memiliki masa aktif keanggotaan 120 hari (syarat min. paket PT: 30 hari). |
|  | Test Data |
|  | User: amanda@test.com, Paket: PT-01 (Personal Trainer 4 Sesi, Rp600.000). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka menu Paket Personal Trainer. |

2. Klik tombol Beli Paket Sesi pada paket PT-01.
3. Amati formulir checkout pemesanan sesi PT.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Berhasil masuk ke laman checkout sesi PT; tombol bayar aktif. |

2) Lapisan Data Persistence: Sistem mencatat pesanan paket PT baru dengan status pending_payment.
3) Lapisan Efek Lanjutan: Alur berlanjut ke pengunggahan bukti transfer sesi PT.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Akun member dengan membership valid > 30 hari. |
|  | Hasil Aktual (Actual Result) |
|  | Pengguna diizinkan melanjutkan pesanan paket PT karena syarat durasi terpenuhi. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 12: Penolakan Beli Paket PT Saat Masa Aktif Member Kurang
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PT-002 |
|  | Scenario Reference |
|  | TS-PT-002 (Ref: WBS 3.5.2, Business Rule BR-PT-02) |
|  | Judul / Objective |
|  | Membuktikan bahwa sistem menolak pembelian paket sesi PT apabila sisa masa aktif membership member lebih kecil dari masa berlaku paket PT. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | Login sebagai Member Siti (U-06) yang status keanggotaannya expired (0 hari). Paket PT-01 mewajibkan keanggotaan aktif minimal 30 hari. |
|  | Test Data |
|  | User: siti.expired@test.com, Paket: PT-01. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka menu Paket Personal Trainer. |

2. Klik tombol Beli Paket Sesi pada paket PT-01.
3. Amati pesan feedback sistem.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Muncul modal peringatan oranye: "Masa aktif keanggotaan gym Anda tidak mencukupi untuk mengambil paket PT ini. Silakan perpanjang membership terlebih dahulu." Tombol checkout dinonaktifkan. |

2) Lapisan Data Persistence: Tidak ada record pesanan baru pada tabel pt_orders.
3) Lapisan Efek Lanjutan: Tidak terjadi transaksi finansial yang menggantung.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Akun member expired/inaktif di database. |
|  | Hasil Aktual (Actual Result) |
|  | Tombol pembelian diblokir dan muncul instruksi perpanjangan membership. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 13: Render Kalender FullCalendar Slot Jadwal Trainer Dinamis
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PT-003 |
|  | Scenario Reference |
|  | TS-PT-003 (Ref: WBS 4.3.1, Komponen Interaktif) |
|  | Judul / Objective |
|  | Membuktikan bahwa FullCalendar merender slot jam ketersediaan trainer secara real-time dan membedakan slot kosong serta slot terisi. |
|  | Tipe & Prioritas |
|  | Positif · Medium |
|  | Precondition |
|  | Member Amanda (U-04) memiliki sisa kuota sesi PT aktif. Trainer Budi Santoso (U-02) telah menetapkan jadwal kerja harian. |
|  | Test Data |
|  | Trainer: budi@gym.test (U-02), Rentang: Minggu berjalan. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka menu Booking Jadwal PT. |

2. Pilih trainer Budi Santoso dari dropdown.
3. Amati kalender interaktif yang dirender oleh library FullCalendar.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Kalender menampilkan event blok hijau (Available) pada jam kosong dan blok abu-abu (Booked) pada jam yang sudah terisi. |

2) Lapisan Data Persistence: Endpoint AJAX /api/trainers/2/slots mengembalikan response JSON array jadwal dengan status HTTP 200.
3) Lapisan Efek Lanjutan: Member hanya dapat mengklik slot yang berwarna hijau.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Library frontend FullCalendar aktif, API endpoint slot trainer berfungsi. |
|  | Hasil Aktual (Actual Result) |
|  | Grid kalender berhasil merender slot ketersediaan trainer sesuai jadwal riil database. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 14: Pengajuan Booking Sesi PT & Pemotongan Kuota 1 Sesi
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PT-004 |
|  | Scenario Reference |
|  | TS-PT-004 (Ref: WBS 4.4.1, BR-PT-03) |
|  | Judul / Objective |
|  | Membuktikan bahwa pengajuan reservasi sesi PT berhasil mem-booking slot jam dan memotong sisa kuota sesi member sebanyak tepat 1 sesi. |
|  | Tipe & Prioritas |
|  | Positif · High |
|  | Precondition |
|  | Member Amanda (U-04) memiliki kuota 4 sesi PT aktif. Slot besok jam 10:00 berstatus kosong. |
|  | Test Data |
|  | User: amanda@test.com, Trainer: U-02, Jadwal: Besok pukul 10:00 - 11:00 WIB. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka form booking PT dan pilih Trainer Budi. |

2. Klik slot jam 10:00 pada kalender.
3. Masukkan catatan latihan: "Latihan otot dada (Chest Day)".
4. Tekan tombol Konfirmasi Reservasi.
5. Muat ulang halaman dasbor profil member.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Alert sukses "Sesi PT berhasil dijadwalkan"; widget kuota sesi member berubah menampilkan angka 3 sesi tersisa. |

2) Lapisan Data Persistence: Baris baru dibuat di tabel pt_sessions (status = 'booked'); kolom remaining_sessions milik Amanda di tabel memberships berkurang dari 4 menjadi 3.
3) Lapisan Efek Lanjutan: Notifikasi booking baru terkirim ke Trainer Budi; slot jam 10:00 otomatis berubah menjadi abu-abu (Booked).
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Database transaksi atomik DB transaction untuk konsistensi booking dan kuota. |
|  | Hasil Aktual (Actual Result) |
|  | Reservasi tercatat, kuota sesi terpotong 1, dan jadwal trainer ter-update seketika. |
|  | Status Eksekusi |
|  | PASS |
|  | Kartu Uji 15: Penanganan Booking Simultan Slot Sama (Race Condition) |

Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PT-005 |
|  | Scenario Reference |
|  | TS-PT-005 (Ref: WBS 4.4.1, Risk RSK-01) |
|  | Judul / Objective |
|  | Membuktikan sistem mencegah double booking jika dua member menekan tombol konfirmasi pada slot jam yang sama secara simultan. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | Slot Trainer Budi hari Rabu jam 14:00 berstatus kosong. Member A dan Member B membuka slot tersebut bersamaan. |
|  | Test Data |
|  | Slot ID: SLOT-1400-BUDI, Member A: Amanda, Member B: Farhan. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Member A dan Member B sama-sama berada di modal konfirmasi jam 14:00. |

2. Member A menekan "Konfirmasi Booking".
3. Dalam selang waktu milidetik, Member B menekan "Konfirmasi Booking".
4. Periksa respons pada kedua layar browser.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Member A menerima pesan sukses; Member B menerima pesan galat "Maaf, slot waktu ini baru saja diambil oleh member lain. Silakan pilih slot lain." |

 2) Lapisan Data Persistence: Hanya ada 1 record sesi tersimpan di tabel pt_sessions untuk slot jam tersebut (terhindar dari bentrok data).
3) Lapisan Efek Lanjutan: Kuota Member B tidak terpotong sama sekali.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Kunci database lockForUpdate() atau unique composite index pada (trainer_id, session_time). |
|  | Hasil Aktual (Actual Result) |
|  | Double-booking tertahan oleh database constraint dan pengaju kedua mendapatkan pesan kegagalan yang aman. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 16: Pembatasan Maksimal 3 Reservasi Sesi Latihan PT
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PT-006 |
|  | Scenario Reference |
|  | TS-PT-006 (Ref: WBS 4.4.2, Tantangan Bisnis Kasus FitCore) |
|  | Judul / Objective |
|  | Membuktikan bahwa sistem menolak pengajuan booking sesi PT ke-4 apabila member masih memiliki 3 reservasi aktif. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | 1. Pengguna login sebagai Member Farhan (U-07). |

2. Member telah memiliki 3 sesi PT berstatus booked/active (RES-001, RES-002, RES-003).
3. Sisa kuota sesi PT member masih tersisa >0> 0>0.
|  | Test Data |
| --- | --- |
|  | User: farhan.pt3@test.com, Trainer: Budi Santoso, Jadwal Sesi: 2026-11-09 14:00. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka menu Booking PT di portal member. |

2. Pilih Trainer Budi Santoso.
3. Masukkan jadwal sesi tanggal besok jam 14:00.
4. Tekan tombol Konfirmasi Booking.
5. Muat ulang halaman daftar Jadwal Sesi Saya.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Muncul pesan galat merah dengan teks "Batas maksimal 3 reservasi aktif telah tercapai. Harap selesaikan sesi Anda terlebih dahulu."; form tidak melakukan redirect sukses. |

2) Lapisan Data Persistence: Setelah halaman dimuat ulang, jumlah baris reservasi pada tabel pt_sessions milik Farhan tetap berjumlah tepat 3 (tidak bertambah menjadi 4).
3) Lapisan Efek Lanjutan: Kuota sesi PT member Farhan tidak terpotong; Trainer Budi tidak menerima notifikasi booking baru.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Fixture data 3 sesi aktif ter-setup via database seeder/API, browser desktop/mobile. |
|  | Hasil Aktual (Actual Result) |
|  | Dialog peringatan batas maksimal 3 sesi muncul, dan jumlah reservasi aktif pada database tetap bernilai 3. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 17: Notifikasi Pengajuan Sesi Muncul di Dasbor Trainer
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-PT-007 |
|  | Scenario Reference |
|  | TS-PT-007 (Ref: WBS 4.4.3, Fitur Notifikasi) |
|  | Judul / Objective |
|  | Membuktikan bahwa booking sesi baru oleh member langsung memunculkan notifikasi dan kartu janji temu pada dasbor trainer terkait. |
|  | Tipe & Prioritas |
|  | Positif · Medium |
|  | Precondition |
|  | Member Amanda (U-04) baru saja sukses memesan sesi latihan dengan Trainer Budi (U-02). |
|  | Test Data |
|  | Trainer: budi@gym.test (U-02), Member: amanda@test.com. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Login sebagai Trainer Budi Santoso (U-02). |

2. Buka halaman Dasbor Jadwal Pelatih.
3. Klik ikon lonceng notifikasi pada pojok kanan atas.
4. Periksa daftar jadwal latihan harian.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Badge lonceng notifikasi menampilkan angka counter merah; tampil item "Amanda Putri memesan sesi latihan besok jam 10:00"; kartu jadwal jam 10:00 terisi. |

2) Lapisan Data Persistence: Tabel notifications mencatat entri notifikasi baru dengan notifiable_id = 2 (ID Trainer Budi).
3) Lapisan Efek Lanjutan: Trainer dapat menekan tombol konfirmasi kehadiran member.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Akun role trainer, sistem database notification Laravel. |
|  | Hasil Aktual (Actual Result) |
|  | Notifikasi masuk secara instan ke dasbor trainer dan kartu jadwal terisi rapi. |
|  | Status Eksekusi |
|  | PASS |



## 4.4 Modul Presensi & QR Scanner Kamera (ATT)

Kartu Uji 18: Scanner Kamera Browser Mendeteksi & Mendekode QR Pass
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-ATT-001 |
|  | Scenario Reference |
|  | TS-ATT-001 (Ref: WBS 2.5.2, Library html5-qrcode) |
|  | Judul / Objective |
|  | Membuktikan bahwa pustaka scanner kamera browser berhasil meminta izin kamera dan mendekode token payload kode QR secara real-time. |
|  | Tipe & Prioritas |
|  | Positif · High |
|  | Precondition |
|  | Login sebagai Resepsionis/Admin pada /admin/check-in. Izin akses kamera browser telah diaktifkan (Allow Camera). |
|  | Test Data |
|  | Kamera: Webcam Device 0, Kode QR: QR-01 (String: FC-PASS-U04-ACTIVE). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka halaman /admin/check-in. |

2. Klik tombol Aktifkan Kamera Scanner.
3. Arahkan kode QR pass QR-01 ke depan kamera scanner.
4. Amati tangkapan frame dan console browser.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Frame preview kamera aktif menampilkan bingkai bidik hijau (scanning target box); saat QR terdeteksi, bingkai berkedip sukses dan membunyikan nada bip. |

2) Lapisan Data Persistence: Payload FC-PASS-U04-ACTIVE terkirim otomatis melalui HTTP POST ke endpoint /admin/attendance/scan.
3) Lapisan Efek Lanjutan: Antarmuka membuka modal identitas pemilik kartu QR.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Perangkat webcam fungsional atau simulasi virtual camera browser, protokol HTTPS/localhost. |
|  | Hasil Aktual (Actual Result) |
|  | Kamera berhasil mengenali string QR dalam waktu < 1 detik dan mengirimkan payload ke backend. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 19: Presensi Member Aktif Berhasil & Tersimpan di Database
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-ATT-002 |
|  | Scenario Reference |
|  | TS-ATT-002 (Ref: WBS 2.5.3, Presensi Sukses) |
|  | Judul / Objective |
|  | Membuktikan bahwa pemindaian kartu QR milik member berstatus aktif berhasil diverifikasi dan mencatat log kehadiran pada basis data. |
|  | Tipe & Prioritas |
|  | Positif · High |
|  | Precondition |
|  | Scanner aktif di portal resepsionis. Member Amanda (U-04) memiliki keanggotaan aktif dan belum melakukan presensi hari ini. |
|  | Test Data |
|  | Token: QR-01 (amanda@test.com, Paket Gold Aktif). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Pindai token QR QR-01 pada scanner resepsionis. |

2. Amati pop-up hasil verifikasi identitas.
3. Buka halaman Log Presensi Harian resepsionis.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Muncul modal pop-up hijau terang dengan teks: "CHECK-IN BERHASIL: Amanda Putri (Member Gold - Aktif)"; foto profil member tampil. |

2) Lapisan Data Persistence: Baris baru tercatat di tabel check_ins dengan user_id = 4, check_in_time = NOW(), status = 'approved'.
3) Lapisan Efek Lanjutan: Metrik total kunjungan gym hari ini di dashboard bertambah +1.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Akun member aktif, role resepsionis/admin. |
|  | Hasil Aktual (Actual Result) |
|  | Presensi tercatat secara instan di database, modal hijau muncul, dan counter pengunjung bertambah. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 20: Penolakan Presensi Kartu Member Kedaluwarsa (Expired)
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-ATT-003 |
|  | Scenario Reference |
|  | TS-ATT-003 (Ref: WBS 2.5.3, BR-Attendance-01) |
|  | Judul / Objective |
|  | Membuktikan bahwa pemindaian kartu QR milik member kedaluwarsa ditolak dengan peringatan merah dan tidak disimpan ke log kehadiran gym. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | Scanner resepsionis aktif. Member Siti (U-06) masa aktifnya telah berakhir kemarin (status = 'expired'). |
|  | Test Data |
|  | Token: QR-02 (siti.expired@test.com). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Pindai token QR QR-02 pada scanner resepsionis. |

2. Amati respons modal pop-up dan suara indikator penolakan.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Muncul modal pop-up merah tebal dengan bunyi buzzer penolakan dan teks: "CHECK-IN DITOLAK: Keanggotaan Siti Aisyah Kedaluwarsa sejak 07/11/2026"; terdapat tombol "Perpanjang Membership". |

2) Lapisan Data Persistence: Tidak ada baris presensi baru di tabel check_ins untuk user ID Siti.
3) Lapisan Efek Lanjutan: Turnstile/pintu masuk gym tidak terbuka; log keamanan mencatat percobaan masuk member inaktif.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Akun member dengan status expired. |
|  | Hasil Aktual (Actual Result) |
|  | Sistem menolak check-in member expired secara visual dan data presensi tidak bertambah. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 21: Pencegahan Duplikasi Pemindaian QR Check-in (< 15 Menit)
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-ATT-004 |
|  | Scenario Reference |
|  | TS-ATT-004 (Ref: WBS 2.5.4, Risk RSK-03) |
|  | Judul / Objective |
|  | Membuktikan bahwa pemindaian kartu QR kedua dalam interval < 15 menit ditolak untuk mencegah duplikasi presensi (anti-duplikasi). |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | 1. Pengguna login sebagai Resepsionis/Admin pada scanner browser /admin/check-in. |

2. Member Amanda (U-04) telah sukses melakukan check-in 2 menit yang lalu.
|  | Test Data |
| --- | --- |
|  | Token Pass: QR-01 (FC-PASS-U04-ACTIVE). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka halaman Scanner Check-in resepsionis. |

2. Arahkan kembali kartu digital pass QR-01 ke depan kamera scanner.
3. Amati respons modal pop-up.
4. Muat ulang halaman log presensi harian.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Tampil pop-up peringatan kuning/oranye "Peringatan: Member Amanda Putri sudah melakukan check-in hari ini (2 menit yang lalu)"; suara peringatan ganda. |

2) Lapisan Data Persistence: Tabel check_ins setelah di-reload hanya memuat 1 baris catatan presensi untuk Member Amanda pada hari tersebut.
3) Lapisan Efek Lanjutan: Metrik total kehadiran gym hari ini di dashboard tidak mengalami penambahan angka ganda.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Akses webcam browser (html5-qrcode), data presensi pertama sudah terdaftar. |
|  | Hasil Aktual (Actual Result) |
|  | Scanner mendeteksi pemindaian berulang dan menolak membuat record presensi baru di database. |
|  | Status Eksekusi |
|  | PASS |



## 4.5 Modul Otorisasi & Keamanan (SEC)

Kartu Uji 22: Proteksi Kontrol Akses Role (RBAC 403 Forbidden)
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-SEC-001 |
|  | Scenario Reference |
|  | TS-SEC-001 (Ref: WBS 2.2.2, Risk RSK-04) |
|  | Judul / Objective |
|  | Membuktikan bahwa akun ber-role Member ditolak secara mutlak saat mencoba membuka URL manajemen paket Admin via direct URL bar. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | Pengguna telah login sebagai Member Amanda (U-04). |
|  | Test Data |
|  | Akun: amanda@test.com, Target URL: /admin/packages. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka tab baru pada browser. |

2. Ketikkan alamat http://localhost:8000/admin/packages langsung pada address bar browser.
3. Tekan tombol Enter.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Sistem menampilkan halaman error resmi HTTP 403 *"403 |
|  | Kebutuhan Khusus / Eksekusi |
|  | Akun member aktif, middleware Spatie role:admin / can:manage-membership-packages aktif. |
|  | Hasil Aktual (Actual Result) |
|  | Halaman langsung mengembalikan status kode HTTP 403 Forbidden dan memblokir render komponen admin. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 23: Pengalihan Tamu Tanpa Autentikasi ke Laman Login (Auth Guard))
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-SEC-002 |
|  | Scenario Reference |
|  | TS-SEC-002 (Ref: WBS 2.2.2, Middleware Auth) |
|  | Judul / Objective |
|  | Membuktikan bahwa pengunjung non-login yang mencoba mengakses dasbor member otomatis dialihkan ke halaman login. |
|  | Tipe & Prioritas |
|  | Negatif · High |
|  | Precondition |
|  | Browser berada dalam mode Incognito / sesi kosong (tidak ada session cookie). |
|  | Test Data |
|  | URL: http://localhost:8000/member/dashboard. |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Buka browser jendela Private/Incognito baru. |

2. Ketikkan URL /member/dashboard pada address bar.
3. Tekan Enter.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Browser seketika dialihkan (redirect) ke URL /login; tampil pesan flash info "Silakan masuk ke akun Anda terlebih dahulu"; komponen dasbor tertutup rapat. |

2) Lapisan Data Persistence: Nilai auth()->check() bernilai false.
3) Lapisan Efek Lanjutan: Parameter URL intended menyimpan riwayat tujuan untuk redirect pasca-login.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Browser incognito/private, middleware auth Laravel standar. |
|  | Hasil Aktual (Actual Result) |
|  | Akses dasbor dicegat dan pengguna secara mulus dialihkan ke halaman login. |
|  | Status Eksekusi |
|  | PASS |



## 4.6 Modul Notifikasi & Integrasi Email (NOTIF)

Kartu Uji 24: Pengiriman Email Notifikasi via Antrean Queue ke Mailtrap
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-NOTIF-001 |
|  | Scenario Reference |
|  | TS-NOTIF-001 (Ref: WBS 5.2.1, 5.2.2, Queue Worker) |
|  | Judul / Objective |
|  | Membuktikan bahwa persetujuan aktivasi memicu dispatch job email secara asinkron tanpa memperlambat respons UI dan email diterima di Mailtrap. |
|  | Tipe & Prioritas |
|  | Positif · Medium |
|  | Precondition |
|  | Transaksi TX-01 disetujui Admin. Konfigurasi SMTP Mailtrap aktif pada file .env. Antrean queue:work berjalan. |
|  | Test Data |
|  | Penerima: dimas@test.com, Subjek: "Selamat! Keanggotaan FitCore Anda Telah Aktif". |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Admin menyetujui transaksi TX-01 pada portal admin. |

2. Ukur waktu respons tombol simpan di browser admin.
3. Periksa antrean pada tabel jobs.
4. Buka inbox layanan Mailtrap pengujian.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Halaman admin merespons sangat cepat (< 1 detik) tanpa freeze menunggu koneksi SMTP luar negeri. |

2) Lapisan Data Persistence: Record pekerjaan pada tabel jobs diproses secara asinkron lalu dihapus setelah sukses dikirim.
3) Lapisan Efek Lanjutan: Kotak masuk Mailtrap menerima 1 buah email HTML resmi berisi kartu digital pass dan rincian masa aktif member Dimas.
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Layanan SMTP Sandbox Mailtrap, worker antrean aktif (php artisan queue:work --once). |
|  | Hasil Aktual (Actual Result) |
|  | Email terkirim asinkron dan berhasil masuk ke inbox Mailtrap dengan template HTML yang presisi. |
|  | Status Eksekusi |
|  | PASS |


Kartu Uji 25: Resiliensi Sistem Transaksi Saat Layanan SMTP Timeout/Down
Komponen Test Case
|  | Detail Perancangan & Spesifikasi Pengujian |
| --- | --- |
|  | Test Case ID |
|  | TC-NOTIF-002 |
|  | Scenario Reference |
|  | TS-NOTIF-002 (Ref: WBS 1.3.3, Fault Tolerance) |
|  | Judul / Objective |
|  | Membuktikan bahwa kegagalan jaringan atau timeout server SMTP tidak menggagalkan proses approval transaksi dan status aktivasi member. |
|  | Tipe & Prioritas |
|  | Negatif · Medium |
|  | Precondition |
|  | Port SMTP Mailtrap sengaja dialihkan ke port tertutup (simulasi koneksi putus/timeout). Terdapat transaksi TX-03 siap disetujui. |
|  | Test Data |
|  | Transaksi: TX-03, Host SMTP: 127.0.0.1:9999 (Offline mock). |
|  | Langkah Pengujian (Test Steps) |
|  | 1. Admin menekan tombol Setujui Pembayaran pada transaksi TX-03. |

2. Periksa respons antarmuka admin.
3. Periksa status transaksi dan membership pada basis data.
4. Periksa log antrean tabel failed_jobs.
|  | Hasil yang Diharapkan (Expected Result) |
| --- | --- |
|  | 1) Lapisan Layar (UI): Admin tetap melihat alert sukses verifikasi pembayaran tanpa mengalami crash error 500. |

2) Lapisan Data Persistence: Status transaksi tetap sah verified dan membership tetap aktif (transaksi inti tidak di-rollback akibat kegagalan notifikasi).
3) Lapisan Efek Lanjutan: Job pengiriman email yang gagal tercatat di tabel failed_jobs untuk dicoba ulang nanti (retry mechanism).
|  | Kebutuhan Khusus / Eksekusi |
| --- | --- |
|  | Desain arsitektur event/job decoupling dengan ShouldQueue. |
|  | Hasil Aktual (Actual Result) |
|  | Kegagalan SMTP tertahan di background queue tanpa merusak alur transaksi utama membership. |
|  | Status Eksekusi |
|  | PASS |

5. Rantai Ketertelusuran Utuh (Full Traceability Matrix)
Tabel berikut membuktikan keterhubungan tak terputus dari tugas WBS pada Test Plan menuju Skenario dan Kasus Uji:
Kode WBS
|  | ID Test Plan |
| --- | --- |
|  | Nama Fitur / Tugas |
|  | ID Test Scenario |
|  | ID Test Case |
|  | Status Uji |
|  | 3.1.1, 3.1.2 |
|  | TP-MEM-01 |
|  | Pemilihan Paket Membership |
|  | TS-MEM-001 |
|  | TC-MEM-001 |
|  | PASS |
|  | 3.4.1 |
|  | TP-MEM-02 |
|  | Manajemen Paket & Harga |
|  | TS-MEM-002 |
|  | TC-MEM-002, TC-MEM-003 |
|  | PASS |
|  | 5.1.1 |
|  | TP-MEM-03 |
|  | Riwayat & Profil Member |
|  | TS-MEM-003 |
|  | TC-MEM-004 |
|  | PASS |
|  | 3.2.1, 3.2.2 |
|  | TP-PAY-01 |
|  | Pembayaran & Upload Bukti |
|  | TS-PAY-001, TS-PAY-002 |
|  | TC-PAY-001, TC-PAY-002, TC-PAY-003 |
|  | PASS |
|  | 3.3.1, 3.3.2 |
|  | TP-PAY-02 |
|  | Verifikasi Pembayaran Admin |
|  | TS-PAY-003, TS-PAY-004, TS-PAY-005 |
|  | TC-PAY-004, TC-PAY-005, TC-PAY-006 |
|  | PASS |
|  | 3.5.1, 3.5.2 |
|  | TP-PT-01 |
|  | Beli Paket Sesi PT |
|  | TS-PT-001, TS-PT-002 |
|  | TC-PT-001, TC-PT-002 |
|  | PASS |
|  | 3.6.1 |
|  | TP-PT-02 |
|  | Master Paket PT Admin |
|  | TS-PT-003 |
|  | (Tercakup di alur PT) |
|  | PASS |
|  | 4.3.1 |
|  | TP-PT-03 |
|  | Kalender Slot PT (FullCalendar) |
|  | TS-PT-004 |
|  | TC-PT-003 |
|  | PASS |
|  | 4.4.1, 4.4.2 |
|  | TP-PT-04 |
|  | Booking Sesi PT & Batas Kuota |
|  | TS-PT-005, TS-PT-006 |
|  | TC-PT-004, TC-PT-005, TC-PT-006 |
|  | PASS |
|  | 4.4.3 |
|  | TP-PT-05 |
|  | Notifikasi Booking Sesi PT |
|  | TS-PT-007 |
|  | TC-PT-007 |
|  | PASS |
|  | 2.5.2 |
|  | TP-ATT-01 |
|  | Scanner Kamera Browser |
|  | TS-ATT-001 |
|  | TC-ATT-001 |
|  | PASS |
|  | 2.5.3, 2.5.4 |
|  | TP-ATT-02 |
|  | Validasi Presensi & Anti-Duplikasi |
|  | TS-ATT-002, TS-ATT-003, TS-ATT-004 |
|  | TC-ATT-002, TC-ATT-003, TC-ATT-004 |
|  | PASS |
|  | 2.2.2 |
|  | TP-SEC-01 |
|  | Role & Permission (Middleware 403) |
|  | TS-SEC-001, TS-SEC-002 |
|  | TC-SEC-001, TC-SEC-002 |
|  | PASS |
|  | 5.2.1, 5.2.2 |
|  | TP-NOTIF-01 |
|  | Konfirmasi Email Queue |
|  | TS-NOTIF-001 |
|  | TC-NOTIF-001 |
|  | PASS |
|  | 1.3.3 |
|  | TP-NOTIF-02 |
|  | Setup SMTP Mailtrap |
|  | TS-NOTIF-002 |
|  | TC-NOTIF-002 |
|  | PASS |

6. Evaluasi Mutu Test Case (Checklist Kualitas Subbab 4.13)
Berdasarkan checklist evaluasi:
1. Jelas (Clear): Langkah uji ditulis atomik diawali kata kerja operasional (Buka, Pilih, Upload, Tekan), menyebut elemen visual spesifik bukan asumsi.
2. Atomik (Atomic): Setiap test case hanya memiliki tepat satu tujuan pembuktian spesifik (tidak ada test case yang menggabungkan registrasi, bayar, dan booking sekaligus).
3. Independen (Independent): Precondition menyiapkan status data awal masing-masing sehingga pengujian dapat dieksekusi secara mandiri.
4. Dapat Diulang (Repeatable): Menggunakan acuan data konkret dari Test Data Sheet (U-01, PKG-02, F-01).
5. Dapat Diverifikasi (Verifiable): Seluruh Expected Result memverifikasi 3 lapisan secara terukur.
6. Keseimbangan Positif & Negatif: Terdiri dari 10 Positive Test Cases (40%) dan 15 Negative/Edge Test Cases (60%), membuktikan ketahanan sistem saat menerima input salah maupun pelanggaran aturan bisnis.
________________


Lembar Pengesahan Eksekusi (Sign-off)
Dokumen Test Case & Execution Record ini disahkan telah selesai disusun dan dieksekusi untuk milestone rilis peninjauan FitCore Gym:
* Test Lead & Quality Architect: Naufal Alif Adjani
* Test Analyst: Achmad Alfi Maksum
* Execution & Automation Specialist: Muhammad Rif’at Syamlan
* Environment & Security Analyst: Siti Aisyah
* Tanggal: 8 November 2026
* Status Hasil: 100% EXECUTED — ALL HIGH PRIORITY PASSED
 




[a]perlu di diskusikan
[b]Benar seperti ini kahh output yang diinginkan?