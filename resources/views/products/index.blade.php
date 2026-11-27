<x-app-layout>
    <div
        x-data="{ slideOverOpen: false }"
        @slider.window="slideOverOpen = true"
        @slider-close.window="slideOverOpen = false"
        class="relative z-100"
    >
        <template x-teleport="body">
            <div x-show="slideOverOpen" @keydown.window.escape="slideOverOpen = false" class="relative z-100" x-cloak>
                <div
                    x-show="slideOverOpen"
                    x-transition:enter="transition-opacity ease-out duration-500"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in duration-400"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click="slideOverOpen = false"
                    class="bg-base-300/40 fixed inset-0 backdrop-blur-md"
                ></div>

                <div class="pointer-events-none fixed inset-0 overflow-hidden">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="fixed inset-y-0 right-0 flex max-w-full pl-0 sm:pl-10">
                            <div
                                x-show="slideOverOpen"
                                x-transition:enter="transform transition ease-out duration-500 sm:duration-700"
                                x-transition:enter-start="translate-x-full"
                                x-transition:enter-end="translate-x-0"
                                x-transition:leave="transform transition ease-in duration-400 sm:duration-700"
                                x-transition:leave-start="translate-x-0"
                                x-transition:leave-end="translate-x-full"
                                class="pointer-events-auto w-screen max-w-2xl"
                            >
                                <div class="bg-base-100 border-base-content/5 relative flex h-full flex-col overflow-hidden border-l shadow-[-20px_0_50px_-15px_rgba(0,0,0,0.1)]">
                                    <div class="h-1.5 w-full bg-linear-to-r from-red-600 to-red-400"></div>

                                    <div class="border-base-content/5 bg-base-100/50 sticky top-0 z-20 flex items-center justify-between border-b px-6 py-8 backdrop-blur-sm sm:px-10">
                                        <div>
                                            <h2 class="flex items-center gap-3 text-2xl font-black tracking-tighter uppercase">
                                                <x-mary-icon
                                                    name="o-magnifying-glass-plus"
                                                    class="h-6 w-6 text-red-600"
                                                />
                                                Detalles del producto
                                            </h2>
                                            <p class="text-base-content/40 mt-1 text-[10px] font-bold tracking-widest uppercase">
                                                Comprana
                                            </p>
                                        </div>

                                        <x-mary-button
                                            icon="o-x-mark"
                                            @click="slideOverOpen = false"
                                            class="btn-ghost btn-circle btn-sm transition-transform duration-300 hover:rotate-90"
                                        />
                                    </div>

                                    <div class="bg-base-200/30 relative flex-1 overflow-y-auto px-6 py-8 sm:px-10">
                                        <div>
                                            <livewire:products.details />
                                        </div>
                                    </div>

                                    <div class="border-base-content/5 bg-base-100/80 border-t px-6 py-4 text-center backdrop-blur-md">
                                        <div class="inline-flex items-center gap-2 opacity-20">
                                            <x-base.brand
                                                :logo="asset('srcs/favicon.ico')"
                                                class="h-4 w-auto grayscale"
                                            />
                                            <span class="text-[8px] font-bold tracking-[.4em] uppercase">COMPRANA</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <section class="bg-base-100 border-base-content/5 mb-12 border-b">
        <div class="mx-auto max-w-7xl px-6 py-10 lg:py-16">
            <div class="flex flex-col justify-between gap-8 lg:flex-row lg:items-end">
                <div class="max-w-xl">
                    <span class="mb-2 block text-[10px] font-black tracking-widest text-red-600 uppercase">
                        Catálogo Selecto
                    </span>
                    <h1 class="mb-6 text-4xl leading-none font-black tracking-tighter uppercase lg:text-6xl">
                        Explora Nuestras <span class="text-base-content/40 italic">Colecciones</span>
                    </h1>
                    <p class="text-base-content/50 text-base font-normal">
                        Calidad garantizada y entregas seguras. Encuentra lo que necesitas con la mejor experiencia de
                        compra.
                    </p>
                </div>

                <div>
                    @include('products.partials.filters')
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto min-h-[60vh] max-w-7xl px-6 pb-24">
        <livewire:products.list />
    </div>
</x-app-layout>
