<x-member-layout title="Upload Bukti Pembayaran - FitCore Athletic" active-nav="pt">
    <div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8 text-slate-900 border-b border-slate-200">
        <div class="mx-auto max-w-6xl">
            <!-- Breadcrumb Navigation -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('member.pt-packages.checkout', ['package_id' => $payment?->pt_package_id ?? request('package_id'), 'trainer_id' => request('trainer_id', 1)]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#BA0030] transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Kembali ke Rincian Pesanan
                </a>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span class="text-slate-400">Langkah 1: Konfirmasi Pembayaran</span>
                    <span>•</span>
                    <span class="text-[#BA0030]">Langkah 2: Form Bukti Transfer</span>
                </div>
            </div>

            <!-- Header Section -->
            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Upload Bukti Pembayaran
                    </h1>
                    <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                        Selesaikan transfer sesuai nominal tepat dan unggah struk pembayaran resmi Anda untuk verifikasi instan tim finance.
                    </p>
                </div>

                <!-- Pill Countdown Timer Hitam -->
                <div class="bg-[#0B0F17] text-white rounded-2xl px-5 py-3 flex items-center gap-3.5 shadow-md self-start md:self-auto shrink-0 border border-slate-800"
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
                    <div class="w-8 h-8 rounded-xl bg-rose-500/20 text-[#BA0030] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none">
                            BATAS WAKTU TRANSFER
                        </span>
                        <span class="text-sm font-black text-white tracking-widest block font-mono mt-0.5" 
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
                    <x-card :padding="false" class="!rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-sm bg-white">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                Ringkasan Pesanan
                            </h2>
                            <span class="text-xs font-mono font-bold text-slate-500">
                                {{ $orderNumber }}
                            </span>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="relative h-20 w-24 shrink-0 overflow-hidden rounded-2xl bg-slate-900 flex items-center justify-center border border-slate-800 shadow-inner">
                                <svg class="h-8 w-8 text-[#BA0030]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6.5 6.5h11M6.5 17.5h11M4 9.5h16M4 14.5h16M8.5 4.5v15M15.5 4.5v15" stroke-linecap="round"/>
                                </svg>
                                <span class="absolute bottom-1 right-1.5 text-[8px] font-black text-rose-300">PT SESI</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-rose-100 text-[#BA0030] mb-1">
                                    PERSONAL TRAINER
                                </span>
                                <h3 class="font-heading text-lg font-extrabold text-slate-900 truncate">
                                    {{ $packageName }}
                                </h3>
                                <p class="text-xs text-slate-500 font-medium">
                                    Pelatih: <span class="text-slate-800 font-semibold">{{ $trainerName }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Box Hitam Total Pembayaran -->
                        <div class="mt-5 rounded-2xl bg-[#0B0F17] p-5 text-white shadow-md">
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                                TOTAL PEMBAYARAN :
                            </span>
                            <div class="mt-1 flex items-baseline justify-between">
                                <span class="font-heading text-2xl sm:text-3xl font-black text-white tracking-tight">
                                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                </span>
                                <span class="text-[11px] text-rose-400 font-semibold">Termasuk kode unik: +{{ $uniqueCode }}</span>
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
