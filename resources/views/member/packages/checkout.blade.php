<x-member-layout title="Detail Pembayaran & Konfirmasi - FitCore Athletic" active-nav="packages">
    <div class="bg-[#F6F6F7] min-h-[calc(100vh-80px)] py-12 px-4 sm:px-6 lg:px-8 text-[#16151A] border-b border-[#E5E7EB]">
        <div class="mx-auto max-w-6xl">
            <!-- Breadcrumb Navigation -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('member.membership.packages') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#565A66] hover:text-[#ED1B45] transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Kembali ke Katalog Paket
                </a>
                <div class="flex items-center gap-2 text-xs font-bold text-[#565A66]">
                    <span class="text-[#ED1B45]">Langkah 1: Konfirmasi Pembayaran</span>
                    <span>•</span>
                    <span class="text-[#565A66]/60">Langkah 2: Form Bukti Transfer</span>
                </div>
            </div>

            <!-- Header Section -->
            <div class="mb-10">
                <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#16151A] tracking-tight">
                    Detail Pembayaran &amp; Konfirmasi
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-[#565A66] leading-relaxed font-medium">
                    Pilih rekening bank tujuan transfer resmi FitCore Athletic untuk menyelesaikan aktivasi paket keanggotaan Anda.
                </p>
            </div>

            @php
                $hasPromo = $package->promo_price !== null && $package->promo_price < $package->price;
                $effectivePrice = $hasPromo ? $package->promo_price : $package->price;
                $uniqueCode = (int) ($uniqueCode ?? random_int(100, 999));
                $totalFinal = $effectivePrice + $uniqueCode;
                $defaultBankId = $banks->first()?->id;
            @endphp

            <form action="{{ route('member.membership.upload-proof', $package->id) }}" method="GET" x-data="{
                selectedBank: '{{ $defaultBankId }}',
                promoCode: '',
                promoApplied: false,
                discount: 0,
                basePrice: {{ $effectivePrice }},
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
                <input type="hidden" name="unique_code" :value="uniqueCode">

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 items-start">
                    <!-- Kolom Kiri: Pilihan Bank & Bantuan -->
                    <div class="lg:col-span-7 space-y-6">
                        <x-payment.bank-selector :banks="$banks" :selected-bank="$defaultBankId" />
                        <x-payment.support-card />
                    </div>

                    <!-- Kolom Kanan: Card Ringkasan Langganan -->
                    <div class="lg:col-span-5 space-y-6">
                        <x-payment.order-summary
                            :badge="$package->duration_value . ' ' . strtoupper($package->duration_unit)"
                            :title="$package->name"
                            :change-url="route('member.membership.packages')"
                            :base-price="$effectivePrice"
                            :unique-code="$uniqueCode"
                            :total-amount="$totalFinal"
                        />
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-member-layout>
