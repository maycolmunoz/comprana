<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>

<body class="bg-base-200/50 text-base-content flex min-h-screen flex-col antialiased">
    @include('layouts.navigation')

    @if (isset($header))
        <header class="bg-base-100/50 border-base-content/5 border-b py-6 backdrop-blur-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-2xl font-black tracking-tight uppercase">{{ $header }}</div>
            </div>
        </header>
    @endif

    <main class="grow">{{ $slot }}</main>

    <x-base.footer />

    <x-base.notification-toast />
</body>
</html>
