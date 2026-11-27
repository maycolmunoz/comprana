<footer class="bg-base-200/50 border-base-content/5 mt-10 w-full border-t py-12 backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid grid-cols-1 gap-12 md:grid-cols-12 lg:gap-8">
            <div class="flex flex-col space-y-6 md:col-span-5">
                <div class="flex items-center gap-3">
                    <x-base.brand
                        href="{{ route('products.index') }}"
                        :logo="asset('srcs/favicon.ico')"
                        alt="Comprana"
                        class="h-8 w-auto !justify-start"
                        wire:navigate
                    />
                    <span class="text-2xl font-black tracking-tighter">COMPRA<span class="text-red-600">NA</span></span>
                </div>
                <p class="text-base-content/60 max-w-sm text-sm leading-relaxed">
                    Tu destino digital de compras: calidad, ofertas irresistibles y la comodidad de tenerlo todo a un
                    clic de distancia.
                </p>
            </div>

            <div class="space-y-6 md:col-span-3">
                <h2 class="text-[10px] font-bold tracking-[0.2em] text-red-600 uppercase">Navegación</h2>
                <nav class="flex flex-col space-y-3">
                    <a
                        href="{{ route('products.index') }}"
                        wire:navigate
                        class="text-base-content/70 text-sm font-normal transition-all duration-300 hover:translate-x-1 hover:text-red-600"
                    >
                        Tienda
                    </a>

                    @auth
                        <a
                            href="{{ route('carts.index') }}"
                            wire:navigate
                            class="text-base-content/70 text-sm font-normal transition-all duration-300 hover:translate-x-1 hover:text-red-600"
                        >
                            Mis carritos
                        </a>
                        <a
                            href="{{ route('orders.index') }}"
                            wire:navigate
                            class="text-base-content/70 text-sm font-normal transition-all duration-300 hover:translate-x-1 hover:text-red-600"
                        >
                            Mis pedidos
                        </a>
                        <a
                            href="{{ route('profile.edit') }}"
                            wire:navigate
                            class="text-base-content/70 text-sm font-normal transition-all duration-300 hover:translate-x-1 hover:text-red-600"
                        >
                            Mi perfil
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            wire:navigate
                            class="text-base-content/70 text-sm font-normal transition-all duration-300 hover:translate-x-1 hover:text-red-600"
                        >
                            Entrar
                        </a>
                        <a
                            href="{{ route('register') }}"
                            wire:navigate
                            class="text-base-content/70 text-sm font-normal transition-all duration-300 hover:translate-x-1 hover:text-red-600"
                        >
                            Crear cuenta
                        </a>
                    @endauth
                </nav>
            </div>

            <div class="space-y-6 md:col-span-4">
                <h2 class="text-[10px] font-bold tracking-[0.2em] text-red-600 uppercase">Contacto</h2>
                <div class="space-y-4">
                    <a
                        href="mailto:hola@comprana.com"
                        class="bg-base-content/5 border-base-content/5 flex items-start gap-4 rounded-2xl border p-4 transition-all hover:border-red-600/20"
                    >
                        <div class="bg-base-100 rounded-xl p-2 text-red-600">
                            <x-mary-icon name="o-envelope" class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-base-content/60 mb-0.5 text-[10px] font-bold uppercase">Escríbenos</p>
                            <p class="text-sm font-bold">hola@comprana.com</p>
                        </div>
                    </a>

                    <a
                        href="tel:+573000000000"
                        class="bg-base-content/5 border-base-content/5 flex items-start gap-4 rounded-2xl border p-4 transition-all hover:border-red-600/20"
                    >
                        <div class="bg-base-100 rounded-xl p-2 text-red-600">
                            <x-mary-icon name="o-phone" class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-base-content/60 mb-0.5 text-[10px] font-bold uppercase">Llámanos</p>
                            <p class="text-sm font-bold">+57 300 000 0000</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-base-content/5 mt-12 flex flex-col items-center justify-between gap-6 border-t pt-8 md:flex-row">
            <p class="text-base-content/40 text-[10px] font-bold tracking-widest uppercase">
                &copy; {{ date('Y') }} COMPRANA. TODOS LOS DERECHOS RESERVADOS.
            </p>
        </div>
    </div>
</footer>
