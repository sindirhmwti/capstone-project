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

<div class="card overflow-hidden">
    @if ($pekerja->isEmpty())
        <x-empty-state illustration="kosong" title="Tidak ada data pekerja dengan status ini." message="Pilih tab status lain untuk melihat data." />
    @else
        <div class="table-wrap">
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
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($p->dokumenWajib() as $jenis)
                                        @php $dok = $p->dokumenTerbaru($jenis); @endphp
                                        @if ($dok)
                                            <a href="{{ $dok->url() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-full bg-primary-soft px-2.5 py-1 text-xs font-bold text-primary-dark hover:bg-mint"><x-icon name="file-text" class="h-3.5 w-3.5" /> {{ $dok->labelJenis() }}</a>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-bold text-red-700"><x-icon name="x-circle" class="h-3.5 w-3.5" /> {{ \App\Models\DokumenPekerja::JENIS[$jenis] }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td class="text-right">
                                @if ($p->status_verifikasi === 'menunggu')
                                    <a href="{{ route('admin.verification.show', $p) }}" class="btn-primary btn-sm"><x-icon name="shield-check" class="h-4 w-4" /> Periksa</a>
                                @else
                                    <a href="{{ route('admin.verification.show', $p) }}" class="icon-btn" data-tip="Lihat detail" aria-label="Lihat detail {{ $p->pengguna->nama }}"><x-icon name="eye" class="h-5 w-5" /></a>
                                @endif
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
