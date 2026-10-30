@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/95 p-4 text-emerald-900 shadow-xs']) }}>
        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white font-bold text-xs mt-0.5">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <div class="text-xs font-semibold leading-relaxed">
            {{ $status }}
        </div>
    </div>
@endif
