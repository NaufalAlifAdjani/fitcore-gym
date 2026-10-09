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
    <x-button
        type="button"
        variant="outline"
        size="xs"
        x-on:click="showReceiptLightbox = true"
        x-bind:disabled="!selectedPayment?.proof_image_url"
        class="!rounded-full"
    >
        Perbesar
    </x-button>
    <x-button
        variant="outline"
        size="xs"
        x-bind:href="selectedPayment?.proof_image_url || '#'"
        x-bind:download="selectedPayment?.invoice_id || 'bukti-transfer'"
        class="!rounded-full"
    >
        Unduh Gambar
    </x-button>
    <x-button
        type="button"
        variant="outline"
        size="xs"
        x-on:click="printReceipt()"
        x-bind:disabled="!selectedPayment?.proof_image_url"
        class="!rounded-full"
    >
        Cetak Slip
    </x-button>
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
    <x-button
        type="button"
        variant="dark"
        size="xs"
        x-on:click="showReceiptLightbox = false"
        class="absolute right-5 top-5 !bg-white/10 hover:!bg-white/20 text-white"
    >
        Tutup
    </x-button>
    <img
        x-bind:src="selectedPayment?.proof_image_url || ''"
        x-bind:alt="`Bukti transfer ${selectedPayment?.invoice_id || ''}`"
        class="max-h-[90vh] max-w-full rounded-xl object-contain shadow-2xl"
    >
</div>
