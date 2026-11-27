<?php

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $section = '';

    #[Url]
    public $order = 'asc';

    #[On('search')]
    public function updateSearch(string $value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    #[Computed]
    public function products()
    {
        $query = Product::search($this->search)->with('section:id,name');

        if ($this->section !== '') {
            $query->whereHas('section', function ($query) {
                $query->whereName($this->section);
            });
        }

        $this->order = in_array($this->order, ['asc', 'desc']) ? $this->order : 'asc';

        if ($this->search !== '' || $this->section !== '') {
            $query->orderBy('price', $this->order);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate(12);
    }
};
?>

@php
    $sectionNull = $section === '' ? true : false;
    $searchNull = $search === '' ? true : false;
@endphp

<div class="container mx-auto px-6">
    {{-- --- Header & Active Filters --- --}}
    <div class="mb-12 flex flex-col justify-between gap-6 md:flex-row md:items-end">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-bold tracking-[0.3em] text-red-600 uppercase opacity-60">Explorando</span>
                <div class="h-px grow bg-linear-to-r from-red-600/20 to-transparent"></div>
            </div>

            <h2 class="text-base-content flex items-center gap-3 text-3xl font-black tracking-tighter uppercase lg:text-4xl">
                @if ($sectionNull)
                    <x-mary-icon name="o-squares-2x2" class="h-8 w-8 text-red-600" />
                    Todos los productos
                @else
                    <x-mary-icon name="o-tag" class="h-8 w-8 text-red-600" />
                    {{ $section }}
                @endif
            </h2>

            @if (! $searchNull)
                <div class="bg-base-content/5 border-base-content/5 inline-flex items-center gap-2 rounded-full border px-3 py-1">
                    <x-mary-icon name="o-magnifying-glass" class="h-3 w-3 opacity-40" />
                    <span class="text-base-content/60 text-xs font-bold">Búsqueda: </span>
                    <span class="text-xs font-black text-red-600 uppercase">"{{ $search }}"</span>
                </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-4">
            {{-- --- Price Ordering --- --}}
            @if (! $searchNull || ! $sectionNull)
                <div class="bg-base-100 border-base-content/5 flex items-center rounded-2xl border p-1 shadow-sm">
                    <span class="px-4 text-[10px] font-black tracking-widest uppercase opacity-30">Precio</span>

                    @php
                        $orderAsc = $order === 'asc';
                        $orderDesc = $order === 'desc';
                    @endphp

                    <div class="flex gap-1">
                        <x-mary-button
                            wire:click="$set('order', 'asc')"
                            :disabled="$orderAsc"
                            label="Menor"
                            icon="o-bars-arrow-up"
                            class="btn-sm rounded-xl font-bold {{ $orderAsc ? 'btn-primary' : 'btn-ghost opacity-50' }}"
                        />

                        <x-mary-button
                            wire:click="$set('order', 'desc')"
                            :disabled="$orderDesc"
                            label="Mayor"
                            icon="o-bars-arrow-down"
                            class="btn-sm rounded-xl font-bold {{ $orderDesc ? 'btn-primary' : 'btn-ghost opacity-50' }}"
                        />
                    </div>
                </div>

                {{-- --- Clean Filters --- --}}
                <x-mary-button
                    link="{{ route('products.index') }}"
                    icon="o-x-mark"
                    label="Limpiar"
                    class="btn-ghost btn-sm rounded-xl font-bold text-red-600/60 hover:text-red-600"
                />
            @endif
        </div>
    </div>

    {{-- ----- Products Grid ----- --}}
    @if ($this->products->count())
        <div class="group/list grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($this->products as $product)
                <x-products.product-item :$product wire:key='{{ $product->id }}' />
            @endforeach
        </div>
    @else
        <x-base.empty-state
            icon="o-magnifying-glass"
            title="Sin coincidencias"
            description="No pudimos encontrar lo que buscas. Intenta con otros términos o secciones."
            :link="route('products.index')"
            link-label="Ver todos los productos"
        />
    @endif

    {{-- Pagination --}}
    <div class="border-base-content/5 mt-20 flex justify-center border-t py-10">
        {{ $this->products->links('pagination::tailwind') }}
    </div>
</div>
