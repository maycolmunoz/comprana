<section>
    <header class="mb-8">
        <h2 class="text-2xl font-black uppercase tracking-tighter text-base-content">
            Cambiar contraseña
        </h2>

        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">
            Asegúrate de usar una contraseña larga y aleatoria para mantener tu cuenta segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-mary-input label="Contraseña actual" name="current_password" type="password" icon="o-lock-closed"
                autocomplete="current-password" class="bg-base-content/5 border-none focus:ring-red-600/20" />
            <x-base.input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-mary-input label="Nueva contraseña" name="password" type="password" icon="o-key"
                    autocomplete="new-password" class="bg-base-content/5 border-none focus:ring-red-600/20" />
                <x-base.input-error :messages="$errors->updatePassword->get('password')" />
            </div>

            <div>
                <x-mary-input label="Confirmar nueva contraseña" name="password_confirmation" type="password"
                    icon="o-check-badge" autocomplete="new-password" class="bg-base-content/5 border-none focus:ring-red-600/20" />
                <x-base.input-error :messages="$errors->updatePassword->get('password_confirmation')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-base-content/5">
            <x-mary-button type="submit" label="Actualizar contraseña" icon="o-shield-check"
                class="btn-primary px-8 font-black text-xs uppercase tracking-widest rounded-2xl" />

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-2 text-success">
                    <x-mary-icon name="o-check-circle" class="w-4 h-4" />
                    <span class="text-[10px] font-black uppercase tracking-widest">Guardado</span>
                </div>
            @endif
        </div>
    </form>
</section>
