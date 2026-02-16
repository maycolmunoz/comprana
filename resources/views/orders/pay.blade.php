 <?php
    
        use MercadoPago\MercadoPagoConfig;
        use MercadoPago\Client\Preference\PreferenceClient;
       
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
    
        $client = new PreferenceClient();
    
    
        foreach($cart->products as $product){
            $item = new stdClass();
            $item->title = $product->name;
            $item->quantity = $product->pivot->cant;
            $item->unit_price = $product->price;

            $products[] = $item;
        }

        $preference = $client->create([
            "items"=> $products,
            "back_urls" =>array(
            "success" => route('orders.complete', ['order'=>$order]),
            "failure" => route('orders.pay', [$cart, $order]),
            "pending" => route('orders.complete', ['order'=>$order]),
            ),
            "auto_return"=> "approved",
            "payment_methods" => array(
                "excluded_payment_types" => array(
                array("id" => "ticket")
            ),
             "installments" => 1
            ),
            "notification_url"=> route('webhooks', ['order'=>$order])
            ,
        ]);

        $total = 0;
?>
        <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Pagar Pedido') }}
        </h2>
    </x-slot>

    <div class="container px-6 mx-auto">  
        <div class="flex flex-col my-10 lg:flex-row">
            <div class="order-2 w-full lg:w-1/2">


                {{-- left --}}
                <div class="flex flex-col my-5">
                    <div class="overflow-x-auto ">
                        <div class="inline-block min-w-full py-2 sm:px-1 lg:px-2">
                            <div class="overflow-hidden">
                                <table class="min-w-full">
                                    <thead class="bg-white border-b">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                                Nombre
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                                Precio
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                                Cantidad
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart->products as $product)
                                        @php
                                            $total += $product->pivot->cant * $product->price;
                                        @endphp
                                        <tr class="bg-gray-100 border-b">
                                            <td class="px-6 py-2 text-sm font-medium text-gray-900 whitespace-nowrap">
                                                {{ $product->name }}
                                            </td>
                                            <td class="px-6 py-2 text-sm font-light text-gray-900 whitespace-nowrap">
                                                {{ $product->price }}
                                            </td>
                                            <td
                                                class="px-6 py-2 text-sm font-light text-center text-gray-900 whitespace-nowrap">
                                                {{ $product->pivot->cant }}
                                            </td>
                                            <td class="px-6 py-2 text-sm font-light text-gray-900 whitespace-nowrap">
                                                {{ $product->pivot->cant * $product->price }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right --}}
            <div class="shrink-0 order-1 w-full mb-8 lg:w-1/2 lg:mb-0 lg:order-2">
                <div class="flex justify-center lg:justify-end">
                    <div class="w-full max-w-md px-4 py-3 bg-white border rounded-md">
                        <div class="flex items-center justify-between">
                            <h3 class="font-medium text-gray-700">Total a Pagar: $ {{$total}} </h3>
                        </div>

                        <div id="wallet_container"> </div>


                    </div>
                </div>



            </div>

        </div>

    </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
    const mp = new MercadoPago("{{config('services.mercadopago.key')}}");
    const bricksBuilder = mp.bricks();


    mp.bricks().create("wallet", "wallet_container", {
    initialization: {
        preferenceId: '{{ $preference->id }}',
    },
    customization: {
    texts: {
    valueProp: 'smart_option',
    },
    },
    });
    </script>
    
</x-app-layout>
