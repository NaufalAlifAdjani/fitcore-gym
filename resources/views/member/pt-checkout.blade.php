<x-fitcore-layout :title="'Rincian Pesanan & Pembayaran | FITCORE ATHLETIC'">

    {{-- BANNER HEADER GELAP --}}
    <section class="bg-[#18181B] py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight">
                Rincian Pesanan &amp; Pembayaran
            </h1>
        </div>
    </section>

    {{-- KONTEN UTAMA --}}
    <section class="bg-[#F8F9FA] py-12" x-data="{
        selectedBank: 'bca',
        appliedPromo: '{{ $promoCode }}',
        discount: {{ $discountAmount }},
        subtotal: {{ $subtotal }},
        uniqueCode: {{ $uniqueCode }},
        agreed: true
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <form action="{{ route('member.pt-packages.process-checkout') }}" method="POST">
                @csrf
                <input type="hidden" name="package_id" value="{{ $packageId }}">
                <input type="hidden" name="trainer_id" value="{{ $trainerId }}">
                <input type="hidden" name="total_amount" value="{{ $totalAmount }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    {{-- KOLOM KIRI: PILIHAN REKENING TRANSFER --}}
                    <div class="lg:col-span-5">
                        <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border border-gray-100">
                            
                            <h2 class="text-xs font-black text-zinc-900 tracking-wider uppercase mb-5">
                                REKENING TRANSFER :
                            </h2>

                            <div class="space-y-3.5">
                                
                                {{-- Bank BCA (Selected by default) --}}
                                <label class="block rounded-2xl p-4 cursor-pointer transition border relative"
                                       :class="selectedBank === 'bca' ? 'bg-[#F4F6FF] border-[#C7D2FE]' : 'bg-white border-gray-200 hover:border-gray-300'">
                                    <div class="flex items-start gap-3.5">
                                        <div class="mt-0.5 shrink-0">
                                            <input type="radio" name="bank_id" value="1" x-model="selectedBank" class="hidden">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition"
                                                 :class="selectedBank === 'bca' ? 'border-[#E11D48]' : 'border-gray-300'">
                                                <div class="w-2.5 h-2.5 rounded-full bg-[#E11D48]" x-show="selectedBank === 'bca'"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-black text-zinc-900 text-sm">Bank BCA</h3>
                                            <p class="text-xs text-gray-500 mt-0.5">Transfer via ATM / M Banking / Internet Banking</p>
                                        </div>
                                    </div>
                                </label>

                                {{-- Bank Mandiri --}}
                                <label class="block rounded-2xl p-4 cursor-pointer transition border relative"
                                       :class="selectedBank === 'mandiri' ? 'bg-[#F4F6FF] border-[#C7D2FE]' : 'bg-white border-gray-200 hover:border-gray-300'">
                                    <div class="flex items-start gap-3.5">
                                        <div class="mt-0.5 shrink-0">
                                            <input type="radio" name="bank_id" value="2" x-model="selectedBank" class="hidden">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition"
                                                 :class="selectedBank === 'mandiri' ? 'border-[#E11D48]' : 'border-gray-300'">
                                                <div class="w-2.5 h-2.5 rounded-full bg-[#E11D48]" x-show="selectedBank === 'mandiri'"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-black text-zinc-900 text-sm">Bank Mandiri</h3>
                                            <p class="text-xs text-gray-500 mt-0.5">Transfer via Livin' / ATM / E-Banking</p>
                                        </div>
                                    </div>
                                </label>

                                {{-- Bank BNI --}}
                                <label class="block rounded-2xl p-4 cursor-pointer transition border relative"
                                       :class="selectedBank === 'bni' ? 'bg-[#F4F6FF] border-[#C7D2FE]' : 'bg-white border-gray-200 hover:border-gray-300'">
                                    <div class="flex items-start gap-3.5">
                                        <div class="mt-0.5 shrink-0">
                                            <input type="radio" name="bank_id" value="3" x-model="selectedBank" class="hidden">
                                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition"
                                                 :class="selectedBank === 'bni' ? 'border-[#E11D48]' : 'border-gray-300'">
                                                <div class="w-2.5 h-2.5 rounded-full bg-[#E11D48]" x-show="selectedBank === 'bni'"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-black text-zinc-900 text-sm">Bank BNI</h3>
                                            <p class="text-xs text-gray-500 mt-0.5">Transfer via BNI Mobile / ATM / SMS Banking</p>
                                        </div>
                                    </div>
                                </label>

                            </div>

                        </div>
                    </div>

                    {{-- KOLOM KANAN: RINGKASAN PEMBELIAN & CHECKOUT --}}
                    <div class="lg:col-span-7 space-y-4">
                        
                        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100">
                            
                            <h2 class="text-lg font-black text-zinc-900 mb-5">
                                Ringkasan Pembelian Sesi PT
                            </h2>

                            {{-- Box Highlight Pelatih Terpilih --}}
                            <div class="bg-[#F4F6FF] rounded-2xl p-5 mb-6">
                                <span class="text-[10px] font-black text-[#E11D48] tracking-widest uppercase block">
                                    PELATIH TERPILIH
                                </span>
                                <h3 class="text-lg sm:text-xl font-black text-zinc-900 mt-1 mb-2.5">
                                    {{ $selectedTrainerName }}
                                </h3>

                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2.5 text-xs font-bold text-zinc-800">
                                        <svg class="w-4 h-4 text-[#E11D48]" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M6 5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V5zm9 0a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1V5zM4 9a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9zm12 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V9zM8 11h8v2H8v-2z"/>
                                        </svg>
                                        <span>{{ $packageName }}</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs text-gray-500 font-medium">
                                        <svg class="w-4 h-4 text-[#E11D48]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                            <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                                        </svg>
                                        <span>Durasi 60 Menit / Pertemuan • Berlaku 60 Hari</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Form Kode Promo / Referral --}}
                            <div class="mb-6">
                                <label class="text-[10px] font-black text-zinc-900 tracking-wider uppercase block mb-2">
                                    PUNYA KODE PROMO ATAU REFERRAL?
                                </label>
                                <div class="flex gap-2.5">
                                    <input type="text" 
                                           x-model="appliedPromo" 
                                           class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-zinc-800 flex-1 focus:ring-2 focus:ring-[#E11D48] focus:border-transparent outline-none">
                                    <button type="button" 
                                            class="bg-[#EEF2FF] hover:bg-indigo-100 text-zinc-800 font-bold text-xs rounded-xl px-5 py-2.5 transition active:scale-95">
                                        Terapkan
                                    </button>
                                </div>
                            </div>

                            {{-- Rincian Biaya --}}
                            <div class="space-y-3 pt-2 text-xs sm:text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-zinc-700 font-medium">Subtotal Paket PT ({{ $sessionCount }} Sesi)</span>
                                    <span class="font-black text-zinc-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-[#10B981]">
                                    <span class="font-bold flex items-center gap-1.5">
                                        <span>Diskon Voucher ({{ $promoCode }})</span>
                                        <span class="text-xs">✓</span>
                                    </span>
                                    <span class="font-black">-Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-zinc-700 font-medium flex items-center gap-1">
                                        <span>Kode Unik Verifikasi</span>
                                        <span class="w-3.5 h-3.5 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-[9px] font-bold">?</span>
                                    </span>
                                    <span class="font-black text-[#E11D48]">+RP {{ $uniqueCode }}</span>
                                </div>
                            </div>

                            {{-- Box Total Pembayaran Gelap --}}
                            <div class="bg-[#18181B] text-white rounded-2xl p-5 my-6 flex items-center justify-between shadow-sm">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 tracking-wider uppercase block">
                                        TOTAL PEMBAYARAN
                                    </span>
                                    <span class="text-2xl sm:text-3xl font-black text-white tracking-tight mt-0.5 block">
                                        Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Checkbox Persetujuan --}}
                            <div class="flex items-start gap-3 mt-4">
                                <input type="checkbox" 
                                       id="agree_terms" 
                                       x-model="agreed"
                                       required
                                       class="mt-1 w-4 h-4 rounded border-gray-300 text-[#E11D48] focus:ring-[#E11D48] cursor-pointer">
                                <label for="agree_terms" class="text-xs text-gray-600 leading-relaxed cursor-pointer">
                                    Saya menyetujui <span class="text-[#C80036] font-bold">Syarat &amp; Ketentuan Keanggotaan</span> serta aturan keselamatan Maxfit Fitness Studio.
                                </label>
                            </div>

                            {{-- Tombol Beli Sekarang --}}
                            <button type="submit" 
                                    :disabled="!agreed"
                                    class="w-full bg-[#C80036] hover:bg-[#A8002C] text-white rounded-full py-4 font-black text-sm sm:text-base shadow-lg shadow-rose-900/20 active:scale-[0.99] transition block text-center mt-6 uppercase tracking-wide cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                Beli Sekarang &rarr;
                            </button>

                        </div>

                        {{-- Card Bantuan CS --}}
                        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-full bg-[#F4F6FF] flex items-center justify-center text-[#E11D48] shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-xs sm:text-sm text-zinc-900">Butuh bantuan transaksi?</h4>
                                    <p class="text-xs text-gray-500">Hubungi Customer Service Maxfit</p>
                                </div>
                            </div>
                            <a href="https://wa.me/628119988771" target="_blank" class="text-xs font-black text-[#E11D48] hover:underline flex items-center gap-1 shrink-0">
                                <span>WhatsApp CS</span>
                            </a>
                        </div>

                    </div>

                </div>
            </form>

        </div>
    </section>

</x-fitcore-layout>
