<?php

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\MercadoPagoService;
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

/**
 * @param  array<string, mixed>  $attributes
 */
function orderForCart(User $buyer, Cart $cart, array $attributes = []): Order
{
    return Order::factory()->create(array_merge([
        'customer_id' => $buyer->id,
        'total' => $cart->total(),
        'items' => $cart->toOrderItems(),
        'invoice' => null,
        'payment_id' => null,
        'payment_status' => null,
    ], $attributes));
}

it('stores the order items and clears the cart when creating an order', function () {
    $buyer = User::factory()->create(['phone' => '3001234567', 'address' => 'Calle 1']);
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();

    actingAs($buyer);

    Livewire::test('orders.order', ['cart' => $cart])
        ->set('phone', '3001234567')
        ->set('address', 'Calle 1')
        ->call('placeOrder')
        ->assertRedirect(route('orders.pay', ['order' => Order::first()]));

    $order = Order::first();

    expect($order->total)->toBe(2000)
        ->and($order->items)->toHaveCount(1)
        ->and($order->items[0]['id'])->toBe((string) $product->id)
        ->and($order->items[0]['title'])->toBe($product->name)
        ->and($order->items[0]['quantity'])->toBe(2)
        ->and((int) $order->items[0]['unit_price'])->toBe(1000)
        ->and($cart->fresh()->products)->toBeEmpty();
});

it('does not create an order when the cart has insufficient stock', function () {
    $buyer = User::factory()->create(['phone' => '3001234567', 'address' => 'Calle 1']);
    $cart = cartWithProduct($buyer, 3, 1000);
    $cart->products->first()->update(['stock' => 1]);

    actingAs($buyer);

    Livewire::test('orders.order', ['cart' => $cart->fresh(['products'])])
        ->set('phone', '3001234567')
        ->set('address', 'Calle 1')
        ->call('placeOrder')
        ->assertDispatched('notification');

    expect(Order::count())->toBe(0)
        ->and($cart->fresh()->products)->not->toBeEmpty();
});

it('charges the order snapshot even when the cart changes', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $order = orderForCart($buyer, $cart);

    $cart->products()->detach();
    $other = Product::factory()->create(['price' => 500, 'stock' => 10]);
    $cart->products()->attach($other, ['cant' => 5]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/checkout/preferences' => Http::response(['id' => 'pref-1']),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertOk()
        ->assertSeeText($order->items[0]['title'])
        ->assertDontSeeText($other->name);

    expect($order->fresh()->total)->toBe(2000)
        ->and($order->fresh()->items)->toHaveCount(1)
        ->and((int) $order->fresh()->items[0]['quantity'])->toBe(2);

    Http::assertSent(fn ($request) => (int) $request['items'][0]['quantity'] === 2
        && (float) $request['items'][0]['unit_price'] === 1000.0);
});

it('omits back urls when the app url is not public', function () {
    config(['app.url' => 'http://localhost']);

    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer));

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/checkout/preferences' => Http::response(['id' => 'pref-1']),
    ]);

    app(MercadoPagoService::class)->createPreference($order);

    Http::assertSent(fn ($request) => ! isset($request['back_urls']) && ! isset($request['auto_return']));
});

it('sends back urls when the app url is public', function () {
    config(['app.url' => 'https://comprana.test']);

    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer));

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/checkout/preferences' => Http::response(['id' => 'pref-1']),
    ]);

    app(MercadoPagoService::class)->createPreference($order);

    Http::assertSent(fn ($request) => $request['back_urls']['success'] === route('orders.complete', ['order' => $order])
        && $request['back_urls']['failure'] === route('orders.pay', ['order' => $order])
        && $request['back_urls']['pending'] === route('orders.complete', ['order' => $order])
        && $request['auto_return'] === 'approved');
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
        ->assertSeeText('Reintentar pago')
        ->assertSee(route('orders.pay', ['order' => $order]), false)
        ->assertDontSeeText('Pago pendiente');
});

