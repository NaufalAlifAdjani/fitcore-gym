<x-fitcore-layout :title="'Upload Bukti Pembayaran | FITCORE ATHLETIC'">

    <div class="relative overflow-hidden bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-rose-100/60 via-transparent to-transparent py-12 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER AREA (Judul + Countdown Timer) --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-black text-zinc-900 tracking-tight">
                        Upload Bukti Pembayaran
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-xl mt-1.5 leading-relaxed">
                        Unggah struk atau bukti transfer bank Anda untuk verifikasi manual tim finance Maxfit dalam 1&times;24 jam. Sesi latihan segera aktif setelah status approved.
                    </p>
                </div>

                {{-- Pill Countdown Timer Hitam --}}
                <div class="bg-[#18181B] text-white rounded-full px-5 py-2.5 flex items-center gap-3.5 shadow-md self-start md:self-auto shrink-0"
                     x-data="{ 
                         hours: 23, 
                         minutes: 59, 
                         seconds: 43,
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
                    <div class="w-7 h-7 rounded-full bg-rose-500/20 text-[#E11D48] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block leading-none">
                            BATAS WAKTU TRANSFER
                        </span>
                        <span class="text-sm font-black text-white tracking-widest block font-mono mt-0.5" 
                              x-text="String(hours).padStart(2,'0') + ':' + String(minutes).padStart(2,'0') + ':' + String(seconds).padStart(2,'0')">
                            23:59:43
                        </span>
                    </div>
                </div>
            </div>

            {{-- KONTEN 2 KOLOM --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- KOLOM KIRI (2 Card Bertumpuk) --}}
                <div class="lg:col-span-5 space-y-6">

                    {{-- CARD 1: RINGKASAN PEMESANAN --}}
                    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between pb-2">
                            <span class="text-xs font-black text-[#E11D48] tracking-wider uppercase">
                                RINGKASAN PEMESANAN
                            </span>
                            <span class="text-xs font-bold text-gray-400">
                                {{ $orderNumber }}
                            </span>
                        </div>

                        {{-- Box Nama Pelatih --}}
                        <div class="bg-[#F4F6FF] p-3.5 rounded-xl font-black text-zinc-900 text-sm sm:text-base my-3.5">
                            {{ $trainerName }}
                        </div>

                        {{-- Detail Item Paket & Harga --}}
                        <div class="space-y-3 pt-1">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-xs sm:text-sm font-black text-zinc-900">{{ $packageName }}</h4>
                                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $packageSubtext }}</p>
                                </div>
                                <span class="text-xs sm:text-sm font-black text-zinc-900 shrink-0">
                                    Rp {{ number_format($packagePrice, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500 font-medium">Kode Unik Verifikasi</span>
                                <span class="font-black text-[#E11D48]">+Rp {{ $uniqueCode }}</span>
                            </div>
                        </div>

                        {{-- Box Total Tagihan --}}
                        <div class="bg-[#F4F6FF] rounded-xl p-4 mt-5 flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-500 tracking-wider uppercase">
                                TOTAL TAGIHAN TRANSFER
                            </span>
                            <span class="text-xl sm:text-2xl font-black text-[#C80036] tracking-tight">
                                Rp {{ number_format($totalAmount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- CARD 2: INSTRUKSI TRANSFER ANTAR BANK --}}
                    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border border-gray-100">
                        
                        {{-- Header Card --}}
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#F4F6FF] flex items-center justify-center text-[#E11D48] shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-black text-sm text-zinc-900 leading-tight">Instruksi Transfer Antar Bank</h3>
                                <p class="text-xs text-gray-400 mt-0.5">Gunakan transfer realtime / BI-FAST / transfer sesama bank</p>
                            </div>
                        </div>

                        {{-- Dark Gradient Bank Card --}}
                        <div class="bg-gradient-to-br from-zinc-900 via-zinc-900 to-rose-950 text-white rounded-2xl p-5 my-4 shadow-md">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-[9px] font-bold text-gray-400 tracking-wider uppercase block">
                                        BANK TUJUAN
                                    </span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="font-black text-sm sm:text-base text-white">
                                            {{ $bank->name ?? 'BANK CENTRAL ASIA (BCA)' }}
                                        </span>
                                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="text-[11px] text-zinc-400 block mt-0.5">
                                        a.n. {{ $bank->account_holder ?? 'PT MAXFIT INDONESIA PERFORMA' }}
                                    </span>
                                </div>
                                <span class="bg-white/10 text-white text-[11px] font-black px-2.5 py-1 rounded-lg border border-white/10 tracking-wider">
                                    BCA 014
                                </span>
                            </div>

                            {{-- Box Nomor Rekening di Dalam Card --}}
                            <div class="bg-[#141418] rounded-xl p-3 flex items-center justify-between mt-4 border border-zinc-800"
                                 x-data="{ copied: false }">
                                <div>
                                    <span class="text-[9px] text-gray-400 block">Nomor Rekening Resmi</span>
                                    <span class="font-mono font-bold text-sm sm:text-base text-white tracking-wider block mt-0.5" id="account_number">
                                        {{ $bank->account_number ?? '8271 0812 3456 7890' }}
                                    </span>
                                </div>
                                <button type="button" 
                                        @click="
                                            navigator.clipboard.writeText('{{ $bank->account_number ?? '8271 0812 3456 7890' }}');
                                            copied = true;
                                            setTimeout(() => copied = false, 2000);
                                        "
                                        class="bg-[#E11D48] hover:bg-rose-600 text-white text-xs font-bold px-4 py-2 rounded-full transition flex items-center gap-1.5 shadow-sm active:scale-95 cursor-pointer">
                                    <span x-text="copied ? 'Tersalin! ✓' : 'Salin No. Rek'">Salin No. Rek</span>
                                </button>
                            </div>
                        </div>

                        {{-- Alert Box Bawah --}}
                        <div class="bg-[#F4F6FF] rounded-xl p-3 text-xs flex items-start gap-2.5 text-gray-600 leading-relaxed">
                            <div class="w-4 h-4 rounded-full bg-amber-500 text-white flex items-center justify-center text-[10px] font-black shrink-0 mt-0.5">
                                !
                            </div>
                            <p>
                                Harap pastikan nominal transfer <strong class="text-zinc-800">sesuai persis hingga 3 angka terakhir</strong> agar sistem reconciliation Maxfit langsung mengenali mutasi secara otomatis tanpa delay.
                            </p>
                        </div>

                    </div>

                </div>

                {{-- KOLOM KANAN (Card Unggah Bukti Transfer) --}}
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-gray-100" 
                         x-data="{ 
                             fileName: 'bukti_transfer_bca_250k.png', 
                             fileSize: '1.4 MB', 
                             hasFile: true,
                             handleFileSelect(e) {
                                 const file = e.target.files[0];
                                 if (file) {
                                     this.fileName = file.name;
                                     this.fileSize = (file.size / (1024*1024)).toFixed(1) + ' MB';
                                     this.hasFile = true;
                                 }
                             },
                             removeFile() {
                                 this.hasFile = false;
                                 this.fileName = '';
                                 this.$refs.fileInput.value = '';
                             }
                         }">
                        
                        <div class="flex items-center justify-between mb-1">
                            <h2 class="text-lg font-black text-zinc-900">
                                Unggah Bukti Transfer
                            </h2>
                            <span class="text-xs font-bold text-[#E11D48]">
                                Langkah Terakhir
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mb-5 leading-relaxed">
                            Pastikan file struk ATM, tangkapan layar m-banking, atau nota internet banking terlihat jelas dan tidak buram.
                        </p>

                        <form action="{{ route('member.pt-packages.submit-proof', ['payment' => $payment->id ?? null]) }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf

                            {{-- Dropzone Area --}}
                            <div>
                                <label class="text-xs font-bold text-zinc-800 mb-2 block">
                                    File Struk / Bukti Transfer *
                                </label>
                                
                                <div class="bg-[#F4F6FF] border-2 border-dashed border-indigo-200 rounded-2xl p-7 text-center cursor-pointer hover:border-indigo-300 transition"
                                     @click="$refs.fileInput.click()">
                                    
                                    <input type="file" 
                                           name="proof_image" 
                                           x-ref="fileInput" 
                                           @change="handleFileSelect"
                                           class="hidden" 
                                           accept="image/png,image/jpeg,image/jpg,application/pdf">

                                    <div class="w-12 h-12 rounded-full bg-white shadow-sm mx-auto flex items-center justify-center text-[#E11D48] mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>

                                    <p class="text-xs text-zinc-700 font-semibold">
                                        Tarik &amp; lepas file ke sini atau <span class="text-[#E11D48] font-bold underline">Klik untuk Jelajahi</span>
                                    </p>
                                    <p class="text-[11px] text-gray-400 mt-1">
                                        Mendukung format JPG, PNG, atau PDF (Ukuran maksimal 5MB)
                                    </p>
                                </div>
                            </div>

                            {{-- Preview File Terunggah --}}
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 my-4 flex items-center justify-between"
                                 x-show="hasFile"
                                 x-cloak>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-zinc-900" x-text="fileName">bukti_transfer_bca_250k.png</h4>
                                        <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">
                                            <span x-text="fileSize">1.4 MB</span> &bull; Berhasil dimuat dan siap dikirim
                                        </p>
                                    </div>
                                </div>
                                <button type="button" 
                                        @click="removeFile"
                                        class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-emerald-100/50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Input 1: Nama Pemilik Rekening Pengirim --}}
                            <div class="mt-4">
                                <label class="text-xs font-bold text-zinc-800 mb-1.5 block">
                                    Nama Pemilik Rekening Pengirim *
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="sender_name" 
                                           value="David Pratama" 
                                           class="bg-[#F4F6FF] border-0 rounded-xl px-4 py-3 text-xs sm:text-sm font-bold text-zinc-900 w-full focus:ring-2 focus:ring-[#E11D48] outline-none">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Sesuai nama yang tertera pada buku tabungan.</p>
                            </div>

                            {{-- Input 2: Bank Asal Pengirim --}}
                            <div class="mt-4">
                                <label class="text-xs font-bold text-zinc-800 mb-1.5 block">
                                    Bank Asal Pengirim *
                                </label>
                                <div class="relative">
                                    <select name="sender_bank" 
                                            class="bg-[#F4F6FF] border-0 rounded-xl px-4 py-3 text-xs sm:text-sm font-bold text-zinc-900 w-full focus:ring-2 focus:ring-[#E11D48] outline-none appearance-none cursor-pointer">
                                        <option value="BCA" selected>BCA - Bank Central Asia</option>
                                        <option value="Mandiri">Mandiri - Bank Mandiri</option>
                                        <option value="BNI">BNI - Bank Negara Indonesia</option>
                                        <option value="BRI">BRI - Bank Rakyat Indonesia</option>
                                        <option value="CIMB">CIMB Niaga</option>
                                        <option value="Lainnya">Bank Lainnya</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Checkbox Pernyataan --}}
                            <div class="flex items-start gap-3 mt-5">
                                <input type="checkbox" 
                                       id="confirm_valid" 
                                       checked
                                       required
                                       class="mt-1 w-4 h-4 rounded border-gray-300 text-[#E11D48] focus:ring-[#E11D48] cursor-pointer">
                                <label for="confirm_valid" class="text-xs text-gray-600 leading-relaxed cursor-pointer">
                                    Saya menyatakan bahwa bukti pembayaran yang diunggah adalah sah dan sesuai dengan rekening tujuan PT MAXFIT KINERJA INDONESIA.
                                </label>
                            </div>

                            {{-- Tombol Submit --}}
                            <button type="submit" 
                                    class="w-full bg-[#E11D48] hover:bg-rose-700 text-white font-black text-xs sm:text-sm py-4 rounded-full mt-6 shadow-md shadow-rose-900/30 transition text-center uppercase tracking-wider cursor-pointer active:scale-[0.99]">
                                KIRIM BUKTI PEMBAYARAN &rarr;
                            </button>

                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

</x-fitcore-layout>
