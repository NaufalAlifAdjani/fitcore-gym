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
    <section x-transition class="w-full max-w-lg rounded-[20px] bg-white p-6 shadow-2xl sm:p-8">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600" aria-hidden="true">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><path d="M12 8v5m0 3h.01M10.3 3.9 2.7 17a2 2 0 0 0 1.7 3h15.2a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
        </div>
        <h2 id="reject-payment-title" class="mt-4 font-poppins text-xl font-bold text-[#16151A]">Tolak Pembayaran</h2>
        <p class="mt-2 font-inter text-sm leading-6 text-[#565A66]">Pilih alasan cepat atau jelaskan alasan penolakan agar member dapat memperbaiki pembayaran.</p>

        <div class="mt-5 flex flex-wrap gap-2">
            @foreach (['Bukti Buram / Tidak Jelas', 'Nominal Transfer Kurang', 'Nama Rekening Beda'] as $reason)
                <button
                    type="button"
                    x-on:click="useQuickReason(@js($reason))"
                    class="rounded-full border border-[#E5E7EB] px-3.5 py-1.5 font-inter text-xs text-[#565A66] hover:border-rose-300 hover:bg-rose-50 hover:text-rose-700 transition cursor-pointer"
                >
                    {{ $reason }}
                </button>
            @endforeach
        </div>

        <form
            x-bind:action="(rejectUrlTemplate || '').replace('__PAYMENT_ID__', selectedPayment?.id || '')"
            method="POST"
            @submit.prevent="reject(selectedPayment?.id, $event)"
        >
            @csrf
            <label for="payment-rejection-reason" class="block mt-5 mb-2 font-inter text-xs font-semibold text-[#16151A]">Alasan penolakan</label>
            <textarea
                id="payment-rejection-reason"
                name="reason"
                x-model="rejectionReason"
                rows="4"
                maxlength="2000"
                required
                placeholder="Tuliskan alasan yang akan disampaikan kepada member..."
                class="w-full rounded-[16px] border border-[#E5E7EB] bg-[#F6F6F7] px-4 py-3 text-sm font-inter outline-none transition focus:border-rose-500 focus:bg-white focus:ring-1 focus:ring-rose-500"
            ></textarea>

            <p x-show="actionError" x-cloak role="alert" class="mt-2 font-inter text-xs font-medium text-rose-700" x-text="actionError"></p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <x-button
                    type="button"
                    variant="secondary"
                    @click="showRejectModal = false; actionError = ''"
                    x-bind:disabled="isSubmitting"
                    class="!rounded-full !px-5 !py-2.5 !text-sm font-inter font-semibold"
                >
                    Batal
                </x-button>
                <x-button
                    type="submit"
                    variant="primary"
                    x-bind:disabled="isSubmitting"
                    class="!rounded-full !bg-[#ED1B45] hover:!bg-[#D1123D] !px-6 !py-2.5 !text-sm font-inter font-semibold disabled:cursor-wait transition shadow-sm"
                >
                    <span x-text="isSubmitting ? 'Memproses...' : 'Konfirmasi Penolakan'">Konfirmasi Penolakan</span>
                </x-button>
            </div>
        </form>
    </section>
</div>
