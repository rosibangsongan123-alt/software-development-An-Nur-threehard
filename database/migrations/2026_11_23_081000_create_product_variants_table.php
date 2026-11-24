<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Issue #8 — varian produk (ukuran / warna).
 *
 * Satu produk punya banyak varian. Tiap varian memegang SKU, harga, dan stok
 * sendiri. branch_id boleh NULL (stok umum/gudang pusat); bila diisi, stok
 * varian itu terikat satu cabang (Issue #7).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku', 50)->unique();
            $table->string('size', 20)->nullable();
            $table->string('color', 50)->nullable();
            $table->unsignedInteger('price');
            $table->integer('stock')->default(0);
            $table->foreignId('branch_id')->nullable()
                ->constrained('branches')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
