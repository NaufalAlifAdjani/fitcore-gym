/**
 * FitCore - Automated Video Walkthrough: 1. LANDING PAGE & PORTAL PUBLIK
 *
 * Merekam seluruh fitur publik FitCore:
 * - Halaman Beranda (Hero Banner, Visi, Fasilitas, Fitur Unggulan)
 * - Katalog Paket Membership Publik (/packages)
 * - Showcase & Profil Pelatih (/trainers)
 * - Halaman Tentang FitCore (/about)
 * - Tampilan Mobile Responsive Portal Publik
 *
 * Output Rekaman: demo-recordings/1-landing/
 * Jalankan: node scripts/demo-landing.js
 */

import { chromium, devices } from 'playwright';
import fs from 'fs';
import path from 'path';

const BASE_URL = process.env.BASE_URL || 'http://127.0.0.1:8000';
const RECORD_DIR = path.resolve('./demo-recordings/1-landing');

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

async function runLandingDemo() {
    console.log('🚀 [1/4] Memulai Perekaman Demo Landing Page & Portal Publik FitCore...');

    const browser = await chromium.launch({
        headless: false,
        slowMo: 50,
        args: ['--start-maximized', '--window-size=1280,820'],
    });

    // 1. DESKTOP VIEWPORT
    const desktopContext = await browser.newContext({
        viewport: { width: 1280, height: 720 },
        recordVideo: {
            dir: path.join(RECORD_DIR, 'desktop'),
            size: { width: 1280, height: 720 },
        },
    });

    const page = await desktopContext.newPage();

    // 1.1 Halaman Beranda (Home)
    console.log('👉 Membuka Halaman Beranda FitCore...');
    await page.goto(`${BASE_URL}/`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);

    // Scroll Hero & Fitur Utama
    await smoothScroll(page, 500);
    await sleep(2000);
    await smoothScroll(page, 600);
    await sleep(2000);
    await smoothScroll(page, -1100);
    await sleep(1500);

    // 1.2 Halaman Paket Membership Publik
    console.log('👉 Membuka Katalog Paket Keanggotaan Publik (/packages)...');
    await highlightAndClick(page, 'nav a[href*="packages"], a:has-text("Paket")', 2500);
    await smoothScroll(page, 500);
    await sleep(2500);
    await smoothScroll(page, -500);
    await sleep(1500);

    // 1.3 Halaman Pelatih Publik
    console.log('👉 Membuka Profil Pelatih / Personal Trainer (/trainers)...');
    await highlightAndClick(page, 'nav a[href*="trainers"], a:has-text("Trainer")', 2500);
    await smoothScroll(page, 500);
    await sleep(2500);

    // 1.4 Halaman Tentang Kami
    console.log('👉 Membuka Halaman Tentang FitCore (/about)...');
    await highlightAndClick(page, 'nav a[href*="about"], a:has-text("Tentang")', 2500);
    await smoothScroll(page, 450);
    await sleep(2500);

    await desktopContext.close();

    // 2. MOBILE VIEWPORT
    console.log('👉 Menampilkan Tampilan Mobile Responsive Portal Publik...');
    const mobileContext = await browser.newContext({
        ...devices['iPhone 14 Pro'],
        recordVideo: {
            dir: path.join(RECORD_DIR, 'mobile'),
            size: { width: 390, height: 844 },
        },
    });

    const mPage = await mobileContext.newPage();
    await mPage.goto(`${BASE_URL}/m`, { waitUntil: 'domcontentloaded' });
    await sleep(2000);
    await smoothScroll(mPage, 450);
    await sleep(2000);
    await mPage.goto(`${BASE_URL}/m/packages-info`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);
    await mPage.goto(`${BASE_URL}/m/trainers`, { waitUntil: 'domcontentloaded' });
    await sleep(2500);

    await mobileContext.close();
    await browser.close();

    console.log('✅ [1/4] Perekaman Landing Page selesai!');
    console.log(`📁 Video tersimpan di: ${RECORD_DIR}`);
}

runLandingDemo().catch(console.error);
