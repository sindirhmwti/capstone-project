<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel PROFIL_PEKERJA (sesuai ERD) + kolom tambahan kategori_pekerjaan, keterampilan, catatan_verifikasi
        Schema::create('profil_pekerja', function (Blueprint $table) {
            $table->id('id_pekerja');
            // Profil ikut terhapus bila akun pengguna dihapus
            $table->foreignId('id_pengguna')->unique()
                ->constrained('pengguna', 'id_pengguna')->cascadeOnDelete();
            $table->enum('kategori_pekerjaan', ['art', 'baby_sitter', 'perawat_lansia']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('alamat')->nullable();
            $table->unsignedInteger('lama_pengalaman')->default(0); // dalam tahun
            $table->string('keterampilan')->nullable();
            $table->text('deskripsi_profil')->nullable();
            $table->enum('status_verifikasi', ['belum_lengkap', 'menunggu', 'terverifikasi', 'ditolak'])
                ->default('belum_lengkap');
            $table->text('catatan_verifikasi')->nullable();
            // Admin yang memverifikasi; bila akun admin dihapus, data verifikasi tetap ada
            $table->foreignId('diverifikasi_oleh')->nullable()
                ->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->dateTime('tanggal_verifikasi')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_pekerja');
    }
};
