<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Pagar pedido" />
    </x-slot>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="mb-8">
            <h2 class="text-2xl font-black uppercase tracking-tighter">Resumen del pedido</h2>
            <p class="text-sm text-base-content/50 mt-2">Revisa tu compra antes de completar el pago.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <x-base.section-card>
                <h3 class="text-xl font-black uppercase tracking-tighter mb-6">Productos</h3>

                <div class="divide-y divide-base-content/5">
                    @foreach ($cart->products as $product)
                        <div class="flex items-center justify-between gap-4 py-4">
                            <div class="min-w-0">
                                <p class="font-bold truncate">{{ $product->name }}</p>
                                <p class="text-xs text-base-content/50 mt-1">
                                    {{ $product->pivot->cant }} ×
                                    <x-base.price :value="$product->price" class="text-xs font-normal" />
                                </p>
                            </div>

                            <x-base.price :value="$product->pivot->cant * $product->price"
                                class="font-black tracking-tighter" />
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-base-content/5 mt-4 pt-6 flex items-end justify-between">
                    <span class="text-sm font-black uppercase tracking-tighter">Total</span>
                    <x-base.price :value="$order->total ?? $cart->total()"
                        class="text-3xl font-black tracking-tighter text-red-600" />
                </div>
            </x-base.section-card>

            <x-base.section-card>
                <h3 class="text-xl font-black uppercase tracking-tighter mb-2">Pago seguro</h3>
                <p class="text-sm text-base-content/60 mb-6">
                    Paga con Mercado Pago. Serás redirigido a su plataforma segura para completar la compra.
                </p>

                <div id="wallet_container"></div>
            </x-base.section-card>
        </div>
    </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        const mp = new MercadoPago("{{ config('services.mercadopago.key') }}");

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
