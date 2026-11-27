<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>

<body class="text-base-content bg-base-200 antialiased">
    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden p-6">
        <div class="absolute top-0 left-0 -z-10 h-full w-full opacity-30">
            <div class="absolute top-[-10%] left-[-10%] h-[40%] w-[40%] rounded-full bg-red-600/10 blur-[120px]"></div>
            <div class="absolute right-[-10%] bottom-[-10%] h-[40%] w-[40%] rounded-full bg-red-600/10 blur-[120px]"></div>
        </div>

        <div class="mb-12">
            <a href="/" class="group flex flex-col items-center gap-4">
                <div class="bg-base-100 border-base-content/5 rounded-3xl border p-4 shadow-xl transition-transform duration-500 group-hover:scale-110">
                    <x-base.brand :logo="asset('srcs/favicon.ico')" alt="comprana" class="h-12 w-auto" />
                </div>
                <h1 class="text-3xl font-black tracking-tighter uppercase">
                    COMPRA<span class="text-red-600">NA</span>
                </h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md">
            <div class="bg-base-100/90 relative overflow-hidden rounded-[2.5rem] border border-white/50 px-8 py-10 shadow-2xl shadow-red-900/5 backdrop-blur-2xl">
                <div class="absolute top-0 left-0 h-1 w-full bg-linear-to-r from-transparent via-red-600/20 to-transparent"></div>

                <div class="relative z-10">{{ $slot }}</div>
            </div>

            <div class="mt-8 text-center">
                <p class="border-base-content/5 inline-block border-t px-8 py-2 text-[10px] font-bold tracking-[0.3em] uppercase opacity-30">
                    &copy; {{ date('Y') }} Comprana
                </p>
            </div>
        </div>
    </div>
</body>
</html>
