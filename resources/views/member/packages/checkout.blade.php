<x-member-layout title="Detail Pembayaran & Konfirmasi - FitCore Athletic" active-nav="packages">
    <div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8 text-slate-900 border-b border-slate-200">
        <div class="mx-auto max-w-6xl">
            <!-- Breadcrumb Navigation -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('member.membership.packages') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#BA0030] transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Kembali ke Katalog Paket
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
                    Detail Pembayaran &amp; Konfirmasi
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                    Pilih rekening bank tujuan transfer resmi FitCore Athletic untuk menyelesaikan aktivasi paket keanggotaan Anda.
                </p>
            </div>

            @php
                $hasPromo = $package->promo_price !== null && $package->promo_price < $package->price;
                $effectivePrice = $hasPromo ? $package->promo_price : $package->price;
                $uniqueCode = 421;
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
                    <!-- Kolom Kiri: Card Rekening Transfer -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                                <div>
                                    <h2 class="font-heading text-lg font-extrabold text-slate-900 flex items-center gap-2">
                                        <svg class="h-5 w-5 text-[#BA0030]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="5" width="18" height="14" rx="2" />
                                            <path d="M3 10h18" />
                                        </svg>
                                        Rekening Transfer :
                                    </h2>
                                    <p class="mt-1 text-xs text-slate-500">Pilih salah satu rekening bank resmi FitCore Athletic tujuan transfer Anda:</p>
                                </div>
                            </div>

                            <!-- List Pilihan Bank dengan Radio Button Interaktif -->
                            <div class="space-y-3.5">
                                @forelse ($banks as $index => $bank)
                                    <label
                                        class="relative flex items-center justify-between p-4 sm:p-5 rounded-2xl border cursor-pointer transition-all duration-200"
                                        x-bind:class="selectedBank == '{{ $bank->id }}' ? 'border-[#BA0030] bg-rose-50/40 ring-2 ring-[#BA0030]/20 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50'"
                                    >
                                        <div class="flex items-center gap-4">
                                            <input
                                                type="radio"
                                                name="bank_id"
                                                value="{{ $bank->id }}"
                                                x-model="selectedBank"
                                                class="h-4 w-4 text-[#BA0030] focus:ring-[#BA0030] border-slate-300"
                                                {{ $index === 0 ? 'checked' : '' }}
                                            />
                                            <div>
                                                <div class="flex items-center gap-2.5">
                                                    <span class="font-heading font-extrabold text-slate-900 text-sm sm:text-base">
                                                        {{ $bank->name }}
                                                    </span>
                                                    <span class="rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs font-bold text-slate-700">
                                                        {{ $bank->account_number }}
                                                    </span>
                                                </div>
                                                <p class="mt-1 text-xs text-slate-500 font-medium">
                                                    Transfer via ATM / M-Banking / Internet Banking (a.n. {{ $bank->account_holder }})
                                                </p>
                                            </div>
                                        </div>
                                        <div class="hidden sm:flex flex-col items-end">
                                            <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                                Verifikasi Otomatis
                                            </span>
                                        </div>
                                    </label>
                                @empty
                                    <!-- Fallback bank list if empty -->
                                    <div class="p-4 rounded-2xl border border-slate-200 bg-white">
                                        <p class="text-xs text-slate-500">Rekening bank default FitCore Gym</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="mt-6 rounded-2xl bg-amber-50 border border-amber-200/80 p-4 flex items-start gap-3 text-xs text-amber-800">
                                <svg class="h-5 w-5 shrink-0 text-amber-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                                </svg>
                                <div>
                                    <p class="font-bold">Penting Sebelum Melakukan Transfer:</p>
                                    <p class="mt-0.5 text-amber-700 leading-relaxed">
                                        Pastikan transfer dilakukan tepat ke nomor rekening bank di atas beserta 3 digit kode unik pada langkah berikutnya agar transaksi dapat diverifikasi tanpa kendala.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Bantuan -->
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 flex items-center justify-between gap-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-bold text-xs text-slate-900">Butuh bantuan transaksi?</p>
                                    <p class="text-[11px] text-slate-500">Hubungi Customer Service Maxfit / WhatsApp CS</p>
                                </div>
                            </div>
                            <a href="https://wa.me/6281234567890" target="_blank" class="px-3.5 py-1.5 rounded-full text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-300 hover:bg-emerald-100 transition whitespace-nowrap">
                                WhatsApp CS
                            </a>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Card Ringkasan Langganan -->
                    <div class="lg:col-span-5 space-y-6">
                        <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">
                            <h2 class="font-heading text-lg font-extrabold text-slate-900 border-b border-slate-100 pb-4">
                                Ringkasan Langganan
                            </h2>

                            <!-- Package Info -->
                            <div class="mt-5 flex items-start justify-between gap-3">
                                <div>
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-rose-100 text-[#BA0030]">
                                        {{ $package->duration_value }} {{ strtoupper($package->duration_unit) }}
                                    </span>
                                    <h3 class="font-heading text-xl font-extrabold text-slate-900 mt-2">
                                        {{ $package->name }}
                                    </h3>
                                    <a href="{{ route('member.membership.packages') }}" class="inline-block text-xs font-bold text-[#BA0030] hover:underline mt-1">
                                        Ubah Paket
                                    </a>
                                </div>
                                <div class="text-right">
                                    <span class="font-heading text-lg font-extrabold text-slate-900">
                                        Rp {{ number_format($effectivePrice, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Input Form Kode Promo -->
                            <div class="mt-6 border-t border-slate-100 pt-5">
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    Punya kode promo atau referral?
                                </label>
                                <div class="flex gap-2">
                                    <input
                                        type="text"
                                        x-model="promoCode"
                                        placeholder="Contoh: FITCORENEW"
                                        class="w-full rounded-xl border-slate-200 text-xs text-slate-900 uppercase tracking-wider focus:border-[#BA0030] focus:ring-[#BA0030]"
                                    />
                                    <button
                                        type="button"
                                        x-on:click="applyPromo()"
                                        class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition shrink-0"
                                    >
                                        Terapkan
                                    </button>
                                </div>
                                <div x-show="promoApplied" x-cloak class="mt-2 text-xs font-semibold text-emerald-600 flex items-center gap-1.5">
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Kode promo berhasil diterapkan (-Rp 50.000)
                                </div>
                            </div>

                            <!-- Rincian Biaya -->
                            <div class="mt-6 border-t border-slate-100 pt-5 space-y-3 text-xs">
                                <div class="flex justify-between text-slate-600">
                                    <span>Biaya Langganan</span>
                                    <span class="font-bold text-slate-900">Rp {{ number_format($effectivePrice, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-slate-600">
                                    <span>Biaya Layanan Aplikasi</span>
                                    <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">
                                        GRATIS
                                    </span>
                                </div>
                                <div class="flex justify-between text-slate-600">
                                    <span class="flex items-center gap-1">
                                        Kode Unik Verifikasi
                                        <span class="text-slate-400" title="Untuk identifikasi verifikasi transfer bank">(?)</span>
                                    </span>
                                    <span class="font-bold text-slate-900">+Rp {{ $uniqueCode }}</span>
                                </div>
                                <div x-show="promoApplied" x-cloak class="flex justify-between text-emerald-600 font-bold">
                                    <span>Diskon Promo</span>
                                    <span>-Rp 50.000</span>
                                </div>
                            </div>

                            <!-- Box Hitam Pekat Total Pembayaran -->
                            <div class="mt-6 rounded-2xl bg-[#0B0F17] p-5 text-white shadow-md">
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">
                                    TOTAL PEMBAYARAN :
                                </span>
                                <div class="mt-1 flex items-baseline justify-between">
                                    <span class="font-heading text-2xl sm:text-3xl font-black text-white tracking-tight" x-text="'Rp ' + total.toLocaleString('id-ID')">
                                        Rp {{ number_format($totalFinal, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-rose-400 font-semibold">Tepat s/d 3 digit terakhir</span>
                                </div>
                            </div>

                            <!-- Checkbox Konfirmasi Syarat & Ketentuan -->
                            <div class="mt-6">
                                <label class="flex items-start gap-3 cursor-pointer text-xs text-slate-600">
                                    <input
                                        type="checkbox"
                                        required
                                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-[#BA0030] focus:ring-[#BA0030]"
                                    />
                                    <span class="leading-relaxed">
                                        Saya menyetujui <span class="text-slate-900 font-bold underline">Syarat &amp; Ketentuan Keanggotaan</span> FitCore Athletic yang berlaku.
                                    </span>
                                </label>
                            </div>

                            <!-- Tombol CTA Beli Sekarang -->
                            <div class="mt-6">
                                <button
                                    type="submit"
                                    class="w-full py-4 rounded-full bg-[#BA0030] hover:bg-[#970027] text-white font-bold text-sm tracking-wide shadow-lg shadow-[#BA0030]/30 transition hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2"
                                >
                                    <span>Beli Sekarang</span>
                                    <span class="text-lg">→</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-member-layout>
