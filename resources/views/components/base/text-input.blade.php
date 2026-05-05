@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'bg-base-content/5 border-base-content/20 focus:border-primary focus:ring-primary/20 rounded-2xl']) !!}>
