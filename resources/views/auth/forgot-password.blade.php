<x-guest-layout>
	<div class="mb-8 text-center">
		<h2 class="text-2xl font-black uppercase tracking-tighter">{{ __('¿Contraseña olvidada?') }}</h2>
		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-2 leading-relaxed">
			{{ __('No hay problema. Ingresa tu correo y te enviaremos un enlace premium para restablecerla.') }}
		</p>
	</div>

	<!-- Session Status -->
	<x-auth-session-status class="mb-6" :status="session('status')" />

	<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
		@csrf

		<x-mary-input label="{{ __('Email de recuperación') }}" name="email" type="email" icon="o-envelope"
			value="{{ old('email') }}" required autofocus class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('email')" class="mt-2" />

		<div class="pt-4">
			<x-mary-button type="submit" label="{{ __('Enviar enlace de restauración') }}"
				class="btn-primary w-full font-black text-sm uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl" />
		</div>

		<div class="text-center pt-2">
			<a href="{{ route('login') }}"
				class="text-[10px] font-black uppercase tracking-widest text-base-content/40 hover:text-red-600 transition-colors">
				{{ __('← Volver al inicio de sesión') }}
			</a>
		</div>
	</form>
</x-guest-layout>
