<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $role = Role::create(['name' => 'admin']);
    $permission = Permission::create(['name' => 'ViewAny:User']);
    $role->givePermissionTo($permission);
    $this->user->assignRole($role);
});

it('can render users list page', function () {
    actingAs($this->user);

    Livewire::test(ListUsers::class)
        ->assertSuccessful();
});

it('can render user creation page', function () {
    $permission = Permission::create(['name' => 'Create:User']);
    $this->user->roles()->first()->givePermissionTo($permission);

    actingAs($this->user);

    Livewire::test(CreateUser::class)
        ->assertSuccessful();
});

it('can create a user', function () {
    $permission = Permission::create(['name' => 'Create:User']);
    $this->user->roles()->first()->givePermissionTo($permission);

    actingAs($this->user);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'roles' => [$this->user->roles()->first()->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'email' => 'newuser@example.com',
    ]);
});

it('can render user edit page', function () {
    $permission = Permission::create(['name' => 'Update:User']);
    $this->user->roles()->first()->givePermissionTo($permission);

    $targetUser = User::factory()->create();

    actingAs($this->user);

    Livewire::test(EditUser::class, [
        'record' => $targetUser->getRouteKey(),
    ])
        ->assertSuccessful();
});
