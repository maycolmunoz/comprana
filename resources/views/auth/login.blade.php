<x-guest-layout>
	<div class="mb-8 text-center">
		<h2 class="text-2xl font-black uppercase tracking-tighter">{{ __('Bienvenido de nuevo') }}</h2>
		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">
			Ingresa a tu cuenta premium
		</p>
	</div>

	<!-- Session Status -->
	<x-auth-session-status class="mb-6" :status="session('status')" />

	<form method="POST" action="{{ route('login') }}" class="space-y-6">
		@csrf

		<x-mary-input label="{{ __('Email') }}" name="email" type="email" icon="o-envelope" value="{{ old('email') }}"
			required autofocus autocomplete="username" class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('email')" class="mt-2" />

		<x-mary-input label="{{ __('Contraseña') }}" name="password" type="password" icon="o-key" required
			autocomplete="current-password" class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('password')" class="mt-2" />

		<!-- Remember Me & Forgot Password -->
		<div class="flex items-center justify-between">
			<label for="remember_me" class="inline-flex items-center group cursor-pointer">
				<input id="remember_me" type="checkbox"
					class="rounded-lg border-base-content/10 text-red-600 shadow-sm focus:ring-red-600/20" name="remember">
				<span
					class="ms-2 text-xs font-bold uppercase tracking-widest text-base-content/40 group-hover:text-red-600 transition-colors">{{ __('Recordarme') }}</span>
			</label>

			@if (Route::has('password.request'))
				<a class="text-xs font-bold text-base-content/40 hover:text-red-600 transition-colors"
					href="{{ route('password.request') }}">
					{{ __('¿Olvidaste tu contraseña?') }}
				</a>
			@endif
		</div>

		<div class="pt-4">
			<x-mary-button type="submit" label="{{ __('Iniciar Sesión') }}"
				class="btn-primary w-full font-black text-sm uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl" />
		</div>

		<div class="text-center pt-2">
			<span
				class="text-[10px] font-bold uppercase tracking-widest text-base-content/30">{{ __('¿No tienes cuenta?') }}</span>
			<a href="{{ route('register') }}"
				class="text-[10px] font-black uppercase tracking-widest text-red-600 hover:underline ml-1">
				{{ __('Regístrate ahora') }}
			</a>
		</div>
	</form>
</x-guest-layout>
