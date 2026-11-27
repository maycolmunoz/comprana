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

    public function editCart(int $cartId): void
    {
        $cart = Cart::whereKey($cartId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $cart) {
            return;
        }

        $this->editingCartId = $cart->id;
        $this->cartName = $cart->name;
        $this->resetValidation();
        $this->showRenameModal = true;
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

<section class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    {{-- Edit Cart Name Modal --}}
    <x-mary-modal wire:model="showRenameModal" title="Renombrar carrito" class="backdrop-blur-xl">
        <form wire:submit.prevent="renameCart" class="space-y-6">
            <x-mary-input
                label="Nuevo nombre"
                wire:model="cartName"
                icon="o-pencil-square"
                class="bg-base-content/5 border-none focus:ring-red-600/20"
            />

            <div class="flex gap-3 pt-4">
                <x-mary-button
                    label="Cancelar"
                    @click="$wire.showRenameModal = false"
                    class="btn-ghost flex-1 rounded-xl text-xs font-black tracking-widest uppercase"
                />
                <x-mary-button
                    type="submit"
                    label="Actualizar"
                    class="btn-primary flex-1 rounded-xl text-xs font-black tracking-widest uppercase"
                />
            </div>
        </form>
    </x-mary-modal>

    @if ($carts->isEmpty())
        <div class="col-span-full">
            <x-base.empty-state
                icon="o-shopping-cart"
                title="No tienes carritos"
                description="Explora la tienda y comienza a agregar productos."
                :link="route('products.index')"
                link-label="Explorar catálogo"
            />
        </div>
    @endif

    {{-- Cart Cards Grid --}}
    @foreach ($carts as $cart)
        <div class="group relative">
            {{-- Active Glow Effect --}}
            @if ($cart->active)
                <div class="absolute -inset-0.5 rounded-[2.5rem] bg-linear-to-r from-red-600 to-red-400 opacity-20 blur transition duration-1000 group-hover:opacity-40 group-hover:duration-200"></div>
            @endif

            <div
                wire:key="cart-{{ $cart->id }}"
                class="relative bg-base-100 border {{ $cart->active ? 'border-red-600/30 shadow-2xl shadow-red-900/10' : 'border-base-content/5 shadow-xl shadow-base-content/5' }} rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:-translate-y-2"
            >
                {{-- Header with Icon & Active Toggle --}}
                <div class="flex items-center justify-between p-6 pb-0">
                    <div class="bg-base-content/5 flex h-12 w-12 items-center justify-center rounded-2xl text-red-600 transition-transform duration-500 group-hover:scale-110">
                        <x-mary-icon name="o-shopping-cart" class="h-6 w-6" />
                    </div>

                    <div class="flex items-center gap-2">
                        @if ($cart->active)
                            <span class="text-[8px] font-black tracking-[0.2em] text-red-600 uppercase">Activo</span>
                        @endif
                        <div class="form-control">
                            <label class="label cursor-pointer p-0">
                                <input
                                    type="checkbox"
                                    class="toggle toggle-sm {{ $cart->active ? 'toggle-error' : 'opacity-30' }}"
                                    {{ $cart->active ? 'checked disabled' : '' }}
                                    wire:loading.attr="disabled"
                                    wire:click="activateCart({{ $cart->id }})"
                                />
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    <h2 class="mb-1 truncate text-xl font-black tracking-tighter uppercase">{{ $cart->name }}</h2>

                    <div class="mb-6 flex items-center gap-2">
                        <div class="h-1 w-8 rounded-full bg-red-600"></div>
                        <span class="text-base-content/40 text-[10px] font-bold tracking-widest uppercase">
                            {{ $cart->products_count }} {{ $cart->products_count === 1 ? 'producto' : 'productos' }}
                        </span>
                    </div>

                    {{-- Actions Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <x-mary-button
                            icon="o-eye"
                            label="Ver"
                            :link="route('carts.show', ['cart' => $cart->id])"
                            class="btn-secondary btn-sm flex-1 rounded-xl text-[10px] font-black tracking-widest uppercase"
                        />
                        <x-mary-button
                            icon="o-pencil"
                            label="Editar"
                            wire:click="editCart({{ $cart->id }})"
                            class="btn-ghost btn-sm bg-base-content/5 hover:bg-base-content/10 flex-1 rounded-xl text-[10px] font-black tracking-widest uppercase"
                        />
                        <x-mary-button
                            icon="o-trash"
                            label="Vaciar"
                            :disabled="$cart->products_count === 0"
                            wire:click="clearCart({{ $cart->id }})"
                            wire:confirm="¿Deseas vaciar este carrito?"
                            class="btn-error btn-outline btn-sm col-span-2 w-full rounded-xl border-red-600/20 text-[10px] font-black tracking-widest text-red-600 uppercase hover:border-red-600/40 hover:bg-red-600/10"
                        />
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</section>
