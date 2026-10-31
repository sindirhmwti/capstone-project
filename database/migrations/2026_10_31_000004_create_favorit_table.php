<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel FAVORIT (sesuai ERD)
        Schema::create('favorit', function (Blueprint $table) {
            $table->id('id_favorit');
            $table->foreignId('id_klien')
                ->constrained('pengguna', 'id_pengguna')->cascadeOnDelete();
            $table->foreignId('id_pekerja')
                ->constrained('profil_pekerja', 'id_pekerja')->cascadeOnDelete();
            $table->timestamp('tanggal_ditambahkan')->useCurrent();
            $table->unique(['id_klien', 'id_pekerja']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorit');
    }
};
