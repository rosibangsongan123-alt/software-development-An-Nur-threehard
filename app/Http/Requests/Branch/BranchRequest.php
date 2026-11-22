<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi POST /api/branches dan PUT/PATCH /api/branches/{branch} (Issue #7).
 *
 * PUT  : name & address wajib (penggantian penuh).
 * PATCH: hanya field yang dikirim yang divalidasi & diubah.
 */
class BranchRequest extends FormRequest
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
        $partial = $this->isMethod('patch');
        $required = $partial ? 'sometimes' : 'required';

        return [
            'name' => [$required, 'string', 'max:100', Rule::unique('branches', 'name')->ignore($this->route('branch'))],
            'address' => [$required, 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama cabang wajib diisi.',
            'name.max' => 'Nama cabang maksimal 100 karakter.',
            'name.unique' => 'Nama cabang sudah dipakai. Gunakan nama lain.',
            'address.required' => 'Alamat cabang wajib diisi.',
            'address.max' => 'Alamat maksimal 255 karakter.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka, tanda +, -, dan spasi.',
            'is_active.boolean' => 'Status aktif harus benar atau salah.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $clean = [];

        foreach (['name', 'address', 'phone'] as $field) {
            if ($this->has($field)) {
                $trimmed = trim((string) $this->input($field));
                // Field kosong diperlakukan sebagai "tidak diisi" (NULL di database).
                $clean[$field] = $trimmed === '' ? null : $trimmed;
            }
        }

        if ($this->has('is_active')) {
            $clean['is_active'] = filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        $this->merge($clean);
    }
}
