<form
    method="GET"
    action="{{ route('member.riwayat-transaksi.index') }}"
    id="transaction-filter-form"
    class="bg-white rounded-2xl p-4 sm:p-5 shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-[#E5E7EB] mb-8"
>
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- 1. Pencarian Input Teks di Kiri -->
        <div class="relative flex-1 min-w-[240px] max-w-md">
            <label for="search-input" class="sr-only">Cari nomor invoice atau nama paket</label>
            <div class="relative">
                <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[#565A66]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                        <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </span>
                <input
                    id="search-input"
                    type="search"
                    name="search"
                    value="{{ $currentSearch }}"
                    placeholder="Cari invoice / nama paket..."
                    class="w-full rounded-full border border-[#E5E7EB] bg-[#F9FAFB] pl-10 pr-10 py-2.5 text-xs sm:text-sm font-inter text-[#16151A] placeholder-[#565A66] focus:border-[#ED1B45] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
                />
                @if ($currentSearch)
                    <a
                        href="{{ route('member.riwayat-transaksi.index', array_filter(['status' => $currentStatus !== 'all' ? $currentStatus : null, 'year' => $currentYear !== 'all' ? $currentYear : null])) }}"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#565A66] hover:text-[#16151A] transition text-sm"
                        title="Hapus kata kunci"
                    >
                        &times;
                    </a>
                @endif
            </div>
        </div>

        <!-- 2. Tab Status (Pill Aktif #ED1B45, Inaktif Transparan) di Tengah -->
        <input type="hidden" name="status" id="status-filter-input" value="{{ $currentStatus }}">

        <div class="flex items-center overflow-x-auto p-1 bg-[#F6F6F7] rounded-full border border-[#E5E7EB] self-start lg:self-center shrink-0">
            <!-- Tab: Semua -->
            <button
                type="button"
                onclick="setStatusFilter('all')"
                @class([
                    'rounded-full px-4 py-2 text-xs font-inter font-semibold transition-all duration-200 cursor-pointer whitespace-nowrap',
                    'bg-[#ED1B45] text-white shadow-sm' => $currentStatus === 'all' || !$currentStatus,
                    'text-[#565A66] hover:text-[#16151A] bg-transparent' => $currentStatus !== 'all' && $currentStatus,
                ])
            >
                Semua
            </button>

            <!-- Tab: Menunggu Verifikasi -->
            <button
                type="button"
                onclick="setStatusFilter('pending')"
                @class([
                    'rounded-full px-4 py-2 text-xs font-inter font-semibold transition-all duration-200 cursor-pointer whitespace-nowrap',
                    'bg-[#ED1B45] text-white shadow-sm' => $currentStatus === 'pending',
                    'text-[#565A66] hover:text-[#16151A] bg-transparent' => $currentStatus !== 'pending',
                ])
            >
                Menunggu Verifikasi
            </button>

            <!-- Tab: Berhasil -->
            <button
                type="button"
                onclick="setStatusFilter('verified')"
                @class([
                    'rounded-full px-4 py-2 text-xs font-inter font-semibold transition-all duration-200 cursor-pointer whitespace-nowrap',
                    'bg-[#ED1B45] text-white shadow-sm' => $currentStatus === 'verified',
                    'text-[#565A66] hover:text-[#16151A] bg-transparent' => $currentStatus !== 'verified',
                ])
            >
                Berhasil
            </button>
        </div>

        <!-- 3. Dropdown Tahun di Kanan -->
        <div class="flex items-center gap-2 self-end lg:self-center shrink-0">
            <div class="relative">
                <label for="year-select" class="sr-only">Pilih Tahun</label>
                <select
                    id="year-select"
                    name="year"
                    onchange="this.form.submit()"
                    class="appearance-none rounded-full border border-[#E5E7EB] bg-white pl-4 pr-9 py-2 text-xs font-inter font-semibold text-[#16151A] shadow-sm hover:border-[#ED1B45]/50 focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] cursor-pointer transition"
                >
                    <option value="all" @selected($currentYear === 'all')>Semua Tahun</option>
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}" @selected($currentYear == $year)>Tahun {{ $year }}</option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#565A66]">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </span>
            </div>

            <!-- Tombol Submit Pencarian -->
            <button
                type="submit"
                class="bg-[#ED1B45] hover:bg-[#D1123D] text-white px-4 py-2 rounded-full font-inter font-semibold text-xs transition shadow-sm cursor-pointer"
            >
                Cari
            </button>

            <!-- Tombol Reset jika filter aktif -->
            @if ($currentSearch || ($currentStatus && $currentStatus !== 'all') || ($currentYear && $currentYear !== 'all'))
                <a
                    href="{{ route('member.riwayat-transaksi.index') }}"
                    class="rounded-full border border-[#E5E7EB] bg-[#F6F6F7] hover:bg-[#E5E7EB] text-[#565A66] hover:text-[#16151A] px-3.5 py-2 font-inter font-semibold text-xs transition"
                    title="Reset semua filter"
                >
                    Reset
                </a>
            @endif
        </div>
    </div>
</form>

<script>
    function setStatusFilter(status) {
        document.getElementById('status-filter-input').value = status;
        document.getElementById('transaction-filter-form').submit();
    }
</script>
