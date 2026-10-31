@extends('layouts.admin')
@section('title', 'Verifikasi Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Admin / Verifikasi Pekerja',
    'title' => 'Verifikasi Pekerja',
    'subtitle' => 'Baby Sitter dan Perawat Lansia memerlukan sertifikat pelatihan. ART cukup KTP.',
])

<div class="mb-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
    <nav class="scrollbar-hide -mx-4 flex gap-2 overflow-x-auto px-4 sm:mx-0 sm:flex-wrap sm:px-0" aria-label="Filter status verifikasi">
        @foreach (['menunggu' => 'Menunggu', 'terverifikasi' => 'Terverifikasi', 'belum_lengkap' => 'Belum Lengkap', 'ditolak' => 'Ditolak', 'semua' => 'Semua'] as $kode => $label)
            <a href="{{ route('admin.verification', array_filter(['status' => $kode, 'kategori' => request('kategori')])) }}"
               class="pill shrink-0 {{ $status === $kode ? 'pill-active' : '' }}">
                {{ $label }} <span class="pill-count">{{ $kode === 'semua' ? $jumlah->sum() : ($jumlah[$kode] ?? 0) }}</span>
            </a>
        @endforeach
    </nav>
    <form method="GET" class="lg:w-56">
        <input type="hidden" name="status" value="{{ $status }}">
        <select name="kategori" onchange="this.form.submit()" class="input" aria-label="Filter kategori">
            <option value="">Semua kategori</option>
            @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="card">
    @if ($pekerja->isEmpty())
        <x-empty-state illustration="kosong" title="Tidak ada data pekerja dengan status ini." message="Pilih tab status lain untuk melihat data." />
    @else
        <div class="table-wrap p-2">
            <table class="table-base">
                <thead><tr><th>Nama pekerja</th><th>Kategori</th><th>Tanggal pengajuan</th><th>Status</th><th>Dokumen</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @foreach ($pekerja as $p)
                        @php $tanggal = $p->dokumen->max('tanggal_unggah'); @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-avatar :name="$p->pengguna->nama" size="sm" />
                                    <div>
                                        <p class="font-semibold text-ink">{{ $p->pengguna->nama }}</p>
                                        <p class="text-xs text-muted">{{ $p->cekKelengkapanProfil() ? 'Profil lengkap' : 'Profil belum lengkap' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $p->labelKategori() }}</td>
                            <td class="text-muted">{{ $tanggal ? $tanggal->format('d/m/Y H:i') : '-' }}</td>
                            <td><x-badge :status="$p->status_verifikasi">{{ $p->labelStatus() }}</x-badge></td>
                            <td>
                                <div class="space-y-1">
                                    @foreach ($p->dokumenWajib() as $jenis)
                                        @php $dok = $p->dokumenTerbaru($jenis); @endphp
                                        @if ($dok)
                                            <a href="{{ $dok->url() }}" target="_blank" rel="noopener" class="flex items-center gap-1 text-xs font-medium text-primary hover:underline"><x-icon name="file-text" class="h-3.5 w-3.5" /> {{ $dok->labelJenis() }}</a>
                                        @else
                                            <span class="flex items-center gap-1 text-xs text-red-600"><x-icon name="x-circle" class="h-3.5 w-3.5" /> {{ \App\Models\DokumenPekerja::JENIS[$jenis] }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.verification.show', $p) }}" class="{{ $p->status_verifikasi === 'menunggu' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                                    {{ $p->status_verifikasi === 'menunggu' ? 'Periksa' : 'Lihat detail' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@if ($pekerja->hasPages())
    <div class="mt-6">{{ $pekerja->links() }}</div>
@endif

@endsection
