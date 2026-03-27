<x-guest-layout>
	<div class="mb-8 text-center">
		<h2 class="text-2xl font-black uppercase tracking-tighter">{{ __('Nueva contraseña') }}</h2>
		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">
			{{ __('Establece tus credenciales premium') }}
		</p>
	</div>

	<form method="POST" action="{{ route('password.store') }}" class="space-y-6">
		@csrf

		<!-- Password Reset Token -->
		<input type="hidden" name="token" value="{{ $request->route('token') }}">

		<x-mary-input label="{{ __('Email') }}" name="email" type="email" icon="o-envelope"
			value="{{ old('email', $request->email) }}" required autocomplete="username"
			class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('email')" class="mt-2" />

		<x-mary-input label="{{ __('Nueva Contraseña') }}" name="password" type="password" icon="o-key" required autofocus
			autocomplete="new-password" class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('password')" class="mt-2" />

		<x-mary-input label="{{ __('Confirmar Contraseña') }}" name="password_confirmation" type="password"
			icon="o-check-badge" required autocomplete="new-password"
			class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

		<div class="pt-4">
			<x-mary-button type="submit" label="{{ __('Restablecer Contraseña') }}"
				class="btn-primary w-full font-black text-sm uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl" />
		</div>
	</form>
</x-guest-layout>
