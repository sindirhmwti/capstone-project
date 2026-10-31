@extends('layouts.worker')
@section('title', 'Edit Profil')
@section('content')

@php $input = 'w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm text-[#172B23] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#176B4D] focus:border-transparent'; @endphp

@include('components.page-header', ['crumb' => 'Calon Pekerja / Edit Profil', 'title' => 'Edit Profil'])

<form method="POST" action="{{ route('worker.profile.update') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6" novalidate>
    @csrf
    @method('PUT')

    <div class="flex items-center gap-4 pb-5 border-b border-gray-100">
        <div class="w-12 h-12 rounded-full bg-[#E4F2E8] flex items-center justify-center font-bold text-[#176B4D]">{{ $pengguna->inisial() }}</div>
        <div>
            <p class="font-semibold text-[#172B23]">Informasi pribadi</p>
            <p class="text-xs text-[#52645B]">Lengkapi data Anda dengan informasi yang sesuai. Kolom bertanda <span class="text-red-500">*</span> wajib diisi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1.5" for="nama">Nama lengkap <span class="text-red-500">*</span></label>
            <input id="nama" name="nama" value="{{ old('nama', $pengguna->nama) }}" class="{{ $input }}">
            @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" for="email">Email <span class="text-red-500">*</span></label>
            <input id="email" type="email" name="email" value="{{ old('email', $pengguna->email) }}" class="{{ $input }}">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" for="no_telepon">Nomor telepon <span class="text-red-500">*</span></label>
            <input id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $pengguna->no_telepon) }}" class="{{ $input }}">
            @error('no_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" for="alamat">Lokasi / domisili <span class="text-red-500">*</span></label>
            <input id="alamat" name="alamat" value="{{ old('alamat', $profil->alamat) }}" placeholder="Contoh: Jakarta Selatan" class="{{ $input }}">
            @error('alamat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" for="tanggal_lahir">Tanggal lahir <span class="text-red-500">*</span></label>
            <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $profil->tanggal_lahir?->toDateString()) }}" class="{{ $input }}">
            @error('tanggal_lahir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" for="jenis_kelamin">Jenis kelamin <span class="text-red-500">*</span></label>
            <select id="jenis_kelamin" name="jenis_kelamin" class="{{ $input }}">
                <option value="">— Pilih —</option>
                @foreach (['Laki-laki', 'Perempuan'] as $jk)
                    <option value="{{ $jk }}" @selected(old('jenis_kelamin', $profil->jenis_kelamin) === $jk)>{{ $jk }}</option>
                @endforeach
            </select>
            @error('jenis_kelamin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" for="keterampilan">Keterampilan <span class="text-red-500">*</span></label>
            <input id="keterampilan" name="keterampilan" value="{{ old('keterampilan', $profil->keterampilan) }}" placeholder="Pisahkan dengan koma, contoh: Memasak, Mencuci" class="{{ $input }}">
            @error('keterampilan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1.5" for="lama_pengalaman">Lama pengalaman kerja {{ $profil->labelKategori() }} (tahun) <span class="text-red-500">*</span></label>
            <input id="lama_pengalaman" type="number" min="0" max="50" name="lama_pengalaman" value="{{ old('lama_pengalaman', $profil->lama_pengalaman) }}" class="{{ $input }}">
            @error('lama_pengalaman') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1.5" for="deskripsi_profil">Pengalaman &amp; informasi pendukung</label>
            <textarea id="deskripsi_profil" name="deskripsi_profil" rows="4" placeholder="Ceritakan pengalaman dan keahlian Anda" class="{{ $input }}">{{ old('deskripsi_profil', $profil->deskripsi_profil) }}</textarea>
            @error('deskripsi_profil') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    @include('components.form-kata-sandi')

    <div class="flex justify-end gap-3">
        <a href="{{ route('worker.profile') }}" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-gray-50">Batal</a>
        <button type="submit" class="px-5 py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Simpan Perubahan</button>
    </div>
</form>

@endsection
