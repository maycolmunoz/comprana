<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name', 'Comprana') }}</title>
	<link rel="shortcut icon" href="{{ asset('srcs/favicon.ico') }}" type="image/x-icon">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">

	<style>
		body {
			font-family: 'Outfit', sans-serif;
		}
	</style>

	<!-- Scripts -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased text-base-content bg-base-200">
	<div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
		{{-- Background Decorative Elements --}}
		<div class="absolute top-0 left-0 w-full h-full -z-10 opacity-30">
			<div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-red-600/10 blur-[120px] rounded-full"></div>
			<div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-red-600/10 blur-[120px] rounded-full"></div>
		</div>

		{{-- Branding --}}
		<div class="mb-12 animate__animated animate__fadeInDown">
			<a href="/" class="flex flex-col items-center gap-4 group">
				<div
					class="p-4 bg-base-100 rounded-3xl shadow-xl border border-base-content/5 group-hover:scale-110 transition-transform duration-500">
					<x-ui.brand :logo="asset('srcs/favicon.ico')" alt="comprana" class="h-12 w-auto" />
				</div>
				<h1 class="text-3xl font-black tracking-tighter uppercase">
					COMPRA<span class="text-red-600">NA</span>
				</h1>
			</a>
		</div>

		{{-- Card Container --}}
		<div class="w-full sm:max-w-md animate__animated animate__fadeInUp animate__faster">
			<div
				class="bg-base-100/90 backdrop-blur-2xl px-8 py-10 shadow-2xl shadow-red-900/5 border border-white/50 rounded-[2.5rem] relative overflow-hidden">
				{{-- Subtle glass shine --}}
				<div class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-transparent via-red-600/20 to-transparent"></div>

				<div class="relative z-10">
					{{ $slot }}
				</div>
			</div>

			{{-- Footer Links --}}
			<div class="mt-8 text-center animate__animated animate__fadeIn animate__delay-1s">
				<p
					class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-30 py-2 border-t border-base-content/5 inline-block px-8">
					&copy; {{ date('Y') }} Comprana Premium Service
				</p>
			</div>
		</div>
	</div>
</body>

</html>
