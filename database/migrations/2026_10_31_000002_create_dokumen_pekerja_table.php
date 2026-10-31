<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel DOKUMEN_PEKERJA (sesuai ERD)
        Schema::create('dokumen_pekerja', function (Blueprint $table) {
            $table->id('id_dokumen');
            // Dokumen ikut terhapus bersama profil pekerja
            $table->foreignId('id_pekerja')
                ->constrained('profil_pekerja', 'id_pekerja')->cascadeOnDelete();
            $table->enum('jenis_dokumen', ['ktp', 'sertifikat_pelatihan']);
            $table->string('lokasi_berkas');
            $table->enum('status_verifikasi', ['menunggu', 'valid', 'tidak_valid'])->default('menunggu');
            $table->timestamp('tanggal_unggah')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_pekerja');
    }
};
