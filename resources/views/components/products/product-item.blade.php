@php
	$inStock = $product->inStock;
	$sectionName = request('section') != '' ? request('section') : $product->section->name ?? 'General';
@endphp

<x-mary-card
	class="h-full bg-base-100 rounded-4xl border border-base-content/5 shadow-xl transition-all duration-500 hover:shadow-2xl hover:shadow-red-600/5 hover:-translate-y-2 group overflow-hidden relative">
	<x-slot:figure class="relative overflow-hidden aspect-square">
		{{-- Product Image with Hover Zoom --}}
		<img src="{{ asset("/storage/{$product->images[0]}") }}"
			class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110"
			alt="{{ $product->name }}" />

		{{-- Section Badge overlay --}}
		<div class="absolute top-4 left-4 z-10 animate__animated animate__fadeIn">
			<x-mary-badge
				class="bg-white/70 backdrop-blur-md border border-white/20 text-red-600 font-bold text-[10px] uppercase tracking-widest px-3 py-1.5 shadow-sm rounded-full"
				:value="$sectionName" />
		</div>

		{{-- Out of Stock Overlay --}}
		@if (!$inStock)
			<div
				class="absolute inset-0 bg-white/60 backdrop-blur-[2px] flex items-center justify-center rotate-[-15deg] z-20 pointer-events-none">
				<span
					class="text-2xl font-black text-red-600 border-4 border-red-600 px-6 py-2 tracking-tighter uppercase opacity-80">
					Agotado
				</span>
			</div>
		@endif
	</x-slot:figure>

	{{-- Content --}}
	<div class="space-y-3 px-1">
		<h3
			class="font-black text-lg lg:text-xl leading-tight tracking-tight uppercase group-hover:text-red-600 transition-colors">
			{{ $product->name }}
		</h3>

		<div class="flex items-center justify-between gap-2">
			<div class="flex flex-col">
				<span class="text-[10px] font-bold uppercase opacity-30 tracking-widest">Precio</span>
				<span class="text-2xl font-black text-base-content italic tracking-tighter">
					<span class="text-red-600 font-normal mr-0.5">$</span>{{ number_format($product->price, 0, ',', '.') }}
				</span>
			</div>

			<div class="flex items-center gap-1.5">
				@if ($inStock)
					@guest
						<x-mary-button :link="route('login')" icon="o-shopping-cart"
							class="btn-primary btn-circle shadow-lg shadow-primary/20 hover:scale-110" />
					@else
						<x-mary-button icon="o-shopping-cart"
							class="btn-primary btn-circle shadow-lg shadow-primary/20 hover:scale-110 active:scale-95 transition-all"
							x-data="{
	    realizarAccion(id) {
	        $dispatch('slider');
	        $dispatch('details', { 'id': id })
	        $dispatch('reset-count')
	    }
	}" @click="realizarAccion('{{ $product->id }}')" />
					@endguest
				@else
					<div class="p-3 bg-base-content/5 rounded-full opacity-30">
						<x-mary-icon name="o-no-symbol" class="w-6 h-6" />
					</div>
				@endif
			</div>
		</div>
	</div>
</x-mary-card>
