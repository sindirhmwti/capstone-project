<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#246C54">
    <title>@yield('title', 'Temukerja') — Temukerja</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-icon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    {{-- Font dimuat satu kali untuk seluruh halaman --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Menandai bahwa JavaScript aktif (dipakai animasi "reveal") --}}
    <script>document.documentElement.classList.add('js');</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="@yield('body-class', 'bg-canvas')">
    @yield('body')

    @unless (View::hasSection('tanpa-toast'))
        @include('components.flash')
    @endunless
    @stack('scripts')
</body>
</html>
