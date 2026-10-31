<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

/**
 * Halaman notifikasi untuk ketiga peran (admin, klien, pekerja).
 * Prefix route (admin / client / worker) menentukan layout yang dipakai.
 */
class NotifikasiController extends Controller
{
    private const PERAN = [
        'admin' => ['layout' => 'layouts.admin', 'crumb' => 'Admin'],
        'klien' => ['layout' => 'layouts.client', 'crumb' => 'Klien'],
        'pekerja' => ['layout' => 'layouts.worker', 'crumb' => 'Calon Pekerja'],
    ];

    public function index(Request $request)
    {
        $pengguna = $request->user();

        $notifikasi = $pengguna->notifikasi()
            ->latest('tanggal_dibuat')->latest('id_notifikasi')
            ->paginate(15);

        return view('notifications.index', [
            'notifikasi' => $notifikasi,
            'jumlahBelumDibaca' => $pengguna->jumlahNotifikasiBelumDibaca(),
            'layout' => self::PERAN[$pengguna->peran]['layout'],
            'crumb' => self::PERAN[$pengguna->peran]['crumb'],
            'prefix' => $this->prefix($request),
        ]);
    }

    /** Menandai satu notifikasi sebagai sudah dibaca. */
    public function read(Request $request, Notifikasi $notifikasi)
    {
        abort_unless($notifikasi->id_pengguna === $request->user()->id_pengguna, 403, 'Notifikasi ini bukan milik Anda.');
        $notifikasi->tandaiDibaca();

        return back();
    }

    /** Menandai seluruh notifikasi sebagai sudah dibaca. */
    public function readAll(Request $request)
    {
        $request->user()->notifikasi()->where('sudah_dibaca', false)->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    private function prefix(Request $request): string
    {
        return ['admin' => 'admin', 'klien' => 'client', 'pekerja' => 'worker'][$request->user()->peran];
    }
}
