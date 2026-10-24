<div class="bg-white rounded-2xl p-10 sm:p-14 text-center shadow-[0_4px_12px_rgba(0,0,0,0.06)] border border-[#E5E7EB]">
    <!-- Icon Ilustrasi Elegan -->
    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#ED1B45]/10 text-[#ED1B45] mb-5">
        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </div>

    <!-- Heading Elegan sesuai Activity Diagram -->
    <h3 class="font-poppins text-xl sm:text-2xl font-bold text-[#16151A]">
        Riwayat tidak ditemukan
    </h3>

    <!-- Pesan Pendukung -->
    <p class="mt-2 text-xs sm:text-sm text-[#565A66] max-w-md mx-auto leading-relaxed font-inter">
        @if ($currentSearch || ($currentStatus && $currentStatus !== 'all') || ($currentYear && $currentYear !== 'all'))
            Tidak ada transaksi pembayaran yang cocok dengan kriteria filter atau pencarian Anda. Silakan coba kata kunci lain atau setel ulang filter.
        @else
            Anda belum memiliki riwayat transaksi pembayaran di FitCore Athletic. Mulai perjalanan kebugaran Anda dengan memilih paket membership atau sesi personal trainer.
        @endif
    </p>

    <!-- Tombol Aksi -->
    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
        @if ($currentSearch || ($currentStatus && $currentStatus !== 'all') || ($currentYear && $currentYear !== 'all'))
            <a
                href="{{ route('member.riwayat-transaksi.index') }}"
                class="rounded-full bg-[#16151A] hover:bg-black text-white px-6 py-2.5 font-inter text-xs font-semibold shadow-sm transition cursor-pointer"
            >
                Reset Filter
            </a>
        @else
            <a
                href="{{ route('member.membership.packages') }}"
                class="rounded-full bg-[#ED1B45] hover:bg-[#D1123D] text-white px-6 py-2.5 font-inter text-xs font-semibold shadow-sm transition inline-flex items-center gap-2 cursor-pointer"
            >
                <span>Lihat Paket Membership</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        @endif
    </div>
</div>
