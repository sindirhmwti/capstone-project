<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\DokumenPekerja;
use App\Models\Notifikasi;
use App\Models\ProfilPekerja;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VerifikasiController extends Controller
{
    /** Halaman Status Verifikasi + unggah dokumen persyaratan. */
    public function index(Request $request)
    {
        $profil = $this->profil($request)->load('dokumen');

        $syarat = [
            'Data pribadi & profil lengkap' => $profil->cekKelengkapanProfil(),
            'Kriteria khusus sudah diisi' => $profil->cekKriteriaTerisi(),
            'Dokumen wajib sudah diunggah' => $profil->cekKelengkapanDokumen(),
        ];

        return view('worker.verification', [
            'pengguna' => $request->user(),
            'profil' => $profil,
            'syarat' => $syarat,
            'siapDiajukan' => ! in_array(false, $syarat, true),
        ]);
    }

    /** Mengunggah (atau mengganti) dokumen KTP / sertifikat pelatihan. */
    public function unggah(Request $request)
    {
        $profil = $this->profil($request);

        if (! $profil->bisaDiajukan()) {
            return back()->with('error', 'Dokumen tidak dapat diubah saat status verifikasi "' . $profil->labelStatus() . '".');
        }

        $data = $request->validate([
            'jenis_dokumen' => ['required', Rule::in($profil->dokumenWajib())],
            'berkas' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ], [
            'berkas.mimes' => 'Format dokumen harus PDF, JPG, atau PNG.',
            'berkas.max' => 'Ukuran dokumen maksimal 2 MB.',
            // Berkas di atas batas upload_max_filesize PHP ditolak sebelum aturan "max" diperiksa
            'berkas.uploaded' => 'Dokumen gagal diunggah. Pastikan ukuran berkas maksimal 2 MB.',
            'berkas.required' => 'Pilih berkas dokumen terlebih dahulu.',
        ]);

        // Dokumen lama dengan jenis yang sama diganti
        $profil->dokumen()->where('jenis_dokumen', $data['jenis_dokumen'])->get()
            ->each(function (DokumenPekerja $lama) {
                $lama->hapusBerkas();
                $lama->delete();
            });

        $berkas = $request->file('berkas');
        $nama = $data['jenis_dokumen'] . '-' . now()->format('YmdHis') . '.' . strtolower($berkas->getClientOriginalExtension());
        $path = $berkas->storeAs('dokumen/' . $profil->id_pekerja, $nama, DokumenPekerja::DISK);

        $profil->dokumen()->create([
            'jenis_dokumen' => $data['jenis_dokumen'],
            'lokasi_berkas' => $path,
            'status_verifikasi' => 'menunggu',
        ]);

        return back()->with('success', DokumenPekerja::JENIS[$data['jenis_dokumen']] . ' berhasil diunggah.');
    }

    /** Mengajukan data dan dokumen untuk diperiksa admin. */
    public function ajukan(Request $request)
    {
        $profil = $this->profil($request)->load('dokumen');

        if (! $profil->bisaDiajukan()) {
            return back()->with('error', 'Data Anda sudah diajukan dan berstatus "' . $profil->labelStatus() . '".');
        }

        if (! $profil->cekKelengkapanProfil() || ! $profil->cekKriteriaTerisi() || ! $profil->cekKelengkapanDokumen()) {
            return back()->with('error', 'Lengkapi profil, kriteria khusus, dan dokumen wajib sebelum mengajukan verifikasi.');
        }

        $profil->ubahStatusVerifikasi('menunggu');

        $pengguna = $request->user();
        Notifikasi::kirim($pengguna->id_pengguna, 'Pengajuan verifikasi terkirim',
            'Data dan dokumen Anda sedang menunggu pemeriksaan admin.');
        Notifikasi::kirimKeAdmin('Data pekerja menunggu verifikasi',
            $pengguna->nama . ' (' . ProfilPekerja::KATEGORI_SINGKAT[$profil->kategori_pekerjaan] . ') mengajukan verifikasi data dan dokumen.');

        return back()->with('success', 'Pengajuan verifikasi berhasil dikirim. Admin akan memeriksa data Anda.');
    }
}
