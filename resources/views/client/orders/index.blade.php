@extends('layouts.client')
@section('title', 'Riwayat Pesanan')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Riwayat Pesanan',
    'title' => 'Riwayat Pesanan',
    'subtitle' => 'Pantau status setiap pesanan yang pernah Anda ajukan.',
    'aksi' => '<a href="' . route('client.workers') . '" class="btn-primary">Pesan pekerja baru</a>',
])

@include('components.tab-status', ['route' => 'client.orders'])

@php
    $ikonStatus = [
        'menunggu' => ['hourglass', 'bg-sun-soft text-sun-dark'],
        'diproses' => ['clock', 'bg-sky-100 text-sky-600'],
        'disetujui' => ['check-circle', 'bg-green-100 text-green-600'],
        'selesai' => ['award', 'bg-slate-200 text-slate-700'],
        'ditolak' => ['x-circle', 'bg-red-100 text-red-600'],
        'dibatalkan' => ['x-circle', 'bg-red-100 text-red-600'],
    ];
@endphp

@php
    $pesanan->getCollection()->loadMissing('jadwalInterview');
    $menungguKonfirmasi = $pesanan->getCollection()->filter(fn ($o) => $o->status_pemesanan === 'disetujui' && in_array($o->jadwalInterview?->konfirmasi('klien'), ['menunggu', 'tidak_merespons'], true));
@endphp
@if ($menungguKonfirmasi->isNotEmpty())
    <x-alert type="warning" title="Undangan interview via WhatsApp" class="mb-5">
        Pesanan Anda telah disetujui. Silakan cek WhatsApp Anda dan balas pesan dari admin untuk pesanan
        @foreach ($menungguKonfirmasi as $o)<a href="{{ route('client.orders.show', $o) }}" class="font-bold underline underline-offset-2">{{ $o->kode() }}</a>{{ $loop->last ? '' : ', ' }}@endforeach.
    </x-alert>
@endif

@if ($pesanan->isEmpty())
    <div class="card">
        <x-empty-state illustration="pesanan" title="Belum terdapat riwayat pesanan{{ $status !== 'semua' ? ' dengan status ini' : '' }}."
            message="Cari pekerja yang sesuai lalu ajukan pemesanan dari halaman profilnya."
            :action="route('client.workers')" action-label="Cari pekerja" />
    </div>
@else
    <div class="grid gap-4 lg:grid-cols-2">
        @foreach ($pesanan as $o)
            @php [$ikon, $warna] = $ikonStatus[$o->status_pemesanan] ?? ['clipboard', 'bg-canvas text-muted']; @endphp
            <a href="{{ route('client.orders.show', $o) }}" class="reveal card card-hover group flex flex-col gap-4 rounded-3xl p-5 sm:flex-row sm:items-center">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $warna }}"><x-icon :name="$ikon" class="h-7 w-7" /></span>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="truncate text-lg font-extrabold text-ink">{{ $o->profilPekerja->pengguna->nama }}</p>
                        <x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge>
                    </div>
                    <p class="mt-0.5 text-[15px] text-muted">{{ $o->profilPekerja->labelKategori() }} · <span class="font-semibold text-ink">{{ $o->kode() }}</span></p>
                    <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-muted">
                        <span class="flex items-center gap-1.5" title="{{ $o->tanggal_pemesanan->translatedFormat('d F Y H:i') }}"><x-icon name="clock" class="h-4 w-4" /> Diajukan {{ $o->tanggal_pemesanan->diffForHumans() }}</span>
                        <span class="flex items-center gap-1.5"><x-icon name="calendar" class="h-4 w-4" /> Mulai {{ $o->tanggal_mulai?->translatedFormat('d M Y') ?? '-' }}</span>
                    </div>
                    @if ($o->status_pemesanan === 'disetujui' && $o->jadwalInterview)
                        @php $ki = $o->jadwalInterview->konfirmasi('klien'); @endphp
                        <p class="mt-2 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ ['hadir' => 'bg-green-100 text-green-800', 'minta_ubah' => 'bg-sky-100 text-sky-800'][$ki] ?? 'bg-sun-soft text-amber-900' }}">
                            @if ($ki === 'hadir')<x-icon name="check-circle" class="h-3.5 w-3.5" />@else<x-ikon-whatsapp class="h-3.5 w-3.5" />@endif
                            {{ ['hadir' => 'Interview: kehadiran dikonfirmasi', 'minta_ubah' => 'Interview: permintaan ubah jadwal ditinjau'][$ki] ?? 'Undangan interview via WhatsApp · cek WhatsApp Anda' }}
                        </p>
                    @endif
                </div>
                <span class="hidden h-10 w-10 items-center justify-center rounded-full bg-canvas text-muted transition-colors group-hover:bg-primary group-hover:text-white sm:flex"><x-icon name="arrow-right" class="h-5 w-5" /></span>
            </a>
        @endforeach
    </div>
@endif
@if ($pesanan->hasPages())
    <div class="mt-6">{{ $pesanan->links() }}</div>
@endif

@endsection
