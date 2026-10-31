<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\ProfilPekerja;
use Illuminate\Support\Collection;

/**
 * Rekomendasi calon pekerja menggunakan algoritma Cosine Similarity.
 *
 * Setiap kriteria khusus menjadi satu dimensi vektor (urut berdasarkan id_kriteria).
 *  - Vektor klien  : 1 untuk kriteria yang dipilih klien, 0 untuk yang tidak dipilih.
 *  - Vektor pekerja: 1 bila nilai_kriteria = "ya", 0 bila "tidak" / belum diisi.
 *
 *                      A · B           Σ (Ai × Bi)
 *  cos(A, B) = ----------------- = ---------------------------
 *               ||A|| × ||B||      √Σ(Ai²) × √Σ(Bi²)
 *
 * Nilai 0–1 dikalikan 100 sehingga menjadi skor kecocokan dalam persen.
 */
class RekomendasiPekerja
{
    /** @var Collection<int, Kriteria>|null */
    private ?Collection $semuaKriteria = null;

    /** Seluruh kriteria (dimuat sekali saat dibutuhkan) sebagai dimensi vektor. */
    private function semuaKriteria(): Collection
    {
        return $this->semuaKriteria ??= Kriteria::ambilSemua();
    }

    /**
     * Mengubah daftar kriteria menjadi vektor biner sesuai urutan seluruh kriteria.
     *
     * @param  array<int, string>|array<int, int>  $nilai  [id_kriteria => 'ya'|'tidak'] atau daftar id_kriteria yang bernilai 1
     * @return array<int, int>
     */
    public function bentukVektor(array $nilai, bool $daftarId = false): array
    {
        return $this->semuaKriteria()
            ->map(function (Kriteria $k) use ($nilai, $daftarId) {
                if ($daftarId) {
                    return in_array($k->id_kriteria, $nilai) ? 1 : 0;
                }

                return ($nilai[$k->id_kriteria] ?? null) === 'ya' ? 1 : 0;
            })
            ->values()
            ->all();
    }

    /**
     * Menghitung Cosine Similarity dua vektor (hasil 0 sampai 1).
     *
     * @param  array<int, int|float>  $a
     * @param  array<int, int|float>  $b
     */
    public function hitungCosineSimilarity(array $a, array $b): float
    {
        $hasilKali = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($a as $i => $nilaiA) {
            $nilaiB = $b[$i] ?? 0;
            $hasilKali += $nilaiA * $nilaiB;
            $normA += $nilaiA ** 2;
            $normB += $nilaiB ** 2;
        }

        // Vektor nol tidak memiliki arah sehingga kemiripan dianggap 0
        if ($normA == 0.0 || $normB == 0.0) {
            return 0.0;
        }

        return $hasilKali / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Mengurutkan kandidat pekerja berdasarkan skor kecocokan terhadap kriteria klien.
     *
     * @param  array<int, int>  $idKriteriaKlien  id kriteria yang dipilih klien
     * @param  Collection<int, ProfilPekerja>  $kandidat  pekerja (relasi kriteria sudah dimuat)
     * @return Collection<int, array{profil: ProfilPekerja, skor: float, cocok: int, total: int}>
     */
    public function rekomendasikan(array $idKriteriaKlien, Collection $kandidat): Collection
    {
        $vektorKlien = $this->bentukVektor($idKriteriaKlien, true);
        $total = array_sum($vektorKlien);

        return $kandidat
            ->map(function (ProfilPekerja $profil) use ($vektorKlien, $idKriteriaKlien, $total) {
                $nilaiPekerja = $profil->ambilKriteria();
                $vektorPekerja = $this->bentukVektor($nilaiPekerja);

                return [
                    'profil' => $profil,
                    'skor' => round($this->hitungCosineSimilarity($vektorKlien, $vektorPekerja) * 100, 1),
                    // Jumlah kriteria klien yang terpenuhi (informasi tambahan untuk klien)
                    'cocok' => collect($idKriteriaKlien)->filter(fn ($id) => ($nilaiPekerja[$id] ?? null) === 'ya')->count(),
                    'total' => $total,
                ];
            })
            ->sortBy([
                ['skor', 'desc'],
                ['cocok', 'desc'],
                fn ($a, $b) => $b['profil']->lama_pengalaman <=> $a['profil']->lama_pengalaman,
            ])
            ->values();
    }
}
