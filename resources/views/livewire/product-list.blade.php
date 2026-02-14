@php
	$sectionNull = $section === '' ? true : false;
	$searchNull = $search === '' ? true : false;
@endphp

<div class="container px-6 mx-auto animate__animated animate__fadeIn">
	{{-- --- Header & Active Filters --- --}}
	<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
		<div class="space-y-2">
			<div class="flex items-center gap-3">
				<span class="text-[10px] uppercase font-bold tracking-[0.3em] text-red-600 opacity-60">Explorando</span>
				<div class="h-px grow bg-linear-to-r from-red-600/20 to-transparent"></div>
			</div>

			<h3 class="text-3xl lg:text-4xl font-black tracking-tighter uppercase text-base-content flex items-center gap-3">
				@if ($sectionNull)
					<x-mary-icon name="o-squares-2x2" class="w-8 h-8 text-red-600" />
					Todos los Productos
				@else
					<x-mary-icon name="o-tag" class="w-8 h-8 text-red-600" />
					{{ $section }}
				@endif
			</h3>

			@if (!$searchNull)
				<div class="inline-flex items-center gap-2 px-3 py-1 bg-base-content/5 border border-base-content/5 rounded-full">
					<x-mary-icon name="o-magnifying-glass" class="w-3 h-3 opacity-40" />
					<span class="text-xs font-bold text-base-content/60">Búsqueda: </span>
					<span class="text-xs font-black uppercase text-red-600">"{{ $search }}"</span>
				</div>
			@endif
		</div>

		<div class="flex flex-wrap items-center gap-4">
			{{-- --- Price Ordering --- --}}
			@if (!$searchNull || !$sectionNull)
				<div class="flex items-center bg-base-100 p-1 rounded-2xl border border-base-content/5 shadow-sm">
					<span class="text-[10px] font-black uppercase tracking-widest opacity-30 px-4">Precio</span>

					@php
						$orderAsc = $order === 'asc';
						$orderDesc = $order === 'desc';
					@endphp

					<div class="flex gap-1">
						<x-mary-button wire:click="$set('order', 'asc')" :disabled="$orderAsc" label="Menor" icon="o-bars-arrow-up"
							class="btn-sm rounded-xl font-bold {{ $orderAsc ? 'btn-primary' : 'btn-ghost opacity-50' }}" />

						<x-mary-button wire:click="$set('order', 'desc')" :disabled="$orderDesc" label="Mayor" icon="o-bars-arrow-down"
							class="btn-sm rounded-xl font-bold {{ $orderDesc ? 'btn-primary' : 'btn-ghost opacity-50' }}" />
					</div>
				</div>

				{{-- --- Clean Filters --- --}}
				<x-mary-button link="{{ route('products.index') }}" icon="o-x-mark" label="Limpiar"
					class="btn-ghost btn-sm font-bold text-red-600/60 hover:text-red-600 rounded-xl" />
			@endif
		</div>
	</div>

	{{-- ----- Products Grid ----- --}}
	@if ($this->products->count())
		<div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 group/list">
			@foreach ($this->products as $product)
				<x-products.product-item :$product wire:key='{{ $product->id }}' />
			@endforeach
		</div>
	@else
		<div class="py-20 flex flex-col items-center text-center animate__animated animate__pulse">
			<div class="w-24 h-24 bg-red-600/5 rounded-full flex items-center justify-center mb-6">
				<x-mary-icon name="o-magnifying-glass" class="w-10 h-10 text-red-600/20" />
			</div>
			<h4 class="text-2xl font-black tracking-tighter uppercase mb-2">Sin coincidencias</h4>
			<p class="text-base-content/40 max-w-xs font-medium">No pudimos encontrar lo que buscas. Intenta con otros términos o
				secciones.</p>
			<x-mary-button link="{{ route('products.index') }}" label="Ver todos los productos"
				class="mt-8 btn-primary btn-outline rounded-full font-bold px-8" />
		</div>
	@endif

	{{-- Pagination --}}
	<div class="mt-20 py-10 border-t border-base-content/5 flex justify-center">
		{{ $this->products->links() }}
	</div>
</div>
