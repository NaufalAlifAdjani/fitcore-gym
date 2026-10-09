@props([
    'label' => null,
    'badge' => null,
    'icon' => null,
    'name',
    'type' => 'text',
    'placeholder' => '',
    'value' => null,
])

<div class="space-y-1.5">
    @if ($label || $badge)
        <div class="flex items-center justify-between text-xs font-semibold text-gray-900">
            @if ($label)
                <label for="{{ $name }}">{{ $label }}</label>
            @endif
            @if ($badge)
                <span class="text-gray-400 font-normal">{{ $badge }}</span>
            @endif
        </div>
    @endif

    <div class="relative flex items-center">
        @if ($icon === 'at')
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 text-sm font-medium">
                @
            </span>
        @elseif ($icon === 'lock')
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
        @endif

        <input 
            @if ($type === 'password')
                :type="showPassword ? 'text' : 'password'"
            @else
                type="{{ $type }}"
            @endif
            id="{{ $name }}" 
            name="{{ $name }}" 
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'w-full ' 
                    . ($icon ? 'pl-10 ' : 'pl-4 ') 
                    . ($type === 'password' ? 'pr-11 ' : 'pr-4 ') 
                    . 'py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#be0a32] focus:border-transparent transition'
            ]) }}
        />

        @if ($type === 'password')
            <button type="button" @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!showPassword">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="showPassword" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                </svg>
            </button>
        @endif
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-1" />
</div>
