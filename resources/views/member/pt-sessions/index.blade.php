<x-app-layout>
    <div class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Hero Section (Light Athletic Palette) -->
            <div class="bg-gradient-to-br from-white via-orange-50/50 to-amber-50/30 rounded-3xl p-8 sm:p-10 relative overflow-hidden text-[#16151A] border border-orange-100 shadow-[0_10px_30px_rgba(255,87,34,0.08)]">
                <!-- Top-Right Orange Glow Ambient -->
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-[#FF5722]/15 rounded-full blur-3xl pointer-events-none"></div>

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

                        <!-- Sisa Kuota Bar in Hero -->
                        <div class="space-y-2 pt-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-[#565A66] font-medium">Sisa Kuota Sesi</span>
                                <div class="font-extrabold">
                                    <span class="text-[#FF5722]">{{ $quota->remaining_sessions ?? 7 }}</span>
                                    <span class="text-[#16151A]"> / {{ $quota->total_sessions ?? 16 }} Sesi Tersedia</span>
                                </div>
                            </div>

                            @php
                                $total = $quota->total_sessions ?? 16;
                                $rem = $quota->remaining_sessions ?? 7;
                                $pct = $total > 0 ? round(($rem / $total) * 100) : 0;
                            @endphp
                            <div class="w-full bg-orange-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-[#FF5722] to-[#FFB703] h-full rounded-full transition-all duration-300 shadow-xs"
                                    style="width: {{ $pct }}%"></div>
                            </div>

                            <p class="text-[11px] text-[#565A66]">
                                Masa berlaku paket hingga
                                {{ $quota && $quota->end_date ? \Carbon\Carbon::parse($quota->end_date)->translatedFormat('d F Y') : '28 November 2025' }}
                            </p>
                        </div>
                    </div>

                    <!-- Right: Stats & Action Button -->
                    <div class="flex flex-col sm:items-end gap-6 shrink-0">
                        <!-- 3 Stat Boxes (Clean White Cards) -->
                        <div class="flex items-center gap-3">
                            <div class="bg-white border border-orange-100 rounded-2xl p-4 text-center min-w-[90px] shadow-xs">
                                <div class="text-3xl font-extrabold font-heading text-[#16151A]">{{ $counts['pending'] }}</div>
                                <div class="text-[10px] font-extrabold text-[#565A66] uppercase tracking-wider mt-1">MENUNGGU</div>
                            </div>
                            <div class="bg-white border border-orange-100 rounded-2xl p-4 text-center min-w-[90px] shadow-xs">
                                <div class="text-3xl font-extrabold font-heading text-[#16151A]">{{ $counts['confirmed'] }}</div>
                                <div class="text-[10px] font-extrabold text-[#565A66] uppercase tracking-wider mt-1">TERKONFIRMASI</div>
                            </div>
                            <div class="bg-white border border-orange-100 rounded-2xl p-4 text-center min-w-[90px] shadow-xs">
                                <div class="text-3xl font-extrabold font-heading text-[#16151A]">{{ $counts['cancelled'] }}</div>
                                <div class="text-[10px] font-extrabold text-[#565A66] uppercase tracking-wider mt-1">DIBATALKAN</div>
                            </div>
                        </div>

                        <!-- + Booking Sesi Baru Button -->
                        <a href="{{ route('pt-sessions.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-[#FF5722]/30 hover:shadow-[#FF5722]/50 transition-all">
                            <span>+</span>
                            <span>Booking Sesi Baru</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kalendar & Jadwal Sesi (Sama dengan Tampilan Dashboard Trainer) -->
            <div>
                <livewire:member.sessions.index />
            </div>

        </div>
    </div>
</x-app-layout>