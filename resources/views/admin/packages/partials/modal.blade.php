<div
    x-show="showModal"
    x-cloak
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-[#16151A]/60 p-4 backdrop-blur-sm"
    aria-labelledby="package-modal-title"
    role="dialog"
    aria-modal="true"
>
    <div
        x-on:click.outside="showModal = false"
        x-transition
        class="max-h-[92vh] w-full max-w-3xl overflow-y-auto rounded-[20px] bg-white p-5 shadow-2xl sm:p-8"
    >
        <div class="mb-6 flex items-start justify-between border-b border-[#E5E7EB] pb-5">
            <div>
                <p class="font-inter text-[10px] font-bold uppercase tracking-[0.16em] text-[#ED1B45]">Katalog Membership</p>
                <h2 id="package-modal-title" class="mt-1 font-poppins text-xl font-bold text-[#16151A]" x-text="isEditMode ? 'Edit Paket Membership' : 'Tambah Paket Membership'"></h2>
            </div>
            <button type="button" x-on:click="showModal = false" aria-label="Tutup modal" class="rounded-full p-2 text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        <form method="POST" x-bind:action="formAction()" class="space-y-5">
            @csrf
            <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEditMode">
            <input type="hidden" name="package_id" x-bind:value="form.id">

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="pkg-name" value="Nama Paket Membership *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <x-text-input id="pkg-name" type="text" name="name" x-model="form.name" required maxlength="255" placeholder="FitCore Unlimited Pro Year" class="!bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45]" />
                    @error('name') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <x-input-label for="pkg-tier" value="Kategori / Tier *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <x-select-input id="pkg-tier" name="tier" x-model="form.tier" required class="!bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45]">
                        <option value="Basic">Basic</option>
                        <option value="Standard">Standard</option>
                        <option value="Premium & VIP">Premium &amp; VIP</option>
                    </x-select-input>
                    @error('tier') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="pkg-badge" value="Nama Badge" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <x-text-input id="pkg-badge" type="text" name="badge" x-model="form.badge" maxlength="50" placeholder="Contoh: BEST SELLER" class="!bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm uppercase focus:!border-[#ED1B45] focus:!ring-[#ED1B45]" />
                    @error('badge') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <x-input-label for="pkg-pt-sessions" value="Sesi Personal Trainer" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <x-text-input id="pkg-pt-sessions" type="number" name="pt_sessions" x-model="form.pt_sessions" min="0" step="1" class="!bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45]" />
                    @error('pt_sessions') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <x-input-label for="pkg-description" value="Deskripsi" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                <textarea id="pkg-description" name="description" x-model="form.description" rows="2" class="w-full rounded-xl border border-[#E5E7EB] bg-white px-3.5 py-3 font-inter text-sm text-[#16151A] placeholder-[#565A66] focus:border-[#ED1B45] focus:ring-[#ED1B45] outline-none"></textarea>
                @error('description') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="pkg-price" value="Tarif Normal (Rp) *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <x-text-input id="pkg-price" type="number" name="price" x-model="form.price" required min="0" step="1" placeholder="4500000" class="!bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45]" />
                    @error('price') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <x-input-label for="pkg-promo-price" value="Harga Promo (Rp)" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <x-text-input id="pkg-promo-price" type="number" name="promo_price" x-model="form.promo_price" min="0" step="1" placeholder="Opsional" class="!bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45]" />
                    <span x-show="discountPercent() > 0" x-cloak class="mt-2 inline-flex rounded-full bg-[#ED1B45] px-3 py-1 font-inter text-xs font-bold text-white">
                        Hemat <span x-text="discountPercent()" class="mx-1"></span>%
                    </span>
                    @error('promo_price') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <span class="mb-1.5 block font-inter text-xs font-semibold text-[#565A66]">Durasi Masa Aktif *</span>
                    <div class="flex gap-2">
                        <x-text-input type="number" name="duration_value" x-model="form.duration_value" required min="1" step="1" aria-label="Jumlah durasi" class="!w-1/2 !bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45]" />
                        <x-select-input name="duration_unit" x-model="form.duration_unit" required aria-label="Satuan durasi" class="!w-1/2 !bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45]">
                            <option value="Hari">Hari</option>
                            <option value="Bulan">Bulan</option>
                            <option value="Tahun">Tahun</option>
                        </x-select-input>
                    </div>
                    @error('duration_value') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                    @error('duration_unit') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <x-input-label for="pkg-duration-in-days" value="Kalkulasi Masa Aktif (Hari) *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <x-text-input id="pkg-duration-in-days" type="number" name="duration_in_days" x-model="form.duration_in_days" required min="1" step="1" class="!bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45]" />
                    @error('duration_in_days') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <x-input-label for="package-feature-input" value="Fitur &amp; Fasilitas Benefit *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                <div class="flex flex-col gap-2 sm:flex-row">
                    <x-text-input
                        id="package-feature-input"
                        type="text"
                        x-model="newFeature"
                        x-on:keydown.enter.prevent="addFeature()"
                        maxlength="255"
                        placeholder="Ketik nama fasilitas, lalu tekan Enter..."
                        class="min-w-0 flex-1 !bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45]"
                    />
                    <button
                        type="button"
                        x-on:click="addFeature()"
                        class="bg-[#16151A] text-white hover:bg-black px-4 py-2.5 rounded-full font-inter text-xs font-semibold transition"
                    >
                        + Tambah Fitur
                    </button>
                </div>
                <p x-show="form.facilities.length === 0" class="mt-2 font-inter text-xs text-[#565A66]">Tambahkan setidaknya satu fasilitas.</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <template x-for="(facility, index) in form.facilities" x-bind:key="`${index}-${facility}`">
                        <span class="inline-flex max-w-full items-center gap-2 rounded-full border border-[#E5E7EB] bg-[#F6F6F7] py-1.5 pl-3 pr-2 font-inter text-xs text-[#16151A]">
                            <span class="text-[#10B981]" aria-hidden="true">✓</span>
                            <span class="break-all" x-text="facility"></span>
                            <input type="hidden" name="facilities[]" x-bind:value="facility">
                            <button type="button" x-on:click="removeFeature(index)" x-bind:aria-label="`Hapus ${facility}`" class="ml-1 font-bold text-[#565A66] hover:text-[#ED1B45]">&times;</button>
                        </span>
                    </template>
                </div>
                @error('facilities') <span class="mt-2 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                @error('facilities.*') <span class="mt-2 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <label class="inline-flex cursor-pointer items-center gap-3 font-inter text-sm font-semibold text-[#16151A]">
                <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-[#D4D4D8] text-[#10B981] focus:ring-[#10B981]">
                Paket aktif dan dapat dipilih member
            </label>

            <div class="flex flex-col-reverse gap-3 border-t border-[#E5E7EB] pt-5 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    x-on:click="showModal = false"
                    class="rounded-full bg-[#F6F6F7] px-5 py-2.5 font-inter text-sm font-semibold text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] transition"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="bg-[#ED1B45] text-white px-6 py-2.5 rounded-full font-inter text-sm font-semibold shadow-sm hover:bg-[#D1123D] transition"
                >
                    <span x-text="isEditMode ? 'Simpan Perubahan' : 'Simpan Paket'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
