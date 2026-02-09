<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Comprana</title>
	<link rel="shortcut icon" href="{{ asset('srcs/favicon.ico') }}" type="image/x-icon">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.bunny.net">
	<link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

	<!-- Styles -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-base-200 animate__animated animate__fadeIn">

	@if (Route::has('login'))
		<nav class="z-10 flex flex-wrap gap-2 p-6 text-right sm:fixed sm:top-0 sm:right-0">
			@auth
				<x-mary-button link="{{ url('tienda') }}"
					class="relative px-3 py-1 text-base font-bold text-white transition duration-100 bg-black border-2 border-black rounded-sm hover:bg-gray-900 hover:text-yellow-500">
					Tienda
				</x-mary-button>
			@else
				<x-mary-button link="{{ route('login') }}"
					class="relative px-3 py-1 text-base font-bold text-white transition duration-100 bg-black border-2 border-black rounded-sm hover:bg-gray-900 hover:text-yellow-500">
					Iniciar Sesión
				</x-mary-button>
				@if (Route::has('register'))
					<x-mary-button link="{{ route('register') }}"
						class="relative px-3 py-1 text-base font-bold text-white transition duration-100 bg-black border-2 border-black rounded-sm hover:bg-gray-900 hover:text-yellow-500">
						Registrarse
					</x-mary-button>
				@endif
			@endauth
		</nav>
	@endif

	{{-- header --}}
	<section class="flex flex-wrap">
		<div class="w-full sm:w-8/12">
			<div class="container h-full mx-auto sm:p-10">
				<nav class="flex items-center justify-between px-4">
					<x-ui.brand href="/" logo="{{ asset('srcs/favicon.ico') }}" logoClass="rounded-full" alt="comprana" />
				</nav>

				<header class="container items-center h-full px-4 mt-10 lg:flex lg:mt-0">
					<div class="w-full">
						<div class="text-6xl font-bold animate__animated animate__flipInX">
							COMPRA<span class="text-red-700">NA</span>
							<div class="w-20 h-2 my-4 bg-red-700"></div>
						</div>
						<h1 class="text-4xl font-bold lg:text-6xl">
							Donde la Comodidad y la Variedad se Encuentran.
						</h1>

						<x-mary-button link="{{ route('products.index') }}" icon="s-shopping-bag"
							class="btn-primary w-full font-bold text-lg hover:-translate-y-1 hover:animate-pulse ">
							TIENDA
						</x-mary-button>
					</div>
				</header>
			</div>
		</div>
		<img src="{{ asset('srcs/landing.jpeg') }}" alt="Leafs"
			class="absolute sm:relative -z-10 opacity-70 sm:opacity-100 object-cover w-full h-48 sm:h-screen sm:w-4/12">
	</section>
</body>

</html>
