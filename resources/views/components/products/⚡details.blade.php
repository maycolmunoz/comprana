<?php

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public ?Product $product = null;

    /** Snapshot for display; re-synced from the product before validation. */
    public int $stock = 0;

    public int $price = 0;

    public int $quantity = 1;

    #[On('details')]
    public function loadProduct(string $id): void
    {
        $product = Product::find($id);

        if (! $product) {
            return;
        }

        $this->product = $product;
        $this->stock = $product->stock;
        $this->price = $product->price;
        $this->quantity = 1;
        $this->resetValidation();
    }

    public function addToCart(): void
    {
        if (! $this->product) {
            return;
        }

        $this->stock = $this->product->stock;
        $this->price = $this->product->price;

        if ($this->quantity < 1 || $this->quantity > $this->stock) {
            $this->addError('quantity', 'La cantidad supera las existencias disponibles.');

            return;
        }

        $user = Auth::user();

        if (! $user) {
            $this->dispatch('notification', ['message' => 'Inicia sesión para agregar productos.', 'type' => 'error']);

            return;
        }

        $cart = $user->activeCart;

        if (! $cart) {
            $this->dispatch('notification', ['message' => 'No tienes un carrito activo.', 'type' => 'error']);

            return;
        }

        $cart->products()->syncWithoutDetaching([
            $this->product->id => [
                'cant' => $this->quantity,
            ],
        ]);

        $this->dispatch('notification', "Se agregó {$this->quantity} de {$this->product->name} a tu carrito");
        $this->dispatch('slider-close');
        $this->quantity = 1;
    }
};
?>

