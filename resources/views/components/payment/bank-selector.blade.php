@props([
    'banks' => [],
    'selectedBank' => '',
    'name' => 'bank_id',
    'title' => 'Rekening Transfer :',
    'subtitle' => 'Pilih salah satu rekening bank resmi FitCore Athletic tujuan transfer Anda:',
])

<div {{ $attributes->merge(['class' => 'space-y-6']) }}>
    <x-card :padding="false" class="!rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm bg-white">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
            <div>
                <h2 class="font-heading text-lg font-extrabold text-slate-900 flex items-center gap-2">
                    <svg class="h-5 w-5 text-[#BA0030]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="M3 10h18" />
                    </svg>
                    {{ $title }}
                </h2>
                <p class="mt-1 text-xs text-slate-500">{{ $subtitle }}</p>
            </div>
        </div>

        <!-- List Pilihan Bank dengan Radio Button Interaktif -->
        <div class="space-y-3.5">
            @forelse ($banks as $index => $bank)
                <label
                    class="relative flex items-center justify-between p-4 sm:p-5 rounded-2xl border cursor-pointer transition-all duration-200"
                    x-bind:class="selectedBank == '{{ $bank->id }}' ? 'border-[#BA0030] bg-rose-50/40 ring-2 ring-[#BA0030]/20 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/50'"
                >
                    <div class="flex items-center gap-4">
                        <input
                            type="radio"
                            name="{{ $name }}"
                            value="{{ $bank->id }}"
                            x-model="selectedBank"
                            class="h-4 w-4 text-[#BA0030] focus:ring-[#BA0030] border-slate-300"
                            {{ $selectedBank == $bank->id || ($selectedBank === '' && $index === 0) ? 'checked' : '' }}
                        />
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="font-heading font-extrabold text-slate-900 text-sm sm:text-base">
                                    {{ $bank->name }}
                                </span>
                                <span class="rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs font-bold text-slate-700">
                                    {{ $bank->account_number }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500 font-medium">
                                Transfer via ATM / M-Banking / Internet Banking (a.n. {{ $bank->account_holder }})
                            </p>
                        </div>
                    </div>
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                            Verifikasi Otomatis
                        </span>
                    </div>
                </label>
            @empty
                <div class="p-4 rounded-2xl border border-slate-200 bg-white">
                    <p class="text-xs text-slate-500">Tidak ada rekening bank aktif yang tersedia.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 rounded-2xl bg-amber-50 border border-amber-200/80 p-4 flex items-start gap-3 text-xs text-amber-800">
            <svg class="h-5 w-5 shrink-0 text-amber-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
            </svg>
            <div>
                <p class="font-bold">Penting Sebelum Melakukan Transfer:</p>
                <p class="mt-0.5 text-amber-700 leading-relaxed">
                    Pastikan transfer dilakukan tepat ke nomor rekening bank di atas beserta 3 digit kode unik pada langkah berikutnya agar transaksi dapat diverifikasi tanpa kendala.
                </p>
            </div>
        </div>
    </x-card>
</div>

