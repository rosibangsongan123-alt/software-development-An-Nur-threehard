<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(10),
            'category' => fake()->randomElement(['Sepatu', 'Sandal', 'Boots']),
            'photo_path' => null,
            'is_active' => true,
        ];
    }
}
