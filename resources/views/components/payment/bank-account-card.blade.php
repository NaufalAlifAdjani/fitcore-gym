@props([
    'bank',
])

<<<<<<< HEAD
<div {{ $attributes->merge(['class' => 'rounded-3xl border border-[#E5E7EB] bg-white p-6 sm:p-7 text-[#16151A] shadow-[0_4px_12px_rgba(0,0,0,0.04)] relative overflow-hidden']) }} x-data="{ copied: false }">
    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-4 mb-5">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#565A66]">Rekening Tujuan Transfer</span>
            <h3 class="font-heading text-base font-extrabold text-[#16151A] mt-0.5">
                {{ $bank->name ?? 'Bank Tujuan' }}
            </h3>
        </div>
        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[10px] font-extrabold uppercase text-emerald-700 tracking-wider">
=======
<div {{ $attributes->merge(['class' => 'rounded-3xl border border-orange-100 bg-white p-6 sm:p-7 text-zinc-900 shadow-md shadow-orange-500/5 relative overflow-hidden']) }} x-data="{ copied: false }">
    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-5">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-zinc-500">Rekening Tujuan Transfer</span>
            <h3 class="font-heading text-base font-extrabold text-zinc-900 mt-0.5">
                {{ $bank->name ?? 'Bank Tujuan' }}
            </h3>
        </div>
        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[10px] font-extrabold uppercase text-emerald-600 tracking-wider">
>>>>>>> origin/feature/theme-blaze-orange
            Akun Bisnis
        </span>
    </div>

    <!-- Nomor Rekening Besar & Tombol Salin -->
<<<<<<< HEAD
    <div class="rounded-2xl bg-[#F6F6F7] border border-[#E5E7EB] p-5 flex items-center justify-between gap-4">
        <div>
            <p class="text-[10px] uppercase font-bold text-[#565A66]">Nomor Rekening Resmi</p>
            <p class="font-mono text-xl sm:text-2xl font-black text-[#16151A] tracking-wider mt-1" id="accountNumberText">
                {{ $bank->account_number ?? '-' }}
            </p>
            <p class="text-xs text-[#565A66] mt-1">
                a.n. <span class="font-semibold text-[#16151A]">{{ $bank->account_holder ?? 'FitCore Gym' }}</span>
=======
    <div class="rounded-2xl bg-orange-50/70 border border-orange-200/80 p-5 flex items-center justify-between gap-4">
        <div>
            <p class="text-[10px] uppercase font-bold text-zinc-500">Nomor Rekening Resmi</p>
            <p class="font-mono text-xl sm:text-2xl font-black text-zinc-900 tracking-wider mt-1" id="accountNumberText">
                {{ $bank->account_number ?? '-' }}
            </p>
            <p class="text-xs text-zinc-600 mt-1">
                a.n. <span class="font-semibold text-zinc-900">{{ $bank->account_holder ?? 'FitCore Gym' }}</span>
>>>>>>> origin/feature/theme-blaze-orange
            </p>
        </div>

        <button
            type="button"
            x-on:click="
                navigator.clipboard.writeText('{{ $bank->account_number ?? '' }}');
                copied = true;
                setTimeout(() => copied = false, 2500);
            "
            class="shrink-0 flex items-center gap-1.5 px-4 py-2.5 rounded-xl font-bold text-xs transition duration-200 border cursor-pointer"
<<<<<<< HEAD
            x-bind:class="copied ? 'bg-emerald-600 text-white border-emerald-500 shadow-sm' : 'bg-white text-[#16151A] border-[#E5E7EB] hover:bg-gray-50 shadow-2xs'"
=======
            x-bind:class="copied ? 'bg-emerald-600 text-white border-emerald-500 shadow-md shadow-emerald-500/20' : 'bg-white text-zinc-700 border-gray-300 hover:border-[#FF5722] hover:text-[#FF5722] shadow-xs'"
>>>>>>> origin/feature/theme-blaze-orange
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
            </svg>
            <span x-text="copied ? 'Tersalin!' : 'Salin Rekening'">Salin Rekening</span>
        </button>
    </div>

<<<<<<< HEAD
    <div class="mt-4 flex items-center gap-2 text-[11px] text-[#565A66]">
        <svg class="h-4 w-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
=======
    <div class="mt-4 flex items-center gap-2 text-[11px] text-zinc-500">
        <svg class="h-4 w-4 text-emerald-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
>>>>>>> origin/feature/theme-blaze-orange
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span>Rekening resmi terverifikasi &amp; dienkripsi otomatis oleh sistem FitCore</span>
    </div>
</div>

