<footer class="w-full bg-gray-300 border-t border-black">
	<div class="justify-between max-w-7xl px-4 py-5 mx-auto text-gray-800 sm:px-6 sm:flex">
		{{-- Menu Section --}}
		<div class="p-5 border-r sm:w-2/12 text-center sm:text-left">
			<h4 class="text-sm font-bold text-indigo-600 uppercase mb-3">Menu</h4>
			<nav class="space-y-2">
				<x-mary-button link="/"
					class="w-full text-left hover:text-indigo-600 bg-transparent border-0 shadow-none p-0 text-gray-800 justify-start"
					external>
					Inicio
				</x-mary-button>
				<x-mary-button link="/tienda"
					class="w-full text-left hover:text-indigo-600 bg-transparent border-0 shadow-none p-0 text-gray-800 justify-start"
					external>
					Tienda
				</x-mary-button>
				<x-mary-button link="#"
					class="w-full text-left hover:text-indigo-600 bg-transparent border-0 shadow-none p-0 text-gray-800 justify-start"
					external>
					Servicios
				</x-mary-button>
				<x-mary-button link="#"
					class="w-full text-left hover:text-indigo-600 bg-transparent border-0 shadow-none p-0 text-gray-800 justify-start"
					external>
					Comprana
				</x-mary-button>
			</nav>
		</div>

		{{-- About Section --}}
		<div class="p-5 text-center border-r sm:w-7/12">
			<h3 class="mb-4 text-xl font-bold text-red-600">Comprana</h3>
			<p class="mb-10 text-sm text-gray-500">
				Tu destino digital de compras: calidad fresca, ofertas irresistibles y la comodidad de tenerlo todo a solo un clic
				de distancia.
			</p>
		</div>

		{{-- Contact Section --}}
		<div class="p-5 sm:w-3/12 text-center sm:text-left">
			<h4 class="text-sm font-bold text-indigo-600 uppercase mb-3">Contacto</h4>
			<div class="space-y-2">
				<a class="block hover:text-indigo-600 transition-colors" href="tel:XXXXXXXX">
					XXX XXXX
				</a>
				<a class="block hover:text-indigo-600 transition-colors text-wrap" href="mailto:XXXX@gmail.com">
					XXXX@gmail.com
				</a>
			</div>
		</div>
	</div>
</footer>
