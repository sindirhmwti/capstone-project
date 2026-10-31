<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /** Halaman Profil Pekerja (tampilan informasi lengkap). */
    public function show(Request $request)
    {
        $pengguna = $request->user();
        $profil = $this->profil($request)->load(['kriteria', 'dokumen']);
        $semuaKriteria = Kriteria::ambilSemua();
        $nilaiKriteria = $profil->ambilKriteria();

        return view('worker.profile.show', compact('pengguna', 'profil', 'semuaKriteria', 'nilaiKriteria'));
    }

    /** Formulir Edit Profil Pekerja. */
    public function edit(Request $request)
    {
        return view('worker.profile.edit', [
            'pengguna' => $request->user(),
            'profil' => $this->profil($request),
        ]);
    }

    /** Menyimpan perubahan profil pekerja. */
    public function update(Request $request)
    {
        $pengguna = $request->user();
        $profil = $this->profil($request);

        $data = $request->validate([
            'nama' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('pengguna', 'email')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'no_telepon' => ['required', 'regex:/^(\+62|62|0)8[0-9]{7,12}$/'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString(), 'after:1950-01-01'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'alamat' => ['required', 'string', 'max:255'],
            'lama_pengalaman' => ['required', 'integer', 'min:0', 'max:50'],
            'keterampilan' => ['required', 'string', 'max:255'],
            'deskripsi_profil' => ['nullable', 'string', 'max:1000'],
            'kata_sandi_lama' => ['nullable', 'required_with:kata_sandi_baru', 'current_password'],
            'kata_sandi_baru' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'no_telepon.regex' => 'Nomor telepon harus diawali 08 / 62 / +62 dan berisi 10-15 angka.',
            'kata_sandi_lama.required_with' => 'Masukkan kata sandi lama untuk mengganti kata sandi.',
            'kata_sandi_lama.current_password' => 'Kata sandi lama salah.',
            'tanggal_lahir.before_or_equal' => 'Usia pekerja minimal 18 tahun.',
        ]);

        DB::transaction(function () use ($pengguna, $profil, $data) {
            $pengguna->fill([
                'nama' => $data['nama'],
                'email' => Str::lower($data['email']),
                'no_telepon' => $data['no_telepon'],
            ]);
            if (! empty($data['kata_sandi_baru'])) {
                $pengguna->kata_sandi = $data['kata_sandi_baru'];
            }
            $pengguna->save();

            $profil->update([
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'],
                'lama_pengalaman' => $data['lama_pengalaman'],
                'keterampilan' => $data['keterampilan'],
                'deskripsi_profil' => $data['deskripsi_profil'] ?? null,
            ]);
        });

        return redirect()->route('worker.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
