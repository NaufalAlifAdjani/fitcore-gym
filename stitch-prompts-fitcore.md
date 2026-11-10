# FitCore — Stitch Prompts per Fitur (Web + Mobile)

> Semua prompt di bawah **reuse token dari `DESIGN.md`** (brand Maxfit: primary `#ED1B45`, dark `#141414`, dst) — tidak ada warna/font/radius baru yang diperkenalkan. Yang berubah hanya *pola UI* (form, tabel, dashboard, scanner) karena FitCore adalah sistem informasi manajemen, bukan landing page marketing seperti screen "Classes" sebelumnya.

---

## 🧩 Shared UI Patterns (pakai di SEMUA prompt di bawah — jangan diulang re-derive)

**Design tokens (dari DESIGN.md, apa adanya):**
Primary `#ED1B45` · Dark `#141414` · Background `#F6F6F7` · Surface `#FFFFFF` · Text-primary `#16151A` · Text-secondary `#565A66` · Border `#E5E7EB` · Success `#10B981` · Warning `#F59E0B` · Error `#EF4444` · Radius: card 16px, button/pill full (9999px), input 10px · Font: Poppins (heading, 700-800), Inter (body, 400) · Spacing 8px grid · Shadow card `0 4px 12px rgba(0,0,0,0.06)`.

**Web app shell — Admin & PT:** Fixed left sidebar (dark `#141414`, nav item aktif = red left-border + red text, inactive = muted white `#A1A1AA`), top bar putih (breadcrumb + notification bell + avatar dropdown), main content di atas `#F6F6F7` berisi white card panels.

**Web app shell — Member:** Top navbar terang (logo kiri, nav kanan, avatar dropdown), max-width container ~1200px, konsisten dengan landing page.

**Mobile shell — Member:** Bottom tab bar 5 item (Home, Booking, QR, Riwayat, Profil) — tab aktif ikon+label primary red. Sticky header atas (judul + back button jika perlu).

**Mobile shell — Admin/PT:** Top header (judul + hamburger icon), drawer nav dari kiri (menu sama seperti sidebar web), konten single-column stacked.

**Komponen universal:** status badge pill kecil (success/warning/error/netral), data table (hairline border `#E5E7EB`, hover row highlight), empty state (icon + teks singkat + CTA), form input (radius 10px, border `#E5E7EB`, focus ring 2px primary), toast notification (top-right, radius 10px, auto-dismiss), skeleton loading untuk data fetch.

**Tech output (semua screen):** Laravel Blade + TailwindCSS + Livewire (untuk komponen reaktif seperti tabel, form, real-time status) + Alpine.js (untuk interaksi ringan: dropdown, modal, tab). Semantic HTML5. Reusable Blade components (`x-sidebar`, `x-data-table`, `x-status-badge`, `x-form-input`).

**Accessibility (semua screen):** WCAG AA, kontras ≥4.5:1 body / ≥3:1 large & UI component, focus ring visible, touch target ≥44x44px di mobile, aria-label untuk icon-only button.

---

# 👤 MEMBER

## 1. Autentikasi (Register & Login)

**Web:**
```
Design the Register and Login screens for FitCore member portal, following the existing Maxfit design system (DESIGN.md tokens — do not introduce new colors/fonts). Layout: centered card (max-width 440px) on light background #F6F6F7, split-screen optional with a gym photo on the right (reuse hero-style dark overlay from the landing page) and form on the left.
Register form fields: Full Name, Email, Phone Number, Password, Confirm Password, checkbox "I agree to Terms". Login form fields: Email, Password, "Remember me" checkbox, "Forgot password?" link.
Primary pill button "Daftar" / "Masuk" (full width, primary red). Secondary link below to switch between Register/Login. Show inline validation error state (red text + red border on invalid field).
Content: Heading "Bergabung dengan Maxfit" (register) / "Selamat Datang Kembali" (login).
```

**Mobile:**
```
Same Register/Login flow as web, but full-screen single column (no split image), logo centered top, form fields stacked full-width, primary pill button full-width at bottom, sticky above keyboard. Use mobile shell without bottom tab bar (auth screens are pre-login, no tab bar). Larger touch targets (48px input height) for thumb-friendly typing.
```

---

## 2. Transaksi Paket Membership (Pilih Paket + Upload Bukti Transfer)

