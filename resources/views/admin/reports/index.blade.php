@extends('layouts.admin')
@section('title', 'Laporan Penyaluran')
@section('content')

@include('components.page-header', [
    'crumb' => 'Admin / Laporan Penyaluran',
    'title' => 'Laporan Penyaluran',
    'subtitle' => 'Penyaluran dihitung dari pemesanan berstatus Disetujui dan Selesai.',
    'aksi' => $penyaluran->isEmpty()
        ? '<span class="btn-primary opacity-50 cursor-not-allowed" aria-disabled="true">Cetak Laporan</span>'
        : '<a href="' . route('admin.reports.print', request()->only('periode', 'kategori')) . '" target="_blank" rel="noopener" class="btn-primary"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg> Cetak Laporan</a>',
])

{{-- Filter periode --}}
<form method="GET" class="card mb-6 flex flex-col gap-3 p-3 sm:flex-row sm:items-end">
    <div class="sm:w-56">
        <label for="periode" class="label">Periode laporan</label>
        <input type="month" id="periode" name="periode" value="{{ $periode }}" class="input">
    </div>
    <div class="sm:w-56">
        <label for="kategori" class="label">Kategori</label>
        <select id="kategori" name="kategori" class="input">
            <option value="">Semua kategori</option>
            @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn-outline">Tampilkan</button>
    @if ($periode || request('kategori'))
        <a href="{{ route('admin.reports') }}" class="btn-ghost">Semua periode</a>
    @endif
</form>

{{-- Ringkasan --}}
<div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-5">
    <div class="reveal col-span-2 rounded-2xl bg-primary p-5 text-white lg:col-span-1">
        <p class="text-sm text-white/80">Total penyaluran</p>
        <p class="mt-1 text-3xl font-extrabold" data-count="{{ $penyaluran->count() }}">{{ $penyaluran->count() }}</p>
        <p class="mt-0.5 text-xs text-mint">{{ $labelPeriode }}</p>
    </div>
    @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
        <div class="card reveal p-5">
            <p class="text-sm text-muted">{{ $label }}</p>
            <p class="mt-1 text-2xl font-extrabold text-ink" data-count="{{ $perKategori[$kode] ?? 0 }}">{{ $perKategori[$kode] ?? 0 }}</p>
        </div>
    @endforeach
    <div class="card reveal p-5">
        <p class="text-sm text-muted">Selesai</p>
        <p class="mt-1 text-2xl font-extrabold text-ink">{{ $perStatus['selesai'] ?? 0 }}</p>
        <p class="text-xs text-muted">{{ $perStatus['disetujui'] ?? 0 }} masih berjalan</p>
    </div>
</div>

<div class="card">
    @if ($penyaluran->isEmpty())
        <x-empty-state illustration="kosong" title="Data penyaluran tidak tersedia untuk {{ strtolower($labelPeriode) }}." message="Pilih periode atau kategori lain." />
    @else
        <div class="table-wrap p-2">
            <table class="table-base">
                <thead><tr><th>Nama pekerja</th><th>Jenis pekerjaan</th><th>Klien</th><th>Lokasi</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @foreach ($penyaluran as $o)
                        <tr>
                            <td><p class="font-semibold text-ink">{{ $o->profilPekerja->pengguna->nama }}</p><p class="text-xs text-muted">{{ $o->kode() }}</p></td>
                            <td>{{ $o->profilPekerja->labelKategori() }}</td>
                            <td>{{ $o->klien->nama }}</td>
                            <td>{{ $o->lokasi_kerja ?: $o->profilPekerja->alamat }}</td>
                            <td><x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge></td>
                            <td class="text-muted">{{ $o->tanggal_pemesanan->translatedFormat('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
