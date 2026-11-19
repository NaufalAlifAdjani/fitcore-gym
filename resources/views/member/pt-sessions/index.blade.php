<x-app-layout>
    <div class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

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

                        <!-- + Booking Sesi Baru Button -->
                        @if ($hasActiveQuota)
                            <a href="{{ route('pt-sessions.create') }}"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-[#FF5722]/25 hover:shadow-[#FF5722]/40 transition-all cursor-pointer">
                                <span>+</span>
                                <span>Booking Sesi Baru</span>
                            </a>
                        @else
                            <div class="flex flex-col items-center sm:items-end gap-1.5">
                                <button type="button" disabled
                                    title="Anda belum memiliki kuota sesi PT aktif untuk melakukan booking"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-gray-200 text-gray-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed border border-gray-300 shadow-none select-none">
                                    <span>+</span>
                                    <span>Booking Sesi Baru</span>
                                </button>
                                <a href="{{ route('member.pt-packages.index') }}"
                                    class="text-[11px] font-semibold text-[#FF5722] hover:text-[#E64A19] hover:underline flex items-center gap-1">
                                    <span>Beli Paket Sesi PT</span>
                                    <span>&rarr;</span>
                                </a>
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
</x-app-layout>
