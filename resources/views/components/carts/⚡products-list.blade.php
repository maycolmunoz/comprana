<?php

use App\Models\Cart;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Cart $cart;

    #[On('refresh')]
    public function refresh(): void
    {
        $this->cart->load('products');
    }
}; ?>

<div>
    {{-- Navigation & Header --}}
    <div class="border-base-content/5 mb-10 flex flex-col items-end justify-between gap-6 border-b pb-8 md:flex-row">
        <div class="text-left">
            <a
                href="{{ route('carts.index') }}"
                class="text-base-content/40 group mb-4 inline-flex items-center gap-2 text-[10px] font-black tracking-widest uppercase transition-colors hover:text-red-600"
            >
                <x-mary-icon name="o-arrow-left" class="h-3 w-3 transition-transform group-hover:-translate-x-1" />
                Volver a mis carritos
            </a>
            <h2 class="text-3xl leading-none font-black tracking-tighter uppercase">
                Productos en <span class="text-red-600">{{ $cart->name }}</span>
            </h2>
            <p class="text-base-content/40 mt-2 text-[10px] font-bold tracking-widest uppercase">
                Gestiona tu selección exclusiva de Comprana
            </p>
        </div>
        <div class="hidden md:block">
            <x-mary-badge
                value="{{ $cart->products->count() }} {{ $cart->products->count() === 1 ? 'artículo' : 'artículos' }}"
                class="badge-neutral rounded-xl px-4 py-3 text-[10px] font-black tracking-widest uppercase"
            />
        </div>
    </div>

    @if ($cart->products->count())
        <div class="flex flex-col gap-10 lg:flex-row">
            {{-- Products List --}}
            <div class="space-y-6 lg:w-2/3">
                @foreach ($cart->products as $product)
                    <livewire:carts.product :key="$product->id" :$product :quantity="$product->pivot->cant" :$cart />
                @endforeach
            </div>

            {{-- Order Summary Sidebar --}}
            <div class="lg:w-1/3">
                <div class="sticky top-24 space-y-6">
                    {{-- Summary Card --}}
                    <div class="bg-base-100 border-base-content/5 relative overflow-hidden rounded-[2.5rem] border p-8 shadow-2xl shadow-red-900/5">
                        {{-- Decorative Accent --}}
                        <div class="absolute top-0 left-0 h-1.5 w-full bg-linear-to-r from-red-600 to-red-400"></div>

                        <h3 class="mb-8 text-xl font-black tracking-tighter uppercase">Resumen del pedido</h3>

                        {{-- Details --}}
                        <div class="mb-8 space-y-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-base-content/40 text-[10px] font-bold tracking-widest uppercase">Subtotal</span>
                                <x-base.price :value="$cart->total()" class="font-black tabular-nums" />
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-base-content/40 text-[10px] font-bold tracking-widest uppercase">Envío</span>
                                <span class="text-success text-[10px] font-bold tracking-widest uppercase">Gratis</span>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="border-base-content/5 mb-8 border-t pt-6">
                            <div class="flex items-end justify-between">
                                <span class="text-sm font-black tracking-tighter uppercase">Total final</span>
                                <div class="text-right">
                                    <x-base.price
                                        :value="$cart->total()"
                                        class="text-4xl font-black tracking-tighter text-red-600"
                                    />
                                    <p class="text-base-content/30 mt-1 text-[8px] font-bold tracking-widest uppercase">
                                        IVA e impuestos incluidos
                                    </p>
                                </div>
                            </div>
                        </div>

                        <x-mary-button
                            link="{{ route('orders.create', ['id' => $cart->id]) }}"
                            label="Finalizar compra"
                            icon-right="o-arrow-right"
                            class="btn-primary h-auto w-full rounded-2xl py-4 text-sm font-black tracking-widest uppercase"
                        />
                    </div>
                </div>
            </div>
        </div>
    @else
        <x-base.empty-state
            icon="o-shopping-cart"
            title="Tu carrito está vacío"
            description="Agrega productos desde la tienda para comenzar tu compra."
            :link="route('products.index')"
            link-label="Explorar catálogo"
        />
    @endif
</div>
