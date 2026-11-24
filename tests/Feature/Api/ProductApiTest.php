<?php

namespace Tests\Feature\Api;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Issue #8 — CRUD Produk & Varian: GET/POST/PUT/PATCH/DELETE /api/products & /api/product-variants.
 */
class ProductApiTest extends TestCase
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

    public function test_butuh_token(): void
    {
        $this->getJson('/api/products')->assertUnauthorized();
        $this->postJson('/api/products', [])->assertUnauthorized();
        $this->getJson('/api/products/1')->assertUnauthorized();
        $this->putJson('/api/products/1', [])->assertUnauthorized();
        $this->patchJson('/api/products/1', [])->assertUnauthorized();
        $this->deleteJson('/api/products/1')->assertUnauthorized();

        $this->getJson('/api/product-variants')->assertUnauthorized();
        $this->postJson('/api/product-variants', [])->assertUnauthorized();
        $this->getJson('/api/product-variants/1')->assertUnauthorized();
        $this->putJson('/api/product-variants/1', [])->assertUnauthorized();
        $this->deleteJson('/api/product-variants/1')->assertUnauthorized();
    }

    public function test_product_crud_lengkap(): void
    {
        $this->owner();

        // CREATE
        $create = $this->postJson('/api/products', [
            'name' => 'Sepatu Kulit Hitam',
            'description' => 'Kulit asli',
            'category' => 'Sepatu',
            'photo_path' => 'produk/sepatu.jpg',
        ])->assertStatus(201)->assertJsonStructure([
            'success', 'message',
            'data' => ['product' => ['id', 'name', 'description', 'category', 'photo_path', 'is_active', 'variants_count']],
        ]);

        $id = $create->json('data.product.id');
        $this->assertTrue($create->json('data.product.is_active'));

        // READ index + search + filter
        $this->getJson('/api/products?search=sepatu&category=Sepatu&with_variants=1')
            ->assertOk()
            ->assertJsonPath('data.meta.total', 1);

        $this->getJson('/api/products?search=sendal')->assertOk()->assertJsonPath('data.meta.total', 0);

        // READ detail
        $this->getJson('/api/products/'.$id)->assertOk()->assertJsonPath('data.product.id', $id);

        // UPDATE (PUT penuh)
        $this->putJson('/api/products/'.$id, [
            'name' => 'Sepatu Kulit Cokelat',
            'description' => null,
            'category' => 'Sepatu',
        ])->assertOk()->assertJsonPath('data.product.name', 'Sepatu Kulit Cokelat');

        // PATCH sebagian (nonaktifkan)
        $this->patchJson('/api/products/'.$id, ['is_active' => false])
            ->assertOk()
            ->assertJsonPath('data.product.is_active', false);

        // produk nonaktif tidak muncul di daftar default, muncul bila ?with_inactive=1
        $this->getJson('/api/products')->assertOk()->assertJsonPath('data.meta.total', 0);
        $this->getJson('/api/products?with_inactive=1')->assertOk()->assertJsonPath('data.meta.total', 1);

        // validasi: nama duplikat
        $this->postJson('/api/products', ['name' => 'Sepatu Kulit Cokelat'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');

        // validasi: name wajib
        $this->postJson('/api/products', [])->assertStatus(422)->assertJsonValidationErrors('name');

        // DELETE
        $this->deleteJson('/api/products/'.$id)->assertOk();
        $this->assertDatabaseMissing('products', ['id' => $id]);
        $this->getJson('/api/products/'.$id)->assertNotFound();
    }

    public function test_variant_crud_lengkap(): void
    {
        $this->owner();
        $branch = Branch::factory()->create();
        $product = Product::factory()->create();

        $create = $this->postJson('/api/product-variants', [
            'product_id' => $product->id,
            'sku' => 'SKU-001',
            'size' => '42',
            'color' => 'Hitam',
            'price' => '250000',
            'stock' => '4',
            'branch_id' => $branch->id,
        ])->assertStatus(201)->assertJsonStructure([
            'success', 'message',
            'data' => ['variant' => ['id', 'product_id', 'sku', 'size', 'color', 'price', 'stock', 'branch_id', 'is_active']],
        ]);

        $vid = $create->json('data.variant.id');
        $this->assertSame(250000, $create->json('data.variant.price'));
        $this->assertSame(4, $create->json('data.variant.stock'));
        // cabang ikut terbawa supaya FE bisa tampil nama cabang
        $this->assertSame($branch->name, $create->json('data.variant.branch.name'));

        // index + filter product_id + low_stock
        $this->getJson('/api/product-variants?product_id='.$product->id.'&low_stock=1')
            ->assertOk()
            ->assertJsonPath('data.meta.total', 1);

        $this->getJson('/api/product-variants?search=SKU-001')->assertOk()->assertJsonPath('data.meta.total', 1);
        $this->getJson('/api/product-variants?branch_id='.$branch->id)->assertOk()->assertJsonPath('data.meta.total', 1);

        // detail
        $this->getJson('/api/product-variants/'.$vid)->assertOk()->assertJsonPath('data.variant.sku', 'SKU-001');

        // PATCH stok saja
        $this->patchJson('/api/product-variants/'.$vid, ['stock' => 3])
            ->assertOk()
            ->assertJsonPath('data.variant.stock', 3);

        // PUT penuh
        $this->putJson('/api/product-variants/'.$vid, [
            'product_id' => $product->id,
            'sku' => 'SKU-001',
            'price' => 300000,
            'stock' => 5,
        ])->assertOk()->assertJsonPath('data.variant.price', 300000);

        // varian masuk saat produk detail dengan ?with_variants=1
        $this->getJson('/api/products/'.$product->id.'?with_variants=1')
            ->assertOk()
            ->assertJsonPath('data.product.variants_count', 1);

        // validasi: harga minus, stok minus, sku duplikat, product_id tak ada
        $this->postJson('/api/product-variants', [
            'product_id' => 9999, 'sku' => 'SKU-002', 'price' => -1, 'stock' => -5,
        ])->assertStatus(422)->assertJsonValidationErrors(['product_id', 'price', 'stock']);

        $this->postJson('/api/product-variants', [
            'product_id' => $product->id, 'sku' => 'SKU-001', 'price' => 1000, 'stock' => 1,
        ])->assertStatus(422)->assertJsonValidationErrors('sku');

        // DELETE produk yang masih punya varian -> 409, lalu force -> sukses
        $this->deleteJson('/api/products/'.$product->id)->assertStatus(409);
        $this->deleteJson('/api/products/'.$product->id.'?force=1')->assertOk();
        $this->assertDatabaseCount('product_variants', 0);
    }

    public function test_kasir_hanya_boleh_read(): void
    {
        $this->kasir();
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

        $this->getJson('/api/products')->assertOk();
        $this->getJson('/api/products/'.$product->id)->assertOk();
        $this->getJson('/api/product-variants')->assertOk();
        $this->getJson('/api/product-variants/'.$variant->id)->assertOk();

        $this->postJson('/api/products', ['name' => 'Baru'])->assertStatus(403);
        $this->patchJson('/api/products/'.$product->id, ['name' => 'Baru'])->assertStatus(403);
        $this->deleteJson('/api/products/'.$product->id)->assertStatus(403);

        $this->postJson('/api/product-variants', [
            'product_id' => $product->id, 'sku' => 'X', 'price' => 1, 'stock' => 1,
        ])->assertStatus(403);
        $this->deleteJson('/api/product-variants/'.$variant->id)->assertStatus(403);
    }
}
