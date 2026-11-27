<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black tracking-tighter uppercase">¿Olvidaste tu contraseña?</h2>
        <p class="text-base-content/60 mt-2 text-[10px] leading-relaxed font-bold tracking-widest uppercase">
            No hay problema. Ingresa tu correo y te enviaremos un enlace para restablecerla.
        </p>
    </div>

    <x-base.auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <x-mary-input
            label="Correo de recuperación"
            name="email"
            type="email"
            icon="o-envelope"
            value="{{ old('email') }}"
            required
            autofocus
            class="bg-base-content/5 border-none focus:ring-red-600/20"
        />
        <x-base.input-error :messages="$errors->get('email')" class="mt-2" />

        <div class="pt-4">
            <x-mary-button
                type="submit"
                label="Enviar enlace de restauración"
                class="btn-primary w-full rounded-2xl text-sm font-black tracking-widest uppercase"
            />
        </div>

        <div class="pt-2 text-center">
            <a
                href="{{ route('login') }}"
                class="text-base-content/60 text-[10px] font-black tracking-widest uppercase transition-colors hover:text-red-600"
            >
                ← Volver al inicio de sesión
            </a>
        </div>
    </form>
</x-guest-layout>
