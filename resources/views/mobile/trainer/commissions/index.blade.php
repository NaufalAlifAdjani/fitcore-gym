{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : PERSONAL TRAINER (PT)
| MODUL        : Pendapatan & Komisi Sesi Latihan Mobile (100% Visual Sync)
| VIEW         : mobile.trainer.commissions.index
| LAYOUT       : layouts.mobile-trainer
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-trainer title="Pendapatan & Komisi - FitCore Coach" active="commissions">
    <div class="space-y-4">

        <!-- 1. Header Judul Halaman (Sesuai Web Desktop) -->
        <header class="space-y-2">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Shift: {{ $trainer->shift_label }} ({{ $trainer->shift_hours_text }})</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Tarif: Rp {{ number_format($currentRate, 0, ',', '.') }} / Sesi</span>
                </span>
            </div>

            <h1 class="font-poppins font-extrabold text-2xl text-[#16151A] tracking-tight leading-tight">
                Pendapatan &amp; Komisi
            </h1>
            <p class="font-inter text-xs text-[#565A66] leading-relaxed">
                Pantau rincian komisi yang Anda peroleh dari setiap sesi private training yang telah diselesaikan.
            </p>
        </header>

        <!-- 2. 4 Stat Cards Ringkasan (2x2 Grid - Identik Visual Web Desktop) -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Card 1: Komisi Bulan Ini -->
            <div class="rounded-2xl bg-white p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex flex-col justify-between">
                <div>
                    <span class="text-[9px] font-bold text-[#565A66] uppercase tracking-wider block font-inter">Komisi Bulan Ini</span>
                    <h3 class="text-lg font-black font-poppins text-[#10B981] mt-1">Rp {{ number_format($monthEarnings, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-[#565A66] mt-0.5">{{ \Carbon\Carbon::parse($selectedMonth.'-01')->translatedFormat('F Y') }}</p>
                </div>
            </div>

            <!-- Card 2: Sesi Selesai Bulan Ini -->
            <div class="rounded-2xl bg-white p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex flex-col justify-between">
                <div>
                    <span class="text-[9px] font-bold text-[#565A66] uppercase tracking-wider block font-inter">Sesi Selesai</span>
                    <h3 class="text-xl font-black font-poppins text-[#16151A] mt-1">{{ number_format($monthCount) }}</h3>
                    <p class="text-[10px] text-[#3B82F6] font-semibold mt-0.5">Sesi completed</p>
                </div>
            </div>

            <!-- Card 3: Total Seluruh Waktu -->
            <div class="rounded-2xl bg-white p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex flex-col justify-between">
                <div>
                    <span class="text-[9px] font-bold text-[#565A66] uppercase tracking-wider block font-inter">Total Seluruh Waktu</span>
                    <h3 class="text-lg font-black font-poppins text-[#FF5722] mt-1 truncate">Rp {{ number_format($allTimeEarnings, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-[#565A66] mt-0.5">{{ $allTimeCount }} total sesi</p>
                </div>
            </div>

            <!-- Card 4: Tarif Komisi per Sesi -->
            <div class="rounded-2xl bg-white p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex flex-col justify-between">
                <div>
                    <span class="text-[9px] font-bold text-[#565A66] uppercase tracking-wider block font-inter">Tarif per Sesi</span>
                    <h3 class="text-lg font-black font-poppins text-[#16151A] mt-1 truncate">Rp {{ number_format($currentRate, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-[#10B981] font-semibold mt-0.5">Sesuai grade trainer</p>
                </div>
            </div>
        </div>

        <!-- 3. Filter Bulan -->
        <div class="rounded-2xl bg-white p-3.5 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
            <form method="GET" action="{{ route('mobile.trainer.commissions.index') }}" class="flex items-center gap-2">
                <input 
                    type="month" 
                    name="month" 
                    value="{{ $selectedMonth }}" 
                    class="flex-1 rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] px-3 py-1.5 text-xs text-[#16151A] focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722] focus:outline-none"
                >
                <button 
                    type="submit" 
                    class="px-4 py-1.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold transition shadow-xs cursor-pointer shrink-0"
                >
                    Filter
                </button>
            </form>
        </div>

        <!-- 4. Daftar Rincian Sesi Selesai (Stacked Cards - Identik Web Desktop) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                    Rincian Sesi ({{ $monthlySessions->total() }})
                </h3>
            </div>

            @forelse ($monthlySessions as $session)
                @php
                    $sessionEarnings = $session->trainer_earnings ?: $currentRate;
                @endphp
                <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] space-y-2.5 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#16151A]">
                            {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y') }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Lunas / Selesai
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <h4 class="text-xs sm:text-sm font-bold text-[#16151A] font-poppins truncate">
                                {{ $session->member->name ?? 'Member FitCore' }}
                            </h4>
                            <p class="text-[11px] text-[#565A66] truncate">
                                Pukul {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB
                            </p>
                        </div>

                        <div class="text-right shrink-0">
                            <span class="font-poppins font-black text-sm text-[#10B981]">
                                +Rp {{ number_format($sessionEarnings, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 rounded-2xl bg-white border border-dashed border-[#E5E7EB] text-center space-y-2">
                    <p class="text-xs font-bold text-[#16151A]">Tidak Ada Sesi di Bulan Ini</p>
                    <p class="text-[11px] text-[#565A66]">
                        Belum ada sesi latihan privat yang diselesaikan pada periode {{ \Carbon\Carbon::parse($selectedMonth.'-01')->translatedFormat('F Y') }}.
                    </p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if ($monthlySessions->hasPages())
                <div class="pt-2">
                    {{ $monthlySessions->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.mobile-trainer>
