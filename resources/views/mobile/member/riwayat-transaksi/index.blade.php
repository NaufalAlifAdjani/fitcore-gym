{{--
|--------------------------------------------------------------------------
| FitCore Athletic - Mobile UI
|--------------------------------------------------------------------------
| ROLE         : MEMBER
| MODUL        : Riwayat Transaksi & Pembayaran (Fitur #3)
| FILE         : resources/views/mobile/member/riwayat-transaksi/index.blade.php
| LAYOUT       : layouts/mobile-app.blade.php
| AKSES ROUTE  : /m/riwayat-transaksi atau /member/riwayat-transaksi (name: mobile.member.riwayat-transaksi.index)
| DESKRIPSI    : Tampilan daftar riwayat transaksi member dalam bentuk stacked cards
|                dikelompokkan per bulan/tahun, lengkap dengan badge status pembayaran
|                dan tombol download invoice PDF resmi.
|                Menggunakan token warna DESIGN.md Light Theme: primary #ED1B45,
|                surface white #FFFFFF, background #F6F6F7, border #E5E7EB,
|                tombol pill full rounded, tipografi Poppins & Inter.
|--------------------------------------------------------------------------
--}}

<x-layouts.mobile-app title="Riwayat Transaksi - FitCore Athletic" active-tab="history">
    <div class="space-y-4">

        <!-- 1. Header Riwayat -->
        <div>
            <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">
                Riwayat Transaksi
            </h1>
            <p class="text-[11px] text-[#565A66] font-medium font-sans">
                Total {{ $totalCount }} transaksi pembayaran tercatat
            </p>
        </div>

        <!-- 2. Filter Status Cepat (Pill Buttons per stitch-prompts #Shared Patterns) -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-[11px] font-bold no-scrollbar">
            <a href="{{ route('mobile.member.riwayat-transaksi.index', ['status' => 'all']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentStatus === 'all' ? 'bg-[#ED1B45] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]' }}">
                Semua
            </a>
            <a href="{{ route('mobile.member.riwayat-transaksi.index', ['status' => 'verified']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentStatus === 'verified' ? 'bg-[#ED1B45] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]' }}">
                Berhasil
            </a>
            <a href="{{ route('mobile.member.riwayat-transaksi.index', ['status' => 'pending']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentStatus === 'pending' ? 'bg-[#ED1B45] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]' }}">
                Menunggu
            </a>
            <a href="{{ route('mobile.member.riwayat-transaksi.index', ['status' => 'rejected']) }}"
               class="px-3.5 py-1.5 rounded-full transition shrink-0 {{ $currentStatus === 'rejected' ? 'bg-[#ED1B45] text-white shadow-xs' : 'bg-white text-[#565A66] border border-[#E5E7EB] hover:text-[#16151A]' }}">
                Ditolak
            </a>
        </div>

        <!-- 3. Daftar Transaksi Dikelompokkan Per Bulan (Stacked Cards, Surface White #FFFFFF, Radius 16px) -->
        <div class="space-y-5">
            @forelse ($groupedPayments as $period => $payments)
                <div class="space-y-2.5">
                    <!-- Bulan & Tahun Label -->
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#565A66] px-1 font-poppins">
                        {{ $period }}
                    </h3>

                    <!-- Kartu Transaksi di Periode Ini -->
                    <div class="space-y-2.5">
                        @foreach ($payments as $payment)
                            @php
                                $isVerified = $payment->status === 'verified';
                                $isPending = $payment->status === 'pending';
                                $isRejected = $payment->status === 'rejected';
                                $packageName = $payment->membershipPackage->name 
                                    ?? $payment->ptPackage->name 
                                    ?? $payment->activatedMembership->package->name 
                                    ?? 'Paket Gym FitCore';
                            @endphp

                            <div class="p-4 rounded-2xl bg-white border border-[#E5E7EB] space-y-3 shadow-[0_4px_12px_rgba(0,0,0,0.04)]">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-mono text-[#565A66]">
                                        #{{ $payment->invoice_id ?? 'INV-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}
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
                                    <div>
                                        <h4 class="text-xs sm:text-sm font-bold text-[#16151A] font-poppins">
                                            {{ $packageName }}
                                        </h4>
                                        <p class="text-[11px] text-[#565A66] mt-0.5 font-sans">
                                            Bank {{ $payment->bank->name ?? 'Transfer Bank' }} • {{ ($payment->transfer_date ?? $payment->created_at)->translatedFormat('d M Y') }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <span class="font-poppins font-black text-sm sm:text-base text-[#16151A]">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Aksi Unduh Invoice PDF (Hanya jika terverifikasi) -->
                                @if ($isVerified)
                                    <div class="pt-2 border-t border-[#E5E7EB] flex items-center justify-between">
                                        <span class="text-[10px] text-emerald-700 font-semibold">
                                            ✓ Transaksi Selesai
                                        </span>
                                        <a href="{{ route('mobile.member.riwayat-transaksi.invoice', $payment) }}"
                                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-[#F6F6F7] hover:bg-[#ED1B45] text-[#16151A] hover:text-white text-[11px] font-bold transition border border-[#E5E7EB] shadow-2xs group">
                                            <svg class="w-3.5 h-3.5 text-[#ED1B45] group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            <span>Invoice PDF</span>
                                        </a>
                                    </div>
                                @elseif ($isRejected && $payment->rejection_reason)
                                    <div class="pt-2 border-t border-[#E5E7EB] text-[11px] text-rose-600">
                                        Alasan: {{ $payment->rejection_reason }}
                                    </div>
                                @endif
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
                        <a href="{{ route('member.membership.packages') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-full bg-[#ED1B45] text-white text-xs font-bold shadow-md hover:bg-[#D1123D] transition">
                            Lihat Paket Membership
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</x-layouts.mobile-app>
