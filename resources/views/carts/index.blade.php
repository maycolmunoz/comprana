<x-app-layout>
	<x-slot name="header">
		<div class="flex items-center gap-3">
			<div class="w-1.5 h-8 bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.4)]"></div>
			<h2 class="text-3xl font-black tracking-tighter uppercase text-base-content">
				{{ __('Mis Carritos') }}
			</h2>
		</div>
	</x-slot>

	<div class="py-12 animate__animated animate__fadeIn">
		<div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
			{{-- Info List --}}
			<x-commons.info-list title="Gestión de Carritos" :list="[
			    'Puedes gestionar hasta 8 carritos personalizados simultáneamente.',
			    'El carrito activo es el destino predeterminado de tus nuevas selecciones.',
			    'Organiza, renombra o vacía tus carritos para una experiencia de compra a medida.',
			]" />

			{{-- Cart List Component --}}
			<div class="pt-4">
				<livewire:cart-list />
			</div>
		</div>
	</div>
</x-app-layout>
