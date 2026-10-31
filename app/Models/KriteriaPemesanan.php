<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tabel penghubung dengan primary key gabungan (id_pemesanan, id_kriteria).
 * Penyimpanan data dilakukan melalui relasi Pemesanan::kriteria()->sync().
 */
class KriteriaPemesanan extends Model
{
    protected $table = 'kriteria_pemesanan';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_pemesanan',
        'id_kriteria',
        'nilai_yang_diminta',
    ];

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
