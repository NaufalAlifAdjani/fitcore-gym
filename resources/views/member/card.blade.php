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

            <!-- VIP DIGITAL MEMBERSHIP CARD (Light Athletic Card) -->
            <div class="relative overflow-hidden rounded-[28px] border-2 border-orange-200/80 bg-gradient-to-br from-white via-orange-50/50 to-amber-50/40 p-6 sm:p-8 text-[#16151A] shadow-[0_16px_40px_rgba(255,87,34,0.12)]">
                <!-- Ambient Glow Effect -->
                <div class="absolute -top-16 -right-16 w-56 h-56 bg-[#FF5722]/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-[#FFB703]/20 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Subtle Grid Background -->
                <div class="absolute inset-0 bg-[radial-gradient(#ff572215_1px,transparent_1px)] [background-size:16px_16px] opacity-70 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col justify-between space-y-6">
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between border-b border-orange-100 pb-4">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FF5722] text-white shadow-md shadow-[#FF5722]/30">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h1.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 012.25 13.5v-3A1.5 1.5 0 013.75 9zm15 0h1.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 0117.25 13.5v-3a1.5 1.5 0 011.5-1.5zM6.75 12h10.5M5.25 7.5v9M18.75 7.5v9" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-heading text-sm font-extrabold tracking-wider text-zinc-900">
                                    FITCORE <span class="text-[#FF5722]">ATHLETIC</span>
                                </p>
                                <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-zinc-500">
                                    Member Identity Pass
                                </p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        @if($activeMembership)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 px-3 py-1 font-inter text-[11px] font-bold text-emerald-600 shadow-sm">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                AKTIF
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/15 border border-rose-500/30 px-3 py-1 font-inter text-[11px] font-bold text-rose-600 shadow-sm">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                NONAKTIF
                            </span>
                        @endif
                    </div>

                    <!-- Member Info & QR Code Display -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 py-2">
                        <!-- Left Details -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 font-inter">Nama Member</p>
                                <h2 class="font-heading text-xl sm:text-2xl font-bold text-zinc-900 tracking-tight mt-0.5">
                                    {{ $user->name }}
                                </h2>
                                @if($profile && $profile->member_code)
                                    <p class="font-mono text-xs font-semibold text-[#FF5722] mt-1 tracking-wider">
                                        ID: {{ $profile->member_code }}
                                    </p>
                                @endif
                            </div>

                            <div class="space-y-1">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500 font-inter">Paket Keanggotaan</p>
                                @if($activeMembership)
                                    <p class="font-inter text-sm font-bold text-zinc-900">
                                        {{ $activeMembership->package->name ?? 'Membership FitCore' }}
                                    </p>
                                    <p class="font-inter text-xs text-zinc-600">
                                        Berlaku s/d <span class="text-zinc-900 font-semibold">{{ $activeMembership->end_date->locale('id')->translatedFormat('d M Y') }}</span>
                                    </p>
                                @else
                                    <p class="font-inter text-sm font-semibold text-rose-600">
                                        Tidak ada membership aktif
                                    </p>
                                    <p class="font-inter text-xs text-zinc-500">
                                        Silakan berlangganan paket untuk check-in.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Right QR Container -->
                        <div class="flex flex-col items-center shrink-0 mx-auto sm:mx-0">
                            @if($qrSvg)
                                <div class="relative rounded-2xl bg-white p-3 shadow-md border-2 border-orange-100 w-36 h-36 flex items-center justify-center transition duration-300 hover:scale-105">
                                    <div class="w-full h-full flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                                        {!! $qrSvg !!}
                                    </div>
                                </div>
                                <span class="mt-2 text-[10px] font-bold tracking-wider text-zinc-500 uppercase flex items-center gap-1 font-inter">
                                    <svg class="w-3 h-3 text-[#FF5722]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                    Scan untuk Check-in
                                </span>
                            @else
                                <div class="rounded-2xl border border-dashed border-gray-300 bg-orange-50/30 p-4 w-36 h-36 flex flex-col items-center justify-center text-center">
                                    <svg class="w-8 h-8 text-zinc-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span class="text-[11px] font-medium text-zinc-500 font-inter">QR Code belum tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Bottom Footer -->
                    <div class="border-t border-orange-100 pt-4 flex items-center justify-between text-[11px] text-zinc-500 font-inter">
                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-[#FF5722]"></span>
                            FitCore Performance Center
                        </span>
                        <span class="font-mono text-[10px] tracking-widest text-zinc-400 uppercase">
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
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[10px] font-bold text-[#FF5722]">1</span>
                        <span>Buka halaman ini melalui smartphone Anda saat tiba di lokasi gym FitCore.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[10px] font-bold text-[#FF5722]">2</span>
                        <span>Tunjukkan layar QR Code ini kepada resepsionis atau arahkan ke scanner check-in otomatis.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[10px] font-bold text-[#FF5722]">3</span>
                        <span>Sistem akan memverifikasi status keaktifan paket membership Anda secara instan (1x per hari).</span>
                    </li>
                </ol>

                @if(!$activeMembership)
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3.5 flex items-center justify-between gap-3 font-inter">
                        <p class="text-xs text-rose-800 font-medium">
                            Membership Anda sedang tidak aktif. Perbarui paket untuk mulai check-in.
                        </p>
                        <a href="{{ route('member.membership.packages') }}" class="shrink-0 rounded-full bg-[#FF5722] px-4 py-1.5 text-xs font-bold text-white hover:bg-rose-700 transition shadow-xs">
                            Beli Paket
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