it('shows a pending payment as pending', function () {
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
            'status' => 'pending',
        ]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.complete', ['order' => $order, 'payment_id' => '123']))
        ->assertOk()
        ->assertSeeText('Pago pendiente')
        ->assertSeeText('Actualizando el estado automáticamente')
        ->assertSee(json_encode(route('orders.payment-status', $order)), false);
});

it('shows a refunded payment as refunded', function () {
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
            'status' => 'refunded',
        ]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.complete', ['order' => $order, 'payment_id' => '123']))
        ->assertOk()
        ->assertSeeText('Pago reembolsado')
        ->assertDontSeeText('Pago rechazado');
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
    $order = Order::factory()->create(['customer_id' => $owner->id, 'total' => 1000]);

    actingAs($other)
        ->get(route('orders.pay', ['order' => $order]))
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

it('syncs an approved payment when the webhook and redirect were missed', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'payment_id' => null,
        'payment_status' => null,
        'invoice' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/search*' => Http::response([
            'results' => [approvedPayment($order, $product)],
        ]),
    ]);

    actingAs($buyer)
        ->getJson(route('orders.payment-status', $order))
        ->assertOk()
        ->assertJson([
            'status' => 'approved',
            'paid' => true,
        ]);

    $order->refresh();

    expect($order->payment_id)->toBe(123)
        ->and($order->payment_status)->toBe('approved')
        ->and($order->invoice)->not->toBeNull();

    Storage::disk('invoices')->assertExists($order->invoice);
});

it('does not downgrade an approved order when checking the payment status', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'payment_id' => 999,
        'payment_status' => 'approved',
    ]);

    Http::preventStrayRequests();

    actingAs($buyer)
        ->getJson(route('orders.payment-status', $order))
        ->assertOk()
        ->assertJson([
            'status' => 'approved',
            'paid' => true,
        ]);

    expect($order->fresh()->payment_id)->toBe(999);
});

it('forbids checking the payment status of another user order', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $owner->id,
        'total' => 1000,
    ]);

    actingAs($other)
        ->getJson(route('orders.payment-status', $order))
        ->assertForbidden();
});

it('shows a retry message when the payment preference cannot be created', function () {
    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer));

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/checkout/preferences' => Http::response(['message' => 'boom'], 500),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertOk()
        ->assertSeeText('No pudimos iniciar el pago')
        ->assertSeeText('Reintentar');
});

it('renders the payment page with a link to the Mercado Pago checkout', function () {
    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer));

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/checkout/preferences' => Http::response([
            'id' => 'pref-1',
            'init_point' => 'https://www.mercadopago.com.co/checkout/v1/redirect?pref_id=pref-1',
        ]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertOk()
        ->assertSee('https://www.mercadopago.com.co/checkout/v1/redirect?pref_id=pref-1', false)
        ->assertSeeText('Pagar con Mercado Pago')
        ->assertSeeText('¿Ya pagaste? Verificar estado')
        ->assertDontSee('wallet_container', false)
        ->assertDontSee('redirectMode', false)
        ->assertDontSee('sdk.mercadopago.com', false);
});

it('redirects an approved order to the payment status page', function () {
    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer), ['payment_status' => 'approved']);

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertRedirect(route('orders.complete', ['order' => $order]));
});

it('records a rejected payment returned to the pay page and offers a retry', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = orderForCart($buyer, $cart);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/456' => Http::response([
            ...approvedPayment($order, $product),
            'id' => 456,
            'status' => 'rejected',
        ]),
        'api.mercadopago.com/checkout/preferences' => Http::response([
            'id' => 'pref-2',
            'init_point' => 'https://www.mercadopago.com.co/checkout/v1/redirect?pref_id=pref-2',
        ]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', [
            'order' => $order,
            'payment_id' => '456',
        ]))
        ->assertOk()
        ->assertSeeText('Tu último pago fue rechazado')
        ->assertSeeText('Pagar con Mercado Pago');

    expect($order->fresh()->payment_id)->toBe(456)
        ->and($order->fresh()->payment_status)->toBe('rejected');
});

