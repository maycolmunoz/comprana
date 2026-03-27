@php
     $name = $getState() ? $getState(): 'NN';
@endphp

<a  href="{{route('orders.invoice', ['name'=> $name ])}}" target="blank" class="flex items-center gap-1 text-center text-sm  px-2 rounded-md hover:bg-gray-700 transition duration-300 ease-in-out transform hover:scale-110 text-white">
   {{$name}}
</a>