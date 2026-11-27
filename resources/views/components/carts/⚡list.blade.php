<?php

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component
{
    public bool $showRenameModal = false;

    public ?int $editingCartId = null;

    public string $cartName = '';

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'cartName' => [
                'required',
                'string',
                'min:2',
                'max:25',
                Rule::unique('carts', 'name')
                    ->where('user_id', Auth::id())
                    ->ignore($this->editingCartId),
            ],
        ];
    }

    public function activateCart(int $cartId): void
    {
        $cart = Cart::whereKey($cartId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $cart) {
            return;
        }

        Cart::where('user_id', Auth::id())->update(['active' => false]);
        $cart->update(['active' => true]);
    }

    public function clearCart(int $cartId): void
    {
        $cart = Cart::whereKey($cartId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $cart) {
            return;
        }

        $this->authorize('author', $cart);
        $cart->products()->detach();

        $this->dispatch('notification', 'Se vació el carrito');
    }

    public function renameCart(): void
    {
        $this->validate();

        $cart = Cart::whereKey($this->editingCartId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $cart) {
            return;
        }

        $cart->update(['name' => $this->cartName]);

        $this->dispatch('notification', 'Se editó el carrito');
        $this->showRenameModal = false;
        $this->reset('cartName', 'editingCartId');
    }

    public function with(): array
    {
        return [
            'carts' => Cart::where('user_id', Auth::id())
                ->withCount('products')
                ->orderByDesc('active')
                ->get(),
        ];
    }
}; ?>

<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

    {{-- Edit Cart Name Modal --}}
    <x-mary-modal wire:model="showRenameModal" title="Renombrar carrito" class="backdrop-blur-xl">
        <form wire:submit.prevent="renameCart" class="space-y-6">
            <x-mary-input label="Nuevo nombre" wire:model="cartName" icon="o-pencil-square"
                class="bg-base-content/5 border-none focus:ring-red-600/20" />

            <div class="flex gap-3 pt-4">
                <x-mary-button label="Cancelar" @click="$wire.showRenameModal = false"
                    class="btn-ghost flex-1 font-black text-xs uppercase tracking-widest rounded-xl" />
                <x-mary-button type="submit" label="Actualizar"
                    class="btn-primary flex-1 font-black text-xs uppercase tracking-widest rounded-xl" />
            </div>
        </form>
    </x-mary-modal>

    @if ($carts->isEmpty())
        <div class="col-span-full">
            <x-base.empty-state icon="o-shopping-cart" title="No tienes carritos"
                description="Explora la tienda y comienza a agregar productos."
                :link="route('products.index')" link-label="Explorar catálogo" />
        </div>
    @endif

    {{-- Cart Cards Grid --}}
    @foreach ($carts as $cart)
        <div class="group relative">
            {{-- Active Glow Effect --}}
            @if ($cart->active)
                <div
                    class="absolute -inset-0.5 bg-linear-to-r from-red-600 to-red-400 rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200">
                </div>
            @endif

            <div wire:key="cart-{{ $cart->id }}"
                class="relative bg-base-100 border {{ $cart->active ? 'border-red-600/30 shadow-2xl shadow-red-900/10' : 'border-base-content/5 shadow-xl shadow-base-content/5' }} rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:-translate-y-2">

                {{-- Header with Icon & Active Toggle --}}
                <div class="p-6 pb-0 flex items-center justify-between">
                    <div
                        class="w-12 h-12 rounded-2xl bg-base-content/5 flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform duration-500">
                        <x-mary-icon name="o-shopping-cart" class="w-6 h-6" />
                    </div>

                    <div class="flex items-center gap-2">
                        @if ($cart->active)
                            <span class="text-[8px] font-black uppercase tracking-[0.2em] text-red-600">Activo</span>
                        @endif
                        <div class="form-control">
                            <label class="label cursor-pointer p-0">
                                <input type="checkbox"
                                    class="toggle toggle-sm {{ $cart->active ? 'toggle-error' : 'opacity-30' }}"
                                    {{ $cart->active ? 'checked disabled' : '' }} wire:loading.attr="disabled"
                                    wire:click="activateCart({{ $cart->id }})" />
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <h2 class="text-xl font-black uppercase tracking-tighter truncate mb-1">
                        {{ $cart->name }}
                    </h3>

                    <div class="flex items-center gap-2 mb-6">
                        <div class="h-1 w-8 bg-red-600 rounded-full"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">
                            {{ $cart->products_count }} {{ $cart->products_count === 1 ? 'producto' : 'productos' }}
                        </span>
                    </div>

                    {{-- Actions Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <x-mary-button icon="o-eye" label="Ver" :link="route('carts.show', ['cart' => $cart->id])"
                            class="btn-secondary btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl flex-1" />
                        <x-mary-button icon="o-pencil" label="Editar"
                            @click="$wire.showRenameModal = true; $wire.editingCartId = {{ $cart->id }}; $wire.cartName = @js($cart->name)"
                            class="btn-ghost btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl flex-1 bg-base-content/5 hover:bg-base-content/10" />
                        <x-mary-button icon="o-trash" label="Vaciar" :disabled="$cart->products_count === 0"
                            wire:click="clearCart({{ $cart->id }})"
                            wire:confirm="¿Deseas vaciar este carrito?"
                            class="col-span-2 btn-error btn-outline btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl w-full border-red-600/20 hover:bg-red-600/10 hover:border-red-600/40 text-red-600" />
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</section>
