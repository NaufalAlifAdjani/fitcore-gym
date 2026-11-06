{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Daftar Riwayat & Jadwal Sesi PT (Fitur #5)
| FILE         : resources/views/mobile/member/pt-sessions/index.blade.php
| LAYOUT       : layouts/mobile-app.blade.php
| AKSES ROUTE  : /m/pt-sessions atau /pt-sessions (name: mobile.member.pt-sessions.index)
| DESKRIPSI    : Daftar kartu sesi latihan personal trainer yang diatur dalam
|                segmented tabs (Semua, Menunggu, Terkonfirmasi, Dibatalkan)
|                dengan ringkasan sisa saldo kuota aktif dan tombol booking baru.
|                Menggunakan token warna DESIGN.md: primary #ED1B45, surface-dark #1E1E1E,
|                tombol pill full rounded, tipografi Poppins & Inter.
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-app title="Jadwal Sesi PT - FitCore Athletic" active-tab="sessions">
    <div class="space-y-4">

        <!-- 1. Header & Tombol Tambah Sesi Baru -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black font-poppins text-white tracking-tight">
                    Sesi Latihan PT
                </h1>
                <p class="text-[11px] text-[#A1A1AA] font-medium font-sans">
                    Sisa saldo kuota: <strong class="text-emerald-400">{{ $quota->remaining_sessions ?? 0 }} Sesi</strong>
                </p>
            </div>

            <a href="{{ route('mobile.member.pt-sessions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white text-xs font-semibold shadow-md shadow-[#ED1B45]/25 transition active:scale-95">
                <span>+ Booking</span>
            </a>
        </div>

        <!-- 2. Segmented Tabs Filter (Pill Control per stitch-prompts #Shared Patterns) -->
        <div class="flex items-center gap-1 p-1 bg-[#1E1E1E] rounded-full border border-white/10 text-[11px] font-bold overflow-x-auto no-scrollbar">
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'all']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentTab === 'all' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#A1A1AA] hover:text-white' }}">
                Semua ({{ $counts['all'] ?? 0 }})
            </a>
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'pending']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentTab === 'pending' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#A1A1AA] hover:text-white' }}">
                Menunggu ({{ $counts['pending'] ?? 0 }})
            </a>
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'confirmed']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentTab === 'confirmed' || $currentTab === 'scheduled' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#A1A1AA] hover:text-white' }}">
                Terkonfirmasi ({{ $counts['confirmed'] ?? 0 }})
            </a>
            <a href="{{ route('mobile.member.pt-sessions.index', ['tab' => 'cancelled']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentTab === 'cancelled' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-[#A1A1AA] hover:text-white' }}">
                Batal ({{ $counts['cancelled'] ?? 0 }})
            </a>
        </div>

        <!-- 3. Daftar Kartu Sesi Latihan (Stacked Cards, Radius 16px, Surface-Dark #1E1E1E) -->
        <div class="space-y-3">
            @forelse ($sessions as $session)
                @php
                    $isPending = $session->status === \App\Enums\PtSessionStatus::Pending;
                    $isConfirmed = $session->status === \App\Enums\PtSessionStatus::Confirmed;
                    $isCancelled = $session->status === \App\Enums\PtSessionStatus::Cancelled;
                @endphp

                <div class="p-4 rounded-2xl bg-[#1E1E1E] border border-white/10 space-y-3 shadow-xs">
                    <div class="flex items-center justify-between">
                        @if ($isPending)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                Menunggu Konfirmasi
                            </span>
                        @elseif ($isConfirmed)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Terkonfirmasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/5 text-[#A1A1AA] border border-white/10">
                                Dibatalkan
                            </span>
                        @endif

                        <span class="text-xs font-bold text-white">
                            {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#ED1B45]/15 text-[#ED1B45] flex items-center justify-center font-bold text-xs shrink-0 border border-[#ED1B45]/30">
                            {{ strtoupper(substr($session->trainer->name ?? 'PT', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs sm:text-sm font-bold text-white truncate font-poppins">
                                Coach {{ $session->trainer->name ?? 'Pelatih' }}
                            </h4>
                            <p class="text-[11px] text-[#A1A1AA] font-sans">
                                Pukul {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB
                            </p>
                        </div>
                    </div>

                    <!-- Catatan Sesi jika ada -->
                    @if ($session->notes)
                        <div class="p-2.5 rounded-xl bg-white/5 border border-white/5 text-[11px] text-[#A1A1AA] italic">
                            "{{ $session->notes }}"
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 rounded-2xl bg-[#1E1E1E]/60 border border-dashed border-white/10 text-center space-y-2">
                    <p class="text-xs font-semibold text-slate-300">Tidak ada jadwal sesi pada filter ini</p>
                    <p class="text-[11px] text-[#A1A1AA]">
                        Pilih tab lain atau lakukan pemesanan sesi latihan baru.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('mobile.member.pt-sessions.create') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-[#ED1B45] text-white text-xs font-bold shadow-md hover:bg-[#D1123D] transition">
                            + Booking Sesi
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</x-layouts.mobile-app>
