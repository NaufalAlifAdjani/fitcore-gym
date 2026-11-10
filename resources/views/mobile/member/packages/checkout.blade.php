{{--
ROLE: MEMBER
MODUL: Checkout & Pembayaran Paket Membership Mobile
VIEW: mobile.member.packages.checkout
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

@php
    $effectivePrice = ($package->promo_price && $package->promo_price < $package->price)
        ? $package->promo_price
        : $package->price;
    $uniqueCode = (int) ($uniqueCode ?? random_int(100, 999));
    $totalAmount = $effectivePrice + $uniqueCode;
    $defaultBankId = $banks->first()?->id ?? 1;

    $facilities = is_array($package->facilities)
        ? $package->facilities
        : (is_string($package->facilities) ? json_decode($package->facilities, true) ?? [] : []);

    if (empty($facilities)) {
        $facilities = [
            'Akses Penuh Area Gym & Cardio',
            'Loker Harian & Kamar Bilas',
            'Akses Check-in QR FitCore',
        ];
    }
@endphp

<x-layouts.mobile-app title="Checkout Paket - FitCore Athletic" active-tab="packages">
    <div x-data="{
        selectedBankId: '{{ $defaultBankId }}',
        banks: {{ json_encode($banks) }},
        previewUrl: null,
        copied: false,
        get currentBank() {
            return this.banks.find(b => b.id == this.selectedBankId) || this.banks[0] || {};
        },
        copyAccount() {
            if (this.currentBank && this.currentBank.account_number) {
                navigator.clipboard.writeText(this.currentBank.account_number);
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            }
        },
        handleImageChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
            } else {
                this.previewUrl = null;
            }
        }
    }" class="space-y-4">

        <!-- 1. Header & Back Link -->
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
            <a href="{{ route('mobile.member.packages') }}"
               class="p-1.5 text-[#565A66] hover:text-[#16151A] transition flex items-center gap-1 cursor-pointer"
               aria-label="Kembali ke Katalog">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-xs font-semibold">Katalog Paket</span>
            </a>
            <h1 class="text-sm font-black font-poppins text-[#16151A] tracking-wide">
                Detail Pembayaran
            </h1>
            <div class="w-8"></div>
        </div>

        <!-- 2. Alert Validasi Error -->
        @if ($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 shadow-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Mohon lengkapi formulir pembayaran:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 shadow-xs">
                {{ session('error') }}
            </div>
        @endif

        <!-- 3. Ringkasan Paket Terpilih Card -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-orange-50 text-[#FF5722] border border-orange-200 mb-1">
                        {{ $package->tier ?? 'PAKET MEMBERSHIP' }}
                    </span>
                    <h2 class="text-lg font-black font-poppins text-[#16151A]">
                        {{ $package->name }}
                    </h2>
                    <p class="text-xs text-[#565A66]">
                        Masa Berlaku: <strong class="text-[#16151A]">{{ $package->duration_days }} Hari</strong>
                    </p>
                </div>

                @if ($package->badge)
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#FF5722] text-white shadow-xs">
                        {{ $package->badge }}
                    </span>
                @endif
            </div>

            <!-- Rincian Biaya -->
            <div class="bg-[#F6F6F7] p-3 rounded-xl border border-[#E5E7EB] space-y-2 text-xs">
                <div class="flex items-center justify-between text-[#565A66]">
                    <span>Harga Paket</span>
                    <span class="font-semibold text-[#16151A]">Rp {{ number_format($effectivePrice, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between text-[#565A66]">
                    <span class="flex items-center gap-1">
                        Kode Unik Verifikasi
                        <span class="text-[10px] text-amber-600 font-bold" title="Kode unik untuk verifikasi otomatis">(3 Digit)</span>
                    </span>
                    <span class="font-bold text-amber-600">+Rp {{ $uniqueCode }}</span>
                </div>
                <div class="pt-2 border-t border-[#E5E7EB] flex items-center justify-between font-bold">
                    <span class="text-xs text-[#16151A] uppercase tracking-wider">Total Transfer Tepat</span>
                    <div class="text-right">
                        <span class="font-poppins font-black text-lg text-[#FF5722]">
                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            <p class="text-[10px] text-[#565A66] italic text-center">
                * Pastikan nominal transfer persis hingga digit terakhir agar verifikasi otomatis berjalan lancar.
            </p>
        </div>

        <!-- 4. Pilihan Rekening Bank Tujuan Transfer -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                1. Pilih Rekening Bank Tujuan
            </h3>

            <!-- Radio List Bank -->
            <div class="grid grid-cols-2 gap-2">
                @foreach ($banks as $bank)
                    <button type="button"
                            @click="selectedBankId = '{{ $bank->id }}'"
                            class="p-3 rounded-xl border text-left transition flex flex-col justify-between cursor-pointer"
                            :class="selectedBankId == '{{ $bank->id }}' ? 'border-[#FF5722] bg-orange-50/50 ring-2 ring-[#FF5722] text-[#16151A]' : 'border-[#E5E7EB] bg-white text-[#565A66] hover:border-gray-300 shadow-xs'">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-poppins font-bold text-xs uppercase" x-text="'{{ $bank->name }}'">{{ $bank->name }}</span>
                            <div class="w-3.5 h-3.5 rounded-full border flex items-center justify-center"
                                 :class="selectedBankId == '{{ $bank->id }}' ? 'border-[#FF5722] bg-[#FF5722]' : 'border-gray-300'">
                                <div x-show="selectedBankId == '{{ $bank->id }}'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <span class="text-[10px] text-[#565A66] truncate font-mono">{{ $bank->account_number }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Detail Bank Terpilih dengan Tombol Salin -->
            <div class="mt-3 p-3.5 rounded-xl bg-[#F6F6F7] border border-[#E5E7EB] space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-[#565A66] block">Nomor Rekening Tujuan</span>
                        <span class="font-mono font-black text-base text-[#16151A] tracking-wider" x-text="currentBank.account_number">
                            {{ $banks->first()?->account_number }}
                        </span>
                    </div>
                    <button type="button"
                            @click="copyAccount()"
                            class="px-3 py-1.5 rounded-full text-[11px] font-bold border transition flex items-center gap-1 cursor-pointer"
                            :class="copied ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-[#16151A] border-[#E5E7EB] hover:border-[#FF5722]'">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                        </svg>
                        <span x-text="copied ? 'Tersalin!' : 'Salin'">Salin</span>
                    </button>
                </div>
                <div class="pt-1.5 border-t border-[#E5E7EB] flex items-center justify-between text-[11px] text-[#565A66]">
                    <span>Atas Nama:</span>
                    <strong class="text-[#16151A] font-semibold" x-text="currentBank.account_holder || 'FitCore Athletic Indonesia'">
                        {{ $banks->first()?->account_holder ?? 'FitCore Athletic Indonesia' }}
                    </strong>
                </div>
            </div>
        </div>

        <!-- 5. Form Upload Bukti Transfer -->
        <form action="{{ route('member.membership.process-payment', $package->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
            @csrf
            <input type="hidden" name="bank_id" :value="selectedBankId">
            <input type="hidden" name="unique_code" value="{{ $uniqueCode }}">

            <h3 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                2. Unggah Bukti Pembayaran
            </h3>

            <!-- Input Nama Pengirim -->
            <div class="space-y-1">
                <label for="sender_name" class="block text-xs font-bold text-[#16151A]">
                    Nama Pemilik Rekening / Pengirim <span class="text-[#FF5722]">*</span>
                </label>
                <input type="text"
                       id="sender_name"
                       name="sender_name"
                       value="{{ old('sender_name', auth()->user()->name) }}"
                       required
                       placeholder="Contoh: Budi Santoso"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-white text-xs text-[#16151A] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] shadow-xs">
            </div>

            <!-- Upload File Bukti Bayar -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-[#16151A]">
                    Foto / Struk Bukti Transfer <span class="text-[#FF5722]">*</span>
                </label>

                <!-- Dropzone / Picker -->
                <div class="relative border-2 border-dashed border-[#E5E7EB] hover:border-[#FF5722]/50 rounded-2xl p-4 text-center bg-[#F6F6F7] transition cursor-pointer">
                    <input type="file"
                           name="proof_image"
                           id="proof_image"
                           accept="image/jpeg,image/png,image/jpg,image/webp"
                           required
                           @change="handleImageChange"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    <!-- Preview Mode -->
                    <template x-if="previewUrl">
                        <div class="space-y-2">
                            <img :src="previewUrl" alt="Preview Bukti Transfer" class="max-h-40 mx-auto rounded-lg shadow-sm border border-[#E5E7EB] object-contain">
                            <span class="text-[10px] text-emerald-600 font-bold block">✓ Gambar berhasil dipilih. Ketuk untuk mengganti.</span>
                        </div>
                    </template>

                    <!-- Placeholder Mode -->
                    <template x-if="!previewUrl">
                        <div class="space-y-1.5 py-2">
                            <div class="w-10 h-10 rounded-xl bg-white text-[#FF5722] border border-[#E5E7EB] flex items-center justify-center mx-auto shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="block text-xs font-bold text-[#16151A]">Ambil Foto / Pilih dari Galeri</span>
                            <span class="block text-[10px] text-[#565A66]">Mendukung JPG, PNG, WEBP (Maksimal 2MB)</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Checkbox Kebijakan & Kebenaran Data -->
            <label class="flex items-start gap-2.5 cursor-pointer text-xs text-[#565A66] pt-1">
                <input type="checkbox" required name="agreed_policy"
                       class="w-4 h-4 mt-0.5 text-[#FF5722] border-[#E5E7EB] bg-white rounded focus:ring-[#FF5722] shrink-0" />
                <span class="text-[11px] leading-tight select-none">
                    Saya menyatakan telah mentransfer sejumlah <strong class="text-[#16151A]">Rp {{ number_format($totalAmount, 0, ',', '.') }}</strong> dengan benar dan sah ke rekening resmi FitCore.
                </span>
            </label>

            <!-- Tombol Aksi Utama Pill (DESIGN.md Blaze Orange) -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3.5 rounded-full bg-[#FF5722] hover:bg-[#E64A19] text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-orange-500/25 flex items-center justify-center gap-2 cursor-pointer transition active:scale-[0.98]">
                    <span>Kirim Bukti Pembayaran</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>

    </div>
</x-layouts.mobile-app>

