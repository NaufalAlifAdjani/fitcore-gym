<x-app-layout>
    <section class="min-h-screen bg-[#F6F6F7] px-4 py-8 text-[#16151A] sm:px-6 lg:px-10">
        <div class="mx-auto max-w-[1600px] space-y-8">
            <!-- Header & Title Section with Date Filter -->
            <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold font-poppins text-[#16151A] tracking-tight">
                        Dashboard Performa Gym
                    </h1>
                    <p class="mt-1 font-inter text-sm text-[#565A66]">
                        Ringkasan metrik operasional gym, pertumbuhan anggota, dan pendapatan real-time FitCore Sudirman Central.
                    </p>
                </div>

                <!-- Date Filter Component -->
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-2 bg-white p-2.5 sm:p-3 rounded-2xl border border-[#E5E7EB] shadow-sm">
                    <div class="flex items-center gap-1.5 pl-1 pr-1">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#FF5722]/10 text-[#FF5722] shrink-0">
                            <svg class="w-4 h-4 text-[#FF5722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <select
                            name="period"
                            id="dashboard-period"
                            onchange="updateDashboardDateInputs(this.value)"
                            class="rounded-xl border-[#E5E7EB] text-xs font-semibold text-[#16151A] bg-[#F6F6F7] focus:border-[#FF5722] focus:ring-[#FF5722] py-1.5 px-2.5 transition cursor-pointer"
                        >
                            <option value="this_month" @selected(($filterPeriod ?? 'this_month') === 'this_month')>Bulan Ini</option>
                            <option value="today" @selected(($filterPeriod ?? '') === 'today')>Hari Ini</option>
                            <option value="7_days" @selected(($filterPeriod ?? '') === '7_days')>7 Hari Terakhir</option>
                            <option value="30_days" @selected(($filterPeriod ?? '') === '30_days')>30 Hari Terakhir</option>
                            <option value="this_year" @selected(($filterPeriod ?? '') === 'this_year')>Tahun Ini</option>
                            <option value="custom" @selected(($filterPeriod ?? '') === 'custom')>Kustom Tanggal</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-1 bg-[#F6F6F7] border border-[#E5E7EB] rounded-xl px-2 py-1">
                        <input
                            type="date"
                            name="start_date"
                            id="filter-start-date"
                            value="{{ $filterStartDate ?? '' }}"
                            onchange="document.getElementById('dashboard-period').value = 'custom'"
                            class="bg-transparent border-0 p-0.5 text-xs font-medium text-[#16151A] focus:ring-0"
                            aria-label="Tanggal Mulai"
                        >
                        <span class="text-xs text-[#565A66] font-medium px-0.5">–</span>
                        <input
                            type="date"
                            name="end_date"
                            id="filter-end-date"
                            value="{{ $filterEndDate ?? '' }}"
                            onchange="document.getElementById('dashboard-period').value = 'custom'"
                            class="bg-transparent border-0 p-0.5 text-xs font-medium text-[#16151A] focus:ring-0"
                            aria-label="Tanggal Selesai"
                        >
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold rounded-xl transition shadow-sm font-inter cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>

                    @if (request()->hasAny(['start_date', 'end_date', 'period']))
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center px-2.5 py-1.5 bg-[#F6F6F7] hover:bg-gray-200 text-[#565A66] text-xs font-medium rounded-xl transition font-inter"
                            title="Reset Filter"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </header>

            <!-- Indikator Rentang Filter Aktif -->
            <div class="flex items-center justify-between text-xs text-[#565A66] font-inter">
                <span class="flex items-center gap-1.5">
                    <span class="inline-block h-2 w-2 rounded-full bg-[#FF5722]"></span>
                    Menampilkan data performa periode: <strong class="text-[#16151A]">{{ $filterRangeLabel ?? 'Bulan Ini' }}</strong>
                </span>
            </div>

            <!-- Banner Notifikasi Pembayaran Menunggu Verifikasi -->
            @if (($pendingVerificationsCount ?? $pendingPaymentsCount ?? 0) > 0)
                <div class="bg-[#FEF3C7] border border-[#FDE68A] rounded-[16px] p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FDE68A] text-[#D97706]">
                            <svg class="h-6 w-6 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-poppins font-bold text-sm text-[#92400E]">
                                Perhatian: Ada {{ $pendingVerificationsCount ?? $pendingPaymentsCount }} pembayaran baru menunggu verifikasi!
                            </p>
                            <p class="font-inter text-xs text-[#B45309]">
                                Tinjau bukti transfer member untuk segera mengaktifkan status membership.
                            </p>
                        </div>
                    </div>
                    <a
                        href="{{ route('admin.payments.index') }}"
                        class="inline-flex items-center justify-center shrink-0 bg-[#F59E0B] hover:bg-[#D97706] text-white font-inter text-sm font-bold px-4 py-2 rounded-full transition shadow-sm"
                    >
                        Tinjau Pembayaran
                    </a>
                </div>
            @endif

            <!-- 1. Stat Cards Section (4 Columns on Large Screen) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Card 1: TOTAL MEMBER AKTIF -->
                <x-stat-card
                    title="TOTAL MEMBER AKTIF"
                    :value="$totalActiveMembers ?? '0'"
                    trend_text="Member dengan status aktif"
                    trend_color="bg-[#10B981]"
                >
                    <x-slot name="icon">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#FF5722]/10 text-[#FF5722]">
                            <svg class="h-6 w-6 text-[#FF5722]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                    </x-slot>
                </x-stat-card>

                <!-- Card 2: PENDAPATAN MEMBERSHIP -->
                <x-stat-card
                    title="PENDAPATAN MEMBERSHIP"
                    :value="$formattedMembershipRevenue ?? 'Rp 0'"
                    trend_text="Paket langganan membership"
                    trend_color="bg-[#10B981]"
                >
                    <x-slot name="icon">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#10B981]/10 text-[#10B981]">
                            <svg class="h-6 w-6 text-[#10B981]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </x-slot>
                </x-stat-card>

                <!-- Card 3: PENDAPATAN SESI PT -->
                <x-stat-card
                    title="PENDAPATAN SESI PT"
                    :value="$formattedPtRevenue ?? 'Rp 0'"
                    trend_text="Paket sesi Personal Trainer"
                    trend_color="bg-[#8B5CF6]"
                >
                    <x-slot name="icon">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#8B5CF6]/10 text-[#8B5CF6]">
                            <svg class="h-6 w-6 text-[#8B5CF6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h1.5a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 012.25 13.5v-3A1.5 1.5 0 013.75 9zm15 0h1.5A1.5 1.5 0 0121.75 10.5v3a1.5 1.5 0 01-1.5 1.5h-1.5A1.5 1.5 0 0117.25 13.5v-3a1.5 1.5 0 011.5-1.5zM6.75 12h10.5M5.25 7.5v9M18.75 7.5v9" />
                            </svg>
                        </span>
                    </x-slot>
                </x-stat-card>

                <!-- Card 4: TOTAL CHECKIN -->
                <x-stat-card
                    title="TOTAL CHECKIN"
                    :value="$formattedTotalCheckIns ?? '0'"
                    trend_text="Presensi akses gym 24 jam"
                    trend_color="bg-[#3B82F6]"
                >
                    <x-slot name="icon">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#3B82F6]/10 text-[#3B82F6]">
                            <svg class="h-6 w-6 text-[#3B82F6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7V5a2 2 0 012-2h2m10 0h2a2 2 0 012 2v2m0 10v2a2 2 0 01-2 2h-2m-10 0H5a2 2 0 01-2-2v-2M7 12h10" />
                            </svg>
                        </span>
                    </x-slot>
                </x-stat-card>
            </div>

            <!-- 2. Grafik Pendapatan (Chart.js) -->
            <x-card class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <h2 class="font-poppins font-bold text-lg text-[#16151A]">
                            {{ $chartTitle }}
                        </h2>
                        <p class="font-inter text-xs text-[#565A66]">
                            Grafik nominal pendapatan dari paket membership dan personal trainer terverifikasi.
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#F6F6F7] border border-[#E5E7EB] text-xs font-medium text-[#565A66] w-fit">
                        <span class="h-2 w-2 rounded-full bg-[#FF5722]"></span>
                        <span>Periode: <strong class="text-[#16151A]">{{ $filterRangeLabel ?? 'Bulan Ini' }}</strong></span>
                    </div>
                </div>

                <div class="relative w-full h-[300px]">
                    <canvas id="adminRevenueChart"></canvas>
                </div>
            </x-card>

            <!-- 3. Antrean Menunggu Verifikasi (Tabel Responsif) -->
            <x-card class="overflow-hidden">
                <x-slot name="header">
                    <div class="p-6 border-b border-[#E5E7EB] flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between w-full">
                        <div>
                            <h2 class="font-poppins font-bold text-xl text-[#16151A]">
                                Antrean Menunggu Verifikasi
                            </h2>
                            <p class="mt-1 font-inter text-sm text-[#565A66]">
                                Daftar transaksi pembayaran transfer yang membutuhkan verifikasi admin sebelum paket member diaktifkan.
                            </p>
                        </div>
                        <a
                            href="{{ route('admin.payments.index') }}"
                            class="inline-flex items-center gap-1.5 self-start sm:self-auto rounded-full border border-[#E5E7EB] bg-white px-4 py-2 font-inter text-xs font-semibold text-[#565A66] shadow-sm transition hover:border-[#FF5722] hover:text-[#FF5722]"
                        >
                            <span>Lihat Semua Pembayaran</span>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </x-slot>

                <!-- Tabel Antrean Pembayaran Responsif -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[960px] border-collapse text-left">
                        <thead class="bg-[#F6F6F7]/50 border-b border-[#E5E7EB] text-[11px] font-bold text-[#565A66] uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-4">INVOICE ID</th>
                                <th scope="col" class="px-5 py-4">PEMESAN</th>
                                <th scope="col" class="px-5 py-4">PAKET</th>
                                <th scope="col" class="px-5 py-4">NOMINAL</th>
                                <th scope="col" class="px-5 py-4">BANK</th>
                                <th scope="col" class="px-5 py-4">TANGGAL UPLOAD</th>
                                <th scope="col" class="px-5 py-4">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB]">
                            @forelse ($pendingPayments as $payment)
                                @php
                                    $packageName = $payment->package_type === 'membership'
                                        ? ($payment->membershipPackage?->name ?? 'Paket Membership')
                                        : ($payment->ptPackage?->name ?? 'Paket Sesi PT');
                                    $packageType = $payment->package_type === 'membership' ? 'MEMBERSHIP' : 'SESI PT';
                                    $bankSender = $payment->bank_sender ?? $payment->bank?->name ?? 'Bank Pengirim';
                                    $bankDestination = $payment->bank_destination ?: 'Mandiri/Gym';
                                    $transferDate = ($payment->transfer_date ?? $payment->created_at)?->format('d M Y, H:i') ?? '-';
                                    $hasProof = (bool) ($payment->proof_image_path || $payment->proof_image_url || $payment->proof_image);
                                    $proofUrl = $payment->proof_image_url ?? ($payment->proof_image_path ? Storage::url($payment->proof_image_path) : null);
                                @endphp
                                <tr class="transition duration-150 hover:bg-[#F6F6F7]/60">
                                    {{-- INVOICE ID --}}
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="font-inter font-bold text-sm text-[#16151A]">{{ $payment->invoice_id }}</span>
                                    </td>

                                    {{-- PEMESAN --}}
                                    <td class="px-5 py-4">
                                        <p class="font-bold text-sm text-[#16151A]">{{ $payment->member?->name ?? 'Member FitCore' }}</p>
                                        <p class="text-xs text-[#565A66]">{{ $payment->member?->email ?? '-' }}</p>
                                    </td>

                                    {{-- PAKET --}}
                                    <td class="px-5 py-4">
                                        <p class="font-bold text-sm text-[#16151A]">{{ $packageName }}</p>
                                        <p class="uppercase text-xs text-[#565A66]">{{ $packageType }}</p>
                                    </td>

                                    {{-- NOMINAL --}}
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="font-poppins font-bold text-sm text-[#16151A]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    </td>

                                    {{-- BANK --}}
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="font-bold text-sm text-[#16151A]">{{ $bankSender }}</p>
                                        <p class="text-xs text-[#565A66]">ke {{ $bankDestination }}</p>
                                    </td>

                                    {{-- TANGGAL UPLOAD --}}
                                    <td class="whitespace-nowrap px-5 py-4 font-inter">
                                        <div class="text-xs text-[#16151A]">{{ $transferDate }}</div>
                                        @if ($hasProof && $proofUrl)
                                            <div x-data="{ openProof: false }" class="mt-1">
                                                <button
                                                    type="button"
                                                    x-on:click="openProof = true"
                                                    class="inline-flex items-center gap-1 text-xs font-semibold text-[#FF5722] hover:underline cursor-pointer"
                                                    title="Lihat Bukti Transfer"
                                                >
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                        <circle cx="12" cy="12" r="3" />
                                                    </svg>
                                                    <span>Bukti</span>
                                                </button>

                                                <!-- Lightbox Modal -->
                                                <div
                                                    x-cloak
                                                    x-show="openProof"
                                                    x-transition.opacity
                                                    x-on:click.self="openProof = false"
                                                    x-on:keydown.escape.window="openProof = false"
                                                    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4 backdrop-blur-sm whitespace-normal"
                                                    role="dialog"
                                                    aria-modal="true"
                                                >
                                                    <div class="relative max-w-lg w-full rounded-[16px] bg-white p-5 shadow-2xl text-left" x-on:click.stop>
                                                        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3 mb-4">
                                                            <div>
                                                                <p class="font-inter text-[10px] font-bold uppercase tracking-wider text-[#FF5722]">Bukti Transfer Member</p>
                                                                <h3 class="font-poppins text-sm font-bold text-[#16151A]">{{ $payment->invoice_id }} - {{ $payment->member?->name }}</h3>
                                                            </div>
                                                            <button type="button" x-on:click="openProof = false" class="rounded-full p-1.5 text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A]" aria-label="Tutup">
                                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                                            </button>
                                                        </div>
                                                        <div class="max-h-[60vh] overflow-auto rounded-xl bg-[#F6F6F7] p-2 flex items-center justify-center">
                                                            <img src="{{ $proofUrl }}" 
                                                                 alt="Bukti transfer {{ $payment->invoice_id }}"
                                                                 class="max-h-[55vh] w-auto rounded-lg object-contain">
                                                        </div>
                                                        <div class="mt-4 flex items-center justify-between text-xs font-inter text-[#565A66]">
                                                            <span>Nominal: <strong class="text-[#FF5722]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></span>
                                                            <button type="button" x-on:click="openProof = false" class="rounded-full bg-[#F6F6F7] px-4 py-1.5 text-xs font-semibold text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] transition">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#565A66]/50 mt-1">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                                <span>Bukti</span>
                                            </span>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <x-badge type="warning">PENDING</x-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#F6F6F7] text-[#565A66] mb-3">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="font-inter font-bold text-sm text-[#16151A]">Tidak Ada Antrean Verifikasi</p>
                                        <p class="mt-1 font-inter text-xs text-[#565A66]">Semua pembayaran telah berhasil diverifikasi atau belum ada transaksi pending.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <x-slot name="footer">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between w-full">
                        <p class="font-inter text-sm text-[#565A66]">
                            @if ($pendingPayments->total() > 0)
                                Menampilkan {{ $pendingPayments->firstItem() }} - {{ $pendingPayments->lastItem() }} dari {{ $pendingPayments->total() }} pembayaran
                            @else
                                Menampilkan 0 pembayaran
                            @endif
                        </p>

                        <div>
                            {{ $pendingPayments->links() }}
                        </div>
                    </div>
                </x-slot>
            </x-card>
        </div>
    </section>

    <!-- Chart.js CDN & Inisialisasi Dinamis -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function updateDashboardDateInputs(period) {
            const startInput = document.getElementById('filter-start-date');
            const endInput = document.getElementById('filter-end-date');
            if (!startInput || !endInput) return;

            const today = new Date();
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            if (period === 'today') {
                const str = formatDate(today);
                startInput.value = str;
                endInput.value = str;
            } else if (period === '7_days') {
                const past = new Date();
                past.setDate(today.getDate() - 6);
                startInput.value = formatDate(past);
                endInput.value = formatDate(today);
            } else if (period === '30_days') {
                const past = new Date();
                past.setDate(today.getDate() - 29);
                startInput.value = formatDate(past);
                endInput.value = formatDate(today);
            } else if (period === 'this_month') {
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                startInput.value = formatDate(firstDay);
                endInput.value = formatDate(lastDay);
            } else if (period === 'this_year') {
                const firstDay = new Date(today.getFullYear(), 0, 1);
                const lastDay = new Date(today.getFullYear(), 11, 31);
                startInput.value = formatDate(firstDay);
                endInput.value = formatDate(lastDay);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const chartCanvas = document.getElementById('adminRevenueChart');
            if (!chartCanvas) return;

            const labels = @js($chartLabels);
            const data = @js($chartData);

            const ctx = chartCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (IDR)',
                        data: data,
                        backgroundColor: 'rgba(255, 87, 34, 0.85)',
                        hoverBackgroundColor: '#FF5722',
                        borderColor: '#FF5722',
                        borderWidth: 1,
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 44,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#16151A',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                family: 'Poppins, sans-serif',
                                size: 13,
                                weight: '600'
                            },
                            bodyFont: {
                                family: 'Inter, sans-serif',
                                size: 12
                            },
                            callbacks: {
                                label: function (context) {
                                    const value = context.raw || 0;
                                    return ' ' + new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                        minimumFractionDigits: 0
                                    }).format(value);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 11
                                },
                                color: '#565A66'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F0F0F1'
                            },
                            ticks: {
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 11
                                },
                                color: '#565A66',
                                callback: function (value) {
                                    if (value >= 1000000) {
                                        return (value / 1000000).toFixed(1) + ' jt';
                                    } else if (value >= 1000) {
                                        return (value / 1000).toFixed(0) + ' rb';
                                    }
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
