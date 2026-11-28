<?php

use App\Events\CreateOrderEvent;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;

it('names the invoice with the order number and date', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $product = Product::factory()->create([
        'name' => 'Café de origen',
        'price' => 1000,
        'stock' => 10,
    ]);
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 2000,
        'invoice' => null,
        'payment_status' => 'approved',
        'created_at' => '2026-11-28 09:31:00',
    ]);

    CreateOrderEvent::dispatch([
        [
            'id' => (string) $product->id,
            'title' => $product->name,
            'quantity' => 2,
            'unit_price' => 1000,
        ],
    ], $order);

    $order->refresh();

    expect($order->invoice)->toBe(
        'factura-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT).'-2026-11-28.pdf'
    );

    Storage::disk('invoices')->assertExists($order->invoice);
});

it('keeps long content inside the receipt and paginates', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create([
        'name' => 'Cliente '.str_repeat('X', 80),
    ]);
    $product = Product::factory()->create([
        'name' => 'Producto '.str_repeat('Y', 90),
        'price' => 1250000,
        'stock' => 100,
    ]);
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'total' => 37500000,
        'address' => 'Dirección '.str_repeat('Z', 180),
        'invoice' => null,
        'payment_status' => 'approved',
    ]);

    $items = array_fill(0, 30, [
        'id' => (string) $product->id,
        'title' => $product->name,
        'quantity' => 1,
        'unit_price' => 1250000,
    ]);

    CreateOrderEvent::dispatch($items, $order);

    $order->refresh();

    Storage::disk('invoices')->assertExists($order->invoice);

    $pdf = Storage::disk('invoices')->get($order->invoice);

    expect(substr($pdf, 0, 4))->toBe('%PDF')
        ->and(preg_match_all('/\/Type \/Page\b/', $pdf))->toBeGreaterThan(1);
});

it('serves an invoice with a descriptive filename', function () {
    Storage::fake('invoices');

    $buyer = User::factory()->create();
    $order = Order::factory()->create([
        'customer_id' => $buyer->id,
        'invoice' => 'order5.pdf',
        'created_at' => '2026-11-28 09:31:00',
    ]);

    Storage::disk('invoices')->put('order5.pdf', '%PDF-1.4');

    actingAs($buyer)
        ->get(route('orders.invoice', ['name' => 'order5.pdf']))
        ->assertOk()
        ->assertHeader(
            'Content-Disposition',
            'inline; filename="'.$order->invoiceFileName().'"'
        );
});
