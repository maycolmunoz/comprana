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

    #[Computed()]
    public function orders()
    {
        $query = Order::where('customer_id', Auth::user()->id)->orderBy('id', 'desc');

        if ($this->status != '') {
            $query->where('status', $this->status);
        }

        return $query->paginate(5);
    }
};
?>

<div class="space-y-6 animate__animated animate__fadeIn">
	@forelse ($this->orders as $order)
		<div
			class="group relative bg-base-100 border border-base-content/5 rounded-3xl p-6 lg:p-8 transition-all duration-500 hover:shadow-2xl hover:shadow-red-900/5 hover:-translate-y-1 overflow-hidden">
			{{-- Decorative Side Accent --}}
			<div
				class="absolute top-0 left-0 w-1.5 h-full bg-linear-to-b from-red-600 to-red-400 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
			</div>

			<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
				{{-- Order Meta & Status --}}
				<div class="flex-1 space-y-4">
					<div class="flex flex-wrap items-center gap-4">
						<span
							class="text-xs font-black uppercase tracking-widest text-red-600">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
						<x-base.status-badge :status="$order->status" :icon="$order->status->getIcon()" />
						@if ($order->payment_id)
							<x-mary-badge label="Pagado" icon="o-credit-card"
								class="badge-neutral bg-base-content/5 text-base-content/60 border-none font-bold uppercase tracking-widest text-[9px] py-3 px-4 rounded-xl" />
						@endif
					</div>

					<div>
						<h4 class="text-2xl font-black uppercase tracking-tighter">Pedido Realizado</h4>
						<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">
							{{ $order->created_at->translatedFormat('d M, Y • g:i A') }}
						</p>
					</div>
				</div>

				{{-- Order Details Grid --}}
				<div
					class="flex-[1.5] grid grid-cols-1 md:grid-cols-2 gap-6 py-6 lg:py-0 border-y lg:border-y-0 lg:border-x border-base-content/5 lg:px-10">
					<div class="space-y-1">
						<span class="text-[9px] font-black uppercase tracking-widest text-base-content/30">Entrega en</span>
						<p class="text-xs font-bold leading-relaxed line-clamp-2">{{ $order->address }}</p>
						<p class="text-[10px] font-medium text-base-content/40">{{ $order->phone }}</p>
					</div>
					<div class="space-y-1">
						<span
							class="text-[9px] font-black uppercase tracking-widest text-base-content/30">Total del Pedido</span>
						<p class="text-2xl font-black tracking-tighter text-red-600">${{ number_format($order->total, 0) }}</p>
					</div>
				</div>

				{{-- Actions --}}
				<div class="flex flex-wrap items-center gap-3 shrink-0">
					@if ($order->invoice)
						<x-mary-button link="{{ route('orders.invoice', ['name' => $order->invoice]) }}" external
							label="Factura PDF" icon="o-document-text"
							class="btn-ghost btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl bg-base-content/5 hover:bg-red-600/10 hover:text-red-600" />
					@endif

					<x-mary-button label="Ver Detalles" icon-right="o-chevron-right"
						class="btn-outline btn-sm font-black text-[10px] uppercase tracking-widest rounded-xl px-6" />
				</div>
			</div>

			{{-- Background Decal --}}
			<div class="absolute -right-4 -bottom-4 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity duration-500">
				<x-mary-icon name="o-shopping-bag" class="w-32 h-32 rotate-12" />
			</div>
		</div>
	@empty
		<x-base.empty-state icon="o-archive-box-x-mark" title="No tienes pedidos aún"
			description="Explora nuestra tienda premium y comienza tu primera experiencia Comprana."
			link="/tienda" />
	@endforelse

	{{-- Pagination Styled --}}
	<div class="pt-10 flex justify-center">
		{{ $this->orders->links('pagination::simple-tailwind') }}
	</div>
</div>