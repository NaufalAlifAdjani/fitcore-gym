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
            <x-text-input
                id="payment-search"
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari invoice / nama member..."
                class="h-[42px] pl-10 pr-3.5"
            />
        </div>
    </div>

    <div class="flex flex-col gap-1.5 lg:w-48 lg:shrink-0">
        <label for="payment-status" class="sr-only">Filter status</label>
        <x-select-input id="payment-status" name="status" class="h-[42px]">
            <option value="">Semua Status</option>
            <option value="pending" @selected(request('status') === 'pending')>Menunggu Verifikasi</option>
            <option value="verified" @selected(request('status') === 'verified')>Terverifikasi</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
        </x-select-input>
    </div>

    <div class="flex flex-col gap-1.5 lg:w-40 lg:shrink-0">
        <x-input-label for="payment-start-date" value="Dari Tanggal" class="px-1 !text-[10px] font-bold uppercase tracking-wider text-gray-500 !mb-0" />
        <x-text-input
            id="payment-start-date"
            type="date"
            name="start_date"
            value="{{ request('start_date') }}"
            class="h-[42px]"
        />
    </div>

    <div class="flex flex-col gap-1.5 lg:w-40 lg:shrink-0">
        <x-input-label for="payment-end-date" value="Sampai Tanggal" class="px-1 !text-[10px] font-bold uppercase tracking-wider text-gray-500 !mb-0" />
        <x-text-input
            id="payment-end-date"
            type="date"
            name="end_date"
            value="{{ request('end_date') }}"
            class="h-[42px]"
        />
    </div>

    <div class="flex gap-2 lg:shrink-0">
        <x-button type="submit" variant="primary" class="h-[42px] flex-1 rounded-xl px-5 lg:flex-none">
            Terapkan
        </x-button>
        @if (request()->hasAny(['search', 'status', 'start_date', 'end_date']))
            <x-button href="{{ route('admin.payments.index') }}" variant="secondary" class="h-[42px] rounded-xl px-4">
                Reset
            </x-button>
        @endif
    </div>
</form>
