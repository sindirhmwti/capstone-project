@extends('layouts.client')
@section('title', 'Beranda')
@section('content')

@include('components.page-header', ['crumb' => 'Klien / Beranda', 'title' => 'Beranda'])

{{-- Sambutan --}}
<div class="reveal relative mb-6 overflow-hidden rounded-2xl bg-primary p-6 sm:p-8">
    <div class="pointer-events-none absolute -bottom-20 -right-10 h-64 w-64 rounded-full bg-white/5"></div>
    <div class="relative grid items-center gap-6 md:grid-cols-[1fr_auto]">
        <div class="text-white">
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-mint">Temukerja · Kebutuhan bertemu keahlian</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Selamat datang, {{ $pengguna->nama }}!</h2>
            <p class="mt-2 max-w-xl text-base text-white/80">Temukan bantuan yang tepat untuk rumah Anda. Pilih kriteria khusus dan dapatkan rekomendasi pekerja dengan skor kecocokan.</p>
            <a href="{{ route('client.workers') }}" class="btn btn-lg mt-5 bg-white text-primary-dark hover:bg-primary-soft"><x-icon name="search" class="h-5 w-5" /> Cari Pekerja Sekarang</a>
        </div>
        <img src="{{ asset('images/illustrations/service-art.svg') }}" alt="" class="hidden h-36 w-auto md:block">
    </div>
</div>

{{-- Ringkasan --}}
<div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-stat icon="heart" label="Favorit" :value="$stats['favorites']" tone="rose" :href="route('client.favorites')" />
    <x-stat icon="clipboard" label="Pesanan aktif" :value="$stats['active_orders']" tone="amber" :href="route('client.orders')" />
    <x-stat icon="clock" label="Riwayat pesanan" :value="$stats['history']" tone="blue" :href="route('client.orders')" />
    <x-stat icon="bell" label="Notifikasi baru" :value="$stats['notifications']" tone="brand" :href="route('client.notifications')" />
</div>

{{-- Rekomendasi --}}
<div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h2 class="text-lg font-bold text-ink">Pekerja untuk Anda</h2>
        <p class="text-sm text-muted">
            {{ $berdasarkanKriteria ? 'Berdasarkan kriteria pencarian atau pesanan terakhir Anda.' : 'Pekerja terverifikasi dengan pengalaman terbanyak. Pilih kriteria di katalog agar rekomendasi lebih tepat.' }}
        </p>
    </div>
    <a href="{{ route('client.workers') }}" class="btn-ghost btn-sm">Lihat semua <x-icon name="arrow-right" class="h-4 w-4" /></a>
</div>
@if ($rekomendasiPekerja->isEmpty())
    <div class="card mb-8"><x-empty-state illustration="kosong" title="Data pekerja belum tersedia." compact /></div>
@else
    <div class="mb-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($rekomendasiPekerja as $item)
            @include('components.worker-card', [
                'profil' => $item['profil'],
                'link' => route('client.workers.show', $item['profil']),
                'skor' => $item['skor'],
                'favorit' => in_array($item['profil']->id_pekerja, $idFavorit),
            ])
        @endforeach
    </div>
@endif

{{-- Pesanan terbaru --}}
<div class="card reveal">
    <div class="flex items-center justify-between border-b border-line px-5 py-4 sm:px-6">
        <h2 class="card-title">Pesanan terbaru</h2>
        <a href="{{ route('client.orders') }}" class="text-sm font-medium text-primary hover:underline">Riwayat pesanan</a>
    </div>
    @forelse ($pesananTerbaru as $o)
        <a href="{{ route('client.orders.show', $o) }}" class="flex items-center gap-4 border-b border-line px-5 py-4 transition-colors last:border-0 hover:bg-canvas sm:px-6">
            <x-avatar :name="$o->profilPekerja->pengguna->nama" />
            <div class="min-w-0 flex-1">
                <p class="truncate font-semibold text-ink">{{ $o->profilPekerja->pengguna->nama }} <span class="font-normal text-muted">· {{ $o->profilPekerja->labelKategori() }}</span></p>
                <p class="text-sm text-muted">{{ $o->kode() }} · {{ $o->tanggal_pemesanan->translatedFormat('d M Y') }}</p>
            </div>
            <x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge>
        </a>
    @empty
        <x-empty-state illustration="pesanan" title="Belum ada pesanan" message="Pesanan yang Anda ajukan akan tampil di sini." :action="route('client.workers')" action-label="Cari pekerja" compact />
    @endforelse
</div>

@endsection
