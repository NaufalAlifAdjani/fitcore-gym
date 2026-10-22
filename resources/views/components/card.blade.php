@props([
    'title' => null,
    'subtitle' => null,
    'padding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-[16px] shadow-[0_4px_12px_rgba(0,0,0,0.06)]']) }}>
    @if (isset($header))
        {{ $header }}
    @elseif ($title || $subtitle)
        <div class="border-b border-[#E5E7EB] px-6 py-4">
            @if ($title)
                <h2 class="font-poppins text-lg font-bold text-[#16151A]">{{ $title }}</h2>
            @endif
            @if ($subtitle)
                <p class="mt-0.5 font-inter text-xs text-[#565A66]">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    @if ($padding)
        <div class="p-6">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif

    @if (isset($footer))
        <div class="border-t border-[#E5E7EB] bg-white px-6 py-4 rounded-b-[16px]">
            {{ $footer }}
        </div>
    @endif
</div>
