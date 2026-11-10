<?php

namespace Tests\Feature;

use App\Models\Kriteria;
use App\Models\Pengguna;
use App\Models\ProfilPekerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Uji fitur foto profil: wajib untuk calon pekerja, opsional untuk klien & admin.
 *
 * Memakai SQLite in-memory dan GD (untuk membuat gambar uji). Bila ekstensi tidak aktif, tes dilewati.
 * Jalankan: php -d extension=pdo_sqlite -d extension=gd vendor/bin/phpunit --filter=FotoProfilTest
 */
class FotoProfilTest extends TestCase
{
    use RefreshDatabase;

    private Pengguna $admin;
    private Pengguna $klien;
    private Pengguna $pekerja;
    private ProfilPekerja $profil;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite') || ! extension_loaded('gd')) {
            $this->markTestSkipped('Butuh ekstensi pdo_sqlite & gd. Jalankan: php -d extension=pdo_sqlite -d extension=gd vendor/bin/phpunit');
        }

        parent::setUp();
        Storage::fake('public');

        $this->admin = $this->buatPengguna('admin', 'Admin Shaka', 'admin@uji.test', '081200000001');
        $this->klien = $this->buatPengguna('klien', 'Rani Amelia', 'klien@uji.test', '081200000002');
        $this->pekerja = $this->buatPengguna('pekerja', 'Budi Santoso', 'art@uji.test', '081200000003');
        $this->profil = ProfilPekerja::create([
            'id_pengguna' => $this->pekerja->id_pengguna, 'kategori_pekerjaan' => 'art', 'status_verifikasi' => 'belum_lengkap',
            'alamat' => 'Jakarta Selatan', 'tanggal_lahir' => '1990-01-01', 'jenis_kelamin' => 'Laki-laki', 'keterampilan' => 'Memasak',
        ]);
    }

    private function buatPengguna(string $peran, string $nama, string $email, ?string $telepon): Pengguna
    {
        return Pengguna::create([
            'nama' => $nama, 'email' => $email, 'no_telepon' => $telepon,
            'kata_sandi' => 'Rahasia123', 'peran' => $peran, 'status_akun' => 'aktif',
        ]);
    }

    private function foto(int $lebar = 400, int $tinggi = 400, string $nama = 'wajah.jpg'): UploadedFile
    {
        return UploadedFile::fake()->image($nama, $lebar, $tinggi);
    }

    /** Lengkapi kriteria & KTP agar hanya foto profil yang menjadi pembeda. */
    private function lengkapiSyaratLain(): void
    {
        $kriteria = Kriteria::create(['nama_kriteria' => 'Bisa memasak', 'kategori_kriteria' => 'keterampilan']);
        $this->profil->kriteriaPekerja()->create(['id_kriteria' => $kriteria->id_kriteria, 'nilai_kriteria' => 'ya']);
        $this->profil->dokumen()->create(['jenis_dokumen' => 'ktp', 'lokasi_berkas' => 'dokumen/ktp.jpg', 'status_verifikasi' => 'menunggu']);
    }

    /* ---------- Pengecekan 1: wajib untuk pengajuan verifikasi ---------- */

    public function test_pekerja_tanpa_foto_tidak_bisa_mengajukan_verifikasi_lalu_bisa_setelah_unggah(): void
    {
        $this->lengkapiSyaratLain();

        $this->actingAs($this->pekerja)->get(route('worker.verification'))
            ->assertOk()->assertSee('Unggah foto profil terlebih dahulu')->assertSee('Foto profil sudah diunggah');

        // Server menolak walau tombol dimanipulasi
        $this->actingAs($this->pekerja)->post(route('worker.verification.submit'))
            ->assertSessionHas('error', 'Unggah foto profil terlebih dahulu sebelum mengajukan verifikasi.');
        $this->assertSame('belum_lengkap', $this->profil->fresh()->status_verifikasi);

        $this->actingAs($this->pekerja)->post(route('worker.profile.photo'), ['foto_profil' => $this->foto()])
            ->assertSessionHasNoErrors()->assertSessionHas('success', 'Foto profil berhasil diunggah.');

        $this->actingAs($this->pekerja)->get(route('worker.verification'))->assertDontSee('Unggah foto profil terlebih dahulu');
        $this->actingAs($this->pekerja)->post(route('worker.verification.submit'))->assertSessionHas('success');
        $this->assertSame('menunggu', $this->profil->fresh()->status_verifikasi);
    }

    public function test_beranda_pekerja_menampilkan_pengingat_dan_checklist_foto(): void
    {
        $this->actingAs($this->pekerja)->get(route('worker.dashboard'))
            ->assertOk()
            ->assertSee('Foto profil wajib diunggah agar profil Anda dapat diverifikasi dan tampil di katalog')
            ->assertSee('Unggah Foto')
            ->assertSee('Foto profil');

        $this->pekerja->update(['foto_profil' => 'foto-profil/ada.jpg']);
        $this->actingAs($this->pekerja)->get(route('worker.dashboard'))
            ->assertDontSee('Foto profil wajib diunggah agar profil Anda dapat diverifikasi');
    }

    public function test_halaman_edit_profil_pekerja_berisi_panduan_foto(): void
    {
        $this->actingAs($this->pekerja)->get(route('worker.profile.edit'))
            ->assertOk()
            ->assertSee('Gunakan foto wajah Anda sendiri yang jelas, menghadap depan, tanpa kacamata hitam/masker. Foto ini akan dilihat klien.')
            ->assertSee('Wajib diunggah')
            ->assertDontSee('Hapus Foto');
    }

    /* ---------- Pengecekan 2: validasi file ---------- */

    public function test_file_bukan_gambar_terlalu_besar_atau_terlalu_kecil_ditolak(): void
    {
        $kirim = fn ($berkas) => $this->actingAs($this->klien)->post(route('client.profile.photo'), ['foto_profil' => $berkas]);

        $kirim(UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'))
            ->assertSessionHasErrors(['foto_profil' => 'File harus berupa gambar JPG, JPEG, PNG, atau WEBP.']);
        $kirim($this->foto(400, 400)->size(2100))
            ->assertSessionHasErrors(['foto_profil' => 'Ukuran foto maksimal 2 MB.']);
        $kirim($this->foto(200, 250))
            ->assertSessionHasErrors(['foto_profil' => 'Ukuran foto minimal 300 x 300 piksel.']);
        $kirim(null)->assertSessionHasErrors(['foto_profil' => 'Pilih foto terlebih dahulu.']);

        $this->assertNull($this->klien->fresh()->foto_profil);
        $this->assertSame([], Storage::disk('public')->allFiles('foto-profil'));
    }

    public function test_format_png_dan_webp_diterima(): void
    {
        $this->actingAs($this->klien)->post(route('client.profile.photo'), ['foto_profil' => $this->foto(300, 300, 'a.png')])->assertSessionHasNoErrors();
        $this->actingAs($this->klien)->post(route('client.profile.photo'), ['foto_profil' => $this->foto(500, 320, 'b.webp')])->assertSessionHasNoErrors();
        $this->assertStringEndsWith('.webp', $this->klien->fresh()->foto_profil);
    }

    /* ---------- Pengecekan 3: ganti & hapus ---------- */

    public function test_ganti_foto_menghapus_file_lama_dan_nama_file_acak(): void
    {
        $this->actingAs($this->pekerja)->post(route('worker.profile.photo'), ['foto_profil' => $this->foto(400, 400, 'wajah-saya.jpg')]);
        $lama = $this->pekerja->fresh()->foto_profil;
        $this->assertStringStartsWith('foto-profil/', $lama);
        $this->assertStringNotContainsString('wajah-saya', $lama); // nama acak
        Storage::disk('public')->assertExists($lama);

        $this->actingAs($this->pekerja)->post(route('worker.profile.photo'), ['foto_profil' => $this->foto(600, 600)])
            ->assertSessionHas('success', 'Foto profil berhasil diganti.');
        $baru = $this->pekerja->fresh()->foto_profil;

        $this->assertNotSame($lama, $baru);
        Storage::disk('public')->assertMissing($lama);
        Storage::disk('public')->assertExists($baru);
        $this->assertCount(1, Storage::disk('public')->allFiles('foto-profil'));
    }

    public function test_klien_bisa_menghapus_foto_tetapi_pekerja_tidak(): void
    {
        // Klien
        $this->actingAs($this->klien)->post(route('client.profile.photo'), ['foto_profil' => $this->foto()]);
        $path = $this->klien->fresh()->foto_profil;
        $this->actingAs($this->klien)->get(route('client.profile'))->assertSee('Foto Profil (opsional)')->assertSee('Hapus Foto')->assertSee('Ganti Foto');
        $this->actingAs($this->klien)->delete(route('client.profile.photo.destroy'))->assertSessionHas('success', 'Foto profil berhasil dihapus.');
        $this->assertNull($this->klien->fresh()->foto_profil);
        Storage::disk('public')->assertMissing($path);

        // Pekerja: tidak ada rute hapus, dan rute milik klien ditolak
        $this->actingAs($this->pekerja)->post(route('worker.profile.photo'), ['foto_profil' => $this->foto()]);
        $fotoPekerja = $this->pekerja->fresh()->foto_profil;
        $this->actingAs($this->pekerja)->delete('/worker/profile/photo')->assertStatus(405);
        $this->actingAs($this->pekerja)->delete(route('client.profile.photo.destroy'))->assertForbidden();
        $this->assertSame($fotoPekerja, $this->pekerja->fresh()->foto_profil);
        Storage::disk('public')->assertExists($fotoPekerja);
    }

    public function test_admin_bisa_unggah_foto_opsional(): void
    {
        $this->actingAs($this->admin)->get(route('admin.profile'))->assertOk()->assertSee('Foto Profil (opsional)');
        $this->actingAs($this->admin)->post(route('admin.profile.photo'), ['foto_profil' => $this->foto()])->assertSessionHasNoErrors();
        $this->assertNotNull($this->admin->fresh()->foto_profil);
        $this->actingAs($this->admin)->get(route('admin.dashboard'))->assertOk()->assertSee($this->admin->fresh()->urlFotoProfil(), false);
    }

    /* ---------- Pengecekan 4: tampil di berbagai halaman ---------- */

    public function test_foto_tampil_di_katalog_detail_admin_dan_inisial_bila_kosong(): void
    {
        $this->profil->update(['status_verifikasi' => 'terverifikasi', 'lama_pengalaman' => 3]);

        // Akun lama terverifikasi tanpa foto tetap tampil di katalog dengan inisial
        $this->actingAs($this->klien)->get(route('client.workers'))->assertOk()->assertSee('Budi Santoso')->assertSee('>BS<', false);

        $this->actingAs($this->pekerja)->post(route('worker.profile.photo'), ['foto_profil' => $this->foto()]);
        $url = $this->pekerja->fresh()->urlFotoProfil();
        $this->assertStringContainsString('/storage/foto-profil/', $url);

        $this->actingAs($this->klien)->get(route('client.workers'))->assertSee($url, false);
        $this->actingAs($this->klien)->get(route('client.workers.show', $this->profil))->assertSee($url, false);
        $this->actingAs($this->admin)->get(route('admin.workers'))->assertSee($url, false);
        $this->actingAs($this->admin)->get(route('admin.workers.show', $this->profil))->assertSee($url, false);
        $this->actingAs($this->admin)->get(route('admin.verification.show', $this->profil))->assertSee($url, false)->assertSee('Cocokkan wajah pada foto ini');
        $this->actingAs($this->pekerja)->get(route('worker.dashboard'))->assertSee($url, false); // topbar & sidebar
    }

    public function test_verifikasi_admin_menampilkan_keterangan_bila_foto_kosong(): void
    {
        $this->actingAs($this->admin)->get(route('admin.verification.show', $this->profil))
            ->assertOk()->assertSee('Pekerja belum mengunggah foto profil.');
    }
}