it('redirects a pending payment to the status page without creating a new preference', function () {
    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer), [
        'payment_id' => 123,
        'payment_status' => 'pending',
    ]);

    Http::preventStrayRequests();

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertRedirect(route('orders.complete', ['order' => $order]));

    Http::assertNothingSent();
});

it('allows retrying an incomplete pending payment without a Mercado Pago payment id', function () {
    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer), [
        'payment_status' => 'pending',
        'payment_id' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/search*' => Http::response(['results' => []]),
        'api.mercadopago.com/checkout/preferences' => Http::response([
            'id' => 'pref-1',
            'init_point' => 'https://www.mercadopago.com.co/checkout/v1/redirect?pref_id=pref-1',
        ]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertOk()
        ->assertSeeText('Pagar con Mercado Pago');

    Http::assertSent(fn ($request) => $request['external_reference'] === (string) $order->id);
});

it('recovers a pending payment without a local payment id before creating a new preference', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'items' => null,
        'payment_id' => null,
        'payment_status' => 'pending',
        'invoice' => null,
    ]);

    $cart->products()->detach();

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/search*' => Http::response([
            'results' => [
                [
                    ...approvedPayment($order, $product),
                    'status' => 'pending',
                ],
            ],
        ]),
    ]);

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertRedirect(route('orders.complete', ['order' => $order]));

    $order->refresh();

    expect($order->payment_id)->toBe(123)
        ->and($order->payment_status)->toBe('pending')
        ->and($order->items)->toHaveCount(1);

    Http::assertNotSent(fn ($request) => str_contains($request->url(), 'checkout/preferences'));
});

it('treats a pending status without a payment id as a payment that can be started', function () {
    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer), [
        'payment_status' => 'pending',
        'payment_id' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/search*' => Http::response(['results' => []]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.complete', ['order' => $order]))
        ->assertOk()
        ->assertSeeText('Pago no iniciado')
        ->assertSeeText('Pagar ahora');
});

it('recovers historical items for a rejected payment before retrying', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'items' => null,
        'payment_id' => null,
        'payment_status' => 'rejected',
        'invoice' => null,
    ]);

    $cart->products()->detach();

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/search*' => Http::response([
            'results' => [
                [
                    ...approvedPayment($order, $product),
                    'status' => 'rejected',
                ],
            ],
        ]),
        'api.mercadopago.com/checkout/preferences' => Http::response([
            'id' => 'pref-1',
            'init_point' => 'https://www.mercadopago.com.co/checkout/v1/redirect?pref_id=pref-1',
        ]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertOk()
        ->assertSeeText('Tu último pago fue rechazado')
        ->assertSeeText('Pagar con Mercado Pago');

    expect($order->fresh()->items)->toHaveCount(1)
        ->and($order->fresh()->payment_status)->toBe('rejected');
});

it('backfills historical order items from a Mercado Pago payment', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'items' => null,
        'payment_id' => null,
        'payment_status' => 'pending',
        'invoice' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/search*' => Http::response([
            'results' => [
                [
                    ...approvedPayment($order, $product),
                    'status' => 'pending',
                ],
            ],
        ]),
    ]);

    actingAs($buyer)
        ->getJson(route('orders.payment-status', $order))
        ->assertOk()
        ->assertJson(['status' => 'pending']);

    expect($order->fresh()->items)->toHaveCount(1)
        ->and($order->fresh()->payment_status)->toBe('pending');
});

it('offers to start the payment when the order has no payment', function () {
    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer));

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/search*' => Http::response(['results' => []]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.complete', ['order' => $order]))
        ->assertOk()
        ->assertSeeText('Pago no iniciado')
        ->assertSeeText('Pagar ahora')
        ->assertSee(route('orders.pay', ['order' => $order]), false)
        ->assertDontSeeText('Ver estado');
});

