{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Beranda Utama (Dashboard Member - Shared Patterns)
| FILE         : resources/views/mobile/member/dashboard.blade.php
| LAYOUT       : layouts/mobile-app.blade.php
| AKSES ROUTE  : /m/dashboard atau /member/dashboard (name: mobile.member.dashboard)
| DESKRIPSI    : Beranda mobile member mengacu pada token DESIGN.md (primary #ED1B45,
|                surface-dark #1E1E1E, card 16px, pill rounded-full, font Poppins/Inter).
|                Menampilkan sambutan personal, ringkasan kuota PT, kartu sesi latihan
|                terdekat, shortcut VIP QR pass, dan aksi cepat layanan gym.
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-app title="Beranda Member - FitCore Athletic" active-tab="beranda">
    <div class="space-y-5">

        <!-- 1. Header Sambutan Pengguna & Status Membership -->
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#A1A1AA]">Selamat Datang Kembali,</p>
                <h1 class="text-xl sm:text-2xl font-black font-poppins text-white tracking-tight">
                    {{ $user->name }}
                </h1>
            </div>

            @if ($activeMembership)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Member Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                    Belum Langganan
                </span>
            @endif
        </div>

        <!-- 2. Shortcut VIP QR Pass Card (Akses Cepat Turnstile / Pintu Masuk) -->
        <a href="{{ route('mobile.member.card') }}" class="block relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#ED1B45] via-[#D1123D] to-[#B50E32] p-4 text-white shadow-[0_8px_24px_rgba(237,27,69,0.35)] transition active:scale-[0.99] group">
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white shrink-0 shadow-inner">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-white/80">Check-in Pintu Masuk</span>
                        <h2 class="text-sm font-extrabold tracking-wide text-white font-poppins">
                            Buka Kartu Member Digital
                        </h2>
                    </div>
                </div>

                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center group-hover:translate-x-1 transition-transform">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </a>

        <!-- 3. Ringkasan Kuota & Metrik Statistik (DESIGN.md Grid 2-kolom, Surface-Dark #1E1E1E, Radius 16px) -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Stat 1: Sisa Sesi PT -->
            <div class="bg-[#1E1E1E] border border-white/10 rounded-2xl p-4 flex flex-col justify-between shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#A1A1AA]">Sisa Kuota PT</span>
                    <span class="w-2 h-2 rounded-full bg-[#ED1B45]"></span>
                </div>
                <div class="mt-3">
                    <div class="font-poppins font-black text-2xl text-white">
                        {{ $quota->remaining_sessions ?? 0 }} <span class="text-xs font-semibold text-[#A1A1AA]">Sesi</span>
                    </div>
                    <p class="mt-1 text-[10px] text-[#A1A1AA] truncate">
                        @if ($quota && $quota->end_date)
                            Hingga {{ \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d M Y') }}
                        @else
                            Tidak ada kuota aktif
                        @endif
                    </p>
                </div>
            </div>

            <!-- Stat 2: Masa Aktif Membership -->
            <div class="bg-[#1E1E1E] border border-white/10 rounded-2xl p-4 flex flex-col justify-between shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#A1A1AA]">Status Member</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-3">
                    <div class="font-poppins font-bold text-base text-white truncate">
                        {{ $activeMembership->package->name ?? 'Non-Aktif' }}
                    </div>
                    <p class="mt-1 text-[10px] text-emerald-400 truncate">
                        @if ($activeMembership && $activeMembership->end_date)
                            Sisa {{ now()->diffInDays(\Carbon\Carbon::parse($activeMembership->end_date), false) }} hari
                        @else
                            Silakan pilih paket
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- 4. Jadwal Latihan Terdekat (Upcoming PT Session) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300">
                    Jadwal Sesi Terdekat
                </h3>
                <a href="{{ route('mobile.member.pt-sessions.index') }}" class="text-xs text-[#ED1B45] font-semibold hover:underline">
                    Lihat Semua
                </a>
            </div>

            @if ($upcomingSession)
                <div class="bg-[#1E1E1E] border border-white/10 rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $upcomingSession->status === \App\Enums\PtSessionStatus::Confirmed ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'bg-amber-500/15 text-amber-400 border border-amber-500/30' }}">
                            {{ $upcomingSession->status === \App\Enums\PtSessionStatus::Confirmed ? 'Terkonfirmasi' : 'Menunggu Konfirmasi' }}
                        </span>
                        <span class="text-[11px] font-bold text-[#A1A1AA]">
                            {{ \Carbon\Carbon::parse($upcomingSession->session_date)->translatedFormat('l, d M') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#ED1B45]/15 text-[#ED1B45] flex items-center justify-center font-bold text-xs shrink-0 border border-[#ED1B45]/30">
                            {{ strtoupper(substr($upcomingSession->trainer->name ?? 'PT', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-bold text-white truncate font-poppins">
                                Coach {{ $upcomingSession->trainer->name ?? 'Personal Trainer' }}
                            </h4>
                            <p class="text-xs text-[#A1A1AA]">
                                Pukul {{ substr($upcomingSession->start_time, 0, 5) }} - {{ substr($upcomingSession->end_time, 0, 5) }} WIB
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State Sesi -->
                <div class="bg-[#1E1E1E]/60 border border-dashed border-white/10 rounded-2xl p-6 text-center space-y-2.5">
                    <div class="w-10 h-10 rounded-full bg-white/5 text-[#A1A1AA] flex items-center justify-center mx-auto">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-300">Belum ada sesi latihan terjadwal</p>
                    <p class="text-[11px] text-[#A1A1AA] max-w-xs mx-auto">
                        Gunakan kuota Anda untuk menjadwalkan latihan bersama Personal Trainer pilihan.
                    </p>
                    <div class="pt-1">
                        <a href="{{ route('mobile.member.pt-sessions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#ED1B45] text-white text-xs font-bold shadow-md hover:bg-[#D1123D] transition">
                            <span>+ Booking Sesi Baru</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- 5. Quick Actions Carousel / Cards -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300">
                Layanan &amp; Transaksi
            </h3>
            
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('mobile.member.pt-sessions.create') }}" class="p-3.5 bg-[#1E1E1E] border border-white/10 rounded-2xl hover:border-[#ED1B45]/50 transition flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-[#ED1B45] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="block text-xs font-bold text-white font-poppins">Booking PT</span>
                        <span class="text-[10px] text-[#A1A1AA]">Pilih jam &amp; slot</span>
                    </div>
                </a>

                <a href="{{ route('member.membership.packages') }}" class="p-3.5 bg-[#1E1E1E] border border-white/10 rounded-2xl hover:border-amber-500/50 transition flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <span class="block text-xs font-bold text-white font-poppins">Paket Gym</span>
                        <span class="text-[10px] text-[#A1A1AA]">Perpanjang akses</span>
                    </div>
                </a>
            </div>
        </div>

    </div>
</x-layouts.mobile-app>
