<x-guest-layout>
	<div class="mb-8 text-center">
		<h2 class="text-2xl font-black uppercase tracking-tighter">{{ __('Área Segura') }}</h2>
		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">
			{{ __('Por favor, confirma tu contraseña antes de continuar.') }}
		</p>
	</div>

	<form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
		@csrf

		<x-mary-input label="{{ __('Tu Contraseña') }}" name="password" type="password" icon="o-lock-closed" required
			autocomplete="current-password" class="bg-base-content/5 border-none focus:ring-red-600/20" />
		<x-input-error :messages="$errors->get('password')" class="mt-2" />

		<div class="pt-4 text-center">
			<x-mary-button type="submit" label="{{ __('Confirmar Acceso') }}"
				class="btn-primary w-full font-black text-sm uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl" />
		</div>
	</form>
</x-guest-layout>
