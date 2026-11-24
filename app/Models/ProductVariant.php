<?php

namespace App\Models;

use Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Varian produk: satu ukuran/warna dari sebuah produk (Issue #8).
 * SKU, harga, dan stok hidup di level ini, bukan di produk induknya.
 *
 * @property int $id
 * @property int $product_id
 * @property string $sku
 * @property string|null $size
 * @property string|null $color
 * @property int $price
 * @property int $stock
 * @property int|null $branch_id
 * @property bool $is_active
 */
#[Fillable(['product_id', 'sku', 'size', 'color', 'price', 'stock', 'branch_id', 'is_active'])]
class ProductVariant extends Model
{
    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory;

    protected $attributes = [
        'is_active' => true,
        'stock' => 0,
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'branch_id' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Cabang tempat stok ini berada. NULL = stok umum (belum dibagi per cabang).
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
