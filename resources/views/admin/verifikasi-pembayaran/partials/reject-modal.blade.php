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
                <button
                    type="button"
                    x-on:click="useQuickReason(@js($reason))"
                    class="rounded-full border border-[#E5E5E8] px-3 py-2 text-xs font-semibold text-[#565A66] transition hover:border-rose-300 hover:bg-rose-50 hover:text-rose-700"
                >
                    {{ $reason }}
                </button>
            @endforeach
        </div>

        <label for="payment-rejection-reason" class="mt-5 block text-xs font-semibold text-[#565A66]">Alasan penolakan</label>
        <textarea
            id="payment-rejection-reason"
            x-model="rejectionReason"
            rows="4"
            maxlength="2000"
            required
            placeholder="Tuliskan alasan yang akan disampaikan kepada member..."
            class="mt-2 w-full rounded-2xl border-[#E5E5E8] px-4 py-3 text-sm focus:border-rose-500 focus:ring-rose-500"
        ></textarea>

        <p x-show="actionError" x-cloak role="alert" class="mt-2 text-xs font-medium text-rose-700" x-text="actionError"></p>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <button
                type="button"
                x-on:click="showRejectModal = false; actionError = ''"
                x-bind:disabled="isSubmitting"
                class="rounded-full bg-[#EEEEEF] px-5 py-3 text-sm font-semibold text-[#565A66] hover:bg-[#E4E4E7] disabled:opacity-60"
            >
                Batal
            </button>
            <button
                type="button"
                x-on:click="reject()"
                x-bind:disabled="isSubmitting"
                class="rounded-full bg-[#BA0030] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#970027] disabled:cursor-wait disabled:opacity-60"
            >
                <span x-text="isSubmitting ? 'Memproses...' : 'Konfirmasi Penolakan'"></span>
            </button>
        </div>
    </section>
</div>
