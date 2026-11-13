{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Jadwal & Riwayat Sesi Personal Trainer (Mobile Refactored)
| VIEW         : resources/views/mobile/member/pt-sessions/index.blade.php
| LAYOUT       : layouts.mobile-app
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

@php
    $totalSessions = $quota->total_sessions ?? 0;
    $remainingSessions = $quota->remaining_sessions ?? 0;
    $usedSessions = $quota->used_sessions ?? 0;
    $quotaPercent = $totalSessions > 0 ? round(($remainingSessions / $totalSessions) * 100) : 0;
@endphp

<x-layouts.mobile-app title="Jadwal Sesi PT - FitCore Athletic" active-tab="pt-sessions">
    <div class="space-y-5">

        <!-- 1. Hero Card Terintegrasi (Judul, Kuota Sesi, Metrik, & Booking Button) -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 relative overflow-hidden text-[#16151A] border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
            <!-- Top-Right Orange Glow Ambient -->
            <div class="absolute -top-16 -right-16 w-48 h-48 bg-[#FF5722]/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-4">
                <!-- Eyebrow & Judul Dua Warna Kontras -->
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-50 border border-orange-200 text-[10px] font-black uppercase tracking-wider text-[#FF5722] mb-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#FF5722] animate-pulse"></span>
                        Sesi Latihan PT
                    </div>
                    <h1 class="text-2xl font-black font-poppins text-[#16151A] tracking-tight leading-tight">
                        Riwayat Sesi <span class="text-[#FF5722]">Personal</span> <span class="text-[#FF5722]">Trainer</span>
                    </h1>
                    <p class="text-xs text-[#565A66] mt-1.5 leading-relaxed font-normal font-sans">
                        Monitoring performa latihan presisi tinggi bersama pelatih profesional bersertifikasi.
                    </p>
                </div>

                <!-- Informasi Kuota Sesi Terintegrasi (Progress Bar Tipis) -->
                <div class="space-y-2 pt-1 border-t border-[#E5E7EB]/70">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-[#565A66] font-medium">Sisa Kuota Sesi</span>
                        <div class="font-extrabold font-poppins">
                            <span class="text-[#FF5722]">{{ $remainingSessions }}</span>
                            <span class="text-[#16151A]"> / {{ $totalSessions }} Sesi Tersedia</span>
                        </div>
                    </div>

                    <!-- Progress bar kuota tipis h-2 -->
                    <div class="w-full bg-[#F6F6F7] rounded-full h-2 overflow-hidden border border-[#E5E7EB]">
                        <div class="bg-gradient-to-r from-[#FF5722] to-[#FFB703] h-full rounded-full transition-all duration-300 shadow-xs"
                             style="width: {{ $quotaPercent }}%"></div>
                    </div>

                    <!-- Teks Masa Berlaku Paket & Ringkasan Total Semua Sesi -->
                    <div class="flex items-center justify-between text-[11px] text-[#565A66]">
                        <span>
                            @if ($quota && $quota->end_date)
                                Berlaku s/d {{ \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d F Y') }}
                            @else
                                Belum ada paket aktif
                            @endif
                        </span>
                        <span class="font-medium text-[#16151A]">Semua Sesi: {{ $counts['all'] ?? 0 }}</span>
                    </div>
                </div>

                <!-- 2. Kotak Metrik Ringkasan (Menunggu, Terkonfirmasi, Dibatalkan - Grid 3 Kolom) -->
                <div class="grid grid-cols-3 gap-2 pt-1">
                    <div class="bg-white border border-[#E5E7EB] rounded-2xl p-2.5 text-center shadow-xs">
                        <div class="text-xl font-black font-poppins text-[#16151A]">{{ $counts['pending'] ?? 0 }}</div>
                        <div class="text-[9px] font-extrabold text-[#565A66] uppercase tracking-wider mt-0.5">MENUNGGU</div>
                    </div>
                    <div class="bg-white border border-[#E5E7EB] rounded-2xl p-2.5 text-center shadow-xs">
                        <div class="text-xl font-black font-poppins text-[#16151A]">{{ $counts['confirmed'] ?? 0 }}</div>
                        <div class="text-[9px] font-extrabold text-[#565A66] uppercase tracking-wider mt-0.5">TERKONFIRMASI</div>
                    </div>
                    <div class="bg-white border border-[#E5E7EB] rounded-2xl p-2.5 text-center shadow-xs">
                        <div class="text-xl font-black font-poppins text-[#16151A]">{{ $counts['cancelled'] ?? 0 }}</div>
                        <div class="text-[9px] font-extrabold text-[#565A66] uppercase tracking-wider mt-0.5">DIBATALKAN</div>
                    </div>
                </div>

                <!-- 3. Tombol Booking Sesi (Touch Ergonomics, w-full di bagian bawah header) -->
                <div class="pt-1">
                    <a href="{{ route('mobile.member.pt-sessions.create') }}"
                       class="flex items-center justify-center gap-2 w-full py-3.5 px-4 rounded-full bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] text-white font-bold font-poppins text-xs uppercase tracking-wider shadow-lg shadow-[#FF5722]/25 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>+ Booking Sesi Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. Komponen Kalender Bulanan & Manajemen Sesi Latihan -->
        <div>
            <livewire:member.sessions.index />
        </div>

    </div>
</x-layouts.mobile-app>
