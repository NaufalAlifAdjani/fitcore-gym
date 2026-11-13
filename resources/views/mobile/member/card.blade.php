{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Kartu Member Digital & QR Check-in (Fitur #4)
| FILE         : resources/views/mobile/member/card.blade.php
| LAYOUT       : layouts/mobile-app.blade.php
| AKSES ROUTE  : /m/card atau /member/card (name: mobile.member.card)
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-app title="Kartu Member Digital - FitCore" active-tab="card">
    <div class="space-y-5">

        <!-- 1. Header Informasi Singkat -->
        <div class="text-center space-y-1">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-[#FF5722] bg-orange-50 border border-orange-200 rounded-full shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5722] animate-pulse"></span>
                FitCore Digital Pass
            </span>
            <h1 class="text-xl sm:text-2xl font-black font-poppins text-[#16151A] tracking-tight">
                Kartu Akses Gym
            </h1>
            <p class="text-xs text-[#565A66] font-medium max-w-xs mx-auto font-sans">
                Tunjukkan QR Code ini ke petugas resepsionis di pintu masuk gym.
            </p>
        </div>

        <!-- 2. VIP Member Digital Card Shell (Light Theme: White Card, Border #E5E7EB, Radius 16px) -->
        <div class="relative overflow-hidden rounded-2xl border border-[#E5E7EB] bg-white p-6 text-[#16151A] shadow-[0_12px_36px_rgba(0,0,0,0.06)]">
            <!-- Subtle Ambient Glow -->
            <div class="absolute -top-12 -right-12 w-44 h-44 bg-[#FF5722]/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-44 h-44 bg-gray-100 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center text-center space-y-5">
                
                <!-- Card Header (Brand Logo & Status Badge) -->
                <div class="w-full flex items-center justify-between border-b border-[#E5E7EB] pb-3.5">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-[#FF5722] to-[#FFB703] flex items-center justify-center text-white font-black text-xs shadow-xs">
                            <svg width="16" height="16" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.1667 26.25L18 25.0833L20.9583 22.125L13.875 15.0417L10.9167 18L9.75 16.8333L10.9167 15.625L9.75 14.4583L11.5 12.7083L10.3333 11.5L11.5 10.3333L12.7083 11.5L14.4583 9.75L15.625 10.9167L16.8333 9.75L18 10.9167L15.0417 13.875L22.125 20.9583L25.0833 18L26.25 19.1667L25.0833 20.375L26.25 21.5417L24.5 23.2917L25.6667 24.5L24.5 25.6667L23.2917 24.5L21.5417 26.25L20.375 25.0833L19.1667 26.25V26.25" fill="white"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="font-poppins text-xs font-black tracking-wider text-[#16151A]">FITCORE</span>
                            <span class="block text-[8px] font-bold text-[#565A66] tracking-widest uppercase">VIP MEMBER</span>
                        </div>
                    </div>

                    <!-- Badge Status Aktif / Nonaktif -->
                    @if ($activeMembership)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            AKTIF
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            NONAKTIF
                        </span>
                    @endif
                </div>

                <!-- Center Large Dynamic QR Code -->
                <div class="relative my-2"
                     id="dynamic-qr-wrapper-mobile"
                     data-generate-url="{{ route('member.card.qr-token') }}"
                     data-svg-url="{{ route('member.card.qr-svg') }}">
                    <!-- Subtle Pulsing Live Ring in Blaze Orange -->
                    <div class="absolute -inset-2.5 rounded-2xl bg-gradient-to-r from-[#FF5722] to-[#FFB703] opacity-40 blur-xs animate-pulse"></div>

                    <!-- White Card Container for High-Contrast QR Scanning -->
                    <div class="relative bg-white p-4 rounded-xl shadow-md border border-[#E5E7EB] flex items-center justify-center">
                        <!-- Loading state -->
                        <div id="qr-loading-mobile" class="w-48 h-48 flex flex-col items-center justify-center text-gray-400 gap-2">
                            <svg class="w-12 h-12 text-orange-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            <span class="text-xs font-semibold">Memuat QR Pass...</span>
                        </div>
                        <!-- QR image -->
                        <img id="qr-image-mobile" src="" alt="QR Code Check-in"
                             class="hidden w-48 h-48 object-contain" />
                        <!-- Error state -->
                        <div id="qr-error-mobile" class="hidden w-48 h-48 flex flex-col items-center justify-center text-gray-400 gap-2">
                            <svg class="w-10 h-10 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="text-xs font-semibold">Gagal memuat QR</span>
                        </div>
                    </div>
                    <!-- Countdown -->
                    <div id="qr-countdown-mobile" class="hidden text-center mt-1.5 text-[10px] font-semibold text-[#FF5722] font-inter">
                        Perbarui dalam <span id="qr-countdown-sec-mobile">30</span>s
                    </div>
                </div>

                <!-- Member Info Section -->
                <div class="w-full space-y-1 pt-1">
                    <h2 class="text-lg font-black font-poppins text-[#16151A] tracking-tight">
                        {{ $user->name }}
                    </h2>
                    <p class="text-xs font-mono font-bold text-[#565A66] tracking-wider">
                        {{ $profile->member_code ?? 'MEM-'.str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <!-- Package Details & Expiry Info -->
                <div class="w-full grid grid-cols-2 gap-2 pt-3 border-t border-[#E5E7EB] text-left">
                    <div class="p-2.5 bg-[#F6F6F7] rounded-xl border border-[#E5E7EB]">
                        <span class="block text-[9px] font-bold uppercase tracking-wider text-[#565A66]">Paket Terdaftar</span>
                        <span class="text-xs font-bold text-[#16151A] truncate block mt-0.5 font-poppins">
                            {{ $activeMembership->package->name ?? 'Belum Berlangganan' }}
                        </span>
                    </div>
                    <div class="p-2.5 bg-[#F6F6F7] rounded-xl border border-[#E5E7EB]">
                        <span class="block text-[9px] font-bold uppercase tracking-wider text-[#565A66]">Masa Berlaku</span>
                        <span class="text-xs font-bold text-[#FF5722] block mt-0.5">
                            @if ($activeMembership && $activeMembership->end_date)
                                {{ \Carbon\Carbon::parse($activeMembership->end_date)->translatedFormat('d M Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <!-- 3. Instruksi Check-in Petugas -->
        <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] text-[#565A66] text-xs flex items-start gap-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
            <div class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5 border border-emerald-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="space-y-0.5">
                <span class="font-bold text-[#16151A] block font-poppins">Petunjuk Akses Pintu Masuk</span>
                <p class="text-[11px] text-[#565A66] leading-relaxed font-sans">
                    Arahkan layar ponsel ke scanner resepsionis dengan kecerahan layar maksimal. Sistem turnstile akan membuka otomatis jika status membership Anda aktif.
                </p>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    (function () {
        const wrapper      = document.getElementById('dynamic-qr-wrapper-mobile');
        const qrImage      = document.getElementById('qr-image-mobile');
        const qrLoading    = document.getElementById('qr-loading-mobile');
        const qrError      = document.getElementById('qr-error-mobile');
        const countdownEl  = document.getElementById('qr-countdown-mobile');
        const countdownSec = document.getElementById('qr-countdown-sec-mobile');

        if (!wrapper) return;

        const GENERATE_URL = wrapper.dataset.generateUrl;
        const SVG_URL      = wrapper.dataset.svgUrl;
        const TTL          = 30;

        let countdownTimer = null;
        let refreshTimer   = null;

        function showLoading() {
            qrLoading.classList.remove('hidden');
            qrImage.classList.add('hidden');
            qrError.classList.add('hidden');
            countdownEl.classList.add('hidden');
        }

        function showQr(svgUrl) {
            qrImage.src = svgUrl;
            qrImage.classList.remove('hidden');
            qrLoading.classList.add('hidden');
            qrError.classList.add('hidden');
            countdownEl.classList.remove('hidden');
        }

        function showError() {
            qrError.classList.remove('hidden');
            qrLoading.classList.add('hidden');
            qrImage.classList.add('hidden');
            countdownEl.classList.add('hidden');
        }

        function startCountdown(seconds) {
            clearInterval(countdownTimer);
            let remaining = seconds;
            countdownSec.textContent = remaining;
            countdownTimer = setInterval(() => {
                remaining -= 1;
                countdownSec.textContent = remaining;
                if (remaining <= 0) clearInterval(countdownTimer);
            }, 1000);
        }

        async function refreshQr() {
            clearTimeout(refreshTimer);
            clearInterval(countdownTimer);
            showLoading();

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            let tokenData;
            try {
                const res = await fetch(GENERATE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                if (!res.ok) throw new Error('generate failed');
                tokenData = await res.json();
            } catch (err) {
                console.error('QR generate error:', err);
                showError();
                refreshTimer = setTimeout(refreshQr, 5000);
                return;
            }

            const svgUrl = `${SVG_URL}?token=${encodeURIComponent(tokenData.token)}&_=${Date.now()}`;
            showQr(svgUrl);

            const ttl = tokenData.ttl ?? TTL;
            startCountdown(ttl);
            refreshTimer = setTimeout(refreshQr, ttl * 1000);
        }

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') refreshQr();
        });

        refreshQr();
    })();
    </script>
    @endpush
</x-layouts.mobile-app>