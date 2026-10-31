@extends('layouts.admin')
@section('title', 'Kelola Akun Pekerja')
@section('content')

@include('components.page-header', ['crumb' => 'Admin / Kelola Akun Pekerja', 'title' => 'Kelola Akun Pekerja', 'subtitle' => 'Kelola data dan status akun calon pekerja.'])

<form method="GET" class="card mb-6 flex flex-col gap-3 p-3 lg:flex-row lg:items-center" role="search">
    <label class="relative flex-1">
        <span class="sr-only">Cari nama atau email</span>
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-muted" />
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau email..." class="input pl-11">
    </label>
    <select name="kategori" class="input lg:w-48" aria-label="Kategori">
        <option value="">Semua kategori</option>
        @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
            <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="status_akun" class="input lg:w-48" aria-label="Status akun">
        <option value="">Semua status akun</option>
        <option value="aktif" @selected(request('status_akun') === 'aktif')>Aktif</option>
        <option value="nonaktif" @selected(request('status_akun') === 'nonaktif')>Nonaktif</option>
    </select>
    <button type="submit" class="btn-primary">Terapkan</button>
</form>

<div class="card">
    @if ($pekerja->isEmpty())
        <x-empty-state illustration="pencarian" title="Akun pekerja tidak ditemukan." message="Ubah kata kunci atau filter." />
    @else
        <div class="table-wrap p-2">
            <table class="table-base">
                <thead>
                    <tr><th>Nama pekerja</th><th>Identitas akun</th><th>Kategori</th><th>Lokasi</th><th>Verifikasi</th><th>Status</th><th class="text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($pekerja as $p)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-avatar :name="$p->pengguna->nama" size="sm" />
                                    <div>
                                        <p class="font-semibold text-ink">{{ $p->pengguna->nama }}</p>
                                        <p class="text-xs text-muted">PK-{{ str_pad($p->id_pekerja, 3, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $p->pengguna->email }}<span class="block text-xs">{{ $p->pengguna->no_telepon }}</span></td>
                            <td>{{ $p->labelKategori() }}</td>
                            <td>{{ $p->alamat ?: '-' }}</td>
                            <td><x-badge :status="$p->status_verifikasi">{{ $p->labelStatus() }}</x-badge></td>
                            <td><x-badge :status="$p->pengguna->status_akun">{{ ucfirst($p->pengguna->status_akun) }}</x-badge></td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.workers.show', $p) }}" class="btn-ghost btn-sm">Detail</a>
                                    <form method="POST" action="{{ route('admin.workers.status', $p) }}"
                                          data-confirm="{{ $p->pengguna->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $p->pengguna->nama }}?">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-sm {{ $p->pengguna->isAktif() ? 'btn-danger' : 'btn-outline' }}">
                                            {{ $p->pengguna->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
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
