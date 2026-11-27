<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black tracking-tighter uppercase">Área segura</h2>
        <p class="text-base-content/60 mt-1 text-[10px] font-bold tracking-widest uppercase">
            Por favor, confirma tu contraseña antes de continuar.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <x-mary-input
            label="Tu contraseña"
            name="password"
            type="password"
            icon="o-lock-closed"
            required
            autocomplete="current-password"
            class="bg-base-content/5 border-none focus:ring-red-600/20"
        />
        <x-base.input-error :messages="$errors->get('password')" class="mt-2" />

        <div class="pt-4 text-center">
            <x-mary-button
                type="submit"
                label="Confirmar acceso"
                class="btn-primary w-full rounded-2xl text-sm font-black tracking-widest uppercase"
            />
        </div>
    </form>
</x-guest-layout>
