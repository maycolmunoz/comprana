@props([
    'value' => 0,
    'class' => 'text-2xl font-black tracking-tighter text-red-600',
    'symbolClass' => 'text-red-600 font-normal',
])

<span {{ $attributes->merge(['class' => $class]) }}>
    <span class="{{ $symbolClass }}">$</span>{{ number_format((int) $value, 0, ',', '.') }}
</span>
