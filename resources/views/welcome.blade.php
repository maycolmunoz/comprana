<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Comprana | Donde la comodidad se encuentra</title>
	<link rel="shortcut icon" href="{{ asset('srcs/favicon.ico') }}" type="image/x-icon">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

	<style>
		body {
			font-family: 'Outfit', sans-serif;
		}

		.hero-gradient {
			background: radial-gradient(circle at 0% 0%, rgba(185, 28, 28, 0.03) 0%, transparent 50%),
				radial-gradient(circle at 100% 100%, rgba(185, 28, 28, 0.03) 0%, transparent 50%);
		}

		.text-glow {
			text-shadow: 0 0 20px rgba(185, 28, 28, 0.15);
		}

		.clip-path-custom {
			clip-path: polygon(15% 0, 100% 0%, 100% 100%, 0% 100%);
		}

		@media (max-width: 1024px) {
			.clip-path-custom {
				clip-path: none;
			}
		}
	</style>

	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-base-100 h-screen overflow-hidden hero-gradient text-base-content">

	<!-- Navbar -->
	<nav class="absolute top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-6 lg:px-12">
		<div class="animate__animated animate__fadeInDown">
			<x-ui.brand href="/" :logo="asset('srcs/favicon.ico')" alt="comprana" class="!justify-start" />
		</div>

		<div class="flex items-center gap-2 lg:gap-4 animate__animated animate__fadeInDown">
			@if (Route::has('login'))
				@auth
					<x-mary-button :link="url('tienda')" class="btn-ghost btn-sm lg:btn-md font-bold" label="Tienda"
						icon="o-shopping-bag" />
				@else
					<x-mary-button :link="route('login')" class="btn-ghost btn-sm lg:btn-md font-bold" label="Entrar" />
					@if (Route::has('register'))
						<x-mary-button :link="route('register')" class="btn-primary btn-sm lg:btn-md font-bold shadow-lg" label="Unirse" />
					@endif
				@endauth
			@endif
		</div>
	</nav>

	<main class="flex flex-col lg:flex-row h-full">
		<!-- Content Area -->
		<div class="w-full lg:w-3/5 flex items-center justify-center p-8 lg:p-20 order-2 lg:order-1 h-full">
			<div class="max-w-2xl animate__animated animate__fadeInLeft">
				<x-mary-badge value="Experiencia Premium" class="badge-primary badge-soft text-xl p-4" />

				<h1 class="text-6xl lg:text-8xl font-black leading-[0.9] mb-8 tracking-tighter">
					COMPRA<span class="text-red-700 text-glow">NA</span>
				</h1>

				<p class="text-xl lg:text-3xl text-base-content/60 mb-12 leading-tight font-light max-w-lg">
					Donde la <span class="font-bold text-base-content italic">comodidad</span> y la <span
						class="font-bold text-base-content italic">variedad</span> se encuentran.
				</p>

				<div class="flex flex-col sm:flex-row gap-5 items-start sm:items-center">
					<x-mary-button link="{{ route('products.index') }}" icon="o-arrow-right-circle"
						class="btn-primary btn-lg px-12 font-black text-lg shadow-2xl shadow-primary/20 hover:scale-105 transition-all group"
						label="IR A LA TIENDA" />

					<span class="text-sm font-medium opacity-40 hidden sm:block">|</span>

					<div class="flex items-center gap-3 opacity-70 hover:opacity-100 transition-opacity cursor-default">
						<div class="flex -space-x-3">
							<div
								class="w-8 h-8 rounded-full border-2 border-base-100 bg-base-200 text-[10px] flex items-center justify-center font-bold font-mono">
								+1k</div>
						</div>
						<p class="text-[10px] uppercase font-bold tracking-widest leading-none">Clientes<br>felices</p>
					</div>
				</div>

				<!-- Subtle stats or footer info -->
				<div
					class="mt-20 flex gap-10 items-center border-t border-base-200 pt-10 animate__animated animate__fadeInUp animate__delay-1s opacity-50">
					<div class="flex flex-col">
						<span class="text-xl font-black">24/7</span>
						<span class="text-[9px] uppercase tracking-widest font-bold">Atención</span>
					</div>
					<div class="flex flex-col">
						<span class="text-xl font-black">SECURE</span>
						<span class="text-[9px] uppercase tracking-widest font-bold">Check-out</span>
					</div>
					<div class="flex flex-col">
						<span class="text-xl font-black">LOCAL</span>
						<span class="text-[9px] uppercase tracking-widest font-bold">Envío</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Image Area -->
		<div class="w-full lg:w-2/5 h-full relative overflow-hidden order-1 lg:order-2">
			<div class="absolute inset-0 bg-linear-to-t lg:bg-linear-to-l from-transparent to-base-100 z-10"></div>
			<div class="h-full w-full clip-path-custom overflow-hidden">
				<img src="{{ asset('srcs/landing.jpeg') }}" alt="Comprana"
					class="absolute inset-0 object-cover w-full h-full scale-105" />
			</div>
		</div>
	</main>
</body>

</html>
