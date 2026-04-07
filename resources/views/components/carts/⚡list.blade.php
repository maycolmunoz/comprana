<?php

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    public bool $myModal1 = false;

    public $carts;

    public $user_id;

    public $cart_id = '';

    #[Validate('required|min:2|max:25|unique:carts')]
    public $name = '';

    public function mount()
    {
        $this->user_id = Auth::user()->id;
        $this->myModal1 = false;
    }

    public function active_cart(string $cart_id)
    {
        Cart::whereActive(true)
            ->whereUserId($this->user_id)
            ->update(['active' => false]);

        Cart::whereId($cart_id)
            ->whereUserId($this->user_id)
            ->update(['active' => true]);
    }

    public function void_cart(string $cart_id)
    {
        $cart = Cart::find($cart_id);
        $this->authorize('author', $cart);
        $cart->products()->detach();
        $this->dispatch('notification', 'Se vació el carrito');
    }

    public function edit_cart()
    {
        $this->validate();
        Cart::whereId($this->cart_id)->update(['name' => $this->name]);

        $this->dispatch('notification', 'Se edito el carrito');
        $this->myModal1 = false;
        $this->reset('name');
    }

    public function with(): array
    {
        return [
            'carts' => Cart::whereUserId($this->user_id)->withCount('products')->orderBy('active', 'desc')->get(),
        ];
    }
}; ?>

<section x-data="{ id: @entangle('cart_id'), name: '', show: false }" @close-edit="show=false"
    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

    {{-- Edit Cart Name Modal --}}
    <x-mary-modal wire:model="myModal1" class="backdrop-blur-xl">
        <div class="p-4 text-center">
            <h3 class="text-2xl font-black uppercase tracking-tighter mb-2">Renombrar Carrito</h3>
            <form wire:submit.prevent='edit_cart()' class="space-y-6">
                <x-mary-input label="Nuevo Nombre" wire:model='name' icon="o-pencil-square"
                    class="bg-base-content/5 border-none focus:ring-red-600/20" />

                <div class="flex gap-3 pt-4">
                    <x-mary-button label="Cancelar" @click="$wire.myModal1 = false"
                        class="btn-ghost flex-1 font-black text-xs uppercase tracking-widest rounded-xl" />
                    <x-mary-button type="submit" label="Actualizar"
                        class="btn-primary flex-1 font-black text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-red-600/20" />
                </div>
            </form>
        </div>
    </x-mary-modal>

    {{-- Cart Cards Grid --}}
    @foreach ($carts as $cart)
        <div class="group relative">
            {{-- Active Glow Effect --}}
            @if ($cart->active)
                <div
                    class="absolute -inset-0.5 bg-linear-to-r from-red-600 to-red-400 rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200 animate-pulse">
                </div>
            @endif

            <div
                class="relative bg-base-100 border {{ $cart->active ? 'border-red-600/30 shadow-2xl shadow-red-900/10' : 'border-base-content/5 shadow-xl shadow-base-content/5' }} rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:-translate-y-2">

                {{-- Header with Icon & Active Toggle --}}
                <div class="p-6 pb-0 flex items-center justify-between">
                    <div
                        class="w-12 h-12 rounded-2xl bg-base-content/5 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-500">
                        <x-mary-icon name="o-shopping-cart" class="w-6 h-6" />
                    </div>

                    <div class="flex items-center gap-2">
                        @if ($cart->active)
                            <span
                                class="text-[8px] font-black uppercase tracking-[0.2em] text-red-600 animate__animated animate__pulse animate__infinite">Activo</span>
                        @endif
                        <div class="form-control">
                            <label class="label cursor-pointer p-0">
                                <input type="checkbox"
                                    class="toggle toggle-sm {{ $cart->active ? 'toggle-error' : 'opacity-30' }}"
                                    {{ $cart->active ? 'checked disabled' : '' }} wire:loading.attr="disabled"
                                    wire:click="active_cart('{{ $cart->id }}')" />
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <h3 class="text-xl font-black uppercase tracking-tighter truncate mb-1">
                        {{ $cart->name }}
                    </h3>

                    <div class="flex items-center gap-2 mb-6">
                        <div class="h-1 w-8 bg-red-600 rounded-full"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">
                            {{ $cart->products_count }} Productos
                        </span>
                    </div>

                    {{-- Actions Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <x-mary-button icon="o-eye" label="Ver" :link="route('carts.show', ['cart' => $cart->id])"
                            class="btn-secondary btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl flex-1" />
                        <x-mary-button icon="o-pencil" label="Editar"
                            @click="$wire.myModal1 = true; $wire.cart_id = '{{ $cart->id }}'; $wire.name = '{{ $cart->name }}'"
                            class="btn-ghost btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl flex-1 bg-base-content/5 hover:bg-base-content/10" />
                        <x-mary-button icon="o-trash" label="Vaciar" :disabled="$cart->products_count === 0"
                            wire:click="void_cart('{{ $cart->id }}')"
                            wire:confirm='¿Desea vaciar el carrito {{ $cart->name }}?'
                            class="col-span-2 btn-error btn-outline btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl w-full border-red-600/20 hover:bg-red-600/10 hover:border-red-600/40 text-red-600" />
                    </div>
                </div>

                {{-- Glass Shine Effect --}}
                <div
                    class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-transparent via-red-600/10 to-transparent">
                </div>
            </div>
        </div>
    @endforeach

</section>
