<x-guest-layout>
	<div class="mb-8 text-center">
		<h2 class="text-2xl font-black uppercase tracking-tighter">¿Contraseña olvidada?</h2>
		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-2 leading-relaxed">
			No hay problema. Ingresa tu correo y te enviaremos un enlace premium para restablecerla.
		</p>
	</div>

	<!-- Session Status -->
	<x-base.auth-session-status class="mb-6" :status="session('status')" />

	<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
		@csrf

		<x-mary-input label="Email de recuperación" name="email" type="email" icon="o-envelope"
			value="{{ old('email') }}" required autofocus class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-base.input-error :messages="$errors->get('email')" class="mt-2" />

		<div class="pt-4">
			<x-mary-button type="submit" label="Enviar enlace de restauración"
				class="btn-primary w-full font-black text-sm uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl" />
		</div>

		<div class="text-center pt-2">
			<a href="{{ route('login') }}"
				class="text-[10px] font-black uppercase tracking-widest text-base-content/40 hover:text-red-600 transition-colors">
				← Volver al inicio de sesión
			</a>
		</div>
	</form>
</x-guest-layout>
