@php
    $sectionNull = $section === '' ? true : false;
    $searchNull = $search === '' ? true : false;
@endphp

<div class="container px-6 mx-auto">
    {{-- --- header --- --}}
    <h3 class="text-2xl font-medium text-gray-700">Sección: {{$sectionNull ? 'Todos' :$section }}</h3>
    <span class="mt-3 text-gray-500 text-md">{{$searchNull  ? '' : "Busqueda: {$search}" }}</span>

    {{-- -----order price----- --}}
    @if(!$searchNull)
    @php
    $orderAsc = $order === 'asc' ? true : false;
    $orderDesc = $order === 'desc' ? true : false;
    @endphp

    <div class="flex items-center rounded-md shadow-xs">
        <span class="text-sm text-gray-500 ">Ordenar por Precio: </span>
        <button wire:click="$set('order', 'asc')" {{$orderAsc ? 'disabled' : '' }}
            class="{{$orderAsc ? 'bg-slate-100 border text-blue-600 ': 'text-slate-800' }} block w-[120px] text-center   hover:text-blue-600 text-sm bg-white hover:bg-slate-100 border border-slate-200 rounded-l-lg font-medium px-2 py-1  items-center">
            menor
        </button>
        <button wire:click="$set('order', 'desc')" {{$orderDesc ? 'disabled' : '' }}
            class="{{$orderDesc ? 'bg-slate-100 border text-blue-600 ': 'text-slate-800' }} block w-[120px] text-center hover:text-blue-600 text-sm bg-white hover:bg-slate-100 border border-slate-200 rounded-r-lg font-medium px-2 py-1  items-center">
            mayor
        </button>
    </div>
    @endif

    @if(!$searchNull || !$sectionNull )
    {{-- ---clean filters--- --}}
    <a wire:navigation href="{{route('products.index')}}"
        class="block w-[120px] text-center  text-slate-800 hover:text-blue-600 text-sm bg-white hover:bg-slate-100 border border-slate-200 rounded-l-lg font-medium px-2 py-1  items-center">
        Limpiar filtros
    </a>
    @endif

    {{-- -----products----- --}}
    @if ($this->products->count())
    <div class="grid grid-cols-1 gap-6 my-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 ">
        @foreach ($this->products as $product)
        <x-products.product-item :$product wire:key='{{$product->id}}'/>
        @endforeach
    </div>
    @else
    <x-commons.null />
    @endif

    {{-- pagination --}}
    <div class="flex flex-col items-center ">
        {{$this->products->links()}}
    </div>
</div>