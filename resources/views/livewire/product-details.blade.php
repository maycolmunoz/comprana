<div class="animate__animated animate__fadeIn">
	<x-commons.loading wire:loading class="items-center justify-center w-full min-h-[400px]" />

	@if ($product)
		<div wire:loading.remove class="flex flex-col lg:flex-row gap-10">
			{{-- Image Gallery Section --}}
			<section x-data="{ currentImage: '' }" x-init="currentImage = '{{ $product->images[0] ?? '' }}'" class="w-full lg:w-1/2 space-y-4">
				{{-- Main Image Display --}}
				<div
					class="bg-base-100 rounded-3xl overflow-hidden border border-base-content/5 shadow-2xl shadow-red-900/5 aspect-square relative group">
					<img :src="'/storage/' + currentImage"
						class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
						alt="{{ $product->name }}">

					{{-- Decorative Overlays --}}
					<div class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent pointer-events-none"></div>

					@if ($product->stock <= 0)
						<div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center rotate-[-15deg] z-10">
							<span class="text-3xl font-black text-red-600 border-4 border-red-600 px-8 py-3 tracking-tighter uppercase">
								Agotado
							</span>
						</div>
					@endif
				</div>

				{{-- Thumbnails --}}
				@if (count($product->images) > 1)
					<div class="flex flex-wrap gap-3">
						@foreach ($product->images as $image)
							<button type="button" @click="currentImage = '{{ $image }}'"
								class="w-20 h-20 rounded-2xl overflow-hidden border-2 transition-all duration-300 relative group"
								:class="currentImage === '{{ $image }}' ? 'border-red-600 shadow-lg shadow-red-600/20 scale-105' :
								    'border-transparent opacity-60 hover:opacity-100'">
								<img src="/storage/{{ $image }}" class="w-full h-full object-cover" alt="thumbnail">
								<div class="absolute inset-0 bg-red-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
							</button>
						@endforeach
					</div>
				@endif
			</section>

			{{-- Product Information Section --}}
			<div class="flex-1 flex flex-col justify-between py-2">
				<div class="space-y-6">
					{{-- Header --}}
					<div class="space-y-2">
						<x-mary-badge value="{{ $product->section->name ?? 'General' }}"
							class="bg-red-600/10 text-red-600 font-bold text-[10px] uppercase tracking-[0.2em] px-3 py-1.5 border-none rounded-lg" />
						<h3 class="text-4xl font-black tracking-tighter uppercase leading-none text-base-content">
							{{ $product->name }}
						</h3>
					</div>

					{{-- Price & Stock --}}
					<div class="flex items-end gap-6">
						<div class="flex flex-col">
							<span class="text-[10px] font-black uppercase tracking-widest text-base-content/30">Precio Premium</span>
							<span class="text-4xl font-black text-base-content italic tracking-tighter">
								<span class="text-red-600 font-normal mr-1">$</span>{{ number_format($product->price, 0, ',', '.') }}
							</span>
						</div>

						<div class="flex flex-col pb-1">
							<span class="text-[10px] font-black uppercase tracking-widest text-base-content/30">Disponibilidad</span>
							<div class="flex items-center gap-1.5 pt-1">
								<div
									class="w-2 h-2 rounded-full {{ $product->stock > 0 ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-red-500' }}">
								</div>
								<span
									class="text-xs font-bold uppercase tracking-widest {{ $product->stock > 0 ? 'text-emerald-600' : 'text-red-600' }}">
									{{ $product->stock > 0 ? $product->stock . ' Unidades' : 'Sin Stock' }}
								</span>
							</div>
						</div>
					</div>

					{{-- Description --}}
					<div class="space-y-4 pt-4">
						<h4 class="text-xs font-black uppercase tracking-widest text-base-content/40">Descripción</h4>
						<p class="text-base text-base-content/70 leading-relaxed font-medium">
							{{ $product->description }}
						</p>
					</div>
				</div>

				{{-- Checkout Actions Case --}}
				<div class="mt-10 pt-10 border-t border-base-content/5 space-y-8">
					@if ($product->stock > 0)
						<form wire:submit.prevent='order({{ $product->id }})' x-data="{ count: @entangle('count'), price: @entangle('price'), available: true }" x-init=" $watch('count', value => { available = value < 1 || value > {{ $product->stock }} ? false : true })"
							@reset-count.window="count = 1" class="space-y-6">

							{{-- Quantity Selector --}}
							<div class="flex flex-col gap-4">
								<label class="text-[10px] font-black uppercase tracking-widest text-base-content/40" for="count">
									Seleccionar Cantidad
									<span class="text-red-600 ml-2 animate-pulse" x-show="!available">Excede el stock disponible</span>
								</label>

								<div
									class="flex items-center bg-base-content/5 rounded-2xl p-1.5 gap-2 w-fit border border-base-content/5 shadow-inner">
									<button type="button" @click="count = count <= 1 ? 1 : parseInt(count) - 1"
										class="w-12 h-12 flex items-center justify-center rounded-xl bg-base-100 text-base-content/60 hover:bg-red-600 hover:text-white hover:shadow-lg hover:shadow-red-600/20 transition-all duration-300">
										<x-mary-icon name="o-minus" class="w-4 h-4" />
									</button>

									<span class="w-16 text-center text-xl font-black tabular-nums text-base-content" x-text="count"></span>

									<button type="button" @click="count = count >= {{ $product->stock }} ? count : parseInt(count) + 1"
										class="w-12 h-12 flex items-center justify-center rounded-xl bg-base-100 text-base-content/60 hover:bg-red-600 hover:text-white hover:shadow-lg hover:shadow-red-600/20 transition-all duration-300">
										<x-mary-icon name="o-plus" class="w-4 h-4" />
									</button>
								</div>
							</div>

							{{-- Total Display --}}
							<div class="flex items-center justify-between p-4 bg-base-content/5 rounded-[1.5rem] border-l-4 border-red-600">
								<span class="text-[10px] font-black uppercase tracking-widest text-base-content/40">Total</span>
								<span class="text-2xl font-black tracking-tighter text-base-content">
									<span class="text-red-600 font-normal">$</span><span x-text="(price * count).toLocaleString()"></span>
								</span>
							</div>

							{{-- Submit Button --}}
							<x-mary-button type="submit" label="Agregar al Carrito" icon="o-shopping-bag"
								class="btn-primary w-full py-4 h-auto font-black text-sm uppercase tracking-widest shadow-2xl shadow-red-600/20 rounded-2xl group active:scale-[0.98] transition-all"
								::disabled="!available" @click.prevent="$dispatch('slider-close')">
								<x-slot:append>
									<x-mary-icon name="o-chevron-right"
										class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" />
								</x-slot:append>
							</x-mary-button>
						</form>
					@else
						<div class="bg-red-600/5 rounded-2xl p-6 text-center border border-red-600/10">
							<x-mary-icon name="o-face-frown" class="w-8 h-8 text-red-600 mx-auto mb-3" />
							<p class="text-sm font-bold text-red-600 uppercase tracking-widest">
								Este producto no se encuentra disponible actualmente
							</p>
						</div>
					@endif
				</div>
			</div>
		</div>
	@endif
</div>
