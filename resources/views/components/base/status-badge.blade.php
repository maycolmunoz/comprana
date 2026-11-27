@props(['status', 'icon', 'class' => ''])

<x-mary-badge
    :label="$status->getLabel()"
    :icon="$icon"
    class="font-black uppercase tracking-widest text-[9px] py-3 px-4 rounded-xl border {{ $status->getBadgeClasses() }} {{ $class }}"
/>
