@props(['class' => ''])

<div class="bg-base-100 border border-base-content/5 shadow-xl shadow-red-900/5 rounded-[2.5rem] overflow-hidden {{ $class }}">
    <div class="p-8 sm:p-12">
        {{ $slot }}
    </div>
</div>
