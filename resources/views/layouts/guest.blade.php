<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.partials.head')
</head>

<body class="antialiased text-base-content bg-base-200">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full -z-10 opacity-30">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-red-600/10 blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-red-600/10 blur-[120px] rounded-full"></div>
        </div>

        <div class="mb-12">
            <a href="/" class="flex flex-col items-center gap-4 group">
                <div
                    class="p-4 bg-base-100 rounded-3xl shadow-xl border border-base-content/5 group-hover:scale-110 transition-transform duration-500">
                    <x-base.brand :logo="asset('srcs/favicon.ico')" alt="comprana" class="h-12 w-auto" />
                </div>
                <h1 class="text-3xl font-black tracking-tighter uppercase">
                    COMPRA<span class="text-red-600">NA</span>
                </h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md">
            <div
                class="bg-base-100/90 backdrop-blur-2xl px-8 py-10 shadow-2xl shadow-red-900/5 border border-white/50 rounded-[2.5rem] relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-transparent via-red-600/20 to-transparent"></div>

                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>

            <div class="mt-8 text-center">
                <p
                    class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-30 py-2 border-t border-base-content/5 inline-block px-8">
                    &copy; {{ date('Y') }} Comprana
                </p>
            </div>
        </div>
    </div>
</body>

</html>
