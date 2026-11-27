<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Pagar pedido" />
    </x-slot>

    <div class="mx-auto max-w-7xl px-6 py-12">
        <div class="mb-8">
            <h2 class="text-2xl font-black tracking-tighter uppercase">Resumen del pedido</h2>
            <p class="text-base-content/50 mt-2 text-sm">Revisa tu compra antes de completar el pago.</p>
        </div>

        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2">
            <x-base.section-card>
                <h3 class="mb-6 text-xl font-black tracking-tighter uppercase">Productos</h3>

                <div class="divide-base-content/5 divide-y">
                    @foreach ($cart->products as $product)
                        <div class="flex items-center justify-between gap-4 py-4">
                            <div class="min-w-0">
                                <p class="truncate font-bold">{{ $product->name }}</p>
                                <p class="text-base-content/50 mt-1 text-xs">
                                    {{ $product->pivot->cant }} ×
                                    <x-base.price :value="$product->price" class="text-xs font-normal" />
                                </p>
                            </div>

                            <x-base.price
                                :value="$product->pivot->cant * $product->price"
                                class="font-black tracking-tighter"
                            />
                        </div>
                    @endforeach
                </div>

                <div class="border-base-content/5 mt-4 flex items-end justify-between border-t pt-6">
                    <span class="text-sm font-black tracking-tighter uppercase">Total</span>
                    <x-base.price
                        :value="$order->total ?? $cart->total()"
                        class="text-3xl font-black tracking-tighter text-red-600"
                    />
                </div>
            </x-base.section-card>

            <x-base.section-card>
                <h3 class="mb-2 text-xl font-black tracking-tighter uppercase">Pago seguro</h3>
                <p class="text-base-content/60 mb-6 text-sm">
                    Paga con Mercado Pago. Serás redirigido a su plataforma segura para completar la compra.
                </p>

                <div id="wallet_container"></div>
            </x-base.section-card>
        </div>
    </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        const mp = new MercadoPago("{{ config('services.mercadopago.key') }}");

        mp.bricks().create('wallet', 'wallet_container', {
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
