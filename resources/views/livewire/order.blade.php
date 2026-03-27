<div class="max-w-5xl mx-auto py-12 px-6 animate__animated animate__fadeIn">
	{{-- Process Header --}}
	<div class="mb-12 text-center">
		<h3 class="text-3xl font-black uppercase tracking-tighter">
			{{ __('Datos de') }} <span class="text-red-600">{{ __('Contacto') }}</span>
		</h3>
		<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40 mt-2">
			{{ __('Confirma dónde quieres recibir tu pedido premium de Comprana') }}
		</p>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
		{{-- Left Section: Illustration --}}
		<div class="hidden lg:block animate__animated animate__fadeInLeft">
			<div class="relative group">
				<div
					class="absolute -inset-4 bg-red-600/5 rounded-[3rem] blur-2xl group-hover:bg-red-600/10 transition-all duration-700">
				</div>
				<img src="{{ asset('srcs/contact.png') }}" alt="{{ __('Información de Contacto') }}"
					class="relative w-3/4 mx-auto rounded-[2.5rem] transform group-hover:scale-105 transition-transform duration-700 drop-shadow-2xl">
			</div>
		</div>

		{{-- Right Section: Form Card --}}
		<div class="animate__animated animate__fadeInRight">
			<div
				class="bg-base-100 border border-base-content/5 shadow-2xl shadow-red-900/5 rounded-[2.5rem] p-8 lg:p-10 relative overflow-hidden">
				{{-- Decorative Accent --}}
				<div class="absolute top-0 left-0 w-full h-1.5 bg-linear-to-r from-red-600 to-red-400"></div>

				<form wire:submit.prevent='contacts' class="space-y-8">
					{{-- Instructions --}}
					<div class="flex items-start gap-4 p-4 bg-base-content/5 rounded-2xl mb-2">
						<x-mary-icon name="o-information-circle" class="w-5 h-5 text-red-600 shrink-0" />
						<p class="text-[10px] font-bold uppercase tracking-widest text-base-content/60 leading-relaxed">
							{{ __('Por favor, verifica que tu número de teléfono y dirección sean correctos para garantizar una entrega premium sin contratiempos.') }}
						</p>
					</div>

					{{-- Phone Input --}}
					<div class="space-y-2">
						<x-mary-input label="{{ __('Número de Teléfono') }}" wire:model='phone' type="number" icon="o-phone"
							placeholder="{{ __('Tu teléfono de contacto') }}" class="bg-base-content/5 border-none focus:ring-red-600/20" />
						<x-input-error :messages="$errors->get('phone')" class="" />
					</div>

					{{-- Address Input --}}
					<div class="space-y-2">
						<x-mary-input label="{{ __('Dirección de Entrega') }}" wire:model='address' icon="o-map-pin"
							placeholder="{{ __('Calle, número, ciudad y detalles adicionales') }}"
							class="bg-base-content/5 border-none focus:ring-red-600/20" />
						<x-input-error :messages="$errors->get('address')" class="" />
					</div>

					{{-- Actions --}}
					<div class="pt-6 border-t border-base-content/5 flex items-center justify-between gap-4">
						<a href="{{ route('carts.show', $cart->id) }}"
							class="text-[10px] font-black uppercase tracking-widest text-base-content/30 hover:text-base-content transition-colors">
							{{ __('← Cancelar') }}
						</a>

						<x-mary-button type="submit" label="{{ __('Continuar al Pago') }}" icon-right="o-credit-card"
							class="btn-primary px-8 py-4 h-auto font-black text-xs uppercase tracking-widest shadow-xl shadow-red-600/20 rounded-2xl group">
							<x-slot:append>
								<x-mary-icon name="o-chevron-right" class="w-3 h-3 group-hover:translate-x-1 transition-transform" />
							</x-slot:append>
						</x-mary-button>
					</div>
				</form>

				{{-- Trust Footer --}}
				<div class="mt-10 text-center opacity-30">
					<p class="text-[8px] font-bold uppercase tracking-[0.4em]">{{ __('Garantía de Privacidad Comprana') }}</p>
				</div>
			</div>
		</div>
	</div>
</div>
