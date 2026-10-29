<x-app-layout>
    <div
        x-data="{
            showDetailModal: false,
            showAddModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
            showEditModal: {{ $errors->any() && old('_method') === 'PUT' ? 'true' : 'false' }},
            showDeleteModal: false,
            deleteType: 'soft',
            selectedMember: null,
            detailLoading: false,

            openDetail(member) {
                this.selectedMember = member;
                this.showDetailModal = true;
                this.detailLoading = true;

                fetch('{{ url('/admin/member-management') }}/' + member.id)
                    .then(res => res.json())
                    .then(data => {
                        this.selectedMember = data;
                        this.detailLoading = false;
                    })
                    .catch(() => {
                        this.detailLoading = false;
                    });
            },

            openEditFromDetail() {
                this.showDetailModal = false;
                this.showEditModal = true;
            },

            openDeleteFromDetail(type = 'soft') {
                this.deleteType = type;
                this.showDetailModal = false;
                this.showDeleteModal = true;
            }
        }"
        x-on:keydown.escape.window="showDetailModal = false; showAddModal = false; showEditModal = false; showDeleteModal = false"
        class="min-h-screen bg-[#F6F6F7] px-4 py-8 text-[#16151A] sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-[1600px] space-y-6">

            <!-- 1. Header & Quick Controls -->
            <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold font-poppins text-[#16151A] tracking-tight">
                        Manajemen Data Member
                    </h1>
                    <p class="font-inter text-sm text-[#565A66] mt-1 max-w-3xl">
                        Kelola informasi keanggotaan, masa aktif langganan, dan riwayat presensi gym secara terpusat.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Search Input (Global search across members) -->
                    <form method="GET" action="{{ route('admin.members.index') }}" class="relative block w-full sm:w-64">
                        @if(request('status') && request('status') !== 'all')
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama, email, ID..."
                            class="w-full rounded-full border border-[#E5E7EB] bg-white py-2 pl-10 pr-9 text-xs font-inter text-[#16151A] placeholder-[#565A66] shadow-xs focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45]"
                        >
                        @if(request('search'))
                            <a
                                href="{{ route('admin.members.index', request()->except(['search', 'page'])) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#ED1B45] transition"
                                title="Hapus kata kunci pencarian"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </form>

                    <!-- Status Dropdown Quick Filter -->
                    <form method="GET" action="{{ route('admin.members.index') }}">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select
                            name="status"
                            onchange="this.form.submit()"
                            class="rounded-full border border-[#E5E7EB] bg-white py-2 px-3 text-xs font-inter text-[#16151A] shadow-xs focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] cursor-pointer"
                        >
                            <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Akan Berakhir (<7 Hari)</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif / Expired</option>
                        </select>
                    </form>

                    <!-- Export CSV Button -->
                    <a
                        href="{{ route('admin.members.export', request()->query()) }}"
                        class="inline-flex items-center gap-2 rounded-full border border-[#E5E7EB] bg-white px-4 py-2 text-xs font-semibold text-[#16151A] shadow-xs transition hover:bg-gray-50 focus:outline-none cursor-pointer"
                    >
                        <svg class="h-4 w-4 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Export CSV</span>
                    </a>

                    <!-- Tambah Member Button -->
                    <button
                        type="button"
                        x-on:click="showAddModal = true"
                        class="inline-flex items-center gap-2 rounded-full bg-[#ED1B45] px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-[#D1123D] focus:outline-none cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Member</span>
                    </button>
                </div>
            </header>

            <!-- 2. Ringkasan Kartu Statistik (4 Cards) -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <!-- Card 1: TOTAL MEMBER -->
                <div class="rounded-2xl border border-[#E5E7EB] bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#565A66] font-inter">
                            TOTAL MEMBER
                        </span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-100 text-[#565A66]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="font-poppins text-3xl font-extrabold text-[#16151A]">
                            {{ number_format($totalMembers, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 flex items-center gap-1 text-xs font-medium text-emerald-600">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            <span>+12.4% vs bulan lalu</span>
                        </p>
                    </div>
                </div>

                <!-- Card 2: MEMBER AKTIF -->
                <div class="rounded-2xl border border-[#E5E7EB] bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#565A66] font-inter">
                            MEMBER AKTIF
                        </span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="font-poppins text-3xl font-extrabold text-[#16151A]">
                            {{ number_format($activeMembers, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 flex items-center gap-1.5 text-xs font-medium text-[#565A66]">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            <span class="font-bold text-emerald-600">{{ $activeRatio }}%</span> rasio aktif
                        </p>
                    </div>
                </div>

                <!-- Card 3: AKAN BERAKHIR <7 HARI -->
                <div class="rounded-2xl border border-[#E5E7EB] bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#565A66] font-inter">
                            AKAN BERAKHIR &lt;7 HARI
                        </span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="font-poppins text-3xl font-extrabold text-[#16151A]">
                            {{ number_format($expiringMembers, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 flex items-center gap-1.5 text-xs font-medium text-amber-600">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Perlu Tindak Lanjut</span>
                        </p>
                    </div>
                </div>

                <!-- Card 4: NONAKTIF / EXPIRED -->
                <div class="rounded-2xl border border-[#E5E7EB] bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#565A66] font-inter">
                            NONAKTIF / EXPIRED
                        </span>
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-100 text-[#565A66]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="font-poppins text-3xl font-extrabold text-[#16151A]">
                            {{ number_format($inactiveMembers, 0, ',', '.') }}
                        </div>
                        <p class="mt-1 flex items-center gap-1.5 text-xs font-medium text-[#565A66]">
                            <span class="font-bold text-[#16151A]">{{ $churnRate }}%</span> total churn
                        </p>
                    </div>
                </div>
            </div>

            <!-- 3. Filter Tabs & Filter Bar -->
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <!-- Status Tab Pills -->
                <div class="flex flex-wrap items-center gap-2">
                    <a
                        href="{{ route('admin.members.index') }}"
                        class="rounded-full px-4 py-2 text-xs font-semibold transition {{ ($statusFilter === 'all' && !request('status')) ? 'bg-[#ED1B45] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:bg-gray-50' }}"
                        title="Tampilkan semua data anggota tanpa filter"
                    >
                        Semua ({{ number_format($totalMembers, 0, ',', '.') }})
                    </a>

                    <a
                        href="{{ route('admin.members.index', array_filter(['status' => 'active', 'search' => request('search')])) }}"
                        class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $statusFilter === 'active' ? 'bg-[#ED1B45] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:bg-gray-50' }}"
                    >
                        Aktif ({{ number_format($activeMembers, 0, ',', '.') }})
                    </a>

                    <a
                        href="{{ route('admin.members.index', array_filter(['status' => 'expiring', 'search' => request('search')])) }}"
                        class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $statusFilter === 'expiring' ? 'bg-[#ED1B45] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:bg-gray-50' }}"
                    >
                        Akan Berakhir ({{ number_format($expiringMembers, 0, ',', '.') }})
                    </a>

                    <a
                        href="{{ route('admin.members.index', array_filter(['status' => 'inactive', 'search' => request('search')])) }}"
                        class="rounded-full px-4 py-2 text-xs font-semibold transition {{ $statusFilter === 'inactive' ? 'bg-[#ED1B45] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:bg-gray-50' }}"
                    >
                        Nonaktif ({{ number_format($inactiveMembers, 0, ',', '.') }})
                    </a>
                </div>

                <!-- Package & Sort Dropdowns + Reset Button -->
                <div class="flex flex-wrap items-center gap-3">
                    <form method="GET" action="{{ route('admin.members.index') }}" class="flex flex-wrap items-center gap-3">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('status') && request('status') !== 'all')
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif

                        <!-- Package Dropdown -->
                        <select
                            name="package"
                            onchange="this.form.submit()"
                            class="rounded-full border border-[#E5E7EB] bg-white py-2 px-3 text-xs font-inter text-[#16151A] shadow-xs focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] cursor-pointer"
                        >
                            <option value="all" {{ !request('package') || request('package') === 'all' ? 'selected' : '' }}>Semua Paket</option>
                            @foreach ($packages as $pkg)
                                <option value="{{ $pkg->id }}" {{ request('package') == $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Sort Dropdown -->
                        <select
                            name="sort"
                            onchange="this.form.submit()"
                            class="rounded-full border border-[#E5E7EB] bg-white py-2 px-3 text-xs font-inter text-[#16151A] shadow-xs focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] cursor-pointer"
                        >
                            <option value="latest" {{ !request('sort') || request('sort') === 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Urutkan: Terlama</option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Urutkan: Nama A-Z</option>
                            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Urutkan: Nama Z-A</option>
                            <option value="expiring_soon" {{ request('sort') === 'expiring_soon' ? 'selected' : '' }}>Urutkan: Segera Kedaluwarsa</option>
                        </select>

                        @if(request('search') || (request('package') && request('package') !== 'all') || (request('status') && request('status') !== 'all') || (request('sort') && request('sort') !== 'latest'))
                            <a
                                href="{{ route('admin.members.index') }}"
                                class="inline-flex items-center gap-1.5 rounded-full border border-red-200 bg-red-50 px-3.5 py-2 text-xs font-semibold text-[#ED1B45] hover:bg-red-100 transition shadow-xs"
                                title="Kembalikan semua filter ke tampilan awal"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>Reset Filter</span>
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Active Filters Chip Bar -->
            @php
                $activePackage = $packages->firstWhere('id', request('package'));
                $hasActiveFilters = request('search') ||
                    (request('status') && request('status') !== 'all') ||
                    (request('package') && request('package') !== 'all') ||
                    (request('sort') && request('sort') !== 'latest');
            @endphp

            @if($hasActiveFilters)
                <div class="flex flex-wrap items-center gap-2 rounded-xl bg-gray-50 border border-[#E5E7EB] p-2.5 px-4 text-xs font-inter text-[#565A66]">
                    <span class="font-semibold text-[#16151A] flex items-center gap-1.5 mr-1">
                        <svg class="h-4 w-4 text-[#ED1B45]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Aktif:
                    </span>

                    @if(request('search'))
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-[#E5E7EB] px-3 py-1 text-xs text-[#16151A] shadow-xs">
                            <span class="text-gray-400">Pencarian:</span>
                            <span class="font-semibold text-[#ED1B45]">"{{ request('search') }}"</span>
                            <a href="{{ route('admin.members.index', request()->except(['search', 'page'])) }}" class="text-gray-400 hover:text-red-500 ml-0.5" title="Hapus pencarian ini">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                        </span>
                    @endif

                    @if(request('status') && request('status') !== 'all')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-[#E5E7EB] px-3 py-1 text-xs text-[#16151A] shadow-xs">
                            <span class="text-gray-400">Status:</span>
                            <span class="font-semibold">
                                @if(request('status') === 'active') Aktif
                                @elseif(request('status') === 'expiring') Akan Berakhir (<7 Hari)
                                @elseif(request('status') === 'inactive') Nonaktif
                                @else {{ ucfirst(request('status')) }}
                                @endif
                            </span>
                            <a href="{{ route('admin.members.index', request()->except(['status', 'page'])) }}" class="text-gray-400 hover:text-red-500 ml-0.5" title="Hapus filter status ini">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                        </span>
                    @endif

                    @if($activePackage)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-[#E5E7EB] px-3 py-1 text-xs text-[#16151A] shadow-xs">
                            <span class="text-gray-400">Paket:</span>
                            <span class="font-semibold text-blue-600">{{ $activePackage->name }}</span>
                            <a href="{{ route('admin.members.index', request()->except(['package', 'page'])) }}" class="text-gray-400 hover:text-red-500 ml-0.5" title="Hapus filter paket ini">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                        </span>
                    @endif

                    @if(request('sort') && request('sort') !== 'latest')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white border border-[#E5E7EB] px-3 py-1 text-xs text-[#16151A] shadow-xs">
                            <span class="text-gray-400">Urutan:</span>
                            <span class="font-semibold">
                                @if(request('sort') === 'oldest') Terlama
                                @elseif(request('sort') === 'name_asc') Nama A-Z
                                @elseif(request('sort') === 'name_desc') Nama Z-A
                                @elseif(request('sort') === 'expiring_soon') Segera Kedaluwarsa
                                @else {{ request('sort') }}
                                @endif
                            </span>
                            <a href="{{ route('admin.members.index', request()->except(['sort', 'page'])) }}" class="text-gray-400 hover:text-red-500 ml-0.5" title="Hapus urutan ini">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                        </span>
                    @endif

                    <a
                        href="{{ route('admin.members.index') }}"
                        class="ml-auto inline-flex items-center gap-1 font-semibold text-[#ED1B45] hover:underline cursor-pointer"
                        title="Kembalikan semua filter ke awal"
                    >
                        <span>Hapus Semua Filter</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>
            @endif

            <!-- 4. Tabel Daftar Member -->
            <div class="rounded-2xl border border-[#E5E7EB] bg-white overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left font-inter text-sm">
                        <thead class="bg-gray-50/80 border-b border-[#E5E7EB] text-[11px] font-bold uppercase tracking-wider text-[#565A66]">
                            <tr>
                                <th class="px-6 py-4">MEMBER</th>
                                <th class="px-6 py-4">PAKET</th>
                                <th class="px-6 py-4">TANGGAL KEDALUWARSA</th>
                                <th class="px-6 py-4">STATUS</th>
                                <th class="px-6 py-4 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E7EB]">
                            @forelse ($members as $m)
                                @php
                                    $latest = $m->latestMembership;
                                    $pkg = $latest?->package;
                                    $isExpired = false;
                                    $isExpiring = false;
                                    $daysRemaining = null;
                                    $expiryText = '-';

                                    if ($latest && $latest->end_date) {
                                        $endDate = \Carbon\Carbon::parse($latest->end_date);
                                        $today = now()->startOfDay();

                                        if ($today->gt($endDate)) {
                                            $isExpired = true;
                                            $expiryText = $endDate->translatedFormat('d M Y');
                                        } else {
                                            $daysRemaining = (int) $today->diffInDays($endDate);
                                            if ($daysRemaining <= 7) {
                                                $isExpiring = true;
                                                $expiryText = $endDate->translatedFormat('d M Y') . " ({$daysRemaining} hari lagi)";
                                            } else {
                                                $expiryText = $endDate->translatedFormat('d M Y');
                                            }
                                        }
                                    }

                                    $memberCode = $m->memberProfile?->member_code ?? '#FC-'.str_pad((string)$m->id, 4, '0', STR_PAD_LEFT);
                                    $initials = collect(explode(' ', $m->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition">
                                    <!-- Member Info -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-200 text-xs font-bold text-[#16151A] ring-2 ring-gray-100">
                                                {{ $initials ?: 'MB' }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-[#16151A] leading-tight">
                                                    {{ $m->name }}
                                                </div>
                                                <div class="text-xs text-[#565A66] mt-0.5">
                                                    {{ $m->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Paket -->
                                    <td class="px-6 py-4">
                                        @if ($pkg)
                                            <span class="inline-block rounded-md bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800">
                                                {{ $pkg->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">Belum Ada Paket</span>
                                        @endif
                                    </td>

                                    <!-- Tanggal Kedaluwarsa -->
                                    <td class="px-6 py-4">
                                        @if ($isExpired)
                                            <span class="font-semibold text-rose-600">
                                                {{ $expiryText }}
                                            </span>
                                        @elseif ($isExpiring)
                                            <span class="font-semibold text-amber-500">
                                                {{ $expiryText }}
                                            </span>
                                        @else
                                            <span class="text-[#16151A]">
                                                {{ $expiryText }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="px-6 py-4">
                                        @if ($m->status === 'inactive' || $isExpired)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600 border border-rose-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                Nonaktif/Expired
                                            </span>
                                        @elseif ($isExpiring)
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-600 border border-amber-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Akan Berakhir
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 border border-emerald-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 text-right">
                                        <button
                                            type="button"
                                            x-on:click="openDetail({{ json_encode([
                                                'id' => $m->id,
                                                'name' => $m->name,
                                                'email' => $m->email,
                                                'phone' => $m->phone,
                                                'gender' => $m->memberProfile?->gender ?? 'Laki-laki',
                                                'address' => $m->memberProfile?->address,
                                                'status' => $m->status,
                                                'member_code' => $memberCode,
                                                'package_id' => $pkg?->id,
                                                'package_name' => $pkg?->name ?? 'Belum Ada',
                                                'end_date' => $latest?->end_date ? \Carbon\Carbon::parse($latest->end_date)->format('Y-m-d') : null,
                                            ]) }})"
                                            class="inline-flex items-center gap-1.5 rounded-full border border-[#E5E7EB] bg-white px-3.5 py-1.5 text-xs font-semibold text-[#16151A] shadow-xs transition hover:bg-gray-50 hover:text-[#ED1B45] cursor-pointer"
                                        >
                                            <svg class="h-3.5 w-3.5 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Lihat Detail</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-14 text-center text-[#565A66]">
                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-[#ED1B45] mb-3">
                                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        @if($hasActiveFilters)
                                            <p class="font-semibold text-base text-[#16151A]">Tidak ada data anggota ditemukan</p>
                                            <p class="text-xs text-[#565A66] mt-1.5 max-w-md mx-auto">
                                                Tidak ada member yang cocok dengan filter aktif
                                                @if(request('search'))
                                                    pencarian <span class="font-bold text-[#ED1B45]">"{{ request('search') }}"</span>
                                                @endif
                                                @if($activePackage)
                                                    pada paket <span class="font-bold text-blue-600">{{ $activePackage->name }}</span>
                                                @endif
                                                .
                                            </p>
                                            <div class="mt-4 flex flex-wrap items-center justify-center gap-3">
                                                @if(request('package') && request('search'))
                                                    <a
                                                        href="{{ route('admin.members.index', request()->except(['package', 'page'])) }}"
                                                        class="inline-flex items-center gap-1.5 rounded-full border border-[#E5E7EB] bg-white px-4 py-2 text-xs font-semibold text-[#16151A] hover:bg-gray-50 shadow-xs"
                                                    >
                                                        <span>Cari "{{ request('search') }}" di Semua Paket</span>
                                                    </a>
                                                @endif
                                                <a
                                                    href="{{ route('admin.members.index') }}"
                                                    class="inline-flex items-center gap-2 rounded-full bg-[#ED1B45] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#D1123D] transition"
                                                >
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    <span>Reset Semua Filter (Tampilkan Semua Member)</span>
                                                </a>
                                            </div>
                                        @else
                                            <p class="font-semibold text-sm text-[#16151A]">Belum ada data anggota</p>
                                            <p class="text-xs text-gray-400 mt-1">Belum ada anggota gym yang terdaftar di dalam sistem.</p>
                                            <button
                                                type="button"
                                                x-on:click="showAddModal = true"
                                                class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#ED1B45] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#D1123D]"
                                            >
                                                <span>+ Tambah Anggota Sekarang</span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- 5. Footer Pagination -->
                @if ($members->hasPages() || $members->total() > 0)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-t border-[#E5E7EB] px-6 py-4 gap-4">
                        <div class="text-xs text-[#565A66]">
                            Menampilkan <span class="font-bold text-[#16151A]">{{ $members->firstItem() ?? 0 }}-{{ $members->lastItem() ?? 0 }}</span> dari <span class="font-bold text-[#16151A]">{{ $members->total() }}</span> member
                        </div>
                        <div>
                            {{ $members->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL 1: DETAIL ANGGOTA (SLIDEOVER / MODAL)-->
        <!-- ========================================== -->
        <div
            x-cloak
            x-show="showDetailModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <!-- Backdrop -->
            <div
                x-show="showDetailModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-on:click="showDetailModal = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <div
                    x-show="showDetailModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full max-w-xl rounded-2xl bg-white text-left shadow-2xl transition-all border border-[#E5E7EB] overflow-hidden"
                >
                    <!-- Detail Header -->
                    <div class="p-6 border-b border-[#E5E7EB]">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-white bg-[#ED1B45] px-2.5 py-1 rounded-full font-inter">
                                PROFIL MEMBER FITCORE
                            </span>
                            <button
                                type="button"
                                x-on:click="showDetailModal = false"
                                class="rounded-full p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- User Profile Card in Header -->
                        <div class="flex items-center gap-4 mt-4">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-gray-200 text-lg font-bold text-[#16151A] ring-4 ring-gray-100">
                                <template x-if="selectedMember">
                                    <span x-text="selectedMember.name ? selectedMember.name.substring(0, 2).toUpperCase() : 'MB'"></span>
                                </template>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-poppins text-lg font-bold text-[#16151A]" x-text="selectedMember?.name"></h3>
                                <p class="text-xs text-[#565A66] flex items-center gap-2 mt-0.5">
                                    <span>ID Anggota: <strong class="text-[#16151A]" x-text="selectedMember?.member_code"></strong></span>
                                </p>
                                <div class="mt-2">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-3 py-0.5 text-xs font-semibold"
                                        :class="selectedMember?.status_color === 'emerald' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : (selectedMember?.status_color === 'amber' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-rose-50 text-rose-600 border border-rose-200')"
                                        x-text="selectedMember?.status_label || (selectedMember?.status === 'active' ? 'Aktif' : 'Nonaktif')"
                                    ></span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Contact & Edit Buttons -->
                        <div class="grid grid-cols-3 gap-2 mt-5">
                            <a
                                :href="'https://wa.me/' + (selectedMember?.phone ? selectedMember.phone.replace(/^0/, '62').replace(/[^0-9]/g, '') : '')"
                                target="_blank"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100"
                            >
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>WhatsApp</span>
                            </a>

                            <a
                                :href="'mailto:' + (selectedMember?.email || '')"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-100 px-3 py-2 text-xs font-semibold text-[#16151A] transition hover:bg-gray-200"
                            >
                                <svg class="h-4 w-4 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Email</span>
                            </a>

                            <button
                                type="button"
                                x-on:click="openEditFromDetail()"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#E5E7EB] bg-white px-3 py-2 text-xs font-semibold text-[#16151A] transition hover:bg-gray-50 hover:text-[#ED1B45] cursor-pointer"
                            >
                                <svg class="h-4 w-4 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <span>Ubah Data</span>
                            </button>
                        </div>
                    </div>

                    <!-- Detail Body -->
                    <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">

                        <!-- SECTION 1: PAKET & MASA BERLAKU -->
                        <div class="rounded-xl border border-[#E5E7EB] bg-gray-50/60 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-[#565A66]">
                                    PAKET &amp; MASA BERLAKU
                                </span>
                                <span class="text-[10px] font-extrabold bg-[#16151A] text-white px-2 py-0.5 rounded-md">
                                    ALL-ACCESS 24/7
                                </span>
                            </div>

                            <div class="mt-3 space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-[#565A66]">Paket Aktif:</span>
                                    <span class="font-bold text-[#16151A]" x-text="selectedMember?.package?.name || 'Belum Ada'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#565A66]">Bergabung Sejak:</span>
                                    <span class="font-medium text-[#16151A]" x-text="selectedMember?.package?.joined_date || '-'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#565A66]">Tanggal Berakhir:</span>
                                    <span class="font-bold text-amber-600" x-text="selectedMember?.package?.end_date || '-'"></span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-4">
                                <div class="flex justify-between text-[11px] font-medium text-[#565A66] mb-1.5">
                                    <span>Masa Aktif: <strong class="text-[#16151A]" x-text="(selectedMember?.package?.active_days || 0) + ' / ' + (selectedMember?.package?.total_days || 365) + ' Hari'"></strong></span>
                                    <span class="text-amber-600 font-bold" x-text="'Tersisa ' + (selectedMember?.package?.remaining_days || 0) + ' Hari'"></span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-gray-200 overflow-hidden">
                                    <div
                                        class="h-full bg-[#ED1B45] rounded-full transition-all duration-300"
                                        :style="'width: ' + (selectedMember?.package?.progress_percentage || 0) + '%'"
                                    ></div>
                                </div>
                            </div>

                            <!-- Perpanjang Button -->
                            <button
                                type="button"
                                x-on:click="openEditFromDetail()"
                                class="w-full mt-4 flex items-center justify-center gap-2 rounded-xl bg-[#ED1B45] py-2.5 text-xs font-semibold text-white transition hover:bg-[#D1123D]"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>Perpanjang / Renewal Sekarang</span>
                            </button>
                        </div>

                        <!-- SECTION 2: STATISTIK PRESENSI GYM -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#565A66]">
                                    STATISTIK PRESENSI GYM
                                </h4>
                                <span class="text-[11px] text-[#565A66]">Bulan Ini ({{ now()->translatedFormat('F Y') }})</span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="rounded-xl border border-[#E5E7EB] bg-gray-50/50 p-2.5">
                                    <span class="text-[10px] text-[#565A66] font-medium">Presensi</span>
                                    <div class="text-base font-extrabold text-[#16151A] mt-0.5" x-text="(selectedMember?.attendance?.monthly_count || 0) + 'x'"></div>
                                    <span class="text-[10px] text-emerald-600 font-bold">+3 vs lalu</span>
                                </div>
                                <div class="rounded-xl border border-[#E5E7EB] bg-gray-50/50 p-2.5">
                                    <span class="text-[10px] text-[#565A66] font-medium">Frekuensi</span>
                                    <div class="text-base font-extrabold text-[#16151A] mt-0.5" x-text="(selectedMember?.attendance?.weekly_avg || 0) + 'x'"></div>
                                    <span class="text-[10px] text-[#565A66]">/minggu</span>
                                </div>
                                <div class="rounded-xl border border-[#E5E7EB] bg-gray-50/50 p-2.5">
                                    <span class="text-[10px] text-[#565A66] font-medium">Jam Favorit</span>
                                    <div class="text-xs font-extrabold text-[#16151A] mt-1" x-text="selectedMember?.attendance?.favorite_hour || '18:30 WIB'"></div>
                                    <span class="text-[10px] text-[#565A66]">(Malam)</span>
                                </div>
                            </div>

                            <!-- 3 Kunjungan Terakhir -->
                            <div class="mt-4">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-[#565A66] mb-2">3 Kunjungan Terakhir</p>
                                <div class="space-y-2">
                                    <template x-for="ci in (selectedMember?.recent_checkins || [])" :key="ci.id || ci.time">
                                        <div class="flex items-center justify-between rounded-xl border border-[#E5E7EB] bg-white p-2.5 text-xs">
                                            <div class="flex items-center gap-2.5">
                                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-[#16151A]" x-text="ci.gate"></p>
                                                    <p class="text-[10px] text-[#565A66]" x-text="ci.time"></p>
                                                </div>
                                            </div>
                                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600 border border-emerald-200">
                                                Sukses
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: RIWAYAT TRANSAKSI -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#565A66]">
                                    RIWAYAT TRANSAKSI
                                </h4>
                                <svg class="h-4 w-4 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>

                            <div class="space-y-2">
                                <template x-for="trx in (selectedMember?.recent_transactions || [])" :key="trx.id || trx.invoice_id">
                                    <div class="rounded-xl border border-[#E5E7EB] bg-white p-3 text-xs">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-bold text-[#16151A]" x-text="trx.package_name"></p>
                                                <p class="text-[10px] text-[#565A66] mt-0.5" x-text="trx.invoice_id + ' • ' + trx.date"></p>
                                            </div>
                                            <div class="text-right">
                                                <span class="font-bold text-[#16151A]" x-text="trx.amount_formatted"></span>
                                            </div>
                                        </div>
                                        <div class="mt-2 flex items-center justify-between pt-2 border-t border-gray-100">
                                            <span class="text-[10px] text-[#565A66]" x-text="'Metode: ' + trx.method"></span>
                                            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-600 border border-emerald-200" x-text="trx.status"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- Detail Modal Footer Actions -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 border-t border-[#E5E7EB]">
                        <a
                            :href="'https://wa.me/' + (selectedMember?.phone ? selectedMember.phone.replace(/^0/, '62').replace(/[^0-9]/g, '') : '') + '?text=Halo%20' + encodeURIComponent(selectedMember?.name || '') + ',%20ini%20dari%20FitCore%20Gym%20mengingatkan%20mengenai%20status%20keanggotaan%20Anda.'"
                            target="_blank"
                            class="inline-flex items-center gap-1.5 rounded-full border border-[#E5E7EB] bg-white px-4 py-2 text-xs font-semibold text-[#16151A] hover:bg-gray-100 transition"
                        >
                            <svg class="h-4 w-4 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>Ingatkan via WA</span>
                        </a>

                        <div class="flex items-center gap-2">
                            <!-- Soft Delete / Deactivate Button -->
                            <button
                                type="button"
                                x-on:click="openDeleteFromDetail('soft')"
                                class="inline-flex items-center gap-1.5 rounded-full bg-amber-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-amber-600"
                            >
                                <span>Nonaktifkan</span>
                            </button>

                            <!-- Hard Delete Button -->
                            <button
                                type="button"
                                x-on:click="openDeleteFromDetail('hard')"
                                class="inline-flex items-center gap-1.5 rounded-full bg-[#ED1B45] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#D1123D]"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL 2: TAMBAH MEMBER (ADD NEW MEMBER)   -->
        <!-- ========================================== -->
        <div
            x-cloak
            x-show="showAddModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                x-show="showAddModal"
                x-transition.opacity
                x-on:click="showAddModal = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 text-center">
                <div
                    x-show="showAddModal"
                    x-transition
                    class="relative w-full max-w-lg rounded-2xl bg-white p-6 sm:p-8 text-left shadow-2xl transition-all border border-[#E5E7EB]"
                >
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-4">
                        <div>
                            <h3 class="font-poppins text-lg font-bold text-[#16151A]">Tambah Anggota Baru</h3>
                            <p class="text-xs text-[#565A66]">Daftarkan member baru dan aktifkan paket keanggotaannya.</p>
                        </div>
                        <button type="button" x-on:click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.members.store') }}" class="mt-4 space-y-4 font-inter text-xs">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block font-semibold text-[#16151A] mb-1">Nama Lengkap *</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="Contoh: Nama Lengkap Member"
                                class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                            >
                            @error('name') <p class="text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email & Telepon -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Email *</label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="nama@email.com"
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                                @error('email') <p class="text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Nomor Telepon (WhatsApp) *</label>
                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    placeholder="08123456789"
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                                @error('phone') <p class="text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Jenis Kelamin & Paket Membership -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Jenis Kelamin</label>
                                <select
                                    name="gender"
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Pilih Paket Membership</label>
                                <select
                                    name="membership_package_id"
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                                    <option value="">-- Tanpa Paket Langsung --</option>
                                    @foreach ($packages as $pkg)
                                        <option value="{{ $pkg->id }}">
                                            {{ $pkg->name }} ({{ $pkg->duration_days ?? 30 }} hari)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div>
                            <label class="block font-semibold text-[#16151A] mb-1">Tanggal Mulai Membership</label>
                            <input
                                type="date"
                                name="start_date"
                                value="{{ old('start_date', now()->format('Y-m-d')) }}"
                                class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                            >
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block font-semibold text-[#16151A] mb-1">Alamat (Opsional)</label>
                            <textarea
                                name="address"
                                rows="2"
                                placeholder="Alamat domisili anggota..."
                                class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                            >{{ old('address') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E5E7EB]">
                            <button
                                type="button"
                                x-on:click="showAddModal = false"
                                class="rounded-full bg-gray-100 px-5 py-2.5 text-xs font-semibold text-[#565A66] hover:bg-gray-200 transition"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="rounded-full bg-[#ED1B45] px-6 py-2.5 text-xs font-semibold text-white hover:bg-[#D1123D] transition shadow-xs"
                            >
                                Simpan Member Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL 3: UBAH DATA MEMBER (EDIT MEMBER)    -->
        <!-- ========================================== -->
        <div
            x-cloak
            x-show="showEditModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                x-show="showEditModal"
                x-transition.opacity
                x-on:click="showEditModal = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 text-center">
                <div
                    x-show="showEditModal"
                    x-transition
                    class="relative w-full max-w-lg rounded-2xl bg-white p-6 sm:p-8 text-left shadow-2xl transition-all border border-[#E5E7EB]"
                >
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-4">
                        <div>
                            <h3 class="font-poppins text-lg font-bold text-[#16151A]">Ubah Data Anggota</h3>
                            <p class="text-xs text-[#565A66]">Perbarui informasi profil dan paket keanggotaan.</p>
                        </div>
                        <button type="button" x-on:click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <form
                        method="POST"
                        :action="'{{ url('/admin/member-management') }}/' + (selectedMember?.id || '')"
                        class="mt-4 space-y-4 font-inter text-xs"
                    >
                        @csrf
                        @method('PUT')

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block font-semibold text-[#16151A] mb-1">Nama Lengkap *</label>
                            <input
                                type="text"
                                name="name"
                                :value="selectedMember?.name"
                                required
                                class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                            >
                        </div>

                        <!-- Email & Telepon -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Email *</label>
                                <input
                                    type="email"
                                    name="email"
                                    :value="selectedMember?.email"
                                    required
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                            </div>
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Nomor Telepon *</label>
                                <input
                                    type="text"
                                    name="phone"
                                    :value="selectedMember?.phone"
                                    required
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                            </div>
                        </div>

                        <!-- Status & Jenis Kelamin -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Status Keanggotaan</label>
                                <select
                                    name="status"
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                                    <option value="active" :selected="selectedMember?.status === 'active'">Aktif</option>
                                    <option value="inactive" :selected="selectedMember?.status === 'inactive'">Nonaktif</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Jenis Kelamin</label>
                                <select
                                    name="gender"
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                                    <option value="Laki-laki" :selected="selectedMember?.gender === 'Laki-laki'">Laki-laki</option>
                                    <option value="Perempuan" :selected="selectedMember?.gender === 'Perempuan'">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Paket Membership & Tanggal Kedaluwarsa -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Paket Membership</label>
                                <select
                                    name="membership_package_id"
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                                    <option value="">-- Tetap / Tidak Ada --</option>
                                    @foreach ($packages as $pkg)
                                        <option value="{{ $pkg->id }}" :selected="selectedMember?.package_id == {{ $pkg->id }}">
                                            {{ $pkg->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-[#16151A] mb-1">Tanggal Kedaluwarsa</label>
                                <input
                                    type="date"
                                    name="end_date"
                                    :value="selectedMember?.end_date"
                                    class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2.5 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                                >
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block font-semibold text-[#16151A] mb-1">Alamat</label>
                            <textarea
                                name="address"
                                rows="2"
                                :value="selectedMember?.address"
                                class="w-full rounded-xl border border-[#E5E7EB] px-3.5 py-2 text-xs text-[#16151A] focus:border-[#ED1B45] focus:ring-1 focus:ring-[#ED1B45] outline-none"
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#E5E7EB]">
                            <button
                                type="button"
                                x-on:click="showEditModal = false"
                                class="rounded-full bg-gray-100 px-5 py-2.5 text-xs font-semibold text-[#565A66] hover:bg-gray-200 transition"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="rounded-full bg-[#ED1B45] px-6 py-2.5 text-xs font-semibold text-white hover:bg-[#D1123D] transition shadow-xs"
                            >
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL 4: KONFIRMASI PENGHAPUSAN           -->
        <!-- (SOFT DELETE / NONAKTIF VS HARD DELETE)    -->
        <!-- ========================================== -->
        <div
            x-cloak
            x-show="showDeleteModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                x-show="showDeleteModal"
                x-transition.opacity
                x-on:click="showDeleteModal = false"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 text-center">
                <div
                    x-show="showDeleteModal"
                    x-transition
                    class="relative w-full max-w-md rounded-2xl bg-white p-6 sm:p-8 text-left shadow-2xl transition-all border border-[#E5E7EB]"
                >
                    <!-- Soft Delete Modal Content -->
                    <template x-if="deleteType === 'soft'">
                        <div>
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 text-amber-500">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                            <div class="mt-4 text-center">
                                <h3 class="font-poppins text-lg font-bold text-[#16151A]">Konfirmasi Nonaktifkan Akun</h3>
                                <p class="text-xs text-[#565A66] mt-2 leading-relaxed">
                                    Apakah Anda yakin ingin menonaktifkan akun member <strong class="text-[#16151A]" x-text="selectedMember?.name"></strong>?
                                    <br>Status anggota akan diubah menjadi non-aktif tanpa menghapus riwayat datanya dari basis data (Soft Delete).
                                </p>
                            </div>
                            <form method="POST" :action="'{{ url('/admin/member-management') }}/' + (selectedMember?.id || '') + '/deactivate'" class="mt-6 flex flex-col sm:flex-row gap-3">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="button"
                                    x-on:click="showDeleteModal = false"
                                    class="w-full rounded-full bg-gray-100 py-2.5 text-xs font-semibold text-[#565A66] hover:bg-gray-200 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="w-full rounded-full bg-amber-500 py-2.5 text-xs font-semibold text-white hover:bg-amber-600 transition shadow-xs"
                                >
                                    Ya, Nonaktifkan Akun
                                </button>
                            </form>
                        </div>
                    </template>

                    <!-- Hard Delete Modal Content -->
                    <template x-if="deleteType === 'hard'">
                        <div>
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-[#ED1B45]">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div class="mt-4 text-center">
                                <h3 class="font-poppins text-lg font-bold text-rose-600">Hapus Permanen Anggota</h3>
                                <p class="text-xs text-[#565A66] mt-2 leading-relaxed">
                                    PERINGATAN: Anda akan menghapus data <strong class="text-[#16151A]" x-text="selectedMember?.name"></strong> secara permanen. Seluruh data akun, profil, dan riwayat presensi akan dihapus dari basis data (Hard Delete) dan <strong>tidak dapat dikembalikan</strong>.
                                </p>
                            </div>
                            <form method="POST" :action="'{{ url('/admin/member-management') }}/' + (selectedMember?.id || '')" class="mt-6 flex flex-col sm:flex-row gap-3">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="button"
                                    x-on:click="showDeleteModal = false"
                                    class="w-full rounded-full bg-gray-100 py-2.5 text-xs font-semibold text-[#565A66] hover:bg-gray-200 transition"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="w-full rounded-full bg-[#ED1B45] py-2.5 text-xs font-semibold text-white hover:bg-[#D1123D] transition shadow-xs"
                                >
                                    Hapus Permanen
                                </button>
                            </form>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
