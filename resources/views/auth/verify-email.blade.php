<x-guest-layout>
	<div class="mb-8 text-center">
		<h2 class="text-2xl font-black uppercase tracking-tighter">{{ __('Verifica tu cuenta') }}</h2>
		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-2 leading-relaxed px-4">
			{{ __('¡Gracias por unirte! Antes de comenzar, por favor verifica tu correo pulsando el enlace que te acabamos de enviar. Si no lo recibiste, con gusto te enviaremos otro.') }}
		</p>
	</div>

	@if (session('status') == 'verification-link-sent')
		<div
			class="mb-6 bg-green-500/10 border border-green-500/20 rounded-2xl p-4 text-[10px] font-bold uppercase tracking-widest text-green-600 text-center animate__animated animate__fadeIn">
			{{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
		</div>
	@endif

	<div class="space-y-4">
		<form method="POST" action="{{ route('verification.send') }}">
			@csrf
			<x-mary-button type="submit" label="{{ __('Reenviar Email de Verificación') }}" icon="o-paper-airplane"
				class="btn-primary w-full font-black text-sm uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl" />
		</form>

		<form method="POST" action="{{ route('logout') }}" class="text-center">
			@csrf
			<button type="submit"
				class="text-[10px] font-black uppercase tracking-widest text-base-content/40 hover:text-red-600 transition-colors">
				{{ __('Cerrar Sesión') }}
			</button>
		</form>
	</div>
</x-guest-layout>
