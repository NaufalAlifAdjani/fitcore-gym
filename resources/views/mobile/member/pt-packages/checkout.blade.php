{{--
ROLE: MEMBER
MODUL: Checkout Paket Personal Trainer Mobile (Langkah 3)
VIEW: mobile.member.pt-packages.checkout
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

@php
    $defaultBankId = $banks->first()?->id ?? 1;
    $uniqueCode = (int) ($uniqueCode ?? random_int(100, 999));
    $totalFinal = (int) ($subtotal + $uniqueCode);
@endphp

<x-layouts.mobile-app title="Checkout Paket PT - FitCore Athletic" active-tab="packages">
    <div x-data="{
        selectedBankId: '{{ $defaultBankId }}',
        agreedTerms: false
    }" class="space-y-4">

        <!-- 1. Header & Back Navigation -->
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
            <a href="{{ route('mobile.member.pt-packages.select-trainer', ['package_id' => $packageId, 'sessions' => $sessionCount]) }}"
               class="p-1.5 text-[#565A66] hover:text-[#16151A] transition flex items-center gap-1 cursor-pointer"
               aria-label="Kembali ke Pilih Pelatih">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-xs font-semibold">Pilih Pelatih</span>
            </a>
            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-[#FF5722] border border-orange-200">
                Langkah 3 dari 4
            </span>
        </div>

        <!-- 2. Judul Halaman -->
        <div class="space-y-1">
            <h1 class="text-xl font-black font-poppins text-[#16151A] tracking-tight">
                Rincian &amp; Pembayaran
            </h1>
            <p class="text-xs text-[#565A66] leading-relaxed">
                Periksa kembali paket latihan Anda dan pilih rekening tujuan transfer.
            </p>
        </div>

        <!-- 3. Alert Error (jika ada) -->
        @if ($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 shadow-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Periksa kembali formulir Anda:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 4. Card Ringkasan Pesanan PT -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <div class="flex items-start justify-between border-b border-[#E5E7EB] pb-2.5">
                <div>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-orange-50 text-[#FF5722] border border-orange-200">
                        {{ $sessionCount }} SESI PERSONAL TRAINER
                    </span>
                    <h2 class="font-poppins font-black text-base text-[#16151A] mt-1">
                        {{ $packageName }}
                    </h2>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB]">
                <div class="w-10 h-10 rounded-full bg-orange-100 text-[#FF5722] flex items-center justify-center font-bold text-xs shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-[10px] uppercase font-bold text-[#565A66] block">Pelatih Pendamping</span>
                    <span class="text-xs font-bold text-[#16151A] block truncate">{{ $selectedTrainerName }}</span>
                </div>
            </div>

            <!-- Breakdown Harga -->
            <div class="space-y-2 pt-1 text-xs">
                <div class="flex justify-between text-[#565A66]">
                    <span>Harga Paket</span>
                    <span class="font-semibold text-[#16151A]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[#565A66]">
                    <span>Kode Unik Verifikasi</span>
                    <span class="font-semibold text-[#FF5722]">+Rp {{ $uniqueCode }}</span>
                </div>
                <div class="flex justify-between items-baseline pt-2 border-t border-[#E5E7EB]">
                    <span class="font-bold text-[#16151A]">Total Pembayaran</span>
                    <span class="font-poppins font-black text-lg text-[#FF5722]">
                        Rp {{ number_format($totalFinal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 5. Form Pilihan Bank & Submit -->
        <form action="{{ route('mobile.member.pt-packages.process-checkout') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="package_id" value="{{ $packageId }}">
            <input type="hidden" name="trainer_id" value="{{ $trainerId }}">
            <input type="hidden" name="unique_code" value="{{ $uniqueCode }}">
            <input type="hidden" name="total_amount" value="{{ $totalFinal }}">
            <input type="hidden" name="bank_id" :value="selectedBankId">

            <!-- Pilih Rekening Bank -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-[#16151A] uppercase tracking-wider font-poppins">
                    Pilih Rekening Bank Pembayaran
                </label>
                <div class="space-y-2">
                    @foreach($banks as $bank)
                        <label class="block cursor-pointer">
                            <div class="p-3.5 rounded-2xl border transition-all flex items-center justify-between"
                                 :class="selectedBankId == '{{ $bank->id }}' ? 'bg-orange-50/60 border-[#FF5722] shadow-xs' : 'bg-white border-[#E5E7EB] hover:bg-gray-50'">
                                <div class="flex items-center gap-3">
                                    <input type="radio"
                                           name="bank_choice"
                                           value="{{ $bank->id }}"
                                           x-model="selectedBankId"
                                           class="w-4 h-4 text-[#FF5722] border-gray-300 focus:ring-[#FF5722]">
                                    <div>
                                        <div class="font-bold text-xs text-[#16151A]">{{ $bank->name }}</div>
                                        <div class="text-[11px] font-mono text-[#565A66]">{{ $bank->account_number }}</div>
                                        <div class="text-[10px] text-gray-400">a.n. {{ $bank->account_holder }}</div>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 text-[9px] font-bold rounded bg-gray-100 text-[#565A66]">
                                    Transfer
                                </span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Checkbox Persetujuan Syarat Layanan -->
            <label class="flex items-start gap-2.5 cursor-pointer text-xs text-[#565A66] pt-1">
                <input type="checkbox"
                       required
                       name="agreed_terms"
                       x-model="agreedTerms"
                       class="w-4 h-4 mt-0.5 text-[#FF5722] border-[#E5E7EB] bg-white rounded focus:ring-[#FF5722] shrink-0 cursor-pointer">
                <span class="text-[11px] leading-tight select-none">
                    Saya menyetujui <strong class="text-[#16151A]">Syarat &amp; Ketentuan Layanan Personal Trainer</strong> FitCore Athletic.
                </span>
            </label>

            <!-- Tombol Lanjut ke Transfer -->
            <div class="pt-2">
                <button type="submit"
                        :disabled="!agreedTerms"
                        :class="!agreedTerms ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] cursor-pointer shadow-md shadow-orange-500/25'"
                        class="w-full py-3.5 px-4 rounded-xl text-white font-bold font-poppins text-xs tracking-wide transition-all flex items-center justify-center gap-2">
                    <span>Lanjut ke Pembayaran &rarr;</span>
                </button>
            </div>
        </form>

    </div>
</x-layouts.mobile-app>

