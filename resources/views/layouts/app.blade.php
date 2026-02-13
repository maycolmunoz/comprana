<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="shortcut icon" href="{{asset('srcs/favicon.ico')}}" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-base-200">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-base-100 shadow-sm">
                    <div class="px-4 py-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>


         {{-- component  --}}
    <div x-cloak x-data="{open: false, timeout: null, message: ''}" 
    x-on:notification.window=" 
    open = true;
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        open = false;
    }, 5000);
    message = $event.detail
    " 
     x-show="open" x-transition.duration.300ms class="fixed z-50 px-4 py-2 text-white transition bg-green-500 rounded-md right-4 top-4 hover:bg-green-600">
        <div class="flex items-center space-x-2">
            <span class="text-3xl"><i class="bx bx-check"></i></span>
            <p class="font-bold" x-text="message"></p>
        </div>
    </div>





        





    </body>
</html>
