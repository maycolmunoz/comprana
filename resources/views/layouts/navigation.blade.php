<nav x-data="{ open: false }" class="bg-base-100/90 border-base-content/10 sticky top-0 z-50 border-b backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            <!-- Left: Brand + Navigation -->
            <div class="flex items-center gap-10">
                <div class="group flex shrink items-center gap-2">
                    <x-base.brand
                        href="{{ route('products.index') }}"
                        :logo="asset('srcs/favicon.ico')"
                        alt="comprana"
                        class="h-9 w-auto justify-start!"
                    />
                    <span class="text-xl font-black tracking-tighter uppercase transition-transform duration-300 group-hover:scale-105">
                        COMPRA<span class="text-red-600">NA</span>
                    </span>
                </div>

                <!-- Navigation Buttons (authenticated users) -->
                @auth
                    <div class="hidden items-center gap-2 md:flex">
                        <x-mary-button
                            link="{{ route('products.index') }}"
                            label="Tienda"
                            icon="o-shopping-bag"
                            class="btn-ghost btn-sm font-bold {{ request()->routeIs('products.index') ? 'bg-base-content/10 text-red-600 shadow-sm' : 'text-base-content/70' }}"
                        />

                        <x-mary-button
                            link="{{ route('carts.index') }}"
                            label="Carritos"
                            icon="o-shopping-cart"
                            class="btn-ghost btn-sm font-bold {{ request()->routeIs('carts.index') ? 'bg-base-content/10 text-red-600 shadow-sm' : 'text-base-content/70' }}"
                        />

                        <x-mary-button
                            link="{{ route('orders.index') }}"
                            label="Pedidos"
                            icon="o-document-text"
                            class="btn-ghost btn-sm font-bold {{ request()->routeIs('orders.index') ? 'bg-base-content/10 text-red-600 shadow-sm' : 'text-base-content/70' }}"
                        />
                    </div>
                @endauth
            </div>

            <!-- Right: Auth Section (far right) -->
            <div class="flex items-center gap-2">
                @auth
                    <!-- Profile Dropdown -->
                    <div class="hidden md:flex">
                        <x-mary-dropdown right class="btn-ghost btn-sm font-bold">
                            <x-slot:trigger>
                                <div class="flex items-center gap-3 pr-1">
                                    <div class="flex flex-col items-end leading-none">
                                        <span class="text-[10px] font-bold uppercase opacity-40">Hola,</span>
                                        <span class="text-xs">{{ Auth::user()->name }}</span>
                                    </div>
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full border border-red-600/20 bg-red-600/10">
                                        <x-mary-icon name="o-user" class="h-4 w-4 text-red-600" />
                                    </div>
                                </div>
                            </x-slot:trigger>

                            <x-mary-menu-item
                                icon="o-identification"
                                label="Mi perfil"
                                link="{{ route('profile.edit') }}"
                            />

                            <x-mary-menu-separator />

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-mary-menu-item
                                    icon="o-power"
                                    label="Cerrar sesión"
                                    onclick="
                                        event.preventDefault();
                                        this.closest('form').submit();
                                    "
                                />
                            </form>
                        </x-mary-dropdown>
                    </div>
                @else
                    <!-- Login/Register Buttons -->
                    <div class="hidden items-center gap-2 md:flex">
                        <x-mary-button
                            :link="route('login')"
                            label="Entrar"
                            class="btn-ghost btn-sm text-base-content/70 font-bold"
                        />

                        <x-mary-button :link="route('register')" label="Unirse" class="btn-primary btn-sm font-bold" />
                    </div>
                @endauth

                <!-- Mobile menu button -->
                <div class="flex md:hidden">
                    <x-mary-button
                        icon="o-bars-3-bottom-right"
                        @click="open = ! open"
                        class="btn-ghost btn-circle"
                        aria-label="Abrir menú"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="border-base-content/10 bg-base-100 border-t md:hidden"
    >
        @auth
            <div class="space-y-2 p-4">
                <x-mary-button
                    link="{{ route('products.index') }}"
                    label="Tienda"
                    icon="o-shopping-bag"
                    class="w-full justify-start btn-ghost {{ request()->routeIs('products.index') ? 'bg-base-content/5 text-red-600' : 'text-base-content/70' }}"
                />

                <x-mary-button
                    link="{{ route('carts.index') }}"
                    label="Carritos"
                    icon="o-shopping-cart"
                    class="w-full justify-start btn-ghost {{ request()->routeIs('carts.index') ? 'bg-base-content/5 text-red-600' : 'text-base-content/70' }}"
                />

                <x-mary-button
                    link="{{ route('orders.index') }}"
                    label="Pedidos"
                    icon="o-document-text"
                    class="w-full justify-start btn-ghost {{ request()->routeIs('orders.index') ? 'bg-base-content/5 text-red-600' : 'text-base-content/70' }}"
                />

                <div class="border-base-content/5 border-t pt-4">
                    <x-mary-button
                        link="{{ route('profile.edit') }}"
                        label="Perfil"
                        icon="o-identification"
                        class="btn-ghost text-base-content/70 w-full justify-start text-xs"
                    />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-mary-button
                            icon="o-power"
                            label="Cerrar sesión"
                            onclick="
                                event.preventDefault();
                                this.closest('form').submit();
                            "
                            class="btn-ghost w-full justify-start text-xs text-red-600"
                        />
                    </form>
                </div>
            </div>
        @else
            <div class="space-y-2 p-4">
                <x-mary-button
                    :link="route('login')"
                    label="Entrar"
                    icon="o-arrow-right-on-rectangle"
                    class="btn-ghost text-base-content/70 w-full justify-start"
                />

                <x-mary-button :link="route('register')" label="Unirse" class="btn-primary w-full justify-start" />
            </div>
        @endauth
    </div>
</nav>
