<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Master produk (Issue #8).
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $category
 * @property string|null $photo_path
 * @property bool $is_active
 */
#[Fillable(['name', 'description', 'category', 'photo_path', 'is_active'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * Nilai bawaan saat produk dibuat tanpa menyebut status, sekalian
     * memastikan objek hasil create() sudah berisi true, bukan NULL.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Varian (ukuran/warna) milik produk ini.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
