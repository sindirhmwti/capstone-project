<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ProfilPekerja;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    /** Halaman Favorit Saya. */
    public function index(Request $request)
    {
        $favorit = $request->user()->favorit()
            ->with('profilPekerja.pengguna')
            ->latest('tanggal_ditambahkan')
            ->get();

        return view('client.favorites', compact('favorit'));
    }

    /** Menambahkan pekerja ke favorit. */
    public function store(Request $request, ProfilPekerja $pekerja)
    {
        if (! ProfilPekerja::tampilDiKatalog()->whereKey($pekerja->getKey())->exists()) {
            return back()->with('error', 'Pekerja tidak tersedia sehingga tidak dapat ditambahkan ke favorit.');
        }

        $request->user()->favorit()->firstOrCreate(['id_pekerja' => $pekerja->id_pekerja]);

        return back()->with('success', $pekerja->pengguna->nama . ' ditambahkan ke favorit.');
    }

    /** Menghapus pekerja dari favorit. */
    public function destroy(Request $request, ProfilPekerja $pekerja)
    {
        $request->user()->favorit()->where('id_pekerja', $pekerja->id_pekerja)->delete();

        return back()->with('success', $pekerja->pengguna->nama . ' dihapus dari favorit.');
    }
}
