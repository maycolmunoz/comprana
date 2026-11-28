<?php

use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Url()]
    public $status = '';

    public bool $showDetailsModal = false;

    public ?int $selectedOrderId = null;

    #[Computed()]
    public function orders()
    {
        $query = Order::where('customer_id', Auth::user()->id)->orderBy('id', 'desc');

        if ($this->status != '') {
            $query->where('status', $this->status);
        }

        return $query->paginate(5);
    }

    #[Computed()]
    public function order(): ?Order
    {
        if (! $this->selectedOrderId) {
            return null;
        }

        return Order::where('customer_id', Auth::user()->id)->find($this->selectedOrderId);
    }

    public function showDetails(int $orderId): void
    {
        $this->selectedOrderId = $orderId;
        $this->showDetailsModal = true;
    }

    public function verifyPayment(int $orderId, MercadoPagoService $mercadopago): void
    {
        $order = Order::where('customer_id', Auth::user()->id)->findOrFail($orderId);

        $status = $mercadopago->syncPayment($order);

        $message = match (true) {
            $status === 'approved' => '¡Pago confirmado! Tu pedido ya está pagado.',
            $order->isPaymentPending() => 'Mercado Pago está procesando el pago. Puedes revisar el estado cuando quieras.',
            in_array($status, ['pending', 'in_process'], true) => 'Aún no registramos un pago. Puedes reintentar.',
            $status === 'rejected' => 'El pago fue rechazado. Puedes reintentarlo.',
            $status === 'cancelled' => 'El pago fue cancelado. Puedes reintentarlo.',
            $status === 'refunded' => 'El pago fue reembolsado.',
            $status === 'charged_back' => 'Mercado Pago reportó un contracargo. Contacta con soporte.',
            $status === 'in_mediation' => 'El pago está en mediación. Contacta con soporte.',
            default => 'Aún no registramos un pago. Puedes pagar ahora.',
        };

        unset($this->orders, $this->order);

        $this->dispatch('notification', $message);
    }
};
?>

