<x-trainer-layout>
    <div class="space-y-8">
        <!-- 1. Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs font-medium text-[#565A66] mb-1">
                    <a href="{{ route('trainer.dashboard') }}" class="hover:text-[#FF5722] transition">Portal Trainer</a>
                    <span>/</span>
                    <span class="text-[#16151A] font-semibold">Pendapatan & Komisi</span>
                </nav>
                <h1 class="font-poppins text-2xl sm:text-3xl font-extrabold text-[#16151A] tracking-tight">
                    Pendapatan &amp; Komisi Sesi Latihan
                </h1>
                <p class="mt-1 font-inter text-xs sm:text-sm text-[#565A66] max-w-2xl leading-relaxed">
                    Pantau rincian komisi yang Anda peroleh dari setiap sesi private training yang telah diselesaikan.
                </p>
            </div>

            <!-- Badge Shift & Tarif Saat Ini -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Shift: {{ $trainer->shift_label }} ({{ $trainer->shift_hours_text }})</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Tarif: Rp {{ number_format($currentRate, 0, ',', '.') }} / Sesi</span>
                </span>
            </div>
        </div>

        <!-- 2. Stat Cards Ringkasan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Komisi Bulan Ini -->
            <div class="rounded-2xl bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-[#565A66] uppercase tracking-wider font-inter">Komisi Bulan Ini</p>
                    <h3 class="text-2xl font-extrabold font-poppins text-[#10B981] mt-1">Rp {{ number_format($monthEarnings, 0, ',', '.') }}</h3>
                    <p class="text-[11px] font-medium text-[#565A66] mt-1">{{ \Carbon\Carbon::parse($selectedMonth.'-01')->translatedFormat('F Y') }}</p>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center text-[#16A34A] shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Card 2: Sesi Selesai Bulan Ini -->
            <div class="rounded-2xl bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-[#565A66] uppercase tracking-wider font-inter">Sesi Selesai Bulan Ini</p>
                    <h3 class="text-2xl font-extrabold font-poppins text-[#16151A] mt-1">{{ number_format($monthCount) }}</h3>
                    <p class="text-[11px] font-medium text-[#3B82F6] mt-1">Sesi latihan completed</p>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-[#DBEAFE] flex items-center justify-center text-[#2563EB] shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Card 3: Akumulasi Sepanjang Waktu -->
            <div class="rounded-2xl bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-[#565A66] uppercase tracking-wider font-inter">Total Seluruh Waktu</p>
                    <h3 class="text-2xl font-extrabold font-poppins text-[#FF5722] mt-1">Rp {{ number_format($allTimeEarnings, 0, ',', '.') }}</h3>
                    <p class="text-[11px] font-medium text-[#565A66] mt-1">Dari {{ $allTimeCount }} total sesi</p>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-[#FF5722]/10 flex items-center justify-center text-[#FF5722] shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>

            <!-- Card 4: Tarif Komisi Anda -->
            <div class="rounded-2xl bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-[#565A66] uppercase tracking-wider font-inter">Tarif Komisi per Sesi</p>
                    <h3 class="text-2xl font-extrabold font-poppins text-[#16151A] mt-1">Rp {{ number_format($currentRate, 0, ',', '.') }}</h3>
                    <p class="text-[11px] font-medium text-[#10B981] mt-1">Ditetapkan oleh manajemen</p>
                </div>
                <div class="h-12 w-12 rounded-2xl bg-[#F3F4F6] flex items-center justify-center text-[#374151] shrink-0">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 3. Filter Bar -->
        <div class="rounded-2xl bg-white p-5 border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('trainer.commissions.index') }}" class="flex items-center gap-3 w-full sm:w-auto">
                <label class="text-xs font-bold text-[#565A66] uppercase tracking-wider">Pilih Bulan:</label>
                <input 
                    type="month" 
                    name="month" 
                    value="{{ $selectedMonth }}" 
                    onchange="this.form.submit()"
                    class="text-xs font-inter rounded-full border border-[#E5E7EB] bg-[#F6F6F7] px-4 py-2 text-[#16151A] focus:border-[#FF5722] focus:bg-white focus:outline-none transition cursor-pointer"
                />
            </form>

            <div class="text-xs text-[#565A66] font-medium">
                Menampilkan riwayat komisi sesi untuk <strong>{{ \Carbon\Carbon::parse($selectedMonth.'-01')->translatedFormat('F Y') }}</strong>
            </div>
        </div>

        <!-- 4. Tabel Riwayat Sesi Selesai & Komisi -->
        <div class="rounded-2xl bg-white border border-[#E5E7EB] shadow-[0px_1px_3px_rgba(0,0,0,0.05)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#E5E7EB] bg-[#FAFAFA] text-[11px] font-bold text-[#565A66] uppercase tracking-wider font-inter">
                            <th class="py-4 px-6">Tanggal &amp; Waktu</th>
                            <th class="py-4 px-6">Member Klien</th>
                            <th class="py-4 px-6">Paket Latihan</th>
                            <th class="py-4 px-6">Catatan Evaluasi</th>
                            <th class="py-4 px-6">Komisi Didapatkan</th>
                            <th class="py-4 px-6 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F1F2F4] text-xs font-inter text-[#16151A]">
                        @forelse ($monthlySessions as $session)
                            @php
                                $earnings = $session->trainer_earnings ?: $currentRate;
                            @endphp
                            <tr class="hover:bg-[#F9FAFB] transition duration-150">
                                <!-- Tanggal & Waktu -->
                                <td class="py-4 px-6">
                                    <div class="font-bold text-[#16151A]">
                                        {{ $session->session_date->format('d M Y') }}
                                    </div>
                                    <div class="text-[11px] text-[#565A66] flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-[#9CA3AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ substr($session->start_time, 0, 5) }} - {{ substr($session->end_time, 0, 5) }} WIB</span>
                                    </div>
                                </td>

                                <!-- Member Klien -->
                                <td class="py-4 px-6">
                                    <div class="font-bold text-[#16151A]">{{ $session->member?->name ?? 'Member FitCore' }}</div>
                                    <div class="text-[11px] text-[#6B7280]">{{ $session->member?->memberProfile?->member_code ?? $session->member?->email }}</div>
                                </td>

                                <!-- Paket Latihan -->
                                <td class="py-4 px-6">
                                    <span class="inline-block px-2.5 py-1 rounded-full bg-[#F3F4F6] text-[#374151] font-semibold text-[11px]">
                                        {{ $session->session_info }}
                                    </span>
                                </td>

                                <!-- Catatan Evaluasi -->
                                <td class="py-4 px-6 max-w-xs">
                                    @if ($session->notes)
                                        <p class="text-[11px] text-[#374151] truncate" title="{{ $session->notes }}">
                                            {{ $session->notes }}
                                        </p>
                                    @else
                                        <span class="text-[#9CA3AF] italic text-[11px]">Tidak ada catatan</span>
                                    @endif
                                </td>

                                <!-- Komisi Didapatkan -->
                                <td class="py-4 px-6">
                                    <div class="font-extrabold text-sm text-[#10B981]">
                                        Rp {{ number_format($earnings, 0, ',', '.') }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-6 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#DCFCE7] text-[#16A34A] border border-[#BBF7D0] text-[11px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                        Selesai
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-[#9CA3AF]">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-[#D1D5DB] mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-sm font-semibold text-[#565A66]">Belum ada sesi latihan yang diselesaikan pada bulan ini.</p>
                                        <p class="text-xs text-[#9CA3AF] mt-0.5">Sesi yang telah ditandai selesai akan otomatis tercatat komisinya di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($monthlySessions->hasPages())
                <div class="p-4 border-t border-[#E5E7EB]">
                    {{ $monthlySessions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-trainer-layout>

