<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfilPekerja extends Model
{
    public const KATEGORI = [
        'art' => 'ART (Asisten Rumah Tangga)',
        'baby_sitter' => 'Baby Sitter',
        'perawat_lansia' => 'Perawat Lansia',
    ];

    public const KATEGORI_SINGKAT = [
        'art' => 'ART',
        'baby_sitter' => 'Baby Sitter',
        'perawat_lansia' => 'Perawat Lansia',
    ];

    public const STATUS_VERIFIKASI = [
        'belum_lengkap' => 'Belum Lengkap',
        'menunggu' => 'Menunggu Verifikasi',
        'terverifikasi' => 'Terverifikasi',
        'ditolak' => 'Ditolak',
    ];

    // Kategori yang wajib melampirkan sertifikat pelatihan
    public const WAJIB_SERTIFIKAT = ['baby_sitter', 'perawat_lansia'];

    protected $table = 'profil_pekerja';
    protected $primaryKey = 'id_pekerja';
    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'kategori_pekerjaan',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'lama_pengalaman',
        'keterampilan',
        'deskripsi_profil',
        'status_verifikasi',
        'catatan_verifikasi',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_verifikasi' => 'datetime',
            'lama_pengalaman' => 'integer',
        ];
    }

    /* ---------- Relasi ---------- */

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diverifikasi_oleh', 'id_pengguna');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenPekerja::class, 'id_pekerja', 'id_pekerja');
    }

    public function kriteriaPekerja(): HasMany
    {
        return $this->hasMany(KriteriaPekerja::class, 'id_pekerja', 'id_pekerja');
    }

    public function kriteria(): BelongsToMany
    {
        return $this->belongsToMany(Kriteria::class, 'kriteria_pekerja', 'id_pekerja', 'id_kriteria')
            ->withPivot('nilai_kriteria');
    }

    public function favorit(): HasMany
    {
        return $this->hasMany(Favorit::class, 'id_pekerja', 'id_pekerja');
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'id_pekerja', 'id_pekerja');
    }

    /* ---------- Scope ---------- */

    /** Pekerja yang boleh tampil di katalog klien: terverifikasi dan akun aktif. */
    public function scopeTampilDiKatalog(Builder $query): Builder
    {
        return $query->where('status_verifikasi', 'terverifikasi')
            ->whereHas('pengguna', fn ($q) => $q->where('status_akun', 'aktif'));
    }

    /* ---------- Operasi (sesuai Class Diagram) ---------- */

    public function hitungUsia(): ?int
    {
        return $this->tanggal_lahir?->age;
    }

    public function wajibSertifikat(): bool
    {
        return in_array($this->kategori_pekerjaan, self::WAJIB_SERTIFIKAT, true);
    }

    /** Daftar jenis dokumen yang wajib diunggah sesuai kategori. */
    public function dokumenWajib(): array
    {
        return $this->wajibSertifikat() ? ['ktp', 'sertifikat_pelatihan'] : ['ktp'];
    }

    /** Dokumen terbaru per jenis. */
    public function dokumenTerbaru(string $jenis): ?DokumenPekerja
    {
        return $this->dokumen
            ->where('jenis_dokumen', $jenis)
            ->sortByDesc('id_dokumen')
            ->first();
    }

    /** Mengecek apakah seluruh dokumen wajib sudah diunggah. */
    public function cekKelengkapanDokumen(): bool
    {
        foreach ($this->dokumenWajib() as $jenis) {
            if (! $this->dokumenTerbaru($jenis)) {
                return false;
            }
        }

        return true;
    }

    /** Mengecek kelengkapan data profil utama. */
    public function cekKelengkapanProfil(): bool
    {
        return filled($this->alamat) && filled($this->tanggal_lahir)
            && filled($this->jenis_kelamin) && filled($this->keterampilan);
    }

    public function cekKriteriaTerisi(): bool
    {
        return $this->kriteriaPekerja()->exists();
    }

    /** Mengambil nilai kriteria pekerja dalam bentuk [id_kriteria => 'ya'|'tidak']. */
    public function ambilKriteria(): array
    {
        return $this->kriteria->pluck('pivot.nilai_kriteria', 'id_kriteria')->all();
    }

    public function ubahStatusVerifikasi(string $status, ?int $idAdmin = null, ?string $catatan = null): void
    {
        $this->update([
            'status_verifikasi' => $status,
            'catatan_verifikasi' => $catatan,
            'diverifikasi_oleh' => $idAdmin,
            'tanggal_verifikasi' => $idAdmin ? now() : null,
        ]);
    }

    /* ---------- Label tampilan ---------- */

    public function labelKategori(): string
    {
        return self::KATEGORI_SINGKAT[$this->kategori_pekerjaan] ?? '-';
    }

    public function labelStatus(): string
    {
        return self::STATUS_VERIFIKASI[$this->status_verifikasi] ?? '-';
    }

    /** Kelas warna badge status verifikasi. */
    public function warnaStatus(): string
    {
        return match ($this->status_verifikasi) {
            'terverifikasi' => 'bg-green-100 text-green-700',
            'menunggu' => 'bg-amber-100 text-amber-700',
            'ditolak' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    /** Pekerja boleh mengubah dokumen dan mengajukan verifikasi hanya saat belum lengkap / ditolak. */
    public function bisaDiajukan(): bool
    {
        return in_array($this->status_verifikasi, ['belum_lengkap', 'ditolak'], true);
    }

    /** Daftar keterampilan dalam bentuk array (dipisah koma). */
    public function daftarKeterampilan(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->keterampilan))));
    }
}
