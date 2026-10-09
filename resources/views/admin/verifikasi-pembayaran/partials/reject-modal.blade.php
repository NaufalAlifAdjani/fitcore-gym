<div
    x-cloak
    x-show="showRejectModal"
    x-transition.opacity
    x-on:click.self="showRejectModal = false; actionError = ''"
    class="fixed inset-0 z-[65] flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-labelledby="reject-payment-title"
>
    <section x-transition class="w-full max-w-lg rounded-[24px] bg-white p-6 shadow-2xl sm:p-8">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600" aria-hidden="true">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M12 8v5m0 3h.01M10.3 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </div>
        <h2 id="reject-payment-title" class="mt-4 font-heading text-xl font-bold text-[#16151A]">Tolak Pembayaran</h2>
        <p class="mt-2 text-sm leading-6 text-[#565A66]">Pilih alasan cepat atau jelaskan alasan penolakan agar member dapat memperbaiki pembayaran.</p>

        <div class="mt-5 flex flex-wrap gap-2">
            @foreach (['Bukti Buram / Tidak Jelas', 'Nominal Transfer Kurang', 'Nama Rekening Beda'] as $reason)
                <x-button
                    type="button"
                    variant="outline"
                    size="xs"
                    x-on:click="useQuickReason(@js($reason))"
                    class="!rounded-full hover:border-rose-300 hover:bg-rose-50 hover:text-rose-700"
                >
                    {{ $reason }}
                </x-button>
            @endforeach
        </div>

        <x-input-label for="payment-rejection-reason" value="Alasan penolakan" class="mt-5 !mb-2" />
        <textarea
            id="payment-rejection-reason"
            x-model="rejectionReason"
            rows="4"
            maxlength="2000"
            required
            placeholder="Tuliskan alasan yang akan disampaikan kepada member..."
            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm outline-none transition focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-200"
        ></textarea>

        <p x-show="actionError" x-cloak role="alert" class="mt-2 text-xs font-medium text-rose-700" x-text="actionError"></p>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <x-button
                type="button"
                variant="secondary"
                x-on:click="showRejectModal = false; actionError = ''"
                x-bind:disabled="isSubmitting"
                class="!px-5 !py-3 !text-sm"
            >
                Batal
            </x-button>
            <x-button
                type="button"
                variant="primary"
                x-on:click="reject()"
                x-bind:disabled="isSubmitting"
                class="!px-5 !py-3 !text-sm disabled:cursor-wait"
            >
                <span x-text="isSubmitting ? 'Memproses...' : 'Konfirmasi Penolakan'"></span>
            </x-button>
        </div>
    </section>
</div>
