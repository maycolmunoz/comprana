<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head', ['title' => 'Comprana | Donde la comodidad se encuentra'])
</head>

<body class="bg-base-100 hero-gradient text-base-content min-h-screen antialiased lg:h-screen lg:overflow-hidden">
    <nav class="absolute top-0 right-0 left-0 z-50 flex items-center justify-between px-6 py-6 lg:px-12">
        <x-base.brand href="/" :logo="asset('srcs/favicon.ico')" alt="Comprana" class="justify-start!" />

        <div class="flex items-center gap-2 lg:gap-4">
            @if (Route::has('login'))
                @auth
                    <x-mary-button
                        :link="route('products.index')"
                        class="btn-ghost btn-sm lg:btn-md font-bold"
                        label="Tienda"
                        icon="o-shopping-bag"
                    />
                @else
                    <x-mary-button :link="route('login')" class="btn-ghost btn-sm lg:btn-md font-bold" label="Entrar" />
                    @if (Route::has('register'))
                        <x-mary-button
                            :link="route('register')"
                            class="btn-primary btn-sm lg:btn-md font-bold shadow-lg"
                            label="Unirse"
                        />
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <main class="flex min-h-screen flex-col lg:h-full lg:flex-row">
        <div class="order-2 flex w-full items-center justify-center px-8 pt-28 pb-12 lg:order-1 lg:w-3/5 lg:p-20">
            <div class="max-w-2xl">
                <div class="mb-6 h-1.5 w-16 rounded-full bg-linear-to-r from-red-600 to-red-400"></div>

                <h1 class="mb-8 text-6xl leading-[0.9] font-black tracking-tighter lg:text-8xl">
                    COMPRA<span class="text-glow text-red-600">NA</span>
                </h1>

                <p class="text-base-content/60 mb-12 max-w-lg text-xl leading-tight font-normal lg:text-3xl">
                    Donde la <span class="text-base-content font-bold italic">comodidad</span> y la
                    <span class="text-base-content font-bold italic">variedad</span> se encuentran.
                </p>

                <x-mary-button
                    link="{{ route('products.index') }}"
                    icon="o-arrow-right-circle"
                    class="btn-primary btn-lg shadow-primary/20 px-12 text-sm font-black tracking-widest uppercase shadow-2xl transition-all hover:scale-105"
                    label="Ir a la tienda"
                />
            </div>
        </div>

        <div class="relative order-1 h-[45vh] min-h-[320px] w-full overflow-hidden lg:order-2 lg:h-full lg:w-2/5">
            <div class="to-base-100 absolute inset-0 z-10 bg-linear-to-t from-transparent lg:bg-linear-to-l"></div>
            <div class="clip-path-custom h-full w-full overflow-hidden">
                <img
                    src="{{ asset('srcs/landing.jpeg') }}"
                    alt="Comprana"
                    class="absolute inset-0 h-full w-full scale-105 object-cover"
                />
            </div>
        </div>
    </main>
</body>
</html>
