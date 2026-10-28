@php
    $isPt = $payment->package_type === 'pt_session' || $payment->payment_type === 'pt_package';
    $packageName = $isPt
        ? ($payment->ptPackage?->name ?? 'Personal Trainer Session')
        : ($payment->membershipPackage?->name
            ?? $payment->activatedMembership?->package?->name
            ?? $payment->membership?->package?->name
            ?? 'Paket Keanggotaan FitCore');

    $packageCategory = $isPt ? 'Personal Trainer' : 'Paket Membership';

    $date = $payment->transfer_date ?? $payment->created_at ?? now();
    $formattedDate = $date->copy()->locale('id')->translatedFormat('d F Y, H:i').' WIB';

    // Active period calculation for verified payments
    $startDate = null;
    $endDate = null;

    if ($payment->activatedMembership?->start_date && $payment->activatedMembership?->end_date) {
        $startDate = $payment->activatedMembership->start_date;
        $endDate = $payment->activatedMembership->end_date;
    } elseif ($payment->membership?->start_date && $payment->membership?->end_date) {
        $startDate = $payment->membership->start_date;
        $endDate = $payment->membership->end_date;
    } elseif ($payment->status === 'verified') {
        $startDate = $payment->verified_at ? $payment->verified_at->copy() : $date->copy();
        $durationDays = $payment->membershipPackage?->duration_in_days
            ?? $payment->membershipPackage?->duration_days
            ?? 30;
        $endDate = $startDate->copy()->addDays((int) $durationDays - 1);
    }

    $activePeriodText = ($startDate && $endDate)
        ? $startDate->copy()->locale('id')->translatedFormat('d M Y') . ' - ' . $endDate->copy()->locale('id')->translatedFormat('d M Y')
        : null;

    $hasProofImage = !empty($payment->proof_image_url) || !empty($payment->proof_image_path);
    $proofUrl = $payment->proof_image_url ?: ($payment->proof_image_path ? asset('storage/' . $payment->proof_image_path) : null);
@endphp

<article
    class="bg-white rounded-2xl p-5 sm:p-6 shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-[#E5E7EB] hover:border-[#ED1B45]/30 transition-all duration-300"
