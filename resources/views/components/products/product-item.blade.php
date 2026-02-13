<x-mary-card :title="$product->name">
	<x-slot:figure>
		<img src="{{ asset("/storage/{$product->images[0]}") }}" />
	</x-slot:figure>

	<x-mary-badge class="badge-primary badge-soft" :value="request('section') != '' ? request('section') : $product->section->name" />
	<p>{{ $product->price }}</p>
	@if (!$product->inStock)
		<p class=" text-red-500">AGOTADO</p>
	@endif

	<x-slot:menu>
		@if ($product->inStock)
			@guest
				<x-mary-button :link="route('login')" icon="s-shopping-cart" class="btn-primary btn-circle" />
			@else
				<x-mary-button icon="s-shopping-cart" class="btn-primary btn-circle" x-data="{
	    realizarAccion(id) {
	        $dispatch('slider');
	        $dispatch('details', { 'id': id })
	        $dispatch('reset-count')
	    }
	}"
					@click="realizarAccion('{{ $product->id }}')" />
			@endguest
		@endif
	</x-slot:menu>
</x-mary-card>
