<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — Temukerja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F7F5] font-sans antialiased">

<div class="min-h-screen flex">
    {{-- Left Panel (decorative) --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#176B4D] to-[#104C38] flex-col justify-between p-12 relative overflow-hidden">
        {{-- Background decorative circles --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>

        {{-- Logo --}}
        <div class="relative z-10">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-white">
                Temu<span class="text-[#20A66A]">kerja</span>
            </a>
        </div>

        {{-- Quote --}}
        <div class="relative z-10">
            <blockquote class="text-white text-2xl font-semibold leading-relaxed mb-4">
                "Temukan pekerja terpercaya untuk keluarga Anda dengan mudah."
            </blockquote>
            <p class="text-white/70 text-sm">Platform pencarian ART, Baby Sitter & Perawat Lansia #1 di Indonesia</p>

            {{-- Stats --}}
            <div class="flex gap-8 mt-8">
                <div>
                    <p class="text-3xl font-bold text-white">128+</p>
                    <p class="text-white/70 text-sm">Pekerja Aktif</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">243+</p>
                    <p class="text-white/70 text-sm">Klien Puas</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-white">4.8</p>
                    <p class="text-white/70 text-sm">Rating Rata-rata</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-white/40 text-xs">
            &copy; {{ date('Y') }} Temukerja. All rights reserved.
        </div>
    </div>

    {{-- Right Panel (form) --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8 text-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-[#172B23]">
                    Temu<span class="text-[#176B4D]">kerja</span>
                </a>
            </div>

            <h1 class="text-3xl font-bold text-[#172B23] mb-2">Selamat Datang Kembali</h1>
            <p class="text-[#52645B] mb-8">Masuk ke akun Temukerja Anda</p>

            {{-- Flash error --}}
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-[#172B23] mb-2">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-[#172B23] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#176B4D] focus:border-transparent transition"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-[#172B23]">Kata Sandi</label>
                        <a href="#" class="text-sm text-[#176B4D] hover:text-[#104C38] font-medium">Lupa kata sandi?</a>
                    </div>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan kata sandi"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-[#172B23] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#176B4D] focus:border-transparent transition pr-12"
                        >
                        <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#176B4D]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 accent-[#176B4D]">
                    <label for="remember" class="text-sm text-[#52645B]">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-[#176B4D] hover:bg-[#104C38] text-white font-semibold py-3 rounded-xl transition-colors duration-200 text-base"
                >
                    Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-[#F5F7F5] px-4 text-sm text-gray-400">atau</span>
                </div>
            </div>

            {{-- Register link --}}
            <p class="text-center text-[#52645B] text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-[#176B4D] font-semibold hover:text-[#104C38]">Daftar Sekarang</a>
            </p>

            {{-- Back to home --}}
            <p class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-[#176B4D] flex items-center justify-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </p>
        </div>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>

