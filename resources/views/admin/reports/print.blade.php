<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penyaluran {{ $labelPeriode }} — PT Shaka Abadi Nusantara</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-icon.svg') }}">
    {{-- Halaman cetak berdiri sendiri (tanpa Vite) agar tetap rapi saat disimpan sebagai PDF --}}
    <style>
        @page { size: A4; margin: 18mm 15mm; }
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color: #13201B; font-size: 12px; margin: 0; }
        .kop { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 3px solid #246C54; padding-bottom: 10px; }
        .kop h1 { margin: 0; font-size: 18px; color: #246C54; }
        .kop p { margin: 2px 0 0; color: #4D5C55; }
        h2 { font-size: 15px; margin: 18px 0 4px; text-align: center; }
        .sub { text-align: center; color: #4D5C55; margin: 0 0 14px; }
        .ringkasan { display: flex; gap: 8px; margin-bottom: 14px; }
        .ringkasan div { flex: 1; border: 1px solid #dfe7e2; border-radius: 6px; padding: 8px; }
        .ringkasan b { display: block; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cfd8d3; padding: 6px 8px; text-align: left; }
        th { background: #E8F3EE; }
        .ttd { margin-top: 40px; display: flex; justify-content: flex-end; text-align: center; }
        .ttd div { width: 220px; }
        .aksi { text-align: right; margin-bottom: 12px; }
        .aksi button { background: #246C54; color: #fff; border: 0; padding: 8px 16px; border-radius: 6px; cursor: pointer; }
        .aksi button:hover { background: #1A5240; }
        /* Di layar: tampil seperti lembar kertas A4 di atas latar abu-abu */
        @media screen {
            html { background: #E8EDEA; }
            body { max-width: 210mm; min-height: 297mm; margin: 24px auto; padding: 18mm 15mm; background: #fff; box-shadow: 0 10px 40px -12px rgba(18, 32, 26, .25); border-radius: 8px; }
        }
        @media print { .aksi { display: none; } * { animation: none !important; transition: none !important; } }
    </style>
</head>
<body>
    <div class="aksi">
        <button onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="kop">
        <div>
            <img src="{{ asset('images/logo-temukerja.svg') }}" alt="temukerja." style="height:34px;margin-bottom:6px">
            <h1>PT Shaka Abadi Nusantara</h1>
            <p>Penyedia &amp; penyalur tenaga kerja domestik — ART, Baby Sitter, Perawat Lansia</p>
            <p>Komplek P&amp;K Jl. Melur No. 183, Cipondoh Indah, Kota Tangerang, Banten</p>
        </div>
        <p>Dicetak: {{ now()->translatedFormat('d F Y H:i') }}<br>Oleh: {{ auth()->user()->nama }}</p>
    </div>

    <h2>LAPORAN PENYALURAN PEKERJA</h2>
    <p class="sub">Periode: {{ $labelPeriode }}{{ request('kategori') ? ' · Kategori ' . \App\Models\ProfilPekerja::KATEGORI_SINGKAT[request('kategori')] : '' }}</p>

    <div class="ringkasan">
        <div><b>{{ $penyaluran->count() }}</b>Total penyaluran</div>
        @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
            <div><b>{{ $perKategori[$kode] ?? 0 }}</b>{{ $label }}</div>
        @endforeach
        <div><b>{{ $perStatus['selesai'] ?? 0 }}</b>Selesai</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Kode</th>
                <th>Nama pekerja</th>
                <th>Jenis pekerjaan</th>
                <th>Klien</th>
                <th>Lokasi</th>
                <th>Tanggal pesan</th>
                <th>Mulai kerja</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penyaluran as $i => $o)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $o->kode() }}</td>
                    <td>{{ $o->profilPekerja->pengguna->nama }}</td>
                    <td>{{ $o->profilPekerja->labelKategori() }}</td>
                    <td>{{ $o->klien->nama }}</td>
                    <td>{{ $o->lokasi_kerja ?: $o->profilPekerja->alamat }}</td>
                    <td>{{ $o->tanggal_pemesanan->format('d/m/Y') }}</td>
                    <td>{{ $o->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $o->labelStatus() }}</td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center">Data penyaluran tidak tersedia.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd">
        <div>
            <p>Tangerang, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-top:60px"><b>{{ auth()->user()->nama }}</b><br>Admin Penyaluran</p>
        </div>
    </div>

    <script>window.addEventListener('load', () => setTimeout(() => window.print(), 300));</script>
</body>
</html>
