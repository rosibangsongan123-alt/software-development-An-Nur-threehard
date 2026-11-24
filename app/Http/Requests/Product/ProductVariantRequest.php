<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi POST /api/product-variants dan PUT/PATCH /api/product-variants/{product_variant} (Issue #8).
 *
 * PUT  : product_id, sku, price, stock wajib (penggantian penuh).
 * PATCH: hanya field yang dikirim yang divalidasi & diubah.
 */
class ProductVariantRequest extends FormRequest
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
            'product_id' => [$required, 'integer', Rule::exists('products', 'id')],
            'sku' => [$required, 'string', 'max:50', Rule::unique('product_variants', 'sku')->ignore($this->route('product_variant'))],
            'size' => ['nullable', 'string', 'max:20'],
            'color' => ['nullable', 'string', 'max:50'],
            'price' => [$required, 'integer', 'min:0', 'max:99999999'],
            'stock' => [$required, 'integer', 'min:0'],
            'branch_id' => ['nullable', 'integer', Rule::exists('branches', 'id')],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk induk wajib dipilih.',
            'product_id.exists' => 'Produk yang dipilih tidak ditemukan.',
            'sku.required' => 'SKU wajib diisi.',
            'sku.max' => 'SKU maksimal 50 karakter.',
            'sku.unique' => 'SKU sudah dipakai varian lain. Gunakan kode lain.',
            'size.max' => 'Ukuran maksimal 20 karakter.',
            'color.max' => 'Warna maksimal 50 karakter.',
            'price.required' => 'Harga wajib diisi.',
            'price.integer' => 'Harga harus angka rupiah tanpa desimal.',
            'price.min' => 'Harga tidak boleh minus.',
            'price.max' => 'Harga maksimal Rp 99.999.999.',
            'stock.integer' => 'Stok harus angka.',
            'stock.min' => 'Stok tidak boleh minus.',
            'branch_id.exists' => 'Cabang yang dipilih tidak ditemukan.',
            'is_active.boolean' => 'Status aktif harus benar atau salah.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $clean = [];

        foreach (['sku', 'size', 'color'] as $field) {
            if ($this->has($field)) {
                $trimmed = trim((string) $this->input($field));
                $clean[$field] = $trimmed === '' ? null : $trimmed;
            }
        }

        // Angka dikirim sebagai string ("250000") dari form FE.
        foreach (['product_id', 'price', 'stock', 'branch_id'] as $field) {
            if ($this->has($field)) {
                $value = $this->input($field);
                $clean[$field] = ($value === null || $value === '') ? null : (int) $value;
            }
        }

        if ($this->has('is_active')) {
            $clean['is_active'] = filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        $this->merge($clean);
    }
}
