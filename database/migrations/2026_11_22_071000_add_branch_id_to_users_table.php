<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Issue #7 — kaitkan user ke cabang.
 *
 * branch_id boleh NULL: Owner kantor pusat tidak terikat satu cabang,
 * sedangkan Kasir selalu punya cabang tempat ia bertugas.
 * nullOnDelete menjaga data user tetap utuh bila cabangnya dihapus.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('branch_id')->nullable()->after('is_active')
                ->constrained('branches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('branch_id');
        });
    }
};
