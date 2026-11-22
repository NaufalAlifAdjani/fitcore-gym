/**
 * FitCore - Automated Video Walkthrough: 3. EKOSISTEM PERSONAL TRAINER
 *
 * Merekam seluruh fitur yang dapat diakses oleh Personal Trainer (Coach Rama):
 * - Login Pelatih (rama@gym.test)
 * - Dashboard Trainer (Shift Kerja Sore, Jadwal Hari Ini, Estimasi Komisi)
 * - Jadwal & Kalender Sesi PT (/trainer/jadwal-sesi)
 * - Klien Bimbingan Aktif (/trainer/klien-saya)
 * - Transparansi Kalkulasi Komisi Otomatis
 * - Scanner Check-in Petugas (/admin/checkin)
 * - Pengalaman Mobile Trainer App (Light Theme /m/trainer/dashboard, /m/trainer/sessions, dll)
 *
 * Output Rekaman: demo-recordings/3-trainer/
 * Jalankan: node scripts/demo-trainer.js
 */

import { chromium, devices } from 'playwright';
import fs from 'fs';
import path from 'path';

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const RECORD_DIR = path.resolve('./demo-recordings/3-trainer');

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

async function runTrainerDemo() {
    console.log('🚀 [3/4] Memulai Perekaman Demo Ekosistem Personal Trainer FitCore...');

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

    // 1.1 Login Trainer
    console.log('👉 Login Akun Trainer (Coach Rama - rama@gym.test)...');
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'domcontentloaded' });
    await humanType(page, 'input[name="email"], input#email', 'rama@gym.test', 40);
    await humanType(page, 'input[name="password"], input#password', 'password123', 40);
    await highlightAndClick(page, 'button[type="submit"]', 3000);
    await page.waitForURL('**/trainer/dashboard**', { timeout: 10000 }).catch(() => {});

    // 1.2 Dashboard Trainer
    console.log('👉 Eksplorasi Dashboard Trainer (Shift Kerja, Metrik, & Estimasi Komisi)...');
    await sleep(2000);
    await smoothScroll(page, 450);
    await sleep(3000);
    await smoothScroll(page, -450);
    await sleep(2000);

    // 1.3 Jadwal Sesi PT & Kalender
    console.log('👉 Membuka Kalender & Manajemen Jadwal Sesi PT (/trainer/jadwal-sesi)...');
    await page.goto(`${BASE_URL}/trainer/jadwal-sesi`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 450);
    await sleep(3000);
    await smoothScroll(page, -450);
    await sleep(1500);

    // 1.4 Klien Saya
    console.log('👉 Membuka Daftar Klien Bimbingan Aktif (/trainer/klien-saya)...');
    await page.goto(`${BASE_URL}/trainer/klien-saya`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 400);
    await sleep(3000);

    // 1.5 Scanner Check-in Petugas
    console.log('👉 Membuka Scanner Check-in Petugas Gym (/admin/checkin)...');
    await page.goto(`${BASE_URL}/admin/checkin`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(page, 350);
    await sleep(2500);

    await desktopContext.close();

    // 2. MOBILE TRAINER EXPERIENCE
    console.log('👉 Memulai Perekaman Mobile Trainer App (iPhone 14 Pro)...');
    const mobileContext = await browser.newContext({
        ...devices['iPhone 14 Pro'],
        recordVideo: {
            dir: path.join(RECORD_DIR, 'mobile'),
            size: { width: 390, height: 844 },
        },
    });

    const mPage = await mobileContext.newPage();

    // Mobile Login Trainer
    await mPage.goto(`${BASE_URL}/m/login`, { waitUntil: 'domcontentloaded' });
    await humanType(mPage, 'input[name="email"], input#email', 'rama@gym.test', 40);
    await humanType(mPage, 'input[name="password"], input#password', 'password123', 40);
    await highlightAndClick(mPage, 'button[type="submit"]', 3000);
    await mPage.waitForURL('**/m/trainer/dashboard**', { timeout: 10000 }).catch(() => {});

    // Mobile Trainer Dashboard
    console.log('👉 Mobile Trainer Dashboard...');
    await sleep(2000);
    await smoothScroll(mPage, 350);
    await sleep(2500);
    await smoothScroll(mPage, -350);
    await sleep(1500);

    // Mobile Trainer Sesi
    console.log('👉 Mobile Trainer Sesi Latihan (/m/trainer/sessions)...');
    await mPage.goto(`${BASE_URL}/m/trainer/sessions`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(mPage, 350);
    await sleep(2500);

    // Mobile Trainer Klien
    console.log('👉 Mobile Trainer Klien Aktif (/m/trainer/clients)...');
    await mPage.goto(`${BASE_URL}/m/trainer/clients`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(mPage, 350);
    await sleep(2500);

    // Mobile Trainer Komisi
    console.log('👉 Mobile Trainer Transparansi Komisi (/m/trainer/commissions)...');
    await mPage.goto(`${BASE_URL}/m/trainer/commissions`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await smoothScroll(mPage, 350);
    await sleep(3000);

    await mobileContext.close();
    await browser.close();

    console.log('✅ [3/4] Perekaman Ekosistem Personal Trainer selesai!');
    console.log(`📁 Video tersimpan di: ${RECORD_DIR}`);
}

runTrainerDemo().catch(console.error);
