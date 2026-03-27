<div class="space-y-6 animate__animated animate__fadeIn">
	@forelse ($this->orders as $order)
		@php
			$badgeConfig = match ($order->status) {
			    \App\Enums\OrderStatus::Processing => [
			        'class' => 'badge-info bg-blue-500/10 text-blue-600 border-blue-500/20',
			        'icon' => 'o-arrow-path',
			    ],
			    \App\Enums\OrderStatus::InTransit => [
			        'class' => 'badge-warning bg-amber-500/10 text-amber-600 border-amber-500/20',
			        'icon' => 'o-truck',
			    ],
			    \App\Enums\OrderStatus::Delivered => [
			        'class' => 'badge-success bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
			        'icon' => 'o-check-circle',
			    ],
			    \App\Enums\OrderStatus::NotDelivered => [
			        'class' => 'badge-error bg-rose-500/10 text-rose-600 border-rose-500/20',
			        'icon' => 'o-x-circle',
			    ],
			};
		@endphp

		<div
			class="group relative bg-base-100 border border-base-content/5 rounded-3xl p-6 lg:p-8 transition-all duration-500 hover:shadow-2xl hover:shadow-red-900/5 hover:-translate-y-1 overflow-hidden">
			{{-- Decorative Side Accent --}}
			<div
				class="absolute top-0 left-0 w-1.5 h-full bg-linear-to-b from-red-600 to-red-400 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
			</div>

			<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
				{{-- Order Meta & Status --}}
				<div class="flex-1 space-y-4">
					<div class="flex flex-wrap items-center gap-4">
						<span
							class="text-xs font-black uppercase tracking-widest text-red-600">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
						<x-mary-badge :label="$order->status->getLabel()" :icon="$badgeConfig['icon']"
							class="font-black uppercase tracking-widest text-[9px] py-3 px-4 rounded-xl border {{ $badgeConfig['class'] }}" />
						@if ($order->payment_id)
							<x-mary-badge label="Pagado" icon="o-credit-card"
								class="badge-neutral bg-base-content/5 text-base-content/60 border-none font-bold uppercase tracking-widest text-[9px] py-3 px-4 rounded-xl" />
						@endif
					</div>

					<div>
						<h4 class="text-2xl font-black uppercase tracking-tighter">{{ __('Pedido Realizado') }}</h4>
						<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">
							{{ $order->created_at->translatedFormat('d M, Y • g:i A') }}
						</p>
					</div>
				</div>

				{{-- Order Details Grid --}}
				<div
					class="flex-[1.5] grid grid-cols-1 md:grid-cols-2 gap-6 py-6 lg:py-0 border-y lg:border-y-0 lg:border-x border-base-content/5 lg:px-10">
					<div class="space-y-1">
						<span class="text-[9px] font-black uppercase tracking-widest text-base-content/30">{{ __('Entrega en') }}</span>
						<p class="text-xs font-bold leading-relaxed line-clamp-2">{{ $order->address }}</p>
						<p class="text-[10px] font-medium text-base-content/40">{{ $order->phone }}</p>
					</div>
					<div class="space-y-1">
						<span
							class="text-[9px] font-black uppercase tracking-widest text-base-content/30">{{ __('Total del Pedido') }}</span>
						<p class="text-2xl font-black tracking-tighter text-red-600">${{ number_format($order->total, 0) }}</p>
					</div>
				</div>

				{{-- Actions --}}
				<div class="flex flex-wrap items-center gap-3 shrink-0">
					@if ($order->invoice)
						<x-mary-button link="{{ route('orders.invoice', ['name' => $order->invoice]) }}" external
							label="{{ __('Factura PDF') }}" icon="o-document-text"
							class="btn-ghost btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl bg-base-content/5 hover:bg-red-600/10 hover:text-red-600" />
					@endif

					<x-mary-button label="{{ __('Ver Detalles') }}" icon-right="o-chevron-right"
						class="btn-outline btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl px-6" />
				</div>
			</div>

			{{-- Background Decal --}}
			<div class="absolute -right-4 -bottom-4 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity duration-500">
				<x-mary-icon name="o-shopping-bag" class="w-32 h-32 rotate-12" />
			</div>
		</div>
	@empty
		<div class="py-20 text-center animate__animated animate__zoomIn">
			<div class="w-24 h-24 bg-base-content/5 rounded-full flex items-center justify-center mx-auto mb-6">
				<x-mary-icon name="o-archive-box-x-mark" class="w-10 h-10 text-base-content/20" />
			</div>
			<h4 class="text-xl font-black uppercase tracking-tighter mb-2">{{ __('No tienes pedidos aún') }}</h4>
			<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/30 mb-8 max-w-xs mx-auto">
				{{ __('Explora nuestra tienda premium y comienza tu primera experiencia Comprana.') }}
			</p>
			<x-mary-button link="/tienda" label="{{ __('Ir a la Tienda') }}"
				class="btn-primary rounded-2xl px-10 font-black text-xs uppercase tracking-widest shadow-xl shadow-red-600/20" />
		</div>
	@endforelse

	{{-- Pagination Styled --}}
	<div class="pt-10">
		{{ $this->orders->links() }}
	</div>
</div>
