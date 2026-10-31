@extends('layouts.auth')
@section('title', 'Pilih Akun')
@section('lebar', 'max-w-2xl')
@section('panel-judul', 'Bergabung bersama Temukerja.')
@section('panel-teks', 'Daftar sebagai klien untuk mencari pekerja, atau sebagai pekerja untuk menawarkan keahlian Anda.')

@section('content')
@include('components.langkah-daftar', ['aktif' => 1])

<h1 class="text-3xl font-bold text-ink">Daftar sebagai apa?</h1>
<p class="mt-2 text-base text-muted">Pilih jenis akun yang sesuai dengan kebutuhan Anda.</p>

@php
    $pilihan = [
        [route('register.klien'), 'search', 'Klien', 'Saya ingin mencari dan memesan pekerja: ART, Baby Sitter, atau Perawat Lansia.', ['Katalog pekerja terverifikasi', 'Rekomendasi sesuai kriteria rumah', 'Pesan & simpan favorit'], 'Daftar sebagai Klien'],
        [route('register.pekerja'), 'briefcase', 'Pekerja', 'Saya ingin menawarkan jasa sebagai ART, Baby Sitter, atau Perawat Lansia.', ['Profil & kriteria khusus', 'Unggah KTP & sertifikat', 'Pantau status verifikasi'], 'Daftar sebagai Pekerja'],
    ];
@endphp

<div class="mt-8 grid gap-4 sm:grid-cols-2">
    @foreach ($pilihan as [$href, $ikon, $judul, $teks, $poin, $tombol])
        <a href="{{ $href }}" class="card card-hover group flex flex-col p-6">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-soft text-primary transition-colors group-hover:bg-primary group-hover:text-white"><x-icon :name="$ikon" class="h-6 w-6" /></span>
            <h2 class="mt-4 text-xl font-bold text-ink">{{ $judul }}</h2>
            <p class="mt-1.5 text-sm text-muted">{{ $teks }}</p>
            <ul class="mt-4 flex-1 space-y-2 text-sm text-ink">
                @foreach ($poin as $p)
                    <li class="flex items-center gap-2"><x-icon name="check" class="h-4 w-4 text-primary-accent" /> {{ $p }}</li>
                @endforeach
            </ul>
            <span class="btn-primary mt-6 w-full">{{ $tombol }} <x-icon name="arrow-right" class="h-4 w-4" /></span>
        </a>
    @endforeach
</div>

<p class="mt-8 text-center text-sm text-muted">
    Sudah memiliki akun? <a href="{{ route('login') }}" class="font-semibold text-primary hover:text-primary-dark">Masuk</a>
</p>
@endsection
