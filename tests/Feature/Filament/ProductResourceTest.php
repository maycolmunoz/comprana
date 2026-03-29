<?php

use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

it('can render products list page', function () {
    $user = User::factory()->create();

    // Create 'admin' role and grant permission using PascalCase
    $role = Role::create(['name' => 'admin']);
    $permission = Permission::create(['name' => 'ViewAny:Product']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);

    $section = Section::create(['name' => 'Test Section']);
    Product::factory()->create(['section_id' => $section->id]);

    actingAs($user);

    Livewire::test(ListProducts::class)
        ->assertSuccessful();
});
