<div x-data="{ pay: 0 }" class="animate__animated animate__fadeIn">
	{{-- Navigation & Header --}}
	<div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10 pb-8 border-b border-base-content/5">
		<div class="text-left">
			<a href="{{ route('carts.index') }}"
				class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-base-content/40 hover:text-red-600 transition-colors mb-4 group">
				<x-mary-icon name="o-arrow-left" class="w-3 h-3 group-hover:-translate-x-1 transition-transform" />
				{{ __('Volver a mis carritos') }}
			</a>
			<h3 class="text-3xl font-black uppercase tracking-tighter leading-none">
				{{ __('Productos en') }} <span class="text-red-600">{{ $cart->name }}</span>
			</h3>
			<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-2">
				{{ __('Gestiona tu selección exclusiva de Comprana') }}
			</p>
		</div>
		<div class="hidden md:block">
			<x-mary-badge value="{{ $cart->products->count() }} {{ __('Items') }}"
				class="badge-neutral font-black uppercase tracking-widest text-[10px] py-3 px-4 rounded-xl" />
		</div>
	</div>

	@if ($cart->products->count())
		<div class="flex flex-col lg:flex-row gap-10">
			{{-- Products List --}}
			<div class="lg:w-2/3 space-y-6">
				@foreach ($cart->products as $product)
					<livewire:cart-product :key='$product->id' :$product :cant="$product->pivot->cant" :$cart>
				@endforeach
			</div>

			{{-- Order Summary Sidebar --}}
			<div class="lg:w-1/3">
				<div class="sticky top-24 space-y-6">
					{{-- Summary Card --}}
					<div
						class="bg-base-100 border border-base-content/5 shadow-2xl shadow-red-900/5 rounded-[2.5rem] p-8 overflow-hidden relative">
						{{-- Decorative Accent --}}
						<div class="absolute top-0 left-0 w-full h-1.5 bg-linear-to-r from-red-600 to-red-400"></div>

						<h4 class="text-xl font-black uppercase tracking-tighter mb-8">{{ __('Resumen del Pedido') }}</h4>

						{{-- Details --}}
						<div class="space-y-4 mb-8">
							<div class="flex justify-between items-center text-sm">
								<span class="font-bold uppercase tracking-widest text-base-content/40 text-[10px]">{{ __('Subtotal') }}</span>
								<span class="font-black tabular-nums">$<span x-text="pay.toLocaleString()"></span></span>
							</div>
							<div class="flex justify-between items-center text-sm">
								<span
									class="font-bold uppercase tracking-widest text-base-content/40 text-[10px]">{{ __('Envío Premium') }}</span>
								<span class="text-green-600 font-bold uppercase text-[10px] tracking-widest">{{ __('Gratis') }}</span>
							</div>
						</div>

						{{-- Total --}}
						<div class="pt-6 border-t border-base-content/5 mb-8">
							<div class="flex justify-between items-end">
								<span class="text-sm font-black uppercase tracking-tighter">{{ __('Total Final') }}</span>
								<div class="text-right">
									<span class="text-4xl font-black tracking-tighter text-red-600">$<span
											x-text="pay.toLocaleString()"></span></span>
									<p class="text-[8px] font-bold uppercase tracking-widest text-base-content/30 mt-1">
										{{ __('IVA e Impuestos incluidos') }}</p>
								</div>
							</div>
						</div>

						<x-mary-button link="{{ route('orders.create', ['id' => $cart->id]) }}" label="{{ __('Finalizar Compra') }}"
							icon-right="o-arrow-right"
							class="btn-primary w-full py-4 h-auto font-black text-sm uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl group" />
					</div>

					{{-- Trust Badges --}}
					<div class="bg-base-content/5 rounded-4xl p-6 text-center">
						<p class="text-[8px] font-black uppercase tracking-[0.3em] text-base-content/30 mb-4">
							{{ __('Transacción 100% Protegida') }}</p>
						<div
							class="flex justify-center items-center gap-6 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
							<x-mary-icon name="o-shield-check" class="w-6 h-6" />
							<x-mary-icon name="o-credit-card" class="w-6 h-6" />
							<x-mary-icon name="o-lock-closed" class="w-6 h-6" />
						</div>
					</div>
				</div>
			</div>
		</div>
	@else
		<div class="py-24 text-center animate__animated animate__zoomIn">
			<div class="mt-10">
				<x-mary-button link="/" label="{{ __('Explorar Catálogo Premium') }}"
					class="btn-outline font-black text-xs uppercase tracking-widest rounded-2xl px-12 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all" />
			</div>
		</div>
	@endif
</div>
