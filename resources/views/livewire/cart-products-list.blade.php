

<div x-data="{pay: 0}"
class="pt-5 bg-gray-100 ">
    <h1 class="mb-5 text-2xl font-bold text-center">Productos del carrito: {{$cart->name}}</h1>

    @if($cart->products->count())
      <div class="justify-center max-w-5xl px-6 mx-auto md:flex md:space-x-6 xl:px-0">
        <div class="rounded-lg md:w-2/3">
          @foreach ($cart->products as $product)
          <livewire:cart-product :key='$product->id' :$product :cant="$product->pivot->cant" :$cart >
          @endforeach
        </div>
        
        <!-- Sub total -->
        <div class="h-full p-6 mt-6 bg-white border rounded-lg shadow-md md:mt-0 md:w-1/3">
          <div class="flex justify-between mb-2">
            <p class="text-gray-700">Productos:</p>
            <p class="text-gray-700 ">{{$cart->products->count()}}</p>
          </div>
          <hr class="my-4" />
          <div class="flex justify-between">
            <p class="text-lg font-bold">Total</p>
            <div class="">
              <p class="mb-1 text-lg font-bold text-center ">$ <span x-text="pay" ></span></p>
              <p class="text-sm text-green-500">Incluye el envío.</p>
            </div>
          </div>
          <a href="{{route('orders.create', ['id'=> $cart->id])}}"
           class="block text-center mt-6 w-full rounded-md bg-blue-500 py-1.5 font-medium text-blue-50 hover:bg-blue-600">
           Comprar
        </a>
        </div>
      </div>

    @else
    <x-commons.null/>
    @endif
  </div>








