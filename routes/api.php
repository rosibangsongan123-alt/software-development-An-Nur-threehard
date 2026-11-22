<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
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
});
