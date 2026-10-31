<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel PEMESANAN (sesuai ERD) + kolom tambahan tanggal_mulai dan lokasi_kerja (dibutuhkan mockup)
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            // restrict: klien / pekerja yang memiliki pesanan tidak bisa dihapus agar data pesanan aman
            $table->foreignId('id_klien')
                ->constrained('pengguna', 'id_pengguna')->restrictOnDelete();
            $table->foreignId('id_pekerja')
                ->constrained('profil_pekerja', 'id_pekerja')->restrictOnDelete();
            $table->timestamp('tanggal_pemesanan')->useCurrent();
            $table->enum('status_pemesanan', ['menunggu', 'diproses', 'disetujui', 'ditolak', 'dibatalkan', 'selesai'])
                ->default('menunggu');
            $table->date('tanggal_mulai')->nullable();
            $table->string('lokasi_kerja')->nullable();
            $table->text('catatan_klien')->nullable();
            $table->text('alasan_pembatalan')->nullable();
            $table->dateTime('tanggal_pembatalan')->nullable();
            $table->foreignId('dibatalkan_oleh')->nullable()
                ->constrained('pengguna', 'id_pengguna')->nullOnDelete();
        });

        // Tabel KRITERIA_PEMESANAN (kriteria yang diminta klien saat memesan)
        Schema::create('kriteria_pemesanan', function (Blueprint $table) {
            $table->foreignId('id_pemesanan')
                ->constrained('pemesanan', 'id_pemesanan')->cascadeOnDelete();
            $table->foreignId('id_kriteria')
                ->constrained('kriteria', 'id_kriteria')->restrictOnDelete();
            $table->string('nilai_yang_diminta', 10);
            $table->primary(['id_pemesanan', 'id_kriteria']);
        });

        // Tabel RIWAYAT_STATUS_PEMESANAN (jejak setiap perubahan status)
        Schema::create('riwayat_status_pemesanan', function (Blueprint $table) {
            $table->id('id_riwayat');
            // restrict: pesanan yang sudah memiliki riwayat tidak bisa dihapus
            $table->foreignId('id_pemesanan')
                ->constrained('pemesanan', 'id_pemesanan')->restrictOnDelete();
            $table->foreignId('diubah_oleh')->nullable()
                ->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->string('status_sebelumnya', 20)->nullable();
            $table->string('status_baru', 20);
            $table->text('catatan_perubahan')->nullable();
            $table->timestamp('tanggal_perubahan')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_pemesanan');
        Schema::dropIfExists('kriteria_pemesanan');
        Schema::dropIfExists('pemesanan');
    }
};