>
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <!-- Kolom Kiri & Tengah: Info Transaksi & Paket -->
        <div class="flex-1 space-y-4">
            <!-- Header Kartu: Status Badge, Nomor Invoice, Tanggal -->
            <div class="flex flex-wrap items-center gap-3">
                @if ($payment->status === 'pending')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-inter font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu Verifikasi
                    </span>
                @elseif ($payment->status === 'verified')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-inter font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <svg class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Berhasil
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-inter font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                        <svg class="h-3.5 w-3.5 text-rose-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        Ditolak
                    </span>
                @endif

                <span class="font-mono text-xs font-bold text-[#16151A] bg-[#F6F6F7] px-2.5 py-1 rounded-md">
                    {{ $payment->invoice_id ?? 'INV-'.$payment->id }}
                </span>

                <span class="text-xs font-inter text-[#565A66] flex items-center gap-1">
                    <svg class="h-3.5 w-3.5 text-[#565A66]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $formattedDate }}
                </span>
            </div>

            <!-- Body Kartu: Nama Paket & Rincian -->
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#ED1B45]/10 text-[#ED1B45]">
                    @if ($isPt)
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    @else
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    @endif
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66] bg-[#F6F6F7] px-2 py-0.5 rounded-full">
                            {{ $packageCategory }}
                        </span>
                        @if ($payment->bank_sender)
                            <span class="text-[10px] font-semibold text-[#565A66]">
                                &bull; Bank {{ $payment->bank_sender }}
                            </span>
                        @endif
                    </div>

                    <h3 class="font-poppins text-lg font-bold text-[#16151A]">
                        {{ $packageName }}
                    </h3>

                    <!-- Kondisional Berdasarkan Status: Masa Aktif atau Alert Waktu Verifikasi -->
                    @if ($payment->status === 'verified')
                        @if ($activePeriodText)
                            <div class="inline-flex items-center gap-1.5 text-xs font-inter font-semibold text-emerald-600 bg-emerald-50/80 px-2.5 py-1 rounded-full border border-emerald-200/50 mt-1">
                                <svg class="h-3.5 w-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Masa Aktif: {{ $activePeriodText }}</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-1.5 text-xs font-inter font-semibold text-emerald-600 mt-1">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Keanggotaan Aktif</span>
                            </div>
                        @endif
                    @elseif ($payment->status === 'pending')
                        <div class="inline-flex items-center gap-1.5 text-xs font-inter font-medium text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200/60 mt-1">
                            <svg class="h-3.5 w-3.5 text-amber-500 animate-spin shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span>Estimasi verifikasi finance: ± 15-30 menit</span>
                        </div>
                    @elseif ($payment->status === 'rejected')
                        <div class="text-xs font-inter text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200/60 mt-1">
                            <span>Alasan Penolakan: {{ $payment->rejection_reason ?? 'Bukti transfer tidak terbaca atau nominal tidak sesuai.' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Total Tagihan & Tombol Aksi -->
        <div class="flex flex-col sm:items-end justify-between gap-4 pt-4 lg:pt-0 border-t lg:border-t-0 border-[#E5E7EB]">
            <!-- Total Tagihan (Kondisional: Merah jika Pending, Hitam jika Verified) -->
            <div class="sm:text-right">
                <p class="font-inter text-[11px] font-semibold uppercase tracking-wider text-[#565A66]">Total Tagihan</p>
                @if ($payment->status === 'pending')
                    <p class="font-poppins text-xl sm:text-2xl font-bold text-[#ED1B45]">
                        Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                    </p>
                @else
                    <p class="font-poppins text-xl sm:text-2xl font-bold text-[#16151A]">
                        Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                    </p>
                @endif
            </div>

            <!-- Tombol Aksi (Kondisional) -->
            <div class="flex items-center gap-2.5">
                @if ($payment->status === 'pending')
                    <!-- Tombol Bukti Transfer (Outline Gelap) -->
                    <button
                        type="button"
                        @click="openReceiptModal({
                            invoice_id: '{{ $payment->invoice_id }}',
                            amount: '{{ number_format((float) $payment->amount, 0, ',', '.') }}',
                            bank_sender: '{{ $payment->bank_sender }}',
                            transfer_date: '{{ $formattedDate }}',
                            image_url: '{{ $proofUrl }}'
                        })"
                        class="rounded-full border border-[#16151A] text-[#16151A] hover:bg-[#16151A] hover:text-white px-4 py-2 font-inter text-xs font-semibold transition cursor-pointer"
                    >
                        Bukti Transfer
                    </button>

                    <!-- Tombol Cek Status (Primary Red) -->
                    <a
                        href="{{ route('member.membership.payment-status', $payment->id) }}"
                        class="rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white px-4 py-2 font-inter text-xs font-semibold shadow-sm transition inline-flex items-center gap-1.5"
                    >
                        <span>Cek Status</span>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @elseif ($payment->status === 'verified')
                    <!-- Tombol Rincian (Outline Gelap) -->
                    <button
                        type="button"
                        @click="openDetailModal({
                            id: '{{ $payment->id }}',
                            invoice_id: '{{ $payment->invoice_id }}',
                            package_name: '{{ addslashes($packageName) }}',
                            package_category: '{{ $packageCategory }}',
                            amount: '{{ number_format((float) $payment->amount, 0, ',', '.') }}',
                            date: '{{ $formattedDate }}',
                            active_period: '{{ $activePeriodText ?? '-' }}',
                            bank_sender: '{{ $payment->bank_sender ?? '-' }}',
                            bank_destination: '{{ addslashes($payment->bank_destination ?? ($payment->bank?->name ?? '-')) }}',
                            status: 'verified',
                            invoice_url: '{{ route('member.riwayat-transaksi.invoice', $payment->id) }}',
                            proof_url: '{{ $proofUrl }}'
                        })"
                        class="rounded-full border border-[#16151A] text-[#16151A] hover:bg-[#16151A] hover:text-white px-4 py-2 font-inter text-xs font-semibold transition cursor-pointer"
                    >
                        Rincian
                    </button>

                    <!-- Tombol Invoice (Dark Button mengarah ke route download PDF) -->
                    <a
                        href="{{ route('member.riwayat-transaksi.invoice', $payment->id) }}"
                        class="rounded-full bg-[#141414] hover:bg-black text-white px-4 py-2 font-inter text-xs font-semibold shadow-sm transition inline-flex items-center gap-1.5"
                        title="Unduh Invoice PDF Resmi"
                    >
                        <svg class="h-3.5 w-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Invoice</span>
                    </a>
                @else
                    <!-- Jika Rejected: Rincian & Bukti -->
                    <button
                        type="button"
                        @click="openDetailModal({
                            invoice_id: '{{ $payment->invoice_id }}',
                            package_name: '{{ addslashes($packageName) }}',
                            package_category: '{{ $packageCategory }}',
                            amount: '{{ number_format((float) $payment->amount, 0, ',', '.') }}',
                            date: '{{ $formattedDate }}',
                            active_period: '-',
                            bank_sender: '{{ $payment->bank_sender ?? '-' }}',
                            bank_destination: '{{ addslashes($payment->bank_destination ?? ($payment->bank?->name ?? '-')) }}',
                            status: 'rejected',
                            rejection_reason: '{{ addslashes($payment->rejection_reason ?? '') }}',
                            proof_url: '{{ $proofUrl }}'
                        })"
                        class="rounded-full border border-[#16151A] text-[#16151A] hover:bg-[#16151A] hover:text-white px-4 py-2 font-inter text-xs font-semibold transition cursor-pointer"
                    >
                        Rincian
                    </button>
                    <a
                        href="{{ route('member.membership.packages') }}"
                        class="rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white px-4 py-2 font-inter text-xs font-semibold shadow-sm transition"
                    >
                        Beli Ulang
                    </a>
                @endif
            </div>
        </div>
    </div>
</article>
