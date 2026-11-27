<section>
    <header class="mb-8">
        <h2 class="text-base-content text-2xl font-black tracking-tighter uppercase">Información del perfil</h2>

        <p class="text-base-content/40 mt-1 text-[10px] font-bold tracking-widest uppercase">
            Actualiza la información de tu perfil y dirección de correo electrónico.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-mary-input
                    label="Nombre"
                    name="name"
                    type="text"
                    icon="o-user"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="bg-base-content/5 border-none focus:ring-red-600/20"
                />
                <x-base.input-error :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <x-mary-input
                    label="Correo"
                    name="email"
                    type="email"
                    icon="o-envelope"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                    class="bg-base-content/5 border-none focus:ring-red-600/20"
                />
                <x-base.input-error :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="bg-warning/10 border-warning/20 rounded-xl border px-4 py-2">
                        <p class="text-warning text-[10px] font-black tracking-widest uppercase">
                            Tu correo no está verificado.
                            <button
                                form="send-verification"
                                class="ml-2 uppercase underline transition-colors hover:underline"
                            >
                                Reenviar verificación
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="text-success mt-1 text-[8px] font-bold tracking-widest uppercase">
                                Se ha enviado un nuevo enlace a tu correo.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-mary-input
                    label="Teléfono"
                    name="phone"
                    type="text"
                    icon="o-phone"
                    value="{{ old('phone', $user->phone) }}"
                    placeholder="Ej: 300 123 4567"
                    class="bg-base-content/5 border-none focus:ring-red-600/20"
                />
                <x-base.input-error :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-mary-input
                    label="Dirección"
                    name="address"
                    type="text"
                    icon="o-map-pin"
                    value="{{ old('address', $user->address) }}"
                    placeholder="Ej: Calle 123 #45-67, Bogotá"
                    class="bg-base-content/5 border-none focus:ring-red-600/20"
                />
                <x-base.input-error :messages="$errors->get('address')" />
            </div>
        </div>

        <div class="border-base-content/5 flex items-center gap-4 border-t pt-4">
            <x-mary-button
                type="submit"
                label="Guardar cambios"
                icon="o-check"
                class="btn-primary rounded-2xl px-8 text-xs font-black tracking-widest uppercase"
            />

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => (show = false), 3000)"
                    class="text-success flex items-center gap-2"
                >
                    <x-mary-icon name="o-check-circle" class="h-4 w-4" />
                    <span class="text-[10px] font-black tracking-widest uppercase">Actualizado con éxito</span>
                </div>
            @endif
        </div>
    </form>
</section>
