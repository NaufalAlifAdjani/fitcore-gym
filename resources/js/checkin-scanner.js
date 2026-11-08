import { Html5Qrcode } from 'html5-qrcode';

/**
 * QR Code Check-In Scanner
 *
 * Reads QR codes via device camera and submits the token to the
 * backend for validation. All check-in decisions are made server-side.
 */

// Resolve the POST URL from the Blade data attribute (set in checkin.blade.php)
const CHECKIN_STORE_URL = document.getElementById('checkin-page')?.dataset?.storeUrl ?? '';

let html5QrCode = null;
let isProcessing = false;

// ── DOM References ──────────────────────────────────────────────────────────
const startBtn      = document.getElementById('btn-start-scanner');
const stopBtn       = document.getElementById('btn-stop-scanner');
const resultBox     = document.getElementById('scan-result');
const statusText    = document.getElementById('scanner-status');
const cameraSelect  = document.getElementById('camera-select');
const cameraWrapper = document.getElementById('camera-wrapper');

// ── Camera Initialization ───────────────────────────────────────────────────
async function loadCameras() {
    try {
        const devices = await Html5Qrcode.getCameras();
        if (!devices || devices.length === 0) {
            showError('Tidak ada kamera yang ditemukan di perangkat ini.');
            startBtn.disabled = true;
            return;
        }
        cameraSelect.innerHTML = '';
        devices.forEach((device, index) => {
            const option = document.createElement('option');
            option.value = device.id;
            option.textContent = device.label || `Kamera ${index + 1}`;
            cameraSelect.appendChild(option);
        });
        // Prefer back camera for admin devices
        const backCamera = devices.find(d => /back|rear|environment/i.test(d.label));
        if (backCamera) cameraSelect.value = backCamera.id;
    } catch (err) {
        console.error('Camera enumeration error:', err);
        showError('Gagal mengakses daftar kamera. Pastikan izin kamera diberikan.');
        startBtn.disabled = true;
    }
}

// ── Scanner Lifecycle ───────────────────────────────────────────────────────
async function startScanner() {
    const cameraId = cameraSelect.value;
    if (!cameraId) {
        showError('Pilih kamera terlebih dahulu.');
        return;
    }

    try {
        html5QrCode = new Html5Qrcode('qr-reader');

        setStatus('Menghubungkan kamera…', 'info');
        startBtn.disabled = true;
        stopBtn.disabled  = false;
        cameraWrapper.classList.remove('hidden');

        await html5QrCode.start(
            cameraId,
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            onScanFailure,
        );

        setStatus('Scanner aktif. Arahkan kamera ke QR Code member.', 'success');
    } catch (err) {
        console.error('Scanner start error:', err);
        handleCameraError(err);
        startBtn.disabled = false;
        stopBtn.disabled  = true;
    }
}

async function stopScanner() {
    if (html5QrCode) {
        try {
            await html5QrCode.stop();
        } catch (_) {
            // Ignore stop errors — camera may already be closed
        }
        try {
            await html5QrCode.clear();
        } catch (_) {
            // Ignore clear errors
        }
        html5QrCode = null;
    }
    startBtn.disabled = false;
    stopBtn.disabled  = true;
    cameraWrapper.classList.add('hidden');
    setStatus('Scanner dihentikan.', 'idle');
}

// ── Scan Callbacks ──────────────────────────────────────────────────────────
async function onScanSuccess(decodedText) {
    // Prevent duplicate callbacks or rapid repeated scans
    if (isProcessing) {
        return;
    }
    isProcessing = true;

    // Hentikan scanner dan matikan kamera otomatis agar satu QR tidak diproses berulang kali
    await stopScanner();

    setStatus('QR Code terbaca. Memvalidasi…', 'info');
    clearResult();

    try {
        await submitToken(decodedText);
    } catch (err) {
        console.error('Submit error:', err);
        showResult(false, 'Terjadi kesalahan saat memproses check-in.');
    } finally {
        isProcessing = false;
    }
}

function onScanFailure(_errorMessage) {
    // Silently ignore — continuous scan failures are expected while camera is searching
}

