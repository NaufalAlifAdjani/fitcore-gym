/**
 * FitCore - Automated Video Walkthrough: 2. EKOSISTEM MEMBER
 *
 * Merekam seluruh fitur yang dapat diakses oleh Member:
 * - Login Member (member@gym.test)
 * - Dashboard Member (Ringkasan Keanggotaan, Sesi PT, & Statistik)
 * - Kartu Member Digital dengan Dynamic QR Code (/member/card)
 * - Katalog Paket Membership (/membership/packages)
 * - Katalog Pembelian Paket Sesi PT (/member/pt-packages)
 * - Pemilihan Pelatih Favorit (/member/pt-packages/select-trainer)
 * - Manajemen Sesi Latihan PT & Kuota (/pt-sessions)
 * - Formulir Reservasi Sesi Baru (/pt-sessions/create)
 * - Riwayat Transaksi Pembayaran & E-Invoice (/member/riwayat-transaksi)
 * - Profil Member (/profile)
 * - Pengalaman Mobile Member App (Light Theme /m/dashboard, /m/card, dll)
 *
 * Output Rekaman: demo-recordings/2-member/
 * Jalankan: node scripts/demo-member.js
 */

import { chromium, devices } from 'playwright';
import fs from 'fs';
import path from 'path';

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const RECORD_DIR = path.resolve('./demo-recordings/2-member');

if (!fs.existsSync(RECORD_DIR)) {
    fs.mkdirSync(RECORD_DIR, { recursive: true });
}

const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

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

async function highlightAndClick(page, selector, delayAfter = 2000) {
    try {
        const el = page.locator(selector).first();
        await el.waitFor({ state: 'visible', timeout: 8000 });
        await el.scrollIntoViewIfNeeded();
        await el.click();
        await sleep(delayAfter);
    } catch (e) {
        await page.click(selector).catch(() => {});
        await sleep(delayAfter);
    }
}

