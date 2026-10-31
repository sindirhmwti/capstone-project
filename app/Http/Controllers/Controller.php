<?php

namespace App\Http\Controllers;

use App\Models\ProfilPekerja;
use Illuminate\Http\Request;

abstract class Controller
{
    /** Profil pekerja milik pengguna yang sedang login. */
    protected function profil(Request $request): ProfilPekerja
    {
        $profil = $request->user()->profilPekerja;
        abort_unless($profil, 404, 'Profil pekerja tidak ditemukan.');

        return $profil;
    }
}
