<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Detalles del carrito" />
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-8 px-6 lg:px-8">
            <x-base.info-list
                title="Gestión de productos"
                :list="[
                    'Ajusta las cantidades de tus productos y utiliza el botón de actualizar para confirmar.',
                    'Si agregas un producto existente, la cantidad se actualizará con la nueva selección.',
                    'Revisa tu selección antes de proceder al pago seguro.',
                ]"
            />

            <div>
                <livewire:carts.products-list :$cart />
            </div>
        </div>
    </div>
</x-app-layout>
