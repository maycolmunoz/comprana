<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.partials.head', ['title' => 'Comprana | Donde la comodidad se encuentra'])
</head>

<body class="antialiased bg-base-100 hero-gradient text-base-content min-h-screen lg:h-screen lg:overflow-hidden">

    <nav class="absolute top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-6 lg:px-12">
        <x-base.brand href="/" :logo="asset('srcs/favicon.ico')" alt="Comprana" class="justify-start!" />

        <div class="flex items-center gap-2 lg:gap-4">
            @if (Route::has('login'))
                @auth
                    <x-mary-button
                        :link="route('products.index')"
                        class="btn-ghost btn-sm lg:btn-md font-bold"
                        label="Tienda"
                        icon="o-shopping-bag" />
                @else
                    <x-mary-button
                        :link="route('login')"
                        class="btn-ghost btn-sm lg:btn-md font-bold"
                        label="Entrar" />
                    @if (Route::has('register'))
                        <x-mary-button
                            :link="route('register')"
                            class="btn-primary btn-sm lg:btn-md font-bold shadow-lg"
                            label="Unirse" />
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <main class="flex flex-col lg:flex-row min-h-screen lg:h-full">
        <div class="w-full lg:w-3/5 flex items-center justify-center px-8 pb-12 pt-28 lg:p-20 order-2 lg:order-1">
            <div class="max-w-2xl">
                <div class="h-1.5 w-16 rounded-full bg-linear-to-r from-red-600 to-red-400 mb-6"></div>

                <h1 class="text-6xl lg:text-8xl font-black leading-[0.9] mb-8 tracking-tighter">
                    COMPRA<span class="text-red-600 text-glow">NA</span>
                </h1>

                <p class="text-xl lg:text-3xl text-base-content/60 mb-12 leading-tight font-normal max-w-lg">
                    Donde la <span class="font-bold text-base-content italic">comodidad</span> y la <span
                        class="font-bold text-base-content italic">variedad</span> se encuentran.
                </p>

                <x-mary-button
                    link="{{ route('products.index') }}"
                    icon="o-arrow-right-circle"
                    class="btn-primary btn-lg px-12 font-black text-sm uppercase tracking-widest shadow-2xl shadow-primary/20 hover:scale-105 transition-all"
                    label="Ir a la tienda" />
            </div>
        </div>

        <div class="w-full lg:w-2/5 h-[45vh] min-h-[320px] lg:h-full relative overflow-hidden order-1 lg:order-2">
            <div class="absolute inset-0 bg-linear-to-t lg:bg-linear-to-l from-transparent to-base-100 z-10"></div>
            <div class="h-full w-full clip-path-custom overflow-hidden">
                <img src="{{ asset('srcs/landing.jpeg') }}" alt="Comprana"
                    class="absolute inset-0 object-cover w-full h-full scale-105" />
            </div>
        </div>
    </main>
</body>

</html>
