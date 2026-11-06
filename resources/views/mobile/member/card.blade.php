{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Kartu Member Digital & QR Check-in (Fitur #4)
| FILE         : resources/views/mobile/member/card.blade.php
| LAYOUT       : layouts/mobile-app.blade.php
| AKSES ROUTE  : /m/card atau /member/card (name: mobile.member.card)
| DESKRIPSI    : Tampilan kartu identitas digital member khusus layar HP.
|                Menampilkan kode QR dinamis dengan animasi pulsing border,
|                status aktif, masa berlaku, dan instruksi check-in instan.
|                Menggunakan token warna DESIGN.md: primary #ED1B45, surface-dark #1E1E1E,
|                tipografi Poppins & Inter.
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-app title="Kartu Member Digital - FitCore" active-tab="card">
    <div class="space-y-5">

        <!-- 1. Header Informasi Singkat -->
        <div class="text-center space-y-1">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-[#ED1B45] bg-rose-500/10 border border-rose-500/20 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-[#ED1B45] animate-pulse"></span>
                FitCore Digital Pass
            </span>
            <h1 class="text-xl sm:text-2xl font-black font-poppins text-white tracking-tight">
                Kartu Akses Gym
            </h1>
            <p class="text-xs text-[#A1A1AA] font-medium max-w-xs mx-auto font-sans">
                Tunjukkan QR Code ini ke petugas resepsionis di pintu masuk gym.
            </p>
        </div>

        <!-- 2. VIP Member Digital Card Shell (Design Token DESIGN.md: dark #141414, surface-dark #1E1E1E, rounded 16px) -->
        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-[#1E1E1E] via-[#161616] to-[#141414] p-6 text-white shadow-[0_12px_36px_rgba(0,0,0,0.5)]">
            <!-- Background Ambient Glow -->
            <div class="absolute -top-12 -right-12 w-44 h-44 bg-[#ED1B45]/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-44 h-44 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center text-center space-y-5">
                
                <!-- Card Header (Brand Logo & Status Badge) -->
                <div class="w-full flex items-center justify-between border-b border-white/10 pb-3.5">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-[#ED1B45] flex items-center justify-center text-white font-black text-xs shadow-md">
                            F
                        </div>
                        <div class="text-left">
                            <span class="font-poppins text-xs font-black tracking-wider text-white">FITCORE</span>
                            <span class="block text-[8px] font-bold text-[#A1A1AA] tracking-widest uppercase">VIP MEMBER</span>
                        </div>
                    </div>

                    <!-- Badge Status Aktif / Nonaktif -->
                    @if ($activeMembership)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            AKTIF
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/15 text-rose-400 border border-rose-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                            NONAKTIF
                        </span>
                    @endif
                </div>

                <!-- Center Large QR Code with Pulsing Border Animation (Prompt #4) -->
                <div class="relative my-2">
                    <!-- Subtle Pulsing Live Ring in Primary Red -->
                    <div class="absolute -inset-2.5 rounded-2xl bg-gradient-to-r from-[#ED1B45] to-[#B50E32] opacity-70 blur-xs animate-pulse"></div>
                    
                    <!-- White Card Container for High-Contrast QR Scanning -->
                    <div class="relative bg-white p-4 rounded-xl shadow-xl flex items-center justify-center">
                        @if (isset($qrSvg) && $qrSvg)
                            <div class="w-48 h-48 flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                                {!! $qrSvg !!}
                            </div>
                        @else
                            <div class="w-48 h-48 flex flex-col items-center justify-center text-gray-400 gap-2">
                                <svg class="w-12 h-12 text-gray-300 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span class="text-xs font-semibold">Memuat QR Pass...</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Member Info Section -->
                <div class="w-full space-y-1 pt-1">
                    <h2 class="text-lg font-black font-poppins text-white tracking-tight">
                        {{ $user->name }}
                    </h2>
                    <p class="text-xs font-mono font-bold text-[#A1A1AA] tracking-wider">
                        {{ $profile->member_code ?? 'MEM-'.str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <!-- Package Details & Expiry Info -->
                <div class="w-full grid grid-cols-2 gap-2 pt-3 border-t border-white/10 text-left">
                    <div class="p-2.5 bg-white/5 rounded-xl border border-white/5">
                        <span class="block text-[9px] font-bold uppercase tracking-wider text-[#A1A1AA]">Paket Terdaftar</span>
                        <span class="text-xs font-bold text-white truncate block mt-0.5 font-poppins">
                            {{ $activeMembership->package->name ?? 'Belum Berlangganan' }}
                        </span>
                    </div>
                    <div class="p-2.5 bg-white/5 rounded-xl border border-white/5">
                        <span class="block text-[9px] font-bold uppercase tracking-wider text-[#A1A1AA]">Masa Berlaku</span>
                        <span class="text-xs font-bold text-[#ED1B45] block mt-0.5">
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
        <div class="p-4 rounded-2xl bg-[#1E1E1E] border border-white/10 text-slate-300 text-xs flex items-start gap-3">
            <div class="w-7 h-7 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="space-y-0.5">
                <span class="font-bold text-white block font-poppins">Petunjuk Akses Pintu Masuk</span>
                <p class="text-[11px] text-[#A1A1AA] leading-relaxed font-sans">
                    Arahkan layar ponsel ke scanner resepsionis dengan kecerahan layar maksimal. Sistem turnstile akan membuka otomatis jika status membership Anda aktif.
                </p>
            </div>
        </div>

    </div>
</x-layouts.mobile-app>
