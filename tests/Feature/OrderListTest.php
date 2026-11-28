<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('shows the order details when the details button is pressed', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 5000,
        'address' => 'Calle 99 #1-23',
        'phone' => '3001234567',
        'created_at' => '2026-01-15 14:30:00',
    ]);

    actingAs($buyer);

    Livewire::test('orders.list')
        ->call('showDetails', $order->id)
        ->assertSet('showDetailsModal', true)
        ->assertSee('Calle 99 #1-23')
        ->assertSee('5.000')
        ->assertSee('15/01/2026');
});

it('shows translated order status labels in the filters', function () {
    $buyer = User::factory()->create();

    actingAs($buyer);

    $this->get(route('orders.index'))
        ->assertOk()
        ->assertSeeText('En Camino')
        ->assertSee('status=en_camino', false)
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

it('does not verify the payment of another user order', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $owner->id,
        'total' => 5000,
    ]);

    actingAs($other);

    Livewire::test('orders.list')
        ->call('verifyPayment', $order->id)
        ->assertStatus(404);
});

it('offers to pay an unpaid order and to check a pending one', function () {
    $buyer = User::factory()->create();
    $unpaid = Order::factory()->create([
        'customer_id' => $buyer->id,
        'payment_id' => null,
        'payment_status' => null,
        'total' => 5000,
    ]);
    $pending = Order::factory()->create([
        'customer_id' => $buyer->id,
        'payment_id' => 123,
        'payment_status' => 'pending',
        'total' => 6000,
    ]);
    $incomplete = Order::factory()->create([
        'customer_id' => $buyer->id,
        'payment_id' => null,
        'payment_status' => 'pending',
        'total' => 7000,
    ]);

    actingAs($buyer);

    Livewire::test('orders.list')
        ->assertSee(route('orders.pay', ['order' => $unpaid]), false)
        ->assertSee(route('orders.pay', ['order' => $incomplete]), false)
        ->assertSee(route('orders.complete', ['order' => $pending]), false)
        ->assertSeeText('Pagar')
        ->assertSeeText('Reintentar pago')
        ->assertSeeText('Ver estado');
});

it('keeps a pending order actionable after verifying the payment', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'payment_id' => 123,
        'payment_status' => 'pending',
        'total' => 5000,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/search*' => Http::response([
            'results' => [
                [
                    'id' => 123,
                    'status' => 'pending',
                    'external_reference' => (string) $order->id,
                    'transaction_amount' => 5000,
                ],
            ],
        ]),
    ]);

    actingAs($buyer);

    Livewire::test('orders.list')
        ->call('verifyPayment', $order->id)
        ->assertSeeText('Ver estado')
        ->assertSee(route('orders.complete', ['order' => $order]), false)
        ->assertDontSeeText('Reintentar pago');
});
