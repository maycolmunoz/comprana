<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Estado de compra" />
    </x-slot>

    @php
        $isApproved = $status === 'approved';
        $isPending = in_array($status, ['pending', 'in_process'], true);

        $title = match (true) {
            $isApproved => 'Pago aprobado',
            $isPending => 'Pago pendiente',
            default => 'Pago rechazado',
        };

        $description = match (true) {
            $isApproved => 'Tu compra se realizó correctamente. Ya puedes consultar el estado de tu pedido.',
            $isPending => 'Tu pago está pendiente de aprobación. Te avisaremos cuando se confirme.',
            default => 'El pago no se completó. Puedes intentarlo de nuevo desde tus pedidos.',
        };

        $icon = match (true) {
            $isApproved => 'o-check-circle',
            $isPending => 'o-clock',
            default => 'o-x-circle',
        };

        $statusClasses = match (true) {
            $isApproved => 'bg-success/10 text-success',
            $isPending => 'bg-warning/10 text-warning',
            default => 'bg-error/10 text-error',
        };
    @endphp

    <div class="max-w-2xl mx-auto px-6 py-16">
        <x-base.section-card>
            <div class="flex flex-col sm:flex-row items-start gap-6">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 {{ $statusClasses }}">
                    <x-mary-icon :name="$icon" class="w-7 h-7" />
                </div>

                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tighter">{{ $title }}</h2>
                    <p class="text-sm text-base-content/60 leading-relaxed mt-2">{{ $description }}</p>

                    <x-mary-button :link="route('orders.index')" label="Ver mis pedidos" icon="o-document-text"
                        class="btn-primary mt-6 font-black text-xs uppercase tracking-widest rounded-2xl" />
                </div>
            </div>
        </x-base.section-card>
    </div>
</x-app-layout>
