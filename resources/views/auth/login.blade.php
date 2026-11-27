<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black tracking-tighter uppercase">Bienvenido de nuevo</h2>
        <p class="text-base-content/60 mt-1 text-[10px] font-bold tracking-widest uppercase">Ingresa a tu cuenta</p>
    </div>

    <x-base.auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <x-mary-input
            label="Correo"
            name="email"
            type="email"
            icon="o-envelope"
            value="{{ old('email') }}"
            required
            autofocus
            autocomplete="username"
            class="bg-base-content/5 border-none focus:ring-red-600/20"
        />
        <x-base.input-error :messages="$errors->get('email')" class="mt-2" />

        <x-mary-input
            label="Contraseña"
            name="password"
            type="password"
            icon="o-key"
            required
            autocomplete="current-password"
            class="bg-base-content/5 border-none focus:ring-red-600/20"
        />
        <x-base.input-error :messages="$errors->get('password')" class="mt-2" />

        <div class="flex items-center justify-between">
            <label for="remember_me" class="group inline-flex cursor-pointer items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="border-base-content/10 rounded-lg text-red-600 shadow-sm focus:ring-red-600/20"
                    name="remember"
                />
                <span class="text-base-content/40 ms-2 text-xs font-bold tracking-widest uppercase transition-colors group-hover:text-red-600">
                    Recordarme
                </span>
            </label>

            @if (Route::has('password.request'))
                <a
                    class="text-base-content/40 text-xs font-bold transition-colors hover:text-red-600"
                    href="{{ route('password.request') }}"
                >
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <x-mary-button
                type="submit"
                label="Iniciar sesión"
                class="btn-primary w-full rounded-2xl text-sm font-black tracking-widest uppercase"
            />
        </div>

        <div class="pt-2 text-center">
            <span class="text-base-content/60 text-[10px] font-bold tracking-widest uppercase">
                ¿No tienes cuenta?
            </span>
            <a
                href="{{ route('register') }}"
                class="ml-1 text-[10px] font-black tracking-widest text-red-600 uppercase hover:underline"
            >
                Regístrate ahora
            </a>
        </div>
    </form>
</x-guest-layout>
