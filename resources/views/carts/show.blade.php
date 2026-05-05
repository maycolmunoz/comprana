<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Detalles del Carrito" />
    </x-slot>

    <div class="py-12 animate__animated animate__fadeIn">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
            <x-base.info-list
                title="Gestión de Productos"
                :list="[
                    'Ajusta las cantidades de tus productos y utiliza el botón de actualizar para confirmar.',
                    'Si agregas un producto existente, la cantidad se consolidará automáticamente.',
                    'Revisa tu selección antes de proceder al pago seguro.',
                ]" />

            <div>
                <livewire:carts.products-list :$cart lazy />
            </div>
        </div>
    </div>
</x-app-layout>
