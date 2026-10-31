<?php

namespace Tests\Unit;

use App\Services\RekomendasiPekerja;
use PHPUnit\Framework\TestCase;

/**
 * Pengujian perhitungan Cosine Similarity pada layanan rekomendasi pekerja.
 */
class ExampleTest extends TestCase
{
    private RekomendasiPekerja $rekomendasi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rekomendasi = new RekomendasiPekerja();
    }

    public function test_vektor_identik_bernilai_satu(): void
    {
        $this->assertEqualsWithDelta(1.0, $this->rekomendasi->hitungCosineSimilarity([1, 0, 1, 1], [1, 0, 1, 1]), 1e-9);
    }

    public function test_vektor_tanpa_irisan_bernilai_nol(): void
    {
        $this->assertSame(0.0, $this->rekomendasi->hitungCosineSimilarity([1, 1, 0, 0], [0, 0, 1, 1]));
    }

    public function test_vektor_nol_dianggap_tidak_mirip(): void
    {
        $this->assertSame(0.0, $this->rekomendasi->hitungCosineSimilarity([0, 0, 0], [1, 1, 0]));
    }

    public function test_contoh_perhitungan_manual(): void
    {
        // Klien memilih 3 kriteria; pekerja memiliki 7 kriteria "ya", 3 di antaranya sama dengan pilihan klien.
        // cos = 3 / (√3 × √7) = 0,6547 → 65,5%
        $klien = [0, 1, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0];
        $pekerja = [1, 1, 1, 1, 0, 1, 0, 0, 1, 0, 1, 0];

        $skor = $this->rekomendasi->hitungCosineSimilarity($klien, $pekerja);

        $this->assertEqualsWithDelta(3 / (sqrt(3) * sqrt(7)), $skor, 1e-9);
        $this->assertSame(65.5, round($skor * 100, 1));
    }
}
