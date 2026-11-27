<section>
    <header class="mb-8">
        <h2 class="text-base-content text-2xl font-black tracking-tighter uppercase">Cambiar contraseña</h2>

        <p class="text-base-content/40 mt-1 text-[10px] font-bold tracking-widest uppercase">
            Asegúrate de usar una contraseña larga y aleatoria para mantener tu cuenta segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-mary-input
                label="Contraseña actual"
                name="current_password"
                type="password"
                icon="o-lock-closed"
                autocomplete="current-password"
                class="bg-base-content/5 border-none focus:ring-red-600/20"
            />
            <x-base.input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-mary-input
                    label="Nueva contraseña"
                    name="password"
                    type="password"
                    icon="o-key"
                    autocomplete="new-password"
                    class="bg-base-content/5 border-none focus:ring-red-600/20"
                />
                <x-base.input-error :messages="$errors->updatePassword->get('password')" />
            </div>

            <div>
                <x-mary-input
                    label="Confirmar nueva contraseña"
                    name="password_confirmation"
                    type="password"
                    icon="o-check-badge"
                    autocomplete="new-password"
                    class="bg-base-content/5 border-none focus:ring-red-600/20"
                />
                <x-base.input-error :messages="$errors->updatePassword->get('password_confirmation')" />
            </div>
        </div>

        <div class="border-base-content/5 flex items-center gap-4 border-t pt-4">
            <x-mary-button
                type="submit"
                label="Actualizar contraseña"
                icon="o-shield-check"
                class="btn-primary rounded-2xl px-8 text-xs font-black tracking-widest uppercase"
            />

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => (show = false), 3000)"
                    class="text-success flex items-center gap-2"
                >
                    <x-mary-icon name="o-check-circle" class="h-4 w-4" />
                    <span class="text-[10px] font-black tracking-widest uppercase">Guardado</span>
                </div>
            @endif
        </div>
    </form>
</section>
