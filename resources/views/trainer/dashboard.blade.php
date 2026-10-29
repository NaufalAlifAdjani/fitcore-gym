<x-trainer-layout>
    <div class="space-y-8">
        <!-- 1. Page Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="font-poppins text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Performa Saya
                </h1>
                <p class="mt-1 font-inter text-xs sm:text-sm text-gray-500 max-w-2xl leading-relaxed">
                    Evaluasi pencapaian sesi latihan, kepuasan member, dan analisis performa mingguan secara menyeluruh.
                </p>
            </div>

            <!-- Month Filter Dropdown / Pill -->
            <div class="shrink-0">
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-full bg-gray-100/90 px-4 py-2 font-inter text-xs font-semibold text-gray-700 transition hover:bg-gray-200 cursor-pointer"
                >
                    <span>{{ $currentMonthLabel }}</span>
                    <svg class="h-3.5 w-3.5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- 2. Metric Stat Cards (3 Column Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Card 1: Volume Latihan -->
            <div class="rounded-2xl bg-white p-6 shadow-xs border border-gray-100 flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 3 21 3 21 9" />
                            <polyline points="9 21 3 21 3 15" />
                            <line x1="21" y1="3" x2="14" y2="10" />
                            <line x1="3" y1="21" x2="10" y2="14" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            VOLUME LATIHAN
                        </span>
                        <h3 class="font-poppins text-base font-bold text-gray-900 leading-tight">
                            Total Sesi
                        </h3>
                    </div>
                </div>

                <div class="mt-5 flex items-baseline">
                    <span class="font-poppins text-4xl font-extrabold text-gray-900 tracking-tight">
                        {{ $completedSessions }}
                    </span>
                    <span class="ml-1.5 font-inter text-base font-bold text-gray-500">
                        / {{ $sessionTarget }} Sesi
                    </span>
                </div>
            </div>

            <!-- Card 2: Kepuasan Klien -->
            <div class="rounded-2xl bg-white p-6 shadow-xs border border-gray-100 flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-500">
                        <svg class="h-5 w-5 fill-amber-400/20" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-inter text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            KEPUASAN KLIEN
                        </span>
                        <h3 class="font-poppins text-base font-bold text-gray-900 leading-tight">
                            Rating Rata-rata
                        </h3>
                    </div>
                </div>

                <div class="mt-5 flex items-center gap-3">
                    <span class="font-poppins text-4xl font-extrabold text-gray-900 tracking-tight">
                        {{ $avgRating }}
                    </span>
                    <div>
                        <!-- 5 Gold Stars -->
                        <div class="flex items-center text-amber-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 fill-amber-400" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="block font-inter text-[11px] font-medium text-gray-400 mt-0.5">
                            5.0 Skala Maksimum
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Manajemen Klien -->
            <div class="rounded-2xl bg-white p-6 shadow-xs border border-gray-100 flex flex-col justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-500">
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
                        <h3 class="font-poppins text-base font-bold text-gray-900 leading-tight">
                            Total Member Aktif
                        </h3>
                    </div>
                </div>

                <div class="mt-5 flex items-baseline">
                    <span class="font-poppins text-4xl font-extrabold text-gray-900 tracking-tight">
                        {{ $activeMembersCount }}
                    </span>
                    <span class="ml-1.5 font-inter text-base font-bold text-gray-500">
                        / {{ $memberTarget }} Member
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. Section: Ulasan & Feedback Member Terbaru -->
        <div class="space-y-4">
            <!-- Section Header & Filter Pills -->
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between pt-4">
                <div>
                    <h2 class="font-poppins text-lg font-bold text-gray-900">
                        Ulasan & Feedback Member Terbaru
                    </h2>
                    <p class="font-inter text-xs text-gray-500 mt-0.5">
                        Ulasan terverifikasi dari member yang telah menyelesaikan sesi latihan
                    </p>
                </div>

                <!-- Star Rating Filter Tabs -->
                <div class="flex items-center gap-1.5">
                    <!-- Semua -->
                    <a
                        href="{{ route('trainer.dashboard') }}"
                        class="rounded-full px-4 py-1.5 font-inter text-xs font-semibold transition {{ empty($selectedStar) ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
                    >
                        Semua ({{ $totalReviewsCount }})
                    </a>

                    <!-- 5 Stars -->
                    <a
                        href="{{ route('trainer.dashboard', ['star' => 5]) }}"
                        class="rounded-full px-3.5 py-1.5 font-inter text-xs font-semibold transition {{ $selectedStar === '5' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
                    >
                        ★ 5 ({{ $fiveStarCount }})
                    </a>

                    <!-- 4 Stars -->
                    <a
                        href="{{ route('trainer.dashboard', ['star' => 4]) }}"
                        class="rounded-full px-3.5 py-1.5 font-inter text-xs font-semibold transition {{ $selectedStar === '4' ? 'bg-[#ED1B45] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
                    >
                        ★ 4 ({{ $fourStarCount }})
                    </a>
                </div>
            </div>

            <!-- Review Cards List -->
            <div class="space-y-3.5">
                @forelse ($reviews as $review)
                    @php
                        $memberName = $review->member?->name ?? 'Member FitCore';
                        $memberCode = $review->member?->memberProfile?->member_code ?? ('MC-' . str_pad((string) $review->member_id, 4, '0', STR_PAD_LEFT));
                        $packageName = ($review->ptSession?->quota ?? $review->ptSession?->memberPtQuota)?->ptPackage?->name ?? 'Paket PT 10 Sesi';
                        
                        // Get initials
                        $parts = explode(' ', trim($memberName));
                        $initials = count($parts) >= 2
                            ? strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1))
                            : strtoupper(substr($memberName, 0, 2));
                    @endphp

                    <div class="rounded-2xl bg-white p-5 shadow-2xs border border-gray-100/90 transition duration-150 hover:shadow-xs">
                        <div class="flex items-start justify-between gap-4">
                            <!-- Left: Avatar + Details + Comment -->
                            <div class="flex items-start gap-4">
                                <!-- Avatar Initial Circle -->
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gray-100 font-inter text-xs font-bold text-gray-700 tracking-wider">
                                    {{ $initials }}
                                </div>

                                <!-- Member Info & Comment -->
                                <div class="space-y-1.5">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                        <span class="font-poppins font-bold text-sm text-gray-900">
                                            {{ $memberName }}
                                        </span>
                                        <span class="font-inter text-xs text-gray-400">
                                            #{{ $memberCode }}
                                        </span>
                                        <span class="text-gray-300">•</span>
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 font-inter text-[11px] font-medium text-gray-700">
                                            {{ $packageName }}
                                        </span>
                                        <span class="text-gray-300">•</span>
                                        <span class="font-inter text-xs text-gray-400">
                                            {{ $review->created_at->translatedFormat('d M Y') }}
                                        </span>
                                    </div>

                                    <!-- Feedback Comment -->
                                    <p class="font-inter text-xs sm:text-sm text-gray-600 leading-relaxed">
                                        "{{ $review->comment ?: 'Pelayanan dan bimbingan sangat memuaskan, teknik latihan terarah dan hasil maksimal.' }}"
                                    </p>
                                </div>
                            </div>

                            <!-- Right: Gold Stars -->
                            <div class="flex items-center text-amber-400 shrink-0">
                                @for ($s = 1; $s <= 5; $s++)
                                    <svg class="h-4 w-4 {{ $s <= $review->rating ? 'fill-amber-400' : 'fill-gray-200 text-gray-200' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-white p-8 text-center text-gray-400 border border-gray-100">
                        <p class="font-inter text-sm">Tidak ada ulasan ditemukan untuk kategori ini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Footer: View All Link -->
            @if ($totalReviewsCount > 0)
                <div class="flex justify-end pt-3">
                    <a
                        href="{{ route('trainer.dashboard') }}"
                        class="inline-flex items-center gap-1.5 font-inter text-xs sm:text-sm font-semibold text-gray-800 hover:text-[#ED1B45] transition duration-150"
                    >
                        <span>Lihat Semua {{ $totalReviewsCount }} Ulasan</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-trainer-layout>
