@props([
    'value' => 0,
    'class' => 'text-2xl font-black tracking-tighter text-red-600',
])

<span {{ $attributes->merge(['class' => $class]) }}>
    <span class="text-red-600 font-normal">$</span>{{ number_format((int) $value, 0, ',', '.') }}
</span>
