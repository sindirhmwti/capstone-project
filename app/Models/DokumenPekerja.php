<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DokumenPekerja extends Model
{
    public const JENIS = [
        'ktp' => 'KTP',
        'sertifikat_pelatihan' => 'Sertifikat Pelatihan',
    ];

    public const STATUS = [
        'menunggu' => 'Menunggu Pemeriksaan',
        'valid' => 'Valid',
        'tidak_valid' => 'Tidak Valid',
    ];

    const CREATED_AT = 'tanggal_unggah';
    const UPDATED_AT = null;

    protected $table = 'dokumen_pekerja';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'id_pekerja',
        'jenis_dokumen',
        'lokasi_berkas',
        'status_verifikasi',
    ];

    protected function casts(): array
    {
        return ['tanggal_unggah' => 'datetime'];
    }

    public function profilPekerja(): BelongsTo
    {
        return $this->belongsTo(ProfilPekerja::class, 'id_pekerja', 'id_pekerja');
    }

    // Dokumen disimpan di disk privat (storage/app/private) dan hanya dapat dibuka lewat route berotorisasi
    public const DISK = 'local';

    public function url(): string
    {
        return route('dokumen.show', $this->id_dokumen);
    }

    public function hapusBerkas(): void
    {
        Storage::disk(self::DISK)->delete($this->lokasi_berkas);
    }

    public function warnaStatus(): string
    {
        return match ($this->status_verifikasi) {
            'valid' => 'bg-green-100 text-green-700',
            'tidak_valid' => 'bg-red-100 text-red-700',
            default => 'bg-amber-100 text-amber-700',
        };
    }

    public function isGambar(): bool
    {
        return in_array(strtolower(pathinfo($this->lokasi_berkas, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'], true);
    }

    public function labelJenis(): string
    {
        return self::JENIS[$this->jenis_dokumen] ?? $this->jenis_dokumen;
    }

    public function labelStatus(): string
    {
        return self::STATUS[$this->status_verifikasi] ?? $this->status_verifikasi;
    }

    public function ubahStatusVerifikasi(string $status): void
    {
        $this->update(['status_verifikasi' => $status]);
    }
}
