@props(['icon' => 'o-archive-box-x-mark', 'title', 'description', 'link' => null, 'linkLabel' => 'Ir a la Tienda'])

<div class="py-20 text-center animate__animated animate__zoomIn">
    <div class="w-24 h-24 bg-base-content/5 rounded-full flex items-center justify-center mx-auto mb-6">
        <x-mary-icon :name="$icon" class="w-10 h-10 text-base-content/20" />
    </div>
    <h4 class="text-xl font-black uppercase tracking-tighter mb-2">{{ $title }}</h4>
    <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/30 mb-8 max-w-xs mx-auto">
        {{ $description }}
    </p>
    @if ($link)
        <x-mary-button :link="$link" :label="$linkLabel"
            class="btn-primary rounded-2xl px-10 font-black text-xs uppercase tracking-widest" />
    @endif
</div>
