@extends('layouts.auth')
@section('title', 'Masuk')
@section('panel-judul', 'Selamat datang kembali di temukerja.')
@section('tanpa-toast', '1')
@section('panel-foto', 'panel-login')
@section('panel-teks', 'Masuk untuk mencari pekerja, mengelola profil, atau memproses data penyaluran.')

@section('content')
<p class="eyebrow"><span class="h-1.5 w-6 rounded-full bg-primary-accent"></span>Klien · Pekerja</p>
<h1 class="mt-3 text-3xl font-extrabold text-ink sm:text-4xl">Masuk ke akun Anda</h1>
<p class="mt-2 text-lg text-muted">Gunakan email dan kata sandi yang sudah terdaftar.</p>

<div class="mt-6 space-y-3">
    @if (session('success')) <x-alert type="success">{{ session('success') }}</x-alert> @endif
    @if (session('info')) <x-alert type="info">{{ session('info') }}</x-alert> @endif
    @if (session('error')) <x-alert type="danger">{{ session('error') }}</x-alert> @endif
</div>

<form method="POST" action="{{ route('login.post') }}" class="mt-6 space-y-5">
    @csrf
    <x-field name="email" type="email" label="Alamat email" icon="mail" placeholder="nama@email.com" required autofocus autocomplete="email" />
    <x-field name="password" type="password" label="Kata sandi" icon="lock" placeholder="Masukkan kata sandi" required autocomplete="current-password" />

    <label class="flex cursor-pointer items-center gap-3 text-[15px] text-muted">
        <input type="checkbox" name="remember" class="h-5 w-5 rounded accent-primary"> Ingat saya di perangkat ini
    </label>

    <button type="submit" class="btn-primary btn-lg w-full">Masuk <x-icon name="arrow-right" class="h-5 w-5" /></button>
</form>

<div class="mt-8 rounded-2xl bg-mint-soft p-5 text-center">
    <p class="text-[15px] text-muted">Belum memiliki akun?</p>
    <a href="{{ route('register') }}" class="btn-outline mt-3 w-full">Daftar sekarang</a>
</div>
@endsection
