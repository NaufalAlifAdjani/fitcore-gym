{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Beranda Utama (Dashboard Member - 100% Visual Sync)
| FILE         : resources/views/mobile/member/dashboard.blade.php
| LAYOUT       : layouts/mobile-app.blade.php
| AKSES ROUTE  : /m/dashboard atau /member/dashboard (name: mobile.member.dashboard)
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-app title="Beranda Member - FitCore Athletic" active-tab="beranda">
    <div class="space-y-5">

        <!-- 1. Header Sambutan & Status Membership (Sesuai Web Desktop) -->
        <header class="space-y-2">
            <div class="flex items-center justify-between">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-[#FF5722] text-[11px] font-inter font-bold uppercase tracking-wider border border-orange-100">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#FF5722]"></span>
                    Member Portal FitCore
                </div>

                @if ($activeMembership)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Member Aktif
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Belum Langganan
                    </span>
                @endif
            </div>

            <div class="mt-1">
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#565A66]">Selamat Datang Kembali,</p>
                <h1 class="font-poppins font-extrabold text-2xl sm:text-3xl text-[#16151A] tracking-tight leading-tight">
                    {{ $user->name }}
                </h1>
            </div>
            <p class="font-inter text-xs text-[#565A66] leading-relaxed">
                Kelola keanggotaan gym Anda, jadwalkan sesi latihan bersama pelatih pribadi, dan akses kartu digital untuk check-in mandiri.
            </p>
        </header>

        <!-- 2. Shortcut VIP QR Pass Card (Akses Cepat Turnstile) -->
        <a href="{{ route('mobile.member.card') }}" class="block relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#FF5722] via-[#F4511E] to-[#E64A19] p-4 text-white shadow-md shadow-orange-500/20 transition active:scale-[0.99] group">
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

        <!-- 3. Ringkasan Kuota & Status Keanggotaan (2 Card Grid) -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Stat 1: Sisa Sesi PT -->
            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 flex flex-col justify-between shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66]">Sisa Kuota PT</span>
                    <span class="w-2 h-2 rounded-full bg-[#FF5722]"></span>
                </div>
                <div class="mt-2.5">
                    <div class="font-poppins font-black text-2xl text-[#16151A]">
                        {{ $quota->remaining_sessions ?? 0 }} <span class="text-xs font-semibold text-[#565A66]">Sesi</span>
                    </div>
                    <p class="mt-1 text-[10px] text-[#565A66] truncate font-sans">
                        @if ($quota && $quota->end_date)
                            Hingga {{ \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d M Y') }}
                        @else
                            Tidak ada kuota aktif
                        @endif
                    </p>
                </div>
            </div>

            <!-- Stat 2: Masa Aktif Membership -->
            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 flex flex-col justify-between shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66]">Status Member</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-2.5">
                    <div class="font-poppins font-bold text-base text-[#16151A] truncate">
                        {{ $activeMembership->package->name ?? 'Non-Aktif' }}
                    </div>
                    <p class="mt-1 text-[10px] text-emerald-600 truncate font-sans font-medium">
                        @if ($activeMembership && $activeMembership->end_date)
                            Sisa {{ now()->diffInDays(\Carbon\Carbon::parse($activeMembership->end_date), false) }} hari
                        @else
                            Silakan pilih paket
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- 4. Navigasi Aksi Cepat (Identik dengan Web Desktop) -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                Layanan Utama Member
            </h3>

            <div class="grid grid-cols-1 gap-3">
                <!-- Card 1: Kartu Member Digital -->
                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-poppins font-bold text-sm text-[#16151A] truncate">Kartu Member Digital</h4>
                            <p class="text-[11px] text-[#565A66] truncate">QR Code akses pintu turnstile instan</p>
                        </div>
                    </div>
                    <a href="{{ route('mobile.member.card') }}" class="px-3.5 py-1.5 rounded-full text-[11px] font-bold bg-[#FF5722] text-white hover:bg-[#E64A19] transition shadow-xs shrink-0">
                        Buka &rarr;
                    </a>
                </div>

                <!-- Card 2: Paket Membership -->
                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-poppins font-bold text-sm text-[#16151A] truncate">Paket Membership</h4>
                            <p class="text-[11px] text-[#565A66] truncate">Pilih atau perpanjang akses gym</p>
                        </div>
                    </div>
                    <a href="{{ route('mobile.member.packages') }}" class="px-3.5 py-1.5 rounded-full text-[11px] font-bold border-2 border-[#FF5722] text-[#FF5722] hover:bg-[#FF5722] hover:text-white transition shadow-xs shrink-0">
                        Katalog &rarr;
                    </a>
                </div>

                <!-- Card 3: Sesi Personal Trainer -->
                <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-poppins font-bold text-sm text-[#16151A] truncate">Sesi Personal Trainer</h4>
                            <p class="text-[11px] text-[#565A66] truncate">Jadwal sesi bersama pelatih bersertifikasi</p>
                        </div>
                    </div>
                    <a href="{{ route('mobile.member.pt-sessions.index') }}" class="px-3.5 py-1.5 rounded-full text-[11px] font-bold border-2 border-[#FF5722] text-[#FF5722] hover:bg-[#FF5722] hover:text-white transition shadow-xs shrink-0">
                        Jadwal &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- 5. Jadwal Sesi Terdekat (Upcoming PT Session) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                    Jadwal Sesi Terdekat
                </h3>
                <a href="{{ route('mobile.member.pt-sessions.index') }}" class="text-xs text-[#FF5722] font-semibold hover:underline">
                    Lihat Semua
                </a>
            </div>

            @if ($upcomingSession)
                <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 space-y-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $upcomingSession->status === \App\Enums\PtSessionStatus::Confirmed ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                            {{ $upcomingSession->status === \App\Enums\PtSessionStatus::Confirmed ? 'Terkonfirmasi' : 'Menunggu Konfirmasi' }}
                        </span>
                        <span class="text-[11px] font-bold text-[#565A66]">
                            {{ \Carbon\Carbon::parse($upcomingSession->session_date)->translatedFormat('l, d M') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-50 text-[#FF5722] flex items-center justify-center font-bold text-xs shrink-0 border border-orange-200">
                            {{ strtoupper(substr($upcomingSession->trainer->name ?? 'PT', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-bold text-[#16151A] truncate font-poppins">
                                Coach {{ $upcomingSession->trainer->name ?? 'Personal Trainer' }}
                            </h4>
                            <p class="text-xs text-[#565A66]">
                                Pukul {{ substr($upcomingSession->start_time, 0, 5) }} - {{ substr($upcomingSession->end_time, 0, 5) }} WIB
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State Sesi -->
                <div class="bg-white/70 border border-dashed border-[#E5E7EB] rounded-2xl p-6 text-center space-y-2.5">
                    <div class="w-10 h-10 rounded-full bg-gray-100 text-[#565A66] flex items-center justify-center mx-auto">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-[#16151A]">Belum ada sesi latihan terjadwal</p>
                    <p class="text-[11px] text-[#565A66] max-w-xs mx-auto">
                        Gunakan kuota Anda untuk menjadwalkan latihan bersama Personal Trainer pilihan.
                    </p>
                    <div class="pt-1">
                        <a href="{{ route('mobile.member.pt-sessions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#FF5722] text-white text-xs font-bold shadow-md shadow-orange-500/20 hover:bg-[#E64A19] transition">
                            <span>+ Booking Sesi Baru</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- 6. Informasi Fasilitas & Jam Operasional (Sesuai Web Desktop) -->
        <div class="bg-white rounded-2xl p-5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
            <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                <div>
                    <h3 class="font-poppins font-bold text-sm text-[#16151A]">Fasilitas FitCore</h3>
                    <p class="text-[10px] text-[#565A66]">Standar kejuaraan dunia &amp; atletik</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    24 Jam Nonstop
                </span>
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] space-y-1">
                    <div class="w-6 h-6 rounded-lg bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h4 class="font-poppins font-bold text-xs text-[#16151A] leading-tight">Hammer &amp; Eleiko</h4>
                    <p class="text-[9px] text-[#565A66] leading-tight">Beban kalibrasi standar dunia.</p>
                </div>

                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] space-y-1">
                    <div class="w-6 h-6 rounded-lg bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h4 class="font-poppins font-bold text-xs text-[#16151A] leading-tight">Trainer CSCS</h4>
                    <p class="text-[9px] text-[#565A66] leading-tight">Pelatih berlisensi internasional.</p>
                </div>

                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] space-y-1">
                    <div class="w-6 h-6 rounded-lg bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h4 class="font-poppins font-bold text-xs text-[#16151A] leading-tight">Sauna &amp; Ice Bath</h4>
                    <p class="text-[9px] text-[#565A66] leading-tight">Fasilitas pemulihan atletik.</p>
                </div>

                <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] space-y-1">
                    <div class="w-6 h-6 rounded-lg bg-orange-50 text-[#FF5722] border border-orange-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h4 class="font-poppins font-bold text-xs text-[#16151A] leading-tight">QR Check-In</h4>
                    <p class="text-[9px] text-[#565A66] leading-tight">Akses turnstile mandiri cepat.</p>
                </div>
            </div>
        </div>

    </div>
</x-layouts.mobile-app>
