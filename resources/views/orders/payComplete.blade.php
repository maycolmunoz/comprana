<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Estado de compra" />
    </x-slot>

    @php
        $isApproved = $status === 'approved';
        $isPending = in_array($status, ['pending', 'in_process'], true) && filled($order->payment_id);
        $isRefunded = $status === 'refunded';
        $isChargedBack = $status === 'charged_back';
        $isInMediation = $status === 'in_mediation';
        $isMissing = $status === null
            || (in_array($status, ['pending', 'in_process'], true) && blank($order->payment_id));
        $canRetry = $isMissing || in_array($status, ['rejected', 'cancelled'], true);

        $title = match (true) {
            $isApproved => 'Pago aprobado',
            $isPending => 'Pago pendiente',
            $isRefunded => 'Pago reembolsado',
            $isChargedBack => 'Pago con contracargo',
            $isInMediation => 'Pago en mediación',
            $isMissing => 'Pago no iniciado',
            default => 'Pago rechazado',
        };

        $description = match (true) {
            $isApproved => 'Tu compra se realizó correctamente. Ya puedes consultar el estado de tu pedido.',
            $isPending => 'Mercado Pago está procesando el pago. Si te pidió completar un paso, hazlo desde su correo o app; actualizaremos el estado automáticamente.',
            $isRefunded => 'El pago fue reembolsado. Si tienes dudas, contacta con nuestro equipo de soporte.',
            $isChargedBack => 'Mercado Pago reportó un contracargo. Contacta con nuestro equipo de soporte para revisar el caso.',
            $isInMediation => 'Mercado Pago abrió una mediación sobre este pago. Contacta con nuestro equipo de soporte para revisar el caso.',
            $isMissing => 'Aún no registramos un pago para este pedido. Si acabas de pagar, espera unos segundos y actualiza el estado antes de intentarlo de nuevo.',
            default => 'El pago no se completó. Puedes reintentarlo con otro medio de pago.',
        };

        $icon = match (true) {
            $isApproved => 'o-check-circle',
            $isPending => 'o-clock',
            $isRefunded => 'o-arrow-uturn-left',
            $isChargedBack => 'o-exclamation-triangle',
            $isInMediation => 'o-exclamation-triangle',
            $isMissing => 'o-credit-card',
            default => 'o-x-circle',
        };

        $statusClasses = match (true) {
            $isApproved => 'bg-success/10 text-success',
            $isPending => 'bg-warning/10 text-warning',
            $isRefunded => 'bg-info/10 text-info',
            $isChargedBack => 'bg-error/10 text-error',
            $isInMediation => 'bg-warning/10 text-warning',
            $isMissing => 'bg-base-content/5 text-base-content/60',
            default => 'bg-error/10 text-error',
        };
    @endphp

    <div class="mx-auto max-w-2xl px-6 py-16">
        <x-base.section-card>
            <div class="flex flex-col items-start gap-6 sm:flex-row">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 {{ $statusClasses }}">
                    <x-mary-icon :name="$icon" class="h-7 w-7" />
                </div>

                <div>
                    <h2 class="text-2xl font-black tracking-tighter uppercase">{{ $title }}</h2>
                    <p class="text-base-content/60 mt-2 text-sm leading-relaxed">{{ $description }}</p>
                    <p class="text-base-content/40 mt-2 text-xs">
                        Pedido #ORD-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }} · Total ${{ number_format((int) $order->total, 0, ',', '.') }}
                    </p>

                    @if ($isPending)
                        <p id="payment-refresh-message" class="text-base-content/40 mt-4 text-xs" aria-live="polite">
                            Actualizando el estado automáticamente…
                        </p>
                    @endif

                    <div class="mt-6 flex flex-wrap gap-3">
                        @if ($canRetry)
                            <x-mary-button
                                :link="route('orders.pay', ['order' => $order])"
                                :label="$isMissing ? 'Pagar ahora' : 'Reintentar pago'"
                                icon="o-credit-card"
                                class="btn-primary rounded-2xl text-xs font-black tracking-widest uppercase"
                            />
                        @endif

                        <x-mary-button
                            :link="route('orders.index')"
                            label="Ver mis pedidos"
                            icon="o-document-text"
                            class="rounded-2xl text-xs font-black tracking-widest uppercase {{ $canRetry ? 'btn-ghost' : 'btn-primary' }}"
                        />

                        @if ($isApproved && $order->invoice)
                            <x-mary-button
                                :link="route('orders.invoice', ['name' => $order->invoice])"
                                label="Factura PDF"
                                icon="o-document-text"
                                external
                                class="btn-ghost rounded-2xl text-xs font-black tracking-widest uppercase"
                            />
                        @endif

                        @if ($isMissing)
                            <x-mary-button
                                label="Actualizar estado"
                                icon="o-arrow-path"
                                onclick="window.location.reload()"
                                class="btn-ghost rounded-2xl text-xs font-black tracking-widest uppercase"
                            />
                        @endif

                        @if ($isPending)
                            <x-mary-button
                                id="refresh-payment"
                                label="Actualizar estado"
                                icon="o-arrow-path"
                                onclick="window.location.reload()"
                                class="btn-ghost hidden rounded-2xl text-xs font-black tracking-widest uppercase"
                            />
                        @endif
                    </div>
                </div>
            </div>
        </x-base.section-card>
    </div>

    @if ($isPending)
        <script>
            (() => {
                const statusUrl = @json(route('orders.payment-status', $order));
                const refreshMessage = document.getElementById('payment-refresh-message');
                const refreshButton = document.getElementById('refresh-payment');
                const maxAttempts = 18;
                let attempts = 0;
                let timer = null;

                async function refreshPaymentStatus() {
                    try {
                        const response = await fetch(statusUrl, {
                            headers: { Accept: 'application/json' },
                        });

                        if (!response.ok) {
                            return;
                        }

                        const data = await response.json();

                        if (data.paid || ['rejected', 'cancelled', 'refunded'].includes(data.status)) {
                            clearInterval(timer);
                            window.location.reload();

                            return;
                        }

                        attempts++;

                        if (attempts >= maxAttempts) {
                            clearInterval(timer);
                            refreshMessage.textContent = 'Seguimos esperando la confirmación de Mercado Pago.';
                            refreshButton.classList.remove('hidden');
                        }
                    } catch {
                        // A network hiccup should not break the page; retry on the next tick.
                    }
                }

                timer = setInterval(refreshPaymentStatus, 10000);
            })();
        </script>
    @endif
</x-app-layout>
