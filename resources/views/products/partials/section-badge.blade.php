<x-ui.badge pill color="orange" size="sm">
    {{request('section') != '' ? request('section') : $product->section->name }}
</x-ui.badge>