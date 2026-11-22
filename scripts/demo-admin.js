/**
 * FitCore - Automated Video Walkthrough: 4. BACKOFFICE ADMINISTRATOR
 *
 * Merekam seluruh fitur yang dapat diakses oleh Administrator:
 * - Login Khusus Admin (/admin/login - admin@fitcore.test)
 * - Menu 1: Dashboard Performa Gym (/admin/dashboard)
 * - Menu 2: Paket & Harga Membership (/admin/packages)
 * - Menu 3: Paket Sesi & Jadwal PT (/admin/pt-sessions)
 * - Menu 4: Verifikasi Pembayaran Manual (/admin/verifikasi-pembayaran)
 * - Menu 5: Scanner Check-in Meja Resepsionis (/admin/checkin)
 * - Menu 6: Manajemen Basis Data Member (/admin/member-management)
 * - Menu 7: Manajemen Personal Trainer (/admin/pt-management)
 * - Menu 8: Pengaturan Sistem (/admin/settings)
 *
 * Output Rekaman: demo-recordings/4-admin/
 * Jalankan: node scripts/demo-admin.js
 */

import { chromium } from 'playwright';
import fs from 'fs';
import path from 'path';

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const RECORD_DIR = path.resolve('./demo-recordings/4-admin');

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

async function runAdminDemo() {
    console.log('🚀 [4/4] Memulai Perekaman Demo Backoffice Administrator FitCore...');

    const browser = await chromium.launch({
        headless: false,
        slowMo: 50,
        args: ['--start-maximized', '--window-size=1280,820'],
    });

    const context = await browser.newContext({
        viewport: { width: 1280, height: 720 },
        recordVideo: {
            dir: RECORD_DIR,
            size: { width: 1280, height: 720 },
        },
    });

    const page = await context.newPage();

    // 1. Login Khusus Admin
    console.log('👉 Membuka Portal Login Admin (/admin/login)...');
    await page.goto(`${BASE_URL}/admin/login`, { waitUntil: 'domcontentloaded' });
    await humanType(page, 'input[name="email"], input#email', 'admin@fitcore.test', 40);
    await humanType(page, 'input[name="password"], input#password', 'password123', 40);
    await highlightAndClick(page, 'button[type="submit"]', 3000);
    await page.waitForURL('**/admin/dashboard**', { timeout: 10000 }).catch(() => {});

    // 2. Menu 1: Dashboard Performa Gym
    console.log('👉 [Menu 1]: Dashboard Performa Gym & Grafik Analitik...');
    await sleep(2000);
    await smoothScroll(page, 450);
    await sleep(3000);
    await smoothScroll(page, -450);
    await sleep(1500);

    // 3. Menu 2: Paket & Harga (Membership)
    console.log('👉 [Menu 2]: Mengklik Menu "Paket & Harga" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Paket & Harga")', 2500);
    await smoothScroll(page, 400);
    await sleep(3000);
    await smoothScroll(page, -400);
    await sleep(1500);

    // 4. Menu 3: Paket Sesi & Jadwal PT
    console.log('👉 [Menu 3]: Mengklik Menu "Paket Sesi & Jadwal PT" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Paket Sesi & Jadwal PT")', 2500);
    await smoothScroll(page, 400);
    await sleep(3000);
    await smoothScroll(page, -400);
    await sleep(1500);

    // 5. Menu 4: Verifikasi Pembayaran
    console.log('👉 [Menu 4]: Mengklik Menu "Verifikasi Pembayaran" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Verifikasi Pembayaran")', 2500);
    await smoothScroll(page, 400);
    await sleep(3000);
    await smoothScroll(page, -400);
    await sleep(1500);

    // 6. Menu 5: Scanner Check-in Meja Resepsionis
    console.log('👉 [Menu 5]: Mengklik Menu "Scanner Check-in" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Scanner Check-in")', 2500);
    await smoothScroll(page, 350);
    await sleep(3500);

    // 7. Menu 6: Manajemen Member
    console.log('👉 [Menu 6]: Mengklik Menu "Manajemen Member" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Manajemen Member")', 2500);
    await smoothScroll(page, 400);
    await sleep(3500);
    await smoothScroll(page, -400);
    await sleep(1500);

    // 8. Menu 7: Manajemen Trainer
    console.log('👉 [Menu 7]: Mengklik Menu "Manajemen Trainer" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Manajemen Trainer")', 2500);
    await smoothScroll(page, 400);
    await sleep(3500);
    await smoothScroll(page, -400);
    await sleep(1500);

    // 9. Menu 8: Pengaturan Sistem
    console.log('👉 [Menu 8]: Mengklik Menu "Pengaturan" pada Sidebar...');
    await highlightAndClick(page, 'aside a:has-text("Pengaturan")', 2500);
    await smoothScroll(page, 350);
    await sleep(3000);

    await context.close();
    await browser.close();

    console.log('✅ [4/4] Perekaman Backoffice Administrator selesai!');
    console.log(`📁 Video tersimpan di: ${RECORD_DIR}`);
}

runAdminDemo().catch(console.error);
