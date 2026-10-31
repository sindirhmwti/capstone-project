<?php

namespace App\Http\Controllers;

use App\Models\ProfilPekerja;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** Halaman beranda publik. */
    public function index()
    {
        $featuredWorkers = ProfilPekerja::tampilDiKatalog()
            ->with('pengguna')
            ->orderByDesc('lama_pengalaman')
            ->take(3)
            ->get()
            ->map(fn (ProfilPekerja $p) => (object) [
                'name' => $p->pengguna->nama,
                'category' => $p->labelKategori(),
                'experience' => $p->lama_pengalaman,
                'location' => $p->alamat,
            ]);

        $jumlahPekerja = ProfilPekerja::tampilDiKatalog()->count();

        return view('public.home', compact('featuredWorkers', 'jumlahPekerja'));
    }

    /**
     * Pintu masuk katalog dari halaman publik.
     * Katalog hanya dapat diakses klien yang sudah login (sesuai use case "Melihat Katalog Pekerja").
     */
    public function katalog(Request $request)
    {
        $pengguna = $request->user();

        // Kategori dari halaman beranda ("ART", "Baby Sitter", "Perawat Lansia") diubah ke kode kategori
        $kategori = array_search($request->query('category'), ProfilPekerja::KATEGORI_SINGKAT, true) ?: null;
        $parameter = array_filter(['kategori' => $kategori, 'cari' => $request->query('search')]);

        if (! $pengguna) {
            session()->put('url.intended', route('client.workers', $parameter));

            return redirect()->route('login')
                ->with('info', 'Silakan masuk sebagai klien untuk melihat katalog pekerja.');
        }

        if ($pengguna->isKlien()) {
            return redirect()->route('client.workers', $parameter);
        }

        return redirect()->route($pengguna->routeDashboard());
    }
}
