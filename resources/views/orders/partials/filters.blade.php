<div class="w-full flex justify-center mx-auto">
    <ul class="flex gap-3 my-2 flex-wrap justify-center px-4 md:px-8 max-w-lg">
        <li class="px-2 py-1 md:text-lg relative bg-gray-200 rounded-lg select-none hover:shadow-sm hover:shadow-teal-500 hover:outline-solid hover:outline-teal-600 hover:cursor-pointer
        {{!request()->has('status') ? 'outline-solid outline-teal-600': '' }}
        ">
            <a href="{{route('orders.index')}}" wire:navigate>
                Todos
            </a>
        </li>
        @foreach ($orderStatus as $status)
         <li class="px-2 py-1 md:text-lg relative bg-gray-200 rounded-lg select-none hover:shadow-sm hover:shadow-teal-500 hover:outline-solid hover:outline-teal-600 hover:cursor-pointer
        {{request('status') === $status ? 'outline-solid outline-teal-600': '' }}
        ">
            <a href="{{route('orders.index', ['status'=> $status])}}" wire:navigate>
                {{$status}}
            </a>
        </li>
        @endforeach
    </ul>
</div>