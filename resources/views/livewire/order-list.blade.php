<!-- component -->
<div class="container p-8 py-10 mx-auto antialiased ">
   <style>
      .blue{
         background-color: blue;
      }
      .yellow{
         background-color: rgb(252, 214, 0);
      }
      .green{
         background-color: green;
      }
      .gray{
         background-color: gray;
      }
   </style>
   @forelse ($this->orders as $order)
   @php
      $color = match($order->status){
         'Procesando' => 'blue',
         'En Camino' => 'yellow',
         'Entregado' => 'green',
         'No Entregado' => 'gray',
      }
   @endphp
   <div class="bg-gray-100  mx-auto border-gray-500 border rounded-xs text-gray-700 mb-2 h-30">
      <div class="flex flex-wrap p-3 border-l-8 border ">
         <div class="pr-3 ml-3 space-y-1 sm:border-r-2">
            <div class="text-sm font-semibold leading-5"><span class="text-xs font-normal leading-4 text-gray-500">
                  #</span> Order{{$order->id}}</div>

            <div class="text-sm font-semibold leading-5">
               {{$order->created_at->format('M j. g:i A') }}
            </div>
            <a href="{{route('orders.invoice', ['name'=> ($order->invoice ?? 'NN') ])}}" 
               class="text-sm font-semibold leading-5 underline text-purple-800 " target="blank"  >
               Factura ▶
            </a>
         </div>
         <div class="flex-1">
            <div class="pr-3 ml-3 space-y-1 sm:border-r-2">
               <div class="text-sm font-normal leading-4"><span class="text-xs font-normal leading-4 text-gray-500">
                     Teléfono</span>
                     {{$order->phone}}
               </div>
               <div class="text-sm font-normal leading-4"><span class="text-xs font-normal leading-4 text-gray-500">
                     Destino</span>
                     {{$order->address}}
               </div>
               <div class="text-sm font-normal leading-4"><span class="text-xs font-normal leading-4 text-gray-500">
                     Total</span>
                     $ {{$order->total}}
               </div>
               <div class="text-sm font-normal leading-4"><span class="text-xs font-normal leading-4 text-gray-500">
                     Estado de Pago</span>
                     {{$order->payment_status}}
               </div>
            </div>
         </div>
         <div>
            <div class="w-20 p-1 my-5 ml-3 {{$color}}">
               <div class="text-xs font-semibold leading-4 text-center text-yellow-100 uppercase">
                  {{$order->status}}
               </div>
            </div>
         </div>
      </div>
   </div>
   @empty
   <x-commons.null />
   @endforelse
   {{$this->orders->links()}}

</div>