async function humanType(page, selector, text, delayMs = 50) {
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

async function runMemberDemo() {
    console.log('🚀 [2/4] Memulai Perekaman Demo Ekosistem Member FitCore...');

    const browser = await chromium.launch({
        headless: false,
        slowMo: 50,
        args: ['--start-maximized', '--window-size=1280,820'],
    });

    // 1. DESKTOP EXPERIENCE
    const desktopContext = await browser.newContext({
        viewport: { width: 1280, height: 720 },
        recordVideo: {
            dir: path.join(RECORD_DIR, 'desktop'),
            size: { width: 1280, height: 720 },
        },
    });

    const page = await desktopContext.newPage();

    // 1.1 Login Member
    console.log('👉 Login Akun Member (member@gym.test)...');
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'domcontentloaded' });
    await humanType(page, 'input[name="email"], input#email', 'member@gym.test', 40);
    await humanType(page, 'input[name="password"], input#password', 'password123', 40);
    await highlightAndClick(page, 'button[type="submit"]', 3000);
    await page.waitForURL('**/member/dashboard**', { timeout: 10000 }).catch(() => {});

    // 1.2 Dashboard Member
    console.log('👉 Eksplorasi Dashboard Member...');
    await sleep(2000);
    await smoothScroll(page, 450);
    await sleep(2500);
    await smoothScroll(page, -450);
    await sleep(2000);

    // 1.3 Kartu Digital Member (Dynamic QR Code)
    console.log('👉 Membuka Kartu Member Digital & Dynamic QR Code (/member/card)...');
    await page.goto(`${BASE_URL}/member/card`, { waitUntil: 'domcontentloaded' });
    await sleep(3500);

    // 1.4 Katalog Paket Membership
    console.log('👉 Membuka Katalog Paket Keanggotaan (/membership/packages)...');
    await page.goto(`${BASE_URL}/membership/packages`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 400);
    await sleep(2500);

    // 1.5 Katalog Paket Sesi PT
    console.log('👉 Membuka Katalog Paket Sesi PT (/member/pt-packages)...');
    await page.goto(`${BASE_URL}/member/pt-packages`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 400);
    await sleep(2500);

    // 1.6 Pilih Pelatih Favorit
    console.log('👉 Membuka Halaman Pemilihan Personal Trainer (/member/pt-packages/select-trainer)...');
    await page.goto(`${BASE_URL}/member/pt-packages/select-trainer`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 400);
    await sleep(2500);

    // 1.7 Sesi Latihan PT & Kuota
    console.log('👉 Membuka Manajemen Sesi Latihan PT (/pt-sessions)...');
    await page.goto(`${BASE_URL}/pt-sessions`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 350);
    await sleep(2500);

    // 1.8 Booking Sesi Latihan Baru
    console.log('👉 Membuka Formulir Reservasi Sesi Baru (/pt-sessions/create)...');
    await page.goto(`${BASE_URL}/pt-sessions/create`, { waitUntil: 'domcontentloaded' });
    await sleep(2000);
    const notesInput = page.locator('textarea[name="notes"], textarea').first();
    if (await notesInput.isVisible()) {
        await humanType(page, 'textarea[name="notes"], textarea', 'Fokus latihan: Program Hipertrofi Dada dan koreksi postur bersama Coach Rama.', 35);
        await sleep(1500);
    }
    await smoothScroll(page, 350);
    await sleep(3000);

    // 1.9 Riwayat Transaksi & Invoice
    console.log('👉 Membuka Riwayat Transaksi Pembayaran (/member/riwayat-transaksi)...');
    await page.goto(`${BASE_URL}/member/riwayat-transaksi`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 350);
    await sleep(3000);

    // 1.10 Profil Member
    console.log('👉 Membuka Pengaturan Profil Member (/profile)...');
    await page.goto(`${BASE_URL}/profile`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 350);
    await sleep(2000);

    await desktopContext.close();

    // 2. MOBILE MEMBER EXPERIENCE
    console.log('👉 Memulai Perekaman Mobile Member App (iPhone 14 Pro)...');
    const mobileContext = await browser.newContext({
        ...devices['iPhone 14 Pro'],
        recordVideo: {
            dir: path.join(RECORD_DIR, 'mobile'),
            size: { width: 390, height: 844 },
        },
    });

    const mPage = await mobileContext.newPage();

    // Mobile Login
    await mPage.goto(`${BASE_URL}/m/login`, { waitUntil: 'domcontentloaded' });
    await humanType(mPage, 'input[name="email"], input#email', 'member@gym.test', 40);
    await humanType(mPage, 'input[name="password"], input#password', 'password123', 40);
    await highlightAndClick(mPage, 'button[type="submit"]', 3000);
    await mPage.waitForURL('**/m/dashboard**', { timeout: 10000 }).catch(() => {});

    // Mobile Dashboard
    console.log('👉 Mobile Member Dashboard...');
    await sleep(2000);
    await smoothScroll(mPage, 350);
    await sleep(2500);
    await smoothScroll(mPage, -350);
    await sleep(2000);

    // Mobile Digital Card
    console.log('👉 Mobile Digital Card & Dynamic QR...');
    await mPage.goto(`${BASE_URL}/m/card`, { waitUntil: 'domcontentloaded' });
    await sleep(3500);

    // Mobile PT Sessions
    console.log('👉 Mobile Sesi Latihan PT...');
    await mPage.goto(`${BASE_URL}/m/pt-sessions`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(mPage, 350);
    await sleep(2500);

    // Mobile PT Packages
    console.log('👉 Mobile Katalog Paket PT...');
    await mPage.goto(`${BASE_URL}/m/pt-packages`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(mPage, 400);
    await sleep(3000);

    await mobileContext.close();
    await browser.close();

    console.log('✅ [2/4] Perekaman Ekosistem Member selesai!');
    console.log(`📁 Video tersimpan di: ${RECORD_DIR}`);
}

runMemberDemo().catch(console.error);
