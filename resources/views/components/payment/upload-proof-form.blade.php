@props([
    'action',
    'banks' => [],
    'defaultSenderBank' => '',
])

<x-card :padding="false" {{ $attributes->merge(['class' => '!rounded-3xl border border-[#E5E7EB] p-6 sm:p-8 shadow-[0_4px_12px_rgba(0,0,0,0.04)] bg-white']) }} x-data="{
    fileName: '',
    filePreview: null,
    handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.fileName = file.name;
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.filePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.filePreview = null;
            }
        }
    },
    removeFile() {
        this.fileName = '';
        this.filePreview = null;
        if (this.$refs.fileInput) {
            this.$refs.fileInput.value = '';
        }
    }
}">
    <h2 class="font-heading text-lg font-extrabold text-[#16151A] border-b border-[#E5E7EB] pb-4 mb-6">
        Formulir Bukti Transfer
    </h2>

    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{ $slot }}

        <!-- 1. Nama Pemilik Rekening Pengirim -->
        <div>
<<<<<<< HEAD
            <label for="sender_name" class="block text-xs font-bold text-[#16151A] mb-2">
                Nama Pengirim / Pemilik Rekening <span class="text-[#ED1B45]">*</span>
=======
            <label for="sender_name" class="block text-xs font-bold text-slate-700 mb-2">
                Nama Pengirim / Pemilik Rekening <span class="text-[#FF5722]">*</span>
>>>>>>> origin/feature/theme-blaze-orange
            </label>
            <x-text-input
                id="sender_name"
                name="sender_name"
                type="text"
                required
                placeholder="Contoh: Muhammad Budi Santoso"
                class="w-full !text-xs !p-3.5"
                value="{{ old('sender_name', auth()->user()?->name) }}"
            />
            <p class="mt-1 text-[11px] text-[#565A66]">Nama yang tertera pada akun bank atau mutasi rekening Anda.</p>
        </div>

        <!-- 2. Bank Pengirim -->
        <div>
<<<<<<< HEAD
            <label for="sender_bank" class="block text-xs font-bold text-[#16151A] mb-2">
                Bank Pengirim <span class="text-[#ED1B45]">*</span>
=======
            <label for="sender_bank" class="block text-xs font-bold text-slate-700 mb-2">
                Bank Pengirim <span class="text-[#FF5722]">*</span>
>>>>>>> origin/feature/theme-blaze-orange
            </label>
            <x-text-input
                id="sender_bank"
                name="sender_bank"
                type="text"
                required
                placeholder="Contoh: BCA / Mandiri / BRI / BNI / Seabank"
                class="w-full !text-xs !p-3.5"
                value="{{ old('sender_bank', $defaultSenderBank) }}"
            />
        </div>

        <!-- 3. Unggah Berkas Bukti Transfer -->
        <div>
<<<<<<< HEAD
            <label class="block text-xs font-bold text-[#16151A] mb-2">
                Foto / Screenshot Bukti Transfer <span class="text-[#ED1B45]">*</span>
=======
            <label class="block text-xs font-bold text-slate-700 mb-2">
                Foto / Screenshot Bukti Transfer <span class="text-[#FF5722]">*</span>
>>>>>>> origin/feature/theme-blaze-orange
            </label>

            <!-- Dropzone Area -->
            <div class="relative flex flex-col items-center justify-center p-6 border-2 border-dashed border-[#E5E7EB] rounded-2xl bg-[#F6F6F7] hover:bg-gray-100/70 transition-colors cursor-pointer group">
                <input
                    type="file"
                    name="proof_image"
                    id="proof_image"
                    required
                    accept="image/jpeg,image/png,image/jpg,image/webp,application/pdf"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    x-ref="fileInput"
                    x-on:change="handleFileChange($event)"
                />

                <div class="text-center space-y-2 pointer-events-none" x-show="!fileName">
<<<<<<< HEAD
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#ED1B45]/10 text-[#ED1B45] group-hover:scale-105 transition-transform">
=======
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-[#FF5722] group-hover:scale-105 transition-transform">
>>>>>>> origin/feature/theme-blaze-orange
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs font-bold text-[#16151A]">
                            Pilih atau seret berkas bukti bayar ke sini
                        </p>
                        <p class="text-[11px] text-[#565A66] mt-0.5">
                            Format yang didukung: JPG, PNG, WEBP, PDF (Maks. 5 MB)
                        </p>
                    </div>
                </div>

                <!-- Preview Area -->
                <div x-show="fileName" x-cloak class="w-full flex items-center justify-between p-3 rounded-xl bg-white border border-[#E5E7EB] z-20">
                    <div class="flex items-center gap-3 truncate">
                        <template x-if="filePreview">
                            <img :src="filePreview" class="h-10 w-10 object-cover rounded-lg border border-[#E5E7EB] shrink-0" alt="Preview Bukti" />
                        </template>
                        <template x-if="!filePreview">
                            <span class="h-10 w-10 flex items-center justify-center rounded-lg bg-gray-100 text-[#565A66] shrink-0 text-xs font-bold uppercase">
                                PDF
                            </span>
                        </template>
                        <div class="truncate">
                            <p class="text-xs font-bold text-[#16151A] truncate" x-text="fileName"></p>
                            <span class="text-[10px] text-emerald-600 font-semibold">Siap diunggah</span>
                        </div>
                    </div>
                    <button type="button" x-on:click="removeFile()" class="p-1.5 text-[#565A66] hover:text-[#ED1B45] rounded-lg hover:bg-[#ED1B45]/10 transition cursor-pointer">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="mt-1.5 text-[11px] text-[#565A66]">
                Gambar struk akan dikompres secara otomatis untuk mempercepat proses verifikasi.
            </p>
        </div>

        <!-- Info Waktu Verifikasi -->
        <div class="rounded-2xl bg-[#F6F6F7] border border-[#E5E7EB] p-4 flex items-center gap-3 text-xs text-[#565A66]">
            <svg class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <p>Estimasi verifikasi finance: <span class="font-bold text-[#16151A]">± 15-30 menit</span> pada jam operasional.</p>
        </div>

        <!-- Tombol Kirim Bukti Transfer -->
        <x-button
            type="submit"
            variant="primary"
<<<<<<< HEAD
            class="w-full !py-4 !text-sm font-bold tracking-wide shadow-lg shadow-[#ED1B45]/20 hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer"
=======
            class="w-full !py-4 !text-sm font-bold tracking-wide shadow-lg shadow-[#FF5722]/30 hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2"
>>>>>>> origin/feature/theme-blaze-orange
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span>KIRIM BUKTI PEMBAYARAN</span>
        </x-button>
    </form>
</x-card>

