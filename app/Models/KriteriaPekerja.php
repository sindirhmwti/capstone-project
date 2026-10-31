<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabel penghubung dengan primary key gabungan (id_pekerja, id_kriteria).
 * Penyimpanan data dilakukan melalui relasi ProfilPekerja::kriteria()->sync().
 */
class KriteriaPekerja extends Model
{
    protected $table = 'kriteria_pekerja';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_pekerja',
        'id_kriteria',
        'nilai_kriteria',
    ];

    public function profilPekerja(): BelongsTo
    {
        return $this->belongsTo(ProfilPekerja::class, 'id_pekerja', 'id_pekerja');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
