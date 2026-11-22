<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Halaman Blade (hasil slicing FE). Data halaman ini diambil lewat endpoint
| API di routes/api.php memakai Bearer token dari POST /api/login.
|
| Catatan untuk FE (Issue #4/#14): link navigasi di views owner masih
| menunjuk file statis mockup (dasboard.php, kelola_produk.php, dst) dan
| route-nya belum ada. Ganti ke route Laravel saat integrasi.
*/

// Root: langsung ke halaman login, bukan halaman bawaan Laravel.
Route::get('/', fn () => redirect()->route('login'));

// Halaman login — form-nya POST ke /api/login (Issue #6).
Route::view('/login', 'auth.login')->name('login');

// Dashboard Owner. Nama view masih 'dasboard' mengikuti nama file dari FE,
// dirapikan setelah FE memperbaiki penamaan file.
Route::view('/owner/dashboard', 'owner.dasboard')->name('owner.dashboard');
