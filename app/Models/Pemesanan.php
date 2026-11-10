<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pemesanan extends Model
{
    public const STATUS = [
        'menunggu' => 'Menunggu',
        'diproses' => 'Diproses',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'dibatalkan' => 'Dibatalkan',
        'selesai' => 'Selesai',
    ];

    // Transisi status yang diizinkan untuk admin
    public const TRANSISI_ADMIN = [
        'menunggu' => ['diproses', 'disetujui', 'ditolak'],
        'diproses' => ['disetujui', 'ditolak'],
        'disetujui' => ['selesai', 'dibatalkan'],
        'ditolak' => [],
        'dibatalkan' => [],
        'selesai' => [],
    ];

    // Status yang masih boleh dibatalkan oleh klien
    public const BISA_DIBATALKAN_KLIEN = ['menunggu', 'diproses'];

    // Status pemesanan yang dihitung sebagai penyaluran pada laporan
    public const STATUS_PENYALURAN = ['disetujui', 'selesai'];

    const CREATED_AT = 'tanggal_pemesanan';
    const UPDATED_AT = null;

    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_klien',
        'id_pekerja',
        'status_pemesanan',
        'tanggal_mulai',
        'lokasi_kerja',
        'catatan_klien',
        'alasan_pembatalan',
        'tanggal_pembatalan',
        'dibatalkan_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pemesanan' => 'datetime',
            'tanggal_mulai' => 'date',
            'tanggal_pembatalan' => 'datetime',
        ];
    }

    /* ---------- Relasi ---------- */

    public function klien(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_klien', 'id_pengguna');
    }

    public function profilPekerja(): BelongsTo
    {
        return $this->belongsTo(ProfilPekerja::class, 'id_pekerja', 'id_pekerja');
    }

    public function pembatal(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibatalkan_oleh', 'id_pengguna');
    }

    public function kriteriaPemesanan(): HasMany
    {
        return $this->hasMany(KriteriaPemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function kriteria(): BelongsToMany
    {
        return $this->belongsToMany(Kriteria::class, 'kriteria_pemesanan', 'id_pemesanan', 'id_kriteria')
            ->withPivot('nilai_yang_diminta');
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatusPemesanan::class, 'id_pemesanan', 'id_pemesanan')
            ->orderBy('tanggal_perubahan')->orderBy('id_riwayat');
    }

    /** Jadwal sesi interview (tabel jadwal_interview) yang dibuat saat pesanan disetujui. */
    public function jadwalInterview(): HasOne
    {
        return $this->hasOne(JadwalInterview::class, 'id_pemesanan', 'id_pemesanan');
    }

    /** Data penyaluran (tabel penyaluran) bila hasil interview pesanan ini cocok. */
    public function penyaluran(): HasOne
    {
        return $this->hasOne(Penyaluran::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Pemesanan yang dihitung sebagai PENYALURAN pada laporan: berstatus "selesai"
     * (pekerja tersalurkan). Tanggal penyaluran = tanggal mulai kerja pada data penyaluran;
     * untuk data lama tanpa data penyaluran dipakai jadwal mulai / tanggal pemesanan.
     *
     * @return \Illuminate\Support\Collection<int, Pemesanan>
     */
    public static function daftarPenyaluran(?\Carbon\Carbon $awalBulan = null, ?string $kategori = null)
    {
        return static::with(['klien', 'profilPekerja.pengguna', 'penyaluran'])
            ->where('status_pemesanan', 'selesai')
            ->when($kategori, fn ($q) => $q->whereHas('profilPekerja', fn ($p) => $p->where('kategori_pekerjaan', $kategori)))
            ->get()
            ->filter(fn (Pemesanan $o) => ! $awalBulan || $o->tanggalPenyaluran()->isSameMonth($awalBulan))
            ->sortBy(fn (Pemesanan $o) => $o->tanggalPenyaluran()->timestamp)
            ->values();
    }

    /** Tanggal pekerja mulai tersalurkan untuk pesanan ini. */
    public function tanggalPenyaluran(): \Carbon\CarbonInterface
    {
        return $this->penyaluran?->tanggal_mulai ?? $this->tanggal_mulai ?? $this->tanggal_pemesanan;
    }

    /* ---------- Operasi ---------- */

    /**
     * Mengubah status pesanan, mencatat riwayat status, dan menyimpan perubahan.
     * Pengiriman notifikasi dilakukan oleh controller agar isi pesan sesuai konteks.
     */
    public function ubahStatus(string $statusBaru, ?int $idPengguna, ?string $catatan = null): void
    {
        $statusLama = $this->status_pemesanan;
        $this->status_pemesanan = $statusBaru;

        if ($statusBaru === 'dibatalkan') {
            $this->alasan_pembatalan = $catatan;
            $this->tanggal_pembatalan = now();
            $this->dibatalkan_oleh = $idPengguna;
        }

        $this->save();

        $this->riwayatStatus()->create([
            'diubah_oleh' => $idPengguna,
            'status_sebelumnya' => $statusLama,
            'status_baru' => $statusBaru,
            'catatan_perubahan' => $catatan,
        ]);
    }

    public function bisaDibatalkanKlien(): bool
    {
        return in_array($this->status_pemesanan, self::BISA_DIBATALKAN_KLIEN, true);
    }

    public function statusBerikutnyaAdmin(): array
    {
        return self::TRANSISI_ADMIN[$this->status_pemesanan] ?? [];
    }

    public function kode(): string
    {
        return 'PS-' . str_pad((string) $this->id_pemesanan, 5, '0', STR_PAD_LEFT);
    }

    public function labelStatus(): string
    {
        return self::STATUS[$this->status_pemesanan] ?? $this->status_pemesanan;
    }

    /** Kelas warna badge status pesanan. */
    public static function warna(string $status): string
    {
        return match ($status) {
            'menunggu' => 'bg-amber-100 text-amber-700',
            'diproses' => 'bg-blue-100 text-blue-700',
            'disetujui' => 'bg-green-100 text-green-700',
            'selesai' => 'bg-[#E4F2E8] text-[#104C38]',
            'ditolak' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    public function warnaStatus(): string
    {
        return self::warna($this->status_pemesanan);
    }
}
