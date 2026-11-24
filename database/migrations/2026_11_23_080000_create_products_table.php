<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Issue #8 — CRUD Produk & Varian.
 *
 * Tabel `products` adalah master produk (induk). Harga dan stok TIDAK ada di
 * sini, melainkan di tabel product_variants, karena satu produk bisa punya
 * beberapa ukuran/warna dengan SKU dan stok masing-masing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120)->unique();
            $table->text('description')->nullable();
            $table->string('category', 50)->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
