@php
    use App\Models\ProfilPekerja;

    $isPekerja = $peran === 'pekerja';
    $wajibSertifikat = $isPekerja && in_array($kategori, ProfilPekerja::WAJIB_SERTIFIKAT, true);
    $judul = $isPekerja ? 'Daftar sebagai ' . ProfilPekerja::KATEGORI_SINGKAT[$kategori] : 'Daftar sebagai Klien';
    $input = 'w-full px-4 py-3 border border-gray-200 rounded-xl bg-white text-[#172B23] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#176B4D] focus:border-transparent transition';

    // Label kolom pengalaman & keterampilan menyesuaikan kategori (sesuai mockup)
    $labelPengalaman = [
        'art' => 'Pengalaman pekerjaan rumah tangga',
        'baby_sitter' => 'Pengalaman mengasuh bayi atau anak',
        'perawat_lansia' => 'Pengalaman merawat lansia',
    ][$kategori] ?? 'Pengalaman';
    $contohKeterampilan = [
        'art' => 'Membersihkan rumah, mencuci, memasak',
        'baby_sitter' => 'Pengasuhan anak, pendampingan aktivitas',
        'perawat_lansia' => 'Pendampingan lansia, aktivitas harian',
    ][$kategori] ?? '';
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $judul }} — Temukerja</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F7F5] font-sans antialiased">

<div class="min-h-screen flex">
    {{-- Panel Kiri --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#176B4D] to-[#104C38] flex-col justify-between p-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>

        <div class="relative z-10">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-white">
                Temu<span class="text-[#20A66A]">kerja</span>
            </a>
        </div>

        <div class="relative z-10">
            <h2 class="text-white text-3xl font-bold mb-4">Langkah awal untuk peluang yang tepat.</h2>
            <p class="text-white/70 text-base leading-relaxed">Sistem pencarian dan pemesanan pekerja. Menghubungkan kebutuhan dengan keahlian.</p>

            <div class="mt-8 space-y-4">
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                    <span class="text-sm">Gratis untuk mendaftar</span>
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                    <span class="text-sm">Profil pekerja diverifikasi admin</span>
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                    <span class="text-sm">Pencocokan berdasarkan kriteria khusus</span>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-white/40 text-xs">
            &copy; {{ date('Y') }} Temukerja &mdash; PT Shaka Abadi Nusantara.
        </div>
    </div>

    {{-- Panel Kanan (formulir) --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12 overflow-y-auto">
        <div class="w-full max-w-md py-6">
            <div class="lg:hidden mb-8 text-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-[#172B23]">
                    Temu<span class="text-[#176B4D]">kerja</span>
                </a>
            </div>

            <h1 class="text-3xl font-bold text-[#172B23] mb-2">{{ $judul }}</h1>
            <p class="text-[#52645B] mb-8">Isi data diri Anda untuk mulai menggunakan Temukerja.</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
                    Data registrasi belum valid. Periksa kembali kolom yang ditandai.
                    @error('peran') <br>{{ $message }} @enderror
                    @error('kategori_pekerjaan') <br>{{ $message }} @enderror
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-5" novalidate>
                @csrf
                <input type="hidden" name="peran" value="{{ $peran }}">
                @if ($isPekerja)
                    <input type="hidden" name="kategori_pekerjaan" value="{{ $kategori }}">
                @endif

                {{-- Nama Lengkap --}}
                <div>
                    <label for="nama" class="block text-sm font-medium text-[#172B23] mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap Anda" required class="{{ $input }}">
                    @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-[#172B23] mb-2">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required class="{{ $input }}">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <label for="no_telepon" class="block text-sm font-medium text-[#172B23] mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="tel" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxxx" required class="{{ $input }}">
                    @error('no_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Kata Sandi --}}
                <div>
                    <label for="kata_sandi" class="block text-sm font-medium text-[#172B23] mb-2">Kata Sandi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="kata_sandi" name="kata_sandi" placeholder="Min. 8 karakter, berisi huruf dan angka" required class="{{ $input }} pr-12">
                        <button type="button" onclick="togglePassword('kata_sandi')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#176B4D]" aria-label="Tampilkan kata sandi">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('kata_sandi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Konfirmasi Kata Sandi --}}
                <div>
                    <label for="kata_sandi_confirmation" class="block text-sm font-medium text-[#172B23] mb-2">Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
                    <input type="password" id="kata_sandi_confirmation" name="kata_sandi_confirmation" placeholder="Ulangi kata sandi" required class="{{ $input }}">
                </div>

                {{-- Data khusus pekerja --}}
                @if ($isPekerja)
                    <div class="bg-[#F5F7F5] border border-gray-200 rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-[#172B23] text-sm">{{ ProfilPekerja::KATEGORI_SINGKAT[$kategori] }}</p>
                            <a href="{{ route('register.pekerja') }}" class="text-xs text-[#176B4D] font-medium hover:underline">Ubah kategori</a>
                        </div>
                        <p class="text-xs text-[#52645B]">
                            @if ($wajibSertifikat)
                                Lengkapi data akun, kemudian unggah KTP dan sertifikat pelatihan. Sertifikat hanya terverifikasi setelah disetujui Admin.
                            @else
                                Registrasi biasa. Tidak memerlukan sertifikat pelatihan, cukup KTP setelah masuk ke dashboard.
                            @endif
                        </p>

                        <div>
                            <label for="deskripsi_profil" class="block text-sm font-medium text-[#172B23] mb-2">{{ $labelPengalaman }}</label>
                            <textarea id="deskripsi_profil" name="deskripsi_profil" rows="3" placeholder="Jelaskan pengalaman dan durasi kerja Anda" class="{{ $input }}">{{ old('deskripsi_profil') }}</textarea>
                            @error('deskripsi_profil') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="keterampilan" class="block text-sm font-medium text-[#172B23] mb-2">Keterampilan</label>
                            <input type="text" id="keterampilan" name="keterampilan" value="{{ old('keterampilan') }}" placeholder="{{ $contohKeterampilan }}" class="{{ $input }}">
                            <p class="mt-1 text-xs text-[#52645B]">Pisahkan dengan koma.</p>
                            @error('keterampilan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-medium text-[#172B23] mb-2">Domisili <span class="text-red-500">*</span></label>
                            <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" placeholder="Contoh: Jakarta Selatan" required class="{{ $input }}">
                            @error('alamat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif

                {{-- Persetujuan --}}
                <div>
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required class="w-4 h-4 mt-0.5 accent-[#176B4D]">
                        <label for="terms" class="text-sm text-[#52645B]">
                            Saya menyetujui Syarat &amp; Ketentuan serta Kebijakan Privasi Temukerja dan bersedia data saya diverifikasi oleh PT Shaka Abadi Nusantara.
                        </label>
                    </div>
                    @error('terms') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-[#176B4D] hover:bg-[#104C38] text-white font-semibold py-3 rounded-xl transition-colors duration-200 text-base">
                    @if (! $isPekerja)
                        Daftar sebagai Klien
                    @elseif ($wajibSertifikat)
                        Simpan dan Lanjutkan ke Dokumen
                    @else
                        Simpan dan Daftar
                    @endif
                </button>
            </form>

            <p class="text-center mt-6 text-sm">
                <a href="{{ $isPekerja ? route('register.pekerja') : route('register') }}" class="text-[#52645B] hover:text-[#176B4D]">← Ubah jenis akun</a>
            </p>
            <p class="text-center mt-3 text-[#52645B] text-sm">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-[#176B4D] font-semibold hover:text-[#104C38]">Login</a>
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
