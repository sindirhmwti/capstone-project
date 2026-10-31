{{--
    Logo Temukerja: ikon SVG + tulisan "temukerja." memakai font halaman agar selalu tajam.
    Pemakaian: <x-logo />  ·  <x-logo variant="light" />  ·  <x-logo :text="false" size="h-9" />
--}}
@props(['variant' => 'dark', 'text' => true, 'size' => 'h-9', 'href' => null])

@php
    $tujuan = $href ?? (auth()->check() ? route(auth()->user()->routeDashboard()) : route('home'));
    $warnaTeks = $variant === 'light' ? 'text-white' : 'text-ink';
    $warnaTitik = $variant === 'light' ? 'text-mint' : 'text-primary';
@endphp

<a href="{{ $tujuan }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 shrink-0']) }} aria-label="Temukerja — kembali ke beranda">
    <img src="{{ asset('images/logo-icon.svg') }}" alt="" class="{{ $size }} w-auto {{ $variant === 'light' ? 'rounded-[11px] ring-2 ring-white/30' : '' }}" width="36" height="36">
    @if ($text)
        <span class="logo-teks text-[1.45rem] font-extrabold leading-none tracking-tight transition-colors {{ $warnaTeks }}">temukerja<span class="logo-titik {{ $warnaTitik }}">.</span></span>
    @endif
</a>
