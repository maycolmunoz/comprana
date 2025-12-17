<div class="container px-1 mx-auto my-auto sm:px-6">
    <x-commons.loading wire:loading class="items-center justify-center w-full h-full" />

    @if($product)
    <div wire:loading.remove class="flex flex-col items-center md:flex-row">
        @if ($product?->images)
        {{-- images --}}
        <section x-data="{image: ''}" x-init="image = '{{$product->images[0]}}'"
            class="w-full max-w-xs md:max-w-full md:w-1/2 lg:w-1/3 dark:bg-gray-800 dark:text-gray-50">
            <img :src="'/storage/images/'+ image" class="w-full rounded shadow-sm dark:bg-gray-500 aspect-square">
            <div class="flex">
                @foreach ($product->images as $image)
                <img x-on:click="image = '{{$image}}'" alt="{{$image}}"
                    class="w-1/4 rounded shadow-sm dark:bg-gray-500 aspect-square hover:cursor-pointer"
                    src="storage/images/{{$image}}">
                @endforeach
            </div>
        </section>
        @endif
       

        {{-- details --}}
        <div class="w-full max-w-lg mx-auto mt-2 md:ml-8 md:mt-0 md:w-1/2">
            <h3 class="text-lg text-gray-700 uppercase">{{$product->name}}</h3>
            <span class="mt-3 text-gray-500">$ {{$product->price}}</span>
            <hr class="my-3">
            <span class="mt-3 text-gray-500">Disponible: {{$product->stock}}</span>
            <p>
                {{$product->description}}
            </p>

            {{-- add to cart --}}
            <form wire:submit.prevent='order({{$product->id}})' 
                x-data="{count: @entangle('count'), price: @entangle('price'), available: true }"
                class="mt-2" x-init=" 
                $watch('count', value => {
                    available = value < 1 || value > $wire.stock ? false : true
                })"
                @reset-count.window="count= 1">
                <label class="text-sm text-gray-700" for="count">Cantidad:
                    <span class="text-red-300" x-show="!available">Seleccione una cantidad</span>
                </label>
                <div class="flex items-center mt-1">
                    <button type="button" @click="count = count <= 1 ? 1 : parseInt(count) - 1"
                        class="text-gray-500 focus:outline-none focus:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                    <span type="number" x-text="count" class="block w-24 text-sm text-gray-700"></span>
                    <button type="button" @click="count = count >= $wire.stock ? count :  parseInt(count) + 1"
                        class="text-gray-500 focus:outline-none focus:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                </div>
                <p>total: <span x-text="price * count" ></span></p>
                <button type="submit" :disabled="!available" @click="$dispatch('slider-close')"
                    :class="available ? 'hover:bg-indigo-500 ' :'bg-red-500 opacity-75 hover:cursor-not-allowed'"
                    class="px-8 py-2 mt-6 text-sm font-medium text-white bg-indigo-600 rounded focus:outline-none focus:bg-indigo-500">
                    Agregar al Carrito
                </button>
            </form>
        </div>
    </div>
    @endif
</div>