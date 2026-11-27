<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black tracking-tighter uppercase">Nueva contraseña</h2>
        <p class="text-base-content/60 mt-1 text-[10px] font-bold tracking-widest uppercase">
            Establece tus credenciales
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}" />

        <x-mary-input
            label="Correo"
            name="email"
            type="email"
            icon="o-envelope"
            value="{{ old('email', $request->email) }}"
            required
            autocomplete="username"
            class="bg-base-content/5 border-none focus:ring-red-600/20"
        />
        <x-base.input-error :messages="$errors->get('email')" class="mt-2" />

        <x-mary-input
            label="Nueva contraseña"
            name="password"
            type="password"
            icon="o-key"
            required
            autofocus
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
                label="Restablecer contraseña"
                class="btn-primary w-full rounded-2xl text-sm font-black tracking-widest uppercase"
            />
        </div>
    </form>
</x-guest-layout>
