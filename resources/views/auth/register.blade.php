<x-guest-layout>
	<div class="mb-8 text-center">
		<h2 class="text-2xl font-black uppercase tracking-tighter">{{ __('Crea tu cuenta') }}</h2>
		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">
			Únete a la mejor experiencia premium
		</p>
	</div>

	<form method="POST" action="{{ route('register') }}" class="space-y-6">
		@csrf

		<x-mary-input label="{{ __('Nombre Completo') }}" name="name" type="text" icon="o-user" value="{{ old('name') }}"
			required autofocus autocomplete="name" class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('name')" class="mt-2" />

		<x-mary-input label="{{ __('Email') }}" name="email" type="email" icon="o-envelope" value="{{ old('email') }}"
			required autocomplete="username" class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('email')" class="mt-2" />

		<x-mary-input label="{{ __('Contraseña') }}" name="password" type="password" icon="o-key" required
			autocomplete="new-password" class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('password')" class="" />

		<x-mary-input label="{{ __('Confirmar Contraseña') }}" name="password_confirmation" type="password"
			icon="o-check-badge" required autocomplete="new-password"
			class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('password_confirmation')" class="" />

		<div class="pt-4">
			<x-mary-button type="submit" label="{{ __('Crear mi Cuenta') }}"
				class="btn-primary w-full font-black text-sm uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl" />
		</div>

		<div class="text-center pt-2">
			<span
				class="text-[10px] font-bold uppercase tracking-widest text-base-content/30">{{ __('¿Ya tienes cuenta?') }}</span>
			<a href="{{ route('login') }}"
				class="text-[10px] font-black uppercase tracking-widest text-red-600 hover:underline ml-1">
				{{ __('Entra aquí') }}
			</a>
		</div>
	</form>
</x-guest-layout>
