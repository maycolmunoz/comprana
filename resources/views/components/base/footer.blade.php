<footer class="w-full bg-base-200/50 backdrop-blur-md border-t border-base-content/5 py-12 mt-10">
    <div class="max-w-7xl px-6 mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8">
            <div class="md:col-span-5 flex flex-col space-y-6">
                <div class="flex items-center gap-3">
                    <x-base.brand href="{{ route('products.index') }}" :logo="asset('srcs/favicon.ico')" alt="Comprana"
                        class="!justify-start h-8 w-auto" wire:navigate />
                    <span class="text-2xl font-black tracking-tighter">COMPRA<span class="text-red-600">NA</span></span>
                </div>
                <p class="text-base-content/60 text-sm leading-relaxed max-w-sm">
                    Tu destino digital de compras: calidad, ofertas irresistibles y la comodidad de tenerlo todo a un
                    clic de distancia.
                </p>
            </div>

            <div class="md:col-span-3 space-y-6">
                <h2 class="text-[10px] uppercase font-bold tracking-[0.2em] text-red-600">Navegación</h2>
                <nav class="flex flex-col space-y-3">
                    <a href="{{ route('products.index') }}" wire:navigate
                        class="text-sm font-normal text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">
                        Tienda
                    </a>

                    @auth
                        <a href="{{ route('carts.index') }}" wire:navigate
                            class="text-sm font-normal text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">
                            Mis carritos
                        </a>
                        <a href="{{ route('orders.index') }}" wire:navigate
                            class="text-sm font-normal text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">
                            Mis pedidos
                        </a>
                        <a href="{{ route('profile.edit') }}" wire:navigate
                            class="text-sm font-normal text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">
                            Mi perfil
                        </a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate
                            class="text-sm font-normal text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}" wire:navigate
                            class="text-sm font-normal text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">
                            Crear cuenta
                        </a>
                    @endauth
                </nav>
            </div>

            <div class="md:col-span-4 space-y-6">
                <h2 class="text-[10px] uppercase font-bold tracking-[0.2em] text-red-600">Contacto</h2>
                <div class="space-y-4">
                    <a href="mailto:hola@comprana.com"
                        class="flex items-start gap-4 p-4 rounded-2xl bg-base-content/5 border border-base-content/5 hover:border-red-600/20 transition-all">
                        <div class="p-2 rounded-xl bg-base-100 text-red-600">
                            <x-mary-icon name="o-envelope" class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-base-content/60 mb-0.5">Escríbenos</p>
                            <p class="text-sm font-bold">hola@comprana.com</p>
                        </div>
                    </a>

                    <a href="tel:+573000000000"
                        class="flex items-start gap-4 p-4 rounded-2xl bg-base-content/5 border border-base-content/5 hover:border-red-600/20 transition-all">
                        <div class="p-2 rounded-xl bg-base-100 text-red-600">
                            <x-mary-icon name="o-phone" class="w-5 h-5" />
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-base-content/60 mb-0.5">Llámanos</p>
                            <p class="text-sm font-bold">+57 300 000 0000</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-base-content/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">
                &copy; {{ date('Y') }} COMPRANA. TODOS LOS DERECHOS RESERVADOS.
            </p>
        </div>
    </div>
</footer>