**Web:**
```
Design the Membership Package selection + payment upload flow for FitCore Member portal (use Member web shell). Layout:
- Step 1: Package grid (3 cards: Basic, Standard, Premium) — reuse the class-card pattern (white surface, radius 16px, shadow) with package name, price (bold, large), feature checklist (icon + text), primary pill CTA "Pilih Paket".
- Step 2 (after selecting): Payment instruction card showing bank transfer details, upload dropzone for payment proof (drag-and-drop or click to upload image/PDF), submit button "Kirim Bukti Pembayaran".
- Status banner after submit: "Menunggu Verifikasi Admin" (warning badge, amber).
Content: Package names "Basic — Rp150.000/bulan", "Standard — Rp250.000/bulan", "Premium — Rp400.000/bulan" with feature lists (akses gym, kelas grup, sesi PT gratis, dst).
```

**Mobile:**
```
Same package selection + upload flow, mobile layout: package cards stack vertically full-width, swipeable carousel optional. Upload step uses native camera/file picker button ("Ambil Foto Bukti Transfer" / "Pilih dari Galeri"). Sticky bottom CTA button. Status badge shown as a banner card below header after submission.
```

---

## 3. Riwayat Pribadi (Kehadiran, Booking, Transaksi)

**Web:**
```
Design the Member "My History" screen (web shell) with 3 tabs: Kehadiran, Booking Sesi PT, Transaksi. Each tab shows a data table: 
- Kehadiran: Date, Time, Status badge (Hadir).
- Booking Sesi PT: Date, Trainer Name, Time Slot, Status badge (Selesai/Berlangsung/Menunggu).
- Transaksi: Date, Package/Item, Amount, Status badge (Terverifikasi/Menunggu/Ditolak).
Use the shared data-table pattern, pagination at bottom, empty state if no records ("Belum ada riwayat" + icon).
```

**Mobile:**
```
Same 3 categories (Kehadiran, Booking, Transaksi) but shown as a segmented tab control at top, list of stacked cards instead of a table (each card: title/date left, status badge right, tap to expand detail). Pull-to-refresh interaction.
```

---

## 4. Check-in (Kartu Member Digital QR — Member sisi mobile)

**Web:**
```
Design the Front-Desk Check-in Scanner screen (used by Admin/staff on a desktop/tablet at the entrance, web shell — Admin sidebar). Main area: large camera viewfinder box (dashed border, primary red) for QR scanning via html5-qrcode, with a manual fallback input field "Masukkan Kode Member Manual". Right side panel: live list of "Check-in Terakhir" (avatar, name, timestamp, green success badge) updating in real time. Success state: green toast "Check-in berhasil — [Nama Member]". Failed state: red toast "QR tidak valid / Membership tidak aktif".
```

**Mobile:**
```
Design the Member's "Kartu Member Digital" screen — mobile shell, accessed via bottom tab "QR". Center: large QR code on white card (radius 16px, shadow), member name + membership status badge (Aktif/green) below it, membership expiry date in text-secondary. Small note "Tunjukkan QR ini ke petugas untuk check-in". Include a subtle pulsing border animation around QR to indicate it's live/scannable.
```

---

## 5. Booking Sesi PT (Beli Paket Sesi + Preferensi Jadwal Auto-Plotting)

**Web:**
```
Design the "Book a PT Session" flow (Member web shell). Step 1: PT package selection (cards: number of sessions, price, primary CTA). Step 2: Trainer selection (grid of PT profile cards — photo, name, specialty tags, rating stars). Step 3: Preference form — select preferred days (multi-select day pills, reuse day-tab component style) + preferred time range (start/end time picker) + submit button "Ajukan Jadwal (Auto-Plotting)". Info banner explaining the system will auto-assign exact slots based on trainer availability, avoiding conflicts.
```

**Mobile:**
```
Same 3-step flow as a mobile wizard (step indicator dots at top, one step per screen, "Lanjut"/"Kembali" buttons at bottom). Trainer selection as horizontal swipeable cards. Day-pills and time picker adapted to full-width touch-friendly controls.
```

---

## 6. Lihat Ketersediaan Jadwal PT (Sebelum Booking)

**Web:**
```
Design a "PT Availability Calendar" screen (Member web shell, read-only browse before booking). Layout: left column list of trainers (avatar, name, specialty), right side a weekly calendar grid (7 columns = days, rows = time slots) showing available slots as light-green cells and booked/unavailable slots as gray cells for the selected trainer. Legend at top (green = tersedia, gray = terisi). CTA pill button "Booking Sesi Ini" appears on hover/select of an available slot.
```

**Mobile:**
```
Same availability view, mobile layout: trainer list as a horizontal avatar scroller at top (tap to select), below it a single-day view of time slots as a vertical stacked list (each slot = row with time + status badge tersedia/terisi + tap-to-book on available ones), day switcher as left/right arrow + date label above the list instead of a full week grid.
```

---

## 7. Rating & Review PT

