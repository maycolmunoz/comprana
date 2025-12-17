<div x-data="{ cant: @entangle('cant'), price: {{ $product->price }}, total: 0 }"
	x-init=' $watch("cant", value => total = price * cant)
        total = cant * price
        pay += total;
        '
	class="justify-between p-4 mb-4 bg-white rounded-lg shadow-md sm:flex sm:justify-start">
	<img src="/storage/images/{{ $product->images[0] }}" alt="product-image" class="w-full rounded-lg sm:w-40" />
	<div class="sm:ml-4 sm:flex sm:w-full sm:justify-between">
		<div class="mt-5 sm:mt-0">
			<h2 class="text-lg font-bold text-gray-900">{{ $product->name }}</h2>
			<p class="mt-1 text-xs text-gray-700">Precio: {{ $product->price }}</p>
			<p class="mt-1 text-xs text-gray-700">Stock: {{ $product->stock }}</p>
		</div>
		<div class="flex justify-between mt-4 sm:space-y-6 sm:mt-0 sm:block sm:space-x-6">
			<div class="flex items-center border-gray-100">
				<span @click=" pay = cant <= 1 ? pay : pay - price
            cant = cant <= 1 ? 1 : parseInt(cant) - 1"
					class="cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-blue-500 hover:text-blue-50">
					-
				</span>
				<span class="flex items-center justify-center w-8 h-8 text-xs bg-white border outline-none" x-text="cant"></span>
				<span @click=" pay += price
            cant = cant >= {{ $product->stock }} ? cant :  parseInt(cant) +1"
					class="px-3 py-1 duration-100 bg-gray-100 rounded-r cursor-pointer hover:bg-blue-500 hover:text-blue-50">
					+ </span>
			</div>
			<p class="text-sm" x-text='total'></p>

			<div class="flex flex-col items-center ">
				<button class="w-full py-1 font-medium bg-blue-500 rounded-md text-blue-50 hover:bg-blue-600"
					wire:click='edit()'>actualizar </button>
				<button
					@click="
          if(confirm('¿Seguro desea eliminar {{ $product->name }} de su carrito?')){
            $wire.delete()
            pay -= total
          }"
					class="w-full py-1 font-medium duration-150 bg-red-500 rounded-md cursor-pointer text-blue-50 hover:bg-red-600 ">
					Eliminar
				</button>
			</div>
		</div>
	</div>
</div>
