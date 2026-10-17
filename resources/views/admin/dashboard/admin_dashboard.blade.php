<x-app-layout>
    <section class="min-h-screen bg-[#F9F9FA] px-4 py-8 text-[#16151A] sm:px-6 lg:px-10">
        <div class="mx-auto max-w-[1600px]">
            <!-- Header Section -->
            <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#BA0030]">Portal Administrasi</p>
                    <h1 class="mt-1 font-heading text-3xl font-extrabold text-[#16151A] sm:text-[36px]">
                        Dashboard Utama
                    </h1>
                    <p class="mt-1 text-sm text-[#565A66]">
                        Ringkasan metrik performa gym, keanggotaan aktif, dan analisis pendapatan mingguan.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-xl bg-white px-3.5 py-2 text-xs font-semibold text-[#565A66] border border-[#EEEEEF] shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Sistem Berjalan Aktif
                    </span>
                    <a
                        href="{{ route('admin.payments.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#BA0030] px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-sm hover:bg-[#960027] transition"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Verifikasi Pembayaran
                    </a>
                </div>
            </header>

            @if(isset($pendingPaymentsCount) && $pendingPaymentsCount > 0)
                <div class="mb-8 flex items-center justify-between rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm text-amber-900">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-bold">Perhatian: Ada {{ $pendingPaymentsCount }} pembayaran baru menunggu verifikasi!</p>
                            <p class="text-xs text-amber-700">Tinjau bukti transfer member untuk segera mengaktifkan status membership.</p>
                        </div>
                    </div>
                    <a
                        href="{{ route('admin.payments.index') }}"
                        class="rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-amber-700 transition"
                    >
                        Buka Antrean
                    </a>
                </div>
            @endif

            <!-- 3 Stat Cards Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
                <!-- Card 1: Active Members -->
                <div class="rounded-2xl border border-[#EEEEEF] bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#565A66]">Total Member Aktif</span>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-[#BA0030]">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-extrabold text-[#16151A]">
                            {{ number_format($activeMembers, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 flex items-center gap-1.5 text-xs text-[#565A66]">
                            <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                            Membership aktif berjalan
                        </p>
                    </div>
                </div>

                <!-- Card 2: Total Revenue Month -->
                <div class="rounded-2xl border border-[#EEEEEF] bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#565A66]">Pendapatan Bulan Ini</span>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-extrabold text-[#16151A]">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 flex items-center gap-1.5 text-xs text-[#565A66]">
                            <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                            Status pembayaran terverifikasi
                        </p>
                    </div>
                </div>

                <!-- Card 3: Total Check-Ins Month -->
                <div class="rounded-2xl border border-[#EEEEEF] bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#565A66]">Check-in Bulan Ini</span>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="text-3xl font-extrabold text-[#16151A]">
                            {{ number_format($totalCheckIns, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 flex items-center gap-1.5 text-xs text-[#565A66]">
                            <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                            Kunjungan fasilitas gym
                        </p>
                    </div>
                </div>
            </div>

            <!-- Revenue Chart Section -->
            <div class="rounded-2xl border border-[#EEEEEF] bg-white p-6 shadow-sm">
                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-[#F0F0F1] pb-4">
                    <div>
                        <h2 class="font-heading text-lg font-bold text-[#16151A]">
                            Pendapatan 7 Hari Terakhir
                        </h2>
                        <p class="text-xs text-[#565A66]">
                            Grafik nominal pendapatan harian dari paket membership dan personal trainer terverifikasi.
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold text-[#565A66]">Total 7 Hari:</span>
                        <span class="ml-1 text-sm font-extrabold text-[#BA0030]">
                            Rp {{ number_format(array_sum($chartData), 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="relative w-full h-[320px]">
                    <canvas id="adminRevenueChart"></canvas>
                </div>
            </div>

            <!-- Quick Navigation Shortcuts -->
            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <a
                    href="{{ route('admin.packages.index') }}"
                    class="flex items-center gap-4 rounded-2xl border border-[#EEEEEF] bg-white p-4 shadow-sm hover:border-[#BA0030]/30 hover:shadow-md transition"
                >
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-[#BA0030]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-[#16151A]">Kelola Paket &amp; Harga</p>
                        <p class="text-xs text-[#565A66]">Atur durasi, tarif dan fasilitas</p>
                    </div>
                </a>

                <a
                    href="{{ route('admin.payments.index') }}"
                    class="flex items-center gap-4 rounded-2xl border border-[#EEEEEF] bg-white p-4 shadow-sm hover:border-[#BA0030]/30 hover:shadow-md transition"
                >
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-[#16151A]">Verifikasi Pembayaran</p>
                        <p class="text-xs text-[#565A66]">Validasi bukti transfer masuk</p>
                    </div>
                </a>

                <a
                    href="{{ route('admin.check-in.index') }}"
                    class="flex items-center gap-4 rounded-2xl border border-[#EEEEEF] bg-white p-4 shadow-sm hover:border-[#BA0030]/30 hover:shadow-md transition"
                >
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-[#16151A]">Scanner Check-in</p>
                        <p class="text-xs text-[#565A66]">Pindai barcode &amp; kehadiran</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
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
                        backgroundColor: 'rgba(186, 0, 48, 0.85)',
                        hoverBackgroundColor: 'rgba(150, 0, 39, 1)',
                        borderColor: '#BA0030',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 48,
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
                            titleFont: {
                                size: 13,
                                family: 'Inter, sans-serif',
                                weight: '600'
                            },
                            bodyFont: {
                                size: 12,
                                family: 'Inter, sans-serif'
                            },
                            padding: 12,
                            cornerRadius: 8,
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
                                    size: 11,
                                    family: 'Inter, sans-serif'
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
                                    size: 11,
                                    family: 'Inter, sans-serif'
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
