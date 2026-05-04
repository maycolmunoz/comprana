@props(['status', 'icon', 'class' => ''])

@php
    $badgeClass = match ($status) {
        \App\Enums\OrderStatus::Processing => 'badge-info bg-blue-500/10 text-blue-600 border-blue-500/20',
        \App\Enums\OrderStatus::InTransit => 'badge-warning bg-amber-500/10 text-amber-600 border-amber-500/20',
        \App\Enums\OrderStatus::Delivered => 'badge-success bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
        \App\Enums\OrderStatus::NotDelivered => 'badge-error bg-rose-500/10 text-rose-600 border-rose-500/20',
        default => 'badge-ghost',
    };
@endphp

<x-mary-badge :label="$status->getLabel()" :icon="$icon"
    class="font-black uppercase tracking-widest text-[9px] py-3 px-4 rounded-xl border {{ $badgeClass }} {{ $class }}" />
