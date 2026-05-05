@props(['status', 'icon', 'class' => ''])

@php
    $badgeClass = match ($status) {
        \App\Enums\OrderStatus::Processing => 'badge-info bg-info/10 text-info border-info/20',
        \App\Enums\OrderStatus::InTransit => 'badge-warning bg-warning/10 text-warning border-warning/20',
        \App\Enums\OrderStatus::Delivered => 'badge-success bg-success/10 text-success border-success/20',
        \App\Enums\OrderStatus::NotDelivered => 'badge-error bg-error/10 text-error border-error/20',
        default => 'badge-ghost',
    };
@endphp

<x-mary-badge :label="$status->getLabel()" :icon="$icon"
    class="font-black uppercase tracking-widest text-[9px] py-3 px-4 rounded-xl border {{ $badgeClass }} {{ $class }}" />
