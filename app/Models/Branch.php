<?php

namespace App\Models;

use Database\Factories\BranchFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Master data cabang (Issue #7).
 *
 * @property int    $id
 * @property string $name
 * @property string $address
 * @property string|null $phone
 * @property bool   $is_active
 */
#[Fillable(['name', 'address', 'phone', 'is_active'])]
class Branch extends Model
{
    /** @use HasFactory<BranchFactory> */
    use HasFactory;

    /**
     * Nilai bawaan saat cabang baru dibuat tanpa menyebut status.
     * Sekalian memastikan objek hasil create() sudah berisi true, bukan NULL.
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
     * Kasir yang ditugaskan di cabang ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
