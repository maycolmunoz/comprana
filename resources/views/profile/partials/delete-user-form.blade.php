<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-black uppercase tracking-tighter text-red-600">
            Eliminar cuenta
        </h2>

        <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1 leading-relaxed">
            Una vez que se elimine tu cuenta, todos sus recursos y datos se borrarán permanentemente. Por favor,
            descarga cualquier dato que desees conservar antes de proceder.
        </p>
    </header>

    <div class="pt-4">
        <x-mary-button label="Eliminar mi cuenta" icon="o-trash"
            class="btn-error btn-outline font-black text-xs uppercase tracking-widest rounded-2xl px-8"
            onclick="document.getElementById('confirm-user-deletion').showModal()" />
    </div>

    <x-mary-modal id="confirm-user-deletion" title="Eliminar cuenta" class="backdrop-blur-xl">
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
            @csrf
            @method('delete')

            <p class="text-sm text-base-content/70 leading-relaxed">
                Esta acción es permanente. Introduce tu contraseña para confirmar que deseas eliminar tu cuenta de
                forma definitiva.
            </p>

            <div>
                <x-mary-input label="Tu contraseña" name="password" type="password" icon="o-lock-closed"
                    placeholder="Ingresa tu contraseña para confirmar"
                    class="bg-base-content/5 border-none focus:ring-red-600/20" />
                <x-base.input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-base-content/5">
                <x-mary-button label="Cancelar" type="button"
                    onclick="document.getElementById('confirm-user-deletion').close()"
                    class="btn-ghost font-black text-xs uppercase tracking-widest rounded-xl" />

                <x-mary-button type="submit" label="Sí, eliminar definitivamente" icon="o-exclamation-triangle"
                    class="btn-error font-black text-xs uppercase tracking-widest rounded-xl px-6" />
            </div>
        </form>
    </x-mary-modal>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.getElementById('confirm-user-deletion')?.showModal();
        </script>
    @endif
</section>
