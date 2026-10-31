<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel KRITERIA (master kriteria khusus)
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->string('nama_kriteria', 100);
            $table->string('kategori_kriteria', 50);
            $table->string('deskripsi')->nullable();
        });

        // Tabel KRITERIA_PEKERJA (nilai kriteria tiap pekerja: ya / tidak)
        Schema::create('kriteria_pekerja', function (Blueprint $table) {
            $table->foreignId('id_pekerja')
                ->constrained('profil_pekerja', 'id_pekerja')->cascadeOnDelete();
            $table->foreignId('id_kriteria')
                ->constrained('kriteria', 'id_kriteria')->cascadeOnDelete();
            $table->string('nilai_kriteria', 10);
            $table->primary(['id_pekerja', 'id_kriteria']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria_pekerja');
        Schema::dropIfExists('kriteria');
    }
};
