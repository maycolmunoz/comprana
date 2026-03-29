<?php

use App\Filament\Resources\Sections\Pages\ManageSections;
use App\Models\Section;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $role = Role::create(['name' => 'admin']);
    $permission = Permission::create(['name' => 'ViewAny:Section']);
    $role->givePermissionTo($permission);
    $this->user->assignRole($role);
});

it('can render sections list page', function () {
    actingAs($this->user);

    Livewire::test(ManageSections::class)
        ->assertSuccessful();
});

it('can create a section', function () {
    $permission = Permission::create(['name' => 'Create:Section']);
    $this->user->roles()->first()->givePermissionTo($permission);

    actingAs($this->user);

    Livewire::test(ManageSections::class)
        ->callAction('create', [
            'name' => 'New Section Name',
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseHas(Section::class, [
        'name' => 'New Section Name',
    ]);
});

it('can edit a section', function () {
    $permission = Permission::create(['name' => 'Update:Section']);
    $this->user->roles()->first()->givePermissionTo($permission);

    $section = Section::create(['name' => 'Old Name']);

    actingAs($this->user);

    Livewire::test(ManageSections::class)
        ->callTableAction('edit', $section, [
            'name' => 'Updated Name',
        ])
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Section::class, [
        'id' => $section->id,
        'name' => 'Updated Name',
    ]);
});
