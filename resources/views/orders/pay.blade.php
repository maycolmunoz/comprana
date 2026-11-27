<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Pagar pedido" />
    </x-slot>

    <div class="container px-6 mx-auto">
        <div class="flex flex-col my-10 lg:flex-row">
            {{-- Order Details --}}
            <div class="order-2 w-full lg:w-1/2">
                <div class="flex flex-col my-5">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full py-2 sm:px-1 lg:px-2">
                            <div class="overflow-hidden">
                                <table class="min-w-full">
                                    <thead class="bg-base-200 border-b border-base-content/5">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-6 py-4 text-sm font-normal text-left text-base-content">
                                                Nombre
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-4 text-sm font-normal text-left text-base-content">
                                                Precio
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-4 text-sm font-normal text-left text-base-content">
                                                Cantidad
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-4 text-sm font-normal text-left text-base-content">
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart->products as $product)
                                            <tr class="bg-base-100 border-b border-base-content/5">
                                                <td class="px-6 py-2 text-sm font-normal text-base-content whitespace-nowrap">
                                                    {{ $product->name }}
                                                </td>
                                                <td class="px-6 py-2 text-sm font-normal text-base-content whitespace-nowrap">
                                                    {{ $product->price }}
                                                </td>
                                                <td
                                                    class="px-6 py-2 text-sm font-normal text-center text-base-content whitespace-nowrap">
                                                    {{ $product->pivot->cant }}
                                                </td>
                                                <td class="px-6 py-2 text-sm font-normal text-base-content whitespace-nowrap">
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

            {{-- Payment Box --}}
            <div class="shrink-0 order-1 w-full mb-8 lg:w-1/2 lg:mb-0 lg:order-2">
                <div class="flex justify-center lg:justify-end">
                    <div class="w-full max-w-md px-4 py-3 bg-base-100 border border-base-content/10 rounded-2xl">
                        <div class="flex items-center justify-between">
                            <h3 class="font-normal text-base-content">Total a pagar: $ {{ $order->total }}</h3>
                        </div>

                        <div id="wallet_container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
    const mp = new MercadoPago("{{ config('services.mercadopago.key') }}");
    const bricksBuilder = mp.bricks();

    mp.bricks().create("wallet", "wallet_container", {
        initialization: {
            preferenceId: '{{ $preference['id'] }}',
        },
        customization: {
            texts: {
                valueProp: 'smart_option',
            },
        },
    });
    </script>
</x-app-layout>
