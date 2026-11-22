/**
 * FitCore - Extended Automated Video Walkthrough (5 - 10 Minutes Duration)
 *
 * Skenario Demo Komprehensif (Navigasi Berjenjang Melalui Sidebar Admin):
 * 1. Public Portal & Landing Page (~50s)
 * 2. Member Ecosystem - Desktop (~100s)
 * 3. Personal Trainer Ecosystem & Income Transparency - Desktop (~80s)
 * 4. Admin Backoffice - Navigasi Runtut Seluruh Menu Sidebar (~110s)
 *    - 4.1 Dashboard Performa Gym
 *    - 4.2 Paket & Harga (Membership)
 *    - 4.3 Paket Sesi & Jadwal PT
 *    - 4.4 Verifikasi Pembayaran Member
 *    - 4.5 Scanner Check-in Meja Resepsionis
 *    - 4.6 Manajemen Member
 * 5. Mobile App Light Theme, Digital QR & Quick Booking (~80s)
 * 6. Closing & Outro (~30s)
 *
 * Total Durasi Rekaman: ~7 - 8 Menit
 */

import { chromium, devices } from 'playwright';
import fs from 'fs';
import path from 'path';

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const RECORD_DIR = path.resolve('./demo-recordings');

if (!fs.existsSync(RECORD_DIR)) {
    fs.mkdirSync(RECORD_DIR, { recursive: true });
}

const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

/**
 * Logout bersih & instan menggunakan clearCookies untuk menghindari race-condition dan blocking networkidle
 */
async function logoutAndGoTo(page, context, targetPath = '/login') {
    console.log(`👉 Logout bersih (clear cookies) & navigasi ke ${targetPath}...`);
    await context.clearCookies();
    await page.goto(`${BASE_URL}${targetPath}`, { waitUntil: 'domcontentloaded' });
    await page.waitForSelector('input[name="email"], input#email', { state: 'visible', timeout: 10000 });
    await sleep(1500);
}

/**
 * Efek visual spotlight/halo agar penonton fokus ke elemen yang sedang dijelaskan
 */
async function spotlight(page, selector, durationMs = 1200) {
    try {
        const el = page.locator(selector).first();
        if (await el.isVisible()) {
            await el.scrollIntoViewIfNeeded();
            await page.evaluate((sel) => {
                const target = document.querySelector(sel);
                if (target) {
                    target.style.transition = 'all 0.3s ease';
                    target.style.outline = '3px solid #FF5722';
                    target.style.boxShadow = '0 0 20px rgba(255, 87, 34, 0.4)';
                    target.style.transform = 'scale(1.015)';
                }
            }, selector);

            await sleep(durationMs);

            await page.evaluate((sel) => {
                const target = document.querySelector(sel);
                if (target) {
                    target.style.outline = '';
                    target.style.boxShadow = '';
                    target.style.transform = '';
                }
            }, selector);
        }
    } catch (e) {
        // Fallback silent
    }
}

/**
 * Sorot dan klik elemen dengan animasi transisi yang nyaman di mata
 */
async function highlightAndClick(page, selector, delayAfter = 2000) {
    try {
        const el = page.locator(selector).first();
        await el.waitFor({ state: 'visible', timeout: 8000 });
        await el.scrollIntoViewIfNeeded();
        await spotlight(page, selector, 800);
        await el.click();
        await sleep(delayAfter);
    } catch (e) {
        console.warn(`[INFO] Click standard fallback untuk: ${selector}`);
        await page.click(selector).catch(() => {});
        await sleep(delayAfter);
    }
}

/**
 * Pengetikan teks alami bergaya pengguna asli
 */
async function humanType(page, selector, text, delayMs = 60) {
    try {
        const el = page.locator(selector).first();
        await el.waitFor({ state: 'visible', timeout: 8000 });
        await el.scrollIntoViewIfNeeded();
        await el.click();
        await page.fill(selector, '');
        for (const char of text) {
            await page.keyboard.type(char, { delay: delayMs });
        }
        await sleep(800);
    } catch (e) {
        await page.fill(selector, text).catch(() => {});
        await sleep(800);
    }
}

/**
 * Scroll halus ke bawah dan ke atas dengan tempo penonton membaca
 */