**Web:**
```
Design a "Rate Your Trainer" modal/screen (Member web shell), triggered after a completed session. Content: trainer photo + name at top, 5-star rating selector (large, primary red filled stars on select), optional text area "Tulis ulasan Anda (opsional)", submit pill button "Kirim Ulasan". Below, show a list of the member's past ratings given (compact list: trainer name, stars given, date).
```

**Mobile:**
```
Same rating flow as a bottom-sheet modal (slides up from bottom, rounded top corners 16px), star selector large and centered for easy tapping, text area below, full-width submit button pinned at the bottom of the sheet.
```

---

# 🔑 SHARED — LOGIN INTERNAL

## 8. Login Internal (Admin & Personal Trainer)

**Web:**
```
Design the Internal Login screen for Admin and PT (separate from Member login, same visual system). Centered card on dark background #141414 (reuse hero-dark treatment) with the Maxfit logo, heading "Portal Internal", fields Email + Password, primary pill button "Masuk", small role indicator text below ("Login sebagai Admin atau Personal Trainer"). No self-registration link (accounts created by Admin only).
```

**Mobile:**
```
Same internal login, full-screen dark background, logo centered top, form fields stacked, primary pill button full-width, no bottom tab bar (pre-login state).
```

---

# 🏋️ PERSONAL TRAINER (PT)

## 9. Dashboard Jadwal PT (Harian/Mingguan)

**Web:**
```
Design the PT Dashboard screen (PT web shell — sidebar with menu: Dashboard, Jadwal, Member Saya, Performa). Top: toggle "Harian / Mingguan" view. Main area: calendar/timeline showing today's or this week's sessions as blocks (time, member name/avatar, status badge: Menunggu/Berlangsung/Selesai — color-coded amber/blue/green). Side panel: quick stats cards (Total Sesi Hari Ini, Member Aktif, Rating Rata-rata).
```

**Mobile:**
```
Same dashboard, mobile shell (hamburger nav). Stats cards as a horizontal scroll row at top. Schedule shown as a vertical agenda list (grouped by time), each item a card with status badge, tap to view session detail.
```

---

## 10. Daftar & Riwayat Member per Sesi

**Web:**
```
Design the "My Members" screen for PT (web shell). Data table: Member Name (avatar + name), Package, Total Sessions Completed, Last Session Date, Status badge (Aktif/Selesai Paket), action button "Lihat Riwayat" opening a detail panel/drawer with full session history for that member (list of past sessions with dates and status).
```

**Mobile:**
```
Same member list as stacked cards (avatar, name, package, status badge), tap card to open a detail screen (full-screen, back button) showing that member's session history as a vertical timeline list.
```

---

## 11. Performa Pribadi PT

**Web:**
```
Design a "My Performance" dashboard for PT (web shell). Top row: 3 stat cards (Total Sesi Bulan Ini, Rating Rata-rata with star icon, Total Member Aktif). Below: a line chart showing sessions completed per week (use primary red line on light card background), and a list of recent reviews received (avatar, member name, stars, review text, date).
```

**Mobile:**
```
Same performance view, stat cards as a 2x2 grid (or horizontal scroll if 3 items), chart full-width below, recent reviews as stacked cards further down — single scrollable column overall.
```

---

# 🛠️ ADMIN / OWNER

## 12. Manajemen Akun PT (CRUD)

**Web:**
```
Design the "Manage Trainers" screen for Admin (web shell, sidebar). Header: "Personal Trainers" title + primary pill button "+ Tambah PT" (opens modal). Data table: Photo/avatar, Name, Specialty, Phone, Status badge (Aktif/Nonaktif), action icons (Edit, Nonaktifkan). Modal form (add/edit): Name, Email, Phone, Specialty tags, Photo upload, Password (for new account) — primary pill submit button.
```

**Mobile:**
```
Same trainer management, list as stacked cards (avatar, name, specialty, status badge, edit icon), floating action button (bottom-right, circular, primary red, "+") opens a full-screen form instead of a modal.
```

---

## 13. Ketersediaan / Jam Kerja PT (Admin Mengatur)

**Web:**
```
Design the "Trainer Availability" management screen for Admin (web shell). Select a trainer from a dropdown/list at top. Main area: weekly schedule grid (7 day columns x time-slot rows), each cell toggleable (click to mark as available/unavailable — available = light green, unavailable = light gray), save button "Simpan Ketersediaan" primary pill, bottom-right.
```

**Mobile:**
```
Same availability editor, mobile layout: trainer selector as a dropdown at top, single-day view with day tabs (7 pill tabs, scrollable horizontally), time slots below as a toggle-list (row = time + on/off switch styled in primary red), sticky save button at bottom.
```

