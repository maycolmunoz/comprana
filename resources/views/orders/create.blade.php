<x-app-layout>
	<x-slot name="header">
		<x-base.page-header title="Hacer Pedido" />
	</x-slot>

	<livewire:orders.order :$cart>

</x-app-layout>
