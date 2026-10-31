<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Akun — Temukerja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F7F5] font-sans antialiased min-h-screen flex flex-col items-center justify-center p-6">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="text-2xl font-bold text-[#172B23] mb-2">
        Temu<span class="text-[#176B4D]">kerja</span>
    </a>
    <p class="text-[#52645B] text-sm mb-10">Langkah 1 — Pilih jenis akun Anda.</p>

    {{-- Step Indicator --}}
    <div class="flex items-center gap-2 mb-10">
        <div class="w-8 h-8 rounded-full bg-[#176B4D] text-white flex items-center justify-center text-sm font-bold">1</div>
        <div class="w-16 h-1 bg-gray-200 rounded"></div>
        <div class="w-8 h-8 rounded-full bg-gray-200 text-[#52645B] flex items-center justify-center text-sm font-bold">2</div>
        <div class="w-16 h-1 bg-gray-200 rounded"></div>
        <div class="w-8 h-8 rounded-full bg-gray-200 text-[#52645B] flex items-center justify-center text-sm font-bold">3</div>
    </div>

    <h1 class="text-3xl font-bold text-[#172B23] mb-2 text-center">Daftar sebagai Apa?</h1>
    <p class="text-[#52645B] text-center mb-10 max-w-md">Pilih jenis akun yang sesuai dengan kebutuhan Anda.</p>

    {{-- Kartu Pilihan Akun --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-2xl mb-8">

        {{-- Klien --}}
        <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 text-center hover:border-[#176B4D] hover:shadow-lg transition-all duration-200 flex flex-col">
            <div class="w-20 h-20 bg-[#E4F2E8] rounded-2xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-[#172B23] mb-2">KLIEN</h2>
            <p class="text-[#52645B] text-sm leading-relaxed mb-4">Untuk pengguna yang ingin mencari dan memesan pekerja: ART, Baby Sitter, atau Perawat Lansia.</p>
            <ul class="text-sm text-[#52645B] text-left space-y-2 mb-6">
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Akses katalog pekerja terverifikasi
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Rekomendasi sesuai kriteria khusus
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Buat &amp; pantau pesanan, simpan favorit
                </li>
            </ul>
            <a href="{{ route('register.klien') }}"
               class="mt-auto block w-full py-3 bg-[#176B4D] text-white font-semibold rounded-xl hover:bg-[#104C38] transition-colors">
                Daftar sebagai Klien →
            </a>
        </div>

        {{-- Pekerja --}}
        <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 text-center hover:border-[#176B4D] hover:shadow-lg transition-all duration-200 flex flex-col">
            <div class="w-20 h-20 bg-[#E4F2E8] rounded-2xl flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-[#172B23] mb-2">PEKERJA</h2>
            <p class="text-[#52645B] text-sm leading-relaxed mb-4">Untuk pengguna yang ingin menawarkan jasa sebagai ART, Baby Sitter, atau Perawat Lansia.</p>
            <ul class="text-sm text-[#52645B] text-left space-y-2 mb-6">
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Buat profil &amp; isi kriteria khusus
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Unggah KTP &amp; sertifikat pelatihan
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Pantau status verifikasi
                </li>
            </ul>
            <a href="{{ route('register.pekerja') }}"
               class="mt-auto block w-full py-3 bg-[#176B4D] text-white font-semibold rounded-xl hover:bg-[#104C38] transition-colors">
                Daftar sebagai Pekerja →
            </a>
        </div>
    </div>

    <p class="text-sm text-[#52645B]">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="text-[#176B4D] font-semibold hover:text-[#104C38]">Login</a>
    </p>
    <a href="{{ route('home') }}" class="mt-4 text-sm text-gray-400 hover:text-[#176B4D]">← Kembali ke Beranda</a>

</body>
</html>
