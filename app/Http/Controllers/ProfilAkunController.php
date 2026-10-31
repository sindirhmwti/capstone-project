<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Edit profil akun untuk Klien dan Admin (nama, email, telepon, kata sandi).
 * Profil pekerja memiliki halaman tersendiri karena datanya lebih lengkap.
 */
class ProfilAkunController extends Controller
{
    public function edit(Request $request)
    {
        $pengguna = $request->user();

        return view('profile.edit', [
            'pengguna' => $pengguna,
            'layout' => $pengguna->isAdmin() ? 'layouts.admin' : 'layouts.client',
            'crumb' => $pengguna->isAdmin() ? 'Admin' : 'Klien',
            'routeUpdate' => $pengguna->isAdmin() ? 'admin.profile.update' : 'client.profile.update',
        ]);
    }

    public function update(Request $request)
    {
        $pengguna = $request->user();

        $data = $request->validate([
            'nama' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('pengguna', 'email')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'no_telepon' => ['required', 'regex:/^(\+62|62|0)8[0-9]{7,12}$/'],
            'kata_sandi_lama' => ['nullable', 'required_with:kata_sandi_baru', 'current_password'],
            'kata_sandi_baru' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'no_telepon.regex' => 'Nomor telepon harus diawali 08 / 62 / +62 dan berisi 10-15 angka.',
            'kata_sandi_lama.required_with' => 'Masukkan kata sandi lama untuk mengganti kata sandi.',
            'kata_sandi_lama.current_password' => 'Kata sandi lama salah.',
        ]);

        $pengguna->fill([
            'nama' => $data['nama'],
            'email' => Str::lower($data['email']),
            'no_telepon' => $data['no_telepon'],
        ]);
        if (! empty($data['kata_sandi_baru'])) {
            $pengguna->kata_sandi = $data['kata_sandi_baru'];
        }
        $pengguna->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
