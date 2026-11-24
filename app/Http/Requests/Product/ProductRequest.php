<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi POST /api/products dan PUT/PATCH /api/products/{product} (Issue #8).
 *
 * PUT  : name wajib (penggantian penuh).
 * PATCH: hanya field yang dikirim yang divalidasi & diubah.
 */
class ProductRequest extends FormRequest
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
            'name' => [$required, 'string', 'max:120', Rule::unique('products', 'name')->ignore($this->route('product'))],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['nullable', 'string', 'max:50'],
            'photo_path' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 120 karakter.',
            'name.unique' => 'Nama produk sudah dipakai. Gunakan nama lain.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
            'category.max' => 'Kategori maksimal 50 karakter.',
            'photo_path.max' => 'Path foto maksimal 255 karakter.',
            'is_active.boolean' => 'Status aktif harus benar atau salah.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $clean = [];

        foreach (['name', 'description', 'category', 'photo_path'] as $field) {
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
