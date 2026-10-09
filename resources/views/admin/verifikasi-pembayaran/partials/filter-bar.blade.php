<form
    method="GET"
    action="{{ route('admin.payments.index') }}"
    class="mb-6 flex w-full flex-col items-stretch gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm lg:flex-row lg:items-end"
>
    <div class="flex min-w-0 flex-1 flex-col gap-1.5 lg:min-w-[220px]">
        <label for="payment-search" class="sr-only">Cari invoice atau nama/email member</label>
        <div class="relative">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8" />
                <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
            <input
                id="payment-search"
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari invoice / nama member..."
                class="h-[42px] w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-3.5 text-xs font-medium text-gray-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-200"
            >
        </div>
    </div>

    <div class="flex flex-col gap-1.5 lg:w-48 lg:shrink-0">
        <label for="payment-status" class="sr-only">Filter status</label>
        <select id="payment-status" name="status" class="h-[42px] w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs font-medium text-gray-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-200">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status') === 'pending')>Menunggu Verifikasi</option>
            <option value="verified" @selected(request('status') === 'verified')>Terverifikasi</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
        </select>
    </div>

    <div class="flex flex-col gap-1.5 lg:w-40 lg:shrink-0">
        <label for="payment-start-date" class="px-1 text-[10px] font-bold uppercase tracking-wider text-gray-500">Dari Tanggal</label>
        <input
            id="payment-start-date"
            type="date"
            name="start_date"
            value="{{ request('start_date') }}"
            class="h-[42px] w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs font-medium text-gray-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-200"
        >
    </div>

    <div class="flex flex-col gap-1.5 lg:w-40 lg:shrink-0">
        <label for="payment-end-date" class="px-1 text-[10px] font-bold uppercase tracking-wider text-gray-500">Sampai Tanggal</label>
        <input
            id="payment-end-date"
            type="date"
            name="end_date"
            value="{{ request('end_date') }}"
            class="h-[42px] w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs font-medium text-gray-800 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-200"
        >
    </div>

    <div class="flex gap-2 lg:shrink-0">
        <button type="submit" class="h-[42px] flex-1 rounded-xl bg-[#BA0030] px-5 text-xs font-semibold text-white transition hover:bg-[#970027] lg:flex-none">
            Terapkan
        </button>
        @if (request()->hasAny(['search', 'status', 'start_date', 'end_date']))
            <a href="{{ route('admin.payments.index') }}" class="inline-flex h-[42px] items-center justify-center rounded-xl bg-[#EEEEEF] px-4 text-xs font-semibold text-[#565A66] transition hover:bg-[#E4E4E7]">
                Reset
            </a>
        @endif
    </div>
</form>
