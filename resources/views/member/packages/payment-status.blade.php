<x-member-layout title="Status Pembayaran - FitCore Athletic" active-nav="riwayat">
<<<<<<< HEAD
    <div class="py-16 px-4 sm:px-6 lg:px-8 bg-[#F6F6F7] min-h-[calc(100vh-80px)] flex items-center justify-center">
        <div class="mx-auto max-w-xl w-full">
            <div class="rounded-3xl border border-[#E5E7EB] bg-white p-8 sm:p-10 shadow-[0_4px_12px_rgba(0,0,0,0.04)] text-center">
=======
    <div class="py-16 px-4 sm:px-6 lg:px-8 bg-[#F8F9FA] min-h-screen flex items-center justify-center">
        <div class="mx-auto max-w-xl w-full">
            <div class="rounded-3xl border border-gray-200 bg-white p-8 sm:p-10 shadow-xl text-center">
>>>>>>> origin/feature/theme-blaze-orange
                <!-- Status Icon -->
                @if ($payment->status === 'pending')
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-amber-50 text-amber-600 border border-amber-200">
                        <svg class="h-10 w-10 animate-pulse" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                @elseif ($payment->status === 'verified')
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">
                        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            <path d="M8 12l3 3 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                @else
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-rose-50 text-rose-600 border border-rose-200">
                        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            <path d="M15 9l-6 6M9 9l6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                @endif

<<<<<<< HEAD
                <h1 class="font-heading text-2xl font-extrabold text-[#16151A] mt-6 sm:text-3xl">
=======
                <h1 class="font-heading text-2xl font-extrabold text-zinc-900 mt-6 sm:text-3xl">
>>>>>>> origin/feature/theme-blaze-orange
                    @if ($payment->status === 'pending')
                        Bukti Pembayaran Diterima
                    @elseif ($payment->status === 'verified')
                        Pembayaran Terverifikasi
                    @else
                        Pembayaran Ditolak
                    @endif
                </h1>

<<<<<<< HEAD
                <p class="mt-2 text-xs leading-relaxed text-[#565A66]">
=======
                <p class="mt-2 text-xs leading-relaxed text-zinc-600">
>>>>>>> origin/feature/theme-blaze-orange
                    @if ($payment->status === 'pending')
                        Terima kasih! Bukti pembayaran Anda sedang diproses oleh staf FitCore Gym. Verifikasi dilakukan maksimal <span class="font-bold text-amber-600">1x24 jam</span>.
                    @elseif ($payment->status === 'verified')
                        Selamat! Pembayaran Anda telah terkonfirmasi. Keanggotaan Anda sekarang sudah aktif dan siap digunakan.
                    @else
                        Maaf, bukti pembayaran Anda belum dapat kami konfirmasi: <span class="font-bold text-rose-600">{{ $payment->rejection_reason }}</span>. Silakan hubungi admin kami.
                    @endif
                </p>

                <!-- Status Badge Component -->
                <div class="mt-4 flex justify-center">
                    <x-badge :status="$payment->status" size="lg" />
                </div>

<<<<<<< HEAD
                <!-- Receipt Details Card (Light Theme) -->
                <div class="mt-8 rounded-2xl border border-[#E5E7EB] bg-[#F6F6F7] p-6 text-left space-y-3.5 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-[#565A66]">Nomor Invoice</span>
                        <span class="font-mono font-bold text-[#16151A]">{{ $payment->invoice_id }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[#565A66]">Paket Pesanan</span>
                        <span class="font-bold text-[#16151A]">{{ $payment->ptPackage?->name ?? $payment->membershipPackage?->name ?? 'Paket FitCore' }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[#565A66]">Nominal Transfer</span>
                        <span class="font-bold text-[#ED1B45]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[#565A66]">Bank Pengirim</span>
                        <span class="font-semibold text-[#16151A]">{{ $payment->bank_sender }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[#565A66]">Tujuan Bank Gym</span>
                        <span class="font-semibold text-[#16151A]">{{ $payment->bank_destination }}</span>
                    </div>

                    <div class="flex justify-between items-center border-t border-[#E5E7EB] pt-3">
                        <span class="text-[#565A66]">Waktu Pengiriman</span>
                        <span class="text-[#16151A]">{{ $payment->transfer_date?->timezone(config('app.timezone'))->format('d M Y, H:i') ?? '-' }} WIB</span>
=======
                <!-- Receipt Details Card (Light Athletic Box) -->
                <div class="mt-8 rounded-2xl border border-orange-100 bg-orange-50/50 p-6 text-left space-y-3.5 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Nomor Invoice</span>
                        <span class="font-mono font-bold text-zinc-900">{{ $payment->invoice_id }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Paket Pesanan</span>
                        <span class="font-bold text-zinc-800">{{ $payment->ptPackage?->name ?? $payment->membershipPackage?->name ?? 'Paket FitCore' }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Nominal Transfer</span>
                        <span class="font-bold text-[#FF5722]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Bank Pengirim</span>
                        <span class="font-semibold text-zinc-800">{{ $payment->bank_sender }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Tujuan Bank Gym</span>
                        <span class="font-semibold text-zinc-800">{{ $payment->bank_destination }}</span>
                    </div>

                    <div class="flex justify-between items-center border-t border-orange-200/60 pt-3">
                        <span class="text-zinc-500">Waktu Pengiriman</span>
                        <span class="text-zinc-700">{{ $payment->transfer_date?->timezone(config('app.timezone'))->format('d M Y, H:i') ?? '-' }} WIB</span>
>>>>>>> origin/feature/theme-blaze-orange
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    @if ($payment->ptPackage || in_array($payment->package_type, ['pt_session', 'pt_package']) || $payment->payment_type === 'pt_package')
                        <x-button href="{{ route('member.pt-packages.index') }}" variant="outline" class="w-full sm:w-auto justify-center">
                            Kembali ke Paket PT
                        </x-button>
                    @else
                        <x-button href="{{ route('member.membership.packages') }}" variant="outline" class="w-full sm:w-auto justify-center">
                            Kembali ke Katalog Paket
                        </x-button>
                    @endif
                    <x-button href="{{ route('dashboard') }}" variant="primary" class="w-full sm:w-auto justify-center">
                        Ke Dashboard Member
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-member-layout>
