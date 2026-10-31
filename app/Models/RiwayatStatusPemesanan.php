<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStatusPemesanan extends Model
{
    const CREATED_AT = 'tanggal_perubahan';
    const UPDATED_AT = null;

    protected $table = 'riwayat_status_pemesanan';
    protected $primaryKey = 'id_riwayat';

    protected $fillable = [
        'id_pemesanan',
        'diubah_oleh',
        'status_sebelumnya',
        'status_baru',
        'catatan_perubahan',
        'tanggal_perubahan',
    ];

    protected function casts(): array
    {
        return ['tanggal_perubahan' => 'datetime'];
    }

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function pengubah(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diubah_oleh', 'id_pengguna');
    }
}
