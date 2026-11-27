<?php

use App\Models\Cart;
use App\Models\Product;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Product $product;

    #[Locked]
    public Cart $cart;

    public int $quantity = 1;

    public function updateQuantity(): void
    {
        $this->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$this->product->stock],
        ]);

        $this->cart->products()->updateExistingPivot($this->product->id, ['cant' => $this->quantity]);

        $this->dispatch('notification', 'Se actualizó la cantidad del producto');
        $this->dispatch('refresh');
    }

    public function removeProduct(): void
    {
        $this->cart->products()->detach($this->product->id);

        $this->dispatch('notification', 'Se eliminó el producto');
        $this->dispatch('refresh');
    }
}; ?>

<div
    x-data="{ quantity: @entangle('quantity'), price: {{ $product->price }} }"
    class="group bg-base-100 border-base-content/5 hover:shadow-base-content/5 relative flex flex-col gap-6 overflow-hidden rounded-3xl border p-4 transition-all duration-300 hover:shadow-xl sm:flex-row"
>
    {{-- Product Image --}}
    <div class="bg-base-content/5 relative h-48 w-full shrink-0 overflow-hidden rounded-2xl sm:w-48">
        <img
            src="{{ $product->imageUrl() }}"
            alt="{{ $product->name }}"
            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
        />
        <div class="absolute top-3 left-3">
            <span class="bg-base-100/80 font-outfit rounded-lg border border-white/20 px-3 py-1 text-[10px] font-black tracking-widest uppercase shadow-sm backdrop-blur-md transition-colors group-hover:border-red-600 group-hover:bg-red-600 group-hover:text-white">
                <x-base.price :value="$product->price" class="font-black" symbol-class="text-current" />
            </span>
        </div>
    </div>

    <div class="flex flex-1 flex-col justify-between py-1">
        <div>
            <div class="mb-2 flex items-start justify-between gap-4">
                <h3 class="truncate text-xl leading-none font-black tracking-tighter uppercase">
                    {{ $product->name }}
                </h3>
                <div class="shrink-0 text-right">
                    <span class="text-xs font-black tracking-widest text-red-600 uppercase"
                        >$<span x-text="(price * quantity).toLocaleString('es-CO')"></span
                    ></span>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-4">
                <div class="flex items-center gap-1.5">
                    <x-mary-icon name="o-cube" class="text-base-content/30 h-3.5 w-3.5" />
                    <span class="text-base-content/40 text-[10px] font-bold tracking-widest uppercase">Existencias: {{ $product->stock }}</span>
                </div>
            </div>
        </div>

        <div class="border-base-content/5 mt-6 flex flex-wrap items-center justify-between gap-6 border-t pt-6">
            {{-- Quantity Controls --}}
            <div class="bg-base-content/5 flex items-center gap-1 rounded-xl p-1">
                <x-mary-button
                    icon="o-minus"
                    class="btn-ghost btn-circle btn-sm"
                    aria-label="Disminuir cantidad"
                    @click="quantity = quantity <= 1 ? 1 : parseInt(quantity) - 1"
                />
                <span class="w-10 text-center text-xs font-black tabular-nums" x-text="quantity"></span>
                <x-mary-button
                    icon="o-plus"
                    class="btn-ghost btn-circle btn-sm"
                    aria-label="Aumentar cantidad"
                    @click="quantity = quantity >= {{ $product->stock }} ? quantity : parseInt(quantity) + 1"
                />
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <x-mary-button
                    wire:click="updateQuantity"
                    icon="o-arrow-path"
                    label="Actualizar"
                    class="btn-ghost btn-sm bg-base-content/5 rounded-xl text-[10px] font-black tracking-widest uppercase hover:bg-red-600/10 hover:text-red-600"
                />
                <x-mary-button
                    wire:click="removeProduct"
                    wire:confirm="¿Seguro deseas eliminar este producto de tu carrito?"
                    icon="o-trash"
                    class="btn-error btn-outline btn-sm rounded-xl border-red-600/20 text-[10px] font-black tracking-widest text-red-600 uppercase hover:bg-red-600/10"
                />
            </div>
        </div>
    </div>
</div>
