<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model Pengguna (generalisasi Admin, Klien, dan CalonPekerja pada Class Diagram).
 * Di basis data cukup satu tabel "pengguna" yang dibedakan melalui kolom "peran".
 */
class Pengguna extends Authenticatable
{
    use Notifiable;

    public const PERAN_ADMIN = 'admin';
    public const PERAN_KLIEN = 'klien';
    public const PERAN_PEKERJA = 'pekerja';

    const CREATED_AT = 'tanggal_dibuat';
    const UPDATED_AT = null;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    // Kolom kata sandi yang dipakai oleh sistem autentikasi Laravel
    protected $authPasswordName = 'kata_sandi';

    protected $fillable = [
        'nama',
        'email',
        'no_telepon',
        'kata_sandi',
        'peran',
        'status_akun',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'kata_sandi' => 'hashed',
            'tanggal_dibuat' => 'datetime',
        ];
    }

    /* ---------- Helper peran ---------- */

    public function isAdmin(): bool
    {
        return $this->peran === self::PERAN_ADMIN;
    }

    public function isKlien(): bool
    {
        return $this->peran === self::PERAN_KLIEN;
    }

    public function isPekerja(): bool
    {
        return $this->peran === self::PERAN_PEKERJA;
    }

    public function isAktif(): bool
    {
        return $this->status_akun === 'aktif';
    }

    /** Route dashboard sesuai peran pengguna. */
    public function routeDashboard(): string
    {
        return match ($this->peran) {
            self::PERAN_ADMIN => 'admin.dashboard',
            self::PERAN_KLIEN => 'client.dashboard',
            default => 'worker.dashboard',
        };
    }

    /** Inisial nama untuk avatar. */
    public function inisial(): string
    {
        $kata = preg_split('/\s+/', trim($this->nama));
        $inisial = mb_substr($kata[0] ?? '', 0, 1) . mb_substr($kata[1] ?? '', 0, 1);

        return mb_strtoupper($inisial ?: 'U');
    }

    /* ---------- Relasi ---------- */

    public function profilPekerja(): HasOne
    {
        return $this->hasOne(ProfilPekerja::class, 'id_pengguna', 'id_pengguna');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_pengguna', 'id_pengguna');
    }

    public function favorit(): HasMany
    {
        return $this->hasMany(Favorit::class, 'id_klien', 'id_pengguna');
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'id_klien', 'id_pengguna');
    }

    /** Jumlah notifikasi yang belum dibaca. */
    public function jumlahNotifikasiBelumDibaca(): int
    {
        return $this->notifikasi()->where('sudah_dibaca', false)->count();
    }
}
