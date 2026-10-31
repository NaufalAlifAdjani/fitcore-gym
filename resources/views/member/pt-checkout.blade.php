<x-member-layout title="Rincian Pesanan & Pembayaran - FitCore Athletic" active-nav="pt">
    <div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8 text-slate-900 border-b border-slate-200">
        <div class="mx-auto max-w-6xl">
            <!-- Breadcrumb Navigation -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('member.pt-packages.select-trainer', ['package_id' => $packageId, 'sessions' => $sessionCount]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#BA0030] transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Kembali ke Pilih Pelatih
                </a>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span class="text-[#BA0030]">Langkah 1: Konfirmasi Pembayaran</span>
                    <span>•</span>
                    <span class="text-slate-400">Langkah 2: Form Bukti Transfer</span>
                </div>
            </div>

            <!-- Header Section -->
            <div class="mb-10">
                <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Rincian Pesanan &amp; Pembayaran
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                    Periksa kembali paket sesi personal trainer dan pilih rekening bank tujuan transfer resmi FitCore Athletic.
                </p>
            </div>

            @php
                $defaultBankId = $banks->first()?->id ?? '';
                $uniqueCode = (int) ($uniqueCode ?? random_int(100, 999));
                $totalFinal = (int) ($subtotal + $uniqueCode);
            @endphp

            <form action="{{ route('member.pt-packages.process-checkout') }}" method="POST" x-data="{
                selectedBank: '{{ $defaultBankId }}',
                promoCode: '',
                promoApplied: false,
                discount: 0,
                basePrice: {{ $subtotal }},
                uniqueCode: {{ $uniqueCode }},
                get total() {
                    return this.basePrice - this.discount + this.uniqueCode;
                },
                applyPromo() {
                    if (this.promoCode.trim().toUpperCase() === 'FITCORENEW' || this.promoCode.trim().toUpperCase() === 'MAXFIT') {
                        this.discount = 50000;
                        this.promoApplied = true;
                    } else if (this.promoCode.trim().length > 0) {
                        alert('Kode promo tidak valid atau telah kedaluwarsa.');
                    }
                }
            }">
                @csrf
                <input type="hidden" name="package_id" value="{{ $packageId }}">
                <input type="hidden" name="trainer_id" value="{{ $trainerId }}">
                <input type="hidden" name="unique_code" :value="uniqueCode">
                <input type="hidden" name="total_amount" :value="total">

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 items-start">
                    <!-- Kolom Kiri: Pilihan Bank & Bantuan -->
                    <div class="lg:col-span-7 space-y-6">
                        <x-payment.bank-selector :banks="$banks" :selected-bank="$defaultBankId" />
                        <x-payment.support-card />
                    </div>

                    <!-- Kolom Kanan: Card Ringkasan Pesanan -->
                    <div class="lg:col-span-5 space-y-6">
                        <x-payment.order-summary
                            :badge="$sessionCount . ' SESI PERSONAL TRAINER'"
                            :title="$packageName"
                            :trainer-name="$selectedTrainerName"
                            :change-url="route('member.pt-packages.index')"
                            :base-price="$subtotal"
                            :unique-code="$uniqueCode"
                            :total-amount="$totalFinal"
                            terms-text="Saya menyetujui Syarat & Ketentuan Layanan Personal Trainer FitCore Athletic yang berlaku."
                        />
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-member-layout>
