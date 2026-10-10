<x-member-layout title="Upload Bukti Pembayaran - FitCore Athletic" active-nav="packages">
    <div class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8 text-slate-900 border-b border-slate-200">
        <div class="mx-auto max-w-6xl">
            <!-- Breadcrumb Navigation -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a href="{{ route('member.membership.checkout', $package->id) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#BA0030] transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Kembali ke Detail Pembayaran
                </a>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span class="text-slate-400">Langkah 1: Konfirmasi Pembayaran</span>
                    <span>•</span>
                    <span class="text-[#BA0030]">Langkah 2: Form Bukti Transfer</span>
                </div>
            </div>

            <!-- Header Section -->
            <div class="mb-10">
                <h1 class="font-heading text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Upload Bukti Pembayaran
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                    Selesaikan transfer sesuai nominal tepat dan unggah struk pembayaran resmi Anda untuk verifikasi instan tim admin.
                </p>
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
                    <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-7 shadow-sm">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
                            Ringkasan Pesanan
                        </h2>

                        <div class="flex items-center gap-4">
                            <!-- Thumbnail Foto Gym -->
                            <div class="relative h-20 w-24 shrink-0 overflow-hidden rounded-2xl bg-slate-900 flex items-center justify-center border border-slate-800 shadow-inner">
                                <svg class="h-8 w-8 text-[#BA0030]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6.5 6.5h11M6.5 17.5h11M4 9.5h16M4 14.5h16M8.5 4.5v15M15.5 4.5v15" stroke-linecap="round"/>
                                </svg>
                                <span class="absolute bottom-1 right-1.5 text-[8px] font-black text-rose-300">FITCORE</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-rose-100 text-[#BA0030] mb-1">
                                    PAKET MEMBERSHIP
                                </span>
                                <h3 class="font-heading text-lg font-extrabold text-slate-900 truncate">
                                    {{ $package->name }}
                                </h3>
                                <p class="text-xs text-slate-500 font-medium">
                                    Masa Aktif: {{ $package->duration_value }} {{ $package->duration_unit }} ({{ $package->duration_in_days }} Hari)
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
                    </div>

                    <!-- 2. Rekening Tujuan Transfer Card (Hitam Elegan ber-badge "Akun Bisnis") -->
                    <div class="rounded-3xl border border-slate-800 bg-[#0B0F17] p-6 sm:p-7 text-white shadow-xl relative overflow-hidden" x-data="{ copied: false }">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-5">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Rekening Tujuan Transfer</span>
                                <h3 class="font-heading text-base font-extrabold text-white mt-0.5">
                                    {{ $selectedBank->name }}
                                </h3>
                            </div>
                            <span class="rounded-full bg-emerald-500/20 border border-emerald-500/40 px-3 py-1 text-[10px] font-extrabold uppercase text-emerald-400 tracking-wider">
                                Akun Bisnis
                            </span>
                        </div>

                        <!-- Nomor Rekening Besar & Tombol Salin -->
                        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400">Nomor Rekening Resmi</p>
                                <p class="font-mono text-xl sm:text-2xl font-black text-white tracking-wider mt-1 select-all" id="bankAccountNumber">
                                    {{ $selectedBank->account_number }}
                                </p>
                                <p class="text-xs text-slate-300 font-medium mt-1">
                                    a.n. {{ $selectedBank->account_holder }}
                                </p>
                            </div>
                            <button
                                type="button"
                                x-on:click="navigator.clipboard.writeText('{{ $selectedBank->account_number }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                class="shrink-0 px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-1.5"
                                x-bind:class="copied ? 'bg-emerald-600 text-white' : 'bg-[#BA0030] hover:bg-[#970027] text-white shadow-md shadow-[#BA0030]/30'"
                            >
                                <span x-show="!copied">Salin</span>
                                <span x-show="copied" x-cloak>Tersalin!</span>
                            </button>
                        </div>
                    </div>

                    <!-- 3. Panduan 4 Langkah Transfer -->
                    <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-7 shadow-sm">
                        <h3 class="font-heading text-sm font-extrabold text-slate-900 mb-4 flex items-center gap-2">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#BA0030] text-white text-[10px] font-bold">i</span>
                            Panduan Langkah Transfer
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 flex items-start gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-100 font-heading font-black text-[#BA0030] text-xs">
                                    1
                                </span>
                                <div>
                                    <p class="font-bold text-xs text-slate-900">Buka Mobile Banking</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Buka aplikasi m-banking atau ATM bank Anda.</p>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 flex items-start gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-100 font-heading font-black text-[#BA0030] text-xs">
                                    2
                                </span>
                                <div>
                                    <p class="font-bold text-xs text-slate-900">Pilih Menu Transfer</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Pilih transfer ke rekening bank tujuan di atas.</p>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 flex items-start gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-100 font-heading font-black text-[#BA0030] text-xs">
                                    3
                                </span>
                                <div>
                                    <p class="font-bold text-xs text-slate-900">Masukkan Nominal Tepat</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Masukkan nominal tepat hingga 3 digit terakhir.</p>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 flex items-start gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-100 font-heading font-black text-[#BA0030] text-xs">
                                    4
                                </span>
                                <div>
                                    <p class="font-bold text-xs text-slate-900">Simpan Bukti Transfer</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Screenshot bukti struk dan unggah pada form.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Card Unggah Bukti Transfer (Langkah Terakhir) -->
                <div class="lg:col-span-6">
                    <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 shadow-sm">
                        <div class="border-b border-slate-100 pb-4 mb-6">
                            <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-rose-50 text-[#BA0030] border border-rose-200 mb-1.5">
                                LANGKAH TERAKHIR
                            </span>
                            <h2 class="font-heading text-xl font-extrabold text-slate-900">
                                Unggah Bukti Transfer
                            </h2>
                            <p class="text-xs text-slate-500 mt-1">
                                Lampirkan struk resmi atau tangkapan layar pembayaran Anda untuk diproses oleh sistem.
                            </p>
                        </div>

                        <form
                            action="{{ route('member.membership.upload-proof.store', $package->id) }}"
                            method="POST"
                            enctype="multipart/form-data"
                            x-data="{
                                fileChosen: false,
                                fileName: '',
                                fileSize: '',
                                handleFileSelect(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        this.fileChosen = true;
                                        this.fileName = file.name;
                                        const mb = (file.size / (1024 * 1024)).toFixed(1);
                                        this.fileSize = mb + ' MB • Berhasil dimuat dan siap dikirim';
                                    }
                                },
                                removeFile() {
                                    this.fileChosen = false;
                                    this.fileName = '';
                                    this.fileSize = '';
                                    $refs.fileInput.value = '';
                                }
                            }"
                            class="space-y-6"
                        >
                            @csrf
                            <input type="hidden" name="bank_id" value="{{ $selectedBank->id }}" />

                            <!-- Kotak Drag & Drop Upload File -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    Berkas Struk / Bukti Pembayaran <span class="text-rose-500">*</span>
                                </label>

                                <div
                                    x-show="!fileChosen"
                                    x-on:click="$refs.fileInput.click()"
                                    x-on:dragover.prevent=""
                                    x-on:drop.prevent="
                                        $refs.fileInput.files = $event.dataTransfer.files;
                                        handleFileSelect({ target: $refs.fileInput });
                                    "
                                    class="border-2 border-dashed border-slate-300 hover:border-[#BA0030] bg-slate-50/50 hover:bg-rose-50/20 rounded-2xl p-8 text-center cursor-pointer transition-all duration-200 group"
                                >
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white border border-slate-200 group-hover:scale-105 transition shadow-sm text-slate-400 group-hover:text-[#BA0030]">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path d="M7 16a4 4 0 0 1-.88-7.903A5 5 0 1 1 15.9 6L16 6a5 5 0 0 1 1 9.9M15 13l-3-3m0 0-3 3m3-3v12" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <p class="mt-4 text-xs font-bold text-slate-800">
                                        Tarik &amp; lepas file ke sini atau <span class="text-[#BA0030] underline">Klik untuk Jelajahi</span>
                                    </p>
                                    <p class="mt-1 text-[11px] text-slate-400">
                                        Format JPG, JPEG, PNG, WEBP (Maksimal 5MB)
                                    </p>
                                </div>

                                <input
                                    type="file"
                                    name="proof_image"
                                    x-ref="fileInput"
                                    x-on:change="handleFileSelect($event)"
                                    accept="image/*"
                                    class="sr-only"
                                    required
                                />

                                <!-- Area Indikator File Terunggah Interaktif -->
                                <div
                                    x-show="fileChosen"
                                    x-cloak
                                    class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4.5 flex items-center justify-between gap-3 shadow-sm"
                                >
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-bold text-xs text-slate-900 truncate" x-text="fileName"></p>
                                            <p class="text-[11px] font-semibold text-emerald-700 mt-0.5" x-text="fileSize"></p>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        x-on:click="removeFile()"
                                        class="shrink-0 text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-white transition"
                                        title="Hapus berkas"
                                    >
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Input Field: Nama Pemilik Rekening Pengirim -->
                            <div>
                                <label for="sender_name" class="block text-xs font-bold text-slate-700 mb-2">
                                    Nama Pemilik Rekening Pengirim <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="sender_name"
                                    name="sender_name"
                                    value="{{ old('sender_name', Auth::user()?->name) }}"
                                    placeholder="Contoh: Budi Santoso"
                                    required
                                    class="w-full rounded-xl border-slate-300 text-xs text-slate-900 focus:border-[#BA0030] focus:ring-[#BA0030] p-3.5 shadow-sm"
                                />
                                <p class="mt-1 text-[11px] text-slate-400">
                                    Pastikan nama sesuai dengan yang tertera pada buku tabungan atau akun e-banking pengirim.
                                </p>
                            </div>

                            <!-- Dropdown Select: Bank Asal Pengirim -->
                            <div>
                                <label for="bank_sender_origin" class="block text-xs font-bold text-slate-700 mb-2">
                                    Bank Asal Pengirim <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    id="bank_sender_origin"
                                    name="bank_sender_origin"
                                    class="w-full rounded-xl border-slate-300 text-xs text-slate-900 focus:border-[#BA0030] focus:ring-[#BA0030] p-3.5 shadow-sm"
                                >
                                    <option value="BCA">Bank Central Asia (BCA)</option>
                                    <option value="Mandiri">Bank Mandiri</option>
                                    <option value="BNI">Bank Negara Indonesia (BNI)</option>
                                    <option value="BRI">Bank Rakyat Indonesia (BRI)</option>
                                    <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                                    <option value="CIMB">CIMB Niaga</option>
                                    <option value="Permata">Bank Permata</option>
                                    <option value="Jago">Bank Jago</option>
                                    <option value="Lainnya">Bank Lainnya / E-Wallet</option>
                                </select>
                            </div>

                            <!-- Checkbox Pernyataan Integritas Bukti Pembayaran -->
                            <div>
                                <label class="flex items-start gap-3 cursor-pointer text-xs text-slate-600">
                                    <input
                                        type="checkbox"
                                        required
                                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-[#BA0030] focus:ring-[#BA0030]"
                                    />
                                    <span class="leading-relaxed">
                                        Saya menyatakan bahwa bukti pembayaran yang diunggah adalah sah dan sesuai dengan nominal tagihan yang ditentukan.
                                    </span>
                                </label>
                            </div>

                            <!-- Tombol Submit Crimson Besar -->
                            <div class="pt-2">
                                <button
                                    type="submit"
                                    class="w-full py-4 rounded-full bg-[#BA0030] hover:bg-[#970027] text-white font-extrabold text-sm tracking-wider uppercase shadow-xl shadow-[#BA0030]/30 transition hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2"
                                >
                                    <span>KIRIM BUKTI PEMBAYARAN</span>
                                    <span class="text-base">→</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-member-layout>
