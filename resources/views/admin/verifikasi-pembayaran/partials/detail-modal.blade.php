<div
    x-cloak
    x-show="showDetailModal"
    x-transition.opacity
    x-on:click.self="closeModals()"
    class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 p-3 backdrop-blur-sm sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="payment-detail-title"
>
    <section x-transition class="max-h-[94vh] w-full max-w-6xl overflow-y-auto rounded-[24px] bg-white shadow-2xl">
        <header class="sticky top-0 z-10 flex items-start justify-between border-b border-[#F0F0F1] bg-white px-5 py-4 sm:px-7">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#BA0030]">Pemeriksaan Transaksi</p>
                <h2 id="payment-detail-title" class="mt-1 font-heading text-xl font-bold text-[#16151A]">Detail Pembayaran</h2>
                <p class="mt-1 font-mono text-xs text-[#858894]" x-text="selectedPayment?.invoice_id || ''"></p>
            </div>
            <button type="button" x-on:click="closeModals()" aria-label="Tutup detail" class="rounded-full p-2 text-[#858894] hover:bg-[#F4F4F5] hover:text-[#16151A]">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></svg>
            </button>
        </header>

        <div x-show="isLoadingDetail" class="p-12 text-center text-sm font-semibold text-[#565A66]">Memuat detail pembayaran...</div>

        <template x-if="selectedPayment">
            <div class="grid gap-7 p-5 sm:p-7 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="space-y-5">
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading text-sm font-bold text-[#16151A]">Bukti Transfer</h3>
                        <span class="text-xs text-[#858894]" x-text="formatDate(selectedPayment.transfer_date)"></span>
                    </div>

                    @include('admin.verifikasi-pembayaran.partials.receipt-viewer')

                    <div class="grid grid-cols-3 gap-3 rounded-2xl bg-[#F9F9FA] p-4">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-[#858894]">Bank Pengirim</p>
                            <p class="mt-1 text-xs font-bold text-[#34343B]" x-text="selectedPayment.bank_sender"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-[#858894]">Waktu Transfer</p>
                            <p class="mt-1 text-xs font-bold text-[#34343B]" x-text="formatDate(selectedPayment.transfer_date)"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-[#858894]">Nominal</p>
                            <p class="mt-1 text-xs font-bold text-[#BA0030]" x-text="formatCurrency(selectedPayment.amount)"></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <section class="rounded-2xl border border-[#EEEEEF] p-5">
                        <h3 class="font-heading text-sm font-bold text-[#16151A]">Profil Pemesan</h3>
                        <div class="mt-4 flex items-center gap-3">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-rose-50 font-heading text-lg font-bold text-[#BA0030]" x-text="(selectedPayment.member?.name || 'M').charAt(0).toUpperCase()"></span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-[#16151A]" x-text="selectedPayment.member?.name || 'Member tidak tersedia'"></p>
                                <p class="truncate text-xs text-[#858894]" x-text="selectedPayment.member?.email || '-'"></p>
                                <p class="mt-1 text-[10px] text-[#858894]">ID Member: <span x-text="selectedPayment.member?.id || '-'"></span></p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-[#EEEEEF] p-5">
                        <h3 class="font-heading text-sm font-bold text-[#16151A]">Rincian Paket</h3>
                        <dl class="mt-4 space-y-3 text-xs">
                            <div class="flex justify-between gap-4">
                                <dt class="text-[#858894]">Nama paket</dt>
                                <dd class="text-right font-semibold text-[#34343B]" x-text="selectedPayment.package?.name || 'Paket tidak tersedia'"></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-[#858894]">Jenis paket</dt>
                                <dd class="font-semibold text-[#34343B]" x-text="selectedPayment.package?.type === 'membership' ? 'Membership' : 'Sesi PT'"></dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-[#858894]">Harga dibayar</dt>
                                <dd class="font-bold text-[#16151A]" x-text="formatCurrency(selectedPayment.amount)"></dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-[#858894]">Status</dt>
                                <dd
                                    x-bind:class="{
                                        'bg-amber-50 text-amber-700': selectedPayment.status === 'pending',
                                        'bg-emerald-50 text-emerald-700': selectedPayment.status === 'verified',
                                        'bg-rose-50 text-rose-700': selectedPayment.status === 'rejected'
                                    }"
                                    class="rounded-full px-3 py-1 font-bold uppercase"
                                    x-text="{pending: 'Menunggu verifikasi', verified: 'Terverifikasi', rejected: 'Ditolak'}[selectedPayment.status]"
                                ></dd>
                            </div>
                        </dl>
                        <p x-show="selectedPayment.rejection_reason" x-cloak class="mt-4 rounded-xl bg-rose-50 p-3 text-xs leading-5 text-rose-700" x-text="selectedPayment.rejection_reason"></p>
                    </section>
                </div>
            </div>
        </template>

        <footer class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-[#F0F0F1] bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-7">
            <p x-show="actionError" x-cloak role="alert" class="text-xs font-medium text-rose-700" x-text="actionError"></p>
            <div class="flex flex-col-reverse gap-3 sm:ml-auto sm:flex-row">
                <button
                    type="button"
                    x-show="selectedPayment?.status === 'pending'"
                    x-on:click="showRejectModal = true; actionError = ''"
                    x-bind:disabled="isSubmitting"
                    class="rounded-full border border-rose-200 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 disabled:opacity-60"
                >
                    Tolak Pembayaran
                </button>
                <button
                    type="button"
                    x-show="selectedPayment?.status === 'pending'"
                    x-on:click="approve()"
                    x-bind:disabled="isSubmitting"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-wait disabled:opacity-60"
                >
                    <span x-show="isSubmitting" class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                    <span x-text="isSubmitting ? 'Memproses...' : 'Setujui & Aktifkan'"></span>
                </button>
                <button type="button" x-show="selectedPayment?.status !== 'pending'" x-on:click="closeModals()" class="rounded-full bg-[#EEEEEF] px-5 py-3 text-sm font-semibold text-[#565A66] hover:bg-[#E4E4E7]">
                    Tutup
                </button>
            </div>
        </footer>
    </section>
</div>
