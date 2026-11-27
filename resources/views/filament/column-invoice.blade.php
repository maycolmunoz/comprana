@php
    $name = $getState();
@endphp

@if ($name)
    <a
        href="{{ route('orders.invoice', ['name' => $name]) }}"
        target="_blank"
        class="flex transform items-center gap-1 rounded-md px-2 text-center text-sm text-white transition duration-300 ease-in-out hover:scale-110 hover:bg-gray-700"
    >
        {{ $name }}
    </a>
@else
    <span class="text-sm text-gray-400">Sin factura</span>
@endif
