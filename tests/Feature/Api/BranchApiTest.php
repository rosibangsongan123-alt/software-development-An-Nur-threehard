<?php

namespace Tests\Feature\Api;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Issue #7 — CRUD Cabang: GET/POST/PUT/PATCH/DELETE /api/branches.
 */
class BranchApiTest extends TestCase
{
    use RefreshDatabase;

    private function owner(): User
    {
        $user = User::factory()->owner()->create();
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    private function kasir(): User
    {
        $user = User::factory()->kasir()->create();
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    public function test_semua_endpoint_butuh_token(): void
    {
        $this->getJson('/api/branches')->assertUnauthorized();
        $this->postJson('/api/branches', [])->assertUnauthorized();
        $this->getJson('/api/branches/1')->assertUnauthorized();
        $this->putJson('/api/branches/1', [])->assertUnauthorized();
        $this->deleteJson('/api/branches/1')->assertUnauthorized();
    }

    public function test_index_mengembalikan_envelope_dan_meta_paging(): void
    {
        $this->owner();
        Branch::factory()->count(3)->create();

        $response = $this->getJson('/api/branches?per_page=2&page=1');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'branches' => [['id', 'name', 'address', 'phone', 'is_active', 'users_count']],
                    'meta' => ['current_page', 'last_page', 'per_page', 'total'],
                ],
            ])
            ->assertJson([
                'success' => true,
                'data' => ['meta' => ['total' => 3, 'per_page' => 2, 'last_page' => 2]],
            ]);

