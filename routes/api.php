<?php

use App\Http\Controllers\Api\AuthController;
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
});
