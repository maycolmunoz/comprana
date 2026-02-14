<section x-data="{ id: @entangle('cart_id'), name: '', show: false }" @close-edit="show=false"
	class="flex flex-wrap items-center justify-center gap-4 px-4 py-5 justify">


	{{-- <x-mary-modal wire:model="myModal1" title="Hey" class="backdrop-blur">
		Press `ESC`, click outside or click `CANCEL` to close.

		<x-slot:actions>
			<x-mary-button label="Cancel" @click="$wire.myModal1 = false" />
		</x-slot:actions>
	</x-mary-modal> --}}


	{{-- <form x-cloak x-show="show" wire:submit.prevent='edit_cart()'
		class="fixed z-50 p-5 mt-5 rounded-md md:w-1/3 top-1/2 bg-slate-500">
		<div>
			<label for="user name" class="block text-sm text-gray-700 capitalize dark:text-gray-200">
				Editar Nombre | <span x-text="name"></span> |
			</label>
			<input placeholder="Nombre Nuevo" type="text" wire:model='name'
				class="block w-full px-3 py-2 mt-2 text-gray-600 placeholder-gray-400 bg-white border border-gray-200 rounded-md focus:border-indigo-400 focus:outline-hidden focus:ring-3 focus:ring-indigo-300 focus:ring-opacity-40">
			<div>
				<x-input-error :messages="$errors->get('name')" class="mt-2" />
			</div>
		</div>

		<div class="flex justify-end gap-1 mt-4">
			<button type="submit"
				class="px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-500 rounded-md hover:bg-indigo-600 focus:outline-hidden focus:bg-indigo-500 focus:ring-3 focus:ring-indigo-300 focus:ring-opacity-50">
				Actualizar
			</button>
			<button type="button" @click="show = false"
				class="px-3 py-2 text-sm tracking-wide text-white capitalize transition-colors duration-200 transform bg-red-500 rounded-md hover:bg-red-600 focus:outline-hidden focus:bg-red-500 focus:ring-3 focus:ring-indigo-300 focus:ring-opacity-50">
				Cancelar
			</button>
		</div>
	</form> --}}

	{{-- -- Card---- --}}
	@foreach ($carts as $cart)
		<div class="flex flex-col items-center justify-center ">
			<x-mary-card :title="$cart->name"
				class="{{ $cart->active ? 'shadow-emerald-500  border-emerald-700' : 'border-gray-400' }} ">
				<x-slot:figure>
					<img src="{{ asset('srcs/cart.webp') }}" />
				</x-slot:figure>
				<x-slot:menu>
					<div class="text-gray-700">
						<input type="checkbox" {{ $cart->active === 0 ? '' : 'checked disabled' }} wire:loading.attr="disabled"
							class="opacity-0 sr-only peer" id="{{ $cart->name }}" wire:click="active_cart('{{ $cart->id }}')" />
						<label for="{{ $cart->name }}"
							class="relative flex h-6 w-11 cursor-pointer items-center rounded-full bg-gray-400 px-0.5 outline-gray-400 transition-colors before:h-5 before:w-5 before:rounded-full before:bg-white before:shadow-sm before:transition-transform before:duration-300 peer-checked:bg-green-500 peer-checked:before:translate-x-full peer-focus-visible:outline-solid peer-focus-visible:outline-offset-2 peer-focus-visible:outline-gray-400 peer-focus-visible:peer-checked:outline-green-500"
							for="{{ $cart->name }}">
						</label>
					</div>
				</x-slot:menu>

				<div class="flex items-center justify-between">
					<span class="font-bold">Productos:</span>
					<span class="font-bold">{{ $cart->products_count }}</span>
				</div>

				<x-slot:actions class="flex flex-col" separator>
					<x-mary-button label="Editar Nombre" class="btn-primary" @click="$wire.myModal1 = true" />
					<x-mary-button label="Vaciar Carrito" class="btn-error" :disabled="$cart->products_count === 0"
						wire:click="void_cart('{{ $cart->id }}')" wire:confirm='¿Desea Vaciar el Carrito {{ $cart->name }}? ' />
					<x-mary-button :link="route('carts.show', ['cart' => $cart->id])" label="Mirar Carrito" class="btn-secondary" />
				</x-slot:actions>
			</x-mary-card>
		</div>
	@endforeach



</section>
