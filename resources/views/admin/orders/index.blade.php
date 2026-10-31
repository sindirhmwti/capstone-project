@extends('layouts.admin')
@section('title', 'Kelola Pemesanan')
@section('content')

@include('components.page-header', ['crumb' => 'Admin / Kelola Pemesanan', 'title' => 'Kelola Pemesanan', 'subtitle' => 'Periksa dan tindak lanjuti pengajuan pemesanan dari klien.'])

@include('components.tab-status', ['route' => 'admin.orders', 'extra' => ['cari' => request('cari')]])

<div class="card overflow-hidden">
<form method="GET" class="flex flex-col gap-3 border-b border-line p-4 sm:flex-row sm:p-5" role="search">
    <input type="hidden" name="status" value="{{ $status }}">
    <label class="relative flex-1">
        <span class="sr-only">Cari nama klien atau pekerja</span>
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-muted" />
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama klien atau pekerja..." class="input pl-11">
    </label>
    <button type="submit" class="btn-primary"><x-icon name="search" class="h-5 w-5" /> Cari</button>
</form>

<div>
    @if ($pesanan->isEmpty())
        <x-empty-state illustration="pesanan" title="Tidak ada pemesanan." message="Pemesanan dengan status atau kata kunci ini belum ada." />
    @else
        <div class="table-wrap">
            <table class="table-base">
                <thead><tr><th>Pesanan</th><th>Klien</th><th>Pekerja</th><th>Diajukan</th><th>Jadwal</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @foreach ($pesanan as $o)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $o) }}" class="font-bold text-primary hover:underline">{{ $o->kode() }}</a></td>
                            <td>{{ $o->klien->nama }}</td>
                            <td>{{ $o->profilPekerja->pengguna->nama }}<span class="block text-xs text-muted">{{ $o->profilPekerja->labelKategori() }}</span></td>
                            <td class="text-muted"><span title="{{ $o->tanggal_pemesanan->format('d/m/Y H:i') }}">{{ $o->tanggal_pemesanan->format('d/m/Y') }}</span><span class="block text-xs">{{ $o->tanggal_pemesanan->diffForHumans() }}</span></td>
                            <td class="text-muted">{{ $o->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                            <td><x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge></td>
                            <td class="text-right">
                                @if ($o->statusBerikutnyaAdmin())
                                    <a href="{{ route('admin.orders.show', $o) }}" class="btn-primary btn-sm"><x-icon name="pencil" class="h-4 w-4" /> Kelola</a>
                                @else
                                    <a href="{{ route('admin.orders.show', $o) }}" class="icon-btn" data-tip="Lihat detail" aria-label="Lihat detail {{ $o->kode() }}"><x-icon name="eye" class="h-5 w-5" /></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
</div>
@if ($pesanan->hasPages())
    <div class="mt-6">{{ $pesanan->links() }}</div>
@endif

@endsection
