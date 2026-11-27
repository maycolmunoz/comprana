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

it('shows translated order status labels in the filters', function () {
    $buyer = User::factory()->create();

    actingAs($buyer);

    $this->get(route('orders.index'))
        ->assertOk()
        ->assertSeeText('En Camino')
        ->assertDontSeeText('en_camino');
});

it('shows the real payment status instead of inferring it from the payment id', function () {
    $buyer = User::factory()->create();
    Order::factory()->create([
        'customer_id' => $buyer->id,
        'payment_id' => 123,
        'payment_status' => 'pending',
        'total' => 5000,
    ]);

    actingAs($buyer);

    Livewire::test('orders.list')
        ->assertSeeText('Pendiente')
        ->assertDontSeeText('Pagado');
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