<div class="space-y-6">
    <x-mary-modal wire:model="showDetailsModal" title="Detalles del pedido" class="backdrop-blur-xl">
        @if ($this->order)
            <div class="space-y-4">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-black tracking-widest text-red-600 uppercase">
                        #ORD-{{ str_pad($this->order->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <x-base.status-badge :status="$this->order->status" :icon="$this->order->status->getIcon()" />
                </div>

                <div class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <span class="text-base-content/30 block text-[9px] font-black tracking-widest uppercase">Fecha</span>
                        <p class="font-bold">{{ $this->order->created_at->format('d/m/Y h:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-base-content/30 block text-[9px] font-black tracking-widest uppercase">Pago</span>
                        <p class="font-bold">{{ $this->order->paymentStatusLabel() }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-base-content/30 block text-[9px] font-black tracking-widest uppercase">Dirección de entrega</span>
                        <p class="font-bold">{{ $this->order->address }}</p>
                        <p class="text-base-content/60">{{ $this->order->phone }}</p>
                    </div>
                    <div>
                        <span class="text-base-content/30 block text-[9px] font-black tracking-widest uppercase">Total del pedido</span>
                        <x-base.price :value="$this->order->total ?? 0" />
                    </div>
                </div>
            </div>
        @endif

        <x-slot:actions>
            @if ($this->order?->invoice)
                <x-mary-button
                    label="Factura PDF"
                    icon="o-document-text"
                    link="{{ route('orders.invoice', ['name' => $this->order->invoice]) }}"
                    external
                    class="btn-ghost rounded-xl text-[10px] font-black tracking-widest uppercase"
                />
            @endif

            @if ($this->order?->canRetryPayment())
                <x-mary-button
                    :link="route('orders.pay', ['order' => $this->order])"
                    :label="$this->order->payment_status === null ? 'Pagar' : 'Reintentar pago'"
                    icon="o-credit-card"
                    class="btn-primary rounded-xl text-[10px] font-black tracking-widest uppercase"
                />
            @elseif ($this->order && ! $this->order->isPaid())
                <x-mary-button
                    :link="route('orders.complete', ['order' => $this->order])"
                    label="Ver estado"
                    icon="o-clock"
                    class="btn-ghost rounded-xl text-[10px] font-black tracking-widest uppercase"
                />
            @endif

            @if ($this->order && ! $this->order->isPaid())
                <x-mary-button
                    label="Verificar pago"
                    icon="o-arrow-path"
                    wire:click="verifyPayment({{ $this->order->id }})"
                    spinner="verifyPayment"
                    class="btn-ghost rounded-xl text-[10px] font-black tracking-widest uppercase"
                />
            @endif

            <x-mary-button
                label="Cerrar"
                @click="$wire.showDetailsModal = false"
                class="btn-primary rounded-xl text-[10px] font-black tracking-widest uppercase"
            />
        </x-slot:actions>
    </x-mary-modal>

    @forelse ($this->orders as $order)
        <div
            wire:key="order-{{ $order->id }}"
            class="group bg-base-100 border-base-content/5 relative overflow-hidden rounded-3xl border p-5 transition-all duration-300 hover:shadow-xl hover:shadow-red-900/5 sm:p-6"
        >
            {{-- Decorative Side Accent --}}
            <div class="absolute top-0 left-0 h-full w-1.5 bg-linear-to-b from-red-600 to-red-400 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

            <div class="flex flex-col gap-5">
                {{-- Order Meta & Status --}}
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-black tracking-widest text-red-600 uppercase">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <x-base.status-badge :status="$order->status" :icon="$order->status->getIcon()" />
                    @php
                        $paymentBadgeClasses = match ($order->payment_status) {
                            'approved' => 'badge-success bg-success/10 text-success border-success/20',
                            'pending', 'in_process' => 'badge-warning bg-warning/10 text-warning border-warning/20',
                            'rejected', 'cancelled', 'charged_back' => 'badge-error bg-error/10 text-error border-error/20',
                            'refunded' => 'badge-info bg-info/10 text-info border-info/20',
                            'in_mediation' => 'badge-warning bg-warning/10 text-warning border-warning/20',
                            default => 'badge-neutral bg-base-content/5 text-base-content/60 border-base-content/10',
                        };
                    @endphp
                    <x-mary-badge
                        :label="$order->paymentStatusLabel()"
                        icon="o-credit-card"
                        class="font-bold uppercase tracking-widest text-[9px] py-3 px-4 rounded-xl border {{ $paymentBadgeClasses }}"
                    />
                    <span class="text-base-content/40 ml-auto text-[10px] font-bold tracking-widest uppercase">
                        {{ $order->created_at->format('d/m/Y h:i A') }}
                    </span>
                </div>

                {{-- Order Summary --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <h2 class="text-xl font-black tracking-tighter uppercase sm:text-2xl">Pedido realizado</h2>
                        <p class="text-base-content/60 mt-2 line-clamp-2 text-xs leading-relaxed font-bold break-words">{{ $order->address }}</p>
                        <p class="text-base-content/40 mt-0.5 text-[10px] font-normal">{{ $order->phone }}</p>
                    </div>
                    <div class="shrink-0 sm:text-right">
                        <span class="text-base-content/30 block text-[9px] font-black tracking-widest uppercase">Total del pedido</span>
                        <x-base.price :value="$order->total ?? 0" />
                    </div>
                </div>

                {{-- Actions --}}
                <div class="border-base-content/5 flex flex-wrap items-center gap-2 border-t pt-4">
                    @if ($order->invoice)
                        <x-mary-button
                            link="{{ route('orders.invoice', ['name' => $order->invoice]) }}"
                            external
                            label="Factura PDF"
                            icon="o-document-text"
                            class="btn-ghost btn-sm bg-base-content/5 rounded-xl text-[10px] font-black tracking-widest uppercase hover:bg-red-600/10 hover:text-red-600"
                        />
                    @endif

                    @if ($order->canRetryPayment())
                        <x-mary-button
                            :link="route('orders.pay', ['order' => $order])"
                            :label="$order->payment_status === null ? 'Pagar' : 'Reintentar pago'"
                            icon="o-credit-card"
                            class="btn-primary btn-sm rounded-xl px-5 text-[10px] font-black tracking-widest uppercase"
                        />
                    @elseif (! $order->isPaid())
                        <x-mary-button
                            :link="route('orders.complete', ['order' => $order])"
                            label="Ver estado"
                            icon="o-clock"
                            class="btn-ghost btn-sm bg-base-content/5 rounded-xl text-[10px] font-black tracking-widest uppercase"
                        />
                    @endif

                    @if (! $order->isPaid())
                        <x-mary-button
                            label="Verificar pago"
                            icon="o-arrow-path"
                            wire:click="verifyPayment({{ $order->id }})"
                            spinner="verifyPayment"
                            class="btn-ghost btn-sm bg-base-content/5 rounded-xl text-[10px] font-black tracking-widest uppercase hover:bg-red-600/10 hover:text-red-600"
                        />
                    @endif

                    <x-mary-button
                        label="Ver detalles"
                        icon-right="o-chevron-right"
                        wire:click="showDetails({{ $order->id }})"
                        class="btn-outline btn-sm ml-auto rounded-xl px-5 text-[10px] font-black tracking-widest uppercase"
                    />
                </div>
            </div>
        </div>
    @empty
        <x-base.empty-state
            icon="o-archive-box-x-mark"
            title="No tienes pedidos aún"
            description="Explora nuestra tienda y comienza tu experiencia en Comprana."
            link="{{ route('products.index') }}"
        />
    @endforelse

    {{-- Pagination Styled --}}
    <div class="flex justify-center pt-10">{{ $this->orders->links('pagination::simple-tailwind') }}</div>
</div>
