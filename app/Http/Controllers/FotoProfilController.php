<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Unggah, ganti, dan hapus foto profil untuk pengguna yang login (admin, klien, calon pekerja).
 * - Calon pekerja: WAJIB, bisa diganti tetapi tidak bisa dihapus.
 * - Klien & admin: opsional, bisa dihapus.
 * File disimpan di storage/app/public/foto-profil dengan nama acak; file lama dihapus saat diganti/dihapus.
 */
class FotoProfilController extends Controller
{
    public function update(Request $request)
    {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();

        $request->validate([
            'foto_profil' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=300,min_height=300'],
        ], [
            'foto_profil.required' => 'Pilih foto terlebih dahulu.',
            'foto_profil.file' => 'Foto gagal diunggah. Silakan coba lagi.',
            'foto_profil.image' => 'File harus berupa gambar JPG, JPEG, PNG, atau WEBP.',
            'foto_profil.mimes' => 'File harus berupa gambar JPG, JPEG, PNG, atau WEBP.',
            'foto_profil.max' => 'Ukuran foto maksimal 2 MB.',
            // Berkas di atas batas upload PHP ditolak sebelum aturan "max" diperiksa
            'foto_profil.uploaded' => 'Foto gagal diunggah. Pastikan ukuran foto maksimal 2 MB.',
            'foto_profil.dimensions' => 'Ukuran foto minimal 300 x 300 piksel.',
        ]);

        $sudahAda = $pengguna->punyaFotoProfil();
        $pathLama = $pengguna->foto_profil;

        // Nama file acak (hash) di folder foto-profil
        $pathBaru = $request->file('foto_profil')->store(Pengguna::FOLDER_FOTO, Pengguna::DISK_FOTO);
        if (! $pathBaru) {
            return back()->withErrors(['foto_profil' => 'Foto gagal disimpan. Silakan coba lagi.']);
        }

        $pengguna->foto_profil = $pathBaru;
        $pengguna->save();

        // File lama ikut dihapus setelah foto baru tersimpan
        if ($pathLama && $pathLama !== $pathBaru) {
            Storage::disk(Pengguna::DISK_FOTO)->delete($pathLama);
        }

        return back()->with('success', $sudahAda ? 'Foto profil berhasil diganti.' : 'Foto profil berhasil diunggah.');
    }

    public function destroy(Request $request)
    {
        /** @var Pengguna $pengguna */
        $pengguna = $request->user();

        if ($pengguna->isPekerja()) {
            return back()->with('error', 'Foto profil wajib untuk calon pekerja. Anda dapat menggantinya, tetapi tidak dapat menghapusnya.');
        }

        if (! $pengguna->punyaFotoProfil()) {
            return back()->with('info', 'Anda belum memiliki foto profil.');
        }

        $pengguna->hapusFileFotoProfil();
        $pengguna->foto_profil = null;
        $pengguna->save();

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}
