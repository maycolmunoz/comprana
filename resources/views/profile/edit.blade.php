<x-app-layout>
	<x-slot name="header">
		<x-base.page-header title="Mi Perfil" />
	</x-slot>

	<div class="py-12 animate__animated animate__fadeIn">
		<div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
			{{-- Profile Information Card --}}
			<x-base.section-card>
				<div class="max-w-xl">
					@include('profile.partials.update-profile-information-form')
				</div>
			</x-base.section-card>

			{{-- Update Password Card --}}
			<x-base.section-card>
				<div class="max-w-xl">
					@include('profile.partials.update-password-form')
				</div>
			</x-base.section-card>

			{{-- Delete Account Card (Caution Style) --}}
			<x-base.section-card class="border-red-600/10">
				<div class="max-w-xl">
					@include('profile.partials.delete-user-form')
				</div>
			</x-base.section-card>
		</div>
	</div>
</x-app-layout>
