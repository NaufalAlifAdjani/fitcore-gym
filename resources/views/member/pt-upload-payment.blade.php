<x-member-layout title="Upload Bukti Pembayaran - FitCore Athletic" active-nav="pt">
    <div class="bg-[#F6F6F7] min-h-[calc(100vh-80px)] py-12 px-4 sm:px-6 lg:px-8 text-[#16151A] border-b border-[#E5E7EB]">
        <div class="mx-auto max-w-6xl">
            <!-- Breadcrumb Navigation -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('member.pt-packages.checkout', ['package_id' => $payment?->pt_package_id ?? request('package_id'), 'trainer_id' => request('trainer_id', 1)]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#565A66] hover:text-[#FF5722] transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Kembali ke Rincian Pesanan
                </a>
                <div class="flex items-center gap-2 text-xs font-bold text-[#565A66]">
                    <span class="text-[#565A66]/60">Langkah 1: Konfirmasi Pembayaran</span>
                    <span>•</span>
                    <span class="text-[#FF5722]">Langkah 2: Form Bukti Transfer</span>
                </div>
            </div>

            <!-- Header Section -->
            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#16151A] tracking-tight">
                        Upload Bukti Pembayaran
                    </h1>
                    <p class="mt-2 text-xs sm:text-sm text-[#565A66] leading-relaxed font-medium">
                        Selesaikan transfer sesuai nominal tepat dan unggah struk pembayaran resmi Anda untuk verifikasi instan tim finance.
                    </p>
                </div>

                <!-- Pill Countdown Timer (Light Theme) -->
                <div class="bg-white text-[#16151A] rounded-2xl px-5 py-3 flex items-center gap-3.5 shadow-[0_4px_12px_rgba(0,0,0,0.04)] self-start md:self-auto shrink-0 border border-[#E5E7EB]"
                     x-data="{ 
                         hours: 23, 
                         minutes: 59, 
                         seconds: 59,
                         init() {
                             setInterval(() => {
                                 if (this.seconds > 0) {
                                     this.seconds--;
                                 } else if (this.minutes > 0) {
                                     this.minutes--;
                                     this.seconds = 59;
                                 } else if (this.hours > 0) {
                                     this.hours--;
                                     this.minutes = 59;
                                     this.seconds = 59;
                                 }
                             }, 1000);
                         }
                     }">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-[#FF5722] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-[#565A66] uppercase tracking-widest block leading-none">
                            BATAS WAKTU TRANSFER
                        </span>
                        <span class="text-sm font-black text-[#FF5722] tracking-widest block font-mono mt-0.5" 
                              x-text="String(hours).padStart(2,'0') + ':' + String(minutes).padStart(2,'0') + ':' + String(seconds).padStart(2,'0')">
                            23:59:59
                        </span>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-8 rounded-2xl bg-rose-50 border border-rose-200 p-5 text-xs text-rose-800">
                    <p class="font-bold mb-2">Mohon periksa kembali formulir Anda:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 items-start">
                <!-- Kolom Kiri: Ringkasan Pesanan & Rekening Tujuan Transfer -->
                <div class="lg:col-span-6 space-y-6">
                    <!-- 1. Ringkasan Pesanan Card -->
                    <x-card :padding="false" class="!rounded-3xl border border-[#E5E7EB] p-6 sm:p-7 shadow-[0_4px_12px_rgba(0,0,0,0.04)] bg-white">
                        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3 mb-4">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-[#565A66]">
                                Ringkasan Pesanan
                            </h2>
                            <span class="text-xs font-mono font-bold text-[#16151A]">
                                {{ $orderNumber }}
                            </span>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="relative h-20 w-24 shrink-0 overflow-hidden rounded-2xl bg-orange-50 border border-orange-200/80 flex items-center justify-center shadow-xs">
                                <svg class="h-8 w-8 text-[#FF5722]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6.5 6.5h11M6.5 17.5h11M4 9.5h16M4 14.5h16M8.5 4.5v15M15.5 4.5v15" stroke-linecap="round"/>
                                </svg>
                                <span class="absolute bottom-1 right-1.5 text-[8px] font-black text-[#FF5722]">PT SESI</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-orange-100 text-[#FF5722] mb-1">
                                    PERSONAL TRAINER
                                </span>
                                <h3 class="font-heading text-lg font-extrabold text-[#16151A] truncate">
                                    {{ $packageName }}
                                </h3>
                                <p class="text-xs text-[#565A66] font-medium">
                                    Pelatih: <span class="text-[#16151A] font-semibold">{{ $trainerName }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Box Total Pembayaran (Light Athletic Palette) -->
                        <div class="mt-5 rounded-2xl bg-gradient-to-r from-orange-50 via-white to-amber-50/50 border border-orange-200 p-5 text-[#16151A] shadow-xs">
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#565A66]">
                                TOTAL PEMBAYARAN :
                            </span>
                            <div class="mt-1 flex items-baseline justify-between">
                                <span class="font-heading text-2xl sm:text-3xl font-black text-[#FF5722] tracking-tight">
                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                </span>
                                <span class="text-[11px] text-[#FF5722] font-semibold">Termasuk kode unik: +{{ $uniqueCode }}</span>
                            </div>
                        </div>
                    </x-card>

                    <!-- 2. Rekening Tujuan Transfer Card -->
                    <x-payment.bank-account-card :bank="$bank" />

                    <!-- 3. Card Bantuan CS -->
                    <x-payment.support-card />
                </div>

                <!-- Kolom Kanan: Formulir Upload Bukti Transfer -->
                <div class="lg:col-span-6 space-y-6">
                    <x-payment.upload-proof-form
                        :action="route('member.pt-packages.submit-proof', ['payment' => $payment?->id])"
                        :default-sender-bank="is_object($bank) ? ($bank->name ?? 'BCA') : 'BCA'"
                    />
                </div>
            </div>
        </div>
    </div>
</x-member-layout>
