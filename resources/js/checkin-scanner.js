import { Html5Qrcode } from 'html5-qrcode';

/**
 * QR Code Check-In Scanner
 *
 * Reads QR codes via device camera and submits the token to the
 * backend for validation. All check-in decisions are made server-side.
 */

const SCAN_COOLDOWN_MS = 3000; // Minimum ms between submissions for same token

// Resolve the POST URL from the Blade data attribute (set in checkin.blade.php)
const CHECKIN_STORE_URL = document.getElementById('checkin-page')?.dataset?.storeUrl ?? '';


let html5QrCode = null;
let isProcessing = false;
let lastSubmittedToken = null;
let lastSubmitTime = 0;

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
            await html5QrCode.clear();
        } catch (_) {
            // Ignore stop errors — camera may already be closed
        }
        html5QrCode = null;
    }
    startBtn.disabled = false;
    stopBtn.disabled  = true;
    cameraWrapper.classList.add('hidden');
    setStatus('Scanner dihentikan.', 'idle');
    isProcessing = false;
}

// ── Scan Callbacks ──────────────────────────────────────────────────────────
async function onScanSuccess(decodedText) {
    // Prevent duplicate rapid submissions
    const now = Date.now();
    if (
        isProcessing ||
        (decodedText === lastSubmittedToken && now - lastSubmitTime < SCAN_COOLDOWN_MS)
    ) {
        return;
    }

    isProcessing       = true;
    lastSubmittedToken = decodedText;
    lastSubmitTime     = now;

    setStatus('QR Code terbaca. Memvalidasi…', 'info');
    clearResult();

    try {
        await submitToken(decodedText);
    } finally {
        // Allow re-scanning after cooldown
        setTimeout(() => { isProcessing = false; }, SCAN_COOLDOWN_MS);
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
        setStatus('Kesalahan jaringan. Scanner siap.', 'error');
        return;
    }

    let data;
    try {
        data = await response.json();
    } catch {
        showResult(false, 'Respons server tidak valid.');
        setStatus('Error server. Scanner siap.', 'error');
        return;
    }

    if (data.success) {
        showResult(true, null, data);
        setStatus('Check-in berhasil! Scanner siap untuk member berikutnya.', 'success');
    } else {
        showResult(false, data.message ?? 'Check-in ditolak.');
        setStatus('Check-in ditolak. Scanner siap.', 'warning');
    }
}

// ── UI Helpers ──────────────────────────────────────────────────────────────
function setStatus(message, type = 'idle') {
    statusText.textContent = message;
    statusText.className = 'text-sm font-medium';
    const colors = {
        idle:    'text-gray-500',
        info:    'text-blue-600',
        success: 'text-green-600',
        warning: 'text-yellow-600',
        error:   'text-red-600',
    };
    statusText.classList.add(colors[type] ?? 'text-gray-500');
}

function clearResult() {
    resultBox.innerHTML = '';
    resultBox.className = 'mt-6 rounded-xl p-5 hidden';
}

function showResult(success, errorMessage = null, data = null) {
    resultBox.classList.remove('hidden');

    if (success && data) {
        resultBox.className = 'mt-6 rounded-xl p-5 bg-green-50 border border-green-200';
        resultBox.innerHTML = `
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-6 h-6 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-green-800 font-semibold text-lg">CHECK-IN BERHASIL</span>
            </div>
            <div class="space-y-1 text-sm text-green-900">
                <div class="flex gap-2"><span class="font-medium w-36 shrink-0">Nama Member</span><span>: ${escapeHtml(data.member_name)}</span></div>
                <div class="flex gap-2"><span class="font-medium w-36 shrink-0">Membership</span><span>: ${escapeHtml(data.membership)}</span></div>
                <div class="flex gap-2"><span class="font-medium w-36 shrink-0">Masa Berlaku</span><span>: ${escapeHtml(data.valid_until)}</span></div>
                <div class="flex gap-2"><span class="font-medium w-36 shrink-0">Waktu Check-In</span><span>: ${escapeHtml(data.checked_in_at)}</span></div>
            </div>
        `;
    } else {
        resultBox.className = 'mt-6 rounded-xl p-5 bg-red-50 border border-red-200';
        resultBox.innerHTML = `
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="text-red-800 font-medium">${escapeHtml(errorMessage ?? 'Check-in gagal.')}</span>
            </div>
        `;
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
