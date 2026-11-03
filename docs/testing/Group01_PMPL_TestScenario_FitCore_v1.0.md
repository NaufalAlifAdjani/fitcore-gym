# DOKUMEN TEST SCENARIO KONSOLIDASI v1.0
## SISTEM FITCORE GYM MANAGEMENT & PT BOOKING PLATFORM

---

### Informasi Dokumen
- **Nama Dokumen**: Test Scenario Specification Document (Modul B - Pertemuan 3)
- **Kode Berkas**: `Group01_PMPL_TestScenario_FitCore_v1.0.md`
- **Aplikasi Objek Uji**: FitCore Gym Management System
- **Standar Format Skenario**: Formula `Verify [behavior] when [condition]` (Tanpa detail klik-per-klik atomik)
- **Target Minimal**: 12 Skenario (Tercapai: 16 Skenario Terverifikasi)

---

## 1. Definisi Business Rules (Aturan Bisnis Utama FitCore)

Sebelum menurunkan skenario pengujian, berikut adalah aturan bisnis inti yang wajib dipatuhi oleh sistem:
- **BR-01 (Active Membership Required)**: Pemesanan sesi Personal Trainer hanya dapat diproses apabila member memiliki membership berstatus `active` dan sisa kuota sesi PT pada `member_pt_quotas` lebih dari 0.
- **BR-02 (Cancellation State Restriction)**: Pembatalan sesi PT oleh member hanya diizinkan apabila status sesi masih `pending` atau `scheduled`, dan minimal 2 jam sebelum waktu sesi dimulai. Pembatalan ditolak jika status sudah `confirmed` oleh trainer di hari H atau sesi sudah `completed`.
- **BR-03 (Trainer Schedule Overlap Prevention)**: Trainer tidak dapat menerima dua sesi booking berbeda pada tanggal dan rentang jam (*time-slot*) yang bertabrakan (*race condition / double-booking*).
- **BR-04 (Single Daily Check-In Limit)**: Setiap member hanya berhak melakukan check-in berhasil sebanyak 1 kali per hari kalender (*unique daily check-in constraint*).
- **BR-05 (Strict Role Isolation & Cross-Tenant Protection)**: Member biasa tidak dapat mengakses, melihat invoice, atau mengubah data sesi/pembayaran member lain (IDOR protection), serta dilarang mengakses panel admin (`admin/*`).

---

## 2. Checklist Kelayakan Cakupan Kategori Skenario

- [x] **Happy Path (Alur Penuh Bisnis)**: Alur pendaftaran $\rightarrow$ checkout membership $\rightarrow$ verifikasi admin $\rightarrow$ booking sesi PT $\rightarrow$ check-in gym.
- [x] **Requirement-Derived (Deviasi Alur)**: Pembatalan pesanan di tengah jalan, abandoned checkout, upload ulang bukti bayar saat ditolak.
- [x] **Business Rules (BR-01 s.d BR-05)**: Terpetakan penuh pada skenario penolakan kuota, pembatalan ilegal, dan overlap jadwal.
- [x] **Negative Scenarios**: Input gambar korup/ekstensi terlarang, booking dengan kuota 0, token QR palsu/kedaluwarsa.
- [x] **Edge Condition Scenarios**: Upload berkas tepat batas 2.048 KB, booking sesi pada batas jam operasional gym (slot terakhir 20:00 - 21:00 WIB).
- [x] **Role-Based & State-Based**: Matriks transisi status pembayaran dan penolakan akses rute admin oleh member.
- [x] **Dependency & Risk-Based**: Simulasi keterlambatan approval admin, retry submit form saat jaringan lambat.

---

## 3. Tabel Konsolidasi Skenario Uji (Tabel TS)

