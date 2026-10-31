{{--
    Layout halaman login & registrasi: panel hijau berilustrasi di kiri (sesuai mockup), formulir di kanan.
    Section opsional: 'panel-judul', 'panel-teks', 'lebar' (max-width formulir)
--}}
@extends('layouts.base')

@section('body-class', 'bg-white')

@section('body')
<div class="flex min-h-screen">
    {{-- Panel kiri --}}
    <aside class="relative hidden w-[44%] max-w-xl flex-col justify-between overflow-hidden bg-primary p-10 text-white lg:flex xl:p-12">
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-white/5"></div>

        <x-logo variant="light" :href="route('home')" class="relative" />

        <div class="relative">
            <img src="{{ asset('images/illustrations/auth.svg') }}" alt="Ilustrasi profil pekerja terverifikasi" class="mx-auto mb-10 w-full max-w-sm animate-fade-up">
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-mint">Temukerja</p>
            <h2 class="mt-3 text-3xl font-bold leading-tight text-white">@yield('panel-judul', 'Langkah awal untuk peluang yang tepat.')</h2>
            <p class="mt-3 max-w-md text-base text-white/75">@yield('panel-teks', 'Sistem pencarian dan pemesanan pekerja. Menghubungkan kebutuhan dengan keahlian.')</p>
        </div>

        <ul class="relative space-y-2.5 text-sm text-white/85">
            @foreach (['Profil pekerja diperiksa admin yayasan', 'Pencocokan berdasarkan kriteria khusus', 'Data pribadi tersimpan aman'] as $poin)
                <li class="flex items-center gap-2.5">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/15"><x-icon name="check" class="h-3.5 w-3.5" /></span>
                    {{ $poin }}
                </li>
            @endforeach
        </ul>
    </aside>

    {{-- Konten kanan --}}
    <div class="flex flex-1 flex-col">
        <div class="flex items-center justify-between px-5 py-5 sm:px-8 lg:justify-end">
            <x-logo :href="route('home')" class="lg:hidden" />
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-muted hover:text-primary">
                <x-icon name="arrow-left" class="h-4 w-4" /> Kembali ke beranda
            </a>
        </div>
        <main class="flex flex-1 items-start justify-center px-5 pb-12 pt-2 sm:px-8 lg:items-center">
            <div class="w-full @yield('lebar', 'max-w-md') animate-fade-up">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@endsection
