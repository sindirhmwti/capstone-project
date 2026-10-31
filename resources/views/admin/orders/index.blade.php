@extends('layouts.admin')
@section('title', 'Kelola Pemesanan')
@section('content')

@include('components.page-header', ['crumb' => 'Admin / Kelola Pemesanan', 'title' => 'Kelola Pemesanan', 'subtitle' => 'Periksa dan tindak lanjuti pengajuan pemesanan dari klien.'])

@include('components.tab-status', ['route' => 'admin.orders', 'extra' => ['cari' => request('cari')]])

<form method="GET" class="card mb-6 flex flex-col gap-3 p-3 sm:flex-row" role="search">
    <input type="hidden" name="status" value="{{ $status }}">
    <label class="relative flex-1">
        <span class="sr-only">Cari nama klien atau pekerja</span>
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-muted" />
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama klien atau pekerja..." class="input pl-11">
    </label>
    <button type="submit" class="btn-primary">Cari</button>
</form>

<div class="card">
    @if ($pesanan->isEmpty())
        <x-empty-state illustration="pesanan" title="Tidak ada pemesanan." message="Pemesanan dengan status atau kata kunci ini belum ada." />
    @else
        <div class="table-wrap p-2">
            <table class="table-base">
                <thead><tr><th>Pesanan</th><th>Klien</th><th>Pekerja</th><th>Diajukan</th><th>Jadwal</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @foreach ($pesanan as $o)
                        <tr>
                            <td class="font-semibold text-ink">{{ $o->kode() }}</td>
                            <td>{{ $o->klien->nama }}</td>
                            <td>{{ $o->profilPekerja->pengguna->nama }}<span class="block text-xs text-muted">{{ $o->profilPekerja->labelKategori() }}</span></td>
                            <td class="text-muted">{{ $o->tanggal_pemesanan->format('d/m/Y H:i') }}</td>
                            <td class="text-muted">{{ $o->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                            <td><x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge></td>
                            <td class="text-right">
                                <a href="{{ route('admin.orders.show', $o) }}" class="{{ $o->statusBerikutnyaAdmin() ? 'btn-primary' : 'btn-secondary' }} btn-sm">{{ $o->statusBerikutnyaAdmin() ? 'Kelola' : 'Detail' }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@if ($pesanan->hasPages())
    <div class="mt-6">{{ $pesanan->links() }}</div>
@endif

@endsection
