{{--
ROLE: PERSONAL TRAINER (PT)
MODUL: Dashboard Harian & Permintaan Sesi Mobile (Fitur #9)
VIEW: mobile.trainer.dashboard
LAYOUT: layouts.mobile-trainer
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

<x-layouts.mobile-trainer title="Dashboard Pelatih - FitCore Coach" active="dashboard">
    <div class="space-y-5" x-data="{
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

        <!-- 1. Sambutan & Status Pelatih -->
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#565A66]">Selamat Bertugas,</p>
                <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">
                    Coach {{ $trainer->name }}
                </h1>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                PT Aktif
            </span>
        </div>

        <!-- 2. Metrik Statistik Horizontal Scroll (DESIGN.md Light Theme, Radius 16px) -->
        <div class="flex gap-3 overflow-x-auto pb-1 no-scrollbar -mx-4 px-4">
            <!-- Stat 1: Sesi Hari Ini -->
            <div class="min-w-[140px] p-4 rounded-2xl bg-white border border-[#E5E7EB] shrink-0 space-y-1 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Sesi Hari Ini</span>
                <span class="text-2xl font-black font-poppins text-[#16151A] block">{{ $todaySessionsCount }}</span>
                <span class="text-[10px] text-emerald-600 font-semibold block">Jadwal aktif</span>
            </div>

            <!-- Stat 2: Permintaan Pending -->
            <div class="min-w-[140px] p-4 rounded-2xl bg-white border border-amber-200 shrink-0 space-y-1 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 block">Butuh Respon</span>
                <span class="text-2xl font-black font-poppins text-amber-600 block">{{ $pendingSessions->count() }}</span>
                <span class="text-[10px] text-[#565A66] font-semibold block">Menunggu respon</span>
            </div>

            <!-- Stat 3: Total Selesai Bulan Ini -->
            <div class="min-w-[140px] p-4 rounded-2xl bg-white border border-[#E5E7EB] shrink-0 space-y-1 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Selesai Bulan Ini</span>
                <span class="text-2xl font-black font-poppins text-[#16151A] block">{{ $completedSessions ?? 0 }}</span>
                <span class="text-[10px] text-[#565A66] font-semibold block">Sesi tuntas</span>
            </div>

            <!-- Stat 4: Total Member Aktif -->
            <div class="min-w-[140px] p-4 rounded-2xl bg-white border border-[#E5E7EB] shrink-0 space-y-1 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] block">Klien Aktif</span>
                <span class="text-2xl font-black font-poppins text-[#FF5722] block">{{ $activeMembersCount }}</span>
                <span class="text-[10px] text-[#565A66] font-semibold block">Member dibimbing</span>
            </div>
        </div>

        <!-- 3. Permintaan Booking Masuk (Actionable Cards Sesuai Backend Controller) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-700 flex items-center gap-1.5 font-poppins">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Permintaan Masuk ({{ $pendingSessions->count() }})
                </h3>
            </div>

            @forelse ($pendingSessions as $pending)
                <div class="p-4 rounded-2xl bg-white border border-amber-200/80 space-y-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)] relative">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#16151A]">
                            {{ \Carbon\Carbon::parse($pending->session_date)->translatedFormat('l, d M Y') }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-[#FF5722] border border-orange-200">
                            {{ substr($pending->start_time, 0, 5) }} - {{ substr($pending->end_time, 0, 5) }} WIB
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-sm shrink-0 border border-amber-200">
                            {{ strtoupper(substr($pending->member->name ?? 'M', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs sm:text-sm font-bold text-[#16151A] truncate font-poppins">
                                {{ $pending->member->name ?? 'Member FitCore' }}
                            </h4>
                            <p class="text-[11px] text-[#565A66] truncate">
                                Paket: {{ $pending->quota->ptPackage->name ?? 'Paket Sesi PT' }}
                            </p>
                        </div>
                        <a href="{{ route('mobile.trainer.sessions.show', $pending) }}" class="text-[#565A66] hover:text-[#FF5722] p-1" title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <!-- Tombol Aksi Konfirmasi / Tolak (Pill Full Radius) -->
                    <div class="pt-2 border-t border-[#E5E7EB] flex items-center gap-2">
                        <button type="button"
                                @click="openRejectModal('{{ route('mobile.trainer.sessions.reject', $pending) }}', '{{ addslashes($pending->member->name ?? 'Member') }}', '{{ substr($pending->start_time, 0, 5) }} WIB')"
                                class="flex-1 py-2 rounded-full border border-gray-300 text-[#565A66] hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 text-xs font-bold transition cursor-pointer text-center">
                            Tolak
                        </button>

                        <form method="POST" action="{{ route('mobile.trainer.sessions.confirm', $pending) }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2 rounded-full bg-[#FF5722] text-white text-xs font-bold shadow-md shadow-orange-500/20 hover:bg-[#E64A19] active:scale-[0.98] transition cursor-pointer">
                                Konfirmasi
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-5 bg-white rounded-2xl border border-[#E5E7EB] text-center text-xs text-[#565A66] shadow-[0_4px_12px_rgba(0,0,0,0.02)] space-y-1">
                    <svg class="w-8 h-8 text-gray-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="font-bold text-[#16151A]">Tidak Ada Permintaan Baru</p>
                    <p class="text-[11px]">Semua permintaan booking dari klien telah Anda respon.</p>
                </div>
            @endforelse
        </div>

        <!-- 4. Agenda Sesi Hari Ini (Vertical List) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                    Jadwal Hari Ini ({{ \Carbon\Carbon::today()->translatedFormat('d M Y') }})
                </h3>
                <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'today']) }}" class="text-xs text-[#FF5722] font-bold hover:underline">
                    Lihat Semua
                </a>
            </div>

            @forelse ($todaySessions as $session)
                <a href="{{ route('mobile.trainer.sessions.show', $session) }}"
                   class="block p-4 rounded-2xl bg-white border border-[#E5E7EB] hover:border-[#FF5722]/40 space-y-2.5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] transition active:scale-[0.99] group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#FF5722]">
                            {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB
                        </span>
                        @if ($session->status === \App\Enums\PtSessionStatus::Confirmed)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Terkonfirmasi
                            </span>
                        @elseif ($session->status === \App\Enums\PtSessionStatus::Pending)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Menunggu
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                {{ ucfirst($session->status->value ?? 'Sesi') }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 text-[#16151A] flex items-center justify-center font-bold text-xs shrink-0 border border-[#E5E7EB] group-hover:border-[#FF5722]/40 transition">
                            {{ strtoupper(substr($session->member->name ?? 'M', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs sm:text-sm font-bold text-[#16151A] truncate font-poppins">
                                {{ $session->member->name ?? 'Member FitCore' }}
                            </h4>
                            <p class="text-[11px] text-[#565A66] truncate font-sans">
                                {{ $session->quota->ptPackage->name ?? 'Sesi Pelatihan PT' }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#FF5722] group-hover:translate-x-0.5 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>
            @empty
                <div class="p-5 bg-white rounded-2xl border border-[#E5E7EB] text-center text-xs text-[#565A66] shadow-[0_4px_12px_rgba(0,0,0,0.02)] space-y-1">
                    <p class="font-bold text-[#16151A]">Tidak Ada Jadwal Hari Ini</p>
                    <p class="text-[11px]">Anda tidak memiliki jadwal sesi latihan aktif untuk hari ini.</p>
                </div>
            @endforelse
        </div>

        <!-- 5. Modal Konfirmasi Penolakan Sesi -->
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
                        <label for="reject_reason" class="block text-xs font-bold text-[#16151A] mb-1">
                            Alasan Penolakan <span class="text-[#565A66] font-normal">(Opsional)</span>
                        </label>
                        <textarea id="reject_reason" name="reason" rows="2" placeholder="Misal: Sedang berhalangan karena jadwal kompetisi..."
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
