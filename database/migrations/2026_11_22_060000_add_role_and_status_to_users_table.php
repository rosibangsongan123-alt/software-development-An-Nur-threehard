<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom tambahan untuk kebutuhan autentikasi (Issue #6).
     *
     * - role      : membedakan hak akses Owner dan Kasir
     * - is_active : akun nonaktif tidak boleh login
     *
     * Nilai role ditulis literal (bukan konstanta App\Models\User) supaya
     * migration tidak bergantung pada kode aplikasi.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role', 20)->default('kasir')->after('password');
            $table->boolean('is_active')->default(true)->after('role');

            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['role']);
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
