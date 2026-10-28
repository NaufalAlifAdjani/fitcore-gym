@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-semibold text-[#565A66] mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
