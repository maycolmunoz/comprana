<section>
	<header class="mb-8">
		<h2 class="text-2xl font-black uppercase tracking-tighter text-base-content">
			{{ __('Información del Perfil') }}
		</h2>

		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1">
			{{ __('Actualiza la información de tu perfil y dirección de correo electrónico.') }}
		</p>
	</header>

	<form id="send-verification" method="post" action="{{ route('verification.send') }}">
		@csrf
	</form>

	<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
		@csrf
		@method('patch')

		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			{{-- Name --}}
			<div>
				<x-mary-input label="{{ __('Nombre') }}" name="name" type="text" icon="o-user"
					value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
					class="bg-base-content/5 border-none focus:ring-red-600/20" />
				<x-input-error :messages="$errors->get('name')" class="" />
			</div>

			{{-- Email --}}
			<div class="space-y-2">
				<x-mary-input label="{{ __('Email') }}" name="email" type="email" icon="o-envelope"
					value="{{ old('email', $user->email) }}" required autocomplete="username"
					class="bg-base-content/5 border-none focus:ring-red-600/20" />
				<x-input-error :messages="$errors->get('email')" class="" />

				@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
					<div class="px-4 py-2 bg-amber-500/10 rounded-xl border border-amber-500/20">
						<p class="text-[10px] font-black uppercase tracking-widest text-amber-700">
							{{ __('Tu correo no está verificado.') }}
							<button form="send-verification" class="ml-2 underline hover:text-amber-900 transition-colors uppercase">
								{{ __('Reenviar verificación') }}
							</button>
						</p>

						@if (session('status') === 'verification-link-sent')
							<p class="mt-1 text-[8px] font-bold uppercase tracking-widest text-green-600">
								{{ __('Se ha enviado un nuevo enlace a tu correo.') }}
							</p>
						@endif
					</div>
				@endif
			</div>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			{{-- Phone --}}
			<div>
				<x-mary-input label="{{ __('Teléfono') }}" name="phone" type="text" icon="o-phone"
					value="{{ old('phone', $user->phone) }}" placeholder="Ej: +34 600 000 000"
					class="bg-base-content/5 border-none focus:ring-red-600/20" />
				<x-input-error :messages="$errors->get('phone')" class="" />
			</div>

			{{-- Address --}}
			<div>
				<x-mary-input label="{{ __('Dirección') }}" name="address" type="text" icon="o-map-pin"
					value="{{ old('address', $user->address) }}" placeholder="Ej: Calle Real 123, Barrio Centro"
					class="bg-base-content/5 border-none focus:ring-red-600/20" />
				<x-input-error :messages="$errors->get('address')" class="" />
			</div>
		</div>

		<div class="flex items-center gap-4 pt-4 border-t border-base-content/5">
			<x-mary-button type="submit" label="{{ __('Guardar Cambios') }}" icon="o-check"
				class="btn-primary px-8 font-black text-xs uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl" />

			@if (session('status') === 'profile-updated')
				<div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
					class="flex items-center gap-2 text-green-600 animate__animated animate__fadeIn">
					<x-mary-icon name="o-check-circle" class="w-4 h-4" />
					<span class="text-[10px] font-black uppercase tracking-widest">{{ __('Actualizado con éxito') }}</span>
				</div>
			@endif
		</div>
	</form>
</section>
