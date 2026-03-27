<x-app-layout>
	<x-slot name="header">
		<div class="flex items-center gap-3">
			<div class="w-1.5 h-8 bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.4)]"></div>
			<h2 class="text-3xl font-black tracking-tighter uppercase text-base-content">
				{{ __('Detalles del Carrito') }}
			</h2>
		</div>
	</x-slot>

	<div class="py-12 animate__animated animate__fadeIn">
		<div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
			{{-- Info List --}}
			<x-commons.info-list title="Gestión de Productos" :list="[
			    'Ajusta las cantidades de tus productos y utiliza el botón de actualizar para confirmar.',
			    'Si agregas un producto existente, la cantidad se consolidará automáticamente.',
			    'Revisa tu selección premium antes de proceder al pago seguro.',
			]" />

			{{-- Cart Products List Component --}}
			<div>
				<livewire:cart-products-list :$cart lazy />
			</div>
		</div>
	</div>
</x-app-layout>
