{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : PERSONAL TRAINER (PT)
| MODUL        : Dashboard Harian & Permintaan Sesi (Fitur #9)
| FILE         : resources/views/mobile/trainer/dashboard.blade.php
| LAYOUT       : layouts/mobile-trainer.blade.php
| AKSES ROUTE  : /m/trainer/dashboard atau /trainer/dashboard (name: mobile.trainer.dashboard)
| DESKRIPSI    : Tampilan mobile untuk pelatih pribadi dengan horizontal stats card row,
|                kartu konfirmasi permintaan sesi booking masuk dari klien,
|                dan agenda jadwal latihan harian terurut vertikal.
|                Menggunakan token warna DESIGN.md: primary #ED1B45, surface-dark #1E1E1E,
|                tombol pill full rounded, tipografi Poppins & Inter.
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-trainer title="Dashboard Pelatih - FitCore Coach" active="dashboard">
    <div class="space-y-5">

        <!-- 1. Sambutan & Status Pelatih -->
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#A1A1AA]">Selamat Bertugas,</p>
                <h1 class="text-xl font-black font-poppins text-white tracking-tight">
                    Coach {{ $trainer->name }}
                </h1>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                PT Aktif
            </span>
        </div>

        <!-- 2. Metrik Statistik Horizontal Scroll (stitch-prompts.md #9, Surface-Dark #1E1E1E, Radius 16px) -->
        <div class="flex gap-3 overflow-x-auto pb-1 no-scrollbar -mx-4 px-4">
            <!-- Stat 1: Sesi Hari Ini -->
            <div class="min-w-[140px] p-3.5 rounded-2xl bg-[#1E1E1E] border border-white/10 shrink-0 space-y-1 shadow-xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#A1A1AA] block">Sesi Hari Ini</span>
                <span class="text-2xl font-black font-poppins text-white block">{{ $todaySessionsCount }}</span>
                <span class="text-[10px] text-emerald-400 font-semibold block">Jadwal aktif</span>
            </div>

            <!-- Stat 2: Permintaan Pending -->
            <div class="min-w-[140px] p-3.5 rounded-2xl bg-[#1E1E1E] border border-white/10 shrink-0 space-y-1 shadow-xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 block">Butuh Respon</span>
                <span class="text-2xl font-black font-poppins text-amber-400 block">{{ $pendingSessions->count() }}</span>
                <span class="text-[10px] text-[#A1A1AA] font-semibold block">Menunggu konfirmasi</span>
            </div>

            <!-- Stat 3: Total Selesai Bulan Ini -->
            <div class="min-w-[140px] p-3.5 rounded-2xl bg-[#1E1E1E] border border-white/10 shrink-0 space-y-1 shadow-xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#A1A1AA] block">Selesai Bulan Ini</span>
                <span class="text-2xl font-black font-poppins text-white block">{{ $completedSessions ?? 0 }}</span>
                <span class="text-[10px] text-[#A1A1AA] font-semibold block">Sesi tuntas</span>
            </div>

            <!-- Stat 4: Total Member Aktif -->
            <div class="min-w-[140px] p-3.5 rounded-2xl bg-[#1E1E1E] border border-white/10 shrink-0 space-y-1 shadow-xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#A1A1AA] block">Klien Aktif</span>
                <span class="text-2xl font-black font-poppins text-white block">{{ $activeMembersCount }}</span>
                <span class="text-[10px] text-[#ED1B45] font-semibold block">Member dibimbing</span>
            </div>
        </div>

        <!-- 3. Permintaan Booking Masuk (Actionable Cards Sesuai Backend Controller) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    Permintaan Masuk ({{ $pendingSessions->count() }})
                </h3>
            </div>

            @forelse ($pendingSessions as $pending)
                <div class="p-4 rounded-2xl bg-[#1E1E1E] border border-amber-500/30 space-y-3 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-300">
                            {{ \Carbon\Carbon::parse($pending->session_date)->translatedFormat('l, d M Y') }}
                        </span>
                        <span class="text-xs font-bold text-[#ED1B45]">
                            {{ substr($pending->start_time, 0, 5) }} - {{ substr($pending->end_time, 0, 5) }} WIB
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-500/10 text-amber-400 flex items-center justify-center font-bold text-xs shrink-0">
                            {{ strtoupper(substr($pending->member->name ?? 'M', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs sm:text-sm font-bold text-white truncate font-poppins">
                                {{ $pending->member->name ?? 'Member FitCore' }}
                            </h4>
                            <p class="text-[11px] text-[#A1A1AA]">
                                Paket: {{ $pending->quota->ptPackage->name ?? 'Paket PT 1-on-1' }}
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Aksi Konfirmasi / Tolak (Pill Full Radius) -->
                    <div class="pt-2 border-t border-white/10 flex items-center gap-2">
                        <form method="POST" action="{{ route('trainer.sessions.reject', $pending) }}" class="flex-1">
                            @csrf
                            <button type="submit" onclick="return confirm('Tolak permintaan sesi ini?')"
                                    class="w-full py-2.5 rounded-full bg-white/10 text-[#A1A1AA] text-xs font-bold hover:bg-rose-500/20 hover:text-rose-400 transition cursor-pointer">
                                Tolak
                            </button>
                        </form>

                        <form method="POST" action="{{ route('trainer.sessions.confirm', $pending) }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2.5 rounded-full bg-[#ED1B45] text-white text-xs font-bold shadow-md hover:bg-[#D1123D] transition cursor-pointer">
                                Konfirmasi
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-4 bg-[#1E1E1E]/60 rounded-2xl border border-white/10 text-center text-xs text-[#A1A1AA]">
                    Tidak ada permintaan booking baru yang perlu direspon.
                </div>
            @endforelse
        </div>

        <!-- 4. Agenda Sesi Hari Ini (Vertical List Sesuai Backend Controller) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300">
                    Jadwal Hari Ini ({{ \Carbon\Carbon::today()->translatedFormat('d M Y') }})
                </h3>
                <a href="{{ route('trainer.sessions.index') }}" class="text-xs text-[#ED1B45] font-semibold hover:underline">
                    Lihat Kalender
                </a>
            </div>

            @forelse ($todaySessions as $session)
                <div class="p-4 rounded-2xl bg-[#1E1E1E] border border-white/10 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#ED1B45]">
                            {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                            Terkonfirmasi
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-white/10 text-white flex items-center justify-center font-bold text-xs shrink-0">
                            {{ strtoupper(substr($session->member->name ?? 'M', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-white truncate font-poppins">
                                {{ $session->member->name ?? 'Member FitCore' }}
                            </h4>
                            <p class="text-[10px] text-[#A1A1AA]">
                                WhatsApp: {{ $session->member->phone ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 bg-[#1E1E1E]/60 rounded-2xl border border-dashed border-white/10 text-center space-y-1">
                    <p class="text-xs font-semibold text-slate-300">Tidak ada jadwal sesi hari ini</p>
                    <p class="text-[11px] text-[#A1A1AA]">Gunakan waktu luang untuk menyusun program latihan klien.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-layouts.mobile-trainer>
