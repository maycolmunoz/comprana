<x-app-layout>
	<x-slot name="header">
		<x-base.page-header title="Mis Pedidos" />
	</x-slot>

	<div class="py-12 animate__animated animate__fadeIn">
		<div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-10">
			{{-- Info Card --}}
 			<x-base.info-list title="Seguimiento de Pedidos" :list="[
			    'Procesando: Tu selección está siendo preparada con los más altos estándares.',
			    'En Camino: El pedido ha sido despachado y se encuentra en tránsito seguro.',
			    'Entregado: Has recibido tu pedido satisfactoriamente.',
			    'No Entregado: Intento de entrega fallido. El paquete está disponible en sucursal.',
			]" />

			{{-- Filter Tabs --}}
			@include('orders.partials.filters')

			{{-- Livewire List --}}
			<div class="pt-2">
				<livewire:orders.list />
			</div>
		</div>
	</div>
</x-app-layout>
