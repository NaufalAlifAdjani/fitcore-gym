<div
    x-show="showModal"
    x-cloak
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-[#16151A]/60 p-4 backdrop-blur-sm"
    aria-labelledby="pt-package-modal-title"
    role="dialog"
    aria-modal="true"
>
    <div
        x-on:click.outside="showModal = false"
        x-transition
        class="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-[20px] bg-white p-5 shadow-2xl sm:p-8"
    >
        <!-- Modal Header -->
        <div class="mb-6 flex items-start justify-between border-b border-[#E5E7EB] pb-5">
            <div>
                <p class="font-inter text-[10px] font-bold uppercase tracking-[0.16em] text-[#ED1B45]">Personal Trainer FitCore</p>
                <h2 id="pt-package-modal-title" class="mt-1 font-poppins text-xl font-bold text-[#16151A]" x-text="isEditMode ? 'Edit Paket Sesi PT' : 'Tambah Paket Sesi PT'"></h2>
            </div>
            <button type="button" x-on:click="showModal = false" aria-label="Tutup modal" class="rounded-full p-2 text-[#565A66] hover:bg-[#F6F6F7] hover:text-[#16151A] transition">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form method="POST" x-bind:action="formAction()" class="space-y-5">
            @csrf
            <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEditMode">
            <input type="hidden" name="package_id" x-bind:value="form.id">

            <!-- Nama Paket -->
            <div>
                <x-input-label for="pt-pkg-name" value="Nama Paket Sesi PT *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                <x-text-input
                    id="pt-pkg-name"
                    type="text"
                    name="name"
                    x-model="form.name"
                    required
                    maxlength="255"
                    placeholder="Contoh: PT 12 Sesi atau Performance Pro"
                    class="!bg-white !border-[#E5E7EB] !p-3 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45] w-full"
                />
                @error('name') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
            </div>

            <!-- Jumlah Sesi & Tarif Harga -->
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="pt-pkg-session-count" value="Jumlah Sesi PT *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <div class="relative">
                        <x-text-input
                            id="pt-pkg-session-count"
                            type="number"
                            name="pt_session_count"
                            x-model="form.pt_session_count"
                            required
                            min="1"
                            step="1"
                            placeholder="4"
                            class="!bg-white !border-[#E5E7EB] !p-3 pr-16 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45] w-full"
                        />
                        <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-[#565A66] font-inter">
                            Sesi
                        </span>
                    </div>
                    @error('pt_session_count') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="pt-pkg-price" value="Harga Paket (Rp) *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <div class="relative">
                        <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-[#565A66] font-inter">
                            Rp
                        </span>
                        <x-text-input
                            id="pt-pkg-price"
                            type="number"
                            name="price"
                            x-model="form.price"
                            required
                            min="0"
                            step="1"
                            placeholder="1500000"
                            class="!bg-white !border-[#E5E7EB] !p-3 pl-12 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45] w-full"
                        />
                    </div>
                    @error('price') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Live Estimasi Harga per Sesi -->
            <div x-show="perSessionPrice() > 0" x-cloak class="rounded-xl border border-rose-100 bg-rose-50/60 p-3.5 flex items-center justify-between text-xs font-inter text-[#16151A]">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-[#ED1B45]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 6v6l4 2" />
                    </svg>
                    <span class="text-[#565A66]">Estimasi tarif per sesi:</span>
                </div>
                <span class="font-bold text-[#ED1B45] text-sm" x-text="formattedPerSessionPrice() + ' / sesi'"></span>
            </div>

            <!-- Syarat Sisa Hari & Masa Berlaku Sesi -->
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="pt-pkg-min-membership-days" value="Syarat Min. Sisa Hari Membership *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <div class="relative">
                        <x-text-input
                            id="pt-pkg-min-membership-days"
                            type="number"
                            name="min_membership_days"
                            x-model="form.min_membership_days"
                            required
                            min="0"
                            step="1"
                            placeholder="0"
                            class="!bg-white !border-[#E5E7EB] !p-3 pr-16 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45] w-full"
                        />
                        <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-[#565A66] font-inter">
                            Hari
                        </span>
                    </div>
                    <p class="mt-1 text-[11px] font-inter text-[#565A66]">
                        Isi 0 jika tidak memerlukan syarat sisa masa aktif membership.
                    </p>
                    @error('min_membership_days') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-input-label for="pt-pkg-validity-days" value="Masa Berlaku Sesi *" class="!mb-1.5 font-inter text-xs text-[#565A66]" />
                    <div class="relative">
                        <x-text-input
                            id="pt-pkg-validity-days"
                            type="number"
                            name="validity_days"
                            x-model="form.validity_days"
                            required
                            min="1"
                            step="1"
                            placeholder="30"
                            class="!bg-white !border-[#E5E7EB] !p-3 pr-16 font-inter !text-sm focus:!border-[#ED1B45] focus:!ring-[#ED1B45] w-full"
                        />
                        <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-[#565A66] font-inter">
                            Hari
                        </span>
                    </div>
                    <p class="mt-1 text-[11px] font-inter text-[#565A66]">
                        Masa kedaluwarsa kuota sesi PT sejak paket dibeli.
                    </p>
                    @error('validity_days') <span class="mt-1 block font-inter text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Checkbox Status Aktif -->
            <div class="pt-2">
                <label class="inline-flex cursor-pointer items-center gap-3 font-inter text-sm font-semibold text-[#16151A]">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        x-model="form.is_active"
                        class="rounded border-[#D4D4D8] text-[#10B981] focus:ring-[#10B981] h-4 w-4"
                    >
                    <span>Paket aktif dan dapat dipilih serta dibeli oleh member</span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col-reverse gap-3 border-t border-[#E5E7EB] pt-5 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    x-on:click="showModal = false"
                    class="rounded-full bg-[#F6F6F7] px-5 py-2.5 font-inter text-sm font-semibold text-[#565A66] hover:bg-[#E5E7EB] hover:text-[#16151A] transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="bg-[#ED1B45] text-white px-6 py-2.5 rounded-full font-inter text-sm font-semibold shadow-sm hover:bg-[#D1123D] transition flex items-center justify-center gap-2 cursor-pointer"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span x-text="isEditMode ? 'Simpan Perubahan' : 'Simpan Paket PT'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
