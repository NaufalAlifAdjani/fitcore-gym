<x-app-layout>
    <div x-data="{
        cancelModalOpen: false,
        targetSessionId: null,
        targetDate: '',
        targetTime: '',
        cancelUrl: ''
    }"
    @open-cancel-modal.window="
        targetSessionId = $event.detail.id;
        targetDate = $event.detail.date;
        targetTime = $event.detail.time;
        cancelUrl = '/pt-sessions/' + $event.detail.id + '/cancel';
        cancelModalOpen = true;
    "
    class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Hero Section (Dark Card matching Image 3) -->
            <div class="bg-[#141414] rounded-3xl p-8 sm:p-10 relative overflow-hidden text-white shadow-xl">
                <!-- Top-Right Red Glow Ambient -->
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-[#ED1B45]/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <!-- Left: Title, Subtitle, & Quota Bar -->
                    <div class="space-y-6 max-w-xl">
                        <div>
                            <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-white tracking-tight leading-tight">
                                Riwayat Sesi <span class="text-[#ED1B45]">Personal</span><br>
                                <span class="text-[#ED1B45]">Trainer</span>
                            </h1>
                            <p class="text-xs sm:text-sm text-[#A1A1AA] mt-3 leading-relaxed">
                                Monitoring performa latihan presisi tinggi bersama pelatih profesional bersertifikasi.
                            </p>
                        </div>

                        <!-- Sisa Kuota Bar in Hero -->
                        <div class="space-y-2 pt-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-[#A1A1AA] font-medium">Sisa Kuota Sesi</span>
                                <div class="font-extrabold">
                                    <span class="text-[#ED1B45]">{{ $quota->remaining_sessions ?? 7 }}</span>
                                    <span class="text-white"> / {{ $quota->total_sessions ?? 16 }} Sesi Tersedia</span>
                                </div>
                            </div>

                            @php
                                $total = $quota->total_sessions ?? 16;
                                $rem = $quota->remaining_sessions ?? 7;
                                $pct = $total > 0 ? round(($rem / $total) * 100) : 0;
                            @endphp
                            <div class="w-full bg-[#2A2A2A] rounded-full h-2.5 overflow-hidden">
                                <div class="bg-[#ED1B45] h-full rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
                            </div>

                            <p class="text-[11px] text-[#A1A1AA]">
                                Masa berlaku paket hingga {{ $quota && $quota->end_date ? \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d F Y') : '28 November 2025' }}
                            </p>
                        </div>
                    </div>

                    <!-- Right: Stats & Action Button -->
                    <div class="flex flex-col sm:items-end gap-6 shrink-0">
                        <!-- 3 Stat Boxes -->
                        <div class="flex items-center gap-3">
                            <div class="bg-[#1E1E1E] border border-[#2E2E2E] rounded-2xl p-4 text-center min-w-[90px]">
                                <div class="text-3xl font-extrabold font-heading text-white">{{ $counts['scheduled'] }}</div>
                                <div class="text-[10px] font-extrabold text-[#A1A1AA] uppercase tracking-wider mt-1">MENDATANG</div>
                            </div>
                            <div class="bg-[#1E1E1E] border border-[#2E2E2E] rounded-2xl p-4 text-center min-w-[90px]">
                                <div class="text-3xl font-extrabold font-heading text-white">{{ $counts['done'] }}</div>
                                <div class="text-[10px] font-extrabold text-[#A1A1AA] uppercase tracking-wider mt-1">SELESAI</div>
                            </div>
                            <div class="bg-[#1E1E1E] border border-[#2E2E2E] rounded-2xl p-4 text-center min-w-[90px]">
                                <div class="text-3xl font-extrabold font-heading text-white">{{ $counts['cancelled'] }}</div>
                                <div class="text-[10px] font-extrabold text-[#A1A1AA] uppercase tracking-wider mt-1">BATAL</div>
                            </div>
                        </div>

                        <!-- + Booking Sesi Baru Button -->
                        <a href="{{ route('pt-sessions.create') }}"
                           class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-[#ED1B45]/30 hover:shadow-[#ED1B45]/50 transition-all">
                            <span>+</span>
                            <span>Booking Sesi Baru</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabs & Month Selector Row -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Filter Tabs (Pill style) -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
                    <a href="{{ route('pt-sessions.index', ['tab' => 'all', 'month' => $selectedMonth]) }}"
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap {{ $currentTab === 'all' ? 'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' : 'text-[#565A66] hover:text-[#16151A] hover:bg-white' }}">
                        Semua
                    </a>
                    <a href="{{ route('pt-sessions.index', ['tab' => 'scheduled', 'month' => $selectedMonth]) }}"
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap {{ $currentTab === 'scheduled' ? 'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' : 'text-[#565A66] hover:text-[#16151A] hover:bg-white' }}">
                        Mendatang ({{ $counts['scheduled'] }})
                    </a>
                    <a href="{{ route('pt-sessions.index', ['tab' => 'done', 'month' => $selectedMonth]) }}"
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap {{ $currentTab === 'done' ? 'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' : 'text-[#565A66] hover:text-[#16151A] hover:bg-white' }}">
                        Selesai ({{ $counts['done'] }})
                    </a>
                    <a href="{{ route('pt-sessions.index', ['tab' => 'cancelled', 'month' => $selectedMonth]) }}"
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap {{ $currentTab === 'cancelled' ? 'bg-[#ED1B45] text-white shadow-md shadow-[#ED1B45]/25' : 'text-[#565A66] hover:text-[#16151A] hover:bg-white' }}">
                        Dibatalkan ({{ $counts['cancelled'] }})
                    </a>
                </div>

                <!-- Month Filter -->
                <form method="GET" action="{{ route('pt-sessions.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    <div class="relative">
                        <input type="month"
                               name="month"
                               value="{{ $selectedMonth }}"
                               onchange="this.form.submit()"
                               class="bg-white border border-[#E5E7EB] text-[#16151A] text-xs font-semibold rounded-full px-4 py-2 focus:ring-[#ED1B45] focus:border-[#ED1B45] shadow-xs cursor-pointer">
                    </div>
                    @if ($selectedMonth)
                        <a href="{{ route('pt-sessions.index', ['tab' => $currentTab]) }}"
                           title="Reset Filter"
                           class="text-xs text-[#565A66] hover:text-[#16151A] p-1 font-bold">
                            ✕
                        </a>
                    @endif
                </form>
            </div>

            <!-- Main Grid: Left Cards, Right Policy Panel -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Left: Sessions List (8 cols) -->
                <div class="lg:col-span-8 space-y-4">
                    @forelse ($sessions as $session)
                        <x-fitcore.session-card :session="$session" />
                    @empty
                        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-12 text-center space-y-4 shadow-sm">
                            <div class="w-16 h-16 rounded-full bg-zinc-100 text-zinc-400 flex items-center justify-center mx-auto">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-[#16151A] font-heading">Belum Ada Sesi PT</h3>
                            <p class="text-xs text-[#565A66] max-w-sm mx-auto">
                                Tidak ditemukan jadwal pada filter yang dipilih. Silakan pesan slot latihan baru bersama pelatih pribadi Anda.
                            </p>
                            <a href="{{ route('pt-sessions.create') }}"
                               class="inline-block px-6 py-2.5 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white font-bold text-xs uppercase tracking-wider transition-colors shadow-md shadow-[#ED1B45]/20">
                                Pilih Jadwal Sekarang
                            </a>
                        </div>
                    @endforelse

                    @if ($sessions->hasPages())
                        <div class="pt-4">
                            {{ $sessions->links() }}
                        </div>
                    @endif
                </div>

                <!-- Right: Panel Ketentuan Sesi (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm space-y-5 sticky top-28">
                        <div class="text-sm font-extrabold text-[#16151A] font-heading">
                            Ketentuan Sesi
                        </div>

                        <!-- Policy 1: Reschedule & Pembatalan -->
                        <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-4 text-xs space-y-1.5">
                            <div class="font-bold text-[#16151A]">
                                Reschedule & Pembatalan
                            </div>
                            <p class="text-[#565A66] leading-relaxed mt-1.5">
                                Reschedule atau pembatalan mandiri dapat dilakukan maksimal <strong class="text-[#16151A]">4 jam sebelum sesi dimulai</strong> tanpa mengurangi sisa kuota Anda.
                            </p>
                        </div>

                        <!-- Policy 2: Toleransi Keterlambatan -->
                        <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-4 text-xs space-y-1.5">
                            <div class="font-bold text-[#16151A]">
                                Toleransi Keterlambatan
                            </div>
                            <p class="text-[#565A66] leading-relaxed mt-1.5">
                                Keterlambatan lebih dari 15 menit akan mengurangi durasi waktu latihan aktif guna menjaga jadwal sesi atlet berikutnya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancel Confirmation Modal -->
            <div x-show="cancelModalOpen"
                 x-transition
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
                 style="display: none;">

                <div @click.away="cancelModalOpen = false"
                     class="max-w-md w-full bg-white border border-[#E5E7EB] rounded-3xl p-7 shadow-2xl space-y-5">

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 text-[#ED1B45] flex items-center justify-center font-bold text-lg shrink-0">
                            ✕
                        </div>
                        <div>
                            <h4 class="text-base font-extrabold text-[#16151A] font-heading">Batalkan Sesi Latihan?</h4>
                            <p class="text-xs text-[#565A66]">Konfirmasi pembatalan sesi PT</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-200 text-xs text-[#16151A] space-y-1">
                        <div class="font-bold text-sm" x-text="targetDate"></div>
                        <div class="text-[#ED1B45] font-bold" x-text="targetTime"></div>
                        <p class="text-[#565A66] pt-1">
                            Sesi ini akan dibatalkan dan <span class="text-emerald-600 font-bold">1 kuota</span> akan segera dikembalikan ke akun Anda.
                        </p>
                    </div>

                    <form :action="cancelUrl" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-xs font-semibold text-[#565A66] mb-1">Alasan Pembatalan (Opsional)</label>
                            <input type="text"
                                   name="reason"
                                   placeholder="Contoh: Ada keperluan mendadak"
                                   class="w-full bg-white border border-[#E5E7EB] rounded-xl px-3 py-2 text-xs text-[#16151A] placeholder-zinc-400 focus:ring-[#ED1B45] focus:border-[#ED1B45]">
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button"
                                    @click="cancelModalOpen = false"
                                    class="px-5 py-2.5 rounded-full bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-xs font-bold transition-colors">
                                Kembali
                            </button>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white text-xs font-bold transition-colors shadow-md shadow-[#ED1B45]/20">
                                Ya, Batalkan Sesi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
