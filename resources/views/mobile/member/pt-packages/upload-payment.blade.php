{{--
ROLE: MEMBER
MODUL: Upload Bukti Pembayaran Paket PT Mobile (Langkah 4)
VIEW: mobile.member.pt-packages.upload-payment
TEMA: LIGHT THEME (#F6F6F7, Surface #FFFFFF, Primary #FF5722, Text #16151A / #565A66)
--}}

<x-layouts.mobile-app title="Upload Bukti Transfer PT - FitCore Athletic" active-tab="packages">
    <div x-data="{
        hours: 23,
        minutes: 59,
        seconds: 59,
        copiedAmount: false,
        copiedAccount: false,
        previewUrl: null,
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
        },
        copy(text, type) {
            navigator.clipboard.writeText(text);
            if (type === 'amount') {
                this.copiedAmount = true;
                setTimeout(() => this.copiedAmount = false, 2000);
            } else {
                this.copiedAccount = true;
                setTimeout(() => this.copiedAccount = false, 2000);
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

        <!-- 1. Header & Back Navigation -->
        <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
            <a href="{{ route('mobile.member.pt-packages.checkout', ['package_id' => $payment?->pt_package_id ?? request('package_id'), 'trainer_id' => request('trainer_id', 1)]) }}"
               class="p-1.5 text-[#565A66] hover:text-[#16151A] transition flex items-center gap-1 cursor-pointer"
               aria-label="Kembali ke Rincian Pesanan">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="text-xs font-semibold">Rincian</span>
            </a>
            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-[#FF5722] border border-orange-200">
                Langkah 4 dari 4
            </span>
        </div>

        <!-- 2. Countdown Timer Box -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF5722] border border-orange-200 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-[#565A66] uppercase tracking-wider block">Batas Waktu Transfer</span>
                    <span class="font-mono font-black text-base text-[#FF5722]"
                          x-text="String(hours).padStart(2,'0') + ':' + String(minutes).padStart(2,'0') + ':' + String(seconds).padStart(2,'0')">
                        23:59:59
                    </span>
                </div>
            </div>
            <span class="font-mono text-xs font-bold text-[#16151A] bg-gray-100 px-2.5 py-1 rounded-lg">
                {{ $orderNumber }}
            </span>
        </div>

        <!-- 3. Instruksi Nominal Tepat (Termasuk Kode Unik) -->
        <div class="bg-gradient-to-br from-orange-50 via-white to-amber-50/40 rounded-2xl p-4 border border-orange-200 shadow-xs space-y-2">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#565A66]">Total Transfer Tepat:</span>
                    <div class="font-poppins font-black text-2xl text-[#FF5722] tracking-tight mt-0.5">
                        Rp {{ number_format($totalAmount, 0, ',', '.') }}
                    </div>
                </div>
                <button type="button"
                        @click="copy('{{ $totalAmount }}', 'amount')"
                        class="px-3 py-1.5 rounded-xl bg-white border border-orange-200 text-[#FF5722] text-xs font-bold shadow-xs hover:bg-orange-50 active:scale-95 transition flex items-center gap-1 cursor-pointer">
                    <span x-text="copiedAmount ? 'Tersalin!' : 'Salin'">Salin</span>
                </button>
            </div>
            <p class="text-[11px] text-[#565A66] leading-relaxed">
                Penting: Transfer hingga <strong class="text-[#FF5722]">3 digit terakhir</strong> (+{{ $uniqueCode }}) agar pembayaran Anda dapat diverifikasi otomatis.
            </p>
        </div>

        <!-- 4. Card Rekening Tujuan Bank -->
        <div class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-3">
            <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-2.5">
                <span class="text-xs font-bold text-[#16151A] font-poppins">
                    {{ is_object($bank) ? ($bank->name ?? 'BANK TRANSFER') : 'BANK TRANSFER' }}
                </span>
                <span class="text-[10px] font-bold text-[#565A66] uppercase">Rekening Resmi</span>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-[#565A66] block">Nomor Rekening:</span>
                    <span class="font-mono font-black text-base text-[#16151A]">
                        {{ is_object($bank) ? ($bank->account_number ?? '8271 0812 3456 7890') : '8271 0812 3456 7890' }}
                    </span>
                    <span class="text-[11px] text-[#565A66] block mt-0.5">
                        a.n. {{ is_object($bank) ? ($bank->account_holder ?? 'PT MAXFIT INDONESIA PERFORMA') : 'PT MAXFIT INDONESIA PERFORMA' }}
                    </span>
                </div>
                <button type="button"
                        @click="copy('{{ is_object($bank) ? ($bank->account_number ?? '8271081234567890') : '8271081234567890' }}', 'account')"
                        class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-[#16151A] text-xs font-bold transition active:scale-95 cursor-pointer">
                    <span x-text="copiedAccount ? 'Tersalin!' : 'Salin'">Salin</span>
                </button>
            </div>
        </div>

        <!-- 5. Form Upload Bukti Transfer -->
        <form action="{{ route('mobile.member.pt-packages.submit-proof', ['payment' => $payment?->id]) }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white rounded-2xl p-4 border border-[#E5E7EB] shadow-[0_4px_12px_rgba(0,0,0,0.04)] space-y-4">
            @csrf

            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#16151A] font-poppins">
                    Unggah Struk Pembayaran
                </h3>
                <p class="text-[11px] text-[#565A66] mt-0.5">
                    Unggah foto struk ATM, bukti m-banking, atau file PDF (Maks. 5 MB).
                </p>
            </div>

            <!-- Upload Area -->
            <div class="relative">
                <input type="file"
                       name="proof_image"
                       required
                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                       @change="handleImageChange($event)"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                <div class="border-2 border-dashed border-[#E5E7EB] rounded-2xl p-4 text-center hover:border-[#FF5722] transition-colors bg-[#F6F6F7]/50"
                     :class="previewUrl ? 'border-emerald-400 bg-emerald-50/20' : ''">
                    
                    <template x-if="previewUrl">
                        <div class="space-y-2">
                            <img :src="previewUrl" class="max-h-40 mx-auto rounded-xl object-contain shadow-xs">
                            <span class="text-xs font-bold text-emerald-600 block">Struk Berhasil Dipilih (Ketuk untuk ganti)</span>
                        </div>
                    </template>

                    <template x-if="!previewUrl">
                        <div class="space-y-1.5 py-3">
                            <div class="w-10 h-10 rounded-full bg-orange-50 text-[#FF5722] mx-auto flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-[#16151A] block">Ambil Foto atau Pilih dari Galeri</span>
                            <span class="text-[10px] text-[#565A66] block">Format: JPG, PNG, atau PDF</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Field Nama Pengirim -->
            <div class="space-y-1">
                <label for="sender_name" class="block text-xs font-bold text-[#16151A]">
                    Nama Pemilik Rekening Pengirim <span class="text-[#FF5722]">*</span>
                </label>
                <input type="text"
                       id="sender_name"
                       name="sender_name"
                       required
                       value="{{ old('sender_name') }}"
                       placeholder="Contoh: Budi Santoso"
                       class="w-full p-3 rounded-xl bg-white border border-[#E5E7EB] text-xs text-[#16151A] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] shadow-xs">
            </div>

            <!-- Field Bank Pengirim -->
            <div class="space-y-1">
                <label for="sender_bank" class="block text-xs font-bold text-[#16151A]">
                    Bank / E-Wallet Pengirim <span class="text-[#FF5722]">*</span>
                </label>
                <input type="text"
                       id="sender_bank"
                       name="sender_bank"
                       required
                       value="{{ old('sender_bank') }}"
                       placeholder="Contoh: BCA / Mandiri / GoPay"
                       class="w-full p-3 rounded-xl bg-white border border-[#E5E7EB] text-xs text-[#16151A] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FF5722] shadow-xs">
            </div>

            <!-- Tombol Kirim Bukti Pembayaran -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3.5 px-4 rounded-xl bg-[#FF5722] hover:bg-[#E64A19] active:scale-[0.99] text-white font-bold font-poppins text-xs tracking-wide shadow-md shadow-orange-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Kirim Bukti Pembayaran</span>
                </button>
            </div>
        </form>

        <!-- 6. Card Bantuan CS WhatsApp -->
        <div class="p-3.5 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center justify-between text-xs">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-[#16151A] block">Butuh Bantuan Transfer?</span>
                    <span class="text-[10px] text-[#565A66]">Hubungi Admin Customer Service</span>
                </div>
            </div>
            <a href="https://wa.me/6281234567890" target="_blank"
               class="px-3 py-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-[11px] transition shadow-xs">
                Chat CS
            </a>
        </div>

    </div>
</x-layouts.mobile-app>

