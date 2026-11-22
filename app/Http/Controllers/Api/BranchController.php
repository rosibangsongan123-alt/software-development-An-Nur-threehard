<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\BranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CRUD Cabang (Issue #7) — GET/POST/PUT/PATCH/DELETE /api/branches.
 *
 * Aturan main:
 * - Semua endpoint butuh Bearer token (auth:sanctum), dipasang di routes/api.php.
 * - Hanya Owner yang boleh menambah, mengubah, menghapus cabang.
 *   Kasir hanya boleh melihat daftar (GET), dipakai untuk pilih cabang di FE.
 * - Cabang yang masih punya user tidak boleh dihapus, supaya riwayat transaksi
 *   kasir tidak kehilangan induknya.
 * - Envelope JSON seragam dengan Auth API:
 *   { success, message, data } / { success, message, errors }.
 */
class BranchController extends Controller
{
    /**
     * GET /api/branches
     *
     * Query yang dikenali: per_page (1-100, default 15), search (nama/alamat),
     * is_active (true/false), with_inactive=1 untuk ikut menyertakan cabang nonaktif.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        $paginator = Branch::query()
            ->withCount('users')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $keyword = '%'.str_replace(['%', '_'], ['\%', '\_'], (string) $request->query('search')).'%';
                $query->where(fn ($q) => $q->where('name', 'like', $keyword)
                    ->orWhere('address', 'like', $keyword));
            })
            ->when($request->filled('is_active'), fn ($query) => $query->where('is_active', $request->boolean('is_active')))
            ->when(! $request->boolean('with_inactive') && ! $request->filled('is_active'), fn ($query) => $query->where('is_active', true))
            ->orderBy('name')
            ->paginate($perPage);

        $items = $paginator->getCollection()->map(fn (Branch $branch) => new BranchResource($branch));

        return response()->json([
            'success' => true,
            'message' => 'Daftar cabang berhasil diambil.',
            'data' => [
                'branches' => $items->all(),
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
     * POST /api/branches
     */
    public function store(BranchRequest $request): JsonResponse
    {
        $forbidden = $this->denyNonOwner();
        if ($forbidden !== null) {
            return $forbidden;
        }

        $branch = Branch::create($request->validated());

        return $this->success(
            message: 'Cabang berhasil dibuat.',
            data: ['branch' => new BranchResource($branch->loadCount('users'))],
            status: 201,
        );
    }

    /**
     * GET /api/branches/{branch}
     */
    public function show(Branch $branch): JsonResponse
    {
        return $this->success(
            message: 'Data cabang berhasil diambil.',
            data: ['branch' => new BranchResource($branch->loadCount('users'))],
        );
    }

    /**
     * PUT /api/branches/{branch}  (ganti penuh)
     * PATCH /api/branches/{branch} (ubah sebagian)
     */
    public function update(BranchRequest $request, Branch $branch): JsonResponse
    {
        $forbidden = $this->denyNonOwner();
        if ($forbidden !== null) {
            return $forbidden;
        }

        $branch->update($request->validated());

        return $this->success(
            message: 'Cabang berhasil diperbarui.',
            data: ['branch' => new BranchResource($branch->loadCount('users'))],
        );
    }

    /**
     * DELETE /api/branches/{branch}
     */
    public function destroy(Request $request, Branch $branch): JsonResponse
    {
        $forbidden = $this->denyNonOwner();
        if ($forbidden !== null) {
            return $forbidden;
        }

        $userCount = $branch->users()->count();

        if ($userCount > 0 && ! $request->boolean('force')) {
            return $this->error(
                message: 'Cabang masih dipakai '.$userCount.' user. Nonaktifkan dulu atau pindahkan user-nya.',
                errors: ['branch' => ['Cabang masih memiliki user terhubung.']],
                status: 409,
            );
        }

        // force=1: lepas dulu kaitan user (branch_id jadi NULL), baru hapus cabang.
        if ($userCount > 0) {
            User::query()->where('branch_id', $branch->id)->update(['branch_id' => null]);
        }

        $branch->delete();

        return $this->success(message: 'Cabang berhasil dihapus.');
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
            message: 'Hanya Owner yang boleh mengelola cabang.',
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
