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

<div x-data="{ quantity: @entangle('quantity'), price: {{ $product->price }} }"
    class="group bg-base-100 border border-base-content/5 rounded-3xl p-4 flex flex-col sm:flex-row gap-6 transition-all duration-300 hover:shadow-xl hover:shadow-base-content/5 relative overflow-hidden">

    {{-- Product Image --}}
    <div class="w-full sm:w-48 h-48 rounded-2xl overflow-hidden bg-base-content/5 relative shrink-0">
        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
        <div class="absolute top-3 left-3">
            <span
                class="bg-base-100/80 backdrop-blur-md px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/20 shadow-sm transition-colors group-hover:bg-red-600 group-hover:text-white group-hover:border-red-600 font-outfit">
                <x-base.price :value="$product->price" class="font-black" />
            </span>
        </div>
    </div>

    <div class="flex-1 flex flex-col justify-between py-1">
        <div>
            <div class="flex justify-between items-start gap-4 mb-2">
                <h3 class="text-xl font-black uppercase tracking-tighter truncate leading-none">{{ $product->name }}</h3>
                <div class="text-right shrink-0">
                    <span class="text-xs font-black uppercase tracking-widest text-red-600">$<span
                            x-text="(price * quantity).toLocaleString('es-CO')"></span></span>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 mt-4">
                <div class="flex items-center gap-1.5">
                    <x-mary-icon name="o-cube" class="w-3.5 h-3.5 text-base-content/30" />
                    <span class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">Existencias:
                        {{ $product->stock }}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-6 mt-6 pt-6 border-t border-base-content/5">
            {{-- Quantity Controls --}}
            <div class="flex items-center bg-base-content/5 rounded-xl p-1 gap-1">
                <x-mary-button icon="o-minus" class="btn-ghost btn-circle btn-sm" aria-label="Disminuir cantidad"
                    @click="quantity = quantity <= 1 ? 1 : parseInt(quantity) - 1" />
                <span class="w-10 text-center text-xs font-black tabular-nums" x-text="quantity"></span>
                <x-mary-button icon="o-plus" class="btn-ghost btn-circle btn-sm" aria-label="Aumentar cantidad"
                    @click="quantity = quantity >= {{ $product->stock }} ? quantity : parseInt(quantity) + 1" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <x-mary-button wire:click="updateQuantity" icon="o-arrow-path" label="Actualizar"
                    class="btn-ghost btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl bg-base-content/5 hover:bg-red-600/10 hover:text-red-600" />
                <x-mary-button wire:click="removeProduct" wire:confirm="¿Seguro deseas eliminar este producto de tu carrito?"
                    icon="o-trash"
                    class="btn-error btn-outline btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl border-red-600/20 hover:bg-red-600/10 text-red-600" />
            </div>
        </div>
    </div>
</div>
