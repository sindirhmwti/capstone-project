@extends('layouts.client')
@section('title', 'Detail Pesanan')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Riwayat Pesanan / Detail',
    'title' => 'Pesanan ' . $pemesanan->kode(),
    'subtitle' => 'Diajukan ' . $pemesanan->tanggal_pemesanan->translatedFormat('d F Y, H:i'),
    'aksi' => '<a href="' . route('client.orders') . '" class="btn-secondary">Riwayat Pesanan</a>',
])

<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <div class="card card-body">
            <div class="mb-5 flex items-center justify-between gap-3">
                <h2 class="card-title">Informasi pesanan</h2>
                <x-badge :status="$pemesanan->status_pemesanan">{{ $pemesanan->labelStatus() }}</x-badge>
            </div>
            <dl class="divide-y divide-line text-sm">
                @foreach ([
                    ['Jenis pekerjaan', $pemesanan->profilPekerja->labelKategori()],
                    ['Jadwal mulai', $pemesanan->tanggal_mulai?->translatedFormat('d F Y') ?? '-'],
                    ['Lokasi pekerjaan', $pemesanan->lokasi_kerja ?: '-'],
                ] as [$label, $nilai])
                    <div class="grid gap-1 py-3 sm:grid-cols-[180px_1fr]"><dt class="text-muted">{{ $label }}</dt><dd class="font-medium text-ink">{{ $nilai }}</dd></div>
                @endforeach
                <div class="grid gap-1 py-3 sm:grid-cols-[180px_1fr]"><dt class="text-muted">Keterangan</dt><dd class="whitespace-pre-line text-ink">{{ $pemesanan->catatan_klien ?: '-' }}</dd></div>
                <div class="grid gap-1 py-3 sm:grid-cols-[180px_1fr]">
                    <dt class="text-muted">Kriteria diminta</dt>
                    <dd class="flex flex-wrap gap-1.5">
                        @forelse ($pemesanan->kriteria as $k)
                            <span class="rounded-full bg-primary-soft px-2.5 py-1 text-xs font-medium text-primary-dark">{{ $k->nama_kriteria }}</span>
                        @empty
                            <span class="text-muted">-</span>
                        @endforelse
                    </dd>
                </div>
                @if ($pemesanan->status_pemesanan === 'dibatalkan')
                    <div class="grid gap-1 py-3 sm:grid-cols-[180px_1fr]"><dt class="text-muted">Dibatalkan oleh</dt><dd>{{ $pemesanan->pembatal?->nama ?? '-' }}, {{ $pemesanan->tanggal_pembatalan?->translatedFormat('d F Y H:i') }}</dd></div>
                    <div class="grid gap-1 py-3 sm:grid-cols-[180px_1fr]"><dt class="text-muted">Alasan pembatalan</dt><dd>{{ $pemesanan->alasan_pembatalan }}</dd></div>
                @endif
            </dl>
        </div>

        @include('components.riwayat-status')
    </div>

    <aside class="space-y-6">
        <a href="{{ route('client.workers.show', $pemesanan->profilPekerja) }}" class="card card-hover block p-5">
            <p class="eyebrow">Pekerja</p>
            <div class="mt-3 flex items-center gap-3">
                <x-avatar :name="$pemesanan->profilPekerja->pengguna->nama" size="lg" />
                <div class="min-w-0">
                    <p class="truncate font-bold text-ink">{{ $pemesanan->profilPekerja->pengguna->nama }}</p>
                    <p class="text-sm text-muted">{{ $pemesanan->profilPekerja->labelKategori() }}</p>
                    <p class="mt-1 inline-flex items-center gap-1 text-sm font-medium text-primary">Lihat profil <x-icon name="arrow-right" class="h-4 w-4" /></p>
                </div>
            </div>
        </a>

        @if ($pemesanan->bisaDibatalkanKlien())
            <form method="POST" action="{{ route('client.orders.cancel', $pemesanan) }}" class="card space-y-3 border-red-100 p-5" data-confirm="Batalkan pesanan {{ $pemesanan->kode() }}?">
                @csrf
                <h2 class="card-title text-red-700">Batalkan pesanan</h2>
                <p class="text-sm text-muted">Pesanan masih dapat dibatalkan selama berstatus Menunggu atau Diproses.</p>
                <x-field name="alasan_pembatalan" type="textarea" label="Alasan pembatalan" required placeholder="Tuliskan alasan pembatalan" />
                <button type="submit" class="btn-danger w-full">Batalkan Pesanan</button>
            </form>
        @endif
    </aside>
</div>

@endsection
