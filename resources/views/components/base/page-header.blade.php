@props(['title'])

<div class="flex items-center gap-3">
    <div class="w-1.5 h-8 bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.4)]"></div>
    <h2 class="text-3xl font-black tracking-tighter uppercase text-base-content">
        {{ $title }}
    </h2>
</div>
