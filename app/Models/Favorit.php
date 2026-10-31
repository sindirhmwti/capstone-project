<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorit extends Model
{
    const CREATED_AT = 'tanggal_ditambahkan';
    const UPDATED_AT = null;

    protected $table = 'favorit';
    protected $primaryKey = 'id_favorit';

    protected $fillable = [
        'id_klien',
        'id_pekerja',
    ];

    protected function casts(): array
    {
        return ['tanggal_ditambahkan' => 'datetime'];
    }

    public function klien(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_klien', 'id_pengguna');
    }

    public function profilPekerja(): BelongsTo
    {
        return $this->belongsTo(ProfilPekerja::class, 'id_pekerja', 'id_pekerja');
    }
}
