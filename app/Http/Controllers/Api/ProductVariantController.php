<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductVariantRequest;
use App\Http\Resources\ProductVariantResource;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CRUD Varian Produk (Issue #8) — GET/POST/PUT/PATCH/DELETE /api/product-variants.
 *
 * Varian = satu ukuran/warna dari sebuah produk, punya SKU, harga, dan stok sendiri.
 * - Semua endpoint butuh Bearer token (auth:sanctum).
 * - Hanya Owner yang boleh menambah, mengubah, menghapus varian. Kasir hanya GET.
 * - Stok tidak boleh minus (aturan bisnis), dijaga di validasi min:0.
 */
class ProductVariantController extends Controller
{
    /**
     * GET /api/product-variants
     *
     * Query yang dikenali: per_page (1-100, default 15), product_id, branch_id,
     * search (sku/warna/ukuran), is_active, with_inactive=1, low_stock=1 (stok <= 5).
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        $paginator = ProductVariant::query()
            ->with(['product', 'branch'])
            ->when($request->filled('product_id'), fn ($query) => $query->where('product_id', (int) $request->query('product_id')))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', (int) $request->query('branch_id')))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $keyword = '%'.str_replace(['%', '_'], ['\%', '\_'], (string) $request->query('search')).'%';
                $query->where(fn ($q) => $q->where('sku', 'like', $keyword)
                    ->orWhere('size', 'like', $keyword)
                    ->orWhere('color', 'like', $keyword));
            })
            ->when($request->boolean('low_stock'), fn ($query) => $query->where('stock', '<=', 5))
            ->when($request->filled('is_active'), fn ($query) => $query->where('is_active', $request->boolean('is_active')))
            ->when(! $request->boolean('with_inactive') && ! $request->filled('is_active'), fn ($query) => $query->where('is_active', true))
            ->orderByDesc('id')
            ->paginate($perPage);

        $items = $paginator->getCollection()->map(fn (ProductVariant $variant) => new ProductVariantResource($variant));

        return response()->json([
            'success' => true,
            'message' => 'Daftar varian berhasil diambil.',
            'data' => [
                'variants' => $items->all(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ],
        ], 200);
    }

    /**
     * POST /api/product-variants
     */
    public function store(ProductVariantRequest $request): JsonResponse
    {
        $forbidden = $this->denyNonOwner();
        if ($forbidden !== null) {
            return $forbidden;
        }

        $variant = ProductVariant::create($request->validated());

        return $this->success(
            message: 'Varian berhasil dibuat.',
            data: ['variant' => new ProductVariantResource($variant->load(['product', 'branch']))],
            status: 201,
        );
    }

    /**
     * GET /api/product-variants/{product_variant}
     */
    public function show(ProductVariant $product_variant): JsonResponse
    {
        return $this->success(
            message: 'Data varian berhasil diambil.',
            data: ['variant' => new ProductVariantResource($product_variant->load(['product', 'branch']))],
        );
    }

    /**
     * PUT /api/product-variants/{product_variant}  (ganti penuh)
     * PATCH /api/product-variants/{product_variant} (ubah sebagian, mis. stok saja)
     */
    public function update(ProductVariantRequest $request, ProductVariant $product_variant): JsonResponse
    {
        $forbidden = $this->denyNonOwner();
        if ($forbidden !== null) {
            return $forbidden;
        }

        $product_variant->update($request->validated());

        return $this->success(
            message: 'Varian berhasil diperbarui.',
            data: ['variant' => new ProductVariantResource($product_variant->load(['product', 'branch']))],
        );
    }

    /**
     * DELETE /api/product-variants/{product_variant}
     */
    public function destroy(ProductVariant $product_variant): JsonResponse
    {
        $forbidden = $this->denyNonOwner();
        if ($forbidden !== null) {
            return $forbidden;
        }

        $product_variant->delete();

        return $this->success(message: 'Varian berhasil dihapus.');
    }

    /**
     * Owner-only guard. Balas null bila boleh lanjut, selain itu balas 403.
     */
    private function denyNonOwner(): ?JsonResponse
    {
        $user = request()->user();

        if ($user instanceof User && $user->isOwner()) {
            return null;
        }

        return $this->error(
            message: 'Hanya Owner yang boleh mengelola varian produk.',
            errors: ['role' => ['Akses ditolak.']],
            status: 403,
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
