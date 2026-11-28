<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Pagar pedido" />
    </x-slot>

    <div class="mx-auto max-w-7xl px-6 py-12">
        <div class="mb-8">
            <h2 class="text-2xl font-black tracking-tighter uppercase">Resumen del pedido</h2>
            <p class="text-base-content/50 mt-2 text-sm">Revisa tu compra antes de completar el pago.</p>
        </div>

        @if ($error)
            <div class="border-error/20 bg-error/5 mb-8 rounded-3xl border p-6">
                <div class="flex items-start gap-4">
                    <x-mary-icon name="o-exclamation-triangle" class="text-error h-6 w-6 shrink-0" />

                    <div>
                        <h3 class="text-lg font-black tracking-tighter uppercase">No pudimos iniciar el pago</h3>
                        <p class="text-base-content/60 mt-2 text-sm">{{ $error }}</p>

                        <div class="mt-5 flex flex-wrap gap-3">
                            <x-mary-button
                                :link="route('orders.pay', ['order' => $order])"
                                label="Reintentar"
                                icon="o-arrow-path"
                                class="btn-primary rounded-xl text-[10px] font-black tracking-widest uppercase"
                            />
                            <x-mary-button
                                :link="route('orders.index')"
                                label="Ver mis pedidos"
                                icon="o-document-text"
                                class="btn-ghost rounded-xl text-[10px] font-black tracking-widest uppercase"
                            />
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2">
            <x-base.section-card>
                <h3 class="mb-6 text-xl font-black tracking-tighter uppercase">Productos</h3>

                <div class="divide-base-content/5 divide-y">
                    @forelse ($order->items ?? [] as $item)
                        <div class="flex items-center justify-between gap-4 py-4">
                            <div class="min-w-0">
                                <p class="truncate font-bold">{{ $item['title'] ?? 'Producto' }}</p>
                                <p class="text-base-content/50 mt-1 text-xs">
                                    {{ $item['quantity'] ?? 0 }} ×
                                    <x-base.price :value="$item['unit_price'] ?? 0" class="text-xs font-normal" />
                                </p>
                            </div>

                            <x-base.price
                                :value="($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0)"
                                class="font-black tracking-tighter"
                            />
                        </div>
                    @empty
                        <p class="text-base-content/50 py-4 text-sm">No hay productos en este pedido.</p>
                    @endforelse
                </div>

                <div class="border-base-content/5 mt-4 flex items-end justify-between border-t pt-6">
                    <span class="text-sm font-black tracking-tighter uppercase">Total</span>
                    <x-base.price
                        :value="$order->total ?? 0"
                        class="text-3xl font-black tracking-tighter text-red-600"
                    />
                </div>
            </x-base.section-card>

            <x-base.section-card>
                <h3 class="mb-2 text-xl font-black tracking-tighter uppercase">Pago seguro</h3>
                <p class="text-base-content/60 mb-6 text-sm">
                    Te llevaremos a Mercado Pago para completar el pago de forma segura. Al terminar, volverás aquí para
                    ver el estado de tu pedido.
                </p>

                @if (in_array($order->payment_status, ['rejected', 'cancelled'], true))
                    <div class="border-error/20 bg-error/5 mb-6 rounded-2xl border p-4" role="alert">
                        <p class="text-sm font-bold">
                            Tu último pago fue {{ $order->payment_status === 'rejected' ? 'rechazado' : 'cancelado' }}.
                        </p>
                        <p class="text-base-content/60 mt-1 text-sm">
                            Puedes intentarlo de nuevo con otro medio de pago.
                        </p>
                    </div>
                @endif

                @if ($checkoutUrl)
                    <a
                        href="{{ $checkoutUrl }}"
                        class="btn btn-primary w-full rounded-xl text-xs font-black tracking-widest uppercase"
                    >
                        <x-mary-icon name="o-credit-card" class="h-5 w-5" />
                        Pagar con Mercado Pago
                    </a>

                    <div class="mt-4 flex flex-col items-center gap-3">
                        <x-mary-button
                            :link="route('orders.index')"
                            label="Volver a mis pedidos"
                            icon="o-document-text"
                            class="btn-ghost rounded-xl text-[10px] font-black tracking-widest uppercase"
                        />

                        <a
                            href="{{ route('orders.complete', ['order' => $order]) }}"
                            class="text-base-content/40 text-[10px] font-black tracking-widest uppercase underline underline-offset-4 transition-colors hover:text-red-600"
                        >
                            ¿Ya pagaste? Verificar estado
                        </a>
                    </div>
                @elseif (! $error)
                    <p class="text-base-content/50 text-sm">El pago no está disponible en este momento.</p>
                @endif
            </x-base.section-card>
        </div>
    </div>
</x-app-layout>
