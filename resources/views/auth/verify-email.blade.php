<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black tracking-tighter uppercase">Verifica tu cuenta</h2>
        <p class="text-base-content/60 mt-2 px-4 text-[10px] leading-relaxed font-bold tracking-widest uppercase">
            ¡Gracias por unirte! Antes de comenzar, por favor verifica tu correo pulsando el enlace que te acabamos de
            enviar. Si no lo recibiste, con gusto te enviaremos otro.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-success/10 border-success/20 text-success mb-6 rounded-2xl border p-4 text-center text-[10px] font-bold tracking-widest uppercase">
            Se ha enviado un nuevo enlace de verificación a tu correo.
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-mary-button
                type="submit"
                label="Reenviar correo de verificación"
                icon="o-paper-airplane"
                class="btn-primary w-full rounded-2xl text-sm font-black tracking-widest uppercase"
            />
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button
                type="submit"
                class="text-base-content/60 text-[10px] font-black tracking-widest uppercase transition-colors hover:text-red-600"
            >
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