async function smoothScroll(page, pixels = 400, step = 30, pauseBetween = 40) {
    let current = 0;
    const direction = pixels >= 0 ? 1 : -1;
    const absPixels = Math.abs(pixels);

    while (current < absPixels) {
        await page.mouse.wheel(0, step * direction);
        current += step;
        await sleep(pauseBetween);
    }
    await sleep(1500);
}

async function runExtendedDemo() {
    console.log('🚀 Memulai Browser Playwright untuk Demo Komprehensif FitCore (5 - 10 Menit)...');

    const browser = await chromium.launch({
        headless: false,
        slowMo: 60,
        args: ['--start-maximized', '--window-size=1280,820'],
    });

    // =========================================================================
    // SECTION 1: PUBLIC & DESKTOP (MEMBER, TRAINER, & ADMIN)
    // =========================================================================
    const desktopContext = await browser.newContext({
        viewport: { width: 1280, height: 720 },
        recordVideo: {
            dir: path.join(RECORD_DIR, 'desktop-extended'),
            size: { width: 1280, height: 720 },
        },
    });

    const page = await desktopContext.newPage();

    // -------------------------------------------------------------------------
    // 1. PUBLIC PORTAL & LANDING PAGE (~50 Detik)
    // -------------------------------------------------------------------------
    console.log('\n🎬 [MODUL 1]: Public Portal & Brand Overview');
    console.log('👉 Membuka Landing Page FitCore...');
    await page.goto(`${BASE_URL}/`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);

    // Scroll Hero Section
    await smoothScroll(page, 400);
    await sleep(2000);
    await smoothScroll(page, 450);
    await sleep(2000);

    // Buka Halaman Paket Membership Publik
    console.log('👉 Navigasi ke Halaman Katalog Paket Publik...');
    await highlightAndClick(page, 'nav a[href*="packages"], a:has-text("Paket")', 2500);
    await smoothScroll(page, 500);
    await sleep(2500);

    // Buka Halaman Pelatih / Trainers Publik
    console.log('👉 Navigasi ke Profil Pelatih (Trainers Showcase)...');
    await highlightAndClick(page, 'nav a[href*="trainers"], a:has-text("Trainer")', 2500);
    await smoothScroll(page, 400);
    await sleep(3000);

    // -------------------------------------------------------------------------
    // 2. MEMBER ECOSYSTEM - DESKTOP (~100 Detik)
    // -------------------------------------------------------------------------
    console.log('\n🎬 [MODUL 2]: Member Ecosystem (Desktop)');
    console.log('👉 Membuka Halaman Login Member...');
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'domcontentloaded' });
    await sleep(1500);

    await humanType(page, 'input[name="email"], input#email', 'member@gym.test', 45);
    await humanType(page, 'input[name="password"], input#password', 'password123', 45);
    await highlightAndClick(page, 'button[type="submit"]', 3000);
    await page.waitForURL('**/member/dashboard**', { timeout: 10000 }).catch(() => {});

    // 2.1 Member Dashboard
    console.log('👉 Eksplorasi Dashboard Member...');
    await sleep(2000);
    await spotlight(page, '.grid, section', 2000);
    await smoothScroll(page, 450);
    await sleep(2500);
    await smoothScroll(page, -450);
    await sleep(2000);

    // 2.2 Kartu Digital Member (Desktop QR Card)
    console.log('👉 Membuka Kartu Member Digital & Dynamic QR Code...');
    await page.goto(`${BASE_URL}/member/card`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await spotlight(page, 'canvas, svg, #qr-code, .bg-white.rounded-2xl', 2500);
    await sleep(3000);

    // 2.3 Sesi Latihan PT & Kuota
    console.log('👉 Navigasi ke Modul Sesi Latihan PT...');
    await page.goto(`${BASE_URL}/pt-sessions`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 300);
    await sleep(2500);

    // 2.4 Booking Sesi Baru
    console.log('👉 Membuka Form Reservasi Sesi Baru...');
    await highlightAndClick(page, 'a[href*="pt-sessions/create"], a:has-text("Booking Sesi Baru")', 2500);
    await sleep(2000);

    const notesInput = page.locator('textarea[name="notes"], textarea').first();
    if (await notesInput.isVisible()) {
        await humanType(page, 'textarea[name="notes"], textarea', 'Fokus sesi hari ini: Form koreksi Deadlift dan program Hipertrofi Bahu bersama Coach Rama.', 40);
        await sleep(1500);
    }
    await smoothScroll(page, 350);
    await sleep(3000);

    // 2.5 Riwayat Transaksi & E-Invoice
    console.log('👉 Melihat Riwayat Transaksi Pembayaran Member...');
    await page.goto(`${BASE_URL}/member/riwayat-transaksi`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 350);
    await sleep(3000);

    // Logout Member secara bersih dan bersiap login Trainer
    await logoutAndGoTo(page, desktopContext, '/login');

    // -------------------------------------------------------------------------
    // 3. PERSONAL TRAINER ECOSYSTEM - DESKTOP (~80 Detik)
    // -------------------------------------------------------------------------
    console.log('\n🎬 [MODUL 3]: Personal Trainer Ecosystem (Coach Rama)');
    console.log('👉 Login sebagai Coach Rama Prasetya...');
    await humanType(page, 'input[name="email"], input#email', 'rama@gym.test', 45);
    await humanType(page, 'input[name="password"], input#password', 'password123', 45);
    await highlightAndClick(page, 'button[type="submit"]', 3000);
    await page.waitForURL('**/trainer/dashboard**', { timeout: 10000 }).catch(() => {});

    // 3.1 Dashboard Trainer & Ringkasan Komisi
    console.log('👉 Menampilkan Dashboard Trainer, Jadwal Hari Ini & Estimasi Komisi...');
    await sleep(2000);
    await smoothScroll(page, 450);
    await sleep(3000);
    await smoothScroll(page, -450);
    await sleep(2000);

    // 3.2 Jadwal Sesi PT & Konfirmasi Kalender
    console.log('👉 Membuka Kalender & Jadwal Sesi PT Trainer...');
    await page.goto(`${BASE_URL}/trainer/jadwal-sesi`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 400);
    await sleep(3000);

    // 3.3 Klien Saya
    console.log('👉 Membuka Daftar Klien Aktif Bimbingan Trainer...');
    await page.goto(`${BASE_URL}/trainer/klien-saya`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 350);
    await sleep(3000);

    // Logout Trainer secara bersih dan bersiap login Admin (portal khusus /admin/login)
    await logoutAndGoTo(page, desktopContext, '/admin/login');

    // -------------------------------------------------------------------------
    // 4. ADMIN BACKOFFICE - NAVIGASI RUNTUT MENU SIDEBAR (~110 Detik)
    // -------------------------------------------------------------------------
    console.log('\n🎬 [MODUL 4]: Admin Backoffice - Navigasi Runtut Sidebar');
    console.log('👉 Login sebagai Admin Utama di Portal Admin (/admin/login)...');
    await humanType(page, 'input[name="email"], input#email', 'admin@fitcore.test', 45);
    await humanType(page, 'input[name="password"], input#password', 'password123', 45);
    await highlightAndClick(page, 'button[type="submit"]', 3000);
    await page.waitForURL('**/admin/dashboard**', { timeout: 10000 }).catch(() => {});

    // 4.1 Menu 1: Dashboard Performa Gym
    console.log('👉 [Admin Menu 1]: Dashboard Performa Gym & Grafik Analitik...');
    await sleep(2000);
    await smoothScroll(page, 450);
    await sleep(3000);
    await smoothScroll(page, -450);
    await sleep(1500);

    // 4.2 Menu 2: Paket & Harga (Membership)
    console.log('👉 [Admin Menu 2]: Mengklik Menu "Paket & Harga" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Paket & Harga")', 2500);
    await smoothScroll(page, 400);
    await sleep(3000);
    await smoothScroll(page, -400);
    await sleep(1500);

    // 4.3 Menu 3: Paket Sesi & Jadwal PT
    console.log('👉 [Admin Menu 3]: Mengklik Menu "Paket Sesi & Jadwal PT" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Paket Sesi & Jadwal PT")', 2500);
    await smoothScroll(page, 400);
    await sleep(3000);
    await smoothScroll(page, -400);
    await sleep(1500);

    // 4.4 Menu 4: Verifikasi Pembayaran
    console.log('👉 [Admin Menu 4]: Mengklik Menu "Verifikasi Pembayaran" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Verifikasi Pembayaran")', 2500);
    await smoothScroll(page, 400);
    await sleep(3000);
    await smoothScroll(page, -400);
    await sleep(1500);

    // 4.5 Menu 5: Scanner Check-in Meja Resepsionis
    console.log('👉 [Admin Menu 5]: Mengklik Menu "Scanner Check-in" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Scanner Check-in")', 2500);
    await smoothScroll(page, 350);
    await sleep(3500);

    // 4.6 Menu 6: Manajemen Member
    console.log('👉 [Admin Menu 6]: Mengklik Menu "Manajemen Member" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Manajemen Member")', 2500);
    await smoothScroll(page, 400);
    await sleep(3500);
    await smoothScroll(page, -400);
    await sleep(1500);

    console.log('✅ Sesi Desktop Extended selesai direkam.');
    await desktopContext.close();

    // =========================================================================
    // SECTION 2: MOBILE APP INTEGRATION (LIGHT MODE EXPERIENCE) (~80 Detik)
    // =========================================================================
    console.log('\n🎬 [MODUL 5]: Mobile App Showcase (iPhone 14 Emulation - Light Mode)');
    const mobileDevice = devices['iPhone 14 Pro'];
    const mobileContext = await browser.newContext({
        ...mobileDevice,
        recordVideo: {
            dir: path.join(RECORD_DIR, 'mobile-extended'),
            size: { width: 390, height: 844 },
        },
    });

    const mPage = await mobileContext.newPage();

    // 5.1 Mobile Login Member
    console.log('👉 Membuka Mobile Login FitCore (Member)...');
    await mPage.goto(`${BASE_URL}/m/login`, { waitUntil: 'domcontentloaded' });
    await sleep(2000);

    await humanType(mPage, 'input[name="email"], input#email', 'member@gym.test', 45);
    await humanType(mPage, 'input[name="password"], input#password', 'password123', 45);
    await highlightAndClick(mPage, 'button[type="submit"]', 3000);
    await mPage.waitForURL('**/m/dashboard**', { timeout: 10000 }).catch(() => {});

    // 5.2 Mobile Member Dashboard
    console.log('👉 Menampilkan Mobile Dashboard (Light Theme #F6F6F7)...');
    await sleep(2000);
    await smoothScroll(mPage, 350);
    await sleep(3000);
    await smoothScroll(mPage, -350);
    await sleep(2000);

    // 5.3 Digital Member Card di Smartphone
    console.log('👉 Membuka Mobile Digital Member Card...');
    await mPage.goto(`${BASE_URL}/m/card`, { waitUntil: 'domcontentloaded' });
    await sleep(3500);

    // 5.4 Sesi PT & Kuota Mobile
    console.log('👉 Membuka Sesi Latihan PT & Kuota Mobile...');
    await mPage.goto(`${BASE_URL}/m/pt-sessions`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(mPage, 300);
    await sleep(3000);

    // 5.5 Katalog Paket Sesi PT Mobile
    console.log('👉 Membuka Katalog Pembelian Paket PT Mobile...');
    await mPage.goto(`${BASE_URL}/m/pt-packages`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(mPage, 400);
    await sleep(3500);

    // 5.6 Mobile Trainer Experience: Logout Member lalu Login sebagai Coach Rama
    console.log('👉 Logout Member di Mobile dan beralih ke Akun Trainer...');
    await logoutAndGoTo(mPage, mobileContext, '/m/login');

    console.log('👉 Login Trainer di Mobile (Coach Rama)...');
    await humanType(mPage, 'input[name="email"], input#email', 'rama@gym.test', 45);
    await humanType(mPage, 'input[name="password"], input#password', 'password123', 45);
    await highlightAndClick(mPage, 'button[type="submit"]', 3000);
    await mPage.waitForURL('**/m/trainer/dashboard**', { timeout: 10000 }).catch(() => {});

    console.log('👉 Menampilkan Mobile Dashboard Pelatih (Trainer View)...');
    await mPage.goto(`${BASE_URL}/m/trainer/dashboard`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(mPage, 350);
    await sleep(3000);

    console.log('✅ Sesi Mobile Extended selesai direkam.');
    await mobileContext.close();

    await browser.close();
    console.log('\n🎉 Selesai! Seluruh rekaman extended (5 - 10 Menit) telah tersimpan di:');
    console.log(`📁 ${RECORD_DIR}`);
    console.log('Silakan baca "scripts/VOICE_OVER_SCRIPT.md" untuk naskah audio lengkap berdurasi ~7.5 Menit!');
}

runExtendedDemo().catch((err) => {
    console.error('❌ Terjadi kesalahan pada script Playwright:', err);
    process.exit(1);
});
