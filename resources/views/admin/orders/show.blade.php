@extends('layouts.admin')
@section('title', 'Detail Pemesanan')
@section('content')

@php $p = $pemesanan->profilPekerja; @endphp

@include('components.page-header', [
    'crumb' => 'Admin / Kelola Pemesanan / Detail',
    'title' => 'Detail Pemesanan ' . $pemesanan->kode(),
    'aksi' => '<a href="' . route('admin.orders') . '" class="px-4 py-2 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-white">← Kembali</a>',
])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-[#172B23]">Detail permintaan</h3>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $pemesanan->warnaStatus() }}">{{ $pemesanan->labelStatus() }}</span>
            </div>
            <dl class="grid grid-cols-1 sm:grid-cols-[170px_1fr] gap-x-6 gap-y-3 text-sm">
                <dt class="text-[#52645B]">Klien</dt><dd class="font-medium">{{ $pemesanan->klien->nama }} · {{ $pemesanan->klien->email }} · {{ $pemesanan->klien->no_telepon }}</dd>
                <dt class="text-[#52645B]">Tanggal pengajuan</dt><dd>{{ $pemesanan->tanggal_pemesanan->translatedFormat('d F Y, H:i') }}</dd>
                <dt class="text-[#52645B]">Jadwal mulai</dt><dd>{{ $pemesanan->tanggal_mulai?->translatedFormat('d F Y') ?? '-' }}</dd>
                <dt class="text-[#52645B]">Lokasi pekerjaan</dt><dd>{{ $pemesanan->lokasi_kerja ?: '-' }}</dd>
                <dt class="text-[#52645B]">Keterangan klien</dt><dd class="whitespace-pre-line">{{ $pemesanan->catatan_klien ?: '-' }}</dd>
                @if ($pemesanan->status_pemesanan === 'dibatalkan')
                    <dt class="text-[#52645B]">Dibatalkan oleh</dt><dd>{{ $pemesanan->pembatal?->nama ?? '-' }}, {{ $pemesanan->tanggal_pembatalan?->translatedFormat('d F Y H:i') }}</dd>
                    <dt class="text-[#52645B]">Alasan pembatalan</dt><dd>{{ $pemesanan->alasan_pembatalan }}</dd>
                @endif
            </dl>

            <h4 class="font-semibold text-[#172B23] mt-6 mb-2 text-sm">Kesesuaian kriteria yang diminta</h4>
            @if ($pemesanan->kriteria->isEmpty())
                <p class="text-sm text-[#52645B]">Klien tidak memilih kriteria khusus.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($pemesanan->kriteria as $k)
                        @php $ya = ($nilaiKriteria[$k->id_kriteria] ?? null) === 'ya'; @endphp
                        <div class="flex items-center justify-between text-sm px-3 py-2 rounded-lg border {{ $ya ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                            <span>{{ $k->nama_kriteria }}</span>
                            <span class="text-xs font-semibold {{ $ya ? 'text-green-700' : 'text-red-600' }}">{{ $ya ? 'Sesuai' : 'Tidak sesuai' }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @include('components.riwayat-status')
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs text-[#52645B] uppercase tracking-wider">Calon pekerja terkait</p>
            <div class="flex items-center gap-3 mt-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#176B4D] to-[#20A66A] flex items-center justify-center text-white font-bold">{{ $p->pengguna->inisial() }}</div>
                <div>
                    <p class="font-semibold">{{ $p->pengguna->nama }}</p>
                    <p class="text-xs text-[#52645B]">{{ $p->labelKategori() }} · {{ $p->alamat }} · {{ $p->pengguna->no_telepon }}</p>
                </div>
            </div>
            <p class="text-xs mt-3">Verifikasi: <span class="font-semibold px-2 py-0.5 rounded-full {{ $p->warnaStatus() }}">{{ $p->labelStatus() }}</span> · Akun {{ $p->pengguna->status_akun }}</p>
            <a href="{{ route('admin.workers.show', $p) }}" class="block mt-3 text-sm text-[#176B4D] font-medium hover:underline">Lihat detail pekerja →</a>
        </div>

        @if ($pemesanan->statusBerikutnyaAdmin())
            <form method="POST" action="{{ route('admin.orders.status', $pemesanan) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                @csrf
                @method('PATCH')
                <h3 class="font-semibold text-[#172B23]">Tindak lanjut pesanan</h3>
                <div>
                    <label for="status_pemesanan" class="block text-sm font-medium mb-1.5">Ubah status menjadi</label>
                    <select id="status_pemesanan" name="status_pemesanan" class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-white">
                        @foreach ($pemesanan->statusBerikutnyaAdmin() as $s)
                            <option value="{{ $s }}" @selected(old('status_pemesanan') === $s)>{{ \App\Models\Pemesanan::STATUS[$s] }}</option>
                        @endforeach
                    </select>
                    @error('status_pemesanan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="catatan" class="block text-sm font-medium mb-1.5">Catatan / alasan</label>
                    <textarea id="catatan" name="catatan" rows="3" placeholder="Wajib diisi bila ditolak atau dibatalkan" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">{{ old('catatan') }}</textarea>
                    @error('catatan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <button class="w-full py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]" onclick="return confirm('Simpan perubahan status pesanan?')">Simpan Status</button>
                <p class="text-xs text-[#52645B]">Klien (dan pekerja, untuk status disetujui/selesai/dibatalkan) akan menerima notifikasi.</p>
            </form>
        @else
            <div class="bg-[#F5F7F5] rounded-2xl p-5 text-sm text-[#52645B]">Pesanan sudah berstatus final ({{ $pemesanan->labelStatus() }}) dan tidak dapat diubah.</div>
        @endif
    </div>
</div>

@endsection
