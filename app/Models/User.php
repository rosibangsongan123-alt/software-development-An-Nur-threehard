<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role', 'is_active', 'branch_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Role yang dikenal aplikasi. Owner punya akses penuh, Kasir hanya transaksi.
     */
    public const ROLE_OWNER = 'owner';

    public const ROLE_KASIR = 'kasir';

    public const ROLES = [self::ROLE_OWNER, self::ROLE_KASIR];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'branch_id' => 'integer',
        ];
    }

    /**
     * Cabang tempat user bertugas (Issue #7). NULL untuk Owner kantor pusat.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isKasir(): bool
    {
        return $this->role === self::ROLE_KASIR;
    }

    /**
     * Halaman tujuan setelah login, dipakai FE untuk routing.
     */
    public function redirectPath(): string
    {
        return $this->isOwner() ? '/owner/dashboard' : '/kasir/dashboard';
    }
}
