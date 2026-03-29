<?php

use App\Filament\Resources\Orders\Pages\ManageOrders;
use App\Models\Order;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $role = Role::create(['name' => 'admin']);
    $permission = Permission::create(['name' => 'ViewAny:Order']);
    $role->givePermissionTo($permission);
    $this->user->assignRole($role);
});

it('can render orders list page', function () {
    actingAs($this->user);

    Livewire::test(ManageOrders::class)
        ->assertSuccessful();
});

it('can list orders', function () {
    $customer = User::factory()->create();
    $orders = Order::factory()->count(5)->create([
        'customer_id' => $customer->id,
    ]);

    actingAs($this->user);

    Livewire::test(ManageOrders::class)
        ->assertCanSeeTableRecords($orders);
});

it('can render order table columns', function () {
    actingAs($this->user);

    Livewire::test(ManageOrders::class)
        ->assertCanRenderTableColumn('status')
        ->assertCanRenderTableColumn('total')
        ->assertCanRenderTableColumn('user.name');
});
