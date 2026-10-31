<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Beranda pekerja: ringkasan status akun, verifikasi, dan kelengkapan profil. */
    public function index(Request $request)
    {
        $pengguna = $request->user();
        $profil = $this->profil($request)->load('dokumen');

        $profileSections = [
            ['label' => 'Data pribadi & profil', 'done' => $profil->cekKelengkapanProfil(), 'link' => route('worker.profile.edit')],
            ['label' => 'Kriteria khusus', 'done' => $profil->cekKriteriaTerisi(), 'link' => route('worker.criteria')],
            ['label' => 'Dokumen KTP', 'done' => (bool) $profil->dokumenTerbaru('ktp'), 'link' => route('worker.verification')],
        ];
        if ($profil->wajibSertifikat()) {
            $profileSections[] = ['label' => 'Sertifikat pelatihan', 'done' => (bool) $profil->dokumenTerbaru('sertifikat_pelatihan'), 'link' => route('worker.verification')];
        }
        $profileSections[] = ['label' => 'Diajukan untuk verifikasi', 'done' => ! $profil->bisaDiajukan(), 'link' => route('worker.verification')];

        $selesai = collect($profileSections)->where('done', true)->count();
        $profileCompletion = (int) round($selesai / count($profileSections) * 100);

        $stats = [
            'documents_uploaded' => $profil->dokumen->count(),
            'notifications' => $pengguna->jumlahNotifikasiBelumDibaca(),
            'orders' => $profil->pemesanan()->whereIn('status_pemesanan', ['disetujui', 'selesai'])->count(),
        ];

        $notifikasiTerbaru = $pengguna->notifikasi()->latest('tanggal_dibuat')->latest('id_notifikasi')->take(3)->get();

        return view('worker.dashboard', compact('pengguna', 'profil', 'stats', 'profileCompletion', 'profileSections', 'notifikasiTerbaru'));
    }
}
