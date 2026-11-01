<?php

namespace Tests\Feature;

use App\Http\Controllers\VerifikasiWhatsAppController;
use App\Services\WhatsAppOtpService;
use Tests\TestCase;

/**
 * Uji verifikasi OTP WhatsApp (mode demo) untuk bagian yang tidak memerlukan basis data.
 * Pembuatan akun setelah kode benar diuji melalui Black Box Testing.
 */
class VerifikasiOtpTest extends TestCase
{
    private function sesiRegistrasi(array $ubah = []): array
    {
        return [VerifikasiWhatsAppController::SESI => array_merge([
            'data' => [
                'peran' => 'klien', 'nama' => 'Uji OTP', 'email' => 'uji.otp@test.com',
                'no_telepon' => '6281234566789', 'kata_sandi' => bcrypt('Rahasia123'),
            ],
            'dibuat' => now()->timestamp,
            'kirim_terakhir' => now()->timestamp,
            'percobaan' => 0,
            'kunci_sampai' => 0,
        ], $ubah)];
    }

    public function test_halaman_verifikasi_tanpa_sesi_diarahkan_ke_registrasi(): void
    {
        $this->get('/register/verifikasi')->assertRedirect('/register');
    }

    public function test_halaman_verifikasi_menampilkan_nomor_tersamar_tanpa_keterangan_kode(): void
    {
        config(['otp.driver' => 'demo', 'otp.demo_code' => '0000']);

        $this->withSession($this->sesiRegistrasi())->get('/register/verifikasi')
            ->assertOk()
            ->assertSee('Verifikasi Nomor WhatsApp')
            ->assertSee('0812****6789')
            ->assertDontSee('Mode Demo');
    }

    public function test_kode_salah_mengurangi_sisa_percobaan(): void
    {
        $this->withSession($this->sesiRegistrasi())
            ->postJson('/register/verifikasi', ['digit' => ['1', '2', '3', '4']])
            ->assertStatus(422)
            ->assertJson(['ok' => false, 'sisa_percobaan' => 4])
            ->assertJsonFragment(['pesan' => 'Kode verifikasi salah. Sisa percobaan: 4']);
    }

    public function test_lima_kali_salah_dikunci_satu_menit(): void
    {
        $this->withSession($this->sesiRegistrasi(['percobaan' => 4]))
            ->postJson('/register/verifikasi', ['digit' => ['9', '9', '9', '9']])
            ->assertStatus(423)
            ->assertJson(['ok' => false, 'kunci' => 60]);
    }

    public function test_saat_terkunci_kode_tidak_diperiksa(): void
    {
        $this->withSession($this->sesiRegistrasi(['kunci_sampai' => now()->timestamp + 30]))
            ->postJson('/register/verifikasi', ['digit' => ['0', '0', '0', '0']])
            ->assertStatus(423);
    }

    public function test_sesi_kedaluwarsa_setelah_sepuluh_menit(): void
    {
        $this->withSession($this->sesiRegistrasi(['dibuat' => now()->subMinutes(11)->timestamp]))
            ->get('/register/verifikasi')
            ->assertRedirect('/register')
            ->assertSessionHas('error', 'Sesi verifikasi berakhir, silakan daftar ulang.');
    }

    public function test_kirim_ulang_ditolak_sebelum_timer_habis_dan_berhasil_setelahnya(): void
    {
        $this->withSession($this->sesiRegistrasi())
            ->postJson('/register/verifikasi/kirim-ulang')->assertStatus(429);

        $this->withSession($this->sesiRegistrasi(['kirim_terakhir' => now()->subSeconds(61)->timestamp]))
            ->postJson('/register/verifikasi/kirim-ulang')
            ->assertOk()->assertJson(['ok' => true, 'tunggu' => 60]);
    }

    public function test_normalisasi_dan_penyamaran_nomor(): void
    {
        $this->assertSame('6281234566789', WhatsAppOtpService::normalisasi('0812-3456-6789'));
        $this->assertSame('6281234566789', WhatsAppOtpService::normalisasi('+62 812 3456 6789'));
        $this->assertSame('6281234566789', WhatsAppOtpService::normalisasi('6281234566789'));
        $this->assertSame('0812****6789', WhatsAppOtpService::samarkan('6281234566789'));
        $this->assertContains('081234566789', WhatsAppOtpService::variasi('6281234566789'));
    }
}
