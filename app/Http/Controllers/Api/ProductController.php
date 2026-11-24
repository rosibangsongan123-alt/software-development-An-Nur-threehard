<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CRUD Produk (Issue #8) — GET/POST/PUT/PATCH/DELETE /api/products.
 *
 * Aturan main:
 * - Semua endpoint butuh Bearer token (auth:sanctum), dipasang di routes/api.php.
 * - Hanya Owner yang boleh menambah, mengubah, menghapus produk.
 *   Kasir hanya boleh melihat daftar & detail (dipakai saat transaksi).
 * - Produk adalah induk: harga & stok hidup di variannya (lihat ProductVariantController).
 * - Envelope JSON seragam: { success, message, data } / { success, message, errors }.
 */
class ProductController extends Controller
{
    /**
     * GET /api/products
     *
     * Query yang dikenali: per_page (1-100, default 15), search (nama/deskripsi/kategori),
     * category, is_active (true/false), with_inactive=1, with_variants=1.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        $paginator = Product::query()
            ->withCount('variants')
            ->when($request->boolean('with_variants'), fn ($query) => $query->with('variants'))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $keyword = '%'.str_replace(['%', '_'], ['\%', '\_'], (string) $request->query('search')).'%';
                $query->where(fn ($q) => $q->where('name', 'like', $keyword)
                    ->orWhere('description', 'like', $keyword)
                    ->orWhere('category', 'like', $keyword));
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->query('category')))
            ->when($request->filled('is_active'), fn ($query) => $query->where('is_active', $request->boolean('is_active')))
            ->when(! $request->boolean('with_inactive') && ! $request->filled('is_active'), fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->paginate($perPage);

        $items = $paginator->getCollection()->map(fn (Product $product) => new ProductResource($product));

        return response()->json([
            'success' => true,
            'message' => 'Daftar produk berhasil diambil.',
            'data' => [
                'products' => $items->all(),
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
     * POST /api/products
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $forbidden = $this->denyNonOwner();
        if ($forbidden !== null) {
            return $forbidden;
        }

        $product = Product::create($request->validated());

        return $this->success(
            message: 'Produk berhasil dibuat.',
            data: ['product' => new ProductResource($product->loadCount('variants'))],
            status: 201,
        );
    }

    /**
     * GET /api/products/{product}
     */
    public function show(Request $request, Product $product): JsonResponse
    {
        $product->loadCount('variants');

        // Sekalian bawa daftar varian bila FE minta (?with_variants=1).
        if ($request->boolean('with_variants')) {
            $product->load('variants');
        }

        return $this->success(
            message: 'Data produk berhasil diambil.',
            data: ['product' => new ProductResource($product)],
        );
    }

    /**
     * PUT /api/products/{product}  (ganti penuh)
     * PATCH /api/products/{product} (ubah sebagian)
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $forbidden = $this->denyNonOwner();
        if ($forbidden !== null) {
            return $forbidden;
        }

        $product->update($request->validated());

        return $this->success(
            message: 'Produk berhasil diperbarui.',
            data: ['product' => new ProductResource($product->loadCount('variants'))],
        );
    }

    /**
     * DELETE /api/products/{product}
     *
     * Produk yang masih punya varian tidak boleh dihapus begitu saja
     * (?force=1 untuk menghapus variannya sekaligus).
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        $forbidden = $this->denyNonOwner();
        if ($forbidden !== null) {
            return $forbidden;
        }

        $variantCount = $product->variants()->count();

        if ($variantCount > 0 && ! $request->boolean('force')) {
            return $this->error(
                message: 'Produk masih punya '.$variantCount.' varian. Hapus variannya dulu atau kirim ?force=1.',
                errors: ['product' => ['Produk masih memiliki varian.']],
                status: 409,
            );
        }

        if ($variantCount > 0) {
            $product->variants()->delete();
        }

        $product->delete();

        return $this->success(message: 'Produk berhasil dihapus.');
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
            message: 'Hanya Owner yang boleh mengelola produk.',
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