| ID Skenario | Source | Feature | Scenario Description (*Verify [behavior] when [condition]*) | Type | Priority |
|---|---|---|---|---|---|
| **TS-MEM-001** | Requirement | Membership Catalog | *Verify package list and pricing details are accurately rendered when authenticated member accesses catalog page.* | Happy Path | High |
| **TS-MEM-002** | Requirement | Membership Checkout | *Verify payment record with status PENDING is created when member submits valid checkout with bank transfer method.* | Happy Path | High |
| **TS-MEM-003** | Risk (Security) | Payment Proof Upload | *Verify upload is rejected with error validation message when member uploads non-image file (.php / .exe).* | Negative | High |
| **TS-MEM-004** | Edge Condition | Payment Proof Upload | *Verify upload is successfully processed when member submits proof file with size exactly at upper limit boundary (2048 KB).* | Edge | Medium |
| **TS-ADM-005** | BR-01 / State | Admin Verification | *Verify membership status activates and valid period extends when admin approves pending payment with valid verification log recorded.* | State-Based | Critical |
| **TS-ADM-006** | State | Admin Verification | *Verify payment status changes to REJECTED and membership remains inactive when admin rejects invalid transfer slip with a specific rejection reason.* | State-Based | High |
| **TS-PTS-007** | Requirement | PT Session Booking | *Verify booking session is created with status PENDING and quota is deducted by 1 when eligible member books available trainer time-slot.* | Happy Path | Critical |
| **TS-PTS-008** | BR-01 | PT Session Booking | *Verify booking submission is blocked with warning alert when member attempts to book PT session with 0 remaining session quota.* | Negative | High |
| **TS-PTS-009** | BR-03 (Risk) | Slot Conflict Prevention | *Verify system rejects second booking request with conflict error message when two members attempt to book the exact same trainer, date, and time slot.* | Negative / Race | Critical |
| **TS-PTS-010** | Edge Condition | Slot Operational Hours | *Verify booking is permitted on the last operational slot (20:00 - 21:00) but rejected for slot starting at or after closing time (21:00 onwards).* | Edge | Medium |
| **TS-PTS-011** | BR-02 / State | PT Session Cancellation | *Verify booking status transitions to CANCELLED and member quota is restored (+1) when member cancels pending session at least 2 hours prior to schedule.* | Happy Path | High |
| **TS-PTS-012** | BR-02 | PT Session Cancellation | *Verify cancellation action is disabled and rejected by server when member attempts to cancel a session that is already CONFIRMED by trainer on the same day.* | Negative / BR | Critical |
| **TS-CHK-013** | Requirement | QR Check-In | *Verify check-in record is successfully logged and welcome badge appears when staff scans valid active member dynamic QR code.* | Happy Path | High |
| **TS-CHK-014** | BR-04 | QR Check-In | *Verify check-in attempt is rejected with "Already Checked In Today" message when staff scans the same member QR token for the second time on the same date.* | Negative / BR | High |
| **TS-CHK-015** | State / Risk | QR Check-In | *Verify check-in is rejected with "Membership Inactive" alert when staff scans QR code of a member whose membership has expired.* | Negative / State | High |
| **TS-SEC-016** | BR-05 (RBAC) | Route Authorization | *Verify HTTP 403 Forbidden is returned and access denied when regular member tries to directly navigate to admin payment verification endpoint (/admin/verifikasi-pembayaran).* | Security / Role | Critical |

---

## 4. Matriks Traceability (Traceability Matrix)

| Test Objective | Kode Risiko Produk | Skenario Terkait |
|---|---|---|
| Objektif 1: Keutuhan Transaksi & Upload | PR-01, PR-06 | `TS-MEM-002`, `TS-MEM-003`, `TS-MEM-004`, `TS-ADM-005`, `TS-ADM-006` |
| Objektif 2: Pencegahan Double Booking PT | PR-02 | `TS-PTS-009`, `TS-PTS-010` |
| Objektif 3: Integritas Kuota & Pembatalan | PR-03 | `TS-PTS-007`, `TS-PTS-008`, `TS-PTS-011`, `TS-PTS-012` |
| Objektif 4: Validasi Check-In Unik Harian | PR-04 | `TS-CHK-013`, `TS-CHK-014`, `TS-CHK-015` |
| Objektif 5: Penegakan Hak Akses (RBAC) | PR-05 | `TS-SEC-016` |

---

## 5. Ringkasan Evaluasi Kualitas Skenario
1. **Tidak Ada Instruksi Prosedural**: Seluruh deskripsi murni menyatakan kondisi bisnis (*WHAT*), tanpa kata imperatif seperti "Klik tombol X" atau "Isi kolom input Y".
2. **Independensi Skenario**: Setiap skenario dapat berdiri sendiri dengan kondisi awal (*precondition*) yang terisolasi.
3. **Cakupan Lengkap**: Memenuhi proporsi Happy Path (31%), Negative & Business Rules (44%), Edge Condition (13%), dan Role/Security (12%).

