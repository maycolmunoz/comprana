<?php

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    config([
        'services.mercadopago.token' => 'test-token',
        'services.mercadopago.webhook_secret' => 'test-secret',
        'services.mercadopago.currency' => 'COP',
    ]);
});

function mpSignature(string $dataId, string $requestId, string $ts = '1234567890'): string
{
    $canonical = "id:{$dataId};request-id:{$requestId};ts:{$ts};";

    return "ts={$ts},v1=".hash_hmac('sha256', $canonical, config('services.mercadopago.webhook_secret'));
}

function cartWithProduct(User $buyer, int $quantity = 2, int $price = 1000): Cart
{
    $cart = $buyer->carts()->firstOrFail();
    $product = Product::factory()->create(['price' => $price, 'stock' => 10]);

    $cart->products()->attach($product, ['cant' => $quantity]);

    return $cart->fresh(['products']);
}

function approvedPayment(Order $order, Product $product, int $quantity = 2, int $amount = 2000): array
{
    return [
        'id' => 123,
        'status' => 'approved',
        'external_reference' => (string) $order->id,
        'transaction_amount' => $amount,
        'additional_info' => [
            'items' => [
                [
                    'id' => (string) $product->id,
                    'title' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => 1000,
                ],
            ],
        ],
    ];
}

it('stores the cart total when creating an order', function () {
    $buyer = User::factory()->create(['phone' => '3001234567', 'address' => 'Calle 1']);
    $cart = cartWithProduct($buyer, 2, 1000);

    actingAs($buyer);

    Livewire::test('orders.order', ['cart' => $cart])
        ->set('phone', '3001234567')
        ->set('address', 'Calle 1')
        ->call('contacts');

    expect(Order::first()->total)->toBe(2000);
});

it('updates the order total from the current cart before creating the preference', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 3, 1000);
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 1,
        'invoice' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/checkout/preferences' => Http::response(['id' => 'pref-1']),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', ['id' => $cart->id, 'order' => $order]))
        ->assertOk();

    expect($order->fresh()->total)->toBe(3000);
});

it('marks an order as paid and generates the invoice from a signed webhook', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'invoice' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/123' => Http::response(approvedPayment($order, $product)),
    ]);

    $requestId = 'request-123';

    $this->post(route('webhooks'), [
        'type' => 'payment',
        'data' => ['id' => '123'],
    ], [
        'x-signature' => mpSignature('123', $requestId),
        'x-request-id' => $requestId,
    ])->assertNoContent();

    $order->refresh();

    expect($order->payment_id)->toBe(123)
        ->and($order->payment_status)->toBe('approved')
        ->and($order->invoice)->not->toBeNull();

    expect($product->fresh()->stock)->toBe(8);
    Storage::disk('invoices')->assertExists($order->invoice);
});

it('rejects a webhook with an invalid signature', function () {
    Http::preventStrayRequests();

    $order = Order::factory()->create([
        'total' => 1000,
        'invoice' => null,
    ]);

    $this->post(route('webhooks'), [
        'type' => 'payment',
        'data' => ['id' => '123'],
    ], [
        'x-signature' => 'ts=123,v1=invalid',
        'x-request-id' => 'request-123',
    ])->assertUnauthorized();

    expect($order->fresh()->payment_id)->toBeNull();
});

it('does not duplicate the invoice or stock when a webhook is retried', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'invoice' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/123' => Http::response(approvedPayment($order, $product)),
    ]);

    $requestId = 'request-123';
    $headers = [
        'x-signature' => mpSignature('123', $requestId),
        'x-request-id' => $requestId,
    ];
    $payload = ['type' => 'payment', 'data' => ['id' => '123']];

    $this->post(route('webhooks'), $payload, $headers)->assertNoContent();
    $invoice = $order->fresh()->invoice;

    $this->post(route('webhooks'), $payload, $headers)->assertNoContent();

    expect($order->fresh()->invoice)->toBe($invoice);
    expect($product->fresh()->stock)->toBe(8);
});

it('ignores a payment whose amount does not match the order', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'invoice' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/123' => Http::response(
            approvedPayment($order, $product, amount: 1)
        ),
    ]);

    $requestId = 'request-123';

    $this->post(route('webhooks'), [
        'type' => 'payment',
        'data' => ['id' => '123'],
    ], [
        'x-signature' => mpSignature('123', $requestId),
        'x-request-id' => $requestId,
    ])->assertNoContent();

    expect($order->fresh()->payment_id)->toBeNull();
});

it('applies the payment on the success redirect', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'invoice' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/123' => Http::response(approvedPayment($order, $product)),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.complete', ['order' => $order, 'payment_id' => '123']))
        ->assertOk()
        ->assertSee('Pago aprobado');

    expect($order->fresh()->payment_status)->toBe('approved');
});

it('shows a rejected payment as not approved', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'invoice' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/123' => Http::response([
            ...approvedPayment($order, $product),
            'status' => 'rejected',
        ]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.complete', ['order' => $order, 'payment_id' => '123']))
        ->assertOk()
        ->assertSeeText('Pago rechazado')
        ->assertDontSeeText('Pago pendiente');
});

it('forbids showing the completion page for another user order', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->create(['customer_id' => $owner->id, 'total' => 1000]);

    actingAs($other)
        ->get(route('orders.complete', $order))
        ->assertForbidden();
});

it('forbids paying another user order', function () {
    Http::preventStrayRequests();

    $owner = User::factory()->create();
    $other = User::factory()->create();
    $cart = cartWithProduct($other);
    $order = Order::factory()->create(['customer_id' => $owner->id, 'total' => 1000]);

    actingAs($other)
        ->get(route('orders.pay', ['id' => $cart->id, 'order' => $order]))
        ->assertForbidden();
});

it('forbids downloading another user invoice', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $owner->id,
        'invoice' => 'order1.pdf',
    ]);

    actingAs($other)
        ->get(route('orders.invoice', ['name' => $order->invoice]))
        ->assertNotFound();
});
