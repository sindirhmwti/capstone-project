@extends('layouts.client')
@section('title', 'Detail Pesanan')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Riwayat Pesanan / Detail',
    'title' => 'Pesanan ' . $pemesanan->kode(),
    'subtitle' => 'Diajukan ' . $pemesanan->tanggal_pemesanan->translatedFormat('d F Y, H:i'),
    'aksi' => '<a href="' . route('client.orders') . '" class="btn-secondary">Riwayat Pesanan</a>',
])

<div class="grid gap-6 xl:grid-cols-3">
    <div class="space-y-6 xl:col-span-2">
        <div class="card card-body">
            <div class="mb-5 flex items-center justify-between gap-3">
                <h2 class="card-title">Informasi pesanan</h2>
                <x-badge :status="$pemesanan->status_pemesanan">{{ $pemesanan->labelStatus() }}</x-badge>
            </div>
            <dl class="divide-y divide-line text-[15px]">
                @foreach ([
                    ['Jenis pekerjaan', $pemesanan->profilPekerja->labelKategori()],
                    ['Jadwal mulai', $pemesanan->tanggal_mulai?->translatedFormat('d F Y') ?? '-'],
                    ['Lokasi pekerjaan', $pemesanan->lokasi_kerja ?: '-'],
                ] as [$label, $nilai])
                    <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">{{ $label }}</dt><dd class="font-medium text-ink">{{ $nilai }}</dd></div>
                @endforeach
                <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">Keterangan</dt><dd class="whitespace-pre-line text-ink">{{ $pemesanan->catatan_klien ?: '-' }}</dd></div>
                <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]">
                    <dt class="text-muted">Kriteria diminta</dt>
                    <dd class="flex flex-wrap gap-1.5">
                        @forelse ($pemesanan->kriteria as $k)
                            <span class="inline-flex items-center gap-1 rounded-full bg-primary-soft px-3 py-1 text-sm font-semibold text-primary-dark"><x-icon name="check" class="h-3.5 w-3.5" stroke="3" /> {{ $k->nama_kriteria }}</span>
                        @empty
                            <span class="text-muted">-</span>
                        @endforelse
                    </dd>
                </div>
                @if ($pemesanan->status_pemesanan === 'dibatalkan')
                    <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">Dibatalkan oleh</dt><dd>{{ $pemesanan->pembatal?->nama ?? '-' }}, {{ $pemesanan->tanggal_pembatalan?->translatedFormat('d F Y H:i') }}</dd></div>
                    <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">Alasan pembatalan</dt><dd>{{ $pemesanan->alasan_pembatalan }}</dd></div>
                @endif
            </dl>
        </div>

        @include('components.riwayat-status')
    </div>

    <aside class="space-y-6 xl:sticky xl:top-28 xl:h-fit">
        <a href="{{ route('client.workers.show', $pemesanan->profilPekerja) }}" class="card card-hover block overflow-hidden"><div class="bg-gradient-to-br from-primary-dark to-primary p-5 text-white">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-mint">Pekerja</p>
            <div class="mt-3 flex items-center gap-3">
                <x-avatar :name="$pemesanan->profilPekerja->pengguna->nama" size="lg" />
                <div class="min-w-0">
                    <p class="truncate text-lg font-extrabold">{{ $pemesanan->profilPekerja->pengguna->nama }}</p>
                    <p class="text-sm text-white/75">{{ $pemesanan->profilPekerja->labelKategori() }}</p>
                </div>
            </div>
            </div>
            <p class="flex items-center justify-between px-5 py-4 text-[15px] font-bold text-primary">Lihat profil pekerja <x-icon name="arrow-right" class="h-5 w-5" /></p>
        </a>

        @if ($pemesanan->bisaDibatalkanKlien())
            <form method="POST" action="{{ route('client.orders.cancel', $pemesanan) }}" class="card space-y-4 border-red-200 p-5 sm:p-6" data-confirm="Batalkan pesanan {{ $pemesanan->kode() }}?">
                @csrf
                <h2 class="card-title flex items-center gap-2 text-red-700"><x-icon name="x-circle" /> Batalkan pesanan</h2>
                <p class="text-sm text-muted">Pesanan masih dapat dibatalkan selama berstatus Menunggu atau Diproses.</p>
                <x-field name="alasan_pembatalan" type="textarea" label="Alasan pembatalan" required placeholder="Tuliskan alasan pembatalan" />
                <button type="submit" class="btn-danger w-full">Batalkan Pesanan</button>
            </form>
        @endif
    </aside>
</div>

@endsection
