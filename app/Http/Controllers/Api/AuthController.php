<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Auth API (Issue #6): POST /api/login, POST /api/logout, GET /api/me.
 *
 * Token memakai Laravel Sanctum (personal access token, tipe Bearer).
 * Semua respons berbentuk JSON dengan envelope:
 *   { success: bool, message: string, data?: array, errors?: array }
 */
class AuthController extends Controller
{
    /**
     * Umur token: 1 hari, atau 30 hari jika FE mengirim remember=true.
     */
    private const TOKEN_TTL_DAYS = 1;

    private const TOKEN_TTL_REMEMBER_DAYS = 30;

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->validated('email'))->first();

        // Balasan sama untuk email tidak terdaftar dan password salah,
        // supaya tidak memudahkan penebakan akun mana yang ada di database.
        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            return $this->error(
                message: 'Email atau password salah.',
                errors: ['email' => ['Email atau password salah.']],
                status: 401,
            );
        }

        if (! $user->is_active) {
            return $this->error(
                message: 'Akun ini sedang dinonaktifkan. Hubungi Owner.',
                errors: ['email' => ['Akun dinonaktifkan.']],
                status: 403,
            );
        }

        $ttlDays = $request->boolean('remember')
            ? self::TOKEN_TTL_REMEMBER_DAYS
            : self::TOKEN_TTL_DAYS;

        $expiresAt = now()->addDays($ttlDays);

        $token = $user->createToken(
            name: 'api-'.Str::limit((string) $request->userAgent(), 60, ''),
            abilities: ['*'],
            expiresAt: $expiresAt,
        )->plainTextToken;

        return $this->success(
            message: 'Login berhasil.',
            data: [
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => $expiresAt->toIso8601String(),
            ],
            status: 200,
        );
    }

    /**
     * Menghapus token yang sedang dipakai. Device lain tidak terpengaruh.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(message: 'Logout berhasil.');
    }

    /**
     * Data user untuk token aktif. Dipakai FE untuk cek sesi saat halaman dibuka.
     */
    public function me(Request $request): JsonResponse
    {
        return $this->success(
            message: 'Data user berhasil diambil.',
            data: ['user' => new UserResource($request->user())],
        );
    }

    private function success(string $message, array $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    private function error(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
