{{--
    Layout halaman login & registrasi: panel hijau tua full height di kiri, formulir putih di kanan.
    Di HP panel kiri diringkas menjadi header hijau kecil.
    Section opsional: 'panel-judul', 'panel-teks', 'lebar' (max-width formulir)
--}}
@extends('layouts.base')

@section('body-class', 'bg-white')
@section('halaman-publik', '1')

@section('body')
<div class="flex min-h-screen flex-col overflow-x-clip lg:flex-row">
    {{-- Panel kiri (desktop): foto dengan efek Ken Burns + overlay hijau --}}
    <aside class="relative hidden w-[46%] max-w-[720px] shrink-0 overflow-hidden bg-primary-darker text-white lg:block">
        <div class="sticky top-0 h-screen overflow-hidden">
            <img src="{{ asset('images/photos/' . View::yieldContent('panel-foto', 'panel-registrasi') . '.webp') }}" alt="" class="ken-burns absolute inset-0 h-full w-full object-cover" width="1200" height="1200">
            <div class="absolute inset-0 bg-gradient-to-t from-primary-deep via-primary-darker/55 to-primary/15"></div>
            <div class="bg-grid-light pointer-events-none absolute inset-0 opacity-50"></div>

            <div class="relative flex h-full flex-col justify-between p-10 xl:p-14">
                <x-logo variant="light" size="h-10" :href="route('home')" />

                <div>
                    <p data-auth-item class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-mint ring-1 ring-white/20 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-sun"></span> temukerja
                    </p>
                    <h2 data-auth-item class="mt-5 max-w-lg text-3xl font-extrabold leading-tight text-white xl:text-[2.6rem]">@yield('panel-judul', 'Langkah awal untuk peluang yang tepat.')</h2>
                    <p data-auth-item class="mt-4 max-w-lg text-lg text-white/85">@yield('panel-teks', 'Sistem pencarian dan pemesanan pekerja. Menghubungkan kebutuhan dengan keahlian.')</p>

                    <ul class="mt-8 grid gap-3 2xl:grid-cols-3">
                        @foreach ([['shield-check', 'Pekerja diperiksa admin yayasan'], ['sparkles', 'Pencocokan kriteria khusus'], ['lock', 'Data pribadi tersimpan aman']] as [$ikon, $poin])
                            <li data-auth-item class="flex items-center gap-3 rounded-2xl bg-white/10 p-3 text-sm font-semibold text-white ring-1 ring-white/15 backdrop-blur-md">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sun text-ink"><x-icon :name="$ikon" class="h-5 w-5" /></span>
                                {{ $poin }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </aside>

    {{-- Header ringkas (HP & tablet) dengan foto --}}
    <div class="relative overflow-hidden px-5 pb-10 pt-5 text-white sm:px-8 lg:hidden">
        <img src="{{ asset('images/photos/' . View::yieldContent('panel-foto', 'panel-registrasi') . '.webp') }}" alt="" class="ken-burns absolute inset-0 h-full w-full object-cover" width="1200" height="800">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-deep/95 via-primary-darker/85 to-primary/70"></div>
        <div class="relative flex items-center justify-between">
            <x-logo variant="light" :href="route('home')" />
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-2 text-sm font-semibold text-white backdrop-blur"><x-icon name="arrow-left" class="h-4 w-4" /> Beranda</a>
        </div>
        <p data-auth-item class="relative mt-6 text-2xl font-extrabold leading-tight">@yield('panel-judul', 'Langkah awal untuk peluang yang tepat.')</p>
        <p data-auth-item class="relative mt-1.5 text-white/85">@yield('panel-teks', 'Sistem pencarian dan pemesanan pekerja.')</p>
    </div>

    {{-- Konten kanan --}}
    <div class="relative -mt-4 flex flex-1 flex-col rounded-t-3xl bg-white lg:mt-0 lg:rounded-none">
        <div class="hidden items-center justify-end px-8 py-6 lg:flex xl:px-12">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-[15px] font-semibold text-muted transition-colors hover:bg-primary-soft hover:text-primary-dark">
                <x-icon name="arrow-left" class="h-5 w-5" /> Kembali ke beranda
            </a>
        </div>
        <main class="flex flex-1 items-start justify-center px-5 pb-14 pt-8 sm:px-8 lg:items-center lg:pt-0 xl:px-12">
            <div class="w-full @yield('lebar', 'max-w-lg') animate-masuk-kanan">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@endsection
