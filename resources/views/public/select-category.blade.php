<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Kategori Pekerjaan — Temukerja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F7F5] font-sans antialiased min-h-screen flex flex-col items-center justify-center p-6">

    <a href="{{ route('home') }}" class="text-2xl font-bold text-[#172B23] mb-2">
        Temu<span class="text-[#176B4D]">kerja</span>
    </a>
    <p class="text-[#52645B] text-sm mb-10">Langkah 2 — Registrasi pekerja: pilih kategori.</p>

    {{-- Step Indicator --}}
    <div class="flex items-center gap-2 mb-10">
        <div class="w-8 h-8 rounded-full bg-[#176B4D] text-white flex items-center justify-center text-sm font-bold">✓</div>
        <div class="w-16 h-1 bg-[#176B4D] rounded"></div>
        <div class="w-8 h-8 rounded-full bg-[#176B4D] text-white flex items-center justify-center text-sm font-bold">2</div>
        <div class="w-16 h-1 bg-gray-200 rounded"></div>
        <div class="w-8 h-8 rounded-full bg-gray-200 text-[#52645B] flex items-center justify-center text-sm font-bold">3</div>
    </div>

    <h1 class="text-3xl font-bold text-[#172B23] mb-2 text-center">Keahlian Anda, peluang baru.</h1>
    <p class="text-[#52645B] text-center mb-10 max-w-lg">Pilih kategori pekerjaan. Kenali persyaratannya sebelum melengkapi data akun.</p>

    @php
        $kategori = [
            'art' => [
                'label' => 'ART (Asisten Rumah Tangga)',
                'tag' => 'Registrasi Biasa',
                'desc' => 'Pekerjaan rumah tangga dan bantuan aktivitas rumah tangga sesuai kebutuhan klien.',
                'syarat' => 'Registrasi biasa · tanpa sertifikat profesi (cukup KTP)',
                'tombol' => 'Pilih ART',
                'warna' => 'bg-[#E4F2E8] text-[#176B4D]',
                'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            ],
            'baby_sitter' => [
                'label' => 'Baby Sitter',
                'tag' => 'Verifikasi Dokumen',
                'desc' => 'Pekerjaan pengasuhan bayi dan anak sesuai kebutuhan keluarga.',
                'syarat' => 'KTP + sertifikat pelatihan wajib · diperiksa Admin',
                'tombol' => 'Pilih Baby Sitter',
                'warna' => 'bg-orange-50 text-orange-500',
                'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            'perawat_lansia' => [
                'label' => 'Perawat Lansia',
                'tag' => 'Verifikasi Dokumen',
                'desc' => 'Pendampingan dan perawatan lansia sesuai kompetensi pekerja.',
                'syarat' => 'KTP + sertifikat pelatihan wajib · diperiksa Admin',
                'tombol' => 'Pilih Perawat Lansia',
                'warna' => 'bg-blue-50 text-blue-500',
                'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-5xl mb-6">
        @foreach ($kategori as $kode => $k)
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-[#176B4D] hover:shadow-lg transition-all duration-200 flex flex-col">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 {{ $k['warna'] }}">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $k['icon'] }}" />
                    </svg>
                </div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-[#52645B] mb-1">{{ $k['tag'] }}</p>
                <h2 class="text-lg font-bold text-[#172B23] mb-2">{{ $k['label'] }}</h2>
                <p class="text-sm text-[#52645B] leading-relaxed mb-4">{{ $k['desc'] }}</p>
                <p class="text-xs text-[#52645B] border-t border-gray-100 pt-3 mb-5 flex items-start gap-2">
                    <svg class="w-4 h-4 text-[#176B4D] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ $k['syarat'] }}
                </p>
                <a href="{{ route('register.pekerja.form', $kode) }}"
                   class="mt-auto block w-full text-center py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38] transition-colors">
                    {{ $k['tombol'] }} →
                </a>
            </div>
        @endforeach
    </div>

    <div class="w-full max-w-5xl bg-[#E4F2E8] border border-[#176B4D]/10 rounded-xl p-4 text-sm text-[#104C38] mb-8">
        <strong>Akun aktif tidak berarti sertifikat terverifikasi.</strong>
        Dokumen Baby Sitter dan Perawat Lansia ditinjau Admin. ART dapat mendaftar tanpa sertifikat khusus.
        Profil hanya tampil di katalog klien setelah diverifikasi Admin.
    </div>

    <div class="flex items-center gap-6 text-sm">
        <a href="{{ route('register') }}" class="text-[#52645B] hover:text-[#176B4D]">← Ubah jenis akun</a>
        <a href="{{ route('login') }}" class="text-[#176B4D] font-semibold hover:text-[#104C38]">Sudah memiliki akun? Login</a>
    </div>

</body>
</html>
