<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Comprana') }}</title>
    <link rel="shortcut icon" href="{{ asset('srcs/favicon.ico') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles()
</head>

<body class="antialiased bg-base-200/50 text-base-content min-h-screen flex flex-col">
    @include('layouts.navigation')

    @if (isset($header))
        <header class="bg-base-100/50 backdrop-blur-md border-b border-base-content/5 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-2xl font-black tracking-tight uppercase">
                    {{ $header }}
                </div>
            </div>
        </header>
    @endif

    <main class="grow">
        {{ $slot }}
    </main>

    <x-base.footer />



    <x-base.notification-toast />

    @livewireScriptConfig()
</body>

</html>
