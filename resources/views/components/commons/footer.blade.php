<footer class="w-full bg-base-200/50 backdrop-blur-md border-t border-base-content/5 py-12 mt-10">
	<div class="max-w-7xl px-6 mx-auto">
		<div class="grid grid-cols-1 md:grid-cols-12 gap-12 lg:gap-8">

			{{-- Brand Section --}}
			<div class="md:col-span-4 flex flex-col space-y-6">
				<div class="flex items-center gap-3">
					<x-ui.brand href="/" :logo="asset('srcs/favicon.ico')" alt="comprana" class="!justify-start h-8 w-auto" />
					<span class="text-2xl font-black tracking-tighter">COMPRA<span class="text-red-600">NA</span></span>
				</div>
				<p class="text-base-content/60 text-sm leading-relaxed max-w-sm">
					Tu destino digital de compras: calidad fresca, ofertas irresistibles y la comodidad de tenerlo todo a solo un clic
					de distancia.
				</p>
				<div class="flex gap-4">
					<x-mary-button icon="o-heart"
						class="btn-circle btn-ghost btn-sm bg-base-content/5 hover:bg-base-content/10 transition-colors" />
					<x-mary-button icon="o-share"
						class="btn-circle btn-ghost btn-sm bg-base-content/5 hover:bg-base-content/10 transition-colors" />
					<x-mary-button icon="o-globe-alt"
						class="btn-circle btn-ghost btn-sm bg-base-content/5 hover:bg-base-content/10 transition-colors" />
				</div>
			</div>

			{{-- Links Sections --}}
			<div class="md:col-span-2 space-y-6">
				<h4 class="text-[10px] uppercase font-bold tracking-[0.2em] text-red-600">Navegación</h4>
				<nav class="flex flex-col space-y-3">
					<a href="/"
						class="text-sm font-medium text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">Inicio</a>
					<a href="/tienda"
						class="text-sm font-medium text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">Tienda</a>
					<a href="#"
						class="text-sm font-medium text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">Mis
						Pedidos</a>
					<a href="#"
						class="text-sm font-medium text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">Favoritos</a>
				</nav>
			</div>

			<div class="md:col-span-2 space-y-6">
				<h4 class="text-[10px] uppercase font-bold tracking-[0.2em] text-red-600">Ayuda</h4>
				<nav class="flex flex-col space-y-3">
					<a href="#"
						class="text-sm font-medium text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">Soporte</a>
					<a href="#"
						class="text-sm font-medium text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">Envíos</a>
					<a href="#"
						class="text-sm font-medium text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">Términos</a>
					<a href="#"
						class="text-sm font-medium text-base-content/70 hover:text-red-600 hover:translate-x-1 transition-all duration-300">Privacidad</a>
				</nav>
			</div>

			{{-- Contact Section --}}
			<div class="md:col-span-4 space-y-6">
				<h4 class="text-[10px] uppercase font-bold tracking-[0.2em] text-red-600">Contacto Directo</h4>
				<div class="space-y-4">
					<div
						class="flex items-start gap-4 p-4 rounded-2xl bg-base-content/5 border border-base-content/5 group hover:border-red-600/20 transition-all cursor-default">
						<div class="p-2 rounded-xl bg-base-100 shadow-sm text-red-600 group-hover:scale-110 transition-transform">
							<x-mary-icon name="o-envelope" class="w-5 h-5" />
						</div>
						<div>
							<p class="text-[10px] uppercase font-bold text-base-content/40 mb-0.5">Escríbenos</p>
							<a href="mailto:hola@comprana.com" class="text-sm font-bold truncate">hola@comprana.com</a>
						</div>
					</div>

					<div
						class="flex items-start gap-4 p-4 rounded-2xl bg-base-content/5 border border-base-content/5 group hover:border-red-600/20 transition-all cursor-default">
						<div class="p-2 rounded-xl bg-base-100 shadow-sm text-red-600 group-hover:scale-110 transition-transform">
							<x-mary-icon name="o-phone" class="w-5 h-5" />
						</div>
						<div>
							<p class="text-[10px] uppercase font-bold text-base-content/40 mb-0.5">Llámanos</p>
							<a href="tel:+573000000000" class="text-sm font-bold">+57 300 000 0000</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="mt-20 pt-8 border-t border-base-content/5 flex flex-col md:flex-row justify-between items-center gap-6">
			<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">
				&copy; {{ date('Y') }} COMPRANA. TODOS LOS DERECHOS RESERVADOS.
			</p>
			<div
				class="flex items-center gap-6 opacity-30 hover:opacity-100 transition-opacity duration-500 italic font-medium text-[10px]">
				<span>Visa</span>
				<span>Mastercard</span>
				<span>American Express</span>
				<span>PayPal</span>
			</div>
		</div>
	</div>
</footer>