---

## 14. Verifikasi Pembayaran

**Web:**
```
Design the "Payment Verification" screen for Admin (web shell). Data table: Member Name, Package, Amount, Upload Date, Proof Thumbnail (small clickable image), Status badge (Menunggu/Terverifikasi/Ditolak), action buttons "Verifikasi" (green) / "Tolak" (red) for pending rows. Clicking the thumbnail opens a modal with the full-size payment proof image and member's transaction details.
```

**Mobile:**
```
Same verification list as stacked cards (member name, amount, status badge, small proof thumbnail), tap card opens full-screen detail view with large proof image and two action buttons ("Verifikasi" / "Tolak") pinned at the bottom.
```

---

## 15. Manajemen Member & Status Keanggotaan

**Web:**
```
Design the "Manage Members" screen for Admin (web shell). Header: search bar + filter dropdown (Status: Aktif/Nonaktif/Akan Berakhir). Data table: Avatar+Name, Email, Package, Membership Expiry Date, Status badge (auto-colored: green Aktif, amber Akan Berakhir <7 hari, red Nonaktif/Expired), action icon "Lihat Detail" opening a side drawer with full member profile, attendance summary, and transaction history.
```

**Mobile:**
```
Same member list, search bar sticky at top, filter as a horizontal chip row below it, list as stacked cards (name, expiry date, status badge), tap card opens a full-screen member detail profile.
```

---

## 16. Manajemen Paket Sesi PT & Jadwal

**Web:**
```
Design the "PT Session Packages & Schedule" management screen for Admin (web shell), 2 tabs: "Paket Sesi" and "Jadwal Sesi". Paket Sesi tab: table of packages (Name, Number of Sessions, Price, Status active/inactive) + "+ Tambah Paket" button opening a form modal. Jadwal Sesi tab: master calendar view of all trainers' sessions across the week (color-coded by trainer), with conflict warnings highlighted in red if any overlapping bookings are detected (should not normally occur due to auto-plotting, but shown for oversight).
```

**Mobile:**
```
Same 2 tabs as segmented control at top. Paket Sesi tab: stacked package cards with edit icon. Jadwal Sesi tab: simplified single-trainer-at-a-time agenda list (trainer selector dropdown + vertical time list) instead of a full grid, since a multi-trainer week grid doesn't fit mobile width well.
```

---

## 17. Manajemen Harga & Paket Membership

**Web:**
```
Design the "Membership Packages & Pricing" management screen for Admin (web shell). Table/grid of packages (Basic/Standard/Premium): Name, Price, Duration, Feature list (editable checklist), Status toggle (active/inactive), Edit and Delete icons per row. "+ Tambah Paket" primary pill button opens a form modal (Name, Price, Duration, Features list builder, Active toggle).
```

**Mobile:**
```
Same package pricing management as stacked cards (package name, price, active toggle switch, edit icon), floating "+" action button at bottom-right opens a full-screen add/edit form.
```

---

## 18. Dashboard Performa Gym (Admin)

**Web:**
```
Design the Admin "Performance Dashboard" (Admin web shell, default landing page after login). Top row: 4 stat cards (Total Member Aktif, Pendapatan Bulan Ini, Total Check-in Hari Ini, Sesi PT Berlangsung) — reuse the stat-card pattern from the landing page (big bold number + label + small icon) but on white card surface instead of the red diagonal banner. Below: a revenue line/bar chart (monthly trend, primary red), and a member growth chart. Bottom section: recent activity feed (new registrations, payments verified, check-ins) as a compact list.
```

**Mobile:**
```
Same dashboard, stat cards as a 2x2 grid at top (or horizontal scroll), charts stacked full-width below, activity feed as a scrollable list at the bottom — single continuous scroll, no side-by-side panels.
```

---

## 📋 Catatan
- Fitur **notifikasi email** (reminder masa aktif, konfirmasi booking/pembayaran) tidak dibuatkan Stitch prompt karena bukan screen UI berdiri sendiri — ini adalah email template + trigger backend. Kalau perlu, aku bisa buatkan desain email template terpisah (HTML email, bukan Stitch).
- Semua 18 prompt di atas **konsisten memakai token yang sama dari `DESIGN.md`** — tidak ada warna/font/spacing baru yang diperkenalkan, hanya pola komponen baru (tabel, form, dashboard, scanner) yang memang belum ada di landing page.
- Urutan implementasi disarankan ikuti prioritas PRD: mulai dari fitur #1, #8, #12-15 (Must Have inti), baru lanjut ke Should Have (#3, #9, #10, #16, #17, #6, #18), lalu Could Have (#7, #11) terakhir.
