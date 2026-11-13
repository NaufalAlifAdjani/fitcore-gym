{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Riwayat Transaksi & Pembayaran (100% Visual Sync)
| FILE         : resources/views/mobile/member/riwayat-transaksi/index.blade.php
| LAYOUT       : layouts/mobile-app.blade.php
| AKSES ROUTE  : /m/riwayat-transaksi atau /member/riwayat-transaksi (name: mobile.member.riwayat-transaksi.index)
| TEMA         : Light Theme (Canvas #F6F6F7, Surface #FFFFFF, Accent #FF5722)
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-app title="Riwayat Transaksi - FitCore Athletic" active-tab="history">
    <div
        x-data="{
            showDetailModal: false,
            selectedDetail: null,

            openDetailModal(detail) {
                this.selectedDetail = detail;
                this.showDetailModal = true;
            },
            closeDetailModal() {
                this.showDetailModal = false;
                this.selectedDetail = null;
            }
        }"
        x-on:keydown.escape.window="closeDetailModal()"
        class="space-y-5"
    >
        <!-- 1. Header Halaman Riwayat Transaksi (Sesuai Web Desktop) -->
        <header class="space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-[#FF5722] text-[11px] font-inter font-bold uppercase tracking-wider border border-orange-100">
                <span class="h-1.5 w-1.5 rounded-full bg-[#FF5722]"></span>
                Member Portal FitCore
            </div>
            <h1 class="font-poppins font-extrabold text-2xl sm:text-3xl text-[#16151A] tracking-tight leading-tight">
                RIWAYAT TRANSAKSI
            </h1>
            <p class="font-inter text-xs text-[#565A66] leading-relaxed">
                Pantau seluruh catatan pembayaran membership gym dan sesi personal trainer Anda. Total {{ $totalCount }} transaksi tercatat.
            </p>
        </header>

        <!-- 2. Filter Status Cepat (Pill Buttons) -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs font-bold no-scrollbar">
            <a href="{{ route('mobile.member.riwayat-transaksi.index', ['status' => 'all']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 whitespace-nowrap {{ $currentStatus === 'all' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]' }}">
                Semua
            </a>
            <a href="{{ route('mobile.member.riwayat-transaksi.index', ['status' => 'verified']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 whitespace-nowrap {{ $currentStatus === 'verified' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]' }}">
                Berhasil
            </a>
            <a href="{{ route('mobile.member.riwayat-transaksi.index', ['status' => 'pending']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 whitespace-nowrap {{ $currentStatus === 'pending' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]' }}">
                Menunggu
            </a>
            <a href="{{ route('mobile.member.riwayat-transaksi.index', ['status' => 'rejected']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 whitespace-nowrap {{ $currentStatus === 'rejected' ? 'bg-[#FF5722] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]' }}">
                Ditolak
            </a>
        </div>

        <!-- 3. Daftar Transaksi Dikelompokkan Per Bulan (Identik dengan Web Desktop) -->
        <div class="space-y-6">
            @forelse ($groupedPayments as $period => $payments)
                <div class="space-y-3">
                    <!-- Header Grup Tanggal dengan aksen border merah/orange di kiri (Identik Web Desktop) -->
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-2.5">
                        <div class="border-l-4 border-[#FF5722] pl-3">
                            <h2 class="font-poppins text-sm sm:text-base font-bold uppercase tracking-wider text-[#16151A]">
                                {{ $period }}
                            </h2>
                        </div>
                        <span class="rounded-full bg-slate-200/80 text-[#16151A] text-[11px] font-inter font-semibold px-2.5 py-0.5 shadow-2xs">
                            {{ $payments->count() }} Transaksi
                        </span>
                    </div>

                    <!-- Kartu Transaksi di Periode Ini -->
                    <div class="space-y-3">
                        @foreach ($payments as $payment)
                            @php
                                $isVerified = $payment->status === 'verified';
                                $isPending = $payment->status === 'pending';
                                $isRejected = $payment->status === 'rejected';
                                $packageName = $payment->membershipPackage->name 
                                    ?? $payment->ptPackage->name 
                                    ?? $payment->activatedMembership->package->name 
                                    ?? 'Paket Gym FitCore';
                                $invoiceNumber = $payment->invoice_id ?? 'INV-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT);
                                $dateFormatted = ($payment->transfer_date ?? $payment->created_at)->translatedFormat('d M Y, H:i');
                            @endphp

                            <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] space-y-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-mono font-bold text-[#565A66]">
                                        #{{ $invoiceNumber }}
                                    </span>

                                    @if ($isVerified)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Berhasil
                                        </span>
                                    @elseif ($isPending)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Ditolak
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h4 class="text-xs sm:text-sm font-bold text-[#16151A] font-poppins truncate">
                                            {{ $packageName }}
                                        </h4>
                                        <p class="text-[11px] text-[#565A66] mt-0.5 font-sans">
                                            Bank {{ $payment->bank->name ?? 'Transfer Bank' }} • {{ $dateFormatted }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <span class="font-poppins font-black text-sm sm:text-base text-[#16151A]">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Aksi Aksi Cepat (Detail & Unduh Invoice) -->
                                <div class="pt-2.5 border-t border-[#E5E7EB] flex items-center justify-between gap-2">
                                    <button
                                        type="button"
                                        @click="openDetailModal({
                                            invoice: '{{ $invoiceNumber }}',
                                            packageName: '{{ addslashes($packageName) }}',
                                            bank: '{{ addslashes($payment->bank->name ?? 'Transfer Bank') }}',
                                            accountNumber: '{{ $payment->bank->account_number ?? '-' }}',
                                            accountName: '{{ addslashes($payment->bank->account_name ?? 'FitCore Gym') }}',
                                            amount: 'Rp {{ number_format($payment->amount, 0, ',', '.') }}',
                                            date: '{{ $dateFormatted }}',
                                            status: '{{ $payment->status }}',
                                            rejectionReason: '{{ addslashes($payment->rejection_reason ?? '') }}',
                                            downloadUrl: '{{ $isVerified ? route('mobile.member.riwayat-transaksi.invoice', $payment) : '' }}'
                                        })"
                                        class="px-3 py-1.5 rounded-full text-[11px] font-bold border border-[#E5E7EB] text-[#16151A] hover:bg-gray-50 transition cursor-pointer"
                                    >
                                        Rincian Detail
                                    </button>

                                    @if ($isVerified)
                                        <a href="{{ route('mobile.member.riwayat-transaksi.invoice', $payment) }}"
                                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-[11px] font-bold transition shadow-xs group">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            <span>Invoice PDF</span>
                                        </a>
                                    @elseif ($isRejected && $payment->rejection_reason)
                                        <span class="text-[10px] text-rose-600 font-medium truncate max-w-[150px]">
                                            Alasan: {{ $payment->rejection_reason }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-8 rounded-2xl bg-white/70 border border-dashed border-[#E5E7EB] text-center space-y-2">
                    <p class="text-xs font-semibold text-[#16151A]">Belum ada riwayat transaksi</p>
                    <p class="text-[11px] text-[#565A66]">
                        Riwayat pembayaran paket membership atau sesi PT akan tampil di sini.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('mobile.member.packages') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold shadow-md shadow-orange-500/25 transition">
                            Lihat Paket Membership
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- 4. Modal Rincian Detail Transaksi (Alpine.js - Identik Web Desktop) -->
        <div
            x-cloak
            x-show="showDetailModal"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs"
            role="dialog"
            aria-modal="true"
        >
            <div
                x-show="showDetailModal"
                x-transition
                @click.outside="closeDetailModal()"
                class="w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl border border-[#E5E7EB] space-y-4 p-5"
            >
                <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#FF5722]">Rincian Transaksi</span>
                        <h3 class="font-poppins text-base font-bold text-[#16151A]" x-text="selectedDetail?.packageName || 'Detail Pembayaran'"></h3>
                    </div>
                    <button
                        type="button"
                        @click="closeDetailModal()"
                        class="rounded-full p-1.5 text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition cursor-pointer"
                        aria-label="Tutup"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between py-1 border-b border-[#E5E7EB]">
                        <span class="text-[#565A66]">No. Invoice</span>
                        <span class="font-mono font-bold text-[#16151A]" x-text="'#' + (selectedDetail?.invoice || '-')"></span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-[#E5E7EB]">
                        <span class="text-[#565A66]">Waktu Pembayaran</span>
                        <span class="font-medium text-[#16151A]" x-text="selectedDetail?.date || '-'"></span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-[#E5E7EB]">
                        <span class="text-[#565A66]">Metode Bank</span>
                        <span class="font-medium text-[#16151A]" x-text="selectedDetail?.bank || '-'"></span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-[#E5E7EB]">
                        <span class="text-[#565A66]">Total Pembayaran</span>
                        <span class="font-black text-sm text-[#FF5722] font-poppins" x-text="selectedDetail?.amount || '-'"></span>
                    </div>

                    <template x-if="selectedDetail?.rejectionReason">
                        <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-[11px]">
                            <span class="font-bold block">Alasan Penolakan:</span>
                            <span x-text="selectedDetail.rejectionReason"></span>
                        </div>
                    </template>
                </div>

                <div class="pt-2 flex items-center gap-2">
                    <button
                        type="button"
                        @click="closeDetailModal()"
                        class="flex-1 py-2 rounded-full border border-[#E5E7EB] text-xs font-bold text-[#565A66] hover:bg-gray-50 transition"
                    >
                        Tutup
                    </button>

                    <template x-if="selectedDetail?.downloadUrl">
                        <a
                            :href="selectedDetail.downloadUrl"
                            class="flex-1 py-2 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white text-xs font-bold text-center transition shadow-xs"
                        >
                            Unduh Invoice
                        </a>
                    </template>
                </div>
            </div>
        </div>

    </div>
</x-layouts.mobile-app>
