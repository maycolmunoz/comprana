<x-app-layout>
	<x-slot name="header">
		<h2 class="text-xl font-semibold leading-tight text-gray-800">
			Hacer Pedido
		</h2>
	</x-slot>

	<livewire:orders.order :$cart>

</x-app-layout>
