@extends('layouts.admin')
@section('title', 'Detail Akun Pekerja')
@section('content')

@php $input = 'w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#176B4D]'; @endphp

@include('components.page-header', [
    'crumb' => 'Admin / Kelola Akun Pekerja / Detail',
    'title' => 'Detail Akun Pekerja',
    'aksi' => '<a href="' . route('admin.workers') . '" class="px-4 py-2 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-white">← Kembali</a>',
])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        @include('components.detail-pekerja', ['tampilKontak' => true, 'tampilDokumen' => true])

        {{-- Riwayat pemesanan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-[#172B23] mb-3">Riwayat pemesanan</h3>
            @forelse ($profil->pemesanan->sortByDesc('tanggal_pemesanan') as $o)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0 text-sm">
                    <span>{{ $o->kode() }} · {{ $o->klien->nama }} · {{ $o->tanggal_pemesanan->format('d/m/Y') }}</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->warnaStatus() }}">{{ $o->labelStatus() }}</span>
                </div>
            @empty
                <p class="text-sm text-[#52645B]">Belum ada pemesanan untuk pekerja ini.</p>
            @endforelse
        </div>
    </div>

    <div class="space-y-6">
        {{-- Ubah data akun --}}
        <form method="POST" action="{{ route('admin.workers.update', $profil) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4" novalidate>
            @csrf
            @method('PUT')
            <h3 class="font-semibold text-[#172B23]">Kelola data akun</h3>
            <div>
                <label class="block text-sm font-medium mb-1.5" for="nama">Nama lengkap</label>
                <input id="nama" name="nama" value="{{ old('nama', $profil->pengguna->nama) }}" class="{{ $input }}">
                @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $profil->pengguna->email) }}" class="{{ $input }}">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5" for="no_telepon">Nomor telepon</label>
                <input id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $profil->pengguna->no_telepon) }}" class="{{ $input }}">
                @error('no_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5" for="status_akun">Status akun</label>
                <select id="status_akun" name="status_akun" class="{{ $input }}">
                    <option value="aktif" @selected(old('status_akun', $profil->pengguna->status_akun) === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status_akun', $profil->pengguna->status_akun) === 'nonaktif')>Nonaktif</option>
                </select>
                <p class="text-xs text-[#52645B] mt-1">Akun nonaktif tidak dapat login dan tidak tampil di katalog klien.</p>
            </div>
            <button class="w-full py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Simpan Perubahan</button>
        </form>

        {{-- Verifikasi --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-sm">
            <h3 class="font-semibold text-[#172B23] mb-3">Verifikasi</h3>
            <p><span class="text-[#52645B]">Status:</span> <span class="font-semibold">{{ $profil->labelStatus() }}</span></p>
            @if ($profil->verifikator)
                <p class="mt-1"><span class="text-[#52645B]">Diperiksa oleh:</span> {{ $profil->verifikator->nama }}, {{ $profil->tanggal_verifikasi?->translatedFormat('d F Y H:i') }}</p>
            @endif
            @if ($profil->catatan_verifikasi)
                <p class="mt-1"><span class="text-[#52645B]">Catatan:</span> {{ $profil->catatan_verifikasi }}</p>
            @endif
            @if ($profil->status_verifikasi === 'menunggu')
                <a href="{{ route('admin.verification.show', $profil) }}" class="block text-center mt-4 py-2.5 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600">Periksa Data Pekerja</a>
            @endif
        </div>
    </div>
</div>

@endsection
