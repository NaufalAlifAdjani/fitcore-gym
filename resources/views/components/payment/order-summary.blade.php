@props([
    'badge' => '',
    'title' => '',
    'trainerName' => null,
    'changeUrl' => '#',
    'basePrice' => 0,
    'uniqueCode' => 0,
    'totalAmount' => 0,
    'submitLabel' => 'Beli Sekarang',
    'termsText' => 'Saya menyetujui Syarat & Ketentuan Keanggotaan FitCore Athletic yang berlaku.',
])

<x-card :padding="false" {{ $attributes->merge(['class' => '!rounded-3xl border border-[#E5E7EB] p-6 sm:p-8 shadow-[0_4px_12px_rgba(0,0,0,0.04)] bg-white']) }}>
    <h2 class="font-heading text-lg font-extrabold text-[#16151A] border-b border-[#E5E7EB] pb-4">
        Ringkasan Pesanan
    </h2>

    <!-- Package Info -->
    <div class="mt-5 flex items-start justify-between gap-3">
        <div>
            @if ($badge)
<<<<<<< HEAD
                <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-[#ED1B45]/10 text-[#ED1B45]">
=======
                <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-rose-100 text-[#FF5722]">
>>>>>>> origin/feature/theme-blaze-orange
                    {{ $badge }}
                </span>
            @endif
            <h3 class="font-heading text-xl font-extrabold text-[#16151A] mt-2">
                {{ $title }}
            </h3>
            @if ($trainerName)
                <p class="text-xs text-[#565A66] font-semibold mt-0.5">
                    Pelatih: <span class="text-[#16151A]">{{ $trainerName }}</span>
                </p>
            @endif
<<<<<<< HEAD
            <a href="{{ $changeUrl }}" class="inline-block text-xs font-bold text-[#ED1B45] hover:underline mt-1.5">
=======
            <a href="{{ $changeUrl }}" class="inline-block text-xs font-bold text-[#FF5722] hover:underline mt-1.5">
>>>>>>> origin/feature/theme-blaze-orange
                Ubah Paket
            </a>
        </div>
        <div class="text-right">
            <span class="font-heading text-lg font-extrabold text-[#16151A]">
                Rp {{ number_format($basePrice, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Input Form Kode Promo -->
    <div class="mt-6 border-t border-[#E5E7EB] pt-5">
        <label class="block text-xs font-bold text-[#16151A] mb-2">
            Punya kode promo atau referral?
        </label>
        <div class="flex gap-2">
            <x-text-input
                type="text"
                x-model="promoCode"
                placeholder="Contoh: FITCORENEW"
                class="uppercase tracking-wider !text-xs !p-2.5"
            />
            <x-button
                type="button"
                variant="dark"
                size="sm"
                x-on:click="applyPromo()"
                class="!rounded-xl shrink-0 cursor-pointer"
            >
                Terapkan
            </x-button>
        </div>
        <div x-show="promoApplied" x-cloak class="mt-2 text-xs font-semibold text-emerald-600 flex items-center gap-1.5">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Kode promo berhasil diterapkan (-Rp 50.000)
        </div>
    </div>

    <!-- Rincian Biaya -->
    <div class="mt-6 border-t border-[#E5E7EB] pt-5 space-y-3 text-xs">
        <div class="flex justify-between text-[#565A66]">
            <span>Biaya Paket</span>
            <span class="font-bold text-[#16151A]">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between items-center text-[#565A66]">
            <span>Biaya Layanan Aplikasi</span>
            <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">
                GRATIS
            </span>
        </div>
        <div class="flex justify-between text-[#565A66]">
            <span class="flex items-center gap-1">
                Kode Unik Verifikasi
                <span class="text-[#565A66]/70" title="Untuk identifikasi verifikasi transfer bank">(?)</span>
            </span>
            <span class="font-bold text-[#16151A]">+Rp {{ $uniqueCode }}</span>
        </div>
        <div x-show="promoApplied" x-cloak class="flex justify-between text-emerald-600 font-bold">
            <span>Diskon Promo</span>
            <span>-Rp 50.000</span>
        </div>
    </div>

<<<<<<< HEAD
    <!-- Box Total Pembayaran (Light Theme) -->
    <div class="mt-6 rounded-2xl bg-[#F6F6F7] border border-[#E5E7EB] p-5 text-[#16151A] shadow-sm">
        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#565A66]">
            TOTAL PEMBAYARAN :
        </span>
        <div class="mt-1 flex items-baseline justify-between">
            <span class="font-heading text-2xl sm:text-3xl font-black text-[#16151A] tracking-tight" x-text="'Rp ' + total.toLocaleString('id-ID')">
                Rp {{ number_format($totalAmount, 0, ',', '.') }}
            </span>
            <span class="text-[11px] text-[#ED1B45] font-semibold">Tepat s/d 3 digit terakhir</span>
=======
    <!-- Box Total Pembayaran (Light Athletic Palette) -->
    <div class="mt-6 rounded-2xl bg-gradient-to-r from-orange-50 via-white to-amber-50/50 border-2 border-orange-200 p-5 text-zinc-900 shadow-sm">
        <span class="text-[10px] font-extrabold uppercase tracking-widest text-zinc-500">
            TOTAL PEMBAYARAN :
        </span>
        <div class="mt-1 flex items-baseline justify-between">
            <span class="font-heading text-2xl sm:text-3xl font-black text-[#FF5722] tracking-tight" x-text="'Rp ' + total.toLocaleString('id-ID')">
                Rp {{ number_format($totalAmount, 0, ',', '.') }}
            </span>
            <span class="text-[11px] text-[#FF5722] font-semibold">Tepat s/d 3 digit terakhir</span>
>>>>>>> origin/feature/theme-blaze-orange
        </div>
    </div>

    <!-- Checkbox Konfirmasi Syarat & Ketentuan -->
    <div class="mt-6">
        <label class="flex items-start gap-3 cursor-pointer text-xs text-[#565A66]">
            <input
                type="checkbox"
                required
<<<<<<< HEAD
                class="mt-0.5 h-4 w-4 rounded border-[#E5E7EB] text-[#ED1B45] focus:ring-[#ED1B45]"
=======
                class="mt-0.5 h-4 w-4 rounded border-slate-300 text-[#FF5722] focus:ring-[#FF5722]"
>>>>>>> origin/feature/theme-blaze-orange
            />
            <span class="leading-relaxed">
                {{ $termsText }}
            </span>
        </label>
    </div>

    <!-- Tombol CTA Beli Sekarang -->
    <div class="mt-6">
        <x-button
            type="submit"
            variant="primary"
<<<<<<< HEAD
            class="w-full !py-4 !text-sm font-bold tracking-wide shadow-lg shadow-[#ED1B45]/20 hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer"
=======
            class="w-full !py-4 !text-sm font-bold tracking-wide shadow-lg shadow-[#FF5722]/30 hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2"
>>>>>>> origin/feature/theme-blaze-orange
        >
            <span>{{ $submitLabel }}</span>
            <span class="text-lg">→</span>
        </x-button>
    </div>
</x-card>

