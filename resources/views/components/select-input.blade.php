@props([
    'disabled' => false,
    'options' => [],
])

<select @disabled($disabled) {{ $attributes->merge([
    'class' => 'w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-xs font-medium text-gray-800 outline-none transition focus:border-[#BA0030] focus:bg-white focus:ring-2 focus:ring-[#BA0030]/20 disabled:opacity-50'
]) }}>
    @if (! empty($options))
        @foreach ($options as $val => $lbl)
            <option value="{{ $val }}">{{ $lbl }}</option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
