<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black tracking-tighter uppercase">Crea tu cuenta</h2>
        <p class="text-base-content/60 mt-1 text-[10px] font-bold tracking-widest uppercase">
            Únete a la mejor experiencia
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <x-mary-input
            label="Nombre completo"
            name="name"
            type="text"
            icon="o-user"
            value="{{ old('name') }}"
            required
            autofocus
            autocomplete="name"
            class="bg-base-content/5 border-none focus:ring-red-600/20"
        />
        <x-base.input-error :messages="$errors->get('name')" class="mt-2" />

        <x-mary-input
            label="Correo"
            name="email"
            type="email"
            icon="o-envelope"
            value="{{ old('email') }}"
            required
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
            autocomplete="new-password"
            class="bg-base-content/5 border-none focus:ring-red-600/20"
        />
        <x-base.input-error :messages="$errors->get('password')" class="mt-2" />

        <x-mary-input
            label="Confirmar contraseña"
            name="password_confirmation"
            type="password"
            icon="o-check-badge"
            required
            autocomplete="new-password"
            class="bg-base-content/5 border-none focus:ring-red-600/20"
        />
        <x-base.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

        <div class="pt-4">
            <x-mary-button
                type="submit"
                label="Crear mi cuenta"
                class="btn-primary w-full rounded-2xl text-sm font-black tracking-widest uppercase"
            />
        </div>

        <div class="pt-2 text-center">
            <span class="text-base-content/60 text-[10px] font-bold tracking-widest uppercase">
                ¿Ya tienes cuenta?
            </span>
            <a
                href="{{ route('login') }}"
                class="ml-1 text-[10px] font-black tracking-widest text-red-600 uppercase hover:underline"
            >
                Entra aquí
            </a>
        </div>
    </form>
</x-guest-layout>
