<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####??')),
            'size' => fake()->randomElement(['38', '39', '40', '41', '42']),
            'color' => fake()->randomElement(['Hitam', 'Cokelat', 'Putih']),
            'price' => fake()->numberBetween(50000, 900000),
            'stock' => fake()->numberBetween(0, 50),
            'branch_id' => null,
            'is_active' => true,
        ];
    }
}
