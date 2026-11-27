<?php

use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('calculates the cart total from quantities and prices', function () {
    $user = User::factory()->create();
    $cart = $user->carts()->first();
    $first = Product::factory()->create(['name' => 'Producto A', 'price' => 1500]);
    $second = Product::factory()->create(['name' => 'Producto B', 'price' => 2500]);
    $cart->products()->attach($first, ['cant' => 2]);
    $cart->products()->attach($second, ['cant' => 3]);

    expect($cart->fresh(['products'])->total())->toBe(10500);
});

it('opens the rename modal with the cart name', function () {
    $user = User::factory()->create();
    $cart = $user->carts()->first();

    actingAs($user);

    Livewire::test('carts.list')
        ->call('editCart', $cart->id)
        ->assertSet('showRenameModal', true)
        ->assertSet('cartName', $cart->name);
});

it('renames the user own cart even when the name does not change', function () {
    $user = User::factory()->create();
    $cart = $user->carts()->first();

    actingAs($user);

    Livewire::test('carts.list')
        ->set('editingCartId', $cart->id)
        ->set('cartName', $cart->name)
        ->call('renameCart')
        ->assertHasNoErrors();

    expect($cart->fresh()->name)->toBe($cart->name);
});

it('does not rename another user cart', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $cart = $owner->carts()->first();

    actingAs($other);

    Livewire::test('carts.list')
        ->set('editingCartId', $cart->id)
        ->set('cartName', 'Ajeno')
        ->call('renameCart')
        ->assertHasNoErrors();

    expect($cart->fresh()->name)->not->toBe('Ajeno');
});

it('keeps only one active cart', function () {
    $user = User::factory()->create();
    $carts = $user->carts()->orderBy('id')->get();

    actingAs($user);

    Livewire::test('carts.list')->call('activateCart', $carts[1]->id);

    expect($carts[0]->fresh()->active)->toBeFalse()
        ->and($carts[1]->fresh()->active)->toBeTrue();
});

it('does not activate another user cart', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $ownerCart = $owner->carts()->orderBy('id')->get()[1];

    actingAs($other);

    Livewire::test('carts.list')->call('activateCart', $ownerCart->id);

    expect($ownerCart->fresh()->active)->toBeFalse()
        ->and($other->activeCart->id)->not->toBe($ownerCart->id);
});

it('rejects a quantity over the real stock even if the client tampers with the stock property', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'Producto D', 'stock' => 3]);

    actingAs($user);

    Livewire::test('products.details')
        ->call('loadProduct', $product->id)
        ->set('stock', 999)
        ->set('quantity', 10)
        ->call('addToCart')
        ->assertHasErrors(['quantity']);

    expect($user->activeCart->products()->whereKey($product->id)->exists())->toBeFalse();
});

it('rejects a quantity greater than stock', function () {
    $user = User::factory()->create();
    $cart = $user->carts()->first();
    $product = Product::factory()->create(['name' => 'Producto C', 'stock' => 3]);
    $cart->products()->attach($product, ['cant' => 1]);

    actingAs($user);

    Livewire::test('carts.product', ['product' => $product, 'cart' => $cart, 'quantity' => 1])
        ->set('quantity', 4)
        ->call('updateQuantity')
        ->assertHasErrors(['quantity']);
});

it('rejects a quantity below one', function () {
    $user = User::factory()->create();
    $cart = $user->carts()->first();
    $product = Product::factory()->create(['name' => 'Producto E', 'stock' => 3]);
    $cart->products()->attach($product, ['cant' => 1]);

    actingAs($user);

    Livewire::test('carts.product', ['product' => $product, 'cart' => $cart, 'quantity' => 1])
        ->set('quantity', 0)
        ->call('updateQuantity')
        ->assertHasErrors(['quantity']);
});
