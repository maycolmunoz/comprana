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
		[x-cloak] {
			display: none !important;
		}

		body {
			font-family: 'Outfit', sans-serif;
		}
	</style>

	<!-- Scripts -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-base-200/50 text-base-content min-h-screen flex flex-col">
	{{-- Navigation --}}
	@include('layouts.navigation')

	{{-- Page Heading --}}
	@if (isset($header))
		<header class="bg-base-100/50 backdrop-blur-md border-b border-base-content/5 py-6">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-2xl font-black tracking-tight uppercase">
					{{ $header }}
				</div>
			</div>
		</header>
	@endif

	{{-- Main Content --}}
	<main class="grow">
		{{ $slot }}
	</main>

	{{-- Footer --}}
	<x-commons.footer />

	{{-- Notification Toast --}}
	<div x-cloak x-data="{ open: false, timeout: null, message: '', type: 'success' }"
		x-on:notification.window=" 
            open = true;
            clearTimeout(timeout);
            message = $event.detail.message || $event.detail;
            type = $event.detail.type || 'success';
            timeout = setTimeout(() => { open = false; }, 5000);
        "
		x-show="open" x-transition:enter="transition ease-out duration-300"
		x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
		x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
		x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
		x-transition:leave-end="opacity-0" class="fixed z-[100] bottom-5 right-5 w-full max-w-sm px-4">

		<div
			class="bg-base-100 border border-base-content/10 shadow-2xl rounded-3xl p-4 flex items-center gap-4 backdrop-blur-xl">
			<div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0"
				:class="{
				    'bg-green-500/10 text-green-600': type === 'success',
				    'bg-red-500/10 text-red-600': type === 'error',
				    'bg-amber-500/10 text-amber-600': type === 'warning'
				}">
				<x-mary-icon name="o-check-circle" class="w-6 h-6" x-show="type === 'success'" />
				<x-mary-icon name="o-x-circle" class="w-6 h-6" x-show="type === 'error'" />
				<x-mary-icon name="o-exclamation-triangle" class="w-6 h-6" x-show="type === 'warning'" />
			</div>
			<div class="flex-grow">
				<p class="text-sm font-bold leading-tight" x-text="message"></p>
			</div>
			<button @click="open = false" class="opacity-30 hover:opacity-100 transition-opacity">
				<x-mary-icon name="o-x-mark" class="w-4 h-4" />
			</button>
		</div>
	</div>
</body>

</html>
