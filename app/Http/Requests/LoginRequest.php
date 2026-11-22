<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi endpoint POST /api/login (Issue #6).
 *
 * Nama field disamakan dengan form FE: email, password, remember.
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => ['required', 'string', 'max:72'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Pesan kesalahan berbahasa Indonesia agar FE bisa menampilkannya apa adanya.
     *
     * Panjang kata sandi sengaja tidak divalidasi di sini: kalau input password
     * pendek ditolak 422, user dengan password lama bisa bingung dan akunnya
     * terlihat rusak. Cukup dibalas 401 "email atau password salah".
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.max' => 'Kata sandi maksimal 72 karakter.',
            'remember.boolean' => 'Ingat saya harus bernilai benar atau salah.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('email')) {
            $this->merge(['email' => strtolower(trim((string) $this->input('email')))]);
        }
    }
}
