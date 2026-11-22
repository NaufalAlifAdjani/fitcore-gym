/**
 * FitCore - Master Runner (Menjalankan seluruh 4 demo secara berurutan)
 * 1. Landing Page
 * 2. Member
 * 3. Trainer
 * 4. Admin
 */

import { spawn } from 'child_process';
import path from 'path';

const scripts = [
    { name: '1. Landing Page', file: 'scripts/demo-landing.js' },
    { name: '2. Member Ecosystem', file: 'scripts/demo-member.js' },
    { name: '3. Personal Trainer Ecosystem', file: 'scripts/demo-trainer.js' },
    { name: '4. Admin Backoffice', file: 'scripts/demo-admin.js' },
];

async function runScript(script) {
    return new Promise((resolve, reject) => {
        console.log(`\n======================================================`);
        console.log(`▶️ MENJALANKAN DEMO: ${script.name}`);
        console.log(`======================================================\n`);

        const proc = spawn('node', [script.file], { stdio: 'inherit' });

        proc.on('close', (code) => {
            if (code === 0) {
                console.log(`\n✅ ${script.name} SELESAI DENGAN SUKSES!`);
                resolve();
            } else {
                reject(new Error(`${script.name} gagal dengan exit code ${code}`));
            }
        });
    });
}

async function main() {
    console.log('🎬 Memulai Perekaman Lengkap Seluruh Modul FitCore...');
    for (const s of scripts) {
        await runScript(s);
    }
    console.log('\n🎉 SELURUH 4 MODUL DEMO TELAH SELESAI DIREKAM!');
    console.log('📁 Folder Rekaman: demo-recordings/');
}

main().catch((err) => {
    console.error('❌ Terjadi kesalahan:', err);
    process.exit(1);
});
