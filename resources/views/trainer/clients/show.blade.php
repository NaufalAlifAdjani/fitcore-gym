<x-trainer-layout title="Riwayat Member: {{ $member->name }} - FitCore PT Portal">
    <div class="space-y-8">
        <!-- 1. Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-inter font-medium text-gray-500">
            <a href="{{ route('trainer.dashboard') }}" class="hover:text-gray-900 transition">Dashboard</a>
            <svg class="h-3.5 w-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <a href="{{ route('trainer.clients.index') }}" class="hover:text-gray-900 transition">Klien Saya</a>
            <svg class="h-3.5 w-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-gray-900 font-semibold">{{ $member->name }}</span>
        </nav>

        <!-- 2. Flash Alerts -->
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

        <!-- 3. Member Profile Card -->
        <div class="rounded-3xl bg-white p-6 sm:p-8 shadow-xs border border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start sm:items-center gap-4 sm:gap-6">
                @if ($member->avatar)
                    <img src="{{ $member->avatar }}" alt="{{ $member->name }}" class="h-20 w-20 rounded-2xl object-cover border-2 border-gray-100 shadow-xs">
                @else
                    <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-rose-500 to-[#FF5722] text-white font-poppins font-extrabold flex items-center justify-center text-2xl shadow-sm">
                        {{ strtoupper(substr($member->name, 0, 2)) }}
                    </div>
                @endif

                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="font-poppins text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                            {{ $member->name }}
                        </h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ $member->memberProfile?->member_code ?? ('MBR-' . str_pad((string)$member->id, 4, '0', STR_PAD_LEFT)) }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs font-inter text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            {{ $member->email }}
                        </span>
                        @if ($member->phone)
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', (string) $member->phone);
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener" class="flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 font-medium">
                                <svg class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.584 1.961.949 2.796.949 3.179 0 5.765-2.587 5.767-5.766.001-3.181-2.585-5.736-5.767-5.736zm3.374 8.204c-.144.405-.837.774-1.17.824-.311.045-.698.057-1.116-.076-.252-.08-.576-.187-.991-.366-1.752-.762-2.888-2.532-2.975-2.648-.087-.116-.71-1.026-.71-1.956 0-.93.487-1.387.661-1.579.174-.192.38-.24.507-.24.127 0 .253.002.363.007.116.006.273-.044.426.326.159.385.541 1.32.589 1.418.048.098.08.212.016.34-.064.128-.096.208-.19.319-.095.111-.2.248-.286.333-.095.095-.195.198-.084.388.111.19.493.813 1.056 1.315.726.647 1.339.847 1.53.942.19.095.302.08.414-.048.111-.128.476-.556.603-.746.127-.19.253-.159.428-.095.174.064 1.11.523 1.301.618.19.095.317.143.364.222.048.079.048.46-.096.865z"/>
                                </svg>
                                <span>{{ $member->phone }}</span>
                            </a>
                        @endif
                        @if ($member->memberProfile?->gender)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 uppercase">
                                {{ $member->memberProfile->gender }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Metrics Header -->
            <div class="flex items-center gap-4 sm:gap-6 border-t lg:border-t-0 lg:border-l border-gray-100 pt-4 lg:pt-0 lg:pl-6">
                <div class="text-center sm:text-left">
                    <span class="block text-[11px] font-bold text-gray-400 uppercase font-inter">Total Sesi</span>
                    <span class="font-poppins text-2xl font-extrabold text-gray-900">{{ $totalPurchasedSessions ?? $totalSessionsWithTrainer }}</span>
                </div>
                <div class="h-8 w-px bg-gray-100"></div>
                <div class="text-center sm:text-left">
                    <span class="block text-[11px] font-bold text-gray-400 uppercase font-inter">Selesai</span>
                    <span class="font-poppins text-2xl font-extrabold text-emerald-600">{{ $completedSessionsWithTrainer }}</span>
                </div>
                <div class="h-8 w-px bg-gray-100"></div>
                <div class="text-center sm:text-left">
                    <span class="block text-[11px] font-bold text-gray-400 uppercase font-inter">Sisa Kuota</span>
                    <span class="font-poppins text-2xl font-extrabold {{ $remainingActiveQuota > 0 ? 'text-[#FF5722]' : 'text-gray-400' }}">
                        {{ $remainingActiveQuota }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 4. Active Quota Package Information -->
        @if ($activeQuota)
            <div class="rounded-2xl bg-gradient-to-r from-rose-50 via-white to-gray-50 p-5 sm:p-6 border border-rose-100 shadow-2xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-[#FF5722] text-white">
                                PAKET AKTIF
                            </span>
                            <h3 class="font-poppins text-base font-bold text-gray-900">
                                {{ $activeQuota->ptPackage?->name ?? 'Paket Personal Trainer' }}
                            </h3>
                        </div>
                        <p class="font-inter text-xs text-gray-500 mt-1">
                            Berlaku hingga <strong class="text-gray-700">{{ $activeQuota->end_date?->translatedFormat('d F Y') }}</strong> ({{ (int) now()->diffInDays($activeQuota->end_date, false) }} hari lagi).
                        </p>
                    </div>

                    <div class="sm:text-right shrink-0">
                        <div class="font-poppins text-lg font-extrabold text-gray-900">
                            {{ $activeQuota->remaining_sessions }} <span class="text-xs font-normal text-gray-500">dari {{ $activeQuota->total_sessions }} sesi tersisa</span>
                        </div>
                        <!-- Progress bar -->
                        @php
                            $percentage = $activeQuota->total_sessions > 0 ? round(($activeQuota->used_sessions / $activeQuota->total_sessions) * 100) : 0;
                        @endphp
                        <div class="mt-1.5 w-full sm:w-48 bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-[#FF5722] h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-2xl bg-gray-50 p-4 border border-gray-100 text-xs font-inter text-gray-500 flex items-center justify-between">
                <span>Member saat ini tidak memiliki kuota paket PT aktif (seluruh kuota telah habis atau kedaluwarsa).</span>
                <span class="font-semibold text-gray-400">Kuota Habis</span>
            </div>
        @endif

        <!-- 5. Sessions History Section -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-poppins text-xl font-bold text-gray-900 tracking-tight">
                        Riwayat Sesi Latihan
                    </h2>
                    <p class="font-inter text-xs text-gray-500">
                        Histori lengkap jadwal, kehadiran, catatan latihan, dan review dari member ini.
                    </p>
                </div>

                <!-- Session Status Filters -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                    <a
                        href="{{ route('trainer.clients.show', ['member' => $member->id, 'session_status' => 'all']) }}"
                        class="px-3 py-1.5 rounded-xl text-xs font-inter font-semibold transition shrink-0 {{ $sessionStatusFilter === 'all' ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                    >
                        Semua Sesi
                    </a>
                    <a
                        href="{{ route('trainer.clients.show', ['member' => $member->id, 'session_status' => 'completed']) }}"
                        class="px-3 py-1.5 rounded-xl text-xs font-inter font-semibold transition shrink-0 {{ $sessionStatusFilter === 'completed' ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                    >
                        Selesai
                    </a>
                    <a
                        href="{{ route('trainer.clients.show', ['member' => $member->id, 'session_status' => 'confirmed']) }}"
                        class="px-3 py-1.5 rounded-xl text-xs font-inter font-semibold transition shrink-0 {{ $sessionStatusFilter === 'confirmed' ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                    >
                        Terkonfirmasi
                    </a>
                    <a
                        href="{{ route('trainer.clients.show', ['member' => $member->id, 'session_status' => 'pending']) }}"
                        class="px-3 py-1.5 rounded-xl text-xs font-inter font-semibold transition shrink-0 {{ $sessionStatusFilter === 'pending' ? 'bg-amber-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                    >
                        Menunggu
                    </a>
                    <a
                        href="{{ route('trainer.clients.show', ['member' => $member->id, 'session_status' => 'cancelled']) }}"
                        class="px-3 py-1.5 rounded-xl text-xs font-inter font-semibold transition shrink-0 {{ $sessionStatusFilter === 'cancelled' ? 'bg-rose-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
                    >
                        Dibatalkan
                    </a>
                </div>
            </div>

            <!-- Sessions List / Table -->
            @if ($sessions->isEmpty())
                <div class="rounded-2xl bg-white border border-gray-100 p-12 text-center shadow-xs">
                    <p class="font-inter text-sm text-gray-500">
                        Tidak ada catatan riwayat sesi latihan dengan status yang dipilih.
                    </p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($sessions as $session)
                        <div class="rounded-2xl bg-white p-5 shadow-xs border border-gray-100 transition hover:border-gray-200">
                            <div class="flex items-start gap-4">
                                <!-- Date Calendar Box -->
                                <div class="flex flex-col items-center justify-center h-14 w-14 rounded-2xl bg-gray-50 border border-gray-100 shrink-0 text-center">
                                    <span class="text-[10px] font-inter font-bold text-gray-400 uppercase">
                                        {{ $session->session_date->format('M') }}
                                    </span>
                                    <span class="font-poppins text-lg font-black text-gray-900 leading-tight">
                                        {{ $session->session_date->format('d') }}
                                    </span>
                                </div>

                                <!-- Session Details -->
                                <div class="space-y-1 flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-poppins text-sm font-bold text-gray-900">
                                            Sesi Private Training
                                        </h3>
                                        <!-- Status Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $session->status->badgeClasses() }}">
                                            {{ $session->status->label() }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-y-1 gap-x-3 text-xs font-inter text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                            {{ $session->time_range }}
                                        </span>
                                        <span>•</span>
                                        <span>{{ $session->session_date->translatedFormat('l, d F Y') }}</span>
                                        @if ($session->quota?->ptPackage?->name)
                                            <span>•</span>
                                            <span>{{ $session->quota->ptPackage->name }}</span>
                                        @endif
                                        @if ($session->completed_at)
                                            <span>•</span>
                                            <span class="text-emerald-600 font-medium">Diselesaikan: {{ $session->completed_at->format('d/m/Y H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Workout Notes / Evaluasi PT -->
                            @if ($session->notes)
                                <div class="mt-3.5 rounded-xl bg-gray-50/90 p-3 border border-gray-100 text-xs font-inter text-gray-700">
                                    <div class="flex items-center gap-1.5 font-bold text-gray-800 mb-1 text-[11px] uppercase tracking-wider">
                                        <svg class="h-3.5 w-3.5 text-[#FF5722]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        Catatan Latihan PT:
                                    </div>
                                    <p class="leading-relaxed whitespace-pre-line">{{ $session->notes }}</p>
                                </div>
                            @endif

                            <!-- Rating & Ulasan Member (jika ada) -->
                            @if ($session->rating)
                                <div class="mt-3 rounded-xl bg-amber-50/50 p-3 border border-amber-200/50 text-xs font-inter text-gray-800">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="flex text-amber-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="h-3.5 w-3.5 {{ $i <= $session->rating->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="font-bold text-gray-900 text-xs">{{ $session->rating->rating }}.0</span>
                                        <span class="text-gray-400">• Feedback Member</span>
                                    </div>
                                    @if ($session->rating->comment)
                                        <p class="italic text-gray-600">"{{ $session->rating->comment }}"</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-trainer-layout>

