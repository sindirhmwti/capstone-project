<?php

namespace App\Models;

use App\Support\WhatsApp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Jadwal sesi interview sebuah pesanan yang disetujui admin (tabel jadwal_interview),
 * termasuk waktu undangan WhatsApp dikirim dan status konfirmasi klien & calon pekerja.
 */
class JadwalInterview extends Model
{
    /** Jadwal diisi dan ditampilkan dalam jam WIB. */
    public const ZONA = 'Asia/Jakarta';

    public const PIHAK = [
        'klien' => 'Klien',
        'pekerja' => 'Calon Pekerja',
    ];

    public const KONFIRMASI = [
        'menunggu' => 'Menunggu',
        'hadir' => 'Hadir',
        'minta_ubah' => 'Minta Ubah Jadwal',
        'tidak_merespons' => 'Tidak Merespons',
    ];

    public const HASIL = [
        'belum' => 'Belum ada hasil',
        'cocok' => 'Cocok',
        'tidak_cocok' => 'Tidak Cocok',
    ];

    public const METODE = [
        'offline' => 'Offline',
        'online' => 'Online',
    ];

    /** Status "menunggu" lebih dari sekian jam sejak undangan dikirim → perlu dihubungi ulang. */
    public const BATAS_TANPA_RESPONS_JAM = 24;

    const CREATED_AT = 'tanggal_dibuat';
    const UPDATED_AT = null;

    protected $table = 'jadwal_interview';
    protected $primaryKey = 'id_interview';

    protected $fillable = [
        'id_pemesanan',
        'tanggal_interview',
        'waktu_interview',
        'metode',
        'lokasi_atau_link',
        'catatan',
        'undangan_klien_dikirim_pada',
        'undangan_pekerja_dikirim_pada',
        'konfirmasi_klien',
        'catatan_konfirmasi_klien',
        'dikonfirmasi_klien_pada',
        'konfirmasi_pekerja',
        'catatan_konfirmasi_pekerja',
        'dikonfirmasi_pekerja_pada',
        'jadwal_diubah_pada',
        'hasil_interview',
        'catatan_hasil',
        'hasil_dicatat_pada',
        'hasil_dicatat_oleh',
        'dibuat_oleh',
    ];

