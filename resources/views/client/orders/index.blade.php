@extends('layouts.client')
@section('title', 'Riwayat Pesanan')
@section('content')

@include('components.page-header', ['crumb' => 'Klien / Riwayat Pesanan', 'title' => 'Riwayat Pesanan', 'subtitle' => 'Pantau status setiap pesanan yang pernah Anda ajukan.'])

@include('components.tab-status', ['route' => 'client.orders'])

<div class="card">
    @if ($pesanan->isEmpty())
        <x-empty-state illustration="pesanan" title="Belum terdapat riwayat pesanan{{ $status !== 'semua' ? ' dengan status ini' : '' }}."
            message="Cari pekerja yang sesuai lalu ajukan pemesanan dari halaman profilnya."
            :action="route('client.workers')" action-label="Cari pekerja" />
    @else
        {{-- Tampilan kartu untuk HP --}}
        <div class="divide-y divide-line md:hidden">
            @foreach ($pesanan as $o)
                <a href="{{ route('client.orders.show', $o) }}" class="flex items-start gap-3 p-4 hover:bg-canvas">
                    <x-avatar :name="$o->profilPekerja->pengguna->nama" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-semibold text-ink">{{ $o->profilPekerja->pengguna->nama }}</p>
                        <p class="text-sm text-muted">{{ $o->profilPekerja->labelKategori() }} · {{ $o->kode() }}</p>
                        <p class="mt-1 text-xs text-muted">Diajukan {{ $o->tanggal_pemesanan->format('d/m/Y') }} · Mulai {{ $o->tanggal_mulai?->format('d/m/Y') ?? '-' }}</p>
                    </div>
                    <x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge>
                </a>
            @endforeach
        </div>

        {{-- Tampilan tabel untuk tablet / laptop --}}
        <div class="hidden p-2 md:block">
            <table class="table-base">
                <thead>
                    <tr><th>Pekerja</th><th>Jenis pekerjaan</th><th>Tanggal pengajuan</th><th>Jadwal mulai</th><th>Status</th><th class="text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($pesanan as $o)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-avatar :name="$o->profilPekerja->pengguna->nama" size="sm" />
                                    <div>
                                        <p class="font-semibold text-ink">{{ $o->profilPekerja->pengguna->nama }}</p>
                                        <p class="text-xs text-muted">{{ $o->kode() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $o->profilPekerja->labelKategori() }}</td>
                            <td class="text-muted">{{ $o->tanggal_pemesanan->format('d/m/Y') }}</td>
                            <td class="text-muted">{{ $o->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                            <td><x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge></td>
                            <td class="text-right"><a href="{{ route('client.orders.show', $o) }}" class="btn-ghost btn-sm">Lihat detail</a></td>
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
