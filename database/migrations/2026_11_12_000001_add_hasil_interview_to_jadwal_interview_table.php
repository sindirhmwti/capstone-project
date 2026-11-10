<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hasil interview (cocok / tidak cocok) pada tabel jadwal_interview (tabel milik fitur interview).
     */
    public function up(): void
    {
        Schema::table('jadwal_interview', function (Blueprint $table) {
            $table->enum('hasil_interview', ['belum', 'cocok', 'tidak_cocok'])->default('belum')->after('jadwal_diubah_pada');
            $table->text('catatan_hasil')->nullable()->after('hasil_interview');
            $table->dateTime('hasil_dicatat_pada')->nullable()->after('catatan_hasil');
            $table->foreignId('hasil_dicatat_oleh')->nullable()->after('hasil_dicatat_pada')
                ->constrained('pengguna', 'id_pengguna')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_interview', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hasil_dicatat_oleh');
            $table->dropColumn(['hasil_interview', 'catatan_hasil', 'hasil_dicatat_pada']);
        });
    }
};
