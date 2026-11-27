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
    <div wire:loading class="flex items-center justify-center w-full min-h-100">
        <x-mary-loading class="loading-lg text-primary" />
    </div>

    @if ($product)
        <div wire:loading.remove class="flex flex-col gap-10">
            {{-- Image Gallery Section --}}
            <section x-data="{ currentImage: '' }" x-init="currentImage = @js($product->images[0] ?? '')"
                class="flex flex-col lg:flex-row gap-4">
                {{-- Main Image Display --}}
                <div class="w-full lg:w-3/4">
                    <div
                        class="bg-base-100 rounded-3xl overflow-hidden border border-base-content/5 shadow-2xl shadow-red-900/5 aspect-square relative group">
                        <img :src="@js(asset('storage')) + '/' + currentImage"
                            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                            alt="{{ $product->name }}">

                        {{-- Decorative Overlays --}}
                        <div class="absolute inset-0 bg-linear-to-t from-black/20 to-transparent pointer-events-none"></div>

                        @if ($product->stock <= 0)
                            <div
                                class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center rotate-[-15deg] z-10">
                                <span
                                    class="text-3xl font-black text-red-600 border-4 border-red-600 px-8 py-3 tracking-tighter uppercase">
                                    Agotado
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Thumbnails --}}
                @if (count($product->images) > 1)
                    <div class="flex flex-wrap lg:flex-col gap-3 lg:w-1/4">
                        @foreach ($product->images as $image)
                            <button type="button" @click="currentImage = @js($image)"
                                class="w-20 h-20 rounded-2xl overflow-hidden border-2 transition-all duration-300 relative group"
                                aria-label="Ver imagen {{ $loop->iteration }} de {{ $product->name }}"
                                :class="currentImage === @js($image) ?
                                    'border-red-600 shadow-md shadow-red-600/10 scale-105' :
                                    'border-transparent opacity-60 hover:opacity-100'">
                                <img src="{{ asset('storage/'.$image) }}" class="w-full h-full object-cover"
                                    alt="{{ $product->name }} - imagen {{ $loop->iteration }}">
                                <div class="absolute inset-0 bg-red-600/10 opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
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
                        <x-mary-badge value="{{ $product->section->name ?? 'General' }}"
                            class="bg-red-600/10 text-red-600 font-bold text-[10px] uppercase tracking-[0.2em] px-3 py-1.5 border-none rounded-lg" />

                        <h3 class="text-2xl font-black tracking-tighter uppercase leading-none text-base-content">
                            {{ $product->name }}
                        </h3>

                        <p class="text-base text-base-content/70 leading-relaxed font-normal">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>

                {{-- Price & Stock --}}
                <div class="flex items-end gap-6 mt-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black uppercase tracking-widest text-base-content/30">
                            Precio
                        </span>
                        <x-base.price :value="$product->price"
                            class="text-4xl font-black text-base-content italic tracking-tighter" />
                    </div>

                    <div class="flex flex-col pb-1">
                        <span class="text-[10px] font-black uppercase tracking-widest text-base-content/30">
                            Disponibilidad
                        </span>
                        <div class="flex items-center gap-1.5 pt-1">
                            <div
                                class="{{ $product->stock > 0 ? 'bg-success' : 'bg-base-content/50' }} w-2 h-2 rounded-full">
                            </div>
                            <span
                                class="{{ $product->stock > 0 ? 'text-success' : 'text-red-600' }} text-xs font-bold uppercase tracking-widest">
                                {{ $product->stock > 0 ? $product->stock.' unidades' : 'Sin existencias' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Checkout Actions Case --}}
                <div class="my-4 pt-6 border-t border-base-content/5 space-y-8">
                    @if ($product->stock > 0)
                        <form wire:submit.prevent="addToCart"
                            x-data="{ quantity: @entangle('quantity'), price: @entangle('price'), available: true }"
                            x-init="$watch('quantity', value => { available = value < 1 || value > {{ $product->stock }} ? false : true })"
                            @reset-quantity.window="quantity = 1" class="space-y-6">
                            {{-- Quantity & Total Block --}}
                            <div
                                class="flex flex-row items-center justify-between gap-4 lg:gap-6 p-3 lg:p-4 bg-base-content/5 rounded-3xl border-l-4 border-red-600 w-full">
                                {{-- Quantity Selector --}}
                                <div class="flex flex-col gap-4 lg:w-auto">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-base-content/40">
                                        Seleccionar cantidad
                                        <span class="text-red-600 ml-2" x-show="!available">
                                            La cantidad supera las existencias
                                        </span>
                                    </span>

                                    <div
                                        class="flex items-center bg-base-content/5 rounded-2xl p-1.5 gap-2 w-fit border border-base-content/5 shadow-inner">
                                        <x-mary-button icon="o-minus" class="btn-circle btn-sm btn-ghost" aria-label="Disminuir cantidad"
                                            @click="quantity = quantity <= 1 ? 1 : parseInt(quantity) - 1" />

                                        <span class="w-16 text-center text-xl font-black tabular-nums text-base-content"
                                            x-text="quantity"></span>

                                        <x-mary-button icon="o-plus" class="btn-circle btn-sm btn-ghost" aria-label="Aumentar cantidad"
                                            @click="quantity = quantity >= {{ $product->stock }} ? quantity : parseInt(quantity) + 1" />
                                    </div>

                                    @error('quantity')
                                        <p class="text-[10px] font-bold text-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Total Display --}}
                                <div class="flex flex-col gap-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-base-content/40">
                                        Total
                                    </span>
                                    <span class="text-2xl font-black tracking-tighter text-base-content">
                                        <span class="text-red-600 font-normal">$</span>
                                        <span x-text="(price * quantity).toLocaleString('es-CO')"></span>
                                    </span>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <x-mary-button type="submit" label="Agregar al carrito" icon="o-shopping-bag"
                                class="btn-primary w-full py-4 h-auto font-black text-sm uppercase tracking-widest rounded-2xl group active:scale-[0.98] transition-all"
                                x-bind:disabled="!available">
                                <x-slot:append>
                                    <x-mary-icon name="o-chevron-right"
                                        class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" />
                                </x-slot:append>
                            </x-mary-button>
                        </form>
                    @else
                        <div class="bg-red-600/5 rounded-2xl p-6 text-center border border-red-600/10">
                            <x-mary-icon name="o-face-frown" class="w-8 h-8 text-red-600 mx-auto mb-3" />
                            <p class="text-sm font-bold text-red-600 uppercase tracking-widest">
                                Este producto no se encuentra disponible actualmente
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
