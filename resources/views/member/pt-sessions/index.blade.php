<x-app-layout>
    @php
        $bookedId = session('booked_session_id') ?? request('booked');
        $bookedSession = $bookedId ? \App\Models\PtSession::with(['trainer.trainerProfile', 'quota'])->find($bookedId) : null;
        $showSuccessModal = session()->has('success') || request()->has('booked');
    @endphp

    <div class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-xs text-emerald-900">{{ session('success') }}</p>
                            <p class="text-[11px] text-emerald-700">Permintaan sesi latihan Anda sudah tersimpan dan menunggu konfirmasi dari pelatih.</p>
                        </div>
                    </div>
                    <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-800 text-sm p-1 cursor-pointer">&times;</button>
                </div>
            @endif

            <!-- Hero Section (Light Theme Card) -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 relative overflow-hidden text-[#16151A] border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                <!-- Top-Right Orange Glow Ambient -->
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-[#FF5722]/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <!-- Left: Title, Subtitle, & Quota Bar -->
                    <div class="space-y-6 max-w-xl">
                        <div>
                            <h1 class="text-3xl sm:text-5xl font-extrabold font-heading text-[#16151A] tracking-tight leading-tight">
                                Riwayat Sesi <span class="text-[#FF5722]">Personal</span><br>
                                <span class="text-[#FF5722]">Trainer</span>
                            </h1>
                            <p class="text-xs sm:text-sm text-[#565A66] mt-3 leading-relaxed">
                                Monitoring performa latihan presisi tinggi bersama pelatih profesional bersertifikasi.
                            </p>
                        </div>

                        @php
                            $hasActiveQuota = $quota && $quota->status === 'active' && ! $quota->isExpired() && $quota->remaining_sessions > 0;
                            $total = $quota ? (int) $quota->total_sessions : 0;
                            $rem = $quota ? (int) $quota->remaining_sessions : 0;
                            $pct = $total > 0 ? min(100, round(($rem / $total) * 100)) : 0;
                        @endphp

                        <!-- Sisa Kuota Bar in Hero -->
                        <div class="space-y-2 pt-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-[#565A66] font-medium">Sisa Kuota Sesi</span>
                                <div class="font-extrabold">
                                    <span class="text-[#FF5722]">{{ $rem }}</span>
                                    <span class="text-[#16151A]"> / {{ $total }} Sesi Tersedia</span>
                                </div>
                            </div>

                            <div class="w-full bg-orange-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-[#FF5722] to-[#FFB703] h-full rounded-full transition-all duration-300 shadow-xs"
                                    style="width: {{ $pct }}%"></div>
                            </div>

                            <p class="text-[11px] text-[#565A66]">
                                @if ($hasActiveQuota && $quota->end_date)
                                    Masa berlaku paket hingga
                                    {{ \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d F Y') }}
                                @elseif ($quota && $quota->remaining_sessions <= 0)
                                    Sisa kuota sesi latihan telah habis
                                @elseif ($quota && $quota->isExpired())
                                    Masa berlaku paket telah berakhir pada
                                    {{ \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d F Y') }}
                                @else
                                    Belum memiliki paket sesi PT aktif
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Right: Stats & Action Button -->
                    <div class="flex flex-col sm:items-end gap-6 shrink-0">
                        <!-- 3 Stat Boxes (Clean White Cards) -->
                        <div class="flex items-center gap-3">
                            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 text-center min-w-[90px] shadow-xs">
                                <div class="text-3xl font-extrabold font-heading text-[#16151A]">{{ $counts['pending'] }}</div>
                                <div class="text-[10px] font-extrabold text-[#565A66] uppercase tracking-wider mt-1">MENUNGGU</div>
                            </div>
                            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 text-center min-w-[90px] shadow-xs">
                                <div class="text-3xl font-extrabold font-heading text-[#16151A]">{{ $counts['confirmed'] }}</div>
                                <div class="text-[10px] font-extrabold text-[#565A66] uppercase tracking-wider mt-1">TERKONFIRMASI</div>
                            </div>
                            <div class="bg-white border border-[#E5E7EB] rounded-2xl p-4 text-center min-w-[90px] shadow-xs">
                                <div class="text-3xl font-extrabold font-heading text-[#16151A]">{{ $counts['cancelled'] }}</div>
                                <div class="text-[10px] font-extrabold text-[#565A66] uppercase tracking-wider mt-1">DIBATALKAN</div>
                            </div>
                        </div>

                        @php
                            $userTrainer = $quota?->trainer ?? auth()->user()?->getCurrentPtTrainer();
                        @endphp

                        <!-- Action Buttons -->
                        @if ($hasActiveQuota)
                            <div class="flex flex-col sm:flex-row items-center gap-2.5">
                                @if ($rem <= 3)
                                    <a href="{{ route('member.pt-packages.index') }}"
                                        class="inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-full bg-orange-50 hover:bg-orange-100 text-[#FF5722] border border-orange-200 font-bold text-xs tracking-wider transition-all cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>Tambah Kuota Sesi</span>
                                    </a>
                                @endif
                                <a href="{{ route('pt-sessions.create') }}"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-[#FF5722]/25 hover:shadow-[#FF5722]/40 transition-all cursor-pointer">
                                    <span>+</span>
                                    <span>Booking Sesi Baru</span>
                                </a>
                            </div>
                        @else
                            <div class="flex flex-col items-center sm:items-end gap-1.5">
                                <a href="{{ route('member.pt-packages.index') }}"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white font-bold text-xs tracking-wider shadow-lg shadow-[#FF5722]/25 hover:shadow-[#FF5722]/40 transition-all cursor-pointer">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span>{{ $userTrainer ? 'Perpanjang Sesi Bersama ' . $userTrainer->name : 'Beli Paket Sesi PT' }}</span>
                                </a>
                                <p class="text-[11px] text-[#565A66]">
                                    {{ $userTrainer ? 'Tambah kuota sesi untuk melanjutkan program latihan bersama ' . $userTrainer->name . '.' : 'Selesaikan pembelian paket untuk membuka akses booking jadwal latihan.' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kalendar & Jadwal Sesi -->
            <div>
                <livewire:member.sessions.index />
            </div>

        </div>
    </div>

    <!-- Pop-up Modal Booking Berhasil Diajukan -->
    @if ($showSuccessModal)
        <div x-data="{ open: true }" x-show="open" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">

            <div @click.away="open = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="max-w-md w-full bg-white border border-[#E5E7EB] rounded-3xl p-6 sm:p-8 relative text-center space-y-5 shadow-2xl overflow-hidden">

                <!-- Ambient Glow -->
                <div class="pointer-events-none absolute -top-24 left-1/2 h-48 w-48 -translate-x-1/2 rounded-full bg-orange-500/10 blur-3xl"></div>

                <!-- Close Button -->
                <button type="button" @click="open = false"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 text-zinc-500 hover:text-zinc-900 flex items-center justify-center font-bold text-sm hover:bg-gray-200 transition-colors cursor-pointer">
                    ✕
                </button>

                <!-- Icon -->
                <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200 text-amber-500 flex items-center justify-center mx-auto shadow-xl shadow-amber-500/15">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- Status Badge -->
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-50 border border-amber-200 text-amber-700">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        STATUS: MENUNGGU KONFIRMASI PT
                    </span>
                </div>

                <!-- Title & Description -->
                <div>
                    <h3 class="text-2xl font-black text-[#16151A] font-heading tracking-tight">
                        Booking Berhasil <span class="text-[#FF5722]">Diajukan!</span>
                    </h3>
                    <p class="text-xs text-[#565A66] mt-2 leading-relaxed">
                        Permintaan booking sesi privat Anda telah terkirim ke pelatih. Jadwal sesi akan berstatus Terkonfirmasi setelah pelatih menyetujui.
                    </p>
                </div>

                <!-- Booking Summary Card -->
                @if ($bookedSession)
                    <div class="p-4 rounded-2xl bg-gray-50 border border-[#E5E7EB] text-left text-xs space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[#565A66]">Pelatih:</span>
                            <span class="font-bold text-[#16151A]">{{ $bookedSession->trainer->name ?? 'Coach' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#565A66]">Tanggal Sesi:</span>
                            <span class="font-bold text-[#16151A]">{{ \Carbon\Carbon::parse($bookedSession->session_date)->locale('id')->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#565A66]">Jam Latihan:</span>
                            <span class="font-bold text-[#FF5722]">{{ substr($bookedSession->start_time, 0, 5) }} - {{ substr($bookedSession->end_time, 0, 5) }} WIB</span>
                        </div>
                    </div>
                @endif

                <!-- Action Button -->
                <button type="button" @click="open = false"
                    class="w-full py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] shadow-lg shadow-orange-500/25 text-white font-bold text-xs uppercase tracking-wider transition-colors cursor-pointer">
                    Lihat di Daftar Sesi
                </button>
            </div>
        </div>
    @endif
</x-app-layout>
