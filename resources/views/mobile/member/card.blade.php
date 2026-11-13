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

<x-layouts.mobile-app title="Kartu Member Digital - FitCore Athletic" active-tab="card">
    <div class="space-y-6">

        <!-- 1. Header Informasi Singkat (Sesuai Web Desktop) -->
        <div class="text-center space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 text-[11px] font-extrabold uppercase tracking-widest text-[#FF5722] bg-orange-50 border border-orange-200 rounded-full shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-[#FF5722] animate-pulse"></span>
                FitCore Digital Pass
            </span>
            <h1 class="font-poppins text-2xl font-extrabold text-[#16151A] tracking-tight">
                Kartu Akses Gym
            </h1>
            <p class="text-xs text-[#565A66] font-medium max-w-xs mx-auto leading-relaxed">
                Tunjukkan QR Code Kartu Member Digital berikut kepada staf resepsionis FitCore untuk verifikasi kehadiran dan akses gym instan.
            </p>
        </div>

        <!-- 2. VIP DIGITAL MEMBERSHIP CARD (100% Identik Visual Web Desktop) -->
        <div class="relative overflow-hidden rounded-[24px] border border-white/20 bg-cover bg-center p-5 text-white shadow-[0_20px_50px_rgba(235,80,0,0.28)]"
             style="background-image: url('{{ asset('images/membership-card/background.jpg') }}');">
            <!-- Subtle Contrast Overlay to ensure background art shines while text is comfortable to read -->
            <div class="absolute inset-0 bg-gradient-to-br from-black/40 via-black/20 to-black/50 pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.15),transparent_65%)] pointer-events-none"></div>

            <div class="relative z-10 flex flex-col justify-between space-y-5">
                <!-- Card Top Header -->
                <div class="flex items-center justify-between border-b border-white/20 pb-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="h-9 w-9 rounded-xl bg-white/95 p-1.5 shadow-md flex items-center justify-center backdrop-blur-md shrink-0">
                            <img src="{{ asset('images/logo/logo-icon.png') }}" alt="FitCore" class="h-full w-full object-contain">
                        </div>
                        <div>
                            <p class="font-poppins text-xs font-extrabold tracking-wider text-white drop-shadow-xs">
                                FITCORE <span class="text-orange-200">ATHLETIC</span>
                            </p>
                            <p class="text-[8px] font-bold uppercase tracking-[0.2em] text-white/80">
                                Member Identity Pass
                            </p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    @if($activeMembership)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/90 text-white border border-emerald-400/50 px-2.5 py-0.5 font-inter text-[10px] font-bold shadow-md backdrop-blur-md">
                            <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                            AKTIF
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/90 text-white border border-rose-400/50 px-2.5 py-0.5 font-inter text-[10px] font-bold shadow-md backdrop-blur-md">
                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                            NONAKTIF
                        </span>
                    @endif
                </div>

                <!-- Member Info Section -->
                <div class="space-y-3">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-white/75 font-inter">Nama Member</p>
                        <h2 class="font-poppins text-lg font-bold text-white tracking-tight mt-0.5 drop-shadow-sm">
                            {{ $user->name }}
                        </h2>
                        @if($profile && $profile->member_code)
                            <p class="font-mono text-xs font-bold text-orange-200 mt-0.5 tracking-wider drop-shadow-xs">
                                ID: {{ $profile->member_code }}
                            </p>
                        @endif
                    </div>

                    <div class="space-y-0.5">
                        <p class="text-[9px] font-bold uppercase tracking-widest text-white/75 font-inter">Paket Keanggotaan</p>
                        @if($activeMembership)
                            <p class="font-inter text-xs font-bold text-white drop-shadow-xs">
                                {{ $activeMembership->package->name ?? 'Membership FitCore' }}
                            </p>
                            <p class="font-inter text-[11px] text-white/85">
                                Berlaku s/d <span class="text-orange-200 font-bold">{{ \Carbon\Carbon::parse($activeMembership->end_date)->translatedFormat('d M Y') }}</span>
                            </p>
                        @else
                            <p class="font-inter text-xs font-semibold text-rose-200">
                                Tidak ada membership aktif
                            </p>
                            <p class="font-inter text-[11px] text-white/75">
                                Silakan berlangganan paket untuk check-in.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Center Large QR Container -->
                <div class="flex flex-col items-center justify-center pt-1">
                    @if(isset($qrSvg) && $qrSvg)
                        <div class="relative rounded-2xl bg-white p-3 shadow-xl border border-white/60 w-44 h-44 flex items-center justify-center transition duration-300">
                            <div class="w-full h-full flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                                {!! $qrSvg !!}
                            </div>
                        </div>
                        <span class="mt-2.5 text-[10px] font-bold tracking-wider text-white uppercase flex items-center gap-1.5 font-inter drop-shadow-sm">
                            <svg class="w-3.5 h-3.5 text-orange-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            </svg>
                            Scan untuk Check-in
                        </span>
                    @else
                        <div class="rounded-2xl border border-dashed border-white/40 bg-black/25 backdrop-blur-md p-4 w-44 h-44 flex flex-col items-center justify-center text-center text-white">
                            <svg class="w-8 h-8 text-white/70 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="text-xs font-medium text-white/80 font-inter">QR Code belum tersedia</span>
                        </div>
                    @endif
                </div>

                <!-- Card Bottom Footer -->
                <div class="border-t border-white/20 pt-3 flex items-center justify-between text-[10px] text-white/85 font-inter">
                    <span class="flex items-center gap-1.5 font-medium">
                        <span class="h-1.5 w-1.5 rounded-full bg-white shadow-xs"></span>
                        FitCore Performance Center
                    </span>
                    <span class="font-mono text-[9px] tracking-widest text-white/70 uppercase">
                        CONFIDENTIAL PASS
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. Panduan Check-In Gym (Identik dengan Web Desktop) -->
        <div class="rounded-2xl border border-[#E5E7EB] bg-white p-5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3.5">
            <div class="flex items-center gap-2.5">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-orange-50 text-[#FF5722] border border-orange-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4M12 8h.01" />
                    </svg>
                </span>
                <h3 class="font-poppins text-xs sm:text-sm font-bold text-[#16151A]">
                    Panduan Check-In Gym
                </h3>
            </div>

            <ol class="space-y-2.5 text-xs text-[#565A66] font-inter">
                <li class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-50 border border-orange-100 text-[10px] font-bold text-[#FF5722]">1</span>
                    <span>Buka halaman ini melalui smartphone Anda saat tiba di pintu masuk gym FitCore.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-50 border border-orange-100 text-[10px] font-bold text-[#FF5722]">2</span>
                    <span>Arahkan layar QR Code ke scanner resepsionis dengan tingkat kecerahan layar maksimal.</span>
                </li>
                <li class="flex items-start gap-2.5">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-50 border border-orange-100 text-[10px] font-bold text-[#FF5722]">3</span>
                    <span>Sistem turnstile akan terbuka otomatis jika status paket keanggotaan Anda aktif.</span>
                </li>
            </ol>

            @if(!$activeMembership)
                <div class="mt-3 rounded-xl border border-rose-200 bg-rose-50 p-3 flex items-center justify-between gap-2.5 font-inter">
                    <p class="text-[11px] text-rose-800 font-medium">
                        Membership nonaktif. Perbarui paket untuk mulai check-in.
                    </p>
                    <a href="{{ route('mobile.member.packages') }}" class="shrink-0 rounded-full bg-[#FF5722] hover:bg-[#E64A19] px-3.5 py-1 text-xs font-bold text-white transition shadow-xs">
                        Beli Paket
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-layouts.mobile-app>
