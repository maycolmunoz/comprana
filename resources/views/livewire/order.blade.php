<div class="container px-6 mx-auto">
 
    
    <div class="flex flex-col my-8 lg:flex-row">
        <div class="order-2 w-full lg:w-1/2">


            {{-- left  --}}
                    <form class="my-8 lg:w-3/4" wire:submit.prevent='contacts'>
                        <div class="mt-8">
                            <h4 class="text-sm font-medium text-gray-500">Teléfono:</h4>
                            <div class="flex mt-2">
                                <label class="flex-1 block ml-3">
                                    <input type="number" wire:model='phone'
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:border-blue-300 focus:ring-3 focus:ring-blue-200 focus:ring-opacity-50"
                                        placeholder="Teléfono">
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('phone')" class="mb-4" />

                            <h4 class="text-sm font-medium text-gray-500">Dirección:</h4>
                            <div class="flex mt-2">
                                <label class="flex-1 block ml-3">
                                    <textarea wire:model='address'
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-xs focus:border-blue-300 focus:ring-3 focus:ring-blue-200 focus:ring-opacity-50"
                                        placeholder="Dirección"></textarea>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('address')" class="mb-4" />
                        </div>
                        <div class="flex items-center justify-end mt-8">

                            <button type="submit"
                                class="flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-500 focus:outline-hidden focus:bg-blue-500">
                                <span>Siguiente</span>
                                <svg class="w-5 h-5 mx-2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

        </div>

        {{-- Right  --}}
        <div class="shrink-0 order-1 w-full mb-8 lg:w-1/2 lg:mb-0 lg:order-2">
                <img src="{{ asset('srcs/DALL·E 2024-02-28 15.36.51 - informacion de contacto estilo supermercado.png') }}" alt="contactos" class="w-1/2 mx-auto rounded-md">
        </div>

    </div>


</div>
