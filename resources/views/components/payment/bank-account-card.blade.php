@props([
    'bank',
])

<div {{ $attributes->merge(['class' => 'rounded-3xl border border-slate-800 bg-[#0B0F17] p-6 sm:p-7 text-white shadow-xl relative overflow-hidden']) }} x-data="{ copied: false }">
    <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-5">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Rekening Tujuan Transfer</span>
            <h3 class="font-heading text-base font-extrabold text-white mt-0.5">
                {{ $bank->name ?? 'Bank Tujuan' }}
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
            <p class="font-mono text-xl sm:text-2xl font-black text-white tracking-wider mt-1" id="accountNumberText">
                {{ $bank->account_number ?? '-' }}
            </p>
            <p class="text-xs text-slate-400 mt-1">
                a.n. <span class="font-semibold text-slate-200">{{ $bank->account_holder ?? 'FitCore Gym' }}</span>
            </p>
        </div>

        <button
            type="button"
            x-on:click="
                navigator.clipboard.writeText('{{ $bank->account_number ?? '' }}');
                copied = true;
                setTimeout(() => copied = false, 2500);
            "
            class="shrink-0 flex items-center gap-1.5 px-4 py-2.5 rounded-xl font-bold text-xs transition duration-200 border"
            x-bind:class="copied ? 'bg-emerald-600 text-white border-emerald-500 shadow-md shadow-emerald-900/30' : 'bg-slate-800 text-slate-200 border-slate-700 hover:bg-slate-700 hover:text-white'"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
            </svg>
            <span x-text="copied ? 'Tersalin!' : 'Salin Rekening'">Salin Rekening</span>
        </button>
    </div>

    <div class="mt-4 flex items-center gap-2 text-[11px] text-slate-400">
        <svg class="h-4 w-4 text-emerald-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span>Rekening resmi terverifikasi &amp; dienkripsi otomatis oleh sistem FitCore</span>
    </div>
</div>

