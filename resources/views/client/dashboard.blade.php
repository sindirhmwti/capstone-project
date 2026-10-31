@extends('layouts.client')
@section('title', 'Beranda')
@section('content')

<x-banner-sambutan eyebrow="temukerja · Kebutuhan bertemu keahlian" :title="'Selamat datang, ' . $pengguna->nama . '!'"
                   text="Temukan bantuan yang tepat untuk rumah Anda. Pilih kriteria khusus dan dapatkan rekomendasi pekerja dengan skor kecocokan."
                   illustration="keluarga">
    <a href="{{ route('client.workers') }}" class="btn-sun btn-lg"><x-icon name="search" class="h-5 w-5" /> Cari Pekerja Sekarang</a>
    <a href="{{ route('client.orders') }}" class="btn-outline-light btn-lg">Riwayat Pesanan</a>
</x-banner-sambutan>

{{-- Ringkasan --}}
<div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
    <x-stat icon="heart" label="Favorit" :value="$stats['favorites']" tone="rose" :href="route('client.favorites')" note="Pekerja tersimpan" />
    <x-stat icon="clipboard" label="Pesanan aktif" :value="$stats['active_orders']" tone="amber" :href="route('client.orders')" note="Menunggu / diproses / disetujui" />
    <x-stat icon="clock" label="Riwayat pesanan" :value="$stats['history']" tone="blue" :href="route('client.orders')" note="Semua pesanan" />
    <x-stat icon="bell" label="Notifikasi baru" :value="$stats['notifications']" tone="brand" :href="route('client.notifications')" note="Belum dibaca" />
</div>

{{-- Aksi cepat --}}
<div class="mb-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ([
        [route('client.workers', ['kategori' => 'art', 'cari_kriteria' => 1]), 'house', 'Cari ART', 'bg-primary-soft text-primary'],
        [route('client.workers', ['kategori' => 'baby_sitter', 'cari_kriteria' => 1]), 'baby', 'Cari Baby Sitter', 'bg-sun-soft text-sun-dark'],
        [route('client.workers', ['kategori' => 'perawat_lansia', 'cari_kriteria' => 1]), 'hand-heart', 'Cari Perawat Lansia', 'bg-sky-50 text-sky-600'],
        [route('client.favorites'), 'heart', 'Lihat Favorit', 'bg-rose-50 text-rose-500'],
    ] as [$href, $ikon, $label, $warna])
        <a href="{{ $href }}" class="reveal group flex items-center gap-4 rounded-2xl border border-line bg-white p-4 transition duration-200 hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-soft">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $warna }}"><x-icon :name="$ikon" class="h-6 w-6" /></span>
            <span class="flex-1 text-[15px] font-bold text-ink">{{ $label }}</span>
            <x-icon name="chevron-right" class="h-5 w-5 text-muted transition-transform group-hover:translate-x-0.5" />
        </a>
    @endforeach
</div>

<div class="grid gap-6 2xl:grid-cols-[1fr_400px]">
    {{-- Rekomendasi --}}
    <section>
        <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-ink">Pekerja untuk Anda</h2>
                <p class="text-[15px] text-muted">
                    {{ $berdasarkanKriteria ? 'Berdasarkan kriteria pencarian atau pesanan terakhir Anda.' : 'Pekerja terverifikasi dengan pengalaman terbanyak. Pilih kriteria di katalog agar rekomendasi lebih tepat.' }}
                </p>
            </div>
            <a href="{{ route('client.workers') }}" class="btn-ghost btn-sm shrink-0">Lihat semua <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </div>
        @if ($rekomendasiPekerja->isEmpty())
            <div class="card"><x-empty-state illustration="kosong" title="Data pekerja belum tersedia." compact /></div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
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
    </section>

    {{-- Pesanan terbaru --}}
    <section class="card reveal h-fit">
        <div class="card-head">
            <h2 class="card-title">Pesanan terbaru</h2>
            <a href="{{ route('client.orders') }}" class="text-sm font-bold text-primary hover:underline">Riwayat</a>
        </div>
        @forelse ($pesananTerbaru as $o)
            <a href="{{ route('client.orders.show', $o) }}" class="flex items-center gap-3.5 border-b border-line px-5 py-4 transition-colors last:border-0 hover:bg-canvas sm:px-6">
                <x-avatar :name="$o->profilPekerja->pengguna->nama" />
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[15px] font-bold text-ink">{{ $o->profilPekerja->pengguna->nama }}</p>
                    <p class="text-sm text-muted">{{ $o->kode() }} · {{ $o->tanggal_pemesanan->diffForHumans() }}</p>
                </div>
                <x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge>
            </a>
        @empty
            <x-empty-state illustration="pesanan" title="Belum ada pesanan" message="Pesanan yang Anda ajukan akan tampil di sini." :action="route('client.workers')" action-label="Cari pekerja" compact />
        @endforelse
    </section>
</div>

@endsection
