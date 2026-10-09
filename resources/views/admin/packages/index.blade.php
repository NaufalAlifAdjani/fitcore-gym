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
        class="min-h-screen bg-[#F9F9FA] px-4 py-8 text-[#16151A] sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-[1600px]">
            <header class="mb-8 flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 class="font-heading text-3xl font-bold leading-tight text-[#16151A] sm:text-[36px]">
                        Paket Membership &amp; Pengaturan Harga
                    </h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-[#565A66]">
                        Kelola jenis keanggotaan gym, tarif harga, durasi langganan, dan rincian fasilitas member secara terpusat.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <form method="GET" action="{{ route('admin.packages.index') }}" class="flex flex-col gap-3 sm:flex-row">
                        <input type="hidden" name="tier" value="{{ request('tier') }}">
                        <label class="relative block">
                            <span class="sr-only">Cari nama paket</span>
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#858894]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                                <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            <x-text-input
                                type="search"
                                name="search"
                                value="{{ request('search') }}"
                                x-on:input.debounce.400ms="$event.target.form.requestSubmit()"
                                placeholder="Cari paket..."
                                aria-label="Cari nama paket"
                                class="!w-full !rounded-full !border-0 bg-white py-3 pl-11 pr-4 text-sm text-[#16151A] shadow-[0px_1px_3px_rgba(0,0,0,0.08)] placeholder:text-[#858894] focus:ring-2 focus:ring-[#BA0030] sm:!w-52"
                            />
                        </label>

                        <label>
                            <span class="sr-only">Filter status</span>
                            <x-select-input
                                name="status"
                                aria-label="Filter status"
                                onchange="this.form.submit()"
                                class="!w-full !rounded-full !border-0 bg-white py-3 pl-5 pr-10 text-sm font-semibold text-[#34343B] shadow-[0px_1px_3px_rgba(0,0,0,0.08)] focus:ring-2 focus:ring-[#BA0030] sm:!w-44"
                            >
                                <option value="">Semua Status</option>
                                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                            </x-select-input>
                        </label>

                        <button type="submit" class="sr-only">Cari</button>
                    </form>

                    <x-button
                        type="button"
                        variant="primary"
                        x-on:click="openCreateModal()"
                        class="!rounded-full shadow-sm"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        Tambah Paket
                    </x-button>
                </div>
            </header>

            @if (session('success'))
                <x-alert type="success" class="mb-5">
                    {{ session('success') }}
                </x-alert>
            @endif

            <section aria-label="Ringkasan paket membership" class="mb-8 grid grid-cols-1 gap-5 md:grid-cols-3">
                <x-card class="flex min-h-40 items-start justify-between" :padding="true">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#858894]">Total Paket Gym</p>
                        <p class="mt-4 font-heading text-4xl font-extrabold leading-none text-[#16151A] sm:text-5xl">{{ $totalPackages }}</p>
                        <p class="mt-2 text-sm text-[#565A66]">paket terdaftar</p>
                    </div>
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#EEEEEF] text-[#BA0030]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 3.75h7l4.25 4.3v12.2H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            <path d="M14 4v4h4M8.5 13h7M8.5 16.5h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                </x-card>

                <x-card class="flex min-h-40 items-start justify-between" :padding="true">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#858894]">Rata-rata Harga Paket Aktif</p>
                        <p class="mt-4 font-heading text-3xl font-extrabold leading-tight text-[#16151A] sm:text-[38px]">{{ $formattedAvgPrice }}</p>
                        <p class="mt-2 text-sm text-[#565A66]">harga resmi per paket</p>
                    </div>
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#EEEEEF] text-[#BA0030]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <rect x="3.5" y="5" width="17" height="14" rx="2.5" stroke="currentColor" stroke-width="1.8" />
                            <path d="M3.5 9h17M7.5 14h3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                </x-card>

                <x-card class="flex min-h-40 items-start justify-between" :padding="true">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[#858894]">Paket Aktif</p>
                        <p class="mt-4 font-heading text-4xl font-extrabold leading-none text-[#16151A] sm:text-5xl">{{ $activePackages }}</p>
                        <p class="mt-2 text-sm text-[#565A66]">siap dipilih member</p>
                    </div>
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#EEEEEF] text-[#BA0030]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="9" cy="8" r="3.25" stroke="currentColor" stroke-width="1.8" />
                            <path d="M3.75 19v-1.2A4.8 4.8 0 0 1 8.55 13h.9a4.8 4.8 0 0 1 4.8 4.8V19M16 5.3a3.25 3.25 0 0 1 0 6.2m1.2 2a4.8 4.8 0 0 1 3.05 4.5V19" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                </x-card>
            </section>

            <x-card :padding="false" aria-label="Daftar paket membership">
                <x-slot:header>
                    <div class="flex flex-col gap-4">
                        <div class="flex w-fit max-w-full flex-wrap items-center gap-1 rounded-full bg-[#EEEEEF] p-1">
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
                                        'rounded-full px-4 py-2 text-xs font-semibold transition sm:text-sm',
                                        'bg-white text-[#16151A] shadow-[0px_1px_3px_rgba(0,0,0,0.10)]' => $isSelectedTier,
                                        'text-[#565A66] hover:text-[#16151A]' => ! $isSelectedTier,
                                    ])
                                    aria-current="{{ $isSelectedTier ? 'page' : 'false' }}"
                                >
                                    {{ $tierLabel }}
                                </a>
                            @endforeach
                        </div>

                        <p class="text-sm text-[#858894]">
                            Menampilkan <span class="font-semibold text-[#34343B]">{{ $packages->firstItem() ?? 0 }}–{{ $packages->lastItem() ?? 0 }}</span>
                            dari <span class="font-semibold text-[#34343B]">{{ $packages->total() }}</span> paket
                        </p>
                    </div>
                </x-slot:header>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] border-collapse text-left">
                        <thead class="border-b border-[#F0F0F1] bg-[#FCFCFC] text-[10px] font-semibold uppercase tracking-[0.12em] text-[#858894]">
                            <tr>
                                <th scope="col" class="px-6 py-4">Tier &amp; Paket Membership</th>
                                <th scope="col" class="px-5 py-4">Durasi</th>
                                <th scope="col" class="px-5 py-4">Harga Resmi</th>
                                <th scope="col" class="px-5 py-4">Fitur &amp; Benefit</th>
                                <th scope="col" class="px-5 py-4 text-center">Status</th>
                                <th scope="col" class="px-5 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F0F0F1]">
                            @if ($packages->isEmpty())
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <p class="font-heading text-lg font-bold text-[#34343B]">Paket tidak ditemukan</p>
                                        <p class="mt-2 text-sm text-[#858894]">Coba ubah kata pencarian atau filter yang dipilih.</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($packages as $pkg)
                                @php
                                    $avatarStyle = match (strtoupper($pkg->badge ?? '')) {
                                        'BEST SELLER' => 'bg-[#BA0030] text-white',
                                        'PLATINUM VIP' => 'bg-[#1E1E1E] text-white',
                                        'GOLD STANDARD' => 'bg-amber-100 text-amber-700',
                                        'SILVER' => 'bg-[#E5E2E1] text-[#565A66]',
                                        'STUDENT TIER' => 'bg-sky-100 text-sky-700',
                                        default => 'bg-[#EEEEEF] text-[#565A66]',
                                    };
                                    $hasPromo = $pkg->promo_price !== null && $pkg->price > $pkg->promo_price;
                                    $discount = $hasPromo ? (int) round((($pkg->price - $pkg->promo_price) / $pkg->price) * 100) : 0;
                                @endphp
                                <tr @class([
                                    'transition hover:bg-[#FCFCFC]',
                                    'bg-[rgba(186,0,48,0.05)] hover:bg-[rgba(186,0,48,0.08)]' => $pkg->badge === 'BEST SELLER',
                                ])>
                                    <td class="px-6 py-5">
                                        <div class="flex min-w-[250px] items-center gap-3">
                                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $avatarStyle }}">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M5 8.5 12 4l7 4.5v7L12 20l-7-4.5v-7Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                                    <path d="m5.5 8.5 6.5 4 6.5-4M12 12.5V20" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            <div class="min-w-0">
                                                @if ($pkg->badge)
                                                    <x-badge :status="$pkg->badge" :dot="false" size="xs" class="mb-1 !font-extrabold tracking-[0.1em]" />
                                                @endif
                                                <h2 class="font-heading text-[15px] font-bold leading-5 text-[#16151A]">{{ $pkg->name }}</h2>
                                                @if ($pkg->description)
                                                    <p class="mt-1 max-w-xs text-xs leading-5 text-[#717480]">{{ $pkg->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-5">
                                        <p class="text-sm font-semibold text-[#34343B]">{{ $pkg->duration_in_days }} Hari</p>
                                        <p class="mt-1 text-xs text-[#858894]">{{ $pkg->duration_value }} {{ $pkg->duration_unit }}</p>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-5">
                                        @if ($hasPromo)
                                            <p class="font-heading text-base font-bold text-[#16151A]">Rp {{ number_format($pkg->promo_price, 0, ',', '.') }}</p>
                                            <div class="mt-1.5 flex items-center gap-2">
                                                <span class="font-heading text-xs text-[#858894] line-through">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                                <x-badge variant="danger" :dot="false" size="xs" class="!bg-[#E61241] !text-white">HEMAT {{ $discount }}%</x-badge>
                                            </div>
                                        @else
                                            <p class="font-heading text-base font-bold text-[#16151A]">Rp {{ number_format($pkg->price, 0, ',', '.') }}</p>
                                        @endif
                                    </td>

                                    <td class="px-5 py-5">
                                        <ul class="min-w-[235px] space-y-2">
                                            @foreach ($pkg->facilities ?? [] as $facility)
                                                <li class="flex items-start gap-2 text-xs leading-4 text-[#565A66]">
                                                    <span class="mt-px shrink-0 font-bold text-[#10B981]" aria-hidden="true">✓</span>
                                                    <span>{{ $facility }}</span>
                                                </li>
                                            @endforeach
                                            @if (! $pkg->pt_sessions)
                                                <li class="flex items-start gap-2 text-xs leading-4 text-[#858894]">
                                                    <span class="mt-px shrink-0 font-bold text-[#EF4444]/60" aria-hidden="true">×</span>
                                                    <span class="line-through decoration-[#EF4444]/60">Sesi Personal Trainer tidak termasuk</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </td>

                                    <td class="px-5 py-5 text-center">
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
                                                    class="relative inline-flex h-7 w-12 items-center rounded-full transition focus:outline-none focus:ring-2 focus:ring-[#BA0030] focus:ring-offset-2 disabled:cursor-wait"
                                                    x-bind:class="isActive ? 'bg-[#10B981]' : 'bg-[#D4D4D8]'"
                                                >
                                                    <span class="sr-only">Ubah status paket</span>
                                                    <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition" x-bind:class="isActive ? 'translate-x-6' : 'translate-x-1'"></span>
                                                </button>
                                                <span class="mt-1.5 text-[10px] font-semibold" x-bind:class="isActive ? 'text-emerald-700' : 'text-[#858894]'" x-text="isActive ? 'Aktif' : 'Nonaktif'"></span>
                                                <span x-show="error" x-text="error" role="alert" class="mt-1 max-w-28 text-[10px] text-red-600"></span>
                                            </form>
                                        </div>
                                    </td>

                                    <td class="px-5 py-5 text-center">
                                        <x-button
                                            type="button"
                                            variant="ghost"
                                            size="xs"
                                            data-package="{{ json_encode($pkg->only(['id', 'name', 'badge', 'tier', 'description', 'price', 'promo_price', 'duration_value', 'duration_unit', 'duration_in_days', 'facilities', 'pt_sessions', 'is_active']), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}"
                                            x-on:click="openEditModal(JSON.parse($el.dataset.package))"
                                            aria-label="Edit paket {{ $pkg->name }}"
                                            class="!h-10 !w-10 !rounded-full !bg-[#F4F4F5] !p-0 text-[#565A66] hover:!bg-[#EEEEEF] hover:!text-[#BA0030]"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="m14.5 6.5 3 3M4 20l4.3-.9L19 8.4a2.12 2.12 0 0 0-3-3L5.3 16.1 4 20Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </x-button>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                @if ($packages->hasPages())
                    <x-slot:footer class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-[#858894]">
                            Halaman {{ $packages->currentPage() }} dari {{ $packages->lastPage() }}
                        </p>
                        <nav aria-label="Navigasi halaman paket" class="flex items-center justify-center gap-1 rounded-full bg-[#EEEEEF] p-1">
                            @if ($packages->onFirstPage())
                                <span aria-disabled="true" class="rounded-full px-3 py-2 text-xs font-semibold text-[#A5A6AD]">Sebelumnya</span>
                            @else
                                <a href="{{ $packages->previousPageUrl() }}" rel="prev" class="rounded-full px-3 py-2 text-xs font-semibold text-[#565A66] transition hover:bg-white">Sebelumnya</a>
                            @endif

                            @for ($page = max(1, $packages->currentPage() - 2); $page <= min($packages->lastPage(), $packages->currentPage() + 2); $page++)
                                @if ($page === $packages->currentPage())
                                    <span aria-current="page" class="flex h-9 min-w-9 items-center justify-center rounded-full bg-white px-3 text-xs font-bold text-[#BA0030] shadow-sm">{{ $page }}</span>
                                @else
                                    <a href="{{ $packages->url($page) }}" aria-label="Halaman {{ $page }}" class="flex h-9 min-w-9 items-center justify-center rounded-full px-3 text-xs font-semibold text-[#565A66] transition hover:bg-white">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($packages->hasMorePages())
                                <a href="{{ $packages->nextPageUrl() }}" rel="next" class="rounded-full px-3 py-2 text-xs font-semibold text-[#565A66] transition hover:bg-white">Berikutnya</a>
                            @else
                                <span aria-disabled="true" class="rounded-full px-3 py-2 text-xs font-semibold text-[#A5A6AD]">Berikutnya</span>
                            @endif
                        </nav>
                    </x-slot:footer>
                @endif
            </x-card>
        </div>

        @include('admin.packages.partials.modal')
    </div>
</x-app-layout>
