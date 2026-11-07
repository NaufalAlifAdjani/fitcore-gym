{{--
ROLE: MEMBER
MODUL: Daftar Riwayat & Jadwal Sesi PT Mobile
VIEW: mobile.member.pt-sessions.index
LAYOUT: layouts.mobile-app
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #ED1B45, Text #16151A / #565A66)
--}}

@php
    $totalSessions = $quota->total_sessions ?? 0;
    $remainingSessions = $quota->remaining_sessions ?? 0;
    $usedSessions = $quota->used_sessions ?? 0;
    $quotaPercent = $totalSessions > 0 ? round(($remainingSessions / $totalSessions) * 100) : 0;
@endphp

<x-layouts.mobile-app title="Jadwal Sesi PT - FitCore Athletic" active-tab="pt-sessions">
    <div class="space-y-4">

        <!-- 1. Header Judul Halaman -->
        <div class="pb-1">
            <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">
                Sesi Latihan PT
            </h1>
            <p class="text-[11px] text-[#565A66] font-medium font-sans">
                Monitoring jadwal dan riwayat latihan bersama pelatih pribadi Anda.
            </p>
        </div>

        <!-- 2. Kuota Status Card & Tombol Booking Proporsional -->
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] uppercase font-bold text-[#565A66] tracking-wider block">Saldo Kuota PT</span>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-2xl font-black font-poppins text-[#ED1B45]">{{ $remainingSessions }}</span>
                        <span class="text-xs font-semibold text-[#565A66]">/ {{ $totalSessions }} Sesi</span>
                    </div>
                </div>

                <a href="{{ route('mobile.member.pt-sessions.create') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] active:scale-[0.98] text-white text-xs font-bold font-poppins shadow-md shadow-[#ED1B45]/25 transition shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Booking Sesi</span>
                </a>
            </div>

            <!-- Progress Bar Kuota Latihan -->
            <div class="space-y-1">
                <div class="w-full bg-[#F6F6F7] rounded-full h-2 overflow-hidden border border-[#E5E7EB]">
                    <div class="bg-[#ED1B45] h-full rounded-full transition-all duration-300"
                         style="width: {{ $quotaPercent }}%"></div>
                </div>
            </div>

            <div class="flex items-center justify-between text-[10px] text-[#565A66] pt-0.5 border-t border-[#E5E7EB]">
                <span>{{ $usedSessions }} sesi telah digunakan</span>
                <span>
                    @if ($quota && $quota->end_date)
                        Berlaku s/d {{ \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d M Y') }}
                    @else
                        Belum ada paket aktif
                    @endif
                </span>
            </div>
        </div>

        <!-- 3. Segmented Tabs Filter Status (Responsif, Tidak Terpotong) -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1 -mx-4 px-4 sm:mx-0 sm:px-0">
            <!-- Filter Semua -->
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'all']) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $currentTab === 'all' ? 'bg-[#ED1B45] text-white shadow-sm shadow-[#ED1B45]/20' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Semua</span>
                <span class="inline-flex items-center justify-center min-w-4 px-1.5 py-0.2 rounded-full text-[10px] {{ $currentTab === 'all' ? 'bg-white/25 text-white' : 'bg-gray-100 text-[#565A66]' }}">
                    {{ $counts['all'] ?? 0 }}
                </span>
            </a>

            <!-- Filter Menunggu -->
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'pending']) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $currentTab === 'pending' ? 'bg-[#ED1B45] text-white shadow-sm shadow-[#ED1B45]/20' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Menunggu</span>
                <span class="inline-flex items-center justify-center min-w-4 px-1.5 py-0.2 rounded-full text-[10px] {{ $currentTab === 'pending' ? 'bg-white/25 text-white' : 'bg-gray-100 text-[#565A66]' }}">
                    {{ $counts['pending'] ?? 0 }}
                </span>
            </a>

            <!-- Filter Terkonfirmasi -->
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'confirmed']) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $currentTab === 'confirmed' || $currentTab === 'scheduled' ? 'bg-[#ED1B45] text-white shadow-sm shadow-[#ED1B45]/20' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Terkonfirmasi</span>
                <span class="inline-flex items-center justify-center min-w-4 px-1.5 py-0.2 rounded-full text-[10px] {{ $currentTab === 'confirmed' || $currentTab === 'scheduled' ? 'bg-white/25 text-white' : 'bg-gray-100 text-[#565A66]' }}">
                    {{ $counts['confirmed'] ?? 0 }}
                </span>
            </a>

            <!-- Filter Dibatalkan -->
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'cancelled']) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $currentTab === 'cancelled' ? 'bg-[#ED1B45] text-white shadow-sm shadow-[#ED1B45]/20' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Dibatalkan</span>
                <span class="inline-flex items-center justify-center min-w-4 px-1.5 py-0.2 rounded-full text-[10px] {{ $currentTab === 'cancelled' ? 'bg-white/25 text-white' : 'bg-gray-100 text-[#565A66]' }}">
                    {{ $counts['cancelled'] ?? 0 }}
                </span>
            </a>
        </div>

        <!-- 4. Daftar Kartu Sesi Latihan -->
        <div class="space-y-3">
            @forelse ($sessions as $session)
                @php
                    $isPending = $session->status === \App\Enums\PtSessionStatus::Pending;
                    $isConfirmed = $session->status === \App\Enums\PtSessionStatus::Confirmed;
                    $isCancelled = $session->status === \App\Enums\PtSessionStatus::Cancelled;
                @endphp

                <a href="{{ route('mobile.member.pt-sessions.show', $session->id) }}"
                   class="block p-4 rounded-2xl bg-white border border-[#E5E7EB] hover:border-[#ED1B45]/40 space-y-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)] transition active:scale-[0.99] group">
                    <div class="flex items-center justify-between">
                        @if ($isPending)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu Konfirmasi
                            </span>
                        @elseif ($isConfirmed)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Terkonfirmasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                Dibatalkan
                            </span>
                        @endif

                        <span class="text-xs font-bold text-[#16151A]">
                            {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center font-bold font-poppins text-xs shrink-0 border border-[#ED1B45]/20 group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr($session->trainer->name ?? 'PT', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs sm:text-sm font-bold text-[#16151A] truncate font-poppins">
                                Coach {{ $session->trainer->name ?? 'Pelatih' }}
                            </h4>
                            <p class="text-[11px] text-[#565A66] font-sans flex items-center gap-1 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Pukul {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB</span>
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-[#565A66] group-hover:text-[#ED1B45] group-hover:translate-x-0.5 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>

                    @if ($session->notes)
                        <div class="p-2.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] text-[10px] text-[#565A66] italic line-clamp-1">
                            "{{ $session->notes }}"
                        </div>
                    @endif
                </a>
            @empty
                <div class="p-8 rounded-2xl bg-white border border-dashed border-[#E5E7EB] text-center space-y-2 shadow-[0_4px_12px_rgba(0,0,0,0.02)]">
                    <div class="w-12 h-12 rounded-2xl bg-[#ED1B45]/10 text-[#ED1B45] flex items-center justify-center mx-auto mb-2 border border-[#ED1B45]/20">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#16151A]">Tidak ada jadwal sesi pada filter ini</p>
                    <p class="text-[11px] text-[#565A66] max-w-xs mx-auto">
                        Pilih tab status lain atau lakukan reservasi sesi latihan baru bersama pelatih pribadi.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('mobile.member.pt-sessions.create') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white text-xs font-bold font-poppins shadow-md shadow-[#ED1B45]/25 transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Booking Sesi PT</span>
                        </a>
                    </div>
                </div>
            @endforelse

            <!-- Pagination jika ada -->
            @if ($sessions->hasPages())
                <div class="pt-2">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.mobile-app>
