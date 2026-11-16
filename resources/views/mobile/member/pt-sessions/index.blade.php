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
    <div class="space-y-4">

        <!-- 1. Header Halaman (Bersih, Alami, & Tipografi Jelas) -->
        <header class="space-y-1">
            <h1 class="text-2xl font-black font-poppins text-[#16151A] tracking-tight">
                Sesi Latihan PT
            </h1>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Kelola jadwal latihan privat dan pantau sisa kuota sesi personal trainer Anda.
            </p>
        </header>

        <!-- 2. Kartu Saldo Kuota Sesi (Terstruktur & Terhubung ke Top-up) -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66]">Sisa Kuota Sesi</span>
                <a href="{{ route('mobile.member.pt-packages.index') }}" class="text-xs font-bold text-[#FF5722] hover:text-[#E64A19] transition">
                    Top-up / Beli Sesi &rarr;
                </a>
            </div>

            <div>
                <div class="flex items-baseline gap-1.5">
                    <span class="font-poppins font-black text-2xl text-[#16151A]">{{ $remainingSessions }}</span>
                    <span class="text-xs font-semibold text-[#565A66]">/ {{ $totalSessions }} Sesi Tersedia</span>
                </div>

                <!-- Progress bar kuota tipis -->
                <div class="w-full bg-[#F6F6F7] rounded-full h-2 overflow-hidden border border-[#E5E7EB] mt-2">
                    <div class="bg-[#FF5722] h-full rounded-full transition-all duration-300 shadow-xs"
                         style="width: {{ $quotaPercent }}%"></div>
                </div>
            </div>

            <div class="flex items-center justify-between text-[11px] text-[#565A66] pt-2 border-t border-[#E5E7EB]/70">
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

        <!-- 3. Tombol Utama Booking Sesi (Clean, Tanpa Ikon Ganda) -->
        <div>
            <a href="{{ route('mobile.member.pt-sessions.create') }}"
               class="w-full py-3 px-4 rounded-xl bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] text-white font-bold font-poppins text-xs tracking-wide shadow-md shadow-orange-500/20 transition-all flex items-center justify-center">
                <span>+ Booking Sesi Baru</span>
            </a>
        </div>

        <!-- 4. Ringkasan Status Sesi (3 Kolom Seimbang) -->
        <div class="grid grid-cols-3 gap-2.5">
            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-3 text-center shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <div class="text-xl font-black font-poppins text-[#16151A]">{{ $counts['pending'] ?? 0 }}</div>
                <div class="text-[10px] font-bold text-[#565A66] mt-0.5">Menunggu</div>
            </div>
            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-3 text-center shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <div class="text-xl font-black font-poppins text-[#16151A]">{{ $counts['confirmed'] ?? 0 }}</div>
                <div class="text-[10px] font-bold text-[#565A66] mt-0.5">Terkonfirmasi</div>
            </div>
            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-3 text-center shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <div class="text-xl font-black font-poppins text-[#16151A]">{{ $counts['cancelled'] ?? 0 }}</div>
                <div class="text-[10px] font-bold text-[#565A66] mt-0.5">Dibatalkan</div>
            </div>
        </div>

        <!-- 5. Kalender & Jadwal Sesi -->
        <div>
            <livewire:member.sessions.index />
        </div>

    </div>
</x-layouts.mobile-app>
