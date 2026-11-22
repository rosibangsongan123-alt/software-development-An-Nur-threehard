<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRateLimiters();
    }

    /**
     * Rate limiter endpoint login (Issue #6).
     *
     * Dibatasi per kombinasi IP + email supaya satu orang gagal login berkali-kali
     * tidak mengunci user lain yang berbagi IP (misal satu toko).
     */
    private function registerRateLimiters(): void
    {
        RateLimiter::for('login', function (Request $request): Limit {
            $key = $request->ip().'|'.strtolower((string) $request->input('email'));

            return Limit::perMinutes(1, 5)->by($key)
                ->response(function () {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.',
                        'errors' => ['email' => ['Terlalu banyak percobaan login.']],
                    ], 429);
                });
        });
    }
}
