<div class="receipt-print-area overflow-hidden rounded-2xl border border-[#EEEEEF] bg-[#F9F9FA]">
    <template x-if="selectedPayment?.proof_image_url">
        <img
            x-bind:src="selectedPayment.proof_image_url"
            x-bind:alt="`Bukti transfer ${selectedPayment.invoice_id}`"
            class="max-h-[390px] min-h-52 w-full object-contain"
        >
    </template>
    <div x-show="!selectedPayment?.proof_image_url" class="flex min-h-52 items-center justify-center px-6 text-center text-sm text-[#858894]">
        Bukti transfer tidak tersedia.
    </div>
</div>

<div class="flex flex-wrap gap-2">
    <button type="button" x-on:click="showReceiptLightbox = true" x-bind:disabled="!selectedPayment?.proof_image_url" class="rounded-full border border-[#E5E5E8] px-4 py-2 text-xs font-semibold text-[#565A66] hover:bg-[#F9F9FA] disabled:opacity-50">
        Perbesar
    </button>
    <a x-bind:href="selectedPayment?.proof_image_url || '#'" x-bind:download="selectedPayment?.invoice_id || 'bukti-transfer'" class="rounded-full border border-[#E5E5E8] px-4 py-2 text-xs font-semibold text-[#565A66] hover:bg-[#F9F9FA]">
        Unduh Gambar
    </a>
    <button type="button" x-on:click="printReceipt()" x-bind:disabled="!selectedPayment?.proof_image_url" class="rounded-full border border-[#E5E5E8] px-4 py-2 text-xs font-semibold text-[#565A66] hover:bg-[#F9F9FA] disabled:opacity-50">
        Cetak Slip
    </button>
</div>

<div
    x-cloak
    x-show="showReceiptLightbox"
    x-transition.opacity
    x-on:click.self="showReceiptLightbox = false"
    class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/90 p-4 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-label="Pratinjau bukti transfer"
>
    <button type="button" x-on:click="showReceiptLightbox = false" class="absolute right-5 top-5 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20">
        Tutup
    </button>
    <img
        x-bind:src="selectedPayment?.proof_image_url || ''"
        x-bind:alt="`Bukti transfer ${selectedPayment?.invoice_id || ''}`"
        class="max-h-[90vh] max-w-full rounded-xl object-contain shadow-2xl"
    >
</div>
