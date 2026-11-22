<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Cabang '.fake()->unique()->city(),
            'address' => fake()->streetAddress(),
            'phone' => fake()->numerify('08##########'),
            'is_active' => true,
        ];
    }

    /**
     * Cabang yang sudah tutup / dinonaktifkan.
     */
    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
