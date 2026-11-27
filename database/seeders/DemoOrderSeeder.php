<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buyer = User::firstOrCreate(
            ['email' => 'buyer@comprana.test'],
            [
                'name' => 'Comprador Demo',
                'password' => 'password',
                'phone' => '3001234567',
                'address' => 'Calle 123 #45-67, Bogotá',
            ]
        );

        $cart = $buyer->carts()->firstOrFail();

        $products = Product::inRandomOrder()->take(2)->get();

        if ($products->isEmpty()) {
            return;
        }

        $cart->products()->sync(
            $products->mapWithKeys(fn (Product $product) => [
                $product->id => ['cant' => 1],
            ])->all()
        );

        $total = $cart->products()->get()->sum(fn (Product $product) => $product->pivot->cant * $product->price);

        Order::firstOrCreate(
            [
                'customer_id' => $buyer->id,
                'address' => $buyer->address,
            ],
            [
                'phone' => $buyer->phone,
                'status' => OrderStatus::Processing,
                'total' => $total,
                'payment_status' => 'pending',
            ]
        );
    }
}
