<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel BARU "penyaluran": pekerja yang tersalurkan ke klien setelah hasil interview cocok.
     * Selama status "aktif", pekerja tidak tampil di katalog klien.
     */
    public function up(): void
    {
        Schema::create('penyaluran', function (Blueprint $table) {
            $table->id('id_penyaluran');
            $table->foreignId('id_pemesanan')->unique()
                ->constrained('pemesanan', 'id_pemesanan')->restrictOnDelete();
            $table->foreignId('id_pekerja')
                ->constrained('profil_pekerja', 'id_pekerja')->restrictOnDelete();
            $table->foreignId('id_klien')
                ->constrained('pengguna', 'id_pengguna')->restrictOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir')->nullable();
            $table->enum('status', ['aktif', 'berakhir'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()
                ->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->timestamp('tanggal_dibuat')->useCurrent();

            $table->index(['id_pekerja', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyaluran');
    }
};
