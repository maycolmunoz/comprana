<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Estado de Compra" />
    </x-slot>

    <div class="sm:px-10 my-10 sm:my-28">
        @if ($status === 'approved')
            <div
                class="md:w-1/2 mx-auto flex justify-center bg-base-100 items-center px-6 py-4 text-sm border-t-2 rounded-b shadow-xs border-success">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8 text-success stroke-current"
                    fill="none"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"></path>
                </svg>
                <div class="ml-3">
                    <div class="font-bold text-left text-base-content">Compra realizada correctamente.</div>
                    <a
                        href="{{ route('orders.index') }}"
                        class="w-full text-base-content/70 underline hover:text-primary mt-1">
                        Ver mis pedidos.
                    </a>
                </div>
            </div>
        @else
            <div
                class="md:w-1/2 mx-auto flex justify-center bg-base-100 items-center px-6 py-4 text-sm border-t-2 rounded-b shadow-xs border-warning">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8 text-warning stroke-current"
                    fill="none"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"></path>
                </svg>
                <div class="ml-3">
                    <div class="font-bold text-left text-base-content">Compra realizada pago.</div>
                    <div class="font-bold text-left text-base-content">Tu pago está pendiente de ser aprobado.</div>
                    <a
                        href="{{ route('orders.index') }}"
                        class="w-full text-base-content/70 underline hover:text-primary mt-1">
                        Ver mis pedidos.
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>