@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
])

@php
    $variantClasses = match ($variant) {
        'primary' => 'bg-[#BA0030] text-white hover:bg-[#970027] focus:ring-[#BA0030]',
        'secondary' => 'bg-[#EEEEEF] text-[#565A66] hover:bg-[#E4E4E7] focus:ring-gray-300',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700 focus:ring-rose-500',
        'danger-outline' => 'border border-rose-200 text-rose-700 hover:bg-rose-50 focus:ring-rose-200',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500',
        'outline' => 'border border-[#E5E5E8] text-[#565A66] hover:bg-[#F9F9FA] focus:ring-gray-200',
        'dark' => 'bg-[#1E1E1E] text-white hover:bg-black focus:ring-gray-800',
        'ghost' => 'text-[#565A66] hover:bg-[#F4F4F5] hover:text-[#16151A] focus:ring-gray-200',
        default => 'bg-[#BA0030] text-white hover:bg-[#970027] focus:ring-[#BA0030]',
    };

    $sizeClasses = match ($size) {
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-4 py-2 text-xs',
        'lg' => 'px-6 py-3.5 text-sm',
        default => 'px-5 py-3 text-xs sm:text-sm',
    };

    $baseClasses = "inline-flex items-center justify-center gap-2 rounded-full font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 {$sizeClasses} {$variantClasses}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->merge(['class' => $baseClasses]) }}>
        {{ $slot }}
    </button>
@endif
