<x-app-layout>
    <section
        x-data="paymentVerification({
            approveUrlTemplate: @js(route('admin.payments.approve', ['id' => '__PAYMENT_ID__'])),
            rejectUrlTemplate: @js(route('admin.payments.reject', ['id' => '__PAYMENT_ID__'])),
        })"
        x-on:keydown.escape.window="closeModals()"
        class="min-h-screen bg-[#F9F9FA] px-4 py-8 text-[#16151A] sm:px-6 lg:px-10"
    >
        <div class="mx-auto max-w-[1600px]">
            <header class="mb-8">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#BA0030]">Administrasi Gym</p>
                <h1 class="mt-2 font-heading text-3xl font-bold text-[#16151A] sm:text-[36px]">Verifikasi Pembayaran</h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-[#565A66]">
                    Tinjau bukti transfer dan konfirmasi pembayaran membership maupun sesi personal trainer.
                </p>
            </header>

            @include('admin.verifikasi-pembayaran.partials.filter-bar')
            @include('admin.verifikasi-pembayaran.partials.payment-table')
        </div>

        @include('admin.verifikasi-pembayaran.partials.detail-modal')
        @include('admin.verifikasi-pembayaran.partials.reject-modal')

        <div
            x-cloak
            x-show="toast.visible"
            x-transition
            role="status"
            aria-live="polite"
            class="fixed bottom-5 right-5 z-[70] flex max-w-sm items-center gap-3 rounded-2xl bg-[#16151A] px-5 py-4 text-sm font-semibold text-white shadow-xl"
        >
            <span
                x-bind:class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-rose-600'"
                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-white"
                aria-hidden="true"
                x-text="toast.type === 'success' ? '✓' : '!'"
            ></span>
            <span x-text="toast.message"></span>
            <button type="button" x-on:click="toast.visible = false" class="ml-2 text-white/70 hover:text-white" aria-label="Tutup notifikasi">&times;</button>
        </div>
    </section>
</x-app-layout>
