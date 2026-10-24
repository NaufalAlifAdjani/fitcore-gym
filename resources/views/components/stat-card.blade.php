@props([
    'title' => null,
    'value' => null,
    'trend_text' => null,
    'trend_color' => 'bg-[#10B981]',
    // Backwards-compat props for auth-hero
    'number' => null,
    'label' => null,
    'sublabel' => null,
    'highlight' => null,
    'badge' => null,
    'isFeatured' => false,
])

@if ($title !== null)
    <x-card {{ $attributes->merge(['class' => 'p-6 transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_20px_rgba(0,0,0,0.08)] flex flex-col justify-between']) }}>
        <div class="flex items-center justify-between">
            <span class="font-inter text-xs font-bold uppercase tracking-wider text-[#565A66]">
                {{ $title }}
            </span>
            @if (isset($icon))
                <div class="shrink-0">
                    {{ $icon }}
                </div>
            @endif
        </div>
        <div class="mt-4">
            <div class="font-poppins font-extrabold text-[3rem] leading-none text-[#16151A]">
                {{ $value }}
            </div>
            @if ($trend_text)
                <p class="mt-2.5 flex items-center gap-1.5 font-inter text-xs text-[#565A66]">
                    <span class="inline-block h-2 w-2 rounded-full {{ $trend_color }}"></span>
                    {{ $trend_text }}
                </p>
            @endif
        </div>
    </x-card>
@else
    <div x-data="{ current: 0, target: {{ (int) $number }} }"
         x-init="
            let step = Math.max(1, Math.floor(target / 30));
            let timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
            }, 40);
         "
         {{ $attributes->merge([
            'class' => ($isFeatured 
                ? 'relative bg-[#181622]/95 border-2 border-[#be0a32] shadow-[0_4px_15px_rgba(190,10,50,0.25)]' 
                : 'bg-[#181622]/90 border border-gray-800/80') 
                . ' rounded-2xl p-4 text-center flex flex-col items-center justify-center cursor-pointer select-none transition-all duration-300 ease-out transform hover:-translate-y-3 hover:scale-[1.04] hover:shadow-[0_20px_35px_rgba(0,0,0,0.7),0_0_25px_rgba(190,10,50,0.4)] hover:border-[#be0a32] hover:bg-[#221d33] group'
         ]) }}>

        @if ($badge)
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-[#be0a32] text-white text-[9px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-sm transition-transform duration-300 group-hover:scale-110">
                {{ $badge }}
            </span>
        @endif

        <div class="text-3xl sm:text-4xl font-black text-white flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
            <span x-text="current">0</span><span class="text-[#be0a32] font-black">+</span>
        </div>

        <div class="text-[10px] sm:text-[11px] font-bold text-gray-400 mt-2 tracking-wider uppercase leading-tight transition-colors duration-300 group-hover:text-gray-200">
            {{ $label }}
            @if ($sublabel)
                <br>{{ $sublabel }}
            @endif
            @if ($highlight)
                <br><span class="text-[#be0a32] font-extrabold">{{ $highlight }}</span>
            @endif
        </div>
    </div>
@endif