// ── API Request ─────────────────────────────────────────────────────────────
async function submitToken(token) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    let response;
    try {
        response = await fetch(CHECKIN_STORE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken ?? '',
            },
            body: JSON.stringify({ qr_token: token }),
        });
    } catch (networkError) {
        console.error('Network error:', networkError);
        showResult(false, 'Gagal terhubung ke server. Periksa koneksi jaringan.');
        setStatus('Kesalahan jaringan. Scanner dihentikan.', 'error');
        return;
    }

    let data;
    try {
        data = await response.json();
    } catch {
        showResult(false, 'Respons server tidak valid.');
        setStatus('Error server. Scanner dihentikan.', 'error');
        return;
    }

    if (data.success) {
        showResult(true, null, data);
        setStatus('Check-in berhasil. Klik "Scan Member Berikutnya" untuk melanjutkan.', 'success');
        addCheckInToTable(data);
    } else {
        showResult(false, data.message ?? 'Check-in ditolak.');
        setStatus(data.message ?? 'Check-in ditolak.', 'warning');
    }
}

// ── Live Table Update ───────────────────────────────────────────────────────
function addCheckInToTable(data) {
    const tbody = document.getElementById('today-checkin-tbody');
    if (!tbody) return;

    // Hapus placeholder baris kosong jika ada
    const emptyRow = document.getElementById('empty-checkin-row');
    if (emptyRow) {
        emptyRow.remove();
    }

    const memberName = data.member_name ?? 'Member FitCore';
    const memberCode = data.member_code ? `ID: ${data.member_code}` : '';
    const packageName = data.membership ?? 'Membership FitCore';
    const validUntil = data.valid_until ?? '-';
    const checkInTime = data.checked_in_at ? `${data.checked_in_at} WIB` : '-';
    const operatorName = data.checked_by_name ?? 'Admin';

    const tr = document.createElement('tr');
    tr.className = 'hover:bg-slate-50/80 transition-colors bg-emerald-50/70 duration-1000';
    tr.innerHTML = `
        <td class="py-3.5 px-4 font-mono font-semibold text-slate-700 whitespace-nowrap">
            ${escapeHtml(checkInTime)}
        </td>
        <td class="py-3.5 px-4">
            <div class="font-semibold text-[#16151A]">${escapeHtml(memberName)}</div>
            ${memberCode ? `<div class="font-mono text-[11px] text-[#565A66]">${escapeHtml(memberCode)}</div>` : ''}
        </td>
        <td class="py-3.5 px-4 font-medium text-slate-700">
            ${escapeHtml(packageName)}
        </td>
        <td class="py-3.5 px-4 text-[#565A66] whitespace-nowrap">
            ${escapeHtml(validUntil)}
        </td>
        <td class="py-3.5 px-4 text-[#565A66]">
            ${escapeHtml(operatorName)}
        </td>
        <td class="py-3.5 px-4 text-center whitespace-nowrap">
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Berhasil
            </span>
        </td>
    `;

    tbody.prepend(tr);

    // Fade highlight out smoothly
    setTimeout(() => {
        tr.classList.remove('bg-emerald-50/70');
    }, 2500);

    // Update counter badge
    const countEl = document.getElementById('today-checkin-count');
    if (countEl) {
        const currentCount = parseInt(countEl.textContent.trim(), 10) || 0;
        countEl.textContent = currentCount + 1;
    }
}

// ── UI Helpers ──────────────────────────────────────────────────────────────
function setStatus(message, type = 'idle') {
    statusText.textContent = message;
    statusText.className = 'text-sm font-inter font-medium';
    const colors = {
        idle:    'text-[#565A66]',
        info:    'text-blue-600',
        success: 'text-emerald-600',
        warning: 'text-amber-600',
        error:   'text-rose-600',
    };
    statusText.classList.add(colors[type] ?? 'text-[#565A66]');
}

function clearResult() {
    resultBox.innerHTML = '';
    resultBox.className = 'mt-6 rounded-2xl p-5 hidden';
}

