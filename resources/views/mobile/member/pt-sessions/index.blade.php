{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Jadwal & Riwayat Sesi Personal Trainer (Mobile Refactored)
| VIEW         : resources/views/mobile/member/pt-sessions/index.blade.php
| LAYOUT       : layouts.mobile-app
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
| KESELARASAN  : Selaras 1:1 Dengan Layout Hero Section Website Desktop
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

        <!-- Unified Hero Card: Selaras dengan Web Desktop (Riwayat Sesi Personal Trainer) -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 relative overflow-hidden text-[#16151A] border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-5">
            <!-- Top-Right Orange Glow Ambient -->
            <div class="absolute -top-16 -right-16 w-52 h-52 bg-[#FF5722]/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-4">
                <!-- Title & Subtitle Sesuai Website -->
                <div class="space-y-1">
                    <h1 class="text-xl sm:text-2xl font-black font-heading text-[#16151A] tracking-tight leading-snug">
                        Riwayat <span class="text-[#FF5722]">Sesi Latihan PT</span>
                    </h1>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        Monitoring performa latihan presisi tinggi bersama pelatih profesional bersertifikasi.
                    </p>
                </div>

                <!-- Sisa Kuota Bar Sesuai Website -->
                <div class="space-y-2 pt-1 border-t border-[#E5E7EB]/70">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-[#565A66] font-semibold text-[11px] uppercase tracking-wider">Sisa Kuota Sesi</span>
                        <div class="font-extrabold text-xs">
                            <span class="text-[#FF5722] font-black text-sm">{{ $remainingSessions }}</span>
                            <span class="text-[#16151A]"> / {{ $totalSessions }} Sesi Tersedia</span>
                        </div>
                    </div>

                    <!-- Progress bar kuota bernuansa gradient FitCore -->
                    <div class="w-full bg-orange-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#FF5722] to-[#FFB703] h-full rounded-full transition-all duration-300 shadow-xs"
                             style="width: {{ $quotaPercent }}%"></div>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-[#565A66] pt-0.5">
                        <span>
                            @if ($quota && $quota->end_date)
                                Masa berlaku s/d {{ \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d M Y') }}
                            @else
                                Belum ada paket aktif
                            @endif
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-[#16151A]">Semua Sesi: {{ $counts['all'] ?? 0 }}</span>
                            <span class="text-gray-300">•</span>
                            <a href="{{ route('mobile.member.pt-packages.index') }}" class="font-bold text-[#FF5722] hover:text-[#E64A19] transition">
                                Top-up / Beli &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                @if ($remainingSessions <= 0)
                    <!-- Notice Banner Jika Kuota Belum Tersedia -->
                    <div class="p-3 rounded-xl bg-orange-50/90 border border-orange-200/80 flex items-start gap-2.5 text-xs text-[#565A66]">
                        <svg class="w-4 h-4 text-[#FF5722] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="space-y-0.5">
                            <p class="font-bold text-[#16151A]">Kuota Sesi PT Belum Tersedia</p>
                            <p class="text-[11px] leading-relaxed">
                                Beli paket sesi PT terlebih dahulu agar Anda dapat memilih pelatih dan menjadwalkan sesi latihan.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- 3 Stat Boxes Sesuai Website Desktop (Menunggu, Terkonfirmasi, Dibatalkan) -->
                <div class="grid grid-cols-3 gap-2 pt-1">
                    <div class="bg-[#F6F6F7]/80 border border-[#E5E7EB] rounded-2xl p-2.5 text-center shadow-2xs">
                        <div class="text-xl font-black font-heading text-[#16151A]">{{ $counts['pending'] ?? 0 }}</div>
                        <div class="text-[9px] font-extrabold text-[#565A66] uppercase tracking-wider mt-0.5">MENUNGGU</div>
                    </div>
                    <div class="bg-[#F6F6F7]/80 border border-[#E5E7EB] rounded-2xl p-2.5 text-center shadow-2xs">
                        <div class="text-xl font-black font-heading text-[#16151A]">{{ $counts['confirmed'] ?? 0 }}</div>
                        <div class="text-[9px] font-extrabold text-[#565A66] uppercase tracking-wider mt-0.5">TERKONFIRMASI</div>
                    </div>
                    <div class="bg-[#F6F6F7]/80 border border-[#E5E7EB] rounded-2xl p-2.5 text-center shadow-2xs">
                        <div class="text-xl font-black font-heading text-[#16151A]">{{ $counts['cancelled'] ?? 0 }}</div>
                        <div class="text-[9px] font-extrabold text-[#565A66] uppercase tracking-wider mt-0.5">DIBATALKAN</div>
                    </div>
                </div>

                <!-- Tombol Action Utama Sesuai Website Desktop (Pill Rounded-Full) -->
                <div class="pt-1">
                    @if ($remainingSessions > 0)
                        <a href="{{ route('mobile.member.pt-sessions.create') }}"
                           class="w-full py-3.5 px-5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-[#FF5722]/25 hover:shadow-[#FF5722]/40 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <span class="text-sm font-black">+</span>
                            <span>Booking Sesi Baru</span>
                        </a>
                    @else
                        <a href="{{ route('mobile.member.pt-packages.index') }}"
                           class="w-full py-3.5 px-5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-[#FF5722]/25 hover:shadow-[#FF5722]/40 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <span class="text-sm font-black">+</span>
                            <span>Beli Paket Sesi PT (+ Booking Sesi)</span>
                        </a>
                    @endif
                </div>

            </div>
        </div>

        <!-- Kalender & Jadwal Sesi (Livewire Component) -->
        <div>
            <livewire:member.sessions.index />
        </div>

    </div>
</x-layouts.mobile-app>
