<x-app-layout>
    <div class="min-h-screen bg-[#F6F6F7] py-8 sm:py-12 px-4 sm:px-6 lg:px-8 text-[#16151A]">
        <div class="max-w-xl mx-auto space-y-8">

            <!-- Page Title / Header Badge -->
            <div class="text-center space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 text-[11px] font-extrabold uppercase tracking-widest text-[#FF5722] bg-orange-50 border border-orange-200 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#FF5722] animate-pulse"></span>
                    FitCore Digital Pass
                </span>
                <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-[#16151A] tracking-tight">
                    Kartu Member Digital
                </h1>
                <p class="text-xs sm:text-sm text-[#565A66] font-medium max-w-md mx-auto">
                    Tunjukkan QR Code berikut kepada staf resepsionis FitCore untuk verifikasi kehadiran dan akses gym instan.
                </p>
            </div>

            <!-- VIP DIGITAL MEMBERSHIP CARD -->
            <div class="relative overflow-hidden rounded-[28px] border border-white/20 bg-cover bg-center p-6 sm:p-8 text-white shadow-[0_20px_50px_rgba(235,80,0,0.28)]"
                 style="background-image: url('{{ asset('images/membership-card/background.jpg') }}');">
                <!-- Subtle Contrast Overlay to ensure background art shines while text is comfortable to read -->
                <div class="absolute inset-0 bg-gradient-to-br from-black/40 via-black/20 to-black/50 pointer-events-none"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.15),transparent_65%)] pointer-events-none"></div>

                <div class="relative z-10 flex flex-col justify-between space-y-6">
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between border-b border-white/20 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-white/95 p-1.5 shadow-md flex items-center justify-center backdrop-blur-md shrink-0">
                                <img src="{{ asset('images/logo/logo-icon.png') }}" alt="FitCore" class="h-full w-full object-contain">
                            </div>
                            <div>
                                <p class="font-heading text-sm font-extrabold tracking-wider text-white drop-shadow-xs">
                                    FITCORE <span class="text-orange-200">ATHLETIC</span>
                                </p>
                                <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-white/80">
                                    Member Identity Pass
                                </p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        @if($activeMembership)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/90 text-white border border-emerald-400/50 px-3 py-1 font-inter text-[11px] font-bold shadow-md backdrop-blur-md">
                                <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                                AKTIF
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/90 text-white border border-rose-400/50 px-3 py-1 font-inter text-[11px] font-bold shadow-md backdrop-blur-md">
                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                NONAKTIF
                            </span>
                        @endif
                    </div>

                    <!-- Member Info & QR Code Display -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 py-2">
                        <!-- Left Details -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-white/75 font-inter">Nama Member</p>
                                <h2 class="font-heading text-xl sm:text-2xl font-bold text-white tracking-tight mt-0.5 drop-shadow-sm">
                                    {{ $user->name }}
                                </h2>
                                @if($profile && $profile->member_code)
                                    <p class="font-mono text-xs font-bold text-orange-200 mt-1 tracking-wider drop-shadow-xs">
                                        ID: {{ $profile->member_code }}
                                    </p>
                                @endif
                            </div>

                            <div class="space-y-1">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-white/75 font-inter">Paket Keanggotaan</p>
                                @if($activeMembership)
                                    <p class="font-inter text-sm font-bold text-white drop-shadow-xs">
                                        {{ $activeMembership->package->name ?? 'Membership FitCore' }}
                                    </p>
                                    <p class="font-inter text-xs text-white/85">
                                        Berlaku s/d <span class="text-orange-200 font-bold">{{ $activeMembership->end_date->locale('id')->translatedFormat('d M Y') }}</span>
                                    </p>
                                @else
                                    <p class="font-inter text-sm font-semibold text-rose-200">
                                        Tidak ada membership aktif
                                    </p>
                                    <p class="font-inter text-xs text-white/75">
                                        Silakan berlangganan paket untuk check-in.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Right QR Container -->
                        <div class="flex flex-col items-center shrink-0 mx-auto sm:mx-0">
                            @if($qrSvg)
                                <div class="relative rounded-2xl bg-white p-3 shadow-xl border border-white/60 w-36 h-36 flex items-center justify-center transition duration-300 hover:scale-105">
                                    <div class="w-full h-full flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                                        {!! $qrSvg !!}
                                    </div>
                                </div>
                                <span class="mt-2 text-[10px] font-bold tracking-wider text-white uppercase flex items-center gap-1.5 font-inter drop-shadow-sm">
                                    <svg class="w-3.5 h-3.5 text-orange-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                    Scan untuk Check-in
                                </span>
                            @else
                                <div class="rounded-2xl border border-dashed border-white/40 bg-black/25 backdrop-blur-md p-4 w-36 h-36 flex flex-col items-center justify-center text-center text-white">
                                    <svg class="w-8 h-8 text-white/70 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span class="text-[11px] font-medium text-white/80 font-inter">QR Code belum tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Bottom Footer -->
                    <div class="border-t border-white/20 pt-4 flex items-center justify-between text-[11px] text-white/85 font-inter">
                        <span class="flex items-center gap-1.5 font-medium">
                            <span class="h-2 w-2 rounded-full bg-white shadow-xs"></span>
                            FitCore Performance Center
                        </span>
                        <span class="font-mono text-[10px] tracking-widest text-white/70 uppercase">
                            CONFIDENTIAL PASS
                        </span>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Penggunaan -->
            <div class="rounded-2xl border border-[#E5E7EB] bg-white p-6 shadow-sm space-y-4">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-50 text-[#FF5722]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4M12 8h.01" />
                        </svg>
                    </span>
                    <h3 class="font-heading text-sm font-bold text-[#16151A]">
                        Panduan Check-In Gym
                    </h3>
                </div>

                <ol class="space-y-3 text-xs text-[#565A66] font-inter">
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-50 text-[10px] font-bold text-[#FF5722]">1</span>
                        <span>Buka halaman ini melalui smartphone Anda saat tiba di lokasi gym FitCore.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-50 text-[10px] font-bold text-[#FF5722]">2</span>
                        <span>Tunjukkan layar QR Code ini kepada resepsionis atau arahkan ke scanner check-in otomatis.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-50 text-[10px] font-bold text-[#FF5722]">3</span>
                        <span>Sistem akan memverifikasi status keaktifan paket membership Anda secara instan (1x per hari).</span>
                    </li>
                </ol>

                @if(!$activeMembership)
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3.5 flex items-center justify-between gap-3 font-inter">
                        <p class="text-xs text-rose-800 font-medium">
                            Membership Anda sedang tidak aktif. Perbarui paket untuk mulai check-in.
                        </p>
                        <a href="{{ route('member.membership.packages') }}" class="shrink-0 rounded-full bg-[#FF5722] hover:bg-[#E64A19] px-4 py-1.5 text-xs font-bold text-white transition shadow-xs">
                            Beli Paket
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
