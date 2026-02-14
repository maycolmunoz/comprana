<x-app-layout>
	<x-slot name="header">
		<div class="flex items-center gap-3">
			<div class="w-1.5 h-8 bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.4)]"></div>
			<h2 class="text-3xl font-black tracking-tighter uppercase text-base-content">
				{{ __('Mi Perfil') }}
			</h2>
		</div>
	</x-slot>

	<div class="py-12 animate__animated animate__fadeIn">
		<div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
			{{-- Profile Information Card --}}
			<div class="bg-base-100 border border-base-content/5 shadow-xl shadow-red-900/5 rounded-[2.5rem] overflow-hidden">
				<div class="p-8 sm:p-12">
					<div class="max-w-xl">
						@include('profile.partials.update-profile-information-form')
					</div>
				</div>
			</div>

			{{-- Update Password Card --}}
			<div class="bg-base-100 border border-base-content/5 shadow-xl shadow-red-900/5 rounded-[2.5rem] overflow-hidden">
				<div class="p-8 sm:p-12">
					<div class="max-w-xl">
						@include('profile.partials.update-password-form')
					</div>
				</div>
			</div>

			{{-- Delete Account Card (Caution Style) --}}
			<div class="bg-red-50/30 border border-red-600/10 shadow-xl shadow-red-900/5 rounded-[2.5rem] overflow-hidden">
				<div class="p-8 sm:p-12">
					<div class="max-w-xl">
						@include('profile.partials.delete-user-form')
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
