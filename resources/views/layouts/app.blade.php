<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.partials.head')
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
</body>

</html>