<div>
    <div wire:loading class="flex min-h-100 w-full items-center justify-center">
        <x-mary-loading class="loading-lg text-primary" />
    </div>

    @if ($product)
        <div wire:loading.remove class="flex flex-col gap-10">
            {{-- Image Gallery Section --}}
            <section
                x-data="{ currentImage: '' }"
                x-init="currentImage = @js($product->images[0] ?? '')"
                class="flex flex-col gap-4 lg:flex-row"
            >
                {{-- Main Image Display --}}
                <div class="w-full lg:w-3/4">
                    <div class="bg-base-100 border-base-content/5 group relative aspect-square overflow-hidden rounded-3xl border shadow-2xl shadow-red-900/5">
                        <img
                            :src="@js(asset('storage')) + '/' + currentImage"
                            class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110"
                            alt="{{ $product->name }}"
                        />

                        {{-- Decorative Overlays --}}
                        <div class="pointer-events-none absolute inset-0 bg-linear-to-t from-black/20 to-transparent"></div>

                        @if ($product->stock <= 0)
                            <div class="absolute inset-0 z-10 flex rotate-[-15deg] items-center justify-center bg-white/60 backdrop-blur-sm">
                                <span class="border-4 border-red-600 px-8 py-3 text-3xl font-black tracking-tighter text-red-600 uppercase">
                                    Agotado
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Thumbnails --}}
                @if (count($product->images) > 1)
                    <div class="flex flex-wrap gap-3 lg:w-1/4 lg:flex-col">
                        @foreach ($product->images as $image)
                            <button
                                type="button"
                                @click="currentImage = @js($image)"
                                class="group relative h-20 w-20 overflow-hidden rounded-2xl border-2 transition-all duration-300"
                                aria-label="Ver imagen {{ $loop->iteration }} de {{ $product->name }}"
                                :class="currentImage === @js($image) ?
                                    'border-red-600 shadow-md shadow-red-600/10 scale-105' :
                                    'border-transparent opacity-60 hover:opacity-100'"
                            >
                                <img
                                    src="{{ asset('storage/'.$image) }}"
                                    class="h-full w-full object-cover"
                                    alt="{{ $product->name }} - imagen {{ $loop->iteration }}"
                                />
                                <div class="absolute inset-0 bg-red-600/10 opacity-0 transition-opacity group-hover:opacity-100"></div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- Product Information Section --}}
            <div class="flex flex-col justify-between py-2">
                <div class="space-y-6">
                    {{-- Header --}}
                    <div class="space-y-2">
                        <x-mary-badge
                            value="{{ $product->section->name ?? 'General' }}"
                            class="rounded-lg border-none bg-red-600/10 px-3 py-1.5 text-[10px] font-bold tracking-[0.2em] text-red-600 uppercase"
                        />

                        <h3 class="text-base-content text-2xl leading-none font-black tracking-tighter uppercase">
                            {{ $product->name }}
                        </h3>

                        <p class="text-base-content/70 text-base leading-relaxed font-normal">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>

                {{-- Price & Stock --}}
                <div class="mt-4 flex items-end gap-6">
                    <div class="flex flex-col">
                        <span class="text-base-content/30 text-[10px] font-black tracking-widest uppercase">
                            Precio
                        </span>
                        <x-base.price
                            :value="$product->price"
                            class="text-base-content text-4xl font-black tracking-tighter italic"
                        />
                    </div>

                    <div class="flex flex-col pb-1">
                        <span class="text-base-content/30 text-[10px] font-black tracking-widest uppercase">
                            Disponibilidad
                        </span>
                        <div class="flex items-center gap-1.5 pt-1">
                            <div class="{{ $product->stock > 0 ? 'bg-success' : 'bg-base-content/50' }} w-2 h-2 rounded-full"></div>
                            <span class="{{ $product->stock > 0 ? 'text-success' : 'text-red-600' }} text-xs font-bold uppercase tracking-widest">
                                {{ $product->stock > 0 ? $product->stock.' unidades' : 'Sin existencias' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Checkout Actions Case --}}
                <div class="border-base-content/5 my-4 space-y-8 border-t pt-6">
                    @if ($product->stock > 0)
                        <form
                            wire:submit.prevent="addToCart"
                            x-data="{ quantity: @entangle('quantity'), price: @entangle('price'), available: true }"
                            x-init="$watch('quantity', value => { available = value < 1 || value > {{ $product->stock }} ? false : true })"
                            @reset-quantity.window="quantity = 1"
                            class="space-y-6"
                        >
                            {{-- Quantity & Total Block --}}
                            <div class="bg-base-content/5 flex w-full flex-row items-center justify-between gap-4 rounded-3xl border-l-4 border-red-600 p-3 lg:gap-6 lg:p-4">
                                {{-- Quantity Selector --}}
                                <div class="flex flex-col gap-4 lg:w-auto">
                                    <span class="text-base-content/40 text-[10px] font-black tracking-widest uppercase">
                                        Seleccionar cantidad
                                        <span class="ml-2 text-red-600" x-show="! available">
                                            La cantidad supera las existencias
                                        </span>
                                    </span>

                                    <div class="bg-base-content/5 border-base-content/5 flex w-fit items-center gap-2 rounded-2xl border p-1.5 shadow-inner">
                                        <x-mary-button
                                            icon="o-minus"
                                            class="btn-circle btn-sm btn-ghost"
                                            aria-label="Disminuir cantidad"
                                            @click="quantity = quantity <= 1 ? 1 : parseInt(quantity) - 1"
                                        />

                                        <span
                                            class="text-base-content w-16 text-center text-xl font-black tabular-nums"
                                            x-text="quantity"
                                        ></span>

                                        <x-mary-button
                                            icon="o-plus"
                                            class="btn-circle btn-sm btn-ghost"
                                            aria-label="Aumentar cantidad"
                                            @click="quantity = quantity >= {{ $product->stock }} ? quantity : parseInt(quantity) + 1"
                                        />
                                    </div>

                                    @error('quantity')
                                        <p class="text-error text-[10px] font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Total Display --}}
                                <div class="flex flex-col gap-2">
                                    <span class="text-base-content/40 text-[10px] font-black tracking-widest uppercase">
                                        Total
                                    </span>
                                    <span class="text-base-content text-2xl font-black tracking-tighter">
                                        <span class="font-normal text-red-600">$</span>
                                        <span x-text="(price * quantity).toLocaleString('es-CO')"></span>
                                    </span>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <x-mary-button
                                type="submit"
                                label="Agregar al carrito"
                                icon="o-shopping-bag"
                                class="btn-primary group h-auto w-full rounded-2xl py-4 text-sm font-black tracking-widest uppercase transition-all active:scale-[0.98]"
                                x-bind:disabled="! available"
                            >
                                <x-slot:append>
                                    <x-mary-icon
                                        name="o-chevron-right"
                                        class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                                    />
                                </x-slot:append>
                            </x-mary-button>
                        </form>
                    @else
                        <div class="rounded-2xl border border-red-600/10 bg-red-600/5 p-6 text-center">
                            <x-mary-icon name="o-face-frown" class="mx-auto mb-3 h-8 w-8 text-red-600" />
                            <p class="text-sm font-bold tracking-widest text-red-600 uppercase">
                                Este producto no se encuentra disponible actualmente
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
