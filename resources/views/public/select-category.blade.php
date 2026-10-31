@extends('layouts.auth')
@section('title', 'Pilih Kategori Pekerjaan')
@section('lebar', 'max-w-4xl')
@section('panel-judul', 'Keahlian Anda, peluang baru.')
@section('panel-teks', 'Pilih kategori pekerjaan dan kenali persyaratannya sebelum melengkapi data akun.')

@section('content')
@include('components.langkah-daftar', ['aktif' => 2])

<h1 class="text-3xl font-extrabold text-ink sm:text-4xl">Pilih kategori pekerjaan</h1>
<p class="mt-2 text-lg text-muted">Setiap kategori memiliki persyaratan dokumen yang berbeda.</p>

@php
    $kategori = [
        'art' => ['ART', 'Asisten Rumah Tangga', 'Registrasi biasa', 'Pekerjaan rumah tangga dan bantuan aktivitas rumah sesuai kebutuhan klien.', 'Cukup KTP, tanpa sertifikat profesi', 'layanan-art', 'Asisten rumah tangga memasak di dapur'],
        'baby_sitter' => ['Baby Sitter', 'Pengasuh Anak', 'Verifikasi dokumen', 'Pengasuhan bayi dan anak sesuai kebutuhan keluarga.', 'KTP + sertifikat pelatihan (diperiksa admin)', 'layanan-babysitter', 'Pengasuh menggendong bayi'],
        'perawat_lansia' => ['Perawat Lansia', 'Pendamping Lansia', 'Verifikasi dokumen', 'Pendampingan dan perawatan lansia sesuai kompetensi.', 'KTP + sertifikat pelatihan (diperiksa admin)', 'layanan-lansia', 'Pendamping membantu lansia berjalan'],
    ];
@endphp

<div data-auth-item class="mt-8 grid gap-5 md:grid-cols-3">
    @foreach ($kategori as $kode => [$label, $sub, $tag, $desc, $syarat, $gambar, $alt])
        <a href="{{ route('register.pekerja.form', $kode) }}" class="choice-card group p-5 focus-visible:border-primary focus-visible:outline-none">
            <span class="absolute right-4 top-4 z-10 flex h-8 w-8 items-center justify-center rounded-full border-2 border-line bg-white text-transparent transition-colors group-hover:border-primary group-hover:bg-primary group-hover:text-white group-focus-visible:border-primary group-focus-visible:bg-primary group-focus-visible:text-white">
                <x-icon name="check" class="centang-pop h-4 w-4" stroke="3" />
            </span>
            <div class="-mx-1 -mt-1 overflow-hidden rounded-2xl">
                <img src="{{ asset('images/photos/' . $gambar . '.webp') }}" alt="{{ $alt }}" loading="lazy" width="800" height="533" class="aspect-[4/3] w-full object-cover transition duration-700 group-hover:scale-110">
            </div>
            <span class="mt-4 inline-flex w-fit rounded-full {{ $tag === 'Registrasi biasa' ? 'bg-primary-soft text-primary-dark' : 'bg-sun-soft text-amber-900' }} px-2.5 py-1 text-xs font-bold">{{ $tag }}</span>
            <h2 class="mt-2 text-xl font-extrabold leading-snug text-ink">{{ $label }}</h2>
            <p class="text-sm font-semibold text-muted">{{ $sub }}</p>
            <p class="mt-2 flex-1 text-[15px] text-muted">{{ $desc }}</p>
            <p class="mt-4 flex items-start gap-2 rounded-xl bg-canvas p-3 text-sm font-medium text-ink">
                <x-icon name="file-check" class="h-5 w-5 text-primary" /> {{ $syarat }}
            </p>
            <span class="btn-primary mt-4 w-full px-4">Pilih <x-icon name="arrow-right" class="h-5 w-5" /></span>
        </a>
    @endforeach
</div>

<x-alert type="info" class="mt-6"><strong>Akun aktif tidak berarti terverifikasi.</strong> Profil baru tampil di katalog klien setelah data dan dokumen diperiksa admin.</x-alert>

<div class="mt-8 flex flex-col items-center justify-between gap-3 text-[15px] sm:flex-row">
    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 font-semibold text-muted hover:text-primary"><x-icon name="arrow-left" class="h-5 w-5" /> Ubah jenis akun</a>
    <span class="text-muted">Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-primary">Masuk</a></span>
</div>
@endsection
