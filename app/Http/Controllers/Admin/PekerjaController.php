<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Notifikasi;
use App\Models\ProfilPekerja;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PekerjaController extends Controller
{
    /** Katalog Pekerja (admin): seluruh calon pekerja beserta status verifikasinya. */
    public function katalog(Request $request)
    {
        $pekerja = $this->query($request)->paginate(12)->withQueryString();

        return view('admin.workers.catalog', [
            'pekerja' => $pekerja,
            'daftarLokasi' => $this->daftarLokasi(),
        ]);
    }

    /** Kelola Akun Pekerja: tabel akun calon pekerja. */
    public function index(Request $request)
    {
        $pekerja = $this->query($request)
            ->when($request->filled('status_akun'), fn (Builder $q) => $q->whereHas('pengguna', fn ($p) => $p->where('status_akun', $request->status_akun)))
            ->paginate(15)->withQueryString();

        return view('admin.workers.index', compact('pekerja'));
    }

    /** Detail akun & profil pekerja. */
    public function show(ProfilPekerja $pekerja)
    {
        $pekerja->load(['pengguna', 'dokumen', 'kriteria', 'verifikator', 'pemesanan.klien']);

        return view('admin.workers.show', [
            'profil' => $pekerja,
            'semuaKriteria' => Kriteria::ambilSemua(),
            'nilaiKriteria' => $pekerja->ambilKriteria(),
        ]);
    }

    /** Memperbarui data akun pekerja (nama, email, telepon, status akun). */
    public function update(Request $request, ProfilPekerja $pekerja)
    {
        $pengguna = $pekerja->pengguna;

        $data = $request->validate([
            'nama' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('pengguna', 'email')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'no_telepon' => ['required', 'regex:/^(\+62|62|0)8[0-9]{7,12}$/'],
            'status_akun' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ], [
            'no_telepon.regex' => 'Nomor telepon harus diawali 08 / 62 / +62 dan berisi 10-15 angka.',
        ]);

        $statusLama = $pengguna->status_akun;
        $pengguna->update([
            'nama' => $data['nama'],
            'email' => Str::lower($data['email']),
            'no_telepon' => $data['no_telepon'],
            'status_akun' => $data['status_akun'],
        ]);

        if ($statusLama !== $data['status_akun']) {
            $this->kirimNotifikasiStatus($pengguna->id_pengguna, $data['status_akun']);
        }

        return redirect()->route('admin.workers.show', $pekerja)->with('success', 'Perubahan data akun pekerja berhasil disimpan.');
    }

    /** Mengaktifkan / menonaktifkan akun pekerja secara cepat dari tabel. */
    public function ubahStatus(ProfilPekerja $pekerja)
    {
        $pengguna = $pekerja->pengguna;
        $statusBaru = $pengguna->isAktif() ? 'nonaktif' : 'aktif';
        $pengguna->update(['status_akun' => $statusBaru]);

        $this->kirimNotifikasiStatus($pengguna->id_pengguna, $statusBaru);

        return back()->with('success', 'Akun ' . $pengguna->nama . ' berhasil ' . ($statusBaru === 'aktif' ? 'diaktifkan' : 'dinonaktifkan') . '.');
    }

    /* ---------- Bantuan ---------- */

    private function query(Request $request): Builder
    {
        return ProfilPekerja::with('pengguna')
            ->when($request->filled('cari'), function (Builder $q) use ($request) {
                $cari = $request->cari;
                $q->where(fn ($w) => $w->where('keterampilan', 'like', "%{$cari}%")
                    ->orWhere('alamat', 'like', "%{$cari}%")
                    ->orWhereHas('pengguna', fn ($p) => $p->where('nama', 'like', "%{$cari}%")->orWhere('email', 'like', "%{$cari}%")));
            })
            ->when($request->filled('kategori'), fn (Builder $q) => $q->where('kategori_pekerjaan', $request->kategori))
            ->when($request->filled('lokasi'), fn (Builder $q) => $q->where('alamat', $request->lokasi))
            ->when($request->filled('status'), fn (Builder $q) => $q->where('status_verifikasi', $request->status))
            ->orderBy('id_pekerja');
    }

    private function daftarLokasi()
    {
        return ProfilPekerja::whereNotNull('alamat')->distinct()->orderBy('alamat')->pluck('alamat');
    }

    private function kirimNotifikasiStatus(int $idPengguna, string $status): void
    {
        $status === 'aktif'
            ? Notifikasi::kirim($idPengguna, 'Akun Anda diaktifkan', 'Akun Anda telah diaktifkan kembali oleh admin.')
            : Notifikasi::kirim($idPengguna, 'Akun Anda dinonaktifkan', 'Akun Anda dinonaktifkan oleh admin dan tidak tampil di katalog. Hubungi admin untuk informasi lebih lanjut.');
    }
}