    protected $attributes = [
        'konfirmasi_klien' => 'menunggu',
        'konfirmasi_pekerja' => 'menunggu',
        'hasil_interview' => 'belum',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_interview' => 'date',
            'undangan_klien_dikirim_pada' => 'datetime',
            'undangan_pekerja_dikirim_pada' => 'datetime',
            'dikonfirmasi_klien_pada' => 'datetime',
            'dikonfirmasi_pekerja_pada' => 'datetime',
            'jadwal_diubah_pada' => 'datetime',
            'hasil_dicatat_pada' => 'datetime',
            'tanggal_dibuat' => 'datetime',
        ];
    }

    /* ---------- Relasi ---------- */

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh', 'id_pengguna');
    }

    /* ---------- Data per pihak (klien / pekerja) ---------- */

    /** Akun pihak terkait: klien pemesan atau calon pekerja. */
    public function penerima(string $pihak): Pengguna
    {
        return $pihak === 'klien'
            ? $this->pemesanan->klien
            : $this->pemesanan->profilPekerja->pengguna;
    }

    public function konfirmasi(string $pihak): string
    {
        return (string) $this->{'konfirmasi_' . $pihak};
    }

    public function labelKonfirmasi(string $pihak): string
    {
        return self::KONFIRMASI[$this->konfirmasi($pihak)] ?? $this->konfirmasi($pihak);
    }

    public function catatanKonfirmasi(string $pihak): ?string
    {
        return $this->{'catatan_konfirmasi_' . $pihak};
    }

    public function dikonfirmasiPada(string $pihak): ?Carbon
    {
        return $this->{'dikonfirmasi_' . $pihak . '_pada'};
    }

    public function undanganDikirimPada(string $pihak): ?Carbon
    {
        return $this->{'undangan_' . $pihak . '_dikirim_pada'};
    }

    /** Lebih dari 24 jam sejak undangan dikirim dan status masih "menunggu". */
    public function perluDihubungiUlang(string $pihak): bool
    {
        $dikirim = $this->undanganDikirimPada($pihak);

        return $this->konfirmasi($pihak) === 'menunggu'
            && $dikirim !== null
            && $dikirim->lte(now()->subHours(self::BATAS_TANPA_RESPONS_JAM));
    }

    /** Pihak yang perlu ditindaklanjuti admin (untuk indikator daftar pesanan). */
    public function perluTindakLanjut(string $pihak): bool
    {
        return $this->perluDihubungiUlang($pihak) || in_array($this->konfirmasi($pihak), ['minta_ubah', 'tidak_merespons'], true);
    }

    /** Indikator ringkas untuk daftar pesanan admin: [label, warna badge]. */
    public function indikator(): array
    {
        $pihak = collect(array_keys(self::PIHAK));
        $total = $pihak->count();

        if ($pihak->contains(fn ($p) => $this->perluTindakLanjut($p))) {
            return ['Perlu dihubungi', 'red'];
        }

        $hadir = $pihak->filter(fn ($p) => $this->konfirmasi($p) === 'hadir')->count();
        if ($hadir === $total) {
            return [$hadir . '/' . $total . ' hadir', 'green'];
        }

        $belumDiundang = $pihak->filter(fn ($p) => $this->konfirmasi($p) !== 'hadir' && ! $this->undanganDikirimPada($p))->count();
        if ($belumDiundang > 0) {
            return [$belumDiundang . ' belum diundang', 'blue'];
        }

        $menunggu = $pihak->filter(fn ($p) => $this->konfirmasi($p) === 'menunggu')->count();

        return [($hadir > 0 ? $hadir . '/' . $total . ' hadir · ' : '') . $menunggu . ' menunggu', 'amber'];
    }

    /* ---------- Tampilan jadwal ---------- */

    public function labelTanggal(): string
    {
        return $this->tanggal_interview->locale('id')->translatedFormat('l, d F Y');
    }

    /** Jam tanpa detik, mis. 09:30. */
    public function labelJam(): string
    {
        return substr((string) $this->waktu_interview, 0, 5);
    }

    public function labelWaktu(): string
    {
        return $this->labelJam() . ' WIB';
    }

    public function labelMetode(): string
    {
        return self::METODE[$this->metode] ?? $this->metode;
    }

    public function isOnline(): bool
    {
        return $this->metode === 'online';
    }

    public function labelLokasi(): string
    {
        return $this->isOnline() ? 'Link meeting' : 'Lokasi';
    }

    public function pernahDiubah(): bool
    {
        return $this->jadwal_diubah_pada !== null;
    }

    /** Format waktu sistem (UTC) ke tampilan WIB. */
    public static function tampilWib(?Carbon $waktu, string $format = 'd M Y, H:i'): string
    {
        return $waktu ? $waktu->copy()->timezone(self::ZONA)->locale('id')->translatedFormat($format) . ' WIB' : '-';
    }

    /* ---------- Pesan WhatsApp ---------- */

    /** Pesan undangan dari admin ke klien / calon pekerja (diawali "PERUBAHAN JADWAL INTERVIEW" bila jadwal pernah diubah). */
    public function pesanUndangan(string $pihak): string
    {
        $p = $this->pemesanan;
        $penerima = $this->penerima($pihak);
        $kategori = $p->profilPekerja->labelKategori();

        $baris = [];
        if ($this->pernahDiubah()) {
            $baris[] = 'PERUBAHAN JADWAL INTERVIEW';
            $baris[] = '';
        }

        if ($pihak === 'klien') {
            $baris[] = 'Halo Ibu/Bapak ' . $penerima->nama . ', kami dari PT Shaka Abadi Nusantara.';
            $baris[] = 'Pesanan #' . $p->kode() . ' untuk ' . $kategori . ' - ' . $p->profilPekerja->pengguna->nama . ' telah DISETUJUI.';
            $baris[] = $this->pernahDiubah() ? 'Berikut jadwal sesi interview yang BARU:' : 'Berikut jadwal sesi interview:';
        } else {
            $baris[] = 'Halo ' . $penerima->nama . ', kami dari PT Shaka Abadi Nusantara.';
            $baris[] = 'Anda dipilih oleh klien untuk posisi ' . $kategori . '.';
            $baris[] = $this->pernahDiubah() ? 'Berikut jadwal interview yang BARU:' : 'Berikut jadwal interview:';
        }

        $baris[] = 'Tanggal: ' . $this->labelTanggal();
        $baris[] = 'Waktu: ' . $this->labelWaktu();
        $baris[] = 'Metode: ' . $this->labelMetode();
        $baris[] = 'Lokasi/Link: ' . $this->lokasi_atau_link;
        $baris[] = 'Catatan: ' . ($this->catatan ?: '-');

        if ($pihak === 'klien') {
            $baris[] = 'Mohon balas pesan ini dengan HADIR jika bersedia, atau UBAH JADWAL jika berhalangan. Terima kasih.';
        } else {
            $baris[] = 'Mohon membawa KTP asli dan sertifikat (jika ada).';
            $baris[] = 'Balas pesan ini dengan HADIR jika bersedia, atau UBAH JADWAL jika berhalangan. Terima kasih.';
        }

        return implode("\n", $baris);
    }

    /** Link wa.me undangan ke nomor pihak tersebut, atau null bila nomor belum tersedia (akun lama). */
    public function linkUndangan(string $pihak): ?string
    {
        return WhatsApp::link($this->penerima($pihak)->no_telepon, $this->pesanUndangan($pihak));
    }

    /** Pesan dari klien / calon pekerja ke admin. $jenis: "hadir" atau "ubah". */
    public function pesanKeAdmin(string $pihak, string $jenis): string
    {
        $awal = 'Halo Admin Temukerja, saya ' . $this->penerima($pihak)->nama . ' (' . ($pihak === 'klien' ? 'klien' : 'calon pekerja')
            . ') untuk pesanan #' . $this->pemesanan->kode() . '.';

        return $jenis === 'hadir'
            ? $awal . ' Saya konfirmasi HADIR pada sesi interview ' . $this->labelTanggal() . ' pukul ' . $this->labelWaktu() . '.'
            : $awal . ' Saya ingin MENGUBAH JADWAL interview ' . $this->labelTanggal() . ' pukul ' . $this->labelWaktu() . ' karena ';
    }

    /** Link wa.me ke ADMIN_WHATSAPP; null bila ADMIN_WHATSAPP belum diisi. */
    public function linkKeAdmin(string $pihak, string $jenis): ?string
    {
        return WhatsApp::link(config('services.whatsapp.admin'), $this->pesanKeAdmin($pihak, $jenis));
    }

    /* ---------- Hasil interview ---------- */

    public function pencatatHasil(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'hasil_dicatat_oleh', 'id_pengguna');
    }

    public function hasilSudahDicatat(): bool
    {
        return in_array($this->hasil_interview, ['cocok', 'tidak_cocok'], true);
    }

    public function labelHasil(): string
    {
        return self::HASIL[$this->hasil_interview] ?? $this->hasil_interview;
    }

    /** Pesan WhatsApp "Kabari" setelah hasil interview dicatat. */
    public function pesanHasil(string $pihak): string
    {
        $p = $this->pemesanan;
        $penerima = $this->penerima($pihak);
        $kategori = $p->profilPekerja->labelKategori();
        $sapaan = $pihak === 'klien' ? 'Halo Ibu/Bapak ' . $penerima->nama : 'Halo ' . $penerima->nama;

        if ($this->hasil_interview === 'cocok') {
            $mulai = $p->penyaluran?->labelTanggalMulai() ?? '-';

            $baris = $pihak === 'klien'
                ? [
                    $sapaan . ', kami dari PT Shaka Abadi Nusantara.',
                    'Terima kasih telah mengikuti sesi interview. Hasilnya COCOK dan penyaluran pesanan #' . $p->kode() . ' berhasil.',
                    $p->profilPekerja->pengguna->nama . ' (' . $kategori . ') akan mulai bekerja pada ' . $mulai . '.',
                    'Semoga kerja sama berjalan lancar. Terima kasih.',
                ]
                : [
                    $sapaan . ', kami dari PT Shaka Abadi Nusantara.',
                    'Selamat! Hasil interview Anda COCOK dan Anda tersalurkan sebagai ' . $kategori . '.',
                    'Anda mulai bekerja pada ' . $mulai . '. Mohon hadir tepat waktu dan menjaga nama baik yayasan. Terima kasih.',
                ];

            return implode("\n", $baris);
        }

        $baris = $pihak === 'klien'
            ? [
                $sapaan . ', kami dari PT Shaka Abadi Nusantara.',
                'Terima kasih telah mengikuti sesi interview untuk pesanan #' . $p->kode() . '.',
                'Mohon maaf, hasilnya belum cocok sehingga pesanan ini kami tutup. Silakan memilih pekerja lain di katalog Temukerja, kami siap membantu. Terima kasih.',
            ]
            : [
                $sapaan . ', kami dari PT Shaka Abadi Nusantara.',
                'Terima kasih telah mengikuti interview untuk posisi ' . $kategori . '.',
                'Mohon maaf, hasilnya belum cocok dengan klien. Profil Anda tetap tampil di katalog sehingga peluang dengan klien lain tetap terbuka. Semangat!',
            ];

        return implode("\n", $baris);
    }

    /** Link wa.me pesan hasil, atau null bila nomor belum tersedia. */
    public function linkHasil(string $pihak): ?string
    {
        return WhatsApp::link($this->penerima($pihak)->no_telepon, $this->pesanHasil($pihak));
    }

    /* ---------- Operasi ---------- */

    /** Setelah jadwal diubah: konfirmasi kedua pihak kembali "menunggu" dan undangan perlu dikirim ulang. */
    public function resetKonfirmasi(): void
    {
        foreach (array_keys(self::PIHAK) as $pihak) {
            $this->{'konfirmasi_' . $pihak} = 'menunggu';
            $this->{'catatan_konfirmasi_' . $pihak} = null;
            $this->{'dikonfirmasi_' . $pihak . '_pada'} = null;
            $this->{'undangan_' . $pihak . '_dikirim_pada'} = null;
        }
    }
}
