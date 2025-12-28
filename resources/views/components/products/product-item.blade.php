@php
	$in_stock = $product->stock > 0 ? true : false;
@endphp

<div
	class="{{ !$in_stock ? 'shadow-md shadow-red-500' : '' }} w-full max-w-sm mx-auto overflow-hidden rounded-md shadow-md lg:my-1">
	<div class="flex items-end justify-end w-full h-56 bg-cover"
		style="background-image: url('{{ asset("/storage/{$product->images[0]}") }}')">
		@if ($in_stock)
			@guest
				<x-ui.button href="{{ route('login') }}" color="blue" icon="shopping-cart" class="mx-5 -mb-4" />
			@endguest
			@auth
				<x-ui.button x-data="{
	    realizarAccion(id) {
	        $dispatch('slider');
	        $dispatch('details', { 'id': id })
	        $dispatch('reset-count')
	    }
	}" @click="realizarAccion('{{ $product->id }}')" color="blue" icon="shopping-cart"
					class="mx-5 -mb-4" />
			@endauth
		@endif

	</div>
	<div class="px-5 py-3">
		@include('products.partials.section-badge')
		<h3 class="text-gray-700 uppercase"> {{ $product->name }}<br /></h3>
		<span class="mt-2 text-gray-500">{{ $product->price }}</span>
		@if (!$in_stock)
			<p class="mt-2 text-red-500">AGOTADO</p>
		@endif
	</div>
</div>
