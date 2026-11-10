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

        <!-- 1. Header Judul Halaman -->
        <div class="pb-1">
            <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">
                Jadwal Sesi Klien
            </h1>
            <p class="text-[11px] text-[#565A66] font-medium font-sans">
                Pantau agenda latihan harian dan konfirmasi permintaan sesi klien Anda.
            </p>
        </div>

        <!-- 2. Filter Status Tab Horisontal (Responsif, Tidak Terpotong) -->
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1 -mx-4 px-4 sm:mx-0 sm:px-0">
            <!-- Filter Semua -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'all', 'date' => $selectedDate]) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $tab === 'all' ? 'bg-[#FF5722] text-white shadow-sm' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Semua</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['all'] ?? 0 }}</span>
            </a>

            <!-- Filter Hari Ini -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'today']) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $tab === 'today' ? 'bg-[#FF5722] text-white shadow-sm' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Hari Ini</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'today' ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['today'] ?? 0 }}</span>
            </a>

            <!-- Filter Menunggu -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'pending', 'date' => $selectedDate]) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $tab === 'pending' ? 'bg-[#FF5722] text-white shadow-sm' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Menunggu</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'pending' ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['pending'] ?? 0 }}</span>
            </a>

            <!-- Filter Terkonfirmasi -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'confirmed', 'date' => $selectedDate]) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $tab === 'confirmed' ? 'bg-[#FF5722] text-white shadow-sm' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Terkonfirmasi</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'confirmed' ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['confirmed'] ?? 0 }}</span>
            </a>

            <!-- Filter Selesai -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'completed', 'date' => $selectedDate]) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $tab === 'completed' ? 'bg-[#FF5722] text-white shadow-sm' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Selesai</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'completed' ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['completed'] ?? 0 }}</span>
            </a>

            <!-- Filter Dibatalkan -->
            <a href="{{ route('mobile.trainer.sessions.index', ['tab' => 'cancelled', 'date' => $selectedDate]) }}"
               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold transition shrink-0 whitespace-nowrap {{ $tab === 'cancelled' ? 'bg-[#FF5722] text-white shadow-sm' : 'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A]' }}">
                <span>Dibatalkan</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'cancelled' ? 'bg-white/20 text-white' : 'bg-gray-100 text-[#565A66]' }}">{{ $counts['cancelled'] ?? 0 }}</span>
            </a>
        </div>

        <!-- 3. Daftar Kartu Sesi Latihan -->
        <div class="space-y-3">
            @forelse ($sessions as $session)
                @php
                    $isPending = $session->status === \App\Enums\PtSessionStatus::Pending;
                    $isConfirmed = $session->status === \App\Enums\PtSessionStatus::Confirmed;
                    $isDone = in_array($session->status, [\App\Enums\PtSessionStatus::Completed, \App\Enums\PtSessionStatus::Done]);
                    $isCancelled = $session->status === \App\Enums\PtSessionStatus::Cancelled;
                @endphp

                <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] space-y-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold text-[#16151A]">
                                {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d M Y') }}
                            </span>
                            <span class="text-xs font-bold text-[#FF5722]">
                                • {{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB
                            </span>
                        </div>

                        @if ($isPending)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu
                            </span>
                        @elseif ($isConfirmed)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Terkonfirmasi
                            </span>
                        @elseif ($isDone)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                Selesai
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                Dibatalkan
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('mobile.trainer.sessions.show', $session) }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-xl bg-gray-100 text-[#16151A] flex items-center justify-center font-bold text-xs shrink-0 border border-[#E5E7EB] group-hover:border-[#FF5722]/40 transition">
                            {{ strtoupper(substr($session->member->name ?? 'M', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs sm:text-sm font-bold text-[#16151A] truncate font-poppins group-hover:text-[#FF5722] transition">
                                {{ $session->member->name ?? 'Member FitCore' }}
                            </h4>
                            <p class="text-[11px] text-[#565A66] truncate font-sans">
                                {{ $session->quota->ptPackage->name ?? 'Paket Sesi PT' }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-[#FF5722] group-hover:translate-x-0.5 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <!-- Aksi Cepat Konfirmasi / Tolak jika status masih Pending -->
                    @if ($isPending)
                        <div class="pt-2 border-t border-[#E5E7EB] flex items-center gap-2">
                            <button type="button"
                                    @click="openRejectModal('{{ route('mobile.trainer.sessions.reject', $session) }}', '{{ addslashes($session->member->name ?? 'Member') }}', '{{ substr($session->start_time, 0, 5) }} WIB')"
                                    class="flex-1 py-2 rounded-full border border-gray-300 text-[#565A66] hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 text-xs font-bold transition cursor-pointer text-center">
                                Tolak
                            </button>

                            <form method="POST" action="{{ route('mobile.trainer.sessions.confirm', $session) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full py-2 rounded-full bg-[#FF5722] text-white text-xs font-bold shadow-md shadow-orange-500/20 hover:bg-[#E64A19] active:scale-[0.98] transition cursor-pointer">
                                    Konfirmasi
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 rounded-2xl bg-white border border-dashed border-[#E5E7EB] text-center space-y-2 shadow-[0_4px_12px_rgba(0,0,0,0.02)]">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 text-[#565A66] flex items-center justify-center mx-auto mb-1 border border-[#E5E7EB]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#16151A]">Tidak Ada Sesi Latihan</p>
                    <p class="text-[11px] text-[#565A66] max-w-xs mx-auto">
                        Tidak ada jadwal sesi yang cocok dengan kriteria filter tab saat ini.
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

        <!-- 4. Modal Konfirmasi Penolakan Sesi -->
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

