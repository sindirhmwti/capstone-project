<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    const CREATED_AT = 'tanggal_dibuat';
    const UPDATED_AT = null;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'id_pengguna',
        'judul',
        'isi_notifikasi',
        'sudah_dibaca',
    ];

    protected function casts(): array
    {
        return [
            'sudah_dibaca' => 'boolean',
            'tanggal_dibuat' => 'datetime',
        ];
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    /** Mengirim notifikasi ke satu pengguna. */
    public static function kirim(int $idPengguna, string $judul, string $isi): self
    {
        return static::create([
            'id_pengguna' => $idPengguna,
            'judul' => $judul,
            'isi_notifikasi' => $isi,
        ]);
    }

    /** Mengirim notifikasi ke seluruh admin yang aktif. */
    public static function kirimKeAdmin(string $judul, string $isi): void
    {
        Pengguna::where('peran', Pengguna::PERAN_ADMIN)
            ->where('status_akun', 'aktif')
            ->pluck('id_pengguna')
            ->each(fn ($id) => static::kirim($id, $judul, $isi));
    }

    public function tandaiDibaca(): void
    {
        $this->update(['sudah_dibaca' => true]);
    }
}
