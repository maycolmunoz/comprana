<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-base-100/70 backdrop-blur-lg border-b border-base-content/5">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex justify-between h-20">
			<!-- Brand & Desktop Nav -->
			<div class="flex items-center gap-10">
				<!-- Logo -->
				<div class="shrink flex items-center gap-2 group">
					<x-ui.brand href="{{ route('products.index') }}" :logo="asset('srcs/favicon.ico')" alt="comprana" class="justify-start! h-9 w-auto" />
					<span class="text-xl font-black tracking-tighter uppercase group-hover:scale-105 transition-transform duration-300">
						COMPRA<span class="text-red-600">NA</span>
					</span>
				</div>

				<!-- Desktop Navigation Links -->
				@auth
					<div class="hidden md:flex items-center gap-2">
						<x-mary-button link="{{ route('products.index') }}" label="Tienda" icon="o-shopping-bag"
							class="btn-ghost btn-sm font-bold {{ request()->routeIs('products.index') ? 'bg-base-content/10 text-red-600 shadow-sm' : 'text-base-content/60' }}" />

						<x-mary-button link="{{ route('carts.index') }}" label="Carritos" icon="o-shopping-cart"
							class="btn-ghost btn-sm font-bold {{ request()->routeIs('carts.index') ? 'bg-base-content/10 text-red-600 shadow-sm' : 'text-base-content/60' }}" />

						<x-mary-button link="{{ route('orders.index') }}" label="Pedidos" icon="o-document-text"
							class="btn-ghost btn-sm font-bold {{ request()->routeIs('orders.index') ? 'bg-base-content/10 text-red-600 shadow-sm' : 'text-base-content/60' }}" />
					</div>
				@endauth
			</div>

			<!-- Right side / Actions -->
			<div class="flex items-center gap-2">
				@guest
					<div class="flex items-center gap-2">
						<x-mary-button :link="route('login')" label="Entrar" class="btn-ghost btn-sm font-bold" />
						<x-mary-button :link="route('register')" label="Unirse" class="btn-primary btn-sm font-bold px-6" />
					</div>
				@endguest

				@auth
					<!-- User Dropdown -->
					<div class="hidden md:flex">
						<x-mary-dropdown right class="btn-ghost btn-sm font-bold">
							<x-slot:trigger>
								<div class="flex items-center gap-3 pr-1">
									<div class="flex flex-col items-end leading-none">
										<span class="text-[10px] font-bold uppercase opacity-40">Hola,</span>
										<span class="text-xs">{{ Auth::user()->name }}</span>
									</div>
									<div class="w-8 h-8 rounded-full bg-red-600/10 flex items-center justify-center border border-red-600/20">
										<x-mary-icon name="o-user" class="w-4 h-4 text-red-600" />
									</div>
								</div>
							</x-slot:trigger>

							<x-mary-menu-item icon="o-identification" label="Mi Perfil" link="{{ route('profile.edit') }}" />
							<x-mary-menu-separator />
							<form method="POST" action="{{ route('logout') }}">
								@csrf
								<x-mary-menu-item icon="o-power" label="Cerrar Sesión"
									onclick="event.preventDefault(); this.closest('form').submit();" />
							</form>
						</x-mary-dropdown>
					</div>

					<!-- Mobile Toggle -->
					<div class="flex md:hidden">
						<x-mary-button icon="o-bars-3-bottom-right" @click="open = !open" class="btn-ghost btn-circle" />
					</div>
				@endauth
			</div>
		</div>
	</div>

	<!-- Mobile Menu -->
	@auth
		<div x-show="open" x-transition:enter="transition ease-out duration-200"
			x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
			x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
			x-transition:leave-end="opacity-0 -translate-y-4"
			class="md:hidden border-t border-base-content/5 bg-base-100 p-4 space-y-2">

			<x-mary-button link="{{ route('products.index') }}" label="Tienda" icon="o-shopping-bag"
				class="w-full justify-start btn-ghost {{ request()->routeIs('products.index') ? 'bg-base-content/5 text-red-600' : '' }}" />

			<x-mary-button link="{{ route('carts.index') }}" label="Carritos" icon="o-shopping-cart"
				class="w-full justify-start btn-ghost {{ request()->routeIs('carts.index') ? 'bg-base-content/5 text-red-600' : '' }}" />

			<x-mary-button link="{{ route('orders.index') }}" label="Pedidos" icon="o-document-text"
				class="w-full justify-start btn-ghost {{ request()->routeIs('orders.index') ? 'bg-base-content/5 text-red-600' : '' }}" />

			<div class="pt-4 border-t border-base-content/5">
				<x-mary-button link="{{ route('profile.edit') }}" label="Perfil" icon="o-identification"
					class="w-full justify-start btn-ghost text-xs" />
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<x-mary-button icon="o-power" label="Cerrar Sesión" onclick="event.preventDefault(); this.closest('form').submit();"
						class="w-full justify-start btn-ghost text-red-600 text-xs" />
				</form>
			</div>
		</div>
	@endauth
</nav>
