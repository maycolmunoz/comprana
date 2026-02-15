<div x-data="{ cant: @entangle('cant'), price: {{ $product->price }}, total: 0 }"
	x-init=' $watch("cant", value => total = price * cant)
        total = cant * price
        pay += total;
        '
	class="group bg-base-100 border border-base-content/5 rounded-3xl p-4 flex flex-col sm:flex-row gap-6 transition-all duration-300 hover:shadow-xl hover:shadow-base-content/5 relative overflow-hidden">

	{{-- Glass Shine --}}
	<div class="absolute top-0 left-0 w-full h-px bg-linear-to-r from-transparent via-red-600/10 to-transparent"></div>

	{{-- Product Image --}}
	<div class="w-full sm:w-48 h-48 rounded-2xl overflow-hidden bg-base-content/5 relative shrink-0">
		<img src="/storage/images/{{ $product->images[0] }}" alt="{{ $product->name }}"
			class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
		<div class="absolute top-3 left-3">
			<span
				class="bg-base-100/80 backdrop-blur-md px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/20 shadow-sm transition-colors group-hover:bg-red-600 group-hover:text-white group-hover:border-red-600 font-outfit">
				${{ number_format($product->price, 0) }}
			</span>
		</div>
	</div>

	<div class="flex-1 flex flex-col justify-between py-1">
		<div>
			<div class="flex justify-between items-start gap-4 mb-2">
				<h4 class="text-xl font-black uppercase tracking-tighter truncate leading-none">{{ $product->name }}</h4>
				<div class="text-right shrink-0">
					<span class="text-xs font-black uppercase tracking-widest text-red-600">$<span
							x-text="total.toLocaleString()"></span></span>
				</div>
			</div>

			<div class="flex flex-wrap gap-4 mt-4">
				<div class="flex items-center gap-1.5">
					<x-mary-icon name="o-cube" class="w-3.5 h-3.5 text-base-content/30" />
					<span class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Stock:') }}
						{{ $product->stock }}</span>
				</div>
			</div>
		</div>

		<div class="flex flex-wrap items-center justify-between gap-6 mt-6 pt-6 border-t border-base-content/5">
			{{-- Quantity Controls --}}
			<div class="flex items-center bg-base-content/5 rounded-xl p-1 gap-1">
				<button @click="pay = cant <= 1 ? pay : pay - price; cant = cant <= 1 ? 1 : parseInt(cant) - 1"
					class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-600 hover:text-white transition-all text-base-content/40">
					<x-mary-icon name="o-minus" class="w-3 h-3" />
				</button>
				<span class="w-10 text-center text-xs font-black tabular-nums" x-text="cant"></span>
				<button @click="pay += price; cant = cant >= {{ $product->stock }} ? cant : parseInt(cant) + 1"
					class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-600 hover:text-white transition-all text-base-content/40">
					<x-mary-icon name="o-plus" class="w-3 h-3" />
				</button>
			</div>

			{{-- Actions --}}
			<div class="flex items-center gap-2">
				<x-mary-button wire:click='edit()' icon="o-arrow-path" label="{{ __('Actualizar') }}"
					class="btn-ghost btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl bg-base-content/5 hover:bg-red-600/10 hover:text-red-600" />
				<x-mary-button
					@click="if(confirm('¿Seguro desea eliminar {{ $product->name }} de su carrito?')){ $wire.delete(); pay -= total; }"
					icon="o-trash"
					class="btn-error btn-outline btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl border-red-600/20 hover:bg-red-600/10 text-red-600" />
			</div>
		</div>
	</div>
</div>
