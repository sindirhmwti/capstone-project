<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Penyaluran pekerja ke klien (tabel penyaluran), dibuat saat hasil interview "cocok".
 * Selama berstatus "aktif", pekerja tidak tampil di katalog klien.
 */
class Penyaluran extends Model
{
    public const STATUS = [
        'aktif' => 'Aktif',
        'berakhir' => 'Berakhir',
    ];

    const CREATED_AT = 'tanggal_dibuat';
    const UPDATED_AT = null;

    protected $table = 'penyaluran';
    protected $primaryKey = 'id_penyaluran';

    protected $fillable = [
        'id_pemesanan',
        'id_pekerja',
        'id_klien',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
        'catatan',
        'dibuat_oleh',
    ];

    protected $attributes = [
        'status' => 'aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_berakhir' => 'date',
            'tanggal_dibuat' => 'datetime',
        ];
    }

    /* ---------- Relasi ---------- */

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function profilPekerja(): BelongsTo
    {
        return $this->belongsTo(ProfilPekerja::class, 'id_pekerja', 'id_pekerja');
    }

    public function klien(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_klien', 'id_pengguna');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh', 'id_pengguna');
    }

    /* ---------- Scope & bantuan ---------- */

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function labelStatus(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function labelTanggalMulai(): string
    {
        return $this->tanggal_mulai->locale('id')->translatedFormat('d F Y');
    }
}
