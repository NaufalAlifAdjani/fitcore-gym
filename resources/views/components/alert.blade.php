@props([
    'type' => 'success',
    'message' => null,
    'dismissible' => true,
])

@php
    $styleClasses = match ($type) {
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'error', 'danger' => 'border-rose-200 bg-rose-50 text-rose-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'info' => 'border-sky-200 bg-sky-50 text-sky-800',
        default => 'border-emerald-200 bg-emerald-50 text-emerald-800',
    };

    $icon = match ($type) {
        'success' => '✓',
        'error', 'danger' => '!',
        'warning' => '⚠',
        'info' => 'i',
        default => '✓',
    };
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition
    role="status"
    {{ $attributes->merge([
        'class' => "flex items-center justify-between gap-3 rounded-2xl border px-5 py-3.5 text-sm font-semibold transition-all {$styleClasses}"
    ]) }}
>
    <div class="flex items-center gap-3">
        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-current/10 font-bold text-current" aria-hidden="true">
            {{ $icon }}
        </span>
        <div>
            {{ $message ?? $slot }}
        </div>
    </div>

    @if ($dismissible)
        <button
            type="button"
            x-on:click="show = false"
            class="text-current/70 hover:text-current focus:outline-none"
            aria-label="Tutup notifikasi"
        >
            &times;
        </button>
    @endif
</div>
