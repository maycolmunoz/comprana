@props(['value'])

<label
    {{ $attributes->merge(['class' => 'block font-normal text-sm text-base-content/70']) }}>
    {{ $value ?? $slot }}
</label>
