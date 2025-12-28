<section x-data="{id: @entangle('cart_id'), name: '', show: false}" @close-edit="show=false"
    class="flex flex-wrap items-center justify-center gap-4 px-4 py-5 justify">


    <form x-cloak x-show="show" wire:submit.prevent='edit_cart()' class="fixed z-50 p-5 mt-5 rounded-md md:w-1/3 top-1/2 bg-slate-500">
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
    </form>

    {{---- Card---- --}}
    @foreach ($carts as $cart)
    <div class="flex flex-col items-center justify-center ">
        <div class="p-4 bg-gray-200 rounded-lg border shadow-lg min-w-64 {{$cart->active ? 'shadow-emerald-500  border-emerald-700': 'border-gray-400'}}">
            <div class="flex justify-between mb-2">
                <div class="flex items-center">
                    <img src="{{asset('srcs/cart.webp')}}" alt="{{$cart->name}}" class="mr-4">
                    <div>
                        <h2 class="font-bold">{{$cart->name}} </h2>
                        {{-- active --}}
                        <div class="text-gray-700">
                            <input type="checkbox" {{$cart->active === 0 ? '' : 'checked disabled'}} 
                            wire:loading.attr="disabled"
                            class="opacity-0 sr-only peer" id="{{$cart->name}}"
                            wire:click="active_cart('{{$cart->id}}')"
                            />
                            <label for="{{$cart->name}}"
                                class="relative flex h-6 w-11 cursor-pointer items-center rounded-full bg-gray-400 px-0.5 outline-gray-400 transition-colors before:h-5 before:w-5 before:rounded-full before:bg-white before:shadow-sm before:transition-transform before:duration-300 peer-checked:bg-green-500 peer-checked:before:translate-x-full peer-focus-visible:outline-solid peer-focus-visible:outline-offset-2 peer-focus-visible:outline-gray-400 peer-focus-visible:peer-checked:outline-green-500"
                                for="{{$cart->name}}">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-2">
            <div class="flex items-center justify-between">
                <span class="font-bold">Productos:</span>
                <span class="font-bold">{{$cart->products_count}}</span>
            </div>
            <div class="flex flex-wrap justify-center gap-2 mt-4">
                <button title="Editar Nombre del Carrito" @click="show = true;  
                name= '{{$cart->name}}'
                id= '{{$cart->id}}' " class="px-2 font-bold text-white bg-green-500 rounded hover:bg-green-700">
                    Editar
                </button>
                <button title="Vaciar Carrito" wire:confirm='¿Desea Vaciar el Carrito {{$cart->name}}? '
                    {{$cart->products_count === 0 ? 'disabled' : '' }} wire:click="void_cart('{{$cart->id}}')"
                    class="{{$cart->products_count === 0 ? 'bg-gray-800': 'bg-red-500 hover:bg-red-700'}} px-2 font-bold
                    text-white rounded ">
                    Vaciar
                </button>
            </div>
            <hr class="my-3">
            <div class="flex justify-center mt-4">
                <a href="{{route('carts.show', ['cart'=> $cart->id])}}" wire:navigate
                    class="px-4 py-2 font-bold text-white bg-blue-500 rounded-sm hover:bg-blue-700">
                    Mirar Carrito
                </a>
            </div>
        </div>
    </div>
    @endforeach



</section>