it('does not offer a new payment while the payment is pending', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = orderForCart($buyer, $cart, [
        'payment_id' => 123,
        'payment_status' => 'pending',
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/123' => Http::response([
            ...approvedPayment($order, $product),
            'status' => 'pending',
        ]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.complete', ['order' => $order, 'payment_id' => '123']))
        ->assertOk()
        ->assertSeeText('Pago pendiente')
        ->assertDontSeeText('Pagar ahora')
        ->assertDontSeeText('Reintentar pago')
        ->assertSeeText('Ver mis pedidos');
});

it('does not start a payment when the order total does not match its items', function () {
    $buyer = User::factory()->create();
    $order = orderForCart($buyer, cartWithProduct($buyer), ['total' => 1]);

    Http::preventStrayRequests();

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertOk()
        ->assertSeeText('no coincide con el total');

    Http::assertNothingSent();
});

it('does not start a payment when an order item is out of stock', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = orderForCart($buyer, $cart);

    $product->update(['stock' => 1]);

    Http::preventStrayRequests();

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertOk()
        ->assertSeeText('ya no tienen stock suficiente');

    Http::assertNothingSent();
});

it('shows a clear error when the order has no items and no cart to recover', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 9999,
        'items' => null,
        'invoice' => null,
        'payment_status' => null,
    ]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/search*' => Http::response(['results' => []]),
    ]);

    $this->withoutVite();

    actingAs($buyer)
        ->get(route('orders.pay', ['order' => $order]))
        ->assertOk()
        ->assertSeeText('No pudimos recuperar los productos');
});

it('snapshots the cart items from the legacy pay route', function () {
    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'items' => null,
        'invoice' => null,
        'payment_status' => null,
    ]);

    actingAs($buyer)
        ->get(route('orders.pay.legacy', ['id' => $cart->id, 'order' => $order]))
        ->assertRedirect(route('orders.pay', ['order' => $order]));

    expect($order->fresh()->items)->toHaveCount(1);
});

it('allows a refunded payment to update an approved order', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = orderForCart($buyer, $cart, [
        'payment_id' => 123,
        'payment_status' => 'approved',
        'invoice' => 'factura-123.pdf',
    ]);

    Storage::disk('invoices')->put('factura-123.pdf', '%PDF');

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/123' => Http::response([
            ...approvedPayment($order, $product),
            'id' => 123,
            'status' => 'refunded',
        ]),
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

    expect($order->payment_status)->toBe('refunded')
        ->and($order->payment_id)->toBe(123)
        ->and($order->invoice)->toBe('factura-123.pdf');
});

it('uses the order items for the invoice and stock when Mercado Pago sends other items', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $orderedProduct = $cart->products->first();
    $order = orderForCart($buyer, $cart);
    $otherProduct = Product::factory()->create(['price' => 1000, 'stock' => 10]);

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/123' => Http::response(
            approvedPayment($order, $otherProduct, 1, 2000)
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

    $order->refresh();

    expect($order->invoice)->not->toBeNull()
        ->and($orderedProduct->fresh()->stock)->toBe(8)
        ->and($otherProduct->fresh()->stock)->toBe(10);
});

it('does not downgrade an approved order when a rejected payment webhook arrives', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $cart = cartWithProduct($buyer, 2, 1000);
    $product = $cart->products->first();
    $order = orderForCart($buyer, $cart, [
        'payment_id' => 999,
        'payment_status' => 'approved',
        'invoice' => 'factura-999.pdf',
    ]);

    Storage::disk('invoices')->put('factura-999.pdf', '%PDF');

    Http::preventStrayRequests();
    Http::fake([
        'api.mercadopago.com/v1/payments/123' => Http::response([
            ...approvedPayment($order, $product),
            'id' => 123,
            'status' => 'rejected',
        ]),
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

    expect($order->payment_status)->toBe('approved')
        ->and($order->payment_id)->toBe(999)
        ->and($order->invoice)->toBe('factura-999.pdf');
});
