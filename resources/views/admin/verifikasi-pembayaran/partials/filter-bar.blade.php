<form
    method="GET"
    action="{{ route('admin.payments.index') }}"
    class="bg-white rounded-[16px] shadow-[0_4px_12px_rgba(0,0,0,0.06)] p-6 mb-6 flex w-full flex-col gap-4 md:flex-row md:items-center"
>
    <!-- Input Search (flex-1) -->
    <div class="relative flex-1 min-w-0">
        <label for="payment-search" class="sr-only">Cari invoice atau nama/email member</label>
        <div class="relative">
            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[#565A66]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                    <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
            </span>
            <input
                id="payment-search"
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari invoice / nama member..."
                class="w-full rounded-full border border-[#E5E7EB] bg-white pl-10 pr-4 py-2.5 text-sm font-inter text-[#16151A] placeholder-[#565A66] focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] transition"
            />
        </div>
    </div>

    <!-- Dropdown Status -->
    <div class="relative w-full md:w-48 shrink-0">
        <label for="payment-status" class="sr-only">Filter status</label>
        <div class="relative">
            <select
                id="payment-status"
                name="status"
                class="w-full appearance-none rounded-full border border-[#E5E7EB] bg-white pl-4 pr-10 py-2.5 text-sm font-inter font-medium text-[#16151A] shadow-sm hover:border-[#ED1B45]/40 focus:border-[#ED1B45] focus:outline-none focus:ring-1 focus:ring-[#ED1B45] cursor-pointer transition"
            >
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') === 'pending')>Menunggu Verifikasi</option>
                <option value="verified" @selected(request('status') === 'verified')>Terverifikasi</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
            </select>
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#565A66]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </span>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="flex items-center gap-2 shrink-0">
        <button
            type="submit"
            class="bg-[#ED1B45] text-white px-6 py-2.5 rounded-full font-inter font-semibold shadow-sm hover:bg-[#D1123D] transition shrink-0 cursor-pointer"
        >
            Terapkan
        </button>
        @if (request()->hasAny(['search', 'status']))
            <a
                href="{{ route('admin.payments.index') }}"
                class="rounded-full border border-[#E5E7EB] bg-white px-5 py-2.5 font-inter text-sm font-semibold text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition shrink-0 inline-flex items-center justify-center cursor-pointer"
            >
                Reset
            </a>
        @endif
    </div>
</form>
