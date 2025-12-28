@props([
    'type' => 'info',
    'icon' => true,
    'iconName' => null,
    'iconClass' => null,
])

@php

    $iconName ??= match ($type) {
        'info' => 'information-circle',
        'success' => 'check-circle',
        'warning' => 'exclamation-triangle',
        'error' => 'exclamation-circle',
        default => 'information-circle',
    };

    $iconColor = match ($type) {
        'info' => 'text-blue-500',
        'success' => 'text-green-500',
        'warning' => 'text-orange-500',
        'error' => 'text-red-500',
        default => 'text-blue-500',
    };

    $containerClass = [
        'border rounded-md px-4 py-2 text-white',
        '[:where(&)]:dark:bg-neutral-900  [:where(&)]:bg-white [:where(&)]:border-black/10 [:where(&)]:dark:border-white/10',
        'dark:bg-[color-mix(in_oklab,_var(--color-blue-600)_10%,_var(--color-neutral-900)_90%)] bg-[color-mix(in_oklab,_var(--color-blue-500)_20%,_var(--color-white)_80%)] border-gray-500/55' =>
            $type === 'info',
        'dark:bg-[color-mix(in_oklab,_var(--color-green-600)_10%,_var(--color-neutral-900)_90%)] bg-[color-mix(in_oklab,_var(--color-green-500)_20%,_var(--color-white)_80%)] border-green-500/55' =>
            $type === 'success',
        'dark:bg-[color-mix(in_oklab,_var(--color-yellow-600)_10%,_var(--color-neutral-900)_90%)] bg-[color-mix(in_oklab,_var(--color-yellow-500)_25%,_var(--color-white)_75%)] border-yellow-500/55' =>
            $type === 'warning',
        'dark:bg-[color-mix(in_oklab,_var(--color-red-600)_10%,_var(--color-neutral-900)_90%)] bg-[color-mix(in_oklab,_var(--color-red-500)_25%,_var(--color-white)_75%)] border-red-500/55' =>
            $type === 'error',
        '[&:has([data-slot=alert-heading]+[data-slot=alert-content])>[data-slot=alert-heading]]:mb-2',
        '[&:has([data-slot=alert-content]+[data-slot=alert-actions])>[data-slot=alert-content]]:mb-2',
    ];
@endphp

<div {{ $attributes->class(Arr::toCssClasses($containerClass)) }}>
    <div class="flex items-center gap-x-1" data-slot="alert-heading">
        <div class="shrink-0 size-7 grid place-items-center">
            <x-ui.icon name="{{ $iconName }}" class="{{ Arr::toCssClasses([$iconColor, $iconClass]) }}" />
        </div>

        <div class="flex-1 text-start">
            {{ $heading ?? $slot }}
        </div>
    </div>

    @if (isset($content) && !$content->isEmpty())
        <div class="text-start" data-slot='alert-content'>
            {{ $content }}
        </div>
    @endif

    @if (isset($actions) && !$actions->isEmpty())
        <div data-slot='alert-actions'>
            {{ $actions }}
        </div>
    @endif
</div>
