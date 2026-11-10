<?php

namespace Tests\Feature;

use App\Models\JadwalInterview;
use App\Models\Pemesanan;
use App\Models\Pengguna;
use App\Models\ProfilPekerja;
use App\Support\WhatsApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Uji alur undangan & konfirmasi interview via WhatsApp (wa.me): setujui + jadwal, kirim undangan,
 * tandai jawaban, ubah jadwal, "Perlu dihubungi ulang", banner klien/pekerja, dan hak akses.
 *
 * Memakai SQLite in-memory (phpunit.xml). Bila ekstensi pdo_sqlite tidak aktif, tes dilewati.
 * Jalankan: php -d extension=pdo_sqlite vendor/bin/phpunit --filter=UndanganInterviewTest
 */
class UndanganInterviewTest extends TestCase
{
    use RefreshDatabase;

    private Pengguna $admin;
    private Pengguna $klien;
    private Pengguna $pekerja;
    private Pemesanan $pesanan;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite tidak aktif. Jalankan: php -d extension=pdo_sqlite vendor/bin/phpunit');
        }

        parent::setUp();
        config(['services.whatsapp.admin' => null, 'services.whatsapp.driver' => 'demo']);
        Mail::fake();

        $this->admin = $this->buatPengguna('admin', 'Admin Shaka', 'admin@uji.test', '081200000001');
        $this->klien = $this->buatPengguna('klien', 'Rani Amelia', 'klien@uji.test', '081234566789');
        $this->pekerja = $this->buatPengguna('pekerja', 'Budi Santoso', 'art@uji.test', '081299991234');
        $profil = ProfilPekerja::create([
            'id_pengguna' => $this->pekerja->id_pengguna, 'kategori_pekerjaan' => 'art',
            'alamat' => 'Jakarta Selatan', 'status_verifikasi' => 'terverifikasi',
        ]);
        $this->pesanan = Pemesanan::create([
            'id_klien' => $this->klien->id_pengguna, 'id_pekerja' => $profil->id_pekerja,
            'status_pemesanan' => 'menunggu', 'tanggal_mulai' => now()->addDays(10)->toDateString(),
            'lokasi_kerja' => 'Jakarta Selatan', 'catatan_klien' => 'Butuh ART untuk keluarga kecil.',
        ]);
    }

    protected function tearDown(): void
    {
        if (extension_loaded('pdo_sqlite')) {
            Mail::assertNothingSent(); // fitur ini tidak memakai email sama sekali
        }
        parent::tearDown();
    }

    private function buatPengguna(string $peran, string $nama, string $email, ?string $telepon): Pengguna
    {
        return Pengguna::create([
            'nama' => $nama, 'email' => $email, 'no_telepon' => $telepon,
            'kata_sandi' => 'Rahasia123', 'peran' => $peran, 'status_akun' => 'aktif',
        ]);
    }

    private function dataJadwal(array $ubah = []): array
    {
        return array_merge([
            'tanggal_interview' => now(JadwalInterview::ZONA)->addDays(3)->toDateString(),
            'waktu_interview' => '09:30',
            'metode' => 'offline',
            'lokasi_atau_link' => 'Kantor PT Shaka Abadi Nusantara',
            'catatan_interview' => 'Datang 10 menit lebih awal.',
        ], $ubah);
    }

    private function setujui(): JadwalInterview
    {
        $this->actingAs($this->admin)
            ->post(route('admin.orders.approve', $this->pesanan), $this->dataJadwal())
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.orders.show', $this->pesanan) . '#jadwal-interview');

        return $this->jadwal();
    }

    private function jadwal(): JadwalInterview
    {
        return JadwalInterview::with(['pemesanan.klien', 'pemesanan.profilPekerja.pengguna'])->firstOrFail();
    }

    /* ---------- 3. Setujui + jadwal ---------- */

    public function test_admin_menyetujui_pesanan_dengan_jadwal(): void
    {
        $jadwal = $this->setujui();

        $this->assertSame('disetujui', $this->pesanan->fresh()->status_pemesanan);
        $this->assertSame('09:30', $jadwal->labelJam());
        $this->assertSame('menunggu', $jadwal->konfirmasi_klien);
        $this->assertNull($jadwal->undangan_klien_dikirim_pada);

        $riwayat = $this->pesanan->riwayatStatus()->get();
        $this->assertTrue($riwayat->contains(fn ($r) => $r->status_sebelumnya === 'menunggu' && $r->status_baru === 'disetujui'));
        $this->assertTrue($riwayat->contains(fn ($r) => str_starts_with((string) $r->catatan_perubahan, 'Jadwal interview dibuat')));

        foreach ([$this->klien, $this->pekerja] as $p) {
            $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $p->id_pengguna, 'judul' => 'Pesanan disetujui']);
        }
        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->klien->id_pengguna, 'judul' => 'Status pemesanan Anda diperbarui']);

        $this->actingAs($this->admin)->get(route('admin.orders'))->assertSee('2 belum diundang');
    }

    public function test_validasi_jadwal(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.orders.approve', $this->pesanan), $this->dataJadwal(['tanggal_interview' => now()->subDay()->toDateString()]))
            ->assertSessionHasErrors('tanggal_interview');
        $this->actingAs($this->admin)
            ->post(route('admin.orders.approve', $this->pesanan), $this->dataJadwal(['metode' => 'online', 'lokasi_atau_link' => 'zoom aja']))
            ->assertSessionHasErrors('lokasi_atau_link');
        $this->actingAs($this->admin)
            ->post(route('admin.orders.approve', $this->pesanan), $this->dataJadwal(['lokasi_atau_link' => '']))
            ->assertSessionHasErrors('lokasi_atau_link');

        $this->assertSame('menunggu', $this->pesanan->fresh()->status_pemesanan);
        $this->assertSame(0, JadwalInterview::count());
    }

    public function test_dropdown_status_lama_tidak_bisa_menyetujui_tanpa_jadwal(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.orders.status', $this->pesanan), ['status_pemesanan' => 'disetujui'])
            ->assertSessionHas('error');

        $this->assertSame('menunggu', $this->pesanan->fresh()->status_pemesanan);
    }

    /* ---------- 4b. Kirim undangan via WhatsApp ---------- */

    public function test_kirim_undangan_membuka_wa_me_mencatat_waktu_dan_log(): void
    {
        $this->setujui();
        Log::spy();

        $respons = $this->actingAs($this->admin)->post(route('admin.orders.interview.invite', [$this->pesanan, 'klien']));

        $jadwal = $this->jadwal();
        $pesan = $jadwal->pesanUndangan('klien');
        $respons->assertRedirect('https://wa.me/6281234566789?text=' . rawurlencode($pesan));

        $this->assertStringStartsWith('Halo Ibu/Bapak Rani Amelia, kami dari PT Shaka Abadi Nusantara.', $pesan);
        $this->assertStringContainsString("Pesanan #{$this->pesanan->kode()} untuk ART - Budi Santoso telah DISETUJUI.", $pesan);
        $this->assertStringContainsString("\nWaktu: 09:30 WIB\nMetode: Offline\nLokasi/Link: Kantor PT Shaka Abadi Nusantara\nCatatan: Datang 10 menit lebih awal.", $pesan);
        $this->assertStringContainsString('%0A', $respons->headers->get('Location')); // baris baru ikut ter-encode

        $this->assertNotNull($jadwal->undangan_klien_dikirim_pada);
        $this->assertNull($jadwal->undangan_pekerja_dikirim_pada);
        Log::shouldHaveReceived('info')->withArgs(fn ($m) => str_contains($m, '[WHATSAPP DEMO] Pesan ke 6281234566789') && str_contains($m, 'telah DISETUJUI'));

        $this->assertTrue($this->pesanan->riwayatStatus()->get()->contains(fn ($r) => str_contains((string) $r->catatan_perubahan, 'dikirim via WhatsApp ke klien')));

        $this->actingAs($this->admin)->get(route('admin.orders.show', $this->pesanan))
            ->assertSee('Kirim Ulang via WhatsApp')->assertSee('Undangan dikirim pada', false)->assertSee('Kirim Undangan via WhatsApp');
    }

    public function test_pesan_undangan_calon_pekerja(): void
    {
        $this->setujui();
        $pesan = $this->jadwal()->pesanUndangan('pekerja');

        $this->assertStringStartsWith('Halo Budi Santoso, kami dari PT Shaka Abadi Nusantara.', $pesan);
        $this->assertStringContainsString('Anda dipilih oleh klien untuk posisi ART.', $pesan);
        $this->assertStringContainsString('Mohon membawa KTP asli dan sertifikat (jika ada).', $pesan);

        $this->actingAs($this->admin)->post(route('admin.orders.interview.invite', [$this->pesanan, 'pekerja']))
            ->assertRedirect('https://wa.me/6281299991234?text=' . rawurlencode($pesan));
    }

    public function test_nomor_kosong_tombol_whatsapp_nonaktif(): void
    {
        $this->klien->update(['no_telepon' => null]);
        $this->setujui();

        $this->actingAs($this->admin)->get(route('admin.orders.show', $this->pesanan))->assertSee('Nomor WhatsApp belum tersedia');
        $this->actingAs($this->admin)->post(route('admin.orders.interview.invite', [$this->pesanan, 'klien']))
            ->assertRedirect(route('admin.orders.show', $this->pesanan))->assertSessionHas('error');
        $this->assertNull($this->jadwal()->undangan_klien_dikirim_pada);
    }

    /* ---------- 4c. Tandai jawaban ---------- */

    public function test_admin_menandai_hadir_minta_ubah_dan_tidak_merespons(): void
    {
        $this->setujui();
        $url = fn ($pihak) => route('admin.orders.interview.mark', [$this->pesanan, $pihak]);

        // Hadir
        $this->actingAs($this->admin)->post($url('klien'), ['status' => 'hadir'])->assertSessionHas('success');
        $jadwal = $this->jadwal();
        $this->assertSame('hadir', $jadwal->konfirmasi_klien);
        $this->assertNotNull($jadwal->dikonfirmasi_klien_pada);
        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->klien->id_pengguna, 'judul' => 'Kehadiran interview Anda telah dikonfirmasi admin']);
        $this->actingAs($this->klien)->get(route('client.orders.show', $this->pesanan))->assertSee('Kehadiran Anda telah dikonfirmasi.');

        // Minta ubah wajib catatan
        $this->actingAs($this->admin)->post($url('pekerja'), ['status' => 'minta_ubah'])->assertSessionHasErrors('catatan_pekerja');
        $this->actingAs($this->admin)->post($url('pekerja'), ['status' => 'minta_ubah', 'catatan_pekerja' => 'Sakit, minta diundur ke Jumat.'])
            ->assertSessionHasNoErrors();
        $jadwal = $this->jadwal();
        $this->assertSame('minta_ubah', $jadwal->konfirmasi_pekerja);
        $this->assertSame('Sakit, minta diundur ke Jumat.', $jadwal->catatan_konfirmasi_pekerja);
        $this->assertTrue($this->pesanan->riwayatStatus()->get()->contains(fn ($r) => str_contains((string) $r->catatan_perubahan, 'meminta UBAH JADWAL') && str_contains((string) $r->catatan_perubahan, 'Sakit, minta diundur ke Jumat')));
        $this->actingAs($this->pekerja)->get(route('worker.dashboard'))->assertSee('Permintaan ubah jadwal sedang ditinjau admin.');
        $this->actingAs($this->admin)->get(route('admin.orders.show', $this->pesanan))->assertSee('Sakit, minta diundur ke Jumat.');
        $this->actingAs($this->admin)->get(route('admin.orders'))->assertSee('Perlu dihubungi');

        // Tidak merespons
        $this->actingAs($this->admin)->post($url('pekerja'), ['status' => 'tidak_merespons'])->assertSessionHasNoErrors();
        $this->assertSame('tidak_merespons', $this->jadwal()->konfirmasi_pekerja);
        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->pekerja->id_pengguna, 'judul' => 'Mohon balas undangan interview']);
        $this->actingAs($this->admin)->get(route('admin.orders.show', $this->pesanan))->assertSee('Tidak Merespons');
    }

    public function test_indikator_dua_hadir(): void
    {
        $this->setujui();
        foreach (['klien', 'pekerja'] as $pihak) {
            $this->actingAs($this->admin)->post(route('admin.orders.interview.mark', [$this->pesanan, $pihak]), ['status' => 'hadir']);
        }
        $this->actingAs($this->admin)->get(route('admin.orders'))->assertSee('2/2 hadir');
    }

    /* ---------- 4f. Ubah jadwal ---------- */

    public function test_ubah_jadwal_mereset_konfirmasi_dan_pesan_diawali_perubahan(): void
    {
        $this->setujui();
        $this->actingAs($this->admin)->post(route('admin.orders.interview.invite', [$this->pesanan, 'klien']));
        $this->actingAs($this->admin)->post(route('admin.orders.interview.mark', [$this->pesanan, 'klien']), ['status' => 'hadir']);

        $this->actingAs($this->admin)
            ->put(route('admin.orders.interview', $this->pesanan), $this->dataJadwal(['waktu_interview' => '13:00', 'metode' => 'online', 'lokasi_atau_link' => 'https://meet.google.com/abc-defg-hij']))
            ->assertSessionHasNoErrors()->assertSessionHas('success');

        $jadwal = $this->jadwal();
        $this->assertSame('menunggu', $jadwal->konfirmasi_klien);
        $this->assertSame('menunggu', $jadwal->konfirmasi_pekerja);
        $this->assertNull($jadwal->undangan_klien_dikirim_pada);
        $this->assertNotNull($jadwal->jadwal_diubah_pada);
        $this->assertStringStartsWith("PERUBAHAN JADWAL INTERVIEW\n", $jadwal->pesanUndangan('klien'));
        $this->assertStringStartsWith("PERUBAHAN JADWAL INTERVIEW\n", $jadwal->pesanUndangan('pekerja'));
        $this->assertStringContainsString('Lokasi/Link: https://meet.google.com/abc-defg-hij', $jadwal->pesanUndangan('klien'));
        $this->assertDatabaseHas('notifikasi', ['id_pengguna' => $this->pekerja->id_pengguna, 'judul' => 'Jadwal interview diperbarui']);

        $this->actingAs($this->klien)->get(route('client.orders.show', $this->pesanan))
            ->assertSee('Jadwal interview diperbarui. Cek pesan WhatsApp terbaru dari admin.');
    }

    /* ---------- 4d. Perlu dihubungi ulang ---------- */

    public function test_lewat_24_jam_tanpa_respons_muncul_perlu_dihubungi_ulang(): void
    {
        $this->setujui();
        $this->actingAs($this->admin)->post(route('admin.orders.interview.invite', [$this->pesanan, 'klien']));
        $this->actingAs($this->admin)->get(route('admin.orders.show', $this->pesanan))->assertDontSee('Perlu dihubungi ulang');

        JadwalInterview::query()->update(['undangan_klien_dikirim_pada' => now()->subHours(25)]);

        $this->actingAs($this->admin)->get(route('admin.orders.show', $this->pesanan))->assertSee('Perlu dihubungi ulang');
        $this->actingAs($this->admin)->get(route('admin.orders'))->assertSee('Perlu dihubungi');
    }

    /* ---------- 5. Banner klien & calon pekerja ---------- */

    public function test_banner_klien_dan_pekerja_serta_tombol_ke_admin(): void
    {
        $this->setujui();

        // ADMIN_WHATSAPP kosong: tombol konfirmasi disembunyikan
        $this->actingAs($this->klien)->get(route('client.orders.show', $this->pesanan))
            ->assertOk()
            ->assertSee('Pesanan Anda telah disetujui. Admin akan mengirim undangan interview melalui WhatsApp ke nomor')
            ->assertSee('0812****6789')
            ->assertSee('Belum dikonfirmasi - silakan balas pesan WhatsApp dari admin.')
            ->assertDontSee('Konfirmasi via WhatsApp');
        $this->actingAs($this->klien)->get(route('client.orders'))->assertSee('Silakan cek WhatsApp Anda dan balas pesan dari admin');

        $this->actingAs($this->pekerja)->get(route('worker.dashboard'))
            ->assertOk()->assertSee('Anda dipilih oleh klien. Admin akan mengirim undangan interview melalui WhatsApp ke nomor')->assertSee('0812****1234');

        // Setelah undangan dikirim
        $this->actingAs($this->admin)->post(route('admin.orders.interview.invite', [$this->pesanan, 'klien']));
        $this->actingAs($this->klien)->get(route('client.orders.show', $this->pesanan))
            ->assertSee('Admin telah mengirim undangan interview melalui WhatsApp pada');

        // ADMIN_WHATSAPP diisi: tombol membuka wa.me ke admin dengan pesan benar
        config(['services.whatsapp.admin' => '0811-2233-4455']);
        $jadwal = $this->jadwal();
        $pesanHadir = 'Halo Admin Temukerja, saya Rani Amelia (klien) untuk pesanan #' . $this->pesanan->kode()
            . '. Saya konfirmasi HADIR pada sesi interview ' . $jadwal->labelTanggal() . ' pukul 09:30 WIB.';
        $this->assertSame($pesanHadir, $jadwal->pesanKeAdmin('klien', 'hadir'));
        $this->assertStringContainsString('Saya ingin MENGUBAH JADWAL interview', $jadwal->pesanKeAdmin('pekerja', 'ubah'));
        $this->assertStringContainsString('(calon pekerja)', $jadwal->pesanKeAdmin('pekerja', 'ubah'));

        $this->actingAs($this->klien)->get(route('client.orders.show', $this->pesanan))
            ->assertSee('Konfirmasi via WhatsApp')->assertSee('Minta Ubah Jadwal via WhatsApp')
            ->assertSee(e('https://wa.me/6281122334455?text=' . rawurlencode($pesanHadir)), false);
    }

    /* ---------- 5.5 Hak akses ---------- */

    public function test_pengguna_lain_tidak_bisa_melihat_jadwal_orang_lain(): void
    {
        $this->setujui();

        $klienLain = $this->buatPengguna('klien', 'Klien Lain', 'lain@uji.test', '081200000009');
        $pekerjaLain = $this->buatPengguna('pekerja', 'Pekerja Lain', 'pekerja.lain@uji.test', '081200000008');
        ProfilPekerja::create(['id_pengguna' => $pekerjaLain->id_pengguna, 'kategori_pekerjaan' => 'art']);

        $this->actingAs($klienLain)->get(route('client.orders.show', $this->pesanan))->assertForbidden();
        $this->actingAs($klienLain)->get(route('client.orders'))->assertDontSee($this->pesanan->kode());
        $this->actingAs($pekerjaLain)->get(route('worker.dashboard'))->assertOk()->assertDontSee('Undangan interview');

        // Klien / pekerja tidak bisa memakai aksi admin
        $this->actingAs($this->klien)->post(route('admin.orders.interview.mark', [$this->pesanan, 'klien']), ['status' => 'hadir'])->assertForbidden();
        $this->actingAs($this->pekerja)->post(route('admin.orders.interview.invite', [$this->pesanan, 'pekerja']))->assertForbidden();
        $this->assertSame('menunggu', $this->jadwal()->konfirmasi_klien);
    }

    /* ---------- 2. Helper & service WhatsApp ---------- */

    public function test_helper_dan_service_whatsapp(): void
    {
        $this->assertSame('6281234566789', WhatsApp::normalisasi('0812-3456-6789'));
        $this->assertSame('6281234566789', WhatsApp::normalisasi('+62 812 3456 6789'));
        $this->assertSame('https://wa.me/6281234566789?text=Halo%0ABaris%20dua%20%26%20tiga', WhatsApp::link('081234566789', "Halo\nBaris dua & tiga"));
        $this->assertNull(WhatsApp::link(null, 'x'));
        $this->assertNull(WhatsApp::link('123', 'x'));
        $this->assertSame('0812****6789', WhatsApp::samarkan('6281234566789'));

        Log::spy();
        $this->assertTrue(app(\App\Services\WhatsAppService::class)->kirimPesan('081234566789', 'Tes pesan'));
        Log::shouldHaveReceived('info')->withArgs(fn ($m) => str_contains($m, '[WHATSAPP DEMO] Pesan ke 6281234566789') && str_contains($m, 'Tes pesan'));
    }
}
