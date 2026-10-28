@props([
    'type' => 'default',
    'status' => null,
    'variant' => null,
    'dot' => false,
])

@php
    $resolvedType = strtolower($type ?? $status ?? $variant ?? 'default');

    if ($resolvedType === 'danger' || $resolvedType === 'rejected' || $resolvedType === 'ditolak') {
        $resolvedType = 'error';
    } elseif ($resolvedType === 'pending' || $resolvedType === 'menunggu') {
        $resolvedType = 'warning';
    } elseif ($resolvedType === 'verified' || $resolvedType === 'terverifikasi' || $resolvedType === 'active' || $resolvedType === 'aktif') {
        $resolvedType = 'success';
    }

    $colors = [
        'success' => 'text-[#10B981] bg-[#10B981]/10',
        'warning' => 'text-[#F59E0B] bg-[#F59E0B]/10',
        'error' => 'text-[#EF4444] bg-[#EF4444]/10',
        'default' => 'text-[#565A66] bg-[#565A66]/10',
    ];

    $colorClass = $colors[$resolvedType] ?? $colors['default'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold font-inter {$colorClass}"]) }}>
    @if ($dot)
        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-current shrink-0" aria-hidden="true"></span>
    @endif
    <span>{{ $slot->isEmpty() ? ($status ? strtoupper($status) : ($type !== 'default' ? ucfirst($type) : '')) : $slot }}</span>
</span>
