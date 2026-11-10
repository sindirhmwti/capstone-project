<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalInterview;
use App\Models\Penyaluran;
use App\Services\PenyaluranService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Menu "Penyaluran": daftar pekerja yang tersalurkan dan tombol "Akhiri Penyaluran".
 */
class PenyaluranController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['status' => ['nullable', Rule::in(['semua', 'aktif', 'berakhir'])]]);
        $status = $request->query('status', 'semua');

        $penyaluran = Penyaluran::with(['profilPekerja.pengguna', 'klien', 'pemesanan'])
            ->when($status !== 'semua', fn ($q) => $q->where('status', $status))
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->cari;
                $q->where(fn ($w) => $w->whereHas('profilPekerja.pengguna', fn ($p) => $p->where('nama', 'like', "%{$cari}%"))
                    ->orWhereHas('klien', fn ($k) => $k->where('nama', 'like', "%{$cari}%")));
            })
            ->orderByRaw("case when status = 'aktif' then 0 else 1 end")
            ->latest('tanggal_mulai')
            ->latest('id_penyaluran')
            ->paginate(15)->withQueryString();

        $jumlah = Penyaluran::selectRaw('status, count(*) as jumlah')->groupBy('status')->pluck('jumlah', 'status');

        return view('admin.placements.index', compact('penyaluran', 'status', 'jumlah'));
    }

    /** "Akhiri Penyaluran": status berakhir, pekerja otomatis tampil kembali di katalog. */
    public function akhiri(Request $request, Penyaluran $penyaluran, PenyaluranService $layanan)
    {
        if (! $penyaluran->isAktif()) {
            return back()->with('error', 'Penyaluran ini sudah berakhir.');
        }

        $data = $request->validate([
            'tanggal_berakhir' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . $penyaluran->tanggal_mulai->format('Y-m-d'), 'before_or_equal:' . now(JadwalInterview::ZONA)->toDateString()],
            'catatan_berakhir' => ['nullable', 'string', 'max:500'],
        ], [
            'tanggal_berakhir.required' => 'Tanggal berakhir wajib diisi.',
            'tanggal_berakhir.date_format' => 'Format tanggal berakhir tidak valid.',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal mulai kerja (' . $penyaluran->labelTanggalMulai() . ').',
            'tanggal_berakhir.before_or_equal' => 'Tanggal berakhir tidak boleh melewati hari ini.',
            'catatan_berakhir.max' => 'Catatan maksimal 500 karakter.',
        ]);

        $layanan->akhiri($penyaluran, $data['tanggal_berakhir'], $data['catatan_berakhir'] ?? null, $request->user()->id_pengguna);

        return back()->with('success', 'Penyaluran ' . $penyaluran->profilPekerja->pengguna->nama . ' diakhiri. Pekerja kembali tampil di katalog.');
    }
}
