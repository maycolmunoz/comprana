<x-app-layout>
	<x-slot name="header">
		<div class="flex items-center gap-3">
			<div class="w-1.5 h-8 bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.4)]"></div>
			<h2 class="text-3xl font-black tracking-tighter uppercase text-base-content">
				{{ __('Mis Pedidos') }}
			</h2>
		</div>
	</x-slot>

	<div class="py-12 animate__animated animate__fadeIn">
		<div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-10">
			{{-- Info Card --}}
			<x-commons.info-list title="Seguimiento de Pedidos" :list="[
			    'Procesando: Tu selección premium está siendo preparada con los más altos estándares.',
			    'En Camino: El pedido ha sido despachado y se encuentra en tránsito seguro.',
			    'Entregado: Has recibido tu pedido satisfactoriamente.',
			    'No Entregado: Intento de entrega fallido. El paquete está disponible en sucursal.',
			]" />

			{{-- Filter Tabs --}}
			@include('orders.partials.filters')

			{{-- Livewire List --}}
			<div class="pt-2">
				<livewire:order-list />
			</div>
		</div>
	</div>
</x-app-layout>