        $this->assertCount(2, $response->json('data.branches'));
    }

    public function test_index_menyaring_lewat_search(): void
    {
        $this->owner();
        Branch::factory()->create(['name' => 'An-Nur Gresik', 'address' => 'Jl. Panglima Sudirman']);
        Branch::factory()->create(['name' => 'Toko Lain', 'address' => 'Jl. Basra']);

        $this->getJson('/api/branches?search=gresik')
            ->assertOk()
            ->assertJsonCount(1, 'data.branches')
            ->assertJson(['data' => ['branches' => [['name' => 'An-Nur Gresik']]]]);
    }

    public function test_cabang_nonaktif_tidak_muncul_kecuali_diminta(): void
    {
        $this->owner();
        Branch::factory()->create(['name' => 'Cabang Aktif']);
        Branch::factory()->nonaktif()->create(['name' => 'Cabang Tutup']);

        $this->getJson('/api/branches')
            ->assertOk()
            ->assertJsonCount(1, 'data.branches')
            ->assertJson(['data' => ['branches' => [['name' => 'Cabang Aktif']]]]);

        $this->getJson('/api/branches?with_inactive=1')
            ->assertOk()
            ->assertJsonCount(2, 'data.branches');

        $this->getJson('/api/branches?is_active=0')
            ->assertOk()
            ->assertJsonCount(1, 'data.branches')
            ->assertJson(['data' => ['branches' => [['name' => 'Cabang Tutup']]]]);
    }

    public function test_store_membuat_cabang_baru(): void
    {
        $this->owner();

        $response = $this->postJson('/api/branches', [
            'name' => '  An-Nur Cabang Malang  ',
            'address' => 'Jl. Veteran No. 3, Malang',
            'phone' => '081234567890',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Cabang berhasil dibuat.',
                'data' => ['branch' => [
                    // Spasi depan-belakang dipotong di server.
                    'name' => 'An-Nur Cabang Malang',
                    'address' => 'Jl. Veteran No. 3, Malang',
                    'is_active' => true,
                ]],
            ]);

        $this->assertDatabaseHas('branches', [
            'name' => 'An-Nur Cabang Malang',
            'phone' => '081234567890',
        ]);
    }

    public function test_store_menolak_nama_kosong_dan_duplikat(): void
    {
        $this->owner();
        Branch::factory()->create(['name' => 'An-Nur Pusat']);

        $this->postJson('/api/branches', ['address' => 'Jl. Tanpa Nama'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJson(['success' => false, 'errors' => ['name' => ['Nama cabang wajib diisi.']]]);

        $this->postJson('/api/branches', ['name' => 'An-Nur Pusat', 'address' => 'Jl. Mana Aja'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJson(['errors' => ['name' => ['Nama cabang sudah dipakai. Gunakan nama lain.']]]);
    }

    public function test_store_menolak_nomor_telepon_neko_neko(): void
    {
        $this->owner();

        $this->postJson('/api/branches', [
            'name' => 'Cabang Salah Telp',
            'address' => 'Jl. Uji',
            'phone' => 'abc-123',
        ])->assertStatus(422)->assertJsonValidationErrors(['phone']);
    }

    public function test_show_mengembalikan_satu_cabang(): void
    {
        $this->owner();
        $branch = Branch::factory()->create(['name' => 'An-Nur Jember']);

        $this->getJson('/api/branches/'.$branch->id)
            ->assertOk()
            ->assertJson(['data' => ['branch' => ['id' => $branch->id, 'name' => 'An-Nur Jember', 'users_count' => 0]]]);
    }

    public function test_cabang_yang_tidak_ada_balas_404(): void
    {
        $this->owner();

        $this->getJson('/api/branches/999')->assertNotFound();
        $this->putJson('/api/branches/999', ['name' => 'X', 'address' => 'Y'])->assertNotFound();
        $this->deleteJson('/api/branches/999')->assertNotFound();
    }

    public function test_update_mengganti_data_cabang(): void
    {
        $this->owner();
        $branch = Branch::factory()->create(['name' => 'Lama', 'address' => 'Alamat Lama']);

        $this->putJson('/api/branches/'.$branch->id, [
            'name' => 'Baru',
            'address' => 'Alamat Baru',
            'phone' => '0811000000',
            'is_active' => false,
        ])->assertOk()->assertJson(['message' => 'Cabang berhasil diperbarui.']);

        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'name' => 'Baru',
            'address' => 'Alamat Baru',
            'is_active' => false,
        ]);
    }

    public function test_patch_hanya_mengubah_field_yang_dikirim(): void
    {
        $this->owner();
        $branch = Branch::factory()->create(['name' => 'Stabil', 'address' => 'Alamat Asli', 'phone' => '08111']);

        $this->patchJson('/api/branches/'.$branch->id, ['is_active' => false])
            ->assertOk()
            ->assertJson(['data' => ['branch' => ['name' => 'Stabil', 'is_active' => false]]]);

        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'address' => 'Alamat Asli',
            'phone' => '08111',
        ]);
    }

    public function test_update_mengizinkan_nama_tetap_sama_milik_sendiri(): void
    {
        $this->owner();
        $branch = Branch::factory()->create(['name' => 'An-Nur Bangil']);

        // Nama tidak berubah: rule unique tidak boleh menolak barisnya sendiri.
        $this->putJson('/api/branches/'.$branch->id, ['name' => 'An-Nur Bangil', 'address' => 'Jl. Baru'])
            ->assertOk();
    }

    public function test_destroy_menghapus_cabang_kosong(): void
    {
        $this->owner();
        $branch = Branch::factory()->create();

        $this->deleteJson('/api/branches/'.$branch->id)
            ->assertOk()
            ->assertJson(['success' => true, 'message' => 'Cabang berhasil dihapus.']);

        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
    }

    public function test_destroy_ditolak_bila_cabang_masih_punya_user(): void
    {
        $this->owner();
        $branch = Branch::factory()->create();
        $user = User::factory()->kasir()->create(['branch_id' => $branch->id]);

        $this->deleteJson('/api/branches/'.$branch->id)
            ->assertStatus(409)
            ->assertJson(['success' => false, 'errors' => ['branch' => ['Cabang masih memiliki user terhubung.']]]);

        $this->assertDatabaseHas('branches', ['id' => $branch->id]);

        // force=1 melepas kaitan user lebih dulu, baru cabang dihapus.
        $this->deleteJson('/api/branches/'.$branch->id.'?force=1')
            ->assertOk()
            ->assertJson(['message' => 'Cabang berhasil dihapus.']);

        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
        $this->assertNull($user->fresh()->branch_id);
    }

    public function test_kasir_hanya_boleh_membaca(): void
    {
        $this->kasir();
        $branch = Branch::factory()->create();

        $this->getJson('/api/branches')->assertOk();
        $this->getJson('/api/branches/'.$branch->id)->assertOk();

        $this->postJson('/api/branches', ['name' => 'Cabang Baru', 'address' => 'Jl. Mana'])
            ->assertStatus(403)
            ->assertJson(['success' => false, 'message' => 'Hanya Owner yang boleh mengelola cabang.']);

        $this->patchJson('/api/branches/'.$branch->id, ['name' => 'Ubah'])->assertStatus(403);
        $this->deleteJson('/api/branches/'.$branch->id)->assertStatus(403);

        $this->assertDatabaseHas('branches', ['id' => $branch->id, 'name' => $branch->name]);
    }
}
