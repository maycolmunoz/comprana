<?php

use App\Models\Order;
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
            <x-mary-button
                label="Cerrar"
                @click="$wire.showDetailsModal = false"
                class="btn-primary rounded-xl text-[10px] font-black tracking-widest uppercase"
            />
        </x-slot:actions>
    </x-mary-modal>

    @forelse ($this->orders as $order)
        <div class="group bg-base-100 border-base-content/5 relative overflow-hidden rounded-3xl border p-6 transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl hover:shadow-red-900/5 lg:p-8">
            {{-- Decorative Side Accent --}}
            <div class="absolute top-0 left-0 h-full w-1.5 bg-linear-to-b from-red-600 to-red-400 opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>

            <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-center">
                {{-- Order Meta & Status --}}
                <div class="flex-1 space-y-4">
                    <div class="flex flex-wrap items-center gap-4">
                        <span class="text-xs font-black tracking-widest text-red-600 uppercase">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <x-base.status-badge :status="$order->status" :icon="$order->status->getIcon()" />
                        @php
                            $paymentBadgeClasses = match ($order->payment_status) {
                                'approved' => 'badge-success bg-success/10 text-success border-success/20',
                                'pending', 'in_process' => 'badge-warning bg-warning/10 text-warning border-warning/20',
                                'rejected', 'cancelled' => 'badge-error bg-error/10 text-error border-error/20',
                                default => 'badge-neutral bg-base-content/5 text-base-content/60 border-base-content/10',
                            };
                        @endphp
                        <x-mary-badge
                            :label="$order->paymentStatusLabel()"
                            icon="o-credit-card"
                            class="font-bold uppercase tracking-widest text-[9px] py-3 px-4 rounded-xl border {{ $paymentBadgeClasses }}"
                        />
                    </div>

                    <div>
                        <h2 class="text-2xl font-black tracking-tighter uppercase">Pedido realizado</h2>
                        <p class="text-base-content/40 mt-1 text-[10px] font-bold tracking-widest uppercase">
                            {{ $order->created_at->format('d/m/Y h:i A') }}
                        </p>
                    </div>
                </div>

                {{-- Order Details Grid --}}
                <div class="border-base-content/5 grid flex-[1.5] grid-cols-1 gap-6 border-y py-6 md:grid-cols-2 lg:border-x lg:border-y-0 lg:px-10 lg:py-0">
                    <div class="space-y-1">
                        <span class="text-base-content/30 text-[9px] font-black tracking-widest uppercase">Dirección de entrega</span>
                        <p class="line-clamp-2 text-xs leading-relaxed font-bold">{{ $order->address }}</p>
                        <p class="text-base-content/40 text-[10px] font-normal">{{ $order->phone }}</p>
                    </div>
                    <div class="space-y-1">
                        <span class="text-base-content/30 text-[9px] font-black tracking-widest uppercase">Total del pedido</span>
                        <x-base.price :value="$order->total ?? 0" />
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex shrink-0 flex-wrap items-center gap-3">
                    @if ($order->invoice)
                        <x-mary-button
                            link="{{ route('orders.invoice', ['name' => $order->invoice]) }}"
                            external
                            label="Factura PDF"
                            icon="o-document-text"
                            class="btn-ghost btn-sm bg-base-content/5 rounded-xl text-[10px] font-black tracking-widest uppercase hover:bg-red-600/10 hover:text-red-600"
                        />
                    @endif

                    <x-mary-button
                        label="Ver detalles"
                        icon-right="o-chevron-right"
                        wire:click="showDetails({{ $order->id }})"
                        class="btn-outline btn-sm rounded-xl px-6 text-[10px] font-black tracking-widest uppercase"
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
