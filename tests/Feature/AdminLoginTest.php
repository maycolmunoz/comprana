<?php

use App\Models\User;
use Filament\Auth\Pages\Login;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('keeps an admin signed in after logging into the panel', function () {
    $user = User::factory()->create(['password' => 'password']);
    $user->assignRole(Role::create(['name' => 'admin']));

    Livewire::test(Login::class)
        ->fillForm(['email' => $user->email, 'password' => 'password'])
        ->call('authenticate')
        ->assertHasNoFormErrors();

    $this->assertAuthenticatedAs($user);

    $this->get('/admin')->assertOk();
});

it('loads Livewire on the admin login page', function () {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('data-update-uri', false);
});
