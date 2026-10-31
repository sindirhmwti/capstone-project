@extends('layouts.client')
@section('title', 'Detail Pesanan')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Riwayat Pesanan / Detail',
    'title' => 'Detail Pesanan ' . $pemesanan->kode(),
    'aksi' => '<a href="' . route('client.orders') . '" class="px-4 py-2 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-white">← Riwayat Pesanan</a>',
])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-[#172B23]">Informasi pesanan</h3>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $pemesanan->warnaStatus() }}">{{ $pemesanan->labelStatus() }}</span>
            </div>
            <dl class="grid grid-cols-1 sm:grid-cols-[160px_1fr] gap-x-6 gap-y-3 text-sm">
                <dt class="text-[#52645B]">Pekerja</dt><dd class="font-medium">{{ $pemesanan->profilPekerja->pengguna->nama }}</dd>
                <dt class="text-[#52645B]">Jenis pekerjaan</dt><dd>{{ $pemesanan->profilPekerja->labelKategori() }}</dd>
                <dt class="text-[#52645B]">Tanggal pengajuan</dt><dd>{{ $pemesanan->tanggal_pemesanan->translatedFormat('d F Y, H:i') }}</dd>
                <dt class="text-[#52645B]">Jadwal mulai</dt><dd>{{ $pemesanan->tanggal_mulai?->translatedFormat('d F Y') ?? '-' }}</dd>
                <dt class="text-[#52645B]">Lokasi pekerjaan</dt><dd>{{ $pemesanan->lokasi_kerja ?: '-' }}</dd>
                <dt class="text-[#52645B]">Keterangan</dt><dd class="whitespace-pre-line">{{ $pemesanan->catatan_klien ?: '-' }}</dd>
                <dt class="text-[#52645B]">Kriteria diminta</dt>
                <dd class="flex flex-wrap gap-1.5">
                    @forelse ($pemesanan->kriteria as $k)
                        <span class="text-xs px-2 py-0.5 rounded-md bg-[#E4F2E8] text-[#176B4D]">{{ $k->nama_kriteria }}</span>
                    @empty
                        -
                    @endforelse
                </dd>
                @if ($pemesanan->status_pemesanan === 'dibatalkan')
                    <dt class="text-[#52645B]">Dibatalkan oleh</dt><dd>{{ $pemesanan->pembatal?->nama ?? '-' }}, {{ $pemesanan->tanggal_pembatalan?->translatedFormat('d F Y H:i') }}</dd>
                    <dt class="text-[#52645B]">Alasan pembatalan</dt><dd>{{ $pemesanan->alasan_pembatalan }}</dd>
                @endif
            </dl>
        </div>

        @include('components.riwayat-status')
    </div>

    <div class="space-y-6">
        <a href="{{ route('client.workers.show', $pemesanan->profilPekerja) }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md">
            <p class="text-xs text-[#52645B] uppercase tracking-wider">Pekerja</p>
            <div class="flex items-center gap-3 mt-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#176B4D] to-[#20A66A] flex items-center justify-center text-white font-bold">{{ $pemesanan->profilPekerja->pengguna->inisial() }}</div>
                <div>
                    <p class="font-semibold">{{ $pemesanan->profilPekerja->pengguna->nama }}</p>
                    <p class="text-xs text-[#52645B]">{{ $pemesanan->profilPekerja->labelKategori() }} · Lihat profil ↗</p>
                </div>
            </div>
        </a>

        @if ($pemesanan->bisaDibatalkanKlien())
            <form method="POST" action="{{ route('client.orders.cancel', $pemesanan) }}" class="bg-white rounded-2xl border border-red-100 shadow-sm p-6 space-y-3"
                  onsubmit="return confirm('Batalkan pesanan {{ $pemesanan->kode() }}?')">
                @csrf
                <h3 class="font-semibold text-red-700">Batalkan pesanan</h3>
                <textarea name="alasan_pembatalan" rows="3" placeholder="Tuliskan alasan pembatalan" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-300">{{ old('alasan_pembatalan') }}</textarea>
                @error('alasan_pembatalan') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                <button class="w-full py-2.5 border border-red-300 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50">Batalkan Pesanan</button>
            </form>
        @endif
    </div>
</div>

@endsection
