<x-member-layout title="Kartu Member Digital - FitCore Athletic" active-nav="card">
    <div class="py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto space-y-8">

            <!-- Page Title / Header Badge -->
            <div class="text-center space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 text-[11px] font-extrabold uppercase tracking-widest text-[#BA0030] bg-rose-500/10 border border-[#BA0030]/30 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#BA0030] animate-pulse"></span>
                    FitCore Digital Pass
                </span>
                <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Kartu Member Digital
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 font-medium max-w-md mx-auto">
                    Tunjukkan QR Code berikut kepada staf resepsionis FitCore untuk verifikasi kehadiran dan akses gym instan.
                </p>
            </div>

            <!-- VIP DIGITAL MEMBERSHIP CARD -->
            <div class="relative overflow-hidden rounded-[28px] border border-white/10 bg-gradient-to-br from-[#1C1C22] via-[#141418] to-[#0A0A0E] p-6 sm:p-8 text-white shadow-[0_20px_50px_rgba(0,0,0,0.6)]">
                <!-- Ambient Glow Effect -->
                <div class="absolute -top-16 -right-16 w-56 h-56 bg-[#BA0030]/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-slate-800/30 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Subtle Grid Background -->
                <div class="absolute inset-0 bg-[radial-gradient(#ffffff0a_1px,transparent_1px)] [background-size:16px_16px] opacity-40 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col justify-between space-y-6">
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#BA0030] text-white shadow-md shadow-[#BA0030]/30">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h1.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 012.25 13.5v-3A1.5 1.5 0 013.75 9zm15 0h1.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 0117.25 13.5v-3a1.5 1.5 0 011.5-1.5zM6.75 12h10.5M5.25 7.5v9M18.75 7.5v9" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-heading text-sm font-extrabold tracking-wider text-white">
                                    FITCORE <span class="text-[#BA0030]">ATHLETIC</span>
                                </p>
                                <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                    Member Identity Pass
                                </p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        @if($activeMembership)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 px-3 py-1 font-inter text-[11px] font-bold text-emerald-400 shadow-sm">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                AKTIF
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-500/15 border border-rose-500/30 px-3 py-1 font-inter text-[11px] font-bold text-rose-400 shadow-sm">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                                NONAKTIF
                            </span>
                        @endif
                    </div>

                    <!-- Member Info & QR Code Display -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 py-2">
                        <!-- Left Details -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 font-inter">Nama Member</p>
                                <h2 class="font-heading text-xl sm:text-2xl font-bold text-white tracking-tight mt-0.5">
                                    {{ $user->name }}
                                </h2>
                                @if($profile && $profile->member_code)
                                    <p class="font-mono text-xs font-semibold text-[#BA0030] mt-1 tracking-wider">
                                        ID: {{ $profile->member_code }}
                                    </p>
                                @endif
                            </div>

                            <div class="space-y-1">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 font-inter">Paket Keanggotaan</p>
                                @if($activeMembership)
                                    <p class="font-inter text-sm font-bold text-slate-200">
                                        {{ $activeMembership->package->name ?? 'Membership FitCore' }}
                                    </p>
                                    <p class="font-inter text-xs text-slate-400">
                                        Berlaku s/d <span class="text-white font-semibold">{{ $activeMembership->end_date->locale('id')->translatedFormat('d M Y') }}</span>
                                    </p>
                                @else
                                    <p class="font-inter text-sm font-semibold text-rose-400">
                                        Tidak ada membership aktif
                                    </p>
                                    <p class="font-inter text-xs text-slate-500">
                                        Silakan berlangganan paket untuk check-in.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Right QR Container -->
                        <div class="flex flex-col items-center shrink-0 mx-auto sm:mx-0">
                            @if($qrSvg)
                                <div class="relative rounded-2xl bg-white p-3 shadow-xl border border-white/20 w-36 h-36 flex items-center justify-center transition duration-300 hover:scale-105">
                                    <div class="w-full h-full flex items-center justify-center [&>svg]:w-full [&>svg]:h-full">
                                        {!! $qrSvg !!}
                                    </div>
                                </div>
                                <span class="mt-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase flex items-center gap-1 font-inter">
                                    <svg class="w-3 h-3 text-[#BA0030]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                    Scan untuk Check-in
                                </span>
                            @else
                                <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-900/60 p-4 w-36 h-36 flex flex-col items-center justify-center text-center">
                                    <svg class="w-8 h-8 text-slate-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span class="text-[11px] font-medium text-slate-500 font-inter">QR Code belum tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Bottom Footer -->
                    <div class="border-t border-white/10 pt-4 flex items-center justify-between text-[11px] text-slate-400 font-inter">
                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-[#BA0030]"></span>
                            FitCore Performance Center
                        </span>
                        <span class="font-mono text-[10px] tracking-widest text-slate-500 uppercase">
                            CONFIDENTIAL PASS
                        </span>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Penggunaan -->
            <div class="rounded-2xl border border-slate-800 bg-[#111622]/90 p-6 backdrop-blur-md shadow-lg space-y-4">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#BA0030]/20 text-[#BA0030]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4M12 8h.01" />
                        </svg>
                    </span>
                    <h3 class="font-heading text-sm font-bold text-white">
                        Panduan Check-In Gym
                    </h3>
                </div>

                <ol class="space-y-3 text-xs text-slate-300 font-inter">
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-800 text-[10px] font-bold text-[#BA0030]">1</span>
                        <span>Buka halaman ini melalui smartphone Anda saat tiba di lokasi gym FitCore.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-800 text-[10px] font-bold text-[#BA0030]">2</span>
                        <span>Tunjukkan layar QR Code ini kepada resepsionis atau arahkan ke scanner check-in otomatis.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-800 text-[10px] font-bold text-[#BA0030]">3</span>
                        <span>Sistem akan memverifikasi status keaktifan paket membership Anda secara instan (1x per hari).</span>
                    </li>
                </ol>

                @if(!$activeMembership)
                    <div class="mt-4 rounded-xl border border-rose-500/20 bg-rose-500/10 p-3.5 flex items-center justify-between gap-3 font-inter">
                        <p class="text-xs text-rose-300 font-medium">
                            Membership Anda sedang tidak aktif. Perbarui paket untuk mulai check-in.
                        </p>
                        <a href="{{ route('member.membership.packages') }}" class="shrink-0 rounded-full bg-[#BA0030] px-4 py-1.5 text-xs font-bold text-white hover:bg-rose-700 transition">
                            Beli Paket
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-member-layout>
