@extends('layouts.auth')
@section('title', 'Masuk')
@section('panel-judul', 'Selamat datang kembali di Temukerja.')
@section('tanpa-toast', '1')
@section('panel-teks', 'Masuk untuk mencari pekerja, mengelola profil, atau memproses data penyaluran.')

@section('content')
<p class="eyebrow">Klien · Pekerja · Admin</p>
<h1 class="mt-2 text-3xl font-bold text-ink">Masuk ke akun Anda</h1>
<p class="mt-2 text-base text-muted">Gunakan email dan kata sandi yang sudah terdaftar.</p>

@if (session('success'))
    <div class="mt-6 flex gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        <x-icon name="check-circle" class="mt-0.5 h-5 w-5 text-green-600" /> {{ session('success') }}
    </div>
@endif
@if (session('info'))
    <div class="mt-6 flex gap-3 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">
        <x-icon name="info" class="mt-0.5 h-5 w-5 text-sky-600" /> {{ session('info') }}
    </div>
@endif
@if (session('error'))
    <div class="mt-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" class="mt-0.5 h-5 w-5 text-red-600" /> {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('login.post') }}" class="mt-8 space-y-5">
    @csrf
    <div>
        <label for="email" class="label">Alamat email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="email"
               class="input @error('email') input-error @enderror">
        @error('email') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="label">Kata sandi</label>
        <div class="relative">
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required autocomplete="current-password"
                   class="input pr-12 @error('password') input-error @enderror">
            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg p-2 text-muted hover:text-primary" data-toggle-password="password" aria-label="Tampilkan kata sandi">
                <x-icon name="eye" class="h-5 w-5" data-ikon-lihat />
                <x-icon name="eye-off" class="hidden h-5 w-5" data-ikon-sembunyi />
            </button>
        </div>
        @error('password') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <label class="flex items-center gap-2.5 text-sm text-muted">
        <input type="checkbox" name="remember" class="h-4 w-4 rounded accent-primary"> Ingat saya di perangkat ini
    </label>

    <button type="submit" class="btn-primary btn-lg w-full">Masuk</button>
</form>

<p class="mt-8 text-center text-sm text-muted">
    Belum memiliki akun?
    <a href="{{ route('register') }}" class="font-semibold text-primary hover:text-primary-dark">Daftar sekarang</a>
</p>
@endsection
