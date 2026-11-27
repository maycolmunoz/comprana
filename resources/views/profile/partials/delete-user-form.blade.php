<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-black tracking-tighter text-red-600 uppercase">Eliminar cuenta</h2>

        <p class="text-base-content/40 mt-1 text-[10px] leading-relaxed font-bold tracking-widest uppercase">
            Una vez que se elimine tu cuenta, todos sus recursos y datos se borrarán permanentemente. Por favor,
            descarga cualquier dato que desees conservar antes de proceder.
        </p>
    </header>

    <div class="pt-4">
        <x-mary-button
            label="Eliminar mi cuenta"
            icon="o-trash"
            class="btn-error btn-outline rounded-2xl px-8 text-xs font-black tracking-widest uppercase"
            onclick="document.getElementById('confirm-user-deletion').showModal()"
        />
    </div>

    <x-mary-modal id="confirm-user-deletion" title="Eliminar cuenta" class="backdrop-blur-xl" without-trap-focus>
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
            @csrf
            @method('delete')

            <p class="text-base-content/70 text-sm leading-relaxed">
                Esta acción es permanente. Introduce tu contraseña para confirmar que deseas eliminar tu cuenta de forma
                definitiva.
            </p>

            <div>
                <x-mary-input
                    label="Tu contraseña"
                    name="password"
                    type="password"
                    icon="o-lock-closed"
                    placeholder="Ingresa tu contraseña para confirmar"
                    class="bg-base-content/5 border-none focus:ring-red-600/20"
                />
                <x-base.input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="border-base-content/5 flex items-center justify-end gap-3 border-t pt-6">
                <x-mary-button
                    label="Cancelar"
                    type="button"
                    onclick="document.getElementById('confirm-user-deletion').close()"
                    class="btn-ghost rounded-xl text-xs font-black tracking-widest uppercase"
                />

                <x-mary-button
                    type="submit"
                    label="Sí, eliminar definitivamente"
                    icon="o-exclamation-triangle"
                    class="btn-error rounded-xl px-6 text-xs font-black tracking-widest uppercase"
                />
            </div>
        </form>
    </x-mary-modal>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.getElementById('confirm-user-deletion')?.showModal();
        </script>
    @endif
</section>
