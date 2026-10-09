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
            <x-button type="button" variant="ghost" size="xs" x-on:click="showModal = false" aria-label="Tutup modal" class="!rounded-full !p-2">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </x-button>
        </div>

        <form method="POST" x-bind:action="formAction()" class="space-y-5">
            @csrf
            <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEditMode">
            <input type="hidden" name="package_id" x-bind:value="form.id">

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="package-name" value="Nama Paket Membership *" />
                    <x-text-input id="package-name" type="text" name="name" x-model="form.name" required maxlength="255" placeholder="FitCore Unlimited Pro Year" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="package-tier" value="Kategori / Tier *" />
                    <x-select-input id="package-tier" name="tier" x-model="form.tier" required>
                        <option value="Basic">Basic</option>
                        <option value="Standard">Standard</option>
                        <option value="Premium & VIP">Premium &amp; VIP</option>
                    </x-select-input>
                    <x-input-error :messages="$errors->get('tier')" />
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="package-badge" value="Nama Badge" />
                    <x-text-input id="package-badge" type="text" name="badge" x-model="form.badge" maxlength="50" placeholder="Contoh: BEST SELLER" class="uppercase" />
                    <x-input-error :messages="$errors->get('badge')" />
                </div>
                <div>
                    <x-input-label for="package-pt-sessions" value="Sesi Personal Trainer" />
                    <x-text-input id="package-pt-sessions" type="number" name="pt_sessions" x-model="form.pt_sessions" min="0" step="1" />
                    <x-input-error :messages="$errors->get('pt_sessions')" />
                </div>
            </div>

            <div>
                <x-input-label for="package-description" value="Deskripsi" />
                <textarea id="package-description" name="description" x-model="form.description" rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs font-medium text-gray-800 outline-none transition focus:border-[#BA0030] focus:bg-white focus:ring-2 focus:ring-[#BA0030]/20"></textarea>
                <x-input-error :messages="$errors->get('description')" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="package-price" value="Tarif Normal (Rp) *" />
                    <x-text-input id="package-price" type="number" name="price" x-model="form.price" required min="0" step="1" placeholder="4500000" />
                    <x-input-error :messages="$errors->get('price')" />
                </div>
                <div>
                    <x-input-label for="package-promo-price" value="Harga Promo (Rp)" />
                    <x-text-input id="package-promo-price" type="number" name="promo_price" x-model="form.promo_price" min="0" step="1" placeholder="Opsional" />
                    <template x-if="discountPercent() > 0">
                        <x-badge variant="danger" :dot="false" size="xs" class="mt-2 !bg-[#E61241] !text-white">
                            Hemat <span x-text="discountPercent()" class="mx-1"></span>%
                        </x-badge>
                    </template>
                    <x-input-error :messages="$errors->get('promo_price')" />
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label value="Durasi Masa Aktif *" />
                    <div class="flex gap-2">
                        <x-text-input type="number" name="duration_value" x-model="form.duration_value" required min="1" step="1" aria-label="Jumlah durasi" class="!w-1/2" />
                        <x-select-input name="duration_unit" x-model="form.duration_unit" required aria-label="Satuan durasi" class="!w-1/2">
                            <option value="Hari">Hari</option>
                            <option value="Bulan">Bulan</option>
                            <option value="Tahun">Tahun</option>
                        </x-select-input>
                    </div>
                    <x-input-error :messages="$errors->get('duration_value')" />
                    <x-input-error :messages="$errors->get('duration_unit')" />
                </div>
                <div>
                    <x-input-label for="package-duration-in-days" value="Kalkulasi Masa Aktif (Hari) *" />
                    <x-text-input id="package-duration-in-days" type="number" name="duration_in_days" x-model="form.duration_in_days" required min="1" step="1" />
                    <x-input-error :messages="$errors->get('duration_in_days')" />
                </div>
            </div>

            <div>
                <x-input-label for="package-feature-input" value="Fitur &amp; Fasilitas Benefit *" />
                <div class="flex flex-col gap-2 sm:flex-row">
                    <x-text-input
                        id="package-feature-input"
                        type="text"
                        x-model="newFeature"
                        x-on:keydown.enter.prevent="addFeature()"
                        maxlength="255"
                        placeholder="Ketik nama fasilitas, lalu tekan Enter..."
                        class="min-w-0 flex-1"
                    />
                    <x-button type="button" variant="dark" size="sm" x-on:click="addFeature()" class="!rounded-xl">+ Tambah Fitur</x-button>
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
                <x-input-error :messages="$errors->get('facilities')" />
                <x-input-error :messages="$errors->get('facilities.*')" />
            </div>

            <label class="inline-flex cursor-pointer items-center gap-3 text-sm font-semibold text-[#34343B]">
                <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-[#D4D4D8] text-[#10B981] focus:ring-[#10B981]">
                Paket aktif dan dapat dipilih member
            </label>

            <div class="flex flex-col-reverse gap-3 border-t border-[#F0F0F1] pt-5 sm:flex-row sm:justify-end">
                <x-button type="button" variant="ghost" x-on:click="showModal = false" class="!px-5 !py-3 !text-sm">Batal</x-button>
                <x-button type="submit" variant="primary" class="!px-6 !py-3 !text-sm" x-text="isEditMode ? 'Simpan Perubahan' : 'Simpan Paket'"></x-button>
            </div>
        </form>
    </div>
</div>
