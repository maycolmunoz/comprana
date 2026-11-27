@php
    $inStock = $product->inStock;
    $sectionName = request('section') != '' ? request('section') : $product->section->name ?? 'General';
@endphp

<x-mary-card class="bg-base-100 border-base-content/5 group relative h-full overflow-hidden rounded-4xl border shadow-xl transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-red-600/5">
    <x-slot:figure class="relative aspect-square overflow-hidden">
        {{-- Product Image with Hover Zoom --}}
        <img
            src="{{ $product->imageUrl() }}"
            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
            alt="{{ $product->name }}"
        />

        {{-- Section Badge overlay --}}
        <div class="absolute top-4 left-4 z-10">
            <x-mary-badge
                class="rounded-full border border-white/20 bg-white/70 px-3 py-1.5 text-[10px] font-bold tracking-widest text-red-600 uppercase shadow-sm backdrop-blur-md"
                :value="$sectionName"
            />
        </div>

        {{-- Out of Stock Overlay --}}
        @if (! $inStock)
            <div class="pointer-events-none absolute inset-0 z-20 flex rotate-[-15deg] items-center justify-center bg-white/60 backdrop-blur-[2px]">
                <span class="border-4 border-red-600 px-6 py-2 text-2xl font-black tracking-tighter text-red-600 uppercase opacity-80">
                    Agotado
                </span>
            </div>
        @endif
    </x-slot:figure>

    {{-- Content --}}
    <div class="space-y-3 px-1">
        <h3 class="text-lg leading-tight font-black tracking-tight uppercase transition-colors group-hover:text-red-600 lg:text-xl">
            {{ $product->name }}
        </h3>

        <div class="flex items-center justify-between gap-2">
            <div class="flex flex-col">
                <span class="text-[10px] font-bold tracking-widest uppercase opacity-30">Precio</span>
                <x-base.price
                    :value="$product->price"
                    class="text-base-content text-2xl font-black tracking-tighter italic"
                />
            </div>

            <div class="flex items-center gap-1.5">
                @if ($inStock)
                    @guest
                        <x-mary-button
                            :link="route('login')"
                            icon="o-shopping-cart"
                            class="btn-primary btn-circle shadow-primary/20 shadow-lg hover:scale-110"
                            aria-label="Inicia sesión para comprar {{ $product->name }}"
                        />
                    @else
                        <x-mary-button
                            icon="o-shopping-cart"
                            class="btn-primary btn-circle shadow-primary/20 shadow-lg transition-all hover:scale-110 active:scale-95"
                            aria-label="Ver detalles de {{ $product->name }}"
                            x-data="{
                                realizarAccion(id) {
                                    $dispatch('slider');
                                    $dispatch('details', { id: id });
                                    $dispatch('reset-quantity');
                                },
                            }"
                            @click="realizarAccion('{{ $product->id }}')"
                        />
                    @endguest
                @else
                    <div class="bg-base-content/5 rounded-full p-3 opacity-30">
                        <x-mary-icon name="o-no-symbol" class="h-6 w-6" />
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-mary-card>
