<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Comprana</title>
        <link rel="shortcut icon" href="{{asset('srcs/favicon.ico')}}" type="image/x-icon">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .bg-image {
                background-image: url({{asset('srcs/bg.jfif')}})
            }
        </style>
    </head>
    <body class="antialiased bg-yellow-400 animate__animated animate__fadeIn bg-opacity-85">
     
            @if(Route::has('login'))
                <nav class="z-10 flex flex-wrap gap-2 p-6 text-right sm:fixed sm:top-0 sm:right-0">   
                    @auth
                        <a href="{{ url('tienda') }}" class="relative">
                            <span class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-gray-700 rounded-sm"></span>
                            <span class="relative inline-block w-full h-full px-3 py-1 text-base font-bold text-white transition duration-100 bg-black border-2 border-black rounded-sm fold-bold hover:bg-gray-900 hover:text-yellow-500 dark:bg-black">{{__('Tienda')}}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="relative ">
                            <span class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-gray-700 rounded-sm "></span>
                            <span class="relative inline-block w-full h-full px-3 py-1 text-base font-bold text-white transition duration-100 bg-black border-2 border-black rounded-sm fold-bold hover:bg-gray-900 hover:text-yellow-500 dark:bg-black">{{__('Login')}}</span>
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="relative">
                                <span class="absolute top-0 left-0 w-full h-full mt-1 ml-1 bg-gray-700 rounded-sm"></span>
                                <span class="relative inline-block w-full h-full px-3 py-1 text-base font-bold text-white transition duration-100 bg-black border-2 border-black rounded-sm fold-bold hover:bg-gray-900 hover:text-yellow-500 dark:bg-black">{{__('Register')}}</span>
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif

    {{-- header --}}
    <section class="flex flex-wrap ">
        <div class="w-full sm:w-8/12">
            <div class="container h-full mx-auto sm:p-10">
                <nav class="flex items-center justify-between px-4">
                    <div class="text-4xl font-bold animate__animated animate__flipInX">
                        Compra<span class="text-red-700">na</span>
                    </div>
                </nav>
                <header class="container items-center h-full px-4 mt-10 lg:flex lg:mt-0 ">
                    <div class="w-full">
                        <h1 class="text-4xl font-bold lg:text-6xl">Donde la Comodidad y la Variedad se Encuentran.
                        </h1>
                        <div class="w-20 h-2 my-4 bg-red-700"></div>
                        <p class="mb-10 text-xl">
                            <span class="text-red-600 ">Supermercado</span> virtual lleno de productos frescos, ofertas exclusivas y sin salir de casa. Explora nuestro extenso catálogo, elige entre una amplia gama de productos y disfruta de la facilidad de recibir tus compras directamente en tu puerta.
                        </p>
                        <a href="{{route('products.index')}}"
                        class="flex flex-row items-center justify-center w-full px-4 py-4 mb-4 font-bold leading-6 text-yellow-500 capitalize duration-100 transform bg-red-700 rounded-xs shadow-sm cursor-pointer text-md focus:ring-4 focus:ring-red-500 focus:ring-opacity-50 focus:outline-hidden sm:mb-0 sm:w-auto hover:shadow-lg hover:-translate-y-1 hover:animate-pulse">
                        TIENDA
                        <span class="ml-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#F1C40F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 9v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9"/><path d="M9 22V12h6v10M2 10.6L12 2l10 8.6"/></svg>
                        </span>
                    </a>
                    </div>
                </header>
            </div>
        </div>
        <img src="{{asset('srcs/landing.jpeg')}}" alt="Leafs" class="absolute sm:relative  -z-10 opacity-70 sm:opacity-100 object-cover w-full h-48 sm:h-screen sm:w-4/12">
    </section>


    {{-- details --}}
    <section class="max-w-full p-5 mx-auto sm:p-10 md:p-16 bg-image ">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3 sm:grid-cols-2">

            <div class="relative flex items-end justify-start w-full text-left bg-center bg-cover shadow-2xl shadow-red-700"
                style="height: 450px; background-image:url({{asset('srcs/img1.webp')}});">
                <div class="absolute top-0 bottom-0 left-0 right-0 mt-20 bg-linear-to-b from-transparent to-gray-900">
                </div>
                <div class="absolute top-0 left-0 right-0 flex items-center justify-between mx-5 mt-2">
                    <a href="{{route('products.index', ['section'=> 'Bebidas'])}}"
                        class="px-5 py-2 text-xs text-white uppercase transition duration-500 ease-in-out bg-indigo-600 hover:bg-white hover:text-indigo-600">Ver Más
                    </a>
                </div>
                <main class="z-10 p-5">
                    <p  class="font-medium leading-7 tracking-tight text-white text-md font-regular ">
                        Refresca tu día con nuestra selección de bebidas irresistibles. Desde jugos naturales hasta bebidas energéticas, Comprana tiene todo lo que necesitas para mantenerte hidratado y lleno de energía.
                    </p>
                </main>

            </div>

            <div class="relative flex items-end justify-start w-full text-left bg-center bg-cover shadow-2xl shadow-yellow-700"
                style="height: 450px; background-image:url({{asset('srcs/img2.webp')}});">
                <div class="absolute top-0 bottom-0 left-0 right-0 mt-20 bg-linear-to-b from-transparent to-gray-900">
                </div>
                <div class="absolute top-0 left-0 right-0 flex items-center justify-between mx-5 mt-2">
                    <a href="{{route('products.index', ['section'=> 'Frutas y Verduras'])}}"
                        class="px-5 py-2 text-xs text-white uppercase transition duration-500 ease-in-out bg-indigo-600 hover:bg-white hover:text-indigo-600">Ver Más
                    </a>

                </div>
                <main class="z-10 p-5">
                    <p href="#"
                        class="font-medium leading-7 tracking-tight text-white text-md font-regular">
                        Descubre la frescura y la calidad de nuestras verduras seleccionadas cuidadosamente. Comprana te brinda opciones saludables para cada comida.
                    </p>
                </main>
            </div>

            <div class="relative flex items-end justify-start w-full text-left bg-center bg-cover shadow-2xl shadow-cyan-700"
                style="height: 450px; background-image:url({{asset('srcs/img4.webp')}});">
                <div class="absolute top-0 bottom-0 left-0 right-0 mt-20 bg-linear-to-b from-transparent to-gray-900">
                </div>
                <div class="absolute top-0 left-0 right-0 flex items-center justify-between mx-5 mt-2">
                    <a href="{{route('products.index')}}"
                        class="px-5 py-2 text-xs text-white uppercase transition duration-500 ease-in-out bg-indigo-600 hover:bg-white hover:text-indigo-600">Ver Más
                    </a>

                </div>
                <main class="z-10 p-5">
                    <p 
                        class="font-medium leading-7 tracking-tight text-white text-md font-regular ">
                        Desde cereales hasta productos de limpieza, nuestra selección cuidadosa garantiza que tengas todo lo que necesitas, entregado directamente en tu puerta.
                    </p>
                </main>
            </div>

        </div>
    </section>

    <!-- Testimonials section -->
    <section class="py-16 text-black ">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="mb-8 text-4xl font-bold">Testimonios</h2>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div class="p-8 bg-white rounded-lg shadow-sm">
                    <p class="mb-4 text-gray-700">
                        "¡Comprana ha transformado la forma en que hago mis compras! La frescura de los productos y la rapidez de la entrega son increíbles. ¡Ahora puedo dedicar más tiempo a lo que realmente importa!"
                    </p>
                    <p class="font-medium text-gray-700">- María G.</p>
                </div>
                <div class="p-8 bg-white rounded-lg shadow-sm">
                    <p class="mb-4 text-gray-700">
                        "Siempre busco las ofertas de Comprana. Los precios son geniales, y la calidad de los productos es insuperable. ¡Una experiencia de compra en línea que realmente vale la pena!"
                    </p>
                    <p class="font-medium text-gray-700">- Juan R.</p>
                </div>
                <div class="p-8 bg-white rounded-lg shadow-sm">
                    <p class="mb-4 text-gray-700">
                        "Comprana ha simplificado mi vida. No tengo que preocuparme por cargar bolsas pesadas ni hacer filas. Todo llega directamente a mi puerta. ¡Tan conveniente y confiable!"
                    </p>
                    <p class="font-medium text-gray-700">- Ana M.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- footer --}}
    <x-commons.footer/>
    
    </body>
</html>
