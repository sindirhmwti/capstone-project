@extends($layout)
@section('title', 'Edit Profil')
@section('content')

@php $input = 'w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm text-[#172B23] focus:outline-none focus:ring-2 focus:ring-[#176B4D] focus:border-transparent'; @endphp

@include('components.page-header', ['crumb' => $crumb . ' / Edit Profil', 'title' => 'Edit Profil'])

<form method="POST" action="{{ route($routeUpdate) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6 max-w-4xl" novalidate>
    @csrf
    @method('PUT')

    <div class="flex items-center gap-4 pb-5 border-b border-gray-100">
        <div class="w-12 h-12 rounded-full bg-[#E4F2E8] flex items-center justify-center font-bold text-[#176B4D]">{{ $pengguna->inisial() }}</div>
        <div>
            <p class="font-semibold text-[#172B23]">{{ $pengguna->isAdmin() ? 'Informasi admin' : 'Informasi pribadi' }}</p>
            <p class="text-xs text-[#52645B]">Lengkapi data Anda dengan informasi yang sesuai.</p>
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
            <label class="block text-sm font-medium mb-1.5">Jenis akun</label>
            <input value="{{ $pengguna->isAdmin() ? 'Admin' : 'Klien' }} · terdaftar {{ $pengguna->tanggal_dibuat?->translatedFormat('d F Y') }}" disabled class="{{ $input }} bg-gray-50 text-[#52645B]">
        </div>
    </div>

    @include('components.form-kata-sandi')

    <div class="flex justify-end gap-3">
        <a href="{{ route($pengguna->routeDashboard()) }}" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-gray-50">Batal</a>
        <button type="submit" class="px-5 py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Simpan Perubahan</button>
    </div>
</form>

@endsection
