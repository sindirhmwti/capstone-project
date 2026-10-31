<?php

namespace App\Http\Controllers;

use App\Models\DokumenPekerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Menampilkan berkas dokumen pekerja.
     * Hanya admin dan pemilik dokumen yang boleh membuka (melindungi data pribadi).
     */
    public function show(Request $request, DokumenPekerja $dokumen)
    {
        $pengguna = $request->user();
        $pemilik = $dokumen->profilPekerja?->id_pengguna === $pengguna->id_pengguna;

        abort_unless($pengguna->isAdmin() || $pemilik, 403, 'Anda tidak memiliki hak akses ke dokumen ini.');
        abort_unless(Storage::disk(DokumenPekerja::DISK)->exists($dokumen->lokasi_berkas), 404, 'Berkas dokumen tidak ditemukan.');

        return Storage::disk(DokumenPekerja::DISK)->response($dokumen->lokasi_berkas);
    }
}
