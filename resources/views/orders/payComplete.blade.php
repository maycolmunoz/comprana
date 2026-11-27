<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Estado de compra" />
    </x-slot>

    @php
        $isApproved = $status === 'approved';
        $isPending = in_array($status, ['pending', 'in_process'], true);
        $isRefunded = $status === 'refunded';

        $title = match (true) {
            $isApproved => 'Pago aprobado',
            $isPending => 'Pago pendiente',
            $isRefunded => 'Pago reembolsado',
            default => 'Pago rechazado',
        };

        $description = match (true) {
            $isApproved => 'Tu compra se realizó correctamente. Ya puedes consultar el estado de tu pedido.',
            $isPending => 'Tu pago está pendiente de aprobación. Te avisaremos cuando se confirme.',
            $isRefunded => 'El pago fue reembolsado. Si tienes dudas, contacta con nuestro equipo de soporte.',
            default => 'El pago no se completó. Puedes intentarlo de nuevo desde tus pedidos.',
        };

        $icon = match (true) {
            $isApproved => 'o-check-circle',
            $isPending => 'o-clock',
            $isRefunded => 'o-arrow-uturn-left',
            default => 'o-x-circle',
        };

        $statusClasses = match (true) {
            $isApproved => 'bg-success/10 text-success',
            $isPending => 'bg-warning/10 text-warning',
            $isRefunded => 'bg-info/10 text-info',
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

                    <x-mary-button
                        :link="route('orders.index')"
                        label="Ver mis pedidos"
                        icon="o-document-text"
                        class="btn-primary mt-6 rounded-2xl text-xs font-black tracking-widest uppercase"
                    />
                </div>
            </div>
        </x-base.section-card>
    </div>
</x-app-layout>
