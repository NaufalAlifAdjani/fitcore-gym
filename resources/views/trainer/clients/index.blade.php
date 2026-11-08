<x-trainer-layout title="Klien Saya - FitCore PT Portal">
    <div class="space-y-8">
        <!-- 1. Flash Alerts -->
        @if (session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 text-sm text-emerald-800 flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-inter font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl bg-rose-50 border border-rose-200/80 p-4 text-sm text-rose-800 flex items-center gap-3 shadow-2xs">
                <svg class="h-5 w-5 text-rose-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <span class="font-inter font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- 2. Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-poppins text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Klien Bimbingan Saya
                </h1>
                <p class="mt-1 font-inter text-xs sm:text-sm text-gray-500 max-w-2xl leading-relaxed">
                    Kelola seluruh member yang pernah atau sedang aktif berlatih bersama Anda. Pantau sisa kuota dan akses riwayat setiap sesi latihan.
                </p>
            </div>

            <div class="shrink-0">
                <div class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 font-inter text-xs font-semibold text-gray-700 shadow-xs border border-gray-100">
                    <svg class="h-4 w-4 text-[#FF5722]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- 3. Stat Cards (3 Columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Total Klien -->
            <div class="rounded-2xl bg-white p-5 shadow-xs border border-gray-100 flex flex-col justify-between transition hover:border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-[#FF5722]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            TOTAL PORTOFOLIO
                        </span>
                        <h3 class="font-poppins text-sm font-bold text-gray-900 leading-tight">
                            Total Klien
                        </h3>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="font-poppins text-3xl font-extrabold text-gray-900 tracking-tight">
                        {{ $totalClientsCount }}
                    </span>
                    <span class="font-inter text-xs text-gray-500 ml-1">member</span>
                </div>
            </div>

            <!-- Perlu Perpanjangan / Kuota Menipis -->
            <a
                href="{{ route('trainer.clients.index', array_merge(request()->query(), ['status' => 'low_quota'])) }}"
                class="rounded-2xl bg-white p-5 shadow-xs border border-gray-100 flex flex-col justify-between transition hover:border-amber-200 hover:shadow-xs group"
                title="Filter klien yang sisa kuotanya menipis (≤ 2 sesi) atau habis"
            >
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-100 transition">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            RETENSI &amp; PERPANJANGAN
                        </span>
                        <h3 class="font-poppins text-sm font-bold text-gray-900 leading-tight group-hover:text-amber-600 transition">
                            Perlu Perpanjangan
                        </h3>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div>
                        <span class="font-poppins text-3xl font-extrabold {{ $lowQuotaClientsCount > 0 ? 'text-amber-600' : 'text-gray-900' }} tracking-tight">
                            {{ $lowQuotaClientsCount }}
                        </span>
                        <span class="font-inter text-xs text-gray-500 ml-1">member</span>
                    </div>
                    <span class="font-inter text-[11px] font-semibold text-amber-600 group-hover:underline">
                        Sisa &le; 2 / Habis &rarr;
                    </span>
                </div>
            </a>

            <!-- Sesi Selesai Bulan Ini -->
            <div class="rounded-2xl bg-white p-5 shadow-xs border border-gray-100 flex flex-col justify-between transition hover:border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            PRODUKTIVITAS BULANAN
                        </span>
                        <h3 class="font-poppins text-sm font-bold text-gray-900 leading-tight">
                            Sesi Selesai Bulan Ini
                        </h3>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="font-poppins text-3xl font-extrabold text-gray-900 tracking-tight">
                        {{ $completedSessionsMonth }}
                    </span>
                    <span class="font-inter text-xs text-gray-500 ml-1">sesi terlaksana</span>
                </div>
            </div>
        </div>

        <!-- 4. Filter & Search Toolbar -->
        <div class="rounded-2xl bg-white p-4 sm:p-5 shadow-xs border border-gray-100">
            <form method="GET" action="{{ route('trainer.clients.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama, email, nomor HP, atau ID member..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm font-inter text-gray-900 placeholder-gray-400 focus:outline-hidden focus:ring-2 focus:ring-[#FF5722]/20 focus:border-[#FF5722] transition"
                    >
                </div>

                <!-- Status Tabs / Buttons -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
                    <a
                        href="{{ route('trainer.clients.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                        class="px-3.5 py-2 rounded-xl text-xs font-inter font-semibold transition shrink-0 {{ $statusFilter === 'all' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                    >
                        Semua Klien
                    </a>
                    <a
                        href="{{ route('trainer.clients.index', array_merge(request()->query(), ['status' => 'active_quota'])) }}"
                        class="px-3.5 py-2 rounded-xl text-xs font-inter font-semibold transition shrink-0 {{ $statusFilter === 'active_quota' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                    >
                        Kuota Aktif
                    </a>
                    <a
                        href="{{ route('trainer.clients.index', array_merge(request()->query(), ['status' => 'low_quota'])) }}"
                        class="px-3.5 py-2 rounded-xl text-xs font-inter font-semibold transition shrink-0 {{ $statusFilter === 'low_quota' ? 'bg-amber-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                    >
                        Perlu Perpanjangan
                    </a>
                    <a
                        href="{{ route('trainer.clients.index', array_merge(request()->query(), ['status' => 'empty_quota'])) }}"
                        class="px-3.5 py-2 rounded-xl text-xs font-inter font-semibold transition shrink-0 {{ $statusFilter === 'empty_quota' ? 'bg-gray-800 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                    >
                        Kuota Habis
                    </a>

                    @if ($search !== '' || $statusFilter !== 'all')
                        <a
                            href="{{ route('trainer.clients.index') }}"
                            class="px-3 py-2 rounded-xl text-xs font-inter font-medium text-gray-400 hover:text-gray-700 hover:bg-gray-50 transition shrink-0"
                            title="Reset filter"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 5. Client Cards Grid -->
        @if ($clients->isEmpty())
            <div class="rounded-2xl bg-white border border-gray-100 p-12 text-center shadow-xs">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-rose-50 text-[#FF5722] mb-4">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="font-poppins text-lg font-bold text-gray-900 mb-1">
                    Tidak Ada Klien Ditemukan
                </h3>
                <p class="font-inter text-xs sm:text-sm text-gray-500 max-w-md mx-auto">
                    @if ($search !== '' || $statusFilter !== 'all')
                        Tidak ada data klien yang cocok dengan filter atau kata kunci pencarian Anda.
                    @else
                        Belum ada member yang melakukan booking sesi latihan bersama Anda.
                    @endif
                </p>
                @if ($search !== '' || $statusFilter !== 'all')
                    <div class="mt-4">
                        <a href="{{ route('trainer.clients.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gray-100 text-xs font-semibold text-gray-700 hover:bg-gray-200 transition">
                            Reset Pencarian
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($clients as $client)
                    @php
                        $activeQuotas = $client->ptQuotas->filter(function ($q) {
                            return $q->status === 'active' && $q->end_date >= \Carbon\Carbon::today();
                        });
                        $remainingQuotaSum = (int) $activeQuotas->sum('remaining_sessions');
                        $totalPurchasedSessions = (int) ($activeQuotas->isNotEmpty()
                            ? $activeQuotas->sum('total_sessions')
                            : $client->ptQuotas->sum('total_sessions'));
                        $completedSessionsTogether = $client->memberSessions->whereIn('status', [\App\Enums\PtSessionStatus::Completed, \App\Enums\PtSessionStatus::Done])->count();
                        $lastSession = $client->memberSessions->first();
                        
                        // Clean phone number for WhatsApp
                        $rawPhone = $client->phone;
                        $cleanPhone = preg_replace('/[^0-9]/', '', (string) $rawPhone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    @endphp

                    <div class="rounded-2xl bg-white p-5 shadow-xs border border-gray-100 hover:border-gray-200 hover:shadow-sm transition flex flex-col justify-between">
                        <div>
                            <!-- Header Member Card -->
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3">
                                    @if ($client->avatar)
                                        <img src="{{ $client->avatar }}" alt="{{ $client->name }}" class="h-12 w-12 rounded-xl object-cover border border-gray-100">
                                    @else
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 text-gray-700 font-poppins font-bold flex items-center justify-center text-base border border-gray-100">
                                            {{ strtoupper(substr($client->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-poppins text-sm font-bold text-gray-900 leading-snug">
                                            {{ $client->name }}
                                        </h3>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="font-inter text-[11px] font-semibold text-gray-400">
                                                ID: {{ $client->memberProfile?->member_code ?? ('MBR-' . str_pad((string)$client->id, 4, '0', STR_PAD_LEFT)) }}
                                            </span>
                                            @if ($client->memberProfile?->gender)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-600 uppercase">
                                                    {{ $client->memberProfile->gender }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Kuota Badge -->
                                <div>
                                    @if ($remainingQuotaSum > 2)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Sisa {{ $remainingQuotaSum }} Sesi
                                        </span>
                                    @elseif ($remainingQuotaSum > 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Sisa {{ $remainingQuotaSum }} Sesi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-200/60">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Kuota Habis
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Contact info -->
                            <div class="space-y-1.5 py-3 border-y border-gray-50 text-xs font-inter text-gray-600">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Email</span>
                                    <span class="font-medium text-gray-800 truncate max-w-[180px]">{{ $client->email }}</span>
                                </div>
                                @if ($client->phone)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-400">No. HP / WA</span>
                                        <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener" class="font-medium text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1">
                                            <span>{{ $client->phone }}</span>
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.584 1.961.949 2.796.949 3.179 0 5.765-2.587 5.767-5.766.001-3.181-2.585-5.736-5.767-5.736zm3.374 8.204c-.144.405-.837.774-1.17.824-.311.045-.698.057-1.116-.076-.252-.08-.576-.187-.991-.366-1.752-.762-2.888-2.532-2.975-2.648-.087-.116-.71-1.026-.71-1.956 0-.93.487-1.387.661-1.579.174-.192.38-.24.507-.24.127 0 .253.002.363.007.116.006.273-.044.426.326.159.385.541 1.32.589 1.418.048.098.08.212.016.34-.064.128-.096.208-.19.319-.095.111-.2.248-.286.333-.095.095-.195.198-.084.388.111.19.493.813 1.056 1.315.726.647 1.339.847 1.53.942.19.095.302.08.414-.048.111-.128.476-.556.603-.746.127-.19.253-.159.428-.095.174.064 1.11.523 1.301.618.19.095.317.143.364.222.048.079.048.46-.096.865z"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Session summary stats -->
                            <div class="grid grid-cols-2 gap-2 my-3 text-center">
                                <div class="rounded-xl bg-gray-50/80 p-2.5">
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase font-inter">Sesi Selesai</span>
                                    <span class="font-poppins text-sm font-extrabold text-emerald-600">{{ $completedSessionsTogether }}</span>
                                </div>
                                <div class="rounded-xl bg-gray-50/80 p-2.5">
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase font-inter">Total Sesi</span>
                                    <span class="font-poppins text-sm font-extrabold text-gray-900">{{ $totalPurchasedSessions }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-2">
                            <a
                                href="{{ route('trainer.clients.show', $client->id) }}"
                                class="w-full flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 font-inter text-xs font-semibold text-white shadow-xs hover:bg-[#FF5722] transition"
                            >
                                <span>Lihat Riwayat Sesi</span>
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 6. Pagination -->
            <div class="mt-8">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</x-trainer-layout>

