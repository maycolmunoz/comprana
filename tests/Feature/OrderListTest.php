<?php

use App\Models\Order;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('shows the order details when the details button is pressed', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 5000,
        'address' => 'Calle 99 #1-23',
        'phone' => '3001234567',
    ]);

    actingAs($buyer);

    Livewire::test('orders.list')
        ->call('showDetails', $order->id)
        ->assertSet('myModal', true)
        ->assertSee('Calle 99 #1-23')
        ->assertSee('5,000');
});

it('does not show another user order details', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $owner->id,
        'total' => 5000,
        'address' => 'Calle 99 #1-23',
    ]);

    actingAs($other);

    Livewire::test('orders.list')
        ->call('showDetails', $order->id)
        ->assertDontSee('Calle 99 #1-23');
});
