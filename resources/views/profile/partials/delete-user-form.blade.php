<section class="space-y-6">
	<header>
		<h2 class="text-2xl font-black uppercase tracking-tighter text-red-600">
			{{ __('Eliminar Cuenta') }}
		</h2>

		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-1 leading-relaxed">
			{{ __('Una vez que se elimine tu cuenta, todos sus recursos y datos se borrarán permanentemente. Por favor, descarga cualquier dato que desees conservar antes de proceder.') }}
		</p>
	</header>

	<div class="pt-4">
		<x-mary-button label="{{ __('Eliminar mi Cuenta') }}" icon="o-trash"
			class="btn-error btn-outline font-black text-xs uppercase tracking-widest rounded-2xl px-8" x-data=""
			x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" />
	</div>

	<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
		<div class="p-8 bg-base-100 rounded-4xl border border-base-content/5 shadow-2xl relative overflow-hidden">
			{{-- Warning Accent --}}
			<div class="absolute top-0 left-0 w-full h-1.5 bg-red-600"></div>

			<form method="post" action="{{ route('profile.destroy') }}">
				@csrf
				@method('delete')

				<div class="mb-8">
					<h3 class="text-2xl font-black tracking-tighter uppercase text-base-content">
						{{ __('¿Estás seguro de eliminar tu cuenta?') }}
					</h3>

					<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-2 leading-relaxed">
						{{ __('Esta acción es permanente. Por favor, introduce tu contraseña para confirmar que deseas eliminar tu cuenta de forma definitiva.') }}
					</p>
				</div>

				<div class="space-y-6">
					<div>
						<x-mary-input label="{{ __('Tu Contraseña') }}" name="password" type="password" icon="o-lock-closed"
							placeholder="{{ __('Ingresa tu contraseña para confirmar') }}"
							class="bg-base-content/5 border-none focus:ring-red-600/20" />
						<x-input-error :messages="$errors->userDeletion->get('password')" class="" />
					</div>
				</div>

				<div class="mt-10 flex items-center justify-end gap-3 pt-6 border-t border-base-content/5">
					<x-mary-button label="{{ __('Cancelar') }}" x-on:click="$dispatch('close')"
						class="btn-ghost font-black text-xs uppercase tracking-widest rounded-xl" />

					<x-mary-button type="submit" label="{{ __('Sí, Eliminar Definitivamente') }}" icon="o-exclamation-triangle"
						class="btn-error font-black text-xs uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-xl px-6" />
				</div>
			</form>
		</div>
	</x-modal>
</section>
