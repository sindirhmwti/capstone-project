@extends('layouts.client')
@section('title', 'Beranda')
@section('content')

@include('components.page-header', ['crumb' => 'Klien / Beranda', 'title' => 'Beranda'])

{{-- Sambutan --}}
<div class="bg-gradient-to-r from-[#176B4D] to-[#104C38] rounded-2xl p-6 sm:p-8 mb-6 flex flex-col sm:flex-row items-center justify-between gap-6">
    <div class="text-white">
        <p class="text-[10px] font-semibold tracking-widest uppercase text-green-200">Temukerja · Kebutuhan bertemu keahlian</p>
        <h1 class="text-2xl sm:text-3xl font-bold mt-2 mb-2">Selamat datang, {{ $pengguna->nama }}!</h1>
        <p class="text-green-100 mb-5 max-w-md">Temukan bantuan yang tepat. Dari kebutuhan rumah hingga tenaga profesional, temukan pekerja yang sesuai bersama Temukerja.</p>
        <a href="{{ route('client.workers') }}" class="inline-flex items-center gap-2 bg-white text-[#176B4D] font-semibold px-5 py-2.5 rounded-xl hover:bg-green-50 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" /></svg>
            Cari Pekerja Sekarang
        </a>
    </div>
    <div class="hidden sm:flex items-center justify-center flex-shrink-0">
        <svg class="w-36 h-36 opacity-20" viewBox="0 0 200 200" fill="none"><circle cx="100" cy="80" r="40" fill="white"/><ellipse cx="100" cy="160" rx="70" ry="40" fill="white"/><circle cx="60" cy="90" r="25" fill="white" opacity="0.6"/><circle cx="140" cy="90" r="25" fill="white" opacity="0.6"/></svg>
    </div>
</div>

{{-- Ringkasan --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach ([
        ['Favorit', $stats['favorites'], 'bg-red-100 text-red-500', route('client.favorites'), 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
        ['Pesanan Aktif', $stats['active_orders'], 'bg-orange-100 text-orange-500', Route::has('client.orders') ? route('client.orders') : '#', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['Riwayat Pesanan', $stats['history'], 'bg-blue-100 text-blue-500', Route::has('client.orders') ? route('client.orders') : '#', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['Notifikasi Baru', $stats['notifications'], 'bg-[#E4F2E8] text-[#176B4D]', Route::has('client.notifications') ? route('client.notifications') : '#', 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.9-4.524M9 11v3.159a2 2 0 01-.595 1.436L7 17h5m-1 4a2 2 0 01-4 0'],
    ] as [$label, $nilai, $warna, $link, $ikon])
        <a href="{{ $link }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3 {{ $warna }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ikon }}" /></svg>
            </div>
            <p class="text-2xl font-bold text-[#172B23]">{{ $nilai }}</p>
            <p class="text-xs text-[#52645B] mt-0.5">{{ $label }}</p>
        </a>
    @endforeach
</div>

{{-- Rekomendasi --}}
<div class="flex items-center justify-between mb-4">
    <div>
        <p class="font-semibold text-[#172B23]">Pekerja untuk Anda</p>
        <p class="text-xs text-[#52645B]">
            {{ $berdasarkanKriteria ? 'Rekomendasi berdasarkan kriteria pencarian / pesanan terakhir Anda (Cosine Similarity).' : 'Pekerja terverifikasi dengan pengalaman terbanyak. Pilih kriteria di katalog untuk rekomendasi yang lebih tepat.' }}
        </p>
    </div>
    <a href="{{ route('client.workers') }}" class="text-xs text-[#176B4D] hover:underline whitespace-nowrap">Lihat semua →</a>
</div>
@if ($rekomendasiPekerja->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-[#52645B]">Data pekerja belum tersedia.</div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-6">
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
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <p class="font-semibold text-[#172B23]">Pesanan terbaru</p>
        @if (Route::has('client.orders'))
            <a href="{{ route('client.orders') }}" class="text-xs text-[#176B4D] hover:underline">Riwayat pesanan →</a>
        @endif
    </div>
    @forelse ($pesananTerbaru as $o)
        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0 text-sm">
            <div>
                <p class="font-medium text-[#172B23]">{{ $o->profilPekerja->pengguna->nama }} <span class="text-xs text-[#52645B]">· {{ $o->profilPekerja->labelKategori() }}</span></p>
                <p class="text-xs text-[#52645B]">{{ $o->kode() }} · {{ $o->tanggal_pemesanan->format('d/m/Y') }}</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->warnaStatus() }}">{{ $o->labelStatus() }}</span>
        </div>
    @empty
        <p class="text-sm text-[#52645B]">Belum terdapat riwayat pesanan.</p>
    @endforelse
</div>

@endsection
