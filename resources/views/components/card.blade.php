@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<section {{ $attributes->merge(['class' => 'overflow-hidden rounded-[20px] bg-white shadow-[0px_1px_2px_rgba(0,0,0,0.05)]']) }}>
    @if (isset($header) || $title || $subtitle)
        <div class="flex flex-col gap-1 border-b border-[#F0F0F1] px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-7">
            @if (isset($header))
                {{ $header }}
            @else
                <div>
                    @if ($title)
                        <h2 class="font-heading text-base font-bold text-[#16151A]">{{ $title }}</h2>
                    @endif
                    @if ($subtitle)
                        <p class="mt-0.5 text-xs text-[#858894]">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <div @class([
        'p-5 sm:p-7' => $padding,
    ])>
        {{ $slot }}
    </div>

    @if (isset($footer))
        <footer class="border-t border-[#F0F0F1] bg-[#FCFCFC] px-5 py-4 sm:px-7">
            {{ $footer }}
        </footer>
    @endif
</section>
