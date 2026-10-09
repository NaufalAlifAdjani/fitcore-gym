@props([
    'variant' => 'neutral',
    'status' => null,
    'size' => 'md',
    'dot' => true,
])

@php
    $effectiveVariant = strtolower($status ?? $variant);

    $variantClasses = match ($effectiveVariant) {
        'pending', 'warning', 'menunggu' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'verified', 'terverifikasi', 'active', 'aktif', 'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'rejected', 'ditolak', 'inactive', 'nonaktif', 'danger' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'best seller' => 'bg-[#BA0030] text-white ring-transparent',
        'platinum vip' => 'bg-[#1E1E1E] text-white ring-transparent',
        'gold standard' => 'bg-amber-100 text-amber-800 ring-amber-300',
        'silver' => 'bg-[#E5E2E1] text-[#565A66] ring-gray-300',
        'student tier' => 'bg-sky-100 text-sky-800 ring-sky-200',
        'primary' => 'bg-[#BA0030]/10 text-[#BA0030] ring-[#BA0030]/20',
        default => 'bg-[#EEEEEF] text-[#565A66] ring-gray-200',
    };

    $sizeClasses = match ($size) {
        'xs' => 'px-2 py-0.5 text-[9px]',
        'sm' => 'px-2.5 py-1 text-[10px]',
        'lg' => 'px-4 py-2 text-xs',
        default => 'px-3 py-1.5 text-[10px]',
    };
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1.5 rounded-full font-bold uppercase tracking-wide ring-1 ring-inset transition-colors {$sizeClasses} {$variantClasses}"
]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 rounded-full bg-current shrink-0" aria-hidden="true"></span>
    @endif
    <span>{{ $slot->isEmpty() ? ($status ? ucfirst($status) : '') : $slot }}</span>
</span>
