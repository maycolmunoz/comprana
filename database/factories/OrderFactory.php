<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => User::factory(),
            'total' => $this->faker->numberBetween(100, 1000),
            'address' => $this->faker->address(),
            'phone' => $this->faker->numerify('##########'),
            'status' => OrderStatus::Processing,
            'payment_status' => 'pending',
            'invoice' => 'INV-'.$this->faker->unique()->numberBetween(1000, 9999),
        ];
    }
}
