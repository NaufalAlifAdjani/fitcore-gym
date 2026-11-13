{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : PERSONAL TRAINER (PT)
| MODUL        : Dashboard Harian & Permintaan Sesi (100% Visual Sync)
| VIEW         : mobile.trainer.dashboard
| LAYOUT       : layouts.mobile-trainer
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
|--------------------------------------------------------------------------
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

        <!-- 1. Header Sambutan & Status Pelatih (Sesuai Web Desktop) -->
        <header class="space-y-2">
            <div class="flex items-center justify-between">
                <!-- Floor Active Pill -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold shadow-2xs">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>PT Aktif (Floor Active)</span>
                </div>

                <!-- Date Badge -->
                <div class="text-[10px] font-semibold text-[#565A66] bg-white px-2.5 py-1 rounded-full border border-[#E5E7EB] shadow-2xs">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
                </div>
            </div>

            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#565A66]">Selamat Bertugas,</p>
                <h1 class="font-poppins font-extrabold text-2xl sm:text-3xl text-[#16151A] tracking-tight leading-tight">
                    Coach {{ $trainer->name }} 👋
                </h1>
            </div>
            <p class="font-inter text-xs text-[#565A66] leading-relaxed">
                Kelola jadwal latihan hari ini, tangani konfirmasi booking member, dan pantau estimasi komisi Anda.
            </p>
        </header>

        <!-- 2. 4 Stat Cards Ringkasan (2x2 Grid - Identik Visual Web Desktop) -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Card 1: Sesi Hari Ini -->
            <div class="rounded-2xl bg-white p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] border border-[#E5E7EB] flex flex-col justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-[#FF5722] border border-rose-100">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[9px] font-bold uppercase tracking-wider text-[#565A66]">AGENDA</span>
                        <h3 class="font-poppins text-xs font-bold text-[#16151A] leading-tight">Hari Ini</h3>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="font-poppins text-2xl font-black text-[#16151A]">
                        {{ $todaySessionsCount }}
                    </div>
                    <p class="text-[10px] text-emerald-600 font-medium mt-0.5">
                        {{ $todaySessions->whereIn('status', [\App\Enums\PtSessionStatus::Completed, \App\Enums\PtSessionStatus::Done])->count() }} sesi tuntas
                    </p>
                </div>
            </div>

            <!-- Card 2: Sesi Selesai Bulan Ini -->
            <div class="rounded-2xl bg-white p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] border border-[#E5E7EB] flex flex-col justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[9px] font-bold uppercase tracking-wider text-[#565A66]">VOLUME</span>
                        <h3 class="font-poppins text-xs font-bold text-[#16151A] leading-tight">Bulan Ini</h3>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="font-poppins text-2xl font-black text-[#16151A]">
                        {{ $completedSessions ?? 0 }}
                    </div>
                    <p class="text-[10px] text-[#565A66] font-medium mt-0.5">
                        Sesi terlaksana
                    </p>
                </div>
            </div>

            <!-- Card 3: Permintaan Pending (Butuh Respon) -->
            <div class="rounded-2xl bg-white p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] border {{ $pendingSessions->count() > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-[#E5E7EB]' }} flex flex-col justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[9px] font-bold uppercase tracking-wider text-amber-600">Butuh Respon</span>
                        <h3 class="font-poppins text-xs font-bold text-[#16151A] leading-tight">Permintaan</h3>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="font-poppins text-2xl font-black text-amber-600">
                        {{ $pendingSessions->count() }}
                    </div>
                    <p class="text-[10px] text-amber-700 font-medium mt-0.5">
                        Menunggu konfirmasi
                    </p>
                </div>
            </div>

            <!-- Card 4: Estimasi Komisi -->
            <div class="rounded-2xl bg-white p-4 shadow-[0_4px_12px_rgba(0,0,0,0.04)] border border-[#E5E7EB] flex flex-col justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[9px] font-bold uppercase tracking-wider text-blue-600">KOMISI</span>
                        <h3 class="font-poppins text-xs font-bold text-[#16151A] leading-tight">Estimasi</h3>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="font-poppins text-lg font-black text-[#FF5722] truncate">
                        Rp {{ number_format($estimatedCommission, 0, ',', '.') }}
                    </div>
                    <p class="text-[10px] text-[#565A66] font-medium mt-0.5">
                        {{ $currentMonthLabel }}
                    </p>
                </div>
            </div>
        </div>

        <!-- 3. Permintaan Booking Masuk (Actionable Cards Sesuai Web Desktop) -->
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

                    <!-- Tombol Aksi Konfirmasi / Tolak (Pill Full Radius Sesuai Desktop) -->
                    <div class="pt-2 border-t border-[#E5E7EB] flex items-center gap-2">
                        <button type="button"
                                @click="openRejectModal('{{ route('mobile.trainer.sessions.reject', $pending) }}', '{{ addslashes($pending->member->name ?? 'Member') }}', '{{ substr($pending->start_time, 0, 5) }} WIB')"
                                class="flex-1 py-2 rounded-full border border-gray-300 text-[#565A66] hover:text-rose-600 hover:border-rose-300 hover:bg-rose-50 text-xs font-bold transition cursor-pointer text-center">
                            Tolak
                        </button>

                        <form method="POST" action="{{ route('mobile.trainer.sessions.confirm', $pending) }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold shadow-md shadow-orange-500/20 active:scale-[0.98] transition cursor-pointer">
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

        <!-- 5. Peringatan Sisa Kuota Klien Menipis (Identik Web Desktop) -->
        @if (isset($lowQuotaClients) && $lowQuotaClients->count() > 0)
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-rose-700 flex items-center gap-1.5 font-poppins">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        Kuota Klien Menipis (&le; 2 Sesi)
                    </h3>
                </div>

                <div class="space-y-2.5">
                    @foreach ($lowQuotaClients as $lq)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', (string) ($lq->member->phone ?? ''));
                            if ($cleanPhone && str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                        @endphp
                        <div class="p-3.5 rounded-2xl bg-white border border-rose-200 flex items-center justify-between gap-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-[#16151A] font-poppins truncate">
                                    {{ $lq->member->name ?? 'Member FitCore' }}
                                </h4>
                                <p class="text-[10px] text-[#565A66] truncate mt-0.5">
                                    Paket: {{ $lq->ptPackage->name ?? 'Paket PT' }}
                                </p>
                                <span class="inline-block text-[10px] font-bold text-rose-600 mt-0.5">
                                    Tersisa: {{ $lq->remaining_sessions }} sesi
                                </span>
                            </div>

                            @if ($cleanPhone)
                                <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($lq->member->name) }},%20kuota%20sesi%20latihan%20PT%20Anda%20di%20FitCore%20tersisa%20{{ $lq->remaining_sessions }}%20sesi.%20Yuk%20perpanjang%20paket%20agar%20latihan%20tetap%20konsisten!"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold shrink-0 hover:bg-emerald-100 transition">
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.974.554 1.769.82 2.796.82 3.182 0 5.768-2.587 5.768-5.766.001-3.182-2.585-5.766-5.768-5.766zm9.969 5.766c0 5.508-4.462 9.969-9.969 9.969-1.758 0-3.417-.463-4.871-1.272l-5.16 1.353 1.378-5.034c-.9-1.517-1.416-3.292-1.416-5.016 0-5.508 4.462-9.969 9.969-9.969 5.507 0 9.969 4.461 9.969 9.969z"/>
                                    </svg>
                                    <span>Ingatkan</span>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 6. Modal Konfirmasi Penolakan Sesi -->
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
