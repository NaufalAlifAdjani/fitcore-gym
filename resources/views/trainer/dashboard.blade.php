<x-trainer-layout>
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

        @if (session('info'))
            <div class="rounded-2xl bg-blue-50 border border-blue-200/80 p-4 text-sm text-blue-800 flex items-center gap-3 shadow-2xs">
                <svg class="h-5 w-5 text-blue-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                </svg>
                <span class="font-inter font-medium">{{ session('info') }}</span>
            </div>
        @endif

        <!-- 2. Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-poppins text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Halo, {{ $trainerProfile?->user?->name ?? $trainer->name }} 👋
                </h1>
                <p class="mt-1 font-inter text-xs sm:text-sm text-gray-500 max-w-2xl leading-relaxed">
                    Kelola jadwal latihan hari ini, tangani konfirmasi booking member, dan pantau estimasi komisi Anda.
                </p>
            </div>

            <!-- Current Date Badge -->
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

        <!-- 3. Stat Cards (4 Grid Columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card 1: Sesi Hari Ini -->
            <div class="rounded-2xl bg-white p-5 shadow-xs border border-gray-100 flex flex-col justify-between transition hover:border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-[#FF5722]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            AGENDA HARIAN
                        </span>
                        <h3 class="font-poppins text-sm font-bold text-gray-900 leading-tight">
                            Sesi Hari Ini
                        </h3>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-baseline">
                        <span class="font-poppins text-3xl font-extrabold text-gray-900 tracking-tight">
                            {{ $todaySessionsCount }}
                        </span>
                        <span class="ml-1.5 font-inter text-xs font-semibold text-gray-500">
                            Sesi Terjadwal
                        </span>
                    </div>
                    <p class="mt-1 font-inter text-[11px] text-gray-400">
                        {{ $todaySessions->whereIn('status', [\App\Enums\PtSessionStatus::Completed, \App\Enums\PtSessionStatus::Done])->count() }} selesai terlaksana
                    </p>
                </div>
            </div>

            <!-- Card 2: Sesi Selesai (Bulan Ini) - Tanpa Target -->
            <div class="rounded-2xl bg-white p-5 shadow-xs border border-gray-100 flex flex-col justify-between transition hover:border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            VOLUME LATIHAN
                        </span>
                        <h3 class="font-poppins text-sm font-bold text-gray-900 leading-tight">
                            Total Sesi Selesai
                        </h3>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-baseline">
                        <span class="font-poppins text-3xl font-extrabold text-gray-900 tracking-tight">
                            {{ $completedSessions }}
                        </span>
                        <span class="ml-1.5 font-inter text-xs font-semibold text-gray-500">
                            Sesi Selesai
                        </span>
                    </div>
                    <p class="mt-1 font-inter text-[11px] text-gray-400">
                        Bulan {{ $currentMonthLabel }}
                    </p>
                </div>
            </div>

            <!-- Card 3: Estimasi Komisi (Bulan Ini) -->
            <div class="rounded-2xl bg-white p-5 shadow-xs border border-gray-100 flex flex-col justify-between transition hover:border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2" />
                            <path d="M6 12h.01M18 12h.01" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            PENDAPATAN KOMISI
                        </span>
                        <h3 class="font-poppins text-sm font-bold text-gray-900 leading-tight">
                            Estimasi Komisi
                        </h3>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-baseline">
                        <span class="font-poppins text-2xl font-extrabold text-gray-900 tracking-tight">
                            Rp {{ number_format($estimatedCommission, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="mt-1 font-inter text-[11px] text-gray-400">
                        Dari {{ $completedSessions }} sesi selesai bulan ini
                    </p>
                </div>
            </div>

            <!-- Card 4: Total Member Aktif - Tanpa Target -->
            <div class="rounded-2xl bg-white p-5 shadow-xs border border-gray-100 flex flex-col justify-between transition hover:border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            MANAJEMEN KLIEN
                        </span>
                        <h3 class="font-poppins text-sm font-bold text-gray-900 leading-tight">
                            Total Member Aktif
                        </h3>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-baseline">
                        <span class="font-poppins text-3xl font-extrabold text-gray-900 tracking-tight">
                            {{ $activeMembersCount }}
                        </span>
                        <span class="ml-1.5 font-inter text-xs font-semibold text-gray-500">
                            Member
                        </span>
                    </div>
                    <p class="mt-1 font-inter text-[11px] text-gray-400">
                        Klien bimbingan aktif FitCore
                    </p>
                </div>
            </div>
        </div>

        <!-- 4. Main Operational Sections (2-Column Grid) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left Column: Jadwal Sesi Hari Ini (lg:col-span-7) -->
            <div class="lg:col-span-7 space-y-4">
                <div class="rounded-2xl bg-white p-6 shadow-xs border border-gray-100">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div>
                            <h2 class="font-poppins text-base font-bold text-gray-900">
                                Jadwal Sesi Hari Ini
                            </h2>
                            <p class="font-inter text-xs text-gray-500 mt-0.5">
                                Daftar sesi bimbingan latihan yang terjadwal untuk hari ini
                            </p>
                        </div>
                        <a href="{{ route('trainer.sessions.index', ['d' => \Carbon\Carbon::today()->toDateString(), 'v' => 'day']) }}" 
                           class="inline-flex items-center gap-1 font-inter text-xs font-semibold text-[#FF5722] hover:text-[#c41639] transition">
                            <span>Buka Kalender</span>
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <!-- Sessions List -->
                    <div class="mt-4 space-y-3">
                        @forelse ($todaySessions as $session)
                            @php
                                $member = $session->member;
                                $memberName = $member?->name ?? 'Member FitCore';
                                $memberCode = $member?->memberProfile?->member_code ?? ('MC-' . str_pad((string) $session->member_id, 4, '0', STR_PAD_LEFT));
                                $packageName = ($session->quota ?? $session->memberPtQuota)?->ptPackage?->name ?? 'Paket PT';
                                
                                $parts = explode(' ', trim($memberName));
                                $initials = count($parts) >= 2
                                    ? strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1))
                                    : strtoupper(substr($memberName, 0, 2));
                                
                                $startTimeFormatted = substr($session->start_time, 0, 5);
                                $endTimeFormatted = substr($session->end_time, 0, 5);
                            @endphp

                            <div class="flex items-center justify-between p-3.5 rounded-xl border border-gray-100 hover:border-gray-200 transition bg-gray-50/40">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <!-- Time Pill -->
                                    <div class="shrink-0 text-center px-2.5 py-1.5 rounded-lg bg-white border border-gray-200/70 shadow-2xs">
                                        <span class="block font-poppins font-bold text-xs text-gray-900 leading-tight">
                                            {{ $startTimeFormatted }}
                                        </span>
                                        <span class="block font-inter text-[10px] text-gray-400">
                                            {{ $endTimeFormatted }}
                                        </span>
                                    </div>

                                    <!-- Member Info -->
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-rose-50 text-[#FF5722] font-inter text-xs font-bold">
                                            {{ $initials }}
                                        </div>
                                        <div class="truncate">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-poppins font-bold text-xs sm:text-sm text-gray-900 truncate">
                                                    {{ $memberName }}
                                                </h4>
                                                <span class="font-inter text-[11px] text-gray-400 shrink-0">
                                                    #{{ $memberCode }}
                                                </span>
                                            </div>
                                            <p class="font-inter text-[11px] text-gray-500 truncate mt-0.5">
                                                {{ $packageName }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Badge -->
                                <div class="shrink-0 ml-3">
                                    @php
                                        $badgeClass = match ($session->status?->value ?? (string)$session->status) {
                                            'completed', 'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'confirmed', 'scheduled' => 'bg-emerald-50/80 text-emerald-600 border-emerald-100',
                                            'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            default => 'bg-gray-100 text-gray-600 border-gray-200',
                                        };
                                        $statusLabel = match ($session->status?->value ?? (string)$session->status) {
                                            'completed', 'done' => 'Selesai',
                                            'in_progress' => 'Berlangsung',
                                            'confirmed', 'scheduled' => 'Terkonfirmasi',
                                            'pending' => 'Menunggu',
                                            default => ucfirst((string)$session->status),
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-gray-400 mb-3">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                </div>
                                <h3 class="font-poppins font-bold text-sm text-gray-800">
                                    Tidak Ada Sesi Hari Ini
                                </h3>
                                <p class="font-inter text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                                    Belum ada sesi latihan yang terjadwal untuk hari ini. Anda dapat meninjau ketersediaan waktu atau persiapan program latihan.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Permintaan Booking & Peringatan Kuota (lg:col-span-5) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- 1. Permintaan Booking Baru (Pending Approvals) -->
                <div class="rounded-2xl bg-white p-6 shadow-xs border border-gray-100">
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <h2 class="font-poppins text-base font-bold text-gray-900">
                                Permintaan Booking
                            </h2>
                            @if ($pendingSessions->count() > 0)
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FF5722] text-white">
                                    {{ $pendingSessions->count() }} Baru
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($pendingSessions as $pending)
                            @php
                                $pMember = $pending->member;
                                $pMemberName = $pMember?->name ?? 'Member FitCore';
                                $pPackageName = ($pending->quota ?? $pending->memberPtQuota)?->ptPackage?->name ?? 'Paket PT';
                                $pDateFormatted = \Carbon\Carbon::parse($pending->session_date)->translatedFormat('d M Y');
                                $pTimeFormatted = substr($pending->start_time, 0, 5) . ' - ' . substr($pending->end_time, 0, 5);
                            @endphp

                            <div class="p-3.5 rounded-xl border border-amber-100 bg-amber-50/30 space-y-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h4 class="font-poppins font-bold text-xs sm:text-sm text-gray-900">
                                            {{ $pMemberName }}
                                        </h4>
                                        <p class="font-inter text-[11px] text-gray-500 mt-0.5">
                                            {{ $pPackageName }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800">
                                        Menunggu
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 text-xs font-inter text-gray-600 bg-white/70 px-2.5 py-1.5 rounded-lg border border-amber-100/60">
                                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    <span>{{ $pDateFormatted }} ({{ $pTimeFormatted }})</span>
                                </div>

                                <!-- Action Buttons: Terima / Tolak -->
                                <div class="flex items-center gap-2 pt-1">
                                    <form action="{{ route('trainer.sessions.confirm', $pending) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center py-1.5 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-inter text-xs font-semibold shadow-2xs transition cursor-pointer">
                                            Terima
                                        </button>
                                    </form>

                                    <form action="{{ route('trainer.sessions.reject', $pending) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menolak sesi ini? Kuota akan dikembalikan ke member.')">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center py-1.5 px-3 rounded-lg bg-white hover:bg-gray-100 text-gray-700 border border-gray-200 font-inter text-xs font-semibold transition cursor-pointer">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-gray-400">
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-500 mb-2">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <p class="font-inter text-xs text-gray-500">
                                    Tidak ada permintaan booking yang menunggu konfirmasi.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 2. Peringatan Sisa Kuota Klien (Low Quota Alert) -->
                <div class="rounded-2xl bg-white p-6 shadow-xs border border-gray-100">
                    <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                        <div>
                            <h2 class="font-poppins text-base font-bold text-gray-900">
                                Peringatan Sisa Kuota
                            </h2>
                            <p class="font-inter text-xs text-gray-500 mt-0.5">
                                Klien dengan sisa kuota &le; 2 sesi latihan
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($lowQuotaClients as $quota)
                            @php
                                $qMember = $quota->member;
                                $qMemberName = $qMember?->name ?? 'Member FitCore';
                                $qMemberCode = $qMember?->memberProfile?->member_code ?? ('MC-' . str_pad((string) $quota->member_id, 4, '0', STR_PAD_LEFT));
                                $qPackageName = $quota->ptPackage?->name ?? 'Paket PT';
                                $percentUsed = $quota->total_sessions > 0 ? round(($quota->used_sessions / $quota->total_sessions) * 100) : 100;
                            @endphp

                            <div class="p-3.5 rounded-xl border border-gray-100 bg-gray-50/50 space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h4 class="font-poppins font-bold text-xs sm:text-sm text-gray-900">
                                            {{ $qMemberName }}
                                        </h4>
                                        <span class="font-inter text-[11px] text-gray-400">
                                            #{{ $qMemberCode }} • {{ $qPackageName }}
                                        </span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $quota->remaining_sessions <= 1 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                        Sisa {{ $quota->remaining_sessions }} Sesi
                                    </span>
                                </div>

                                <!-- Progress Bar -->
                                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-[#FF5722] h-1.5 rounded-full" style="width: {{ $percentUsed }}%"></div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] font-inter text-gray-400">
                                    <span>Terpakai: {{ $quota->used_sessions }} sesi</span>
                                    <span>Total: {{ $quota->total_sessions }} sesi</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-gray-400">
                                <p class="font-inter text-xs text-gray-500">
                                    Semua klien bimbingan memiliki sisa kuota yang mencukupi.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-trainer-layout>
