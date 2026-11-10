<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambah SATU kolom "foto_profil" (path file di disk public, folder foto-profil) pada tabel pengguna.
     * Kolom lain tidak diubah. Wajib untuk calon pekerja, opsional untuk klien & admin.
     */
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->string('foto_profil')->nullable()->after('no_telepon');
        });
    }

    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};
