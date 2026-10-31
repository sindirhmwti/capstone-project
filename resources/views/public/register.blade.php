<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar — Temukerja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F7F5] font-sans antialiased">

<div class="min-h-screen flex">
    {{-- Left Panel --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#176B4D] to-[#104C38] flex-col justify-between p-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>

        <div class="relative z-10">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-white">
                Temu<span class="text-[#20A66A]">kerja</span>
            </a>
        </div>

        <div class="relative z-10">
            <h2 class="text-white text-3xl font-bold mb-4">Bergabunglah dengan Temukerja</h2>
            <p class="text-white/70 text-base leading-relaxed">Daftarkan diri Anda dan mulai temukan atau tawarkan jasa pekerjaan rumah tangga secara profesional.</p>

            <div class="mt-8 space-y-4">
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                    <span class="text-sm">Gratis untuk mendaftar</span>
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                    <span class="text-sm">Profil terverifikasi & terpercaya</span>
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                    <span class="text-sm">Proses pemesanan yang mudah & aman</span>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-white/40 text-xs">
            &copy; {{ date('Y') }} Temukerja. All rights reserved.
        </div>
    </div>

    {{-- Right Panel --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12 overflow-y-auto">
        <div class="w-full max-w-md">
            <div class="lg:hidden mb-8 text-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-[#172B23]">
                    Temu<span class="text-[#176B4D]">kerja</span>
                </a>
            </div>

            <h1 class="text-3xl font-bold text-[#172B23] mb-2">Buat Akun Baru</h1>
            <p class="text-[#52645B] mb-8">Isi data diri Anda untuk mulai menggunakan Temukerja</p>

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                @csrf

                {{-- Full Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-[#172B23] mb-2">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap Anda"
                        required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-[#172B23] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#176B4D] focus:border-transparent transition"
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-[#172B23] mb-2">Nomor Telepon</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-[#172B23] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#176B4D] focus:border-transparent transition"
                    >
                    @error('phone')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-[#172B23] mb-2">Kata Sandi</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Min. 8 karakter"
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

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-[#172B23] mb-2">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi kata sandi"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-[#172B23] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#176B4D] focus:border-transparent transition pr-12"
                        >
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#176B4D]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Terms --}}
                <div class="flex items-start gap-3">
                    <input type="checkbox" id="terms" name="terms" required class="w-4 h-4 mt-0.5 accent-[#176B4D]">
                    <label for="terms" class="text-sm text-[#52645B]">
                        Saya menyetujui <a href="#" class="text-[#176B4D] font-medium hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-[#176B4D] font-medium hover:underline">Kebijakan Privasi</a> Temukerja
                    </label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-[#176B4D] hover:bg-[#104C38] text-white font-semibold py-3 rounded-xl transition-colors duration-200 text-base"
                >
                    Daftar Sekarang
                </button>
            </form>

            <p class="text-center mt-6 text-[#52645B] text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-[#176B4D] font-semibold hover:text-[#104C38]">Masuk</a>
            </p>

            <p class="text-center mt-3">
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

