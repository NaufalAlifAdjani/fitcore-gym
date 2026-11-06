{{--
ROLE: MEMBER
MODUL: Daftar Riwayat & Jadwal Sesi PT Mobile
VIEW: mobile.member.pt-sessions.index
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #ED1B45, Text #16151A / #565A66)
--}}

@php
    $totalSessions = $quota->total_sessions ?? 0;
    $remainingSessions = $quota->remaining_sessions ?? 0;
    $usedSessions = $quota->used_sessions ?? 0;
    $quotaPercent = $totalSessions > 0 ? round(($remainingSessions / $totalSessions) * 100) : 0;
@endphp

<x-layouts.mobile-app title="Jadwal Sesi PT - FitCore Athletic" active-tab="sessions">
    <div class="space-y-4">

        <!-- 1. Header & Tombol Tambah Sesi Baru -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">
                    Sesi Latihan PT
                </h1>
                <p class="text-[11px] text-[#565A66] font-medium font-sans">
                    Monitoring sesi latihan bersama pelatih pribadi Anda.
                </p>
            </div>

            <a href="{{ route('mobile.member.pt-sessions.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white text-xs font-bold shadow-md shadow-[#ED1B45]/25 transition active:scale-95">
                <span>+ Booking</span>
            </a>
        </div>

        <!-- 2. Kuota Status Card (Progress Bar & Stats) -->
        <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <div class="flex items-center justify-between text-xs">
                <span class="text-[10px] uppercase font-bold text-[#565A66] tracking-wider">Saldo Kuota Latihan</span>
                <div class="font-extrabold text-xs">
                    <span class="text-[#ED1B45] font-poppins text-sm">{{ $remainingSessions }}</span>
                    <span class="text-[#565A66]"> / {{ $totalSessions }} Sesi Tersedia</span>
                </div>
            </div>

            <!-- Progress Bar Kuota -->
            <div class="w-full bg-[#F6F6F7] rounded-full h-2 overflow-hidden border border-[#E5E7EB]">
                <div class="bg-[#ED1B45] h-full rounded-full transition-all duration-300"
                     style="width: {{ $quotaPercent }}%"></div>
            </div>

            <div class="flex items-center justify-between text-[10px] text-[#565A66]">
                <span>{{ $usedSessions }} Sesi Telah Digunakan</span>
                <span>
                    @if ($quota && $quota->end_date)
                        Masa aktif s/d {{ \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d M Y') }}
                    @else
                        Tidak ada paket aktif
                    @endif
                </span>
            </div>
        </div>

        <!-- 3. Segmented Tabs Filter (Semua, Menunggu, Terkonfirmasi, Batal) -->
        <div class="flex items-center gap-1 p-1 bg-white rounded-full border border-[#E5E7EB] text-[11px] font-bold overflow-x-auto no-scrollbar shadow-xs">
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'all']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentTab === 'all' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                Semua ({{ $counts['all'] ?? 0 }})
            </a>
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'pending']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentTab === 'pending' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                Menunggu ({{ $counts['pending'] ?? 0 }})
            </a>
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'confirmed']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentTab === 'confirmed' || $currentTab === 'scheduled' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                Terkonfirmasi ({{ $counts['confirmed'] ?? 0 }})
            </a>
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'cancelled']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentTab === 'cancelled' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                Batal ({{ $counts['cancelled'] ?? 0 }})
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
                   class="block p-4 rounded-2xl bg-white border border-[#E5E7EB] hover:border-[#ED1B45]/50 space-y-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)] transition active:scale-[0.99] group">
                    <div class="flex items-center justify-between">
                        @if ($isPending)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Menunggu Konfirmasi
                            </span>
                        @elseif ($isConfirmed)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
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
                        <div class="w-10 h-10 rounded-xl bg-gray-100 text-[#16151A] flex items-center justify-center font-bold text-xs shrink-0 border border-[#E5E7EB] group-hover:border-[#ED1B45]/40 transition">
                            {{ strtoupper(substr($session->trainer->name ?? 'PT', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs sm:text-sm font-bold text-[#16151A] truncate font-poppins">
                                Coach {{ $session->trainer->name ?? 'Pelatih' }}
                            </h4>
                            <p class="text-[11px] text-[#565A66] font-sans">
                                Pukul {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-[#565A66] group-hover:text-[#ED1B45] group-hover:translate-x-0.5 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>

                    @if ($session->notes)
                        <div class="p-2 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] text-[10px] text-[#565A66] italic line-clamp-1">
                            "{{ $session->notes }}"
                        </div>
                    @endif
                </a>
            @empty
                <div class="p-8 rounded-2xl bg-white border border-dashed border-[#E5E7EB] text-center space-y-2 shadow-[0_4px_12px_rgba(0,0,0,0.02)]">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 text-[#565A66] flex items-center justify-center mx-auto mb-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#16151A]">Tidak ada jadwal sesi pada filter ini</p>
                    <p class="text-[11px] text-[#565A66]">
                        Pilih tab status lain atau lakukan reservasi sesi latihan baru bersama pelatih pribadi.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('mobile.member.pt-sessions.create') }}"
                           class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-[#ED1B45] text-white text-xs font-bold shadow-md hover:bg-[#D1123D] transition">
                            + Booking Sesi PT
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
