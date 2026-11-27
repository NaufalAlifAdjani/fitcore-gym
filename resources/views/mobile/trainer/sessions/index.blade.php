{{--
ROLE: PERSONAL TRAINER (PT)
MODUL: Jadwal & Riwayat Sesi Pelatih Mobile
VIEW: mobile.trainer.sessions.index
LAYOUT: layouts.mobile-trainer
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

<x-layouts.mobile-trainer title="Jadwal Sesi - FitCore Coach" active="sessions">
    <div class="space-y-4" x-data="{
        showRejectModal: false,
        rejectActionUrl: '',
        memberName: '',
        sessionTime: '',
        openRejectModal(actionUrl, name, time) {
            this.rejectActionUrl = actionUrl;
            this.memberName = name;
            this.sessionTime = time;
            this.showRejectModal = true;
        }
    }">

        <!-- 1. Header Judul Halaman & Mode Switcher (Kalender / Daftar Sesi) -->
        <div class="flex flex-col gap-3 pb-1">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight uppercase">
                        Jadwal Sesi Klien
                    </h1>
                    <p class="text-[11px] text-[#565A66] font-medium font-sans">
                        Pantau agenda latihan dan kelola konfirmasi sesi member Anda.
                    </p>
                </div>
            </div>

            <!-- Segmented Switcher: Kalender vs Daftar Sesi (Sesuai Desktop) -->
            <div class="inline-flex items-center rounded-full border border-[#E5E7EB] p-1 bg-white shadow-2xs self-start">
                <a href="{{ route('mobile.trainer.sessions.index', array_merge(request()->query(), ['mode' => 'calendar', 'date' => $selectedDate ?? now()->toDateString()])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition {{ ($mode ?? 'calendar') === 'calendar' ? 'bg-[#FF5722] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Kalender</span>
                </a>
                <a href="{{ route('mobile.trainer.sessions.index', array_merge(request()->query(), ['mode' => 'tasks'])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 transition {{ ($mode ?? 'calendar') === 'tasks' ? 'bg-[#FF5722] text-white shadow-xs' : 'text-[#565A66] hover:text-[#16151A]' }}">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span>Daftar Sesi</span>
                </a>
            </div>
        </div>

        @php
            $todayDate = \Carbon\Carbon::today()->toDateString();
            $prevDate = $currentDate->copy()->subMonth()->toDateString();
            $nextDate = $currentDate->copy()->addMonth()->toDateString();
        @endphp

        <!-- 2. Navigasi Periode (Google Calendar Style Top Bar) -->
        <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white border border-[#E5E7EB] shadow-2xs">
            <div class="flex items-center gap-1.5">
                <a href="{{ route('mobile.trainer.sessions.index', ['mode' => $mode ?? 'calendar', 'date' => $todayDate, 'tab' => $tab]) }}"
                   class="px-3 py-1 text-xs font-semibold rounded-full border border-[#E5E7EB] text-[#16151A] hover:border-[#FF5722] hover:text-[#FF5722] active:bg-gray-100 transition shadow-2xs">
                    Today
                </a>
                <a href="{{ route('mobile.trainer.sessions.index', ['mode' => $mode ?? 'calendar', 'date' => $prevDate, 'tab' => $tab]) }}"
                   class="w-7 h-7 flex items-center justify-center rounded-full text-[#565A66] hover:bg-gray-100 transition"
                   aria-label="Hari sebelumnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <a href="{{ route('mobile.trainer.sessions.index', ['mode' => $mode ?? 'calendar', 'date' => $nextDate, 'tab' => $tab]) }}"
                   class="w-7 h-7 flex items-center justify-center rounded-full text-[#565A66] hover:bg-gray-100 transition"
                   aria-label="Hari berikutnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="text-right">
                <span class="text-xs sm:text-sm font-bold font-poppins text-[#16151A] block">
                    {{ $currentDate->translatedFormat('F Y') }}
                </span>
                <span class="text-[10px] text-[#565A66] font-medium block">
                    {{ $currentDate->translatedFormat('l, d M Y') }}
                </span>
            </div>
        </div>

        <!-- 3. TAMPILAN KALENDER INTERAKTIF (MONTH GRID) -->
        @if (($mode ?? 'calendar') === 'calendar')
            <div class="rounded-2xl border border-[#E5E7EB] bg-white p-3 shadow-2xs space-y-2">
                <!-- Header 7 Hari -->
                <div class="grid grid-cols-7 text-center pb-2 border-b border-[#E5E7EB]">
                    @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $dayName)
                        <span class="text-[10px] font-bold text-[#565A66] uppercase">{{ $dayName }}</span>
                    @endforeach
                </div>

                <!-- 7 Columns Calendar Body Grid -->
                <div class="grid grid-cols-7 gap-1">
                    @foreach($monthGrid as $gridDate)
                        @php
                            $gridDateString = $gridDate->toDateString();
                            $isCurrentMonth = $gridDate->isSameMonth($currentDate);
                            $isToday = $gridDate->isToday();
                            $isSelected = $gridDateString === $currentDate->toDateString();
                            $daySessions = $calendarSessions->get($gridDateString) ?? collect();
                            $hasSessions = $daySessions->isNotEmpty();
                            $hasPending = $daySessions->contains('status', \App\Enums\PtSessionStatus::Pending);
                            $hasConfirmed = $daySessions->contains('status', \App\Enums\PtSessionStatus::Confirmed);
                        @endphp

                        <a href="{{ route('mobile.trainer.sessions.index', ['mode' => 'calendar', 'date' => $gridDateString]) }}"
                           class="h-10 sm:h-11 rounded-xl flex flex-col items-center justify-center p-0.5 transition cursor-pointer select-none relative
                                  {{ $isSelected ? 'bg-orange-50 ring-2 ring-[#FF5722]' : ($isCurrentMonth ? 'bg-white hover:bg-gray-50' : 'bg-gray-50/50 text-gray-300') }}">

                            @if ($isToday)
                                <span class="w-6 h-6 rounded-full bg-[#FF5722] text-white text-[11px] font-bold flex items-center justify-center shadow-xs">
                                    {{ $gridDate->day }}
                                </span>
                            @else
                                <span class="text-[11px] font-semibold {{ $isCurrentMonth ? ($isSelected ? 'text-[#FF5722] font-bold' : 'text-[#16151A]') : 'text-gray-300' }}">
                                    {{ $gridDate->day }}
                                </span>
                            @endif

                            <!-- Dot Indicator Event -->
                            @if ($hasSessions)
                                <div class="flex items-center gap-0.5 mt-0.5">
                                    @if ($hasPending)
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    @endif
                                    @if ($hasConfirmed)
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    @endif
                                    @if (!$hasPending && !$hasConfirmed)
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    @endif
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 4. Filter Status Tab Horisontal (Sesuai Gaya Tasks Desktop) -->
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1 -mx-4 px-4 sm:mx-0 sm:px-0">
            <!-- Filter Semua -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'all', 'date' => $selectedDate, 'mode' => 'tasks']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($tab === 'all' && ($mode ?? 'calendar') === 'tasks') ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Semua</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($tab === 'all' && ($mode ?? 'calendar') === 'tasks') ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['all'] ?? 0 }}</span>
            </a>

            <!-- Filter Hari Ini -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'today', 'date' => $todayDate, 'mode' => 'tasks']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($tab === 'today' && ($mode ?? 'calendar') === 'tasks') ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Hari Ini</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($tab === 'today' && ($mode ?? 'calendar') === 'tasks') ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['today'] ?? 0 }}</span>
            </a>

            <!-- Filter Menunggu -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'pending', 'date' => $selectedDate, 'mode' => 'tasks']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($tab === 'pending' && ($mode ?? 'calendar') === 'tasks') ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Menunggu</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($tab === 'pending' && ($mode ?? 'calendar') === 'tasks') ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700 font-bold' }}">{{ $counts['pending'] ?? 0 }}</span>
            </a>

            <!-- Filter Terkonfirmasi -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'confirmed', 'date' => $selectedDate, 'mode' => 'tasks']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($tab === 'confirmed' && ($mode ?? 'calendar') === 'tasks') ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Terkonfirmasi</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($tab === 'confirmed' && ($mode ?? 'calendar') === 'tasks') ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['confirmed'] ?? 0 }}</span>
            </a>

            <!-- Filter Selesai -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'completed', 'date' => $selectedDate, 'mode' => 'tasks']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($tab === 'completed' && ($mode ?? 'calendar') === 'tasks') ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Selesai</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($tab === 'completed' && ($mode ?? 'calendar') === 'tasks') ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['completed'] ?? 0 }}</span>
            </a>

            <!-- Filter Dibatalkan -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'cancelled', 'date' => $selectedDate, 'mode' => 'tasks']) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition shrink-0 whitespace-nowrap {{ ($tab === 'cancelled' && ($mode ?? 'calendar') === 'tasks') ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Dibatalkan</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($tab === 'cancelled' && ($mode ?? 'calendar') === 'tasks') ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['cancelled'] ?? 0 }}</span>
            </a>
        </div>

        <!-- 5. Header Sub-Daftar Sesi -->
        <div class="flex items-center justify-between pt-1">
            <h3 class="text-xs sm:text-sm font-bold text-[#16151A] font-poppins">
                @if (($mode ?? 'calendar') === 'calendar')
                    Sesi Latihan &bull; {{ $currentDate->translatedFormat('d F Y') }}
                @else
                    Daftar Sesi Latihan
                @endif
            </h3>
            <span class="text-[11px] font-semibold text-[#565A66]">
                {{ $sessions->total() }} Sesi
            </span>
        </div>

        <!-- 6. Daftar Kartu Sesi Latihan -->
        <div class="space-y-3">
            @forelse ($sessions as $session)
                @php
                    $isPending = $session->status === \App\Enums\PtSessionStatus::Pending;
                    $isConfirmed = $session->status === \App\Enums\PtSessionStatus::Confirmed;
                    $isDone = in_array($session->status, [\App\Enums\PtSessionStatus::Completed, \App\Enums\PtSessionStatus::Done]);
                    $isCancelled = $session->status === \App\Enums\PtSessionStatus::Cancelled;

                    $memberUser = $session->member;
                    $memberProfile = $memberUser?->memberProfile;
                    $memberCode = $memberProfile?->member_code ?? ('MEM-' . str_pad((string)($session->member_id ?? 0), 5, '0', STR_PAD_LEFT));
                    $cleanPhone = preg_replace('/[^0-9]/', '', (string) ($memberUser?->phone ?? ''));
                    if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                @endphp

                <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] space-y-3 shadow-2xs hover:border-gray-300 transition">
                    <!-- Row 1: Jam Latihan & Status Badge -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#FF5722]"></span>
                            <span class="text-xs font-bold text-[#16151A] font-poppins">
                                {{ substr($session->start_time, 0, 5) }} &ndash; {{ substr($session->end_time, 0, 5) }} WIB
                            </span>
                            <span class="text-[11px] text-[#565A66] font-medium">
                                ({{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y') }})
                            </span>
                        </div>

                        @if ($isPending)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu
                            </span>
                        @elseif ($isConfirmed)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Terkonfirmasi
                            </span>
                        @elseif ($isDone)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Selesai
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                Dibatalkan
                            </span>
                        @endif
                    </div>

                    <!-- Row 2: Informasi Member & Paket -->
                    <a href="{{ route('mobile.trainer.sessions.show', $session) }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-xl bg-orange-50 border border-orange-200 text-[#FF5722] flex items-center justify-center font-bold text-xs shrink-0 group-hover:scale-105 transition">
                            {{ strtoupper(substr($session->member->name ?? 'M', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5">
                                <h4 class="text-xs sm:text-sm font-bold text-[#16151A] truncate font-poppins group-hover:text-[#FF5722] transition">
                                    {{ $session->member->name ?? 'Member FitCore' }}
                                </h4>
                                <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-semibold bg-gray-100 text-[#565A66] border border-[#E5E7EB]">
                                    {{ $memberCode }}
                                </span>
                            </div>
                            <p class="text-[11px] text-[#565A66] truncate font-sans mt-0.5">
                                {{ $session->quota->ptPackage->name ?? ($session->memberPtQuota->ptPackage->name ?? 'Paket Sesi PT') }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#FF5722] group-hover:translate-x-0.5 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- Row 3: Action Buttons -->
                    @if ($isPending)
                        <div class="pt-2.5 border-t border-[#E5E7EB] flex items-center gap-2">
                            <button type="button"
                                    @click="openRejectModal('{{ route('mobile.trainer.sessions.reject', $session) }}', '{{ addslashes($session->member->name ?? 'Member') }}', '{{ substr($session->start_time, 0, 5) }} WIB')"
                                    class="flex-1 py-2 rounded-full border border-gray-300 text-[#565A66] hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 text-xs font-bold transition cursor-pointer text-center">
                                Tolak
                            </button>

                            <form method="POST" action="{{ route('mobile.trainer.sessions.confirm', $session) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full py-2 rounded-full bg-[#FF5722] text-white text-xs font-bold shadow-xs hover:bg-[#E64A19] active:scale-[0.98] transition cursor-pointer">
                                    Konfirmasi
                                </button>
                            </form>
                        </div>
                    @elseif ($isConfirmed && $cleanPhone)
                        <div class="pt-2 border-t border-[#E5E7EB] flex items-center justify-between">
                            <a href="{{ route('mobile.trainer.sessions.show', $session) }}"
                               class="text-xs font-bold text-[#FF5722] hover:underline">
                                Lihat Detail Sesi &rarr;
                            </a>

                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($session->member->name ?? '') }},%20mengenai%20sesi%20latihan%20kita%20pada%20{{ urlencode(\Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d M Y')) }}%20pukul%20{{ substr($session->start_time, 0, 5) }}%20WIB."
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 text-[11px] font-bold transition">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.974.554 1.769.82 2.796.82 3.182 0 5.768-2.587 5.768-5.766.001-3.182-2.585-5.766-5.768-5.766zm9.969 5.766c0 5.508-4.462 9.969-9.969 9.969-1.758 0-3.417-.463-4.871-1.272l-5.16 1.353 1.378-5.034c-.9-1.517-1.416-3.292-1.416-5.016 0-5.508 4.462-9.969 9.969-9.969 5.507 0 9.969 4.461 9.969 9.969z"/>
                                </svg>
                                <span>Hubungi</span>
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 rounded-2xl bg-white border border-dashed border-[#E5E7EB] text-center space-y-2 shadow-2xs">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 text-[#565A66] flex items-center justify-center mx-auto mb-1 border border-[#E5E7EB]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#16151A]">Tidak Ada Sesi Latihan</p>
                    <p class="text-[11px] text-[#565A66] max-w-xs mx-auto">
                        @if(($mode ?? 'calendar') === 'calendar')
                            Tidak ada jadwal sesi latihan pada tanggal {{ $currentDate->translatedFormat('d M Y') }}.
                        @else
                            Tidak ada jadwal sesi yang cocok dengan filter saat ini.
                        @endif
                    </p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if ($sessions->hasPages())
                <div class="pt-2">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>

        <!-- 7. Modal Konfirmasi Penolakan Sesi -->
        <div x-show="showRejectModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl max-w-sm w-full p-5 shadow-2xl border border-[#E5E7EB] space-y-4"
                 @click.away="showRejectModal = false">
                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto border border-rose-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <div class="text-center">
                    <h3 class="font-poppins font-extrabold text-base text-[#16151A]">Tolak Permintaan Sesi?</h3>
                    <p class="text-xs text-[#565A66] mt-1 leading-relaxed">
                        Permintaan sesi dari <strong class="text-[#16151A]" x-text="memberName"></strong> pada jam <span x-text="sessionTime"></span> akan dibatalkan dan kuota sesi akan dikembalikan ke member.
                    </p>
                </div>

                <form method="POST" :action="rejectActionUrl" class="space-y-3">
                    @csrf
                    <div>
                        <label for="modal_reason" class="block text-xs font-bold text-[#16151A] mb-1">
                            Alasan Penolakan <span class="text-[#565A66] font-normal">(Opsional)</span>
                        </label>
                        <textarea id="modal_reason" name="reason" rows="2" placeholder="Misal: Sedang berhalangan karena jadwal kompetisi..."
                                  class="w-full p-2.5 rounded-xl border border-[#E5E7EB] bg-[#F6F6F7] text-xs text-[#16151A] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722]"></textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <button type="button"
                                @click="showRejectModal = false"
                                class="flex-1 py-2.5 rounded-full border border-[#E5E7EB] text-xs font-bold text-[#565A66] hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold font-poppins transition shadow-sm">
                            Ya, Tolak Sesi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-layouts.mobile-trainer>
