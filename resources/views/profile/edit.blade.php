<x-app-layout>
    <x-slot name="header">
        <x-base.page-header title="Mi perfil" />
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-8 px-6 lg:px-8">
            <x-base.section-card>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </x-base.section-card>

            <x-base.section-card>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </x-base.section-card>

            <x-base.section-card class="border-red-600/10">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </x-base.section-card>
        </div>
    </div>
</x-app-layout>