function showResult(success, errorMessage = null, data = null) {
    resultBox.classList.remove('hidden');

    if (success && data) {
        resultBox.className = 'mt-6 rounded-2xl p-6 bg-emerald-50 border border-emerald-200 shadow-sm transition';
        resultBox.innerHTML = `
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-emerald-200/60">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-md shadow-emerald-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="font-poppins text-lg font-bold text-emerald-950">Check-in berhasil.</h3>
                        <p class="font-inter text-xs font-medium text-emerald-700">Data kehadiran telah dicatat ke sistem.</p>
                    </div>
                </div>

                <button
                    id="btn-scan-next"
                    type="button"
                    class="bg-[#ED1B45] text-white px-5 py-2.5 rounded-full font-inter font-semibold shadow-sm hover:bg-[#D1123D] transition flex items-center justify-center gap-2 cursor-pointer text-sm shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Scan Member Berikutnya</span>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 pt-4 text-xs font-inter">
                <div class="bg-white/90 p-3 rounded-xl border border-emerald-100 shadow-xs">
                    <span class="text-emerald-700 font-medium block">Nama Member</span>
                    <span class="font-poppins font-bold text-sm text-emerald-950 block truncate">${escapeHtml(data.member_name ?? '-')}</span>
                </div>
                <div class="bg-white/90 p-3 rounded-xl border border-emerald-100 shadow-xs">
                    <span class="text-emerald-700 font-medium block">Paket Membership</span>
                    <span class="font-poppins font-bold text-sm text-emerald-950 block truncate">${escapeHtml(data.membership ?? '-')}</span>
                </div>
                <div class="bg-white/90 p-3 rounded-xl border border-emerald-100 shadow-xs">
                    <span class="text-emerald-700 font-medium block">Masa Berlaku</span>
                    <span class="font-poppins font-bold text-sm text-emerald-950 block">${escapeHtml(data.valid_until ?? '-')}</span>
                </div>
                <div class="bg-white/90 p-3 rounded-xl border border-emerald-100 shadow-xs">
                    <span class="text-emerald-700 font-medium block">Waktu Check-In</span>
                    <span class="font-poppins font-bold text-sm text-emerald-950 block">${escapeHtml(data.checked_in_at ?? '-')} WIB</span>
                </div>
            </div>
        `;
    } else {
        resultBox.className = 'mt-6 rounded-2xl p-6 bg-rose-50 border border-rose-200 shadow-sm transition';
        resultBox.innerHTML = `
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-500 text-white shadow-md shadow-rose-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="font-poppins text-lg font-bold text-rose-950">Check-in Gagal</h3>
                        <p class="font-inter text-sm font-semibold text-rose-700 mt-0.5">${escapeHtml(errorMessage ?? 'Check-in ditolak.')}</p>
                    </div>
                </div>

                <button
                    id="btn-scan-next"
                    type="button"
                    class="bg-[#ED1B45] text-white px-5 py-2.5 rounded-full font-inter font-semibold shadow-sm hover:bg-[#D1123D] transition flex items-center justify-center gap-2 cursor-pointer text-sm shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Scan Member Berikutnya</span>
                </button>
            </div>
        `;
    }

    // Attach click listener ke tombol "Scan Member Berikutnya"
    const scanNextBtn = document.getElementById('btn-scan-next');
    if (scanNextBtn) {
        scanNextBtn.addEventListener('click', async () => {
            clearResult();
            await startScanner();
        });
    }
}

function showError(message) {
    setStatus(message, 'error');
    showResult(false, message);
}

function handleCameraError(err) {
    const msg = err?.message ?? String(err);
    if (/permission|denied|not allowed/i.test(msg)) {
        showError('Izin kamera ditolak. Berikan izin kamera di pengaturan browser Anda, lalu muat ulang halaman.');
    } else if (/not found|no device/i.test(msg)) {
        showError('Kamera tidak ditemukan. Pastikan kamera terhubung dan tidak digunakan aplikasi lain.');
    } else {
        showError(`Kamera gagal dibuka: ${msg}`);
    }
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(String(str)));
    return div.innerHTML;
}

// ── Page Cleanup ─────────────────────────────────────────────────────────────
window.addEventListener('beforeunload', stopScanner);
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'hidden' && html5QrCode) {
        stopScanner();
    }
});

// ── Event Listeners ──────────────────────────────────────────────────────────
startBtn.addEventListener('click', startScanner);
stopBtn.addEventListener('click', stopScanner);

// ── Initialise ───────────────────────────────────────────────────────────────
loadCameras();
