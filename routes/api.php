<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductVariantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes  (Tahap 1 — Issue #5)
|--------------------------------------------------------------------------
| Semua route di sini otomatis berada di grup middleware "api" dan
| di-prefix /api. Response error untuk route api/* dirender JSON
| oleh bootstrap/app.php.
|
| Endpoint sesuai Issue #6:
|   POST /api/login   : email + password -> Bearer token
|   POST /api/logout  : hapus token yang dipakai (butuh header Authorization)
|   GET  /api/me      : data user milik token aktif (cek sesi FE)
*/

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('api.login');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');

    /*
    | Issue #7 — CRUD Cabang.
    |   GET    /api/branches          daftar (pagination: ?page, ?per_page, ?search, ?is_active)
    |   POST   /api/branches          tambah            (Owner)
    |   GET    /api/branches/{id}     detail
    |   PUT    /api/branches/{id}     ganti penuh       (Owner)
    |   PATCH  /api/branches/{id}     ubah sebagian     (Owner)
    |   DELETE /api/branches/{id}     hapus             (Owner; ?force=1 bila masih ada user)
    */
    Route::apiResource('branches', BranchController::class);

    /*
    | Issue #8 — CRUD Produk & Varian.
    |   GET    /api/products                 daftar (?page, ?per_page, ?search, ?category,
    |                                            ?is_active, ?with_inactive=1, ?with_variants=1)
    |   POST   /api/products                 tambah          (Owner)
    |   GET    /api/products/{id}            detail (+ ?with_variants=1 ikut bawa variannya)
    |   PUT    /api/products/{id}            ganti penuh     (Owner)
    |   PATCH  /api/products/{id}            ubah sebagian    (Owner)
    |   DELETE /api/products/{id}            hapus            (Owner; ?force=1 bila masih ada varian)
    |
    |   GET    /api/product-variants         daftar varian (?product_id, ?branch_id, ?search,
    |                                            ?low_stock=1, ?is_active, ?with_inactive=1)
    |   POST   /api/product-variants         tambah          (Owner)
    |   GET    /api/product-variants/{id}    detail
    |   PUT    /api/product-variants/{id}    ganti penuh     (Owner)
    |   PATCH  /api/product-variants/{id}    ubah sebagian    (Owner; mis. stok saja)
    |   DELETE /api/product-variants/{id}    hapus            (Owner)
    |
    | Kasir hanya boleh GET; tulis/hapus dibalas 403 oleh controller.
    */
    Route::apiResource('products', ProductController::class);
    Route::apiResource('product-variants', ProductVariantController::class);
});
