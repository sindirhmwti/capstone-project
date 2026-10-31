@extends('layouts.auth')
@section('title', 'Pilih Kategori Pekerjaan')
@section('lebar', 'max-w-3xl')
@section('panel-judul', 'Keahlian Anda, peluang baru.')
@section('panel-teks', 'Pilih kategori pekerjaan dan kenali persyaratannya sebelum melengkapi data akun.')

@section('content')
@include('components.langkah-daftar', ['aktif' => 2])

<h1 class="text-3xl font-bold text-ink">Pilih kategori pekerjaan</h1>
<p class="mt-2 text-base text-muted">Setiap kategori memiliki persyaratan dokumen yang berbeda.</p>

@php
    $kategori = [
        'art' => ['ART (Asisten Rumah Tangga)', 'Registrasi biasa', 'Pekerjaan rumah tangga dan bantuan aktivitas rumah sesuai kebutuhan klien.', 'Cukup KTP, tanpa sertifikat profesi', 'service-art'],
        'baby_sitter' => ['Baby Sitter', 'Verifikasi dokumen', 'Pengasuhan bayi dan anak sesuai kebutuhan keluarga.', 'KTP + sertifikat pelatihan (diperiksa admin)', 'service-babysitter'],
        'perawat_lansia' => ['Perawat Lansia', 'Verifikasi dokumen', 'Pendampingan dan perawatan lansia sesuai kompetensi.', 'KTP + sertifikat pelatihan (diperiksa admin)', 'service-lansia'],
    ];
@endphp

<div class="mt-8 grid gap-4 md:grid-cols-3">
    @foreach ($kategori as $kode => [$label, $tag, $desc, $syarat, $gambar])
        <a href="{{ route('register.pekerja.form', $kode) }}" class="card card-hover group flex flex-col p-5">
            <img src="{{ asset('images/illustrations/' . $gambar . '.svg') }}" alt="" class="h-24 w-full object-contain">
            <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-primary">{{ $tag }}</p>
            <h2 class="mt-1 text-lg font-bold leading-snug text-ink">{{ $label }}</h2>
            <p class="mt-2 flex-1 text-sm text-muted">{{ $desc }}</p>
            <p class="mt-4 flex items-start gap-2 border-t border-line pt-3 text-xs font-medium text-muted">
                <x-icon name="file-check" class="h-4 w-4 text-primary" /> {{ $syarat }}
            </p>
            <span class="btn-primary btn-sm mt-4 w-full">Pilih <x-icon name="arrow-right" class="h-4 w-4" /></span>
        </a>
    @endforeach
</div>

<div class="mt-6 flex gap-3 rounded-xl border border-primary/15 bg-primary-soft px-4 py-3 text-sm text-primary-darker">
    <x-icon name="info" class="mt-0.5 h-5 w-5 text-primary" />
    <p><strong>Akun aktif tidak berarti terverifikasi.</strong> Profil baru tampil di katalog klien setelah data dan dokumen diperiksa admin.</p>
</div>

<div class="mt-8 flex flex-col items-center justify-between gap-3 text-sm sm:flex-row">
    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 text-muted hover:text-primary"><x-icon name="arrow-left" class="h-4 w-4" /> Ubah jenis akun</a>
    <span class="text-muted">Sudah memiliki akun? <a href="{{ route('login') }}" class="font-semibold text-primary">Masuk</a></span>
</div>
@endsection
