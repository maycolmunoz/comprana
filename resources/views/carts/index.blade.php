<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Mis Carritos" />
    </x-slot>

    <div class="py-12 animate__animated animate__fadeIn">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
            <x-base.info-list
                title="Gestión de Carritos"
                :list="[
                    'Puedes gestionar hasta 8 carritos personalizados simultáneamente.',
                    'El carrito activo es el destino predeterminado de tus nuevas selecciones.',
                    'Organiza, renombra o vacía tus carritos para una experiencia de compra personalizada.',
                ]" />

            <div class="pt-4">
                <livewire:carts.list />
            </div>
        </div>
    </div>
</x-app-layout>
