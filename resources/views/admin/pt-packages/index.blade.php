<x-app-layout>
    <div
        x-data="ptPackages({
            hasErrors: @js($errors->any()),
            oldForm: @js([
                'id' => old('package_id'),
                'name' => old('name', ''),
                'pt_session_count' => old('pt_session_count', 4),
                'price' => old('price', ''),
                'min_membership_days' => old('min_membership_days', 0),
                'validity_days' => old('validity_days', 30),
                'is_active' => old('is_active') !== null ? (bool) old('is_active') : true,
            ]),
            packageBaseUrl: @js(route('admin.pt-packages.index')),
            storeUrl: @js(route('admin.pt-packages.store')),
        })"
        x-on:keydown.escape.window="showModal = false"
        class="min-h-screen bg-[#F6F6F7] px-4 py-6 text-[#16151A] sm:px-6 lg:px-8 w-full max-w-full"
    >
        <div class="mx-auto max-w-full space-y-6">
            <!-- Header & Search/Filter Controls -->
            <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 font-inter text-xs font-bold text-[#ED1B45] border border-rose-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#ED1B45]"></span>
                            Portal Admin
                        </span>
                        <span class="text-xs font-medium text-[#565A66] font-inter">Personal Trainer Service</span>
                    </div>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold font-poppins text-[#16151A] tracking-tight">
                        Manajemen Paket Sesi Personal Trainer (PT)
                    </h1>
                    <p class="font-inter text-sm text-[#565A66] mt-1 max-w-3xl">
                        Atur penawaran sesi latihan privat, tarif resmi, syarat minimum keanggotaan aktif, dan masa berlaku sesi yang dapat dibeli oleh member.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center shrink-0">
                    <form method="GET" action="{{ route('admin.pt-packages.index') }}" class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
                        <!-- Search Bar -->
                        <label class="relative block">
                            <span class="sr-only">Cari nama paket sesi PT</span>
                            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#565A66]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                                <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                            <input
                                type="search"
                                name="search"
                                value="{{ request('search') }}"
                                x-on:input.debounce.400ms="$event.target.form.requestSubmit()"
                                placeholder="Cari paket sesi PT..."
                                aria-label="Cari nama paket sesi PT"
                                class="w-full sm:w-48 md:w-56 rounded-full border border-[#E5E7EB] bg-white py-2 pl-10 pr-4 text-sm font-inter text-[#16151A] placeholder-[#565A66] shadow-sm focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                            >
                        </label>

                        <!-- Filter Status -->
                        <label>
                            <span class="sr-only">Filter status paket</span>
                            <select
                                name="status"
                                aria-label="Filter status paket"
                                onchange="this.form.submit()"
                                class="w-full sm:w-36 rounded-full border border-[#E5E7EB] bg-white py-2 pl-3.5 pr-8 text-sm font-inter font-medium text-[#16151A] shadow-sm focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] cursor-pointer transition"
                            >
                                <option value="">Semua Status</option>
                                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                            </select>
                        </label>

                        <button type="submit" class="sr-only">Cari</button>
                    </form>

                    <!-- Tombol Tambah Paket PT -->
                    <button
                        type="button"
                        x-on:click="openCreateModal()"
                        class="bg-[#ED1B45] text-white px-5 py-2 rounded-full font-inter font-semibold shadow-sm hover:bg-[#D1123D] transition flex items-center justify-center gap-2 shrink-0 cursor-pointer whitespace-nowrap text-sm"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                        <span>Tambah Paket PT</span>
                    </button>
                </div>
            </header>

            <!-- Alert Flash Feedback -->
            @if (session('success'))
                <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-inter font-semibold text-emerald-800 shadow-sm flex items-center gap-2.5">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3.5 text-sm font-inter font-semibold text-rose-800 shadow-sm flex items-center gap-2.5">
                    <svg class="h-5 w-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Tabel Daftar Paket Sesi PT -->
            <div class="bg-white rounded-[16px] shadow-[0_4px_12px_rgba(0,0,0,0.06)] overflow-hidden" aria-label="Daftar paket sesi personal trainer">
                <!-- Info Item & Subheader -->
                <div class="flex flex-col gap-3 border-b border-[#E5E7EB] p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-poppins text-lg font-bold text-[#16151A]">Daftar Paket Sesi PT</h2>
                        <p class="font-inter text-xs text-[#565A66] mt-0.5">Kelola kuota, status aktif, dan batasan masa aktif membership.</p>
                    </div>

                    <p class="font-inter text-sm text-[#565A66]">
                        Menampilkan <span class="font-semibold text-[#16151A]">{{ $packages->firstItem() ?? 0 }}–{{ $packages->lastItem() ?? 0 }}</span>
                        dari <span class="font-semibold text-[#16151A]">{{ $packages->total() }}</span> paket
                    </p>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead class="border-b border-[#E5E7EB] bg-[#F6F6F7]/50 font-inter text-[11px] font-bold text-[#565A66] uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-4 py-3.5 sm:px-5 sm:py-4">Nama Paket PT</th>
                                <th scope="col" class="px-3 py-3.5 sm:px-4 sm:py-4 whitespace-nowrap">Jumlah Sesi</th>
                                <th scope="col" class="px-3 py-3.5 sm:px-4 sm:py-4 whitespace-nowrap">Tarif Harga</th>
                                <th scope="col" class="px-3 py-3.5 sm:px-4 sm:py-4 whitespace-nowrap">Syarat Min. Membership</th>
                                <th scope="col" class="px-3 py-3.5 sm:px-4 sm:py-4 whitespace-nowrap">Masa Berlaku</th>
                                <th scope="col" class="px-3 py-3.5 sm:px-4 sm:py-4 text-center whitespace-nowrap">Status</th>
                                <th scope="col" class="px-3 py-3.5 sm:px-4 sm:py-4 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB] font-inter text-sm">
                            @if ($packages->isEmpty())
                                <tr>
                                    <td colspan="7" class="px-4 py-16 text-center">
                                        <div class="mx-auto flex max-w-sm flex-col items-center">
                                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-[#ED1B45] mb-4">
                                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <path stroke-linecap="round" d="M12 8v4M12 16h.01" />
                                                </svg>
                                            </div>
                                            <p class="font-poppins text-base font-bold text-[#16151A]">Tidak Ada Paket PT Ditemukan</p>
                                            <p class="mt-1 font-inter text-xs text-[#565A66]">
                                                @if (request('search') || request('status'))
                                                    Pencarian dengan filter saat ini tidak membuahkan hasil.
                                                @else
                                                    Belum ada paket sesi personal trainer yang terdaftar pada sistem.
                                                @endif
                                            </p>
                                            @if (request('search') || request('status'))
                                                <a href="{{ route('admin.pt-packages.index') }}" class="mt-4 rounded-full border border-[#E5E7EB] bg-white px-4 py-2 text-xs font-semibold text-[#16151A] hover:bg-[#F6F6F7] transition">
                                                    Reset Filter
                                                </a>
                                            @else
                                                <button
                                                    type="button"
                                                    x-on:click="openCreateModal()"
                                                    class="mt-4 rounded-full bg-[#ED1B45] px-5 py-2 text-xs font-semibold text-white hover:bg-[#D1123D] transition shadow-sm"
                                                >
                                                    + Tambah Paket Pertama
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($packages as $pkg)
                                    @php
                                        $isActive = $pkg->status === 'active';
                                        $perSession = $pkg->pt_session_count > 0 ? (float) $pkg->price / $pkg->pt_session_count : 0;
                                    @endphp
                                    <tr class="hover:bg-[#F6F6F7]/60 transition">
                                        <!-- Nama Paket PT -->
                                        <td class="px-4 py-4 sm:px-5">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-9 w-9 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-xl bg-[#ED1B45]/10 text-[#ED1B45] font-poppins font-bold text-xs sm:text-sm">
                                                    PT
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-poppins font-bold text-[#16151A] leading-snug">
                                                        {{ $pkg->name }}
                                                    </p>
                                                    <p class="text-[11px] font-inter text-[#565A66]">
                                                        ID Paket: #{{ $pkg->id }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Jumlah Sesi -->
                                        <td class="px-3 py-4 sm:px-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#16151A]/5 border border-[#E5E7EB] px-2.5 py-1 font-inter text-xs font-bold text-[#16151A]">
                                                <svg class="h-3.5 w-3.5 text-[#ED1B45]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <path stroke-linecap="round" d="M12 6v6l4 2" />
                                                </svg>
                                                {{ $pkg->pt_session_count }} Sesi
                                            </span>
                                        </td>

                                        <!-- Tarif Harga -->
                                        <td class="px-3 py-4 sm:px-4 whitespace-nowrap">
                                            <div>
                                                <p class="font-poppins font-extrabold text-[#16151A] text-base">
                                                    Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                                </p>
                                                @if ($perSession > 0)
                                                    <p class="text-[11px] font-inter text-[#565A66]">
                                                        ~Rp {{ number_format($perSession, 0, ',', '.') }} / sesi
                                                    </p>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Syarat Minimum Sisa Hari Membership -->
                                        <td class="px-3 py-4 sm:px-4 whitespace-nowrap">
                                            @if ($pkg->min_membership_days > 0)
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                                    <svg class="h-3.5 w-3.5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                        <line x1="16" y1="2" x2="16" y2="6" />
                                                        <line x1="8" y1="2" x2="8" y2="6" />
                                                        <line x1="3" y1="10" x2="21" y2="10" />
                                                    </svg>
                                                    Min. {{ $pkg->min_membership_days }} Hari
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-600">
                                                    Tanpa Syarat
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Masa Berlaku Sesi -->
                                        <td class="px-3 py-4 sm:px-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#16151A]">
                                                <svg class="h-4 w-4 text-[#565A66]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <polyline points="12 6 12 12 16 14" />
                                                </svg>
                                                {{ $pkg->validity_days }} Hari
                                            </span>
                                        </td>

                                        <!-- Status Switch -->
                                        <td class="px-3 py-4 sm:px-4 text-center whitespace-nowrap">
                                            <div
                                                x-data="ptPackageStatus({
                                                    url: @js(route('admin.pt-packages.toggle-status', $pkg)),
                                                    isActive: @js($isActive),
                                                })"
                                            >
                                                <form method="POST" action="{{ route('admin.pt-packages.toggle-status', $pkg) }}" class="inline-flex flex-col items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        type="submit"
                                                        x-on:click.prevent="toggleStatus()"
                                                        x-bind:aria-checked="isActive.toString()"
                                                        role="switch"
                                                        aria-label="Ubah status paket {{ $pkg->name }}"
                                                        x-bind:disabled="isSaving"
                                                        class="relative inline-flex h-7 w-12 items-center rounded-full transition focus:outline-none focus:ring-2 focus:ring-[#ED1B45] focus:ring-offset-2 disabled:cursor-wait cursor-pointer"
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
                                        <td class="px-3 py-4 sm:px-4 text-center whitespace-nowrap">
                                            <button
                                                type="button"
                                                data-package="{{ json_encode([
                                                    'id' => $pkg->id,
                                                    'name' => $pkg->name,
                                                    'pt_session_count' => $pkg->pt_session_count,
                                                    'price' => (int) $pkg->price,
                                                    'min_membership_days' => $pkg->min_membership_days,
                                                    'validity_days' => $pkg->validity_days,
                                                    'status' => $pkg->status,
                                                    'is_active' => $pkg->status === 'active',
                                                ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}"
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

        <!-- Modal Tambah / Edit Paket PT -->
        @include('admin.pt-packages.partials.modal')
    </div>
</x-app-layout>
