<?php

namespace Tests\Feature;

use App\Models\JadwalInterview;
use App\Models\Pemesanan;
use App\Models\Pengguna;
use App\Models\Penyaluran;
use App\Models\ProfilPekerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji hasil interview, penyaluran pekerja, dan ketersediaan pekerja di katalog.
 * Memakai SQLite in-memory; dilewati bila ekstensi pdo_sqlite tidak aktif.
 * Jalankan: php -d extension=pdo_sqlite vendor/bin/phpunit --filter=PenyaluranTest
 */
class PenyaluranTest extends TestCase
{
    use RefreshDatabase;

    private Pengguna $admin;
    private Pengguna $klien;
    private Pengguna $klienLain;
    private Pengguna $pekerja;
    private ProfilPekerja $profil;
    private Pemesanan $pesanan;
    private Pemesanan $pesananLain;
    private JadwalInterview $jadwal;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite tidak aktif. Jalankan: php -d extension=pdo_sqlite vendor/bin/phpunit');
        }

        parent::setUp();

        $this->admin = $this->buatPengguna('admin', 'Admin Shaka', 'admin@uji.test', '081200000001');
        $this->klien = $this->buatPengguna('klien', 'Rani Amelia', 'klien@uji.test', '081200000002');
        $this->klienLain = $this->buatPengguna('klien', 'Hendra Wijaya', 'klien2@uji.test', '081200000004');
        $this->pekerja = $this->buatPengguna('pekerja', 'Budi Santoso', 'art@uji.test', '081200000003');
        $this->profil = ProfilPekerja::create([
            'id_pengguna' => $this->pekerja->id_pengguna, 'kategori_pekerjaan' => 'art', 'status_verifikasi' => 'terverifikasi',
            'alamat' => 'Jakarta Selatan', 'tanggal_lahir' => '1990-01-01', 'jenis_kelamin' => 'Laki-laki', 'keterampilan' => 'Memasak', 'lama_pengalaman' => 4,
        ]);

        $this->pesanan = $this->buatPesanan($this->klien, 'disetujui');
        $this->pesananLain = $this->buatPesanan($this->klienLain, 'menunggu');
        $this->jadwal = $this->pesanan->jadwalInterview()->create([
            'tanggal_interview' => now(JadwalInterview::ZONA)->toDateString(), 'waktu_interview' => '09:00:00',
            'metode' => 'offline', 'lokasi_atau_link' => 'Kantor', 'dibuat_oleh' => $this->admin->id_pengguna,
        ]);
    }

    private function buatPengguna(string $peran, string $nama, string $email, ?string $telepon): Pengguna
    {
        return Pengguna::create([
            'nama' => $nama, 'email' => $email, 'no_telepon' => $telepon,
            'kata_sandi' => 'Rahasia123', 'peran' => $peran, 'status_akun' => 'aktif',
        ]);
    }

    private function buatPesanan(Pengguna $klien, string $status): Pemesanan
    {
        return Pemesanan::create([
            'id_klien' => $klien->id_pengguna, 'id_pekerja' => $this->profil->id_pekerja, 'status_pemesanan' => $status,
            'tanggal_mulai' => now()->addDays(7)->toDateString(), 'lokasi_kerja' => 'Jakarta Selatan', 'catatan_klien' => 'Butuh ART yang bisa memasak.',
        ]);
    }

    private function cocok(?string $tanggal = null)
    {
        return $this->actingAs($this->admin)->post(route('admin.orders.interview.match', $this->pesanan), [
            'tanggal_mulai_kerja' => $tanggal ?? now(JadwalInterview::ZONA)->addDays(3)->toDateString(),
            'catatan_cocok' => 'Kontrak 1 tahun.',
        ]);
    }

    /* ---------- 1. Cocok ---------- */

    public function test_cocok_menyalurkan_pekerja_dan_membatalkan_pesanan_lain(): void
    {
        $this->cocok()->assertSessionHasNoErrors()->assertSessionHas('success')
            ->assertRedirect(route('admin.orders.show', $this->pesanan) . '#hasil-interview');

        $this->assertSame('selesai', $this->pesanan->fresh()->status_pemesanan);
        $this->assertTrue($this->pesanan->riwayatStatus()->get()->contains(fn ($r) => $r->status_baru === 'selesai' && str_starts_with((string) $r->catatan_perubahan, 'Hasil interview cocok, pekerja tersalurkan')));

        $jadwal = $this->jadwal->fresh();
        $this->assertSame('cocok', $jadwal->hasil_interview);
        $this->assertSame('Kontrak 1 tahun.', $jadwal->catatan_hasil);
        $this->assertNotNull($jadwal->hasil_dicatat_pada);
        $this->assertSame($this->admin->id_pengguna, $jadwal->hasil_dicatat_oleh);

        $penyaluran = Penyaluran::firstOrFail();
        $this->assertSame('aktif', $penyaluran->status);
        $this->assertSame($this->profil->id_pekerja, $penyaluran->id_pekerja);
        $this->assertSame($this->klien->id_pengguna, $penyaluran->id_klien);

        // Pesanan lain untuk pekerja yang sama dibatalkan otomatis
        $lain = $this->pesananLain->fresh();
        $this->assertSame('dibatalkan', $lain->status_pemesanan);
        $this->assertSame('Pekerja telah tersalurkan ke klien lain', $lain->alasan_pembatalan);
        $this->assertSame($this->admin->id_pengguna, $lain->dibatalkan_oleh);
        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->klienLain->id_pengguna, 'judul' => 'Pesanan dibatalkan otomatis']);

        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->klien->id_pengguna, 'judul' => 'Penyaluran berhasil!']);
        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->pekerja->id_pengguna, 'judul' => 'Selamat! Anda tersalurkan']);

        // Panel admin: hasil + kabari via WhatsApp
        $this->actingAs($this->admin)->get(route('admin.orders.show', $this->pesanan))
            ->assertOk()->assertSee('Pekerja tersalurkan mulai')->assertSee('Kabari Klien')->assertSee('https://wa.me/6281200000003?text=', false);
        $this->assertStringContainsString('akan mulai bekerja pada', $jadwal->load('pemesanan.penyaluran', 'pemesanan.klien', 'pemesanan.profilPekerja.pengguna')->pesanHasil('klien'));
    }

    public function test_pekerja_tersalurkan_hilang_dari_katalog_dan_tidak_bisa_dipesan(): void
    {
        $this->actingAs($this->klienLain)->get(route('client.workers'))->assertSee('Budi Santoso');
        $this->cocok();

        $tidakAda = fn ($daftar) => collect($daftar->items())->doesntContain(fn ($h) => $h['profil']->is($this->profil));
        $this->actingAs($this->klienLain)->get(route('client.workers'))->assertOk()->assertViewHas('pekerja', $tidakAda);
        $this->actingAs($this->klienLain)->get(route('client.workers', ['cari' => 'Budi']))->assertOk()->assertViewHas('pekerja', $tidakAda);
        $this->actingAs($this->klienLain)->get(route('client.workers.show', $this->profil))
            ->assertOk()->assertSee('Pekerja ini sudah tersalurkan dan tidak tersedia')->assertSee('Sudah Tersalurkan');

        // Pesan lewat URL langsung ditolak (form & simpan)
        $this->actingAs($this->klienLain)->get(route('client.orders.create', $this->profil))
            ->assertRedirect(route('client.workers'))->assertSessionHas('error', 'Pekerja ini sudah tersalurkan dan tidak tersedia.');
        $this->actingAs($this->klienLain)->post(route('client.orders.store', $this->profil), [
            'tanggal_mulai' => now()->addDays(5)->toDateString(), 'lokasi_kerja' => 'Bekasi', 'catatan_klien' => 'Butuh segera untuk rumah.',
        ])->assertRedirect(route('client.workers'))->assertSessionHas('error');
        $this->assertSame(2, Pemesanan::count());

        // Statistik beranda publik hanya menghitung pekerja tersedia
        $this->get(route('home'))->assertOk()->assertViewHas('jumlahPekerja', 0);
    }

    public function test_favorit_menampilkan_badge_sudah_tersalurkan(): void
    {
        $this->klienLain->favorit()->create(['id_pekerja' => $this->profil->id_pekerja]);
        $this->cocok();

        $this->actingAs($this->klienLain)->get(route('client.favorites'))
            ->assertOk()->assertSee('Budi Santoso')->assertSee('Sudah Tersalurkan');
    }

    public function test_beranda_pekerja_dan_admin_menampilkan_status_tersalurkan(): void
    {
        $this->cocok();

        $this->actingAs($this->pekerja)->get(route('worker.dashboard'))
            ->assertOk()->assertSee('Anda sedang bekerja sejak')->assertSee('Profil Anda tidak ditampilkan di katalog selama masa kerja.');
        $this->actingAs($this->pekerja)->get(route('worker.verification'))->assertSee('Tidak (sedang bekerja)');

        $this->actingAs($this->admin)->get(route('admin.workers'))->assertOk()->assertSee('Tersalurkan')->assertSee('di Rani Amelia');
        $this->actingAs($this->admin)->get(route('admin.workers.show', $this->profil))->assertOk()->assertSee('Tersalurkan');
        $this->actingAs($this->admin)->get(route('admin.placements'))->assertOk()->assertSee('Budi Santoso')->assertSee('Akhiri Penyaluran');
    }

    public function test_validasi_cocok_dan_pekerja_tidak_bisa_disalurkan_dua_kali(): void
    {
        $this->cocok(now(JadwalInterview::ZONA)->subDays(2)->toDateString())->assertSessionHasErrors('tanggal_mulai_kerja');
        $this->assertSame('disetujui', $this->pesanan->fresh()->status_pemesanan);
        $this->assertSame(0, Penyaluran::count());

        // Hasil tidak bisa dicatat dua kali
        $this->cocok();
        $this->cocok()->assertSessionHas('error');
        $this->assertSame(1, Penyaluran::count());
    }

    /* ---------- 2. Tidak cocok ---------- */

    public function test_tidak_cocok_membatalkan_pesanan_dan_pekerja_tetap_di_katalog(): void
    {
        $this->actingAs($this->admin)->post(route('admin.orders.interview.nomatch', $this->pesanan), ['alasan_tidak_cocok' => ''])
            ->assertSessionHasErrors('alasan_tidak_cocok');

        $this->actingAs($this->admin)->post(route('admin.orders.interview.nomatch', $this->pesanan), ['alasan_tidak_cocok' => 'Klien butuh pekerja yang bisa menginap'])
            ->assertSessionHasNoErrors();

        $p = $this->pesanan->fresh();
        $this->assertSame('dibatalkan', $p->status_pemesanan);
        $this->assertSame('Hasil interview tidak cocok: Klien butuh pekerja yang bisa menginap', $p->alasan_pembatalan);
        $this->assertSame($this->admin->id_pengguna, $p->dibatalkan_oleh);
        $this->assertSame('tidak_cocok', $this->jadwal->fresh()->hasil_interview);
        $this->assertSame(0, Penyaluran::count());
        $this->assertSame('menunggu', $this->pesananLain->fresh()->status_pemesanan);

        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->klien->id_pengguna, 'isi_notifikasi' => 'Hasil interview belum cocok. Silakan pilih pekerja lain di katalog. (Pesanan ' . $p->kode() . ')']);
        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->pekerja->id_pengguna, 'isi_notifikasi' => 'Hasil interview belum cocok dengan klien. Profil Anda tetap tampil di katalog.']);

        $this->actingAs($this->klienLain)->get(route('client.workers'))->assertSee('Budi Santoso');
        $this->actingAs($this->admin)->get(route('admin.orders.show', $this->pesanan))->assertSee('Hasil interview tidak cocok, pesanan dibatalkan.')->assertSee('Kabari Calon Pekerja');
    }

    public function test_dropdown_lama_tidak_bisa_menyelesaikan_pesanan_disetujui(): void
    {
        $this->actingAs($this->admin)->patch(route('admin.orders.status', $this->pesanan), ['status_pemesanan' => 'selesai'])
            ->assertSessionHas('error');
        $this->assertSame('disetujui', $this->pesanan->fresh()->status_pemesanan);
    }

    /* ---------- 5. Akhiri penyaluran ---------- */

    public function test_akhiri_penyaluran_mengembalikan_pekerja_ke_katalog(): void
    {
        $this->cocok(now(JadwalInterview::ZONA)->toDateString());
        $penyaluran = Penyaluran::firstOrFail();

        $this->actingAs($this->admin)->patch(route('admin.placements.end', $penyaluran), ['tanggal_berakhir' => now()->subDays(3)->toDateString(), 'id_penyaluran' => $penyaluran->id_penyaluran])
            ->assertSessionHasErrors('tanggal_berakhir');

        $this->actingAs($this->admin)->patch(route('admin.placements.end', $penyaluran), [
            'tanggal_berakhir' => now(JadwalInterview::ZONA)->toDateString(), 'catatan_berakhir' => 'Kontrak selesai',
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $penyaluran->refresh();
        $this->assertSame('berakhir', $penyaluran->status);
        $this->assertNotNull($penyaluran->tanggal_berakhir);
        $this->assertStringContainsString('Diakhiri: Kontrak selesai', $penyaluran->catatan);
        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->pekerja->id_pengguna, 'isi_notifikasi' => 'Masa kerja Anda telah berakhir. Profil Anda kembali tampil di katalog.']);

        $this->actingAs($this->klienLain)->get(route('client.workers'))->assertSee('Budi Santoso');
        $this->actingAs($this->admin)->get(route('admin.placements', ['status' => 'berakhir']))->assertSee('Budi Santoso');
        $this->actingAs($this->admin)->get(route('admin.workers'))->assertSee('Tersedia');
        $this->actingAs($this->klien)->get(route('client.orders.show', $this->pesanan))->assertSee('Penyaluran berhasil!');
    }

    /* ---------- 6. Laporan ---------- */

    public function test_laporan_menghitung_pekerja_tersalurkan_berdasarkan_tanggal_mulai(): void
    {
        $bulanDepan = now(JadwalInterview::ZONA)->addMonthNoOverflow()->startOfMonth();
        $this->cocok($bulanDepan->toDateString());

        // Data lama: pesanan selesai tanpa data penyaluran (tetap dihitung, pakai jadwal mulai)
        $lama = $this->buatPesanan($this->klienLain, 'selesai');
        $lama->forceFill(['tanggal_mulai' => now()->startOfMonth()->addDay()->toDateString()])->save();

        // Pesanan disetujui (masih tahap interview) tidak dihitung
        $this->assertSame(2, Pemesanan::daftarPenyaluran()->count());

        $this->actingAs($this->admin)->get(route('admin.reports', ['periode' => $bulanDepan->format('Y-m')]))
            ->assertOk()->assertViewHas('penyaluran', fn ($d) => $d->count() === 1 && $d->first()->is($this->pesanan))
            ->assertViewHas('perStatus', fn ($s) => ($s['aktif'] ?? 0) === 1);
        $this->actingAs($this->admin)->get(route('admin.reports', ['periode' => now()->format('Y-m')]))
            ->assertViewHas('penyaluran', fn ($d) => $d->count() === 1 && $d->first()->is($lama));
        $this->actingAs($this->admin)->get(route('admin.reports.print'))->assertOk()->assertSee('Masih bekerja');
    }

    /* ---------- Hak akses ---------- */

    public function test_klien_dan_pekerja_tidak_bisa_mengakses_aksi_penyaluran(): void
    {
        $this->actingAs($this->klien)->get(route('admin.placements'))->assertForbidden();
        $this->actingAs($this->klien)->post(route('admin.orders.interview.match', $this->pesanan), ['tanggal_mulai_kerja' => now()->toDateString()])->assertForbidden();
        $this->actingAs($this->pekerja)->post(route('admin.orders.interview.nomatch', $this->pesanan), ['alasan_tidak_cocok' => 'tidak mau'])->assertForbidden();
        $this->assertSame('disetujui', $this->pesanan->fresh()->status_pemesanan);
    }
}
