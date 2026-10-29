<x-app-layout>
    <div
        x-data="membershipPackages({
            hasErrors: @js($errors->any()),
            oldForm: @js([
                'id' => old('package_id'),
                'name' => old('name', ''),
                'badge' => old('badge', ''),
                'tier' => old('tier', 'Basic'),
                'description' => old('description', ''),
                'price' => old('price', ''),
                'promo_price' => old('promo_price', ''),
                'duration_value' => old('duration_value', 12),
                'duration_unit' => old('duration_unit', 'Bulan'),
                'duration_in_days' => old('duration_in_days', 365),
                'facilities' => old('facilities', []),
                'pt_sessions' => old('pt_sessions', 0),
                'is_active' => (bool) old('is_active'),
            ]),
            packageBaseUrl: @js(route('admin.packages.index')),
            storeUrl: @js(route('admin.packages.store')),
        })"
        x-on:keydown.escape.window="showModal = false"
        class="min-h-screen bg-[#F6F6F7] px-4 py-8 text-[#16151A] sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-[1600px] space-y-8">
            <!-- 2. Header & Area Pencarian -->
            <header class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold font-poppins text-[#16151A] tracking-tight">
                        Paket Membership &amp; Pengaturan Harga
                    </h1>
                    <p class="font-inter text-sm text-[#565A66] mt-1 max-w-3xl">
                        Kelola jenis keanggotaan gym, tarif harga, durasi langganan, dan rincian fasilitas member secara terpusat.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <form method="GET" action="{{ route('admin.packages.index') }}" class="flex flex-col gap-3 sm:flex-row">
                        <input type="hidden" name="tier" value="{{ request('tier') }}">
                        <label class="relative block">
                            <span class="sr-only">Cari nama paket</span>
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#565A66]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                                <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            <input
                                type="search"
                                name="search"
                                value="{{ request('search') }}"
                                x-on:input.debounce.400ms="$event.target.form.requestSubmit()"
                                placeholder="Cari paket..."
                                aria-label="Cari nama paket"
                                class="w-full sm:w-56 rounded-full border border-[#E5E7EB] bg-white py-2.5 pl-11 pr-4 text-sm font-inter text-[#16151A] placeholder-[#565A66] shadow-sm focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            >
                        </label>

                        <label>
                            <span class="sr-only">Filter status</span>
                            <select
                                name="status"
                                aria-label="Filter status"
                                onchange="this.form.submit()"
                                class="w-full sm:w-44 rounded-full border border-[#E5E7EB] bg-white py-2.5 pl-4 pr-10 text-sm font-inter font-medium text-[#16151A] shadow-sm focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] cursor-pointer transition"
                            >
                                <option value="">Semua Status</option>
                                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                            </select>
                        </label>

                        <button type="submit" class="sr-only">Cari</button>
                    </form>

                    <!-- Tombol Pill + Tambah Paket -->
                    <button
                        type="button"
                        x-on:click="openCreateModal()"
                        class="bg-[#ED1B45] text-white px-6 py-2.5 rounded-full font-inter font-semibold shadow-sm hover:bg-[#D1123D] transition flex items-center justify-center gap-2 shrink-0 cursor-pointer"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        <span>Tambah Paket</span>
                    </button>
                </div>
            </header>

            @if (session('success'))
                <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-inter font-semibold text-emerald-800 shadow-sm flex items-center gap-2.5">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- 5. Card Putih Besar Pembungkus Tabel & Filter Tabs -->
            <div class="bg-white rounded-[16px] shadow-[0_4px_12px_rgba(0,0,0,0.06)] overflow-hidden" aria-label="Daftar paket membership">
                <!-- 4. Filter Tabs & Info Item -->
                <div class="flex flex-col gap-4 border-b border-[#E5E7EB] p-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex w-fit max-w-full flex-wrap items-center gap-2">
                        @php
                            $tierFilters = [
                                '' => 'Semua Tier',
                                'Basic' => 'Basic',
                                'Standard' => 'Standard',
                                'Premium & VIP' => 'Premium & VIP',
                            ];
                        @endphp
                        @foreach ($tierFilters as $tierValue => $tierLabel)
                            @php
                                $isSelectedTier = request('tier', '') === $tierValue;
                            @endphp
                            <a
                                href="{{ route('admin.packages.index', array_merge(request()->only(['search', 'status']), ['tier' => $tierValue])) }}"
                                @class([
                                    'rounded-full px-5 py-2 font-inter text-sm transition',
                                    'bg-[#ED1B45] text-white font-semibold shadow-sm' => $isSelectedTier,
                                    'bg-white border border-[#E5E7EB] text-[#565A66] hover:text-[#16151A] hover:border-[#ED1B45]/40 cursor-pointer' => ! $isSelectedTier,
                                ])
                                aria-current="{{ $isSelectedTier ? 'page' : 'false' }}"
                            >
                                {{ $tierLabel }}
                            </a>
                        @endforeach
                    </div>

                    <p class="font-inter text-sm text-[#565A66]">
                        Menampilkan <span class="font-semibold text-[#16151A]">{{ $packages->firstItem() ?? 0 }}–{{ $packages->lastItem() ?? 0 }}</span>
                        dari <span class="font-semibold text-[#16151A]">{{ $packages->total() }}</span> paket
                    </p>
                </div>

                <!-- Tabel Paket Membership -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] border-collapse text-left">
                        <thead class="border-b border-[#E5E7EB] bg-[#F6F6F7]/50 font-inter text-[11px] font-bold text-[#565A66] uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-4">Tier &amp; Paket Membership</th>
                                <th scope="col" class="px-6 py-4">Durasi</th>
                                <th scope="col" class="px-6 py-4">Harga Resmi</th>
                                <th scope="col" class="px-6 py-4">Fitur &amp; Benefit</th>
                                <th scope="col" class="px-6 py-4 text-center">Status</th>
                                <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB]">
                            @if ($packages->isEmpty())
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-[#F6F6F7] text-[#565A66]">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="mt-3 font-poppins text-lg font-bold text-[#16151A]">Paket tidak ditemukan</p>
                                        <p class="mt-1 font-inter text-sm text-[#565A66]">Coba ubah kata pencarian atau filter yang dipilih.</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($packages as $pkg)
                                @php
                                    $tier = trim((string) ($pkg->tier ?? ''));
                                    $badge = trim((string) ($pkg->badge ?? ''));
                                    $isBestSeller = strcasecmp($badge, 'BEST SELLER') === 0;

                                    // Logika pewarnaan berdasarkan tier
                                    [$avatarStyle, $badgeStyle] = match (true) {
                                        strcasecmp($tier, 'Premium & VIP') === 0 || stripos($tier, 'Premium') !== false => [
                                            'bg-gray-900 text-white',
                                            'bg-gray-900 text-white',
                                        ],
                                        strcasecmp($tier, 'Standard') === 0 => [
                                            'bg-amber-100 text-amber-600',
                                            'bg-amber-100 text-amber-800',
                                        ],
                                        stripos($tier, 'Student') !== false => [
                                            'bg-sky-100 text-sky-600',
                                            'bg-sky-100 text-sky-800',
                                        ],
                                        default => [
                                            'bg-gray-100 text-gray-600',
                                            'bg-gray-100 text-gray-700',
                                        ],
                                    };

                                    // Pengecualian khusus: jika badge bernilai 'BEST SELLER' (case-insensitive)
                                    if ($isBestSeller) {
                                        $avatarStyle = 'bg-red-100 text-[#ED1B45]';
                                        $badgeStyle = 'bg-[#ED1B45] text-white';
                                    }

                                    $hasPromo = $pkg->promo_price !== null && $pkg->price > $pkg->promo_price;
                                    $discount = $hasPromo ? (int) round((($pkg->price - $pkg->promo_price) / $pkg->price) * 100) : 0;
                                @endphp
                                <tr @class([
                                    'transition duration-150 hover:bg-[#F6F6F7]/60',
                                    'bg-[#ED1B45]/5 hover:bg-[#ED1B45]/10' => $isBestSeller,
                                ])>
                                    <!-- Tier & Nama Paket -->
                                    <td class="px-6 py-5">
                                        <div class="flex min-w-[260px] items-center gap-3.5">
                                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $avatarStyle }}">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M5 8.5 12 4l7 4.5v7L12 20l-7-4.5v-7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                                    <path d="m5.5 8.5 6.5 4 6.5-4M12 12.5V20" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            <div class="min-w-0">
                                                @if ($pkg->badge)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold font-inter mb-1.5 {{ $badgeStyle }}">
                                                        {{ $pkg->badge }}
                                                    </span>
                                                @endif
                                                <h2 class="font-poppins font-bold text-lg text-[#16151A] leading-tight">{{ $pkg->name }}</h2>
                                                @if ($pkg->description)
                                                    <p class="mt-1 max-w-xs font-inter text-sm text-[#565A66] leading-relaxed">{{ $pkg->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Durasi -->
                                    <td class="whitespace-nowrap px-6 py-5">
                                        <p class="font-inter text-sm font-bold text-[#16151A]">{{ $pkg->duration_in_days }} Hari</p>
                                        <p class="mt-0.5 font-inter text-sm text-[#565A66]">{{ $pkg->duration_value }} {{ $pkg->duration_unit }}</p>
                                    </td>

                                    <!-- Harga Resmi -->
                                    <td class="whitespace-nowrap px-6 py-5">
                                        @if ($hasPromo)
                                            <p class="font-poppins font-bold text-lg text-[#16151A]">Rp {{ number_format($pkg->promo_price, 0, ',', '.') }}</p>
                                            <div class="mt-1 flex items-center">
                                                <span class="line-through text-[#565A66] text-xs font-inter">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                                <span class="bg-[#ED1B45] text-white rounded-full text-[10px] font-bold px-2 py-0.5 ml-2">HEMAT {{ $discount }}%</span>
                                            </div>
                                        @else
                                            <p class="font-poppins font-bold text-lg text-[#16151A]">Rp {{ number_format($pkg->price, 0, ',', '.') }}</p>
                                        @endif
                                    </td>

                                    <!-- Fitur & Benefit -->
                                    <td class="px-6 py-5">
                                        <ul class="min-w-[240px] space-y-1.5">
                                            @foreach ($pkg->facilities ?? [] as $facility)
                                                <li class="flex items-start gap-2 font-inter text-sm text-[#565A66]">
                                                    <span class="mt-0.5 shrink-0 font-bold text-[#10B981]" aria-hidden="true">✓</span>
                                                    <span>{{ $facility }}</span>
                                                </li>
                                            @endforeach
                                            @if (! $pkg->pt_sessions)
                                                <li class="flex items-start gap-2 font-inter text-sm text-[#565A66]">
                                                    <span class="mt-0.5 shrink-0 font-bold text-[#EF4444]" aria-hidden="true">×</span>
                                                    <span class="line-through text-[#565A66]">Sesi Personal Trainer tidak termasuk</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </td>

                                    <!-- Status Switch -->
                                    <td class="px-6 py-5 text-center">
                                        <div
                                            x-data="membershipPackageStatus({
                                                url: @js(route('admin.packages.toggle-status', $pkg)),
                                                isActive: @js($pkg->is_active),
                                            })"
                                        >
                                            <form method="POST" action="{{ route('admin.packages.toggle-status', $pkg) }}" class="inline-flex flex-col items-center">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    x-on:click.prevent="toggleStatus()"
                                                    x-bind:aria-checked="isActive.toString()"
                                                    role="switch"
                                                    aria-label="Ubah status paket"
                                                    x-bind:disabled="isSaving"
                                                    class="relative inline-flex h-7 w-12 items-center rounded-full transition focus:outline-none focus:ring-2 focus:ring-[#ED1B45] focus:ring-offset-2 disabled:cursor-wait"
                                                    x-bind:class="isActive ? 'bg-[#10B981]' : 'bg-[#D4D4D8]'"
                                                >
                                                    <span class="sr-only">Ubah status paket</span>
                                                    <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition" x-bind:class="isActive ? 'translate-x-6' : 'translate-x-1'"></span>
                                                </button>
                                                <span class="mt-1.5 font-inter text-xs font-semibold" x-bind:class="isActive ? 'text-[#10B981]' : 'text-[#565A66]'" x-text="isActive ? 'Aktif' : 'Nonaktif'"></span>
                                                <span x-show="error" x-text="error" role="alert" class="mt-1 max-w-28 font-inter text-[10px] text-red-600"></span>
                                            </form>
                                        </div>
                                    </td>

                                    <!-- Aksi Edit -->
                                    <td class="px-6 py-5 text-center">
                                        <button
                                            type="button"
                                            data-package="{{ json_encode($pkg->only(['id', 'name', 'badge', 'tier', 'description', 'price', 'promo_price', 'duration_value', 'duration_unit', 'duration_in_days', 'facilities', 'pt_sessions', 'is_active']), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}"
                                            x-on:click="openEditModal(JSON.parse($el.dataset.package))"
                                            aria-label="Edit paket {{ $pkg->name }}"
                                            class="w-9 h-9 rounded-full border border-[#E5E7EB] flex items-center justify-center text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#ED1B45] transition mx-auto cursor-pointer"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="m14.5 6.5 3 3M4 20l4.3-.9L19 8.4a2.12 2.12 0 0 0-3-3L5.3 16.1 4 20Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Footer Paginasi -->
                @if ($packages->hasPages())
                    <footer class="flex flex-col gap-4 border-t border-[#E5E7EB] px-6 py-4 sm:flex-row sm:items-center sm:justify-between bg-white">
                        <p class="font-inter text-sm text-[#565A66]">
                            Halaman {{ $packages->currentPage() }} dari {{ $packages->lastPage() }}
                        </p>
                        <nav aria-label="Navigasi halaman paket" class="flex items-center gap-1.5">
                            @if ($packages->onFirstPage())
                                <span aria-disabled="true" class="rounded-full border border-[#E5E7EB] text-[#565A66]/40 px-3.5 py-2 text-xs font-semibold font-inter cursor-not-allowed">Sebelumnya</span>
                            @else
                                <a href="{{ $packages->previousPageUrl() }}" rel="prev" class="rounded-full border border-[#E5E7EB] text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] px-3.5 py-2 text-xs font-semibold font-inter transition">Sebelumnya</a>
                            @endif

                            @for ($page = max(1, $packages->currentPage() - 2); $page <= min($packages->lastPage(), $packages->currentPage() + 2); $page++)
                                @if ($page === $packages->currentPage())
                                    <span aria-current="page" class="flex h-9 min-w-9 items-center justify-center rounded-full bg-[#ED1B45] text-white px-3 text-xs font-bold font-inter shadow-sm">{{ $page }}</span>
                                @else
                                    <a href="{{ $packages->url($page) }}" aria-label="Halaman {{ $page }}" class="flex h-9 min-w-9 items-center justify-center rounded-full border border-[#E5E7EB] text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] px-3 text-xs font-semibold font-inter transition">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($packages->hasMorePages())
                                <a href="{{ $packages->nextPageUrl() }}" rel="next" class="rounded-full border border-[#E5E7EB] text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] px-3.5 py-2 text-xs font-semibold font-inter transition">Berikutnya</a>
                            @else
                                <span aria-disabled="true" class="rounded-full border border-[#E5E7EB] text-[#565A66]/40 px-3.5 py-2 text-xs font-semibold font-inter cursor-not-allowed">Berikutnya</span>
                            @endif
                        </nav>
                    </footer>
                @endif
            </div>
        </div>

        @include('admin.packages.partials.modal')
    </div>
</x-app-layout>
