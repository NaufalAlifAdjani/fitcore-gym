<x-member-layout title="Status Pembayaran - FitCore Athletic" active-nav="riwayat">
    <div class="py-16 px-4 sm:px-6 lg:px-8 bg-[#0B0F17] min-h-screen flex items-center justify-center">
        <div class="mx-auto max-w-xl w-full">
            <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-8 sm:p-10 shadow-2xl text-center">
                <!-- Status Icon -->
                @if ($payment->status === 'pending')
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/30">
                        <svg class="h-10 w-10 animate-pulse" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                @elseif ($payment->status === 'verified')
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/30">
                        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            <path d="M8 12l3 3 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                @else
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-rose-500/10 text-rose-500 border border-rose-500/30">
                        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            <path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                @endif

                <h1 class="font-heading text-2xl font-extrabold text-white mt-6 sm:text-3xl">
                    @if ($payment->status === 'pending')
                        Bukti Pembayaran Diterima
                    @elseif ($payment->status === 'verified')
                        Pembayaran Terverifikasi
                    @else
                        Pembayaran Ditolak
                    @endif
                </h1>

                <p class="mt-2 text-xs leading-relaxed text-slate-300">
                    @if ($payment->status === 'pending')
                        Terima kasih! Bukti pembayaran Anda sedang diproses oleh staf FitCore Gym. Verifikasi dilakukan maksimal <span class="font-bold text-amber-400">1x24 jam</span>.
                    @elseif ($payment->status === 'verified')
                        Selamat! Pembayaran Anda telah terkonfirmasi. Keanggotaan Anda sekarang sudah aktif dan siap digunakan.
                    @else
                        Maaf, bukti pembayaran Anda belum dapat kami konfirmasi: <span class="font-bold text-rose-400">{{ $payment->rejection_reason }}</span>. Silakan hubungi admin kami.
                    @endif
                </p>

                <!-- Status Badge Component -->
                <div class="mt-4 flex justify-center">
                    <x-badge :status="$payment->status" size="lg" />
                </div>

                <!-- Receipt Details Card -->
                <div class="mt-8 rounded-2xl border border-slate-800 bg-slate-950 p-6 text-left space-y-3.5 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Nomor Invoice</span>
                        <span class="font-mono font-bold text-white">{{ $payment->invoice_id }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Paket Keanggotaan</span>
                        <span class="font-bold text-slate-200">{{ $payment->membershipPackage?->name ?? 'Membership' }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Nominal Transfer</span>
                        <span class="font-bold text-[#BA0030]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Bank Pengirim</span>
                        <span class="font-semibold text-slate-200">{{ $payment->bank_sender }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Tujuan Bank Gym</span>
                        <span class="font-semibold text-slate-200">{{ $payment->bank_destination }}</span>
                    </div>

                    <div class="flex justify-between items-center border-t border-slate-800/80 pt-3">
                        <span class="text-slate-400">Waktu Pengiriman</span>
                        <span class="text-slate-300">{{ $payment->transfer_date?->timezone(config('app.timezone'))->format('d M Y, H:i') ?? '-' }} WIB</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <x-button href="{{ route('member.membership.packages') }}" variant="outline" class="w-full sm:w-auto justify-center">
                        Kembali ke Katalog Paket
                    </x-button>
                    <x-button href="{{ route('dashboard') }}" variant="primary" class="w-full sm:w-auto justify-center">
                        Ke Dashboard Member
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-member-layout>
