<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class KriteriaController extends Controller
{
    /** Formulir pengisian kriteria khusus pekerja (ya / tidak). */
    public function edit(Request $request)
    {
        $profil = $this->profil($request)->load('kriteria');

        return view('worker.criteria', [
            'profil' => $profil,
            'kelompokKriteria' => Kriteria::ambilSemua()->groupBy('kategori_kriteria'),
            'nilaiKriteria' => $profil->ambilKriteria(),
        ]);
    }

    /** Menyimpan nilai kriteria khusus ke tabel kriteria_pekerja. */
    public function update(Request $request)
    {
        $profil = $this->profil($request);
        $semuaId = Kriteria::ambilSemua()->pluck('id_kriteria');

        $data = $request->validate([
            'kriteria' => ['required', 'array'],
            'kriteria.*' => ['required', Rule::in(['ya', 'tidak'])],
        ], [
            'kriteria.required' => 'Kriteria khusus wajib diisi.',
        ]);

        // Seluruh kriteria wajib dijawab agar vektor pekerja lengkap
        $belumDiisi = $semuaId->diff(array_keys($data['kriteria']));
        if ($belumDiisi->isNotEmpty()) {
            throw ValidationException::withMessages([
                'kriteria' => 'Semua kriteria wajib dijawab (masih ada ' . $belumDiisi->count() . ' kriteria yang belum diisi).',
            ]);
        }

        $profil->kriteria()->sync(
            $semuaId->mapWithKeys(fn ($id) => [$id => ['nilai_kriteria' => $data['kriteria'][$id]]])->all()
        );

        return redirect()->route('worker.criteria')->with('success', 'Kriteria khusus berhasil disimpan.');
    }
}
