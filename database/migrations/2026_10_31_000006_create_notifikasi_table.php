<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel NOTIFIKASI (sesuai ERD)
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('id_pengguna')
                ->constrained('pengguna', 'id_pengguna')->cascadeOnDelete();
            $table->string('judul', 150);
            $table->text('isi_notifikasi');
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamp('tanggal_dibuat')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
