<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Uji fitur yang tidak memerlukan basis data
 * (alur lengkap diuji melalui Black Box Testing pada docs/BLACK_BOX_TESTING.md).
 */
class ExampleTest extends TestCase
{
    public function test_halaman_login_dapat_dibuka(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk');
    }

    public function test_halaman_pilih_akun_hanya_klien_dan_pekerja(): void
    {
        $this->get('/register')->assertOk()
            ->assertSee('Daftar sebagai Klien')
            ->assertSee('Daftar sebagai Pekerja');
    }

    public function test_tidak_ada_halaman_registrasi_admin(): void
    {
        $this->get('/register/pekerja/admin')->assertNotFound();
        $this->get('/register/admin')->assertNotFound();
    }

    public function test_tamu_diarahkan_ke_login_saat_membuka_dashboard(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/client/dashboard')->assertRedirect('/login');
        $this->get('/worker/dashboard')->assertRedirect('/login');
    }
}
