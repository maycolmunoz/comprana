<x-app-layout>
	<x-slot name="header">
		<h2 class="text-xl font-semibold leading-tight text-gray-800">
			{{ __('Hacer Pedido') }}
		</h2>
	</x-slot>

	<livewire:order :$cart>

</x-app-layout>
