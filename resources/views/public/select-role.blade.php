<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Role — Temukerja</title>
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
    <p class="text-[#52645B] text-sm mb-10">Langkah terakhir! Pilih peran Anda.</p>

    {{-- Step Indicator --}}
    <div class="flex items-center gap-2 mb-10">
        <div class="w-8 h-8 rounded-full bg-[#176B4D] text-white flex items-center justify-center text-sm font-bold">✓</div>
        <div class="w-16 h-1 bg-[#176B4D] rounded"></div>
        <div class="w-8 h-8 rounded-full bg-[#176B4D] text-white flex items-center justify-center text-sm font-bold">✓</div>
        <div class="w-16 h-1 bg-[#176B4D] rounded"></div>
        <div class="w-8 h-8 rounded-full bg-[#176B4D] text-white flex items-center justify-center text-sm font-bold">3</div>
    </div>

    <h1 class="text-3xl font-bold text-[#172B23] mb-2 text-center">Saya Bergabung Sebagai...</h1>
    <p class="text-[#52645B] text-center mb-10 max-w-md">Pilih peran yang sesuai. Peran ini menentukan fitur yang tersedia untuk Anda.</p>

    {{-- Role Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-2xl mb-8" id="roleCards">

        {{-- Klien --}}
        <label for="role-client" class="cursor-pointer">
            <input type="radio" id="role-client" name="role" value="client" class="sr-only peer" onchange="selectRole('client')">
            <div class="role-card bg-white border-2 border-gray-200 rounded-2xl p-8 text-center hover:border-[#176B4D] hover:shadow-lg transition-all duration-200 peer-checked:border-[#176B4D] peer-checked:bg-[#E4F2E8] peer-checked:shadow-lg" id="card-client">
                <div class="w-20 h-20 bg-[#E4F2E8] rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#172B23] mb-2">Saya Pencari Pekerja</h2>
                <p class="text-[#52645B] text-sm leading-relaxed mb-4">Saya ingin mencari ART, Baby Sitter, atau Perawat Lansia untuk kebutuhan keluarga saya.</p>
                <ul class="text-sm text-[#52645B] text-left space-y-2">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Akses katalog pekerja terverifikasi
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Buat & kelola pesanan
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan pekerja favorit
                    </li>
                </ul>

                {{-- Selected indicator --}}
                <div class="hidden mt-4 py-2 px-4 bg-[#176B4D] text-white rounded-xl text-sm font-semibold" id="check-client">
                    ✓ Dipilih
                </div>
            </div>
        </label>

        {{-- Pekerja --}}
        <label for="role-worker" class="cursor-pointer">
            <input type="radio" id="role-worker" name="role" value="worker" class="sr-only peer" onchange="selectRole('worker')">
            <div class="role-card bg-white border-2 border-gray-200 rounded-2xl p-8 text-center hover:border-[#176B4D] hover:shadow-lg transition-all duration-200 peer-checked:border-[#176B4D] peer-checked:bg-[#E4F2E8] peer-checked:shadow-lg" id="card-worker">
                <div class="w-20 h-20 bg-[#E4F2E8] rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#172B23] mb-2">Saya Pencari Kerja</h2>
                <p class="text-[#52645B] text-sm leading-relaxed mb-4">Saya ingin mendaftarkan diri sebagai ART, Baby Sitter, atau Perawat Lansia profesional.</p>
                <ul class="text-sm text-[#52645B] text-left space-y-2">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Buat profil profesional
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Upload dokumen & sertifikat
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Pantau status verifikasi
                    </li>
                </ul>

                <div class="hidden mt-4 py-2 px-4 bg-[#176B4D] text-white rounded-xl text-sm font-semibold" id="check-worker">
                    ✓ Dipilih
                </div>
            </div>
        </label>
    </div>

    {{-- Continue Button --}}
    <form method="POST" action="{{ route('register.post') }}" id="roleForm">
        @csrf
        <input type="hidden" name="role" id="selectedRole" value="">
        <button
            type="submit"
            id="continueBtn"
            disabled
            class="px-10 py-4 bg-[#176B4D] text-white font-bold rounded-xl text-base hover:bg-[#104C38] transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            Lanjutkan →
        </button>
    </form>

    <a href="{{ route('home') }}" class="mt-6 text-sm text-gray-400 hover:text-[#176B4D]">Kembali ke Beranda</a>

<script>
function selectRole(role) {
    document.getElementById('selectedRole').value = role;
    document.getElementById('continueBtn').disabled = false;

    // Update visual feedback
    ['client', 'worker'].forEach(r => {
        const card = document.getElementById('card-' + r);
        const check = document.getElementById('check-' + r);
        if (r === role) {
            card.classList.add('border-[#176B4D]', 'bg-[#E4F2E8]', 'shadow-lg');
            card.classList.remove('border-gray-200');
            check.classList.remove('hidden');
        } else {
            card.classList.remove('border-[#176B4D]', 'bg-[#E4F2E8]', 'shadow-lg');
            card.classList.add('border-gray-200');
            check.classList.add('hidden');
        }
    });
}
</script>
</body>
</html>

