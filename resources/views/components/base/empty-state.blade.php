@props([
    'icon' => 'o-archive-box-x-mark',
    'title',
    'description',
    'link' => null,
    'linkLabel' => 'Ir a la tienda',
])

<div class="py-20 text-center">
    <div class="bg-base-content/5 mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full">
        <x-mary-icon :name="$icon" class="text-base-content/20 h-10 w-10" />
    </div>
    <h3 class="mb-2 text-xl font-black tracking-tighter uppercase">{{ $title }}</h3>
    <p class="text-base-content/60 mx-auto mb-8 max-w-xs text-[10px] font-bold tracking-widest uppercase">
        {{ $description }}
    </p>
    @if ($link)
        <x-mary-button
            :link="$link"
            :label="$linkLabel"
            class="btn-primary rounded-2xl px-10 text-xs font-black tracking-widest uppercase"
        />
    @endif
</div>
