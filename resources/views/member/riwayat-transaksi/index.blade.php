<x-member-layout title="Riwayat Transaksi - FitCore Athletic" active-nav="riwayat">
    <div
        x-data="{
            showDetailModal: false,
            selectedDetail: null,
            showReceiptModal: false,
            selectedReceipt: null,

            openDetailModal(detail) {
                this.selectedDetail = detail;
                this.showDetailModal = true;
            },
            closeDetailModal() {
                this.showDetailModal = false;
                this.selectedDetail = null;
            },

            openReceiptModal(receipt) {
                this.selectedReceipt = receipt;
                this.showReceiptModal = true;
            },
            closeReceiptModal() {
                this.showReceiptModal = false;
                this.selectedReceipt = null;
            }
        }"
        x-on:keydown.escape.window="closeDetailModal(); closeReceiptModal();"
        class="min-h-screen bg-[#F6F6F7] text-[#16151A] py-10 px-4 sm:px-6 lg:px-8 font-inter"
    >
        <div class="mx-auto max-w-6xl space-y-8">
            <!-- Header Halaman Riwayat Transaksi -->
            <header class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#ED1B45]/10 text-[#ED1B45] text-xs font-inter font-bold uppercase tracking-wider">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#ED1B45]"></span>
                    Member Portal FitCore
                </div>
                <h1 class="font-poppins font-extrabold text-3xl sm:text-4xl lg:text-[3.25rem] text-[#16151A] tracking-tight leading-tight">
                    RIWAYAT TRANSAKSI
                </h1>
                <p class="font-inter text-xs sm:text-sm text-[#565A66] max-w-2xl leading-relaxed">
                    Pantau seluruh catatan pembayaran membership gym dan sesi personal trainer Anda. Dapatkan bukti pembayaran dan unduh invoice resmi digital secara mudah.
                </p>
            </header>

            <!-- Filter Bar Component -->
            @include('member.riwayat-transaksi.partials.filter-bar')

            <!-- List Riwayat Berdasarkan Grup Bulan & Tahun -->
            @forelse ($groupedPayments as $monthYear => $payments)
                <section class="space-y-4 mb-10">
                    <!-- Header Grup Tanggal dengan aksen border merah di kiri dan badge count di kanan -->
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                        <div class="border-l-4 border-[#ED1B45] pl-3.5">
                            <h2 class="font-poppins text-lg sm:text-xl font-bold uppercase tracking-wider text-[#16151A]">
                                {{ $monthYear }}
                            </h2>
                        </div>
                        <span class="rounded-full bg-slate-200/80 text-[#16151A] text-xs font-inter font-semibold px-3 py-1 shadow-sm">
                            {{ $payments->count() }} Transaksi
                        </span>
                    </div>

                    <!-- List Kartu Transaksi -->
                    <div class="space-y-4">
                        @foreach ($payments as $payment)
                            @include('member.riwayat-transaksi.partials.transaction-card', ['payment' => $payment])
                        @endforeach
                    </div>
                </section>
            @empty
                <!-- Empty State jika tidak ada data -->
                @include('member.riwayat-transaksi.partials.empty-state')
            @endforelse
        </div>

        <!-- MODAL 1: Detail Rincian Transaksi -->
        <div
            x-cloak
            x-show="showDetailModal"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
        >
            <div
                x-show="showDetailModal"
                x-transition
                x-on:click.outside="closeDetailModal()"
                class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl border border-[#E5E7EB]"
            >
                <div class="flex items-center justify-between border-b border-[#E5E7EB] px-6 py-4 bg-white">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#ED1B45]">Rincian Transaksi</span>
                        <h3 class="font-poppins text-lg font-bold text-[#16151A]" x-text="selectedDetail?.package_name || 'Detail Paket'"></h3>
                    </div>
                    <button
                        type="button"
                        x-on:click="closeDetailModal()"
                        class="rounded-full p-2 text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition cursor-pointer"
                        aria-label="Tutup"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 font-inter text-xs">
                    <div class="rounded-xl bg-[#F6F6F7] p-4 space-y-2.5">
                        <div class="flex justify-between">
                            <span class="text-[#565A66]">Nomor Invoice</span>
                            <span class="font-mono font-bold text-[#16151A]" x-text="selectedDetail?.invoice_id"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#565A66]">Waktu Pembayaran</span>
                            <span class="font-semibold text-[#16151A]" x-text="selectedDetail?.date"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#565A66]">Kategori</span>
                            <span class="font-semibold text-[#16151A]" x-text="selectedDetail?.package_category"></span>
                        </div>
                        <div class="flex justify-between" x-show="selectedDetail?.active_period && selectedDetail?.active_period !== '-'">
                            <span class="text-[#565A66]">Masa Berlaku</span>
                            <span class="font-semibold text-emerald-600" x-text="selectedDetail?.active_period"></span>
                        </div>
                    </div>

                    <div class="rounded-xl border border-[#E5E7EB] p-4 space-y-2.5">
                        <div class="flex justify-between">
                            <span class="text-[#565A66]">Bank Pengirim</span>
                            <span class="font-semibold text-[#16151A]" x-text="selectedDetail?.bank_sender"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#565A66]">Rekening Tujuan</span>
                            <span class="font-semibold text-[#16151A]" x-text="selectedDetail?.bank_destination"></span>
                        </div>
                        <div class="flex justify-between border-t border-[#E5E7EB] pt-2.5 items-center">
                            <span class="font-bold text-[#16151A]">Total Dibayar</span>
                            <span class="font-poppins text-base font-bold text-[#ED1B45]" x-text="'Rp ' + (selectedDetail?.amount || '0')"></span>
                        </div>
                    </div>

                    <template x-if="selectedDetail?.rejection_reason">
                        <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5 text-rose-700">
                            <p class="font-bold text-[11px] mb-1">Catatan Penolakan:</p>
                            <p x-text="selectedDetail.rejection_reason"></p>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-[#E5E7EB] px-6 py-4 bg-[#F9FAFB]">
                    <button
                        type="button"
                        x-on:click="closeDetailModal()"
                        class="rounded-full border border-[#E5E7EB] bg-white px-5 py-2 text-xs font-inter font-semibold text-[#565A66] hover:bg-[#F6F6F7] transition cursor-pointer"
                    >
                        Tutup
                    </button>
                    <template x-if="selectedDetail?.status === 'verified' && selectedDetail?.invoice_url">
                        <a
                            x-bind:href="selectedDetail.invoice_url"
                            class="rounded-full bg-[#141414] hover:bg-black text-white px-5 py-2 text-xs font-inter font-semibold transition inline-flex items-center gap-1.5"
                        >
                            <svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Unduh Invoice PDF</span>
                        </a>
                    </template>
                </div>
            </div>
        </div>

        <!-- MODAL 2: Bukti Transfer Viewer -->
        <div
            x-cloak
            x-show="showReceiptModal"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
        >
            <div
                x-show="showReceiptModal"
                x-transition
                x-on:click.outside="closeReceiptModal()"
                class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl border border-[#E5E7EB]"
            >
                <div class="flex items-center justify-between border-b border-[#E5E7EB] px-6 py-4 bg-white">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#ED1B45]">Pemeriksaan Transfer</span>
                        <h3 class="font-poppins text-base font-bold text-[#16151A]" x-text="'Bukti Transfer: ' + (selectedReceipt?.invoice_id || '')"></h3>
                    </div>
                    <button
                        type="button"
                        x-on:click="closeReceiptModal()"
                        class="rounded-full p-2 text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition cursor-pointer"
                        aria-label="Tutup"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <template x-if="selectedReceipt?.image_url">
                        <div class="overflow-hidden rounded-xl border border-[#E5E7EB] bg-slate-100 flex items-center justify-center max-h-80">
                            <img
                                x-bind:src="selectedReceipt.image_url"
                                alt="Bukti Transfer"
                                class="w-full h-auto object-contain max-h-80 hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                    </template>

                    <template x-if="!selectedReceipt?.image_url">
                        <div class="rounded-xl border border-dashed border-[#E5E7EB] bg-[#F6F6F7] p-8 text-center text-[#565A66] text-xs">
                            <svg class="mx-auto h-8 w-8 text-[#565A66]/60 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Gambar bukti transfer belum diunggah atau berformat tautan eksternal.
                        </div>
                    </template>

                    <div class="grid grid-cols-2 gap-3 rounded-xl bg-[#F6F6F7] p-3 text-xs">
                        <div>
                            <span class="text-[#565A66] text-[10px] uppercase font-bold">Nominal</span>
                            <p class="font-poppins font-bold text-[#ED1B45]" x-text="'Rp ' + (selectedReceipt?.amount || '0')"></p>
                        </div>
                        <div>
                            <span class="text-[#565A66] text-[10px] uppercase font-bold">Bank Pengirim</span>
                            <p class="font-semibold text-[#16151A]" x-text="selectedReceipt?.bank_sender || '-'"></p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-[#E5E7EB] px-6 py-4 bg-[#F9FAFB] text-right">
                    <button
                        type="button"
                        x-on:click="closeReceiptModal()"
                        class="rounded-full bg-[#16151A] hover:bg-black text-white px-5 py-2 text-xs font-inter font-semibold transition cursor-pointer"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-member-layout>
