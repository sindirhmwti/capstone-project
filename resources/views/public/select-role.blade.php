@extends('layouts.auth')
@section('title', 'Pilih Akun')
@section('lebar', 'max-w-3xl')
@section('panel-judul', 'Bergabung bersama temukerja.')
@section('panel-teks', 'Daftar sebagai klien untuk mencari pekerja, atau sebagai pekerja untuk menawarkan keahlian Anda.')

@section('content')
@include('components.langkah-daftar', ['aktif' => 1])

<h1 class="text-3xl font-extrabold text-ink sm:text-4xl">Daftar sebagai apa?</h1>
<p class="mt-2 text-lg text-muted">Pilih jenis akun yang sesuai dengan kebutuhan Anda.</p>

@php
    $pilihan = [
        [route('register.klien'), 'untuk-keluarga', 'Ibu memeluk anaknya di rumah', 'Klien', 'Saya ingin mencari dan memesan pekerja: ART, Baby Sitter, atau Perawat Lansia.', ['Katalog pekerja terverifikasi', 'Rekomendasi sesuai kriteria rumah', 'Pesan & simpan favorit'], 'Daftar sebagai Klien'],
        [route('register.pekerja'), 'untuk-pekerja', 'Perempuan tersenyum menggunakan laptop di ruangan penuh tanaman', 'Calon Pekerja', 'Saya ingin menawarkan jasa sebagai ART, Baby Sitter, atau Perawat Lansia.', ['Profil & kriteria khusus', 'Unggah KTP & sertifikat', 'Pantau status verifikasi'], 'Daftar sebagai Pekerja'],
    ];
@endphp

<div data-auth-item class="mt-8 grid gap-5 sm:grid-cols-2">
    @foreach ($pilihan as [$href, $gambar, $alt, $judul, $teks, $poin, $tombol])
        <a href="{{ $href }}" class="choice-card group focus-visible:border-primary focus-visible:outline-none">
            <span class="absolute right-5 top-5 z-10 flex h-8 w-8 items-center justify-center rounded-full border-2 border-line bg-white text-transparent transition-colors group-hover:border-primary group-hover:bg-primary group-hover:text-white group-focus-visible:border-primary group-focus-visible:bg-primary group-focus-visible:text-white">
                <x-icon name="check" class="centang-pop h-4 w-4" stroke="3" />
            </span>
            <div class="-mx-2 -mt-2 overflow-hidden rounded-2xl">
                <img src="{{ asset('images/photos/' . $gambar . '.webp') }}" alt="{{ $alt }}" loading="lazy" width="1000" height="667" class="aspect-[16/10] w-full object-cover transition duration-700 group-hover:scale-110">
            </div>
            <h2 class="mt-5 text-2xl font-extrabold text-ink">{{ $judul }}</h2>
            <p class="mt-2 text-[15px] text-muted">{{ $teks }}</p>
            <ul class="mt-4 flex-1 space-y-2.5 text-[15px] font-medium text-ink">
                @foreach ($poin as $p)
                    <li class="flex items-center gap-2.5"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-primary-soft text-primary"><x-icon name="check" class="h-3.5 w-3.5" stroke="3" /></span> {{ $p }}</li>
                @endforeach
            </ul>
            <span class="btn-primary mt-6 w-full">{{ $tombol }} <x-icon name="arrow-right" class="h-5 w-5" /></span>
        </a>
    @endforeach
</div>

<p class="mt-8 text-center text-[15px] text-muted">
    Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary-dark">Masuk</a>
</p>
@endsection
