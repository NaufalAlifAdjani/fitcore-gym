{{--
ROLE: MEMBER
MODUL: Status Pembayaran Paket Membership Mobile
VIEW: mobile.member.packages.payment-status
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

<x-layouts.mobile-app title="Status Pembayaran - FitCore Athletic" active-tab="packages">
    <div class="space-y-4">

        <!-- Status Card -->
        <div class="bg-white rounded-2xl p-6 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] text-center space-y-4">
            
            @if ($payment->status === 'pending')
                <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-8 h-8 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                        Menunggu Verifikasi Admin
                    </span>
                    <h1 class="font-poppins font-black text-lg text-[#16151A]">
                        Bukti Pembayaran Diterima
                    </h1>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        Terima kasih! Bukti transfer Anda sedang diverifikasi oleh tim finance FitCore Gym. Proses verifikasi biasanya memakan waktu maksimal <strong class="text-[#16151A]">1x24 jam</strong>.
                    </p>
                </div>
            @elseif ($payment->status === 'verified')
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Terverifikasi Aktif
                    </span>
                    <h1 class="font-poppins font-black text-lg text-[#16151A]">
                        Pembayaran Berhasil!
                    </h1>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        Selamat! Pembayaran Anda telah dikonfirmasi dan paket keanggotaan Anda kini aktif.
                    </p>
                </div>
            @else
                <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-200">
                        Verifikasi Ditolak
                    </span>
                    <h1 class="font-poppins font-black text-lg text-[#16151A]">
                        Pembayaran Ditolak
                    </h1>
                    <p class="text-xs text-[#565A66] leading-relaxed">
                        Maaf, bukti pembayaran tidak dapat dikonfirmasi: <strong class="text-rose-600">{{ $payment->rejection_reason ?? 'Nominal atau struk tidak sesuai' }}</strong>.
                    </p>
                </div>
            @endif

            <!-- Detail Invoice -->
            <div class="p-3.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] text-left space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-[#565A66]">No. Invoice</span>
                    <span class="font-mono font-bold text-[#16151A]">{{ $payment->invoice_id }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[#565A66]">Paket</span>
                    <span class="font-bold text-[#16151A]">{{ $payment->ptPackage?->name ?? $payment->membershipPackage?->name ?? 'Paket FitCore' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[#565A66]">Total Pembayaran</span>
                    <span class="font-poppins font-bold text-[#FF5722]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[#565A66]">Tujuan Rekening</span>
                    <span class="font-medium text-[#16151A] truncate max-w-[180px]">{{ $payment->bank_destination ?? $payment->bank?->name }}</span>
                </div>
            </div>

            <!-- Tombol Aksi Navigasi Mobile -->
            <div class="pt-2 space-y-2">
                <a href="{{ route('mobile.member.dashboard') }}"
                   class="w-full block text-center py-3 rounded-full font-bold text-xs uppercase tracking-wider bg-[#FF5722] hover:bg-[#E64A19] text-white shadow-md shadow-orange-500/25 transition">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('mobile.member.riwayat-transaksi.index') }}"
                   class="w-full block text-center py-3 rounded-full font-bold text-xs uppercase tracking-wider bg-white border border-[#E5E7EB] text-[#16151A] hover:bg-gray-50 transition">
                    Lihat Riwayat Transaksi
                </a>
            </div>

        </div>

    </div>
</x-layouts.mobile-app>

