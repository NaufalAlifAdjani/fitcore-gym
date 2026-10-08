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
        class="max-h-[92vh] w-full max-w-3xl overflow-y-auto rounded-[24px] bg-white p-5 shadow-2xl sm:p-8"
    >
        <div class="mb-6 flex items-start justify-between border-b border-[#F0F0F1] pb-5">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#BA0030]">Katalog Membership</p>
                <h2 id="package-modal-title" class="mt-1 font-heading text-xl font-bold text-[#16151A]" x-text="isEditMode ? 'Edit Paket Membership' : 'Tambah Paket Membership'"></h2>
            </div>
            <button type="button" x-on:click="showModal = false" aria-label="Tutup modal" class="rounded-full p-2 text-[#858894] hover:bg-[#F4F4F5] hover:text-[#16151A]">
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
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-[#565A66]">Nama Paket Membership *</span>
                    <input type="text" name="name" x-model="form.name" required maxlength="255" placeholder="FitCore Unlimited Pro Year" class="w-full rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]">
                    @error('name') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-[#565A66]">Kategori / Tier *</span>
                    <select name="tier" x-model="form.tier" required class="w-full rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]">
                        <option value="Basic">Basic</option>
                        <option value="Standard">Standard</option>
                        <option value="Premium & VIP">Premium &amp; VIP</option>
                    </select>
                    @error('tier') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-[#565A66]">Nama Badge</span>
                    <input type="text" name="badge" x-model="form.badge" maxlength="50" placeholder="Contoh: BEST SELLER" class="w-full rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm uppercase focus:border-[#BA0030] focus:ring-[#BA0030]">
                    @error('badge') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-[#565A66]">Sesi Personal Trainer</span>
                    <input type="number" name="pt_sessions" x-model="form.pt_sessions" min="0" step="1" class="w-full rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]">
                    @error('pt_sessions') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <label class="block">
                <span class="mb-1.5 block text-xs font-semibold text-[#565A66]">Deskripsi</span>
                <textarea name="description" x-model="form.description" rows="2" class="w-full rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]"></textarea>
                @error('description') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
            </label>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-[#565A66]">Tarif Normal (Rp) *</span>
                    <input type="number" name="price" x-model="form.price" required min="0" step="1" placeholder="4500000" class="w-full rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]">
                    @error('price') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-[#565A66]">Harga Promo (Rp)</span>
                    <input type="number" name="promo_price" x-model="form.promo_price" min="0" step="1" placeholder="Opsional" class="w-full rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]">
                    <span x-show="discountPercent() > 0" x-cloak class="mt-2 inline-flex rounded-full bg-[#E61241] px-3 py-1 text-xs font-bold text-white">
                        Hemat <span x-text="discountPercent()" class="mx-1"></span>%
                    </span>
                    @error('promo_price') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <span class="mb-1.5 block text-xs font-semibold text-[#565A66]">Durasi Masa Aktif *</span>
                    <div class="flex gap-2">
                        <input type="number" name="duration_value" x-model="form.duration_value" required min="1" step="1" aria-label="Jumlah durasi" class="w-1/2 rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]">
                        <select name="duration_unit" x-model="form.duration_unit" required aria-label="Satuan durasi" class="w-1/2 rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]">
                            <option value="Hari">Hari</option>
                            <option value="Bulan">Bulan</option>
                            <option value="Tahun">Tahun</option>
                        </select>
                    </div>
                    @error('duration_value') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    @error('duration_unit') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold text-[#565A66]">Kalkulasi Masa Aktif (Hari) *</span>
                    <input type="number" name="duration_in_days" x-model="form.duration_in_days" required min="1" step="1" class="w-full rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]">
                    @error('duration_in_days') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>
            </div>

            <div>
                <label for="package-feature-input" class="mb-1.5 block text-xs font-semibold text-[#565A66]">Fitur &amp; Fasilitas Benefit *</label>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <input
                        id="package-feature-input"
                        type="text"
                        x-model="newFeature"
                        x-on:keydown.enter.prevent="addFeature()"
                        maxlength="255"
                        placeholder="Ketik nama fasilitas, lalu tekan Enter..."
                        class="min-w-0 flex-1 rounded-xl border-[#E5E5E8] bg-white px-3.5 py-3 text-sm focus:border-[#BA0030] focus:ring-[#BA0030]"
                    >
                    <button type="button" x-on:click="addFeature()" class="rounded-xl bg-[#1E1E1E] px-4 py-3 text-xs font-semibold text-white transition hover:bg-black">+ Tambah Fitur</button>
                </div>
                <p x-show="form.facilities.length === 0" class="mt-2 text-xs text-[#858894]">Tambahkan setidaknya satu fasilitas.</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <template x-for="(facility, index) in form.facilities" x-bind:key="`${index}-${facility}`">
                        <span class="inline-flex max-w-full items-center gap-2 rounded-full border border-[#E5E5E8] bg-[#F9F9FA] py-1.5 pl-3 pr-2 text-xs text-[#34343B]">
                            <span class="text-[#10B981]" aria-hidden="true">✓</span>
                            <span class="break-all" x-text="facility"></span>
                            <input type="hidden" name="facilities[]" x-bind:value="facility">
                            <button type="button" x-on:click="removeFeature(index)" x-bind:aria-label="`Hapus ${facility}`" class="ml-1 font-bold text-[#858894] hover:text-[#BA0030]">&times;</button>
                        </span>
                    </template>
                </div>
                @error('facilities') <span class="mt-2 block text-xs text-red-600">{{ $message }}</span> @enderror
                @error('facilities.*') <span class="mt-2 block text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <label class="inline-flex cursor-pointer items-center gap-3 text-sm font-semibold text-[#34343B]">
                <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-[#D4D4D8] text-[#10B981] focus:ring-[#10B981]">
                Paket aktif dan dapat dipilih member
            </label>

            <div class="flex flex-col-reverse gap-3 border-t border-[#F0F0F1] pt-5 sm:flex-row sm:justify-end">
                <button type="button" x-on:click="showModal = false" class="rounded-full px-5 py-3 text-sm font-semibold text-[#565A66] transition hover:bg-[#F4F4F5]">Batal</button>
                <button type="submit" class="rounded-full bg-[#BA0030] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#970027] focus:outline-none focus:ring-2 focus:ring-[#BA0030] focus:ring-offset-2" x-text="isEditMode ? 'Simpan Perubahan' : 'Simpan Paket'"></button>
            </div>
        </form>
    </div>
</div>
