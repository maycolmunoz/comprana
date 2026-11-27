<?php

use App\Models\Cart;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Cart $cart;

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex items-center justify-center my-40">
            <span class="loading loading-spinner loading-lg text-primary"></span>
        </div>
        HTML;
    }

    #[On('refresh')]
    public function refresh(): void
    {
        $this->cart->load('products');
    }
}; ?>

<div>
    {{-- Navigation & Header --}}
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10 pb-8 border-b border-base-content/5">
        <div class="text-left">
            <a href="{{ route('carts.index') }}"
                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-base-content/40 hover:text-red-600 transition-colors mb-4 group">
                <x-mary-icon name="o-arrow-left" class="w-3 h-3 group-hover:-translate-x-1 transition-transform" />
                Volver a mis carritos
            </a>
            <h2 class="text-3xl font-black uppercase tracking-tighter leading-none">
                Productos en <span class="text-red-600">{{ $cart->name }}</span>
            </h2>
            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-2">
                Gestiona tu selección exclusiva de Comprana
            </p>
        </div>
        <div class="hidden md:block">
            <x-mary-badge value="{{ $cart->products->count() }} {{ $cart->products->count() === 1 ? 'artículo' : 'artículos' }}"
                class="badge-neutral font-black uppercase tracking-widest text-[10px] py-3 px-4 rounded-xl" />
        </div>
    </div>

    @if ($cart->products->count())
        <div class="flex flex-col lg:flex-row gap-10">
            {{-- Products List --}}
            <div class="lg:w-2/3 space-y-6">
                @foreach ($cart->products as $product)
                    <livewire:carts.product :key="$product->id" :$product :quantity="$product->pivot->cant" :$cart />
                @endforeach
            </div>

            {{-- Order Summary Sidebar --}}
            <div class="lg:w-1/3">
                <div class="sticky top-24 space-y-6">
                    {{-- Summary Card --}}
                    <div
                        class="bg-base-100 border border-base-content/5 shadow-2xl shadow-red-900/5 rounded-[2.5rem] p-8 overflow-hidden relative">
                        {{-- Decorative Accent --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-linear-to-r from-red-600 to-red-400"></div>

                        <h3 class="text-xl font-black uppercase tracking-tighter mb-8">Resumen del pedido</h3>

                        {{-- Details --}}
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold uppercase tracking-widest text-base-content/40 text-[10px]">Subtotal</span>
                                <x-base.price :value="$cart->total()" class="font-black tabular-nums" />
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold uppercase tracking-widest text-base-content/40 text-[10px]">Envío</span>
                                <span class="text-success font-bold uppercase text-[10px] tracking-widest">Gratis</span>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="pt-6 border-t border-base-content/5 mb-8">
                            <div class="flex justify-between items-end">
                                <span class="text-sm font-black uppercase tracking-tighter">Total final</span>
                                <div class="text-right">
                                    <x-base.price :value="$cart->total()" class="text-4xl font-black tracking-tighter text-red-600" />
                                    <p class="text-[8px] font-bold uppercase tracking-widest text-base-content/30 mt-1">
                                        IVA e impuestos incluidos</p>
                                </div>
                            </div>
                        </div>

                        <x-mary-button link="{{ route('orders.create', ['id' => $cart->id]) }}" label="Finalizar compra"
                            icon-right="o-arrow-right"
                            class="btn-primary w-full py-4 h-auto font-black text-sm uppercase tracking-widest rounded-2xl" />
                    </div>
                </div>
            </div>
        </div>
    @else
        <x-base.empty-state icon="o-shopping-cart" title="Tu carrito está vacío"
            description="Agrega productos desde la tienda para comenzar tu compra."
            :link="route('products.index')" link-label="Explorar catálogo" />
    @endif
</div>
