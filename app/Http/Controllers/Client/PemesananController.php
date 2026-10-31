<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Notifikasi;
use App\Models\Pemesanan;
use App\Models\ProfilPekerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    /** Riwayat pesanan klien dengan tab status. */
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');
        $pengguna = $request->user();

        $pesanan = $pengguna->pemesanan()
            ->with('profilPekerja.pengguna')
            ->when($status !== 'semua', fn ($q) => $q->where('status_pemesanan', $status))
            ->latest('tanggal_pemesanan')
            ->paginate(10)->withQueryString();

        $jumlah = $pengguna->pemesanan()->selectRaw('status_pemesanan, count(*) as jumlah')
            ->groupBy('status_pemesanan')->pluck('jumlah', 'status_pemesanan');

        return view('client.orders.index', compact('pesanan', 'status', 'jumlah'));
    }

    /** Detail pesanan beserta riwayat status. */
    public function show(Request $request, Pemesanan $pemesanan)
    {
        $this->pastikanMilik($request, $pemesanan);
        $pemesanan->load(['profilPekerja.pengguna', 'kriteria', 'riwayatStatus.pengubah', 'pembatal']);

        return view('client.orders.show', compact('pemesanan'));
    }

    /** Formulir pengajuan pemesanan untuk pekerja yang dipilih. */
    public function create(Request $request, ProfilPekerja $pekerja)
    {
        if ($pesan = $this->alasanTidakTersedia($request, $pekerja)) {
            return redirect()->route('client.workers')->with('error', $pesan);
        }

        $pekerja->load('pengguna');

        return view('client.orders.create', [
            'profil' => $pekerja,
            'kelompokKriteria' => Kriteria::ambilSemua()->groupBy('kategori_kriteria'),
            'idKriteria' => session('kriteria_dicari', []),
            'nilaiKriteria' => $pekerja->ambilKriteria(),
        ]);
    }

    /** Menyimpan pengajuan pemesanan beserta kriteria yang diminta. */
    public function store(Request $request, ProfilPekerja $pekerja)
    {
        $data = $request->validate([
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today', 'before:' . now()->addYear()->toDateString()],
            'lokasi_kerja' => ['required', 'string', 'max:255'],
            'catatan_klien' => ['required', 'string', 'min:10', 'max:1000'],
            'kriteria' => ['nullable', 'array'],
            'kriteria.*' => ['integer', 'exists:kriteria,id_kriteria'],
        ], [
            'tanggal_mulai.after_or_equal' => 'Jadwal mulai kerja tidak boleh sebelum hari ini.',
            'tanggal_mulai.before' => 'Jadwal mulai kerja maksimal satu tahun dari hari ini.',
        ]);

        // A-2: pekerja tidak tersedia
        if ($pesan = $this->alasanTidakTersedia($request, $pekerja)) {
            return redirect()->route('client.workers')->with('error', $pesan);
        }

        $pengguna = $request->user();

        $pesanan = DB::transaction(function () use ($data, $pekerja, $pengguna) {
            $pesanan = Pemesanan::create([
                'id_klien' => $pengguna->id_pengguna,
                'id_pekerja' => $pekerja->id_pekerja,
                'status_pemesanan' => 'menunggu',
                'tanggal_mulai' => $data['tanggal_mulai'],
                'lokasi_kerja' => $data['lokasi_kerja'],
                'catatan_klien' => $data['catatan_klien'],
            ]);

            $pesanan->kriteria()->sync(
                collect($data['kriteria'] ?? [])->unique()
                    ->mapWithKeys(fn ($id) => [(int) $id => ['nilai_yang_diminta' => 'ya']])->all()
            );

            $pesanan->riwayatStatus()->create([
                'diubah_oleh' => $pengguna->id_pengguna,
                'status_sebelumnya' => null,
                'status_baru' => 'menunggu',
                'catatan_perubahan' => 'Pesanan diajukan oleh klien.',
            ]);

            return $pesanan;
        });

        $namaPekerja = $pekerja->pengguna->nama;
        Notifikasi::kirim($pengguna->id_pengguna, 'Pengajuan pemesanan diterima',
            'Pesanan ' . $pesanan->kode() . ' untuk ' . $namaPekerja . ' berhasil diajukan dan menunggu konfirmasi admin.');
        Notifikasi::kirimKeAdmin('Pengajuan pemesanan baru dari klien',
            $pengguna->nama . ' mengajukan pesanan ' . $pesanan->kode() . ' untuk ' . $namaPekerja . ' (' . $pekerja->labelKategori() . ').');

        return redirect()->route('client.orders.show', $pesanan)
            ->with('success', 'Pemesanan berhasil diajukan. Admin akan memproses pesanan Anda.');
    }

    /** Membatalkan pesanan (hanya saat menunggu / diproses) dengan alasan. */
    public function cancel(Request $request, Pemesanan $pemesanan)
    {
        $this->pastikanMilik($request, $pemesanan);

        if (! $pemesanan->bisaDibatalkanKlien()) {
            return back()->with('error', 'Pesanan berstatus "' . $pemesanan->labelStatus() . '" tidak dapat dibatalkan.');
        }

        $data = $request->validate([
            'alasan_pembatalan' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $pemesanan->ubahStatus('dibatalkan', $request->user()->id_pengguna, $data['alasan_pembatalan']);

        Notifikasi::kirimKeAdmin('Pesanan dibatalkan klien',
            $request->user()->nama . ' membatalkan pesanan ' . $pemesanan->kode() . '. Alasan: ' . $data['alasan_pembatalan']);
        Notifikasi::kirim($request->user()->id_pengguna, 'Pesanan dibatalkan',
            'Pesanan ' . $pemesanan->kode() . ' berhasil dibatalkan.');

        return back()->with('success', 'Pesanan ' . $pemesanan->kode() . ' berhasil dibatalkan.');
    }

    /* ---------- Bantuan ---------- */

    private function pastikanMilik(Request $request, Pemesanan $pemesanan): void
    {
        abort_unless($pemesanan->id_klien === $request->user()->id_pengguna, 403, 'Pesanan ini bukan milik Anda.');
    }

    /** Mengembalikan pesan bila pekerja tidak dapat dipesan, atau null bila tersedia. */
    private function alasanTidakTersedia(Request $request, ProfilPekerja $pekerja): ?string
    {
        if (! ProfilPekerja::tampilDiKatalog()->whereKey($pekerja->getKey())->exists()) {
            return 'Pekerja tidak tersedia sehingga tidak dapat dipesan.';
        }

        if ($pekerja->pemesanan()->where('status_pemesanan', 'disetujui')->exists()) {
            return 'Pekerja sedang bertugas pada pesanan lain sehingga tidak dapat dipesan saat ini.';
        }

        $sudahAda = $request->user()->pemesanan()
            ->where('id_pekerja', $pekerja->id_pekerja)
            ->whereIn('status_pemesanan', ['menunggu', 'diproses'])
            ->exists();
        if ($sudahAda) {
            return 'Anda masih memiliki pesanan aktif untuk pekerja ini. Pantau statusnya di Riwayat Pesanan.';
        }

        return null;
    }
}
