<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\ProfilPekerja;
use App\Services\RekomendasiPekerja;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KatalogController extends Controller
{
    /**
     * Halaman "Temukan Pekerja yang Tepat": katalog + filter + rekomendasi Cosine Similarity.
     */
    public function index(Request $request, RekomendasiPekerja $rekomendasi)
    {
        $request->validate([
            'kategori' => ['nullable', 'in:art,baby_sitter,perawat_lansia'],
            'kriteria' => ['nullable', 'array'],
            'kriteria.*' => ['integer', 'exists:kriteria,id_kriteria'],
        ]);

        $idKriteria = array_map('intval', $request->input('kriteria', []));
        // Default: hanya tampilkan pekerja yang memenuhi semua kriteria yang dipilih
        $wajibSemua = ! $request->has('cari_kriteria') || $request->boolean('wajib_semua');

        // Kriteria terakhir disimpan di sesi untuk mengisi otomatis formulir pemesanan
        session(['kriteria_dicari' => $idKriteria]);

        $query = ProfilPekerja::tampilDiKatalog()
            ->with(['pengguna', 'kriteria'])
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->cari;
                $q->where(fn ($w) => $w->where('keterampilan', 'like', "%{$cari}%")
                    ->orWhere('alamat', 'like', "%{$cari}%")
                    ->orWhereHas('pengguna', fn ($p) => $p->where('nama', 'like', "%{$cari}%")));
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori_pekerjaan', $request->kategori))
            ->when($request->filled('lokasi'), fn ($q) => $q->where('alamat', $request->lokasi));

        if ($idKriteria && $wajibSemua) {
            foreach ($idKriteria as $id) {
                $query->whereHas('kriteriaPekerja', fn ($q) => $q->where('id_kriteria', $id)->where('nilai_kriteria', 'ya'));
            }
        }

        $kandidat = $query->get();

        // Tanpa kriteria: urutkan berdasarkan pengalaman, tanpa skor
        $hasil = $idKriteria
            ? $rekomendasi->rekomendasikan($idKriteria, $kandidat)
            : $kandidat->sortByDesc('lama_pengalaman')->values()
                ->map(fn ($p) => ['profil' => $p, 'skor' => null, 'cocok' => null, 'total' => 0]);

        $perHalaman = 9;
        $halaman = LengthAwarePaginator::resolveCurrentPage();
        $pekerja = new LengthAwarePaginator(
            $hasil->forPage($halaman, $perHalaman)->values(),
            $hasil->count(),
            $perHalaman,
            $halaman,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('client.workers.index', [
            'pekerja' => $pekerja,
            'kelompokKriteria' => Kriteria::ambilSemua()->groupBy('kategori_kriteria'),
            'idKriteria' => $idKriteria,
            'wajibSemua' => $wajibSemua,
            'daftarLokasi' => ProfilPekerja::tampilDiKatalog()->whereNotNull('alamat')->distinct()->orderBy('alamat')->pluck('alamat'),
            'idFavorit' => $request->user()->favorit()->pluck('id_pekerja')->all(),
        ]);
    }

    /** Detail profil pekerja (hanya pekerja terverifikasi & aktif). */
    public function show(Request $request, ProfilPekerja $pekerja, RekomendasiPekerja $rekomendasi)
    {
        $tersedia = ProfilPekerja::tampilDiKatalog()->whereKey($pekerja->getKey())->exists();
        if (! $tersedia) {
            return redirect()->route('client.workers')->with('error', 'Data profil pekerja tidak tersedia.');
        }

        $pekerja->load(['pengguna', 'kriteria']);
        $idKriteria = session('kriteria_dicari', []);
        $skor = $idKriteria ? $rekomendasi->rekomendasikan($idKriteria, collect([$pekerja]))->first() : null;

        return view('client.workers.show', [
            'profil' => $pekerja,
            'semuaKriteria' => Kriteria::ambilSemua(),
            'nilaiKriteria' => $pekerja->ambilKriteria(),
            'idKriteria' => $idKriteria,
            'skor' => $skor,
            'favorit' => $request->user()->favorit()->where('id_pekerja', $pekerja->id_pekerja)->exists(),
            'jumlahPenyaluran' => $pekerja->pemesanan()->whereIn('status_pemesanan', ['disetujui', 'selesai'])->count(),
        ]);
    }
}
