@props([
    'initials',
    'color_theme' => 'red',
])

@php
    $themes = [
        'red' => 'bg-[#ED1B45]/10 text-[#ED1B45]',
        'orange' => 'bg-[#F59E0B]/10 text-[#F59E0B]',
        'blue' => 'bg-[#3B82F6]/10 text-[#3B82F6]',
        'purple' => 'bg-[#8B5CF6]/10 text-[#8B5CF6]',
        'green' => 'bg-[#10B981]/10 text-[#10B981]',
        'gray' => 'bg-[#565A66]/10 text-[#565A66]',
    ];

    $themeClass = $themes[$color_theme] ?? $themes['red'];
@endphp

<div {{ $attributes->merge(['class' => "w-10 h-10 rounded-full font-bold text-sm flex items-center justify-center font-inter shrink-0 {$themeClass}"]) }}>
    {{ strtoupper(substr($initials, 0, 2)) }}
</div>
