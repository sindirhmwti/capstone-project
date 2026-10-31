<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    public const KATEGORI = [
        'lingkungan' => 'Lingkungan Rumah',
        'kondisi_fisik' => 'Kondisi Fisik',
        'keahlian' => 'Keahlian',
        'ketersediaan' => 'Ketersediaan',
    ];

    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $timestamps = false;

    protected $fillable = [
        'nama_kriteria',
        'kategori_kriteria',
        'deskripsi',
    ];

    public function kriteriaPekerja(): HasMany
    {
        return $this->hasMany(KriteriaPekerja::class, 'id_kriteria', 'id_kriteria');
    }

    public function kriteriaPemesanan(): HasMany
    {
        return $this->hasMany(KriteriaPemesanan::class, 'id_kriteria', 'id_kriteria');
    }

    /** Mengambil seluruh kriteria dengan urutan tetap (dipakai untuk membentuk vektor). */
    public static function ambilSemua(): Collection
    {
        return static::orderBy('id_kriteria')->get();
    }

    public function labelKategori(): string
    {
        return self::KATEGORI[$this->kategori_kriteria] ?? $this->kategori_kriteria;
    }
}
