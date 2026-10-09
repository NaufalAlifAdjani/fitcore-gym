<x-member-layout title="Checkout Pembayaran Membership - FitCore Athletic" active-nav="packages">
    <div class="py-12 px-4 sm:px-6 lg:px-8 bg-[#0B0F17] min-h-screen">
        <div class="mx-auto max-w-5xl">
            <!-- Header Breadcrumb / Back Button -->
            <div class="mb-8 flex items-center justify-between">
                <a href="{{ route('member.membership.packages') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-white transition">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Kembali ke Katalog Paket
                </a>
                <span class="text-xs font-semibold text-rose-400">Langkah 2 dari 2: Checkout &amp; Pembayaran</span>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <!-- Left Column: Package Summary -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6 sm:p-8 shadow-xl">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#BA0030]">Paket Dipilih</span>
                                <h2 class="font-heading text-xl font-extrabold text-white mt-0.5">{{ $package->name }}</h2>
                            </div>
                            @if ($package->badge)
                                <x-badge :status="$package->badge" :dot="false" size="xs" />
                            @endif
                        </div>

                        <div class="mt-6 space-y-4 text-xs">
                            <div class="flex justify-between text-slate-400">
                                <span>Durasi Masa Aktif</span>
                                <span class="font-bold text-white">{{ $package->duration_value }} {{ $package->duration_unit }} ({{ $package->duration_in_days }} Hari)</span>
                            </div>

                            @php
                                $hasPromo = $package->promo_price !== null && $package->price > $package->promo_price;
                                $effectivePrice = $hasPromo ? $package->promo_price : $package->price;
                            @endphp

                            <div class="flex justify-between text-slate-400">
                                <span>Harga Normal</span>
                                <span class="font-bold text-white">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                            </div>

                            @if ($hasPromo)
                                <div class="flex justify-between text-rose-400">
                                    <span>Potongan Promo</span>
                                    <span class="font-bold">- Rp {{ number_format($package->price - $package->promo_price, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="border-t border-slate-800 pt-4 flex justify-between items-baseline">
                                <span class="font-bold text-slate-200">Total Tagihan</span>
                                <span class="font-heading text-2xl font-extrabold text-[#BA0030]">
                                    Rp {{ number_format($effectivePrice, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl bg-slate-800/50 p-4">
                            <p class="text-[11px] font-bold text-slate-300 uppercase tracking-wider">Fitur Paket Ini:</p>
                            <ul class="mt-2 space-y-2">
                                @foreach ($package->facilities ?? [] as $facility)
                                    <li class="flex items-center gap-2 text-xs text-slate-300">
                                        <span class="text-[#BA0030] font-bold">✓</span>
                                        <span>{{ $facility }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Target Bank Information Box -->
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6 sm:p-8 shadow-xl">
                        <h3 class="font-heading text-sm font-extrabold text-white flex items-center gap-2">
                            <svg class="h-4 w-4 text-[#BA0030]" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="2"/><path d="M3 9h18" stroke="currentColor" stroke-width="2"/></svg>
                            Rekening Tujuan Transfer Gym
                        </h3>
                        <p class="mt-1 text-xs text-slate-400">Transfer tepat ke salah satu rekening resmi FitCore Gym di bawah ini:</p>

                        <div class="mt-4 space-y-3">
                            @foreach ($banks as $bank)
                                <div class="rounded-2xl border border-slate-800 bg-slate-950 p-4 flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-white text-xs">{{ $bank->name }}</span>
                                            <span class="text-[10px] text-slate-400">a.n. {{ $bank->account_holder }}</span>
                                        </div>
                                        <p class="font-mono text-sm font-bold text-rose-400 mt-1 select-all">{{ $bank->account_number }}</p>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950/60 px-2.5 py-1 rounded-full border border-emerald-800/40">AKTIF</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column: Upload Proof Form -->
                <div class="lg:col-span-7">
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/90 p-6 sm:p-8 shadow-xl">
                        <h3 class="font-heading text-lg font-extrabold text-white">Form Bukti Transfer</h3>
                        <p class="mt-1 text-xs text-slate-400">Lengkapi detail rekening Anda dan unggah struk transfer resmi untuk verifikasi.</p>

                        @if ($errors->any())
                            <x-alert type="error" class="mt-4 mb-2">
                                Silakan periksa kembali kelengkapan form pengiriman bukti bayar Anda.
                            </x-alert>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('member.membership.process-payment', $package->id) }}"
                            enctype="multipart/form-data"
                            x-data="{
                                imagePreview: null,
                                handleFileSelect(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (event) => {
                                            this.imagePreview = event.target.result;
                                        };
                                        reader.readAsDataURL(file);
                                    } else {
                                        this.imagePreview = null;
                                    }
                                }
                            }"
                            class="mt-6 space-y-5"
                        >
                            @csrf

                            <!-- Input Nama Pengirim -->
                            <div>
                                <x-input-label for="sender_name" value="Nama Pemilik Rekening Pengirim *" class="!text-slate-300" />
                                <x-text-input
                                    id="sender_name"
                                    type="text"
                                    name="sender_name"
                                    value="{{ old('sender_name', Auth::user()?->name) }}"
                                    required
                                    placeholder="Contoh: Budi Santoso"
                                    class="!bg-slate-950 !border-slate-800 !text-white focus:!border-[#BA0030]"
                                />
                                <x-input-error :messages="$errors->get('sender_name')" />
                            </div>

                            <!-- Dropdown Bank Tujuan -->
                            <div>
                                <x-input-label for="bank_id" value="Pilih Bank Tujuan Gym *" class="!text-slate-300" />
                                <x-select-input
                                    id="bank_id"
                                    name="bank_id"
                                    required
                                    class="!bg-slate-950 !border-slate-800 !text-white focus:!border-[#BA0030]"
                                >
                                    <option value="">-- Pilih Bank Tujuan Transfer --</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}" @selected(old('bank_id') == $bank->id)>
                                            {{ $bank->name }} - {{ $bank->account_number }} (a.n. {{ $bank->account_holder }})
                                        </option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('bank_id')" />
                            </div>

                            <!-- Upload Proof File & Image Preview -->
                            <div>
                                <x-input-label for="proof_image" value="Unggah Struk / Bukti Transfer *" class="!text-slate-300" />
                                <div class="mt-1 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-800 bg-slate-950 p-6 transition hover:border-[#BA0030]/60">
                                    <template x-if="!imagePreview">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-500" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <div class="mt-3 flex text-xs text-slate-400">
                                                <label for="proof_image" class="relative cursor-pointer font-bold text-rose-400 hover:underline">
                                                    <span>Klik untuk mengunggah</span>
                                                    <input
                                                        id="proof_image"
                                                        name="proof_image"
                                                        type="file"
                                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                                        required
                                                        x-on:change="handleFileSelect($event)"
                                                        class="sr-only"
                                                    >
                                                </label>
                                                <span class="pl-1">atau tarik gambar ke sini</span>
                                            </div>
                                            <p class="mt-1 text-[10px] text-slate-500">Format JPG, PNG, WEBP (Maksimal 2MB)</p>
                                        </div>
                                    </template>

                                    <template x-if="imagePreview">
                                        <div class="w-full text-center">
                                            <img x-bind:src="imagePreview" alt="Pratinjau Bukti Bayar" class="max-h-64 rounded-xl mx-auto object-contain border border-slate-700 shadow-md">
                                            <div class="mt-3 flex items-center justify-center gap-3">
                                                <label for="proof_image" class="cursor-pointer text-xs font-bold text-rose-400 hover:underline">
                                                    Ganti Gambar
                                                </label>
                                                <button type="button" x-on:click="imagePreview = null" class="text-xs font-semibold text-slate-400 hover:text-white">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <x-input-error :messages="$errors->get('proof_image')" />
                            </div>

                            <div class="pt-4 border-t border-slate-800">
                                <x-button type="submit" variant="primary" class="w-full !py-3.5 justify-center font-extrabold text-sm shadow-lg shadow-[#BA0030]/20">
                                    Kirim Bukti Pembayaran
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-member-layout>
