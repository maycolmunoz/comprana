<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $images = [
            'products/1.png',
            'products/2.png',
            'products/3.png',
            'products/4.png',
        ];

        return [
            'name' => fake()->sentence(3),
            'images' => $images,
            'description' => fake()->paragraph(),
            'price' => rand(1000, 100000),
            'stock' => rand(100, 1000),
        ];
    }
}
