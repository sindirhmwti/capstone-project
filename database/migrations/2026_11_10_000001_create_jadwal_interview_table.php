<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel BARU "jadwal_interview": jadwal sesi interview yang dibuat admin saat menyetujui pesanan,
     * waktu undangan WhatsApp dikirim, dan status konfirmasi klien & calon pekerja (dicatat admin).
     * Tabel lama (pemesanan, riwayat_status_pemesanan, dst.) tidak diubah.
     */
    public function up(): void
    {
        Schema::create('jadwal_interview', function (Blueprint $table) {
            $table->id('id_interview');
            $table->foreignId('id_pemesanan')->unique()
                ->constrained('pemesanan', 'id_pemesanan')->cascadeOnDelete();

            // Jadwal (jam WIB / Asia/Jakarta, sesuai yang diisi admin)
            $table->date('tanggal_interview');
            $table->time('waktu_interview');
            $table->enum('metode', ['offline', 'online']);
            $table->string('lokasi_atau_link');
            $table->text('catatan')->nullable();

            // Waktu undangan WhatsApp dikirim admin
            $table->dateTime('undangan_klien_dikirim_pada')->nullable();
            $table->dateTime('undangan_pekerja_dikirim_pada')->nullable();

            // Konfirmasi klien
            $table->enum('konfirmasi_klien', ['menunggu', 'hadir', 'minta_ubah', 'tidak_merespons'])->default('menunggu');
            $table->text('catatan_konfirmasi_klien')->nullable();
            $table->dateTime('dikonfirmasi_klien_pada')->nullable();

            // Konfirmasi calon pekerja
            $table->enum('konfirmasi_pekerja', ['menunggu', 'hadir', 'minta_ubah', 'tidak_merespons'])->default('menunggu');
            $table->text('catatan_konfirmasi_pekerja')->nullable();
            $table->dateTime('dikonfirmasi_pekerja_pada')->nullable();

            // Terisi bila admin pernah mengubah jadwal (pesan berikutnya diawali "PERUBAHAN JADWAL INTERVIEW")
            $table->dateTime('jadwal_diubah_pada')->nullable();

            $table->foreignId('dibuat_oleh')->nullable()
                ->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->timestamp('tanggal_dibuat')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_interview');
    }
};
