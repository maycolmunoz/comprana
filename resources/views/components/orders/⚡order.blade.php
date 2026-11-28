<?php

use App\Models\Order as ModelsOrder;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public $user;

    #[Validate('required|min:10|max:10')]
    public $phone = '';

    #[Validate('required|min:5|max:255')]
    public $address = '';

    #[Locked]
    public $cart;

    public function mount()
    {
        $this->user = Auth::user();
        $this->phone = $this->user->phone;
        $this->address = $this->user->address;
    }

    public function placeOrder()
    {
        $this->validate();

        $this->cart->load('products');

        if ($this->cart->products->isEmpty()) {
            $this->dispatch('notification', 'Tu carrito está vacío.');

            return;
        }

        $unavailableProducts = $this->cart->products
            ->filter(fn (Product $product): bool => $product->stock < (int) $product->pivot->cant)
            ->pluck('name');

        if ($unavailableProducts->isNotEmpty()) {
            $this->dispatch('notification', 'Sin stock suficiente: '.$unavailableProducts->implode(', '));

            return;
        }

        $items = $this->cart->toOrderItems();
        $total = (int) collect($items)->sum(
            fn (array $item): int => (int) $item['unit_price'] * (int) $item['quantity']
        );

        $order = DB::transaction(function () use ($items, $total) {
            $order = ModelsOrder::create([
                'customer_id' => $this->user->id,
                'phone' => $this->phone,
                'address' => $this->address,
                'total' => $total,
                'items' => $items,
            ]);

            $this->cart->products()->detach();

            return $order;
        });

        $this->redirectRoute('orders.pay', ['order' => $order]);
    }
}; ?>

<div class="mx-auto max-w-5xl px-6 py-12">
    {{-- Process Header --}}
    <div class="mb-12 text-center">
        <h2 class="text-3xl font-black tracking-tighter uppercase">
            Datos de <span class="text-red-600">contacto</span>
        </h2>
        <p class="text-base-content/40 mt-2 text-[10px] font-bold tracking-widest uppercase">
            Confirma dónde quieres recibir tu pedido de Comprana
        </p>
    </div>

    <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
        {{-- Left Section: Illustration --}}
        <div class="hidden lg:block">
            <div class="group relative">
                <div class="absolute -inset-4 rounded-[3rem] bg-red-600/5 blur-2xl transition-all duration-700 group-hover:bg-red-600/10"></div>
                <img
                    src="{{ asset('srcs/contact.png') }}"
                    alt="Información de Contacto"
                    class="relative mx-auto w-3/4 transform rounded-[2.5rem] drop-shadow-2xl transition-transform duration-700 group-hover:scale-105"
                />
            </div>
        </div>

        {{-- Right Section: Form Card --}}
        <div>
            <div class="bg-base-100 border-base-content/5 relative overflow-hidden rounded-[2.5rem] border p-8 shadow-2xl shadow-red-900/5 lg:p-10">
                {{-- Decorative Accent --}}
                <div class="absolute top-0 left-0 h-1.5 w-full bg-linear-to-r from-red-600 to-red-400"></div>

                <form wire:submit.prevent="placeOrder" class="space-y-8">
                    {{-- Instructions --}}
                    <div class="bg-base-content/5 mb-2 flex items-start gap-4 rounded-2xl p-4">
                        <x-mary-icon name="o-information-circle" class="h-5 w-5 shrink-0 text-red-600" />
                        <p class="text-base-content/60 text-[10px] leading-relaxed font-bold tracking-widest uppercase">
                            Por favor, verifica que tu número de teléfono y dirección sean correctos para garantizar una
                            entrega sin contratiempos.
                        </p>
                    </div>

                    {{-- Phone Input --}}
                    <div class="space-y-2">
                        <x-mary-input
                            label="Número de teléfono"
                            wire:model="phone"
                            type="number"
                            icon="o-phone"
                            placeholder="Tu teléfono de contacto"
                            class="bg-base-content/5 border-none focus:ring-red-600/20"
                        />
                        <x-base.input-error :messages="$errors->get('phone')" />
                    </div>

                    {{-- Address Input --}}
                    <div class="space-y-2">
                        <x-mary-input
                            label="Dirección de entrega"
                            wire:model="address"
                            icon="o-map-pin"
                            placeholder="Calle, número, ciudad y detalles adicionales"
                            class="bg-base-content/5 border-none focus:ring-red-600/20"
                        />
                        <x-base.input-error :messages="$errors->get('address')" />
                    </div>

                    {{-- Actions --}}
                    <div class="border-base-content/5 flex items-center justify-between gap-4 border-t pt-6">
                        <a
                            href="{{ route('carts.show', $cart->id) }}"
                            class="text-base-content/30 hover:text-base-content text-[10px] font-black tracking-widest uppercase transition-colors"
                        >
                            ← Cancelar
                        </a>

                        <x-mary-button
                            type="submit"
                            label="Continuar al pago"
                            icon-right="o-credit-card"
                            spinner="placeOrder"
                            class="btn-primary group h-auto rounded-2xl px-8 py-4 text-xs font-black tracking-widest uppercase"
                        >
                            <x-slot:append>
                                <x-mary-icon
                                    name="o-chevron-right"
                                    class="h-3 w-3 transition-transform group-hover:translate-x-1"
                                />
                            </x-slot:append>
                        </x-mary-button>
                    </div>
                </form>

                {{-- Trust Footer --}}
                <div class="mt-10 text-center opacity-30">
                    <p class="text-[8px] font-bold tracking-[0.4em] uppercase">Garantía de privacidad Comprana</p>
                </div>
            </div>
        </div>
    </div>
</div>
