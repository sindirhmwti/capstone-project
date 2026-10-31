@extends('layouts.auth')

@php
    use App\Models\ProfilPekerja;

    $isPekerja = $peran === 'pekerja';
    $wajibSertifikat = $isPekerja && in_array($kategori, ProfilPekerja::WAJIB_SERTIFIKAT, true);
    $judul = $isPekerja ? 'Daftar sebagai ' . ProfilPekerja::KATEGORI_SINGKAT[$kategori] : 'Daftar sebagai Klien';

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

@section('title', $judul)
@section('lebar', 'max-w-lg')
@section('panel-judul', $isPekerja ? 'Tawarkan keahlian Anda secara profesional.' : 'Temukan pekerja yang cocok untuk keluarga Anda.')
@section('panel-teks', $isPekerja ? 'Lengkapi data akun, lalu profil dan dokumen Anda akan diperiksa admin sebelum tampil di katalog.' : 'Pilih kriteria khusus rumah Anda dan dapatkan rekomendasi pekerja dengan skor kecocokan.')

@section('content')
@include('components.langkah-daftar', ['aktif' => 3])

<h1 class="text-3xl font-bold text-ink">{{ $judul }}</h1>
<p class="mt-2 text-base text-muted">Isi data diri Anda. Kolom bertanda <span class="required">*</span> wajib diisi.</p>

@if ($errors->any())
    <div class="mt-6 flex gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
        <x-icon name="alert-circle" class="mt-0.5 h-5 w-5 text-red-600" />
        <div>
            Data registrasi belum valid. Periksa kembali kolom yang ditandai.
            @error('peran') <br>{{ $message }} @enderror
            @error('kategori_pekerjaan') <br>{{ $message }} @enderror
        </div>
    </div>
@endif

<form method="POST" action="{{ route('register.post') }}" class="mt-8 space-y-5" novalidate>
    @csrf
    <input type="hidden" name="peran" value="{{ $peran }}">
    @if ($isPekerja)
        <input type="hidden" name="kategori_pekerjaan" value="{{ $kategori }}">
    @endif

    <x-field name="nama" label="Nama lengkap" required placeholder="Masukkan nama lengkap Anda" autocomplete="name" />
    <div class="grid gap-5 sm:grid-cols-2">
        <x-field name="email" type="email" label="Alamat email" required placeholder="nama@email.com" autocomplete="email" />
        <x-field name="no_telepon" type="tel" label="Nomor telepon" required placeholder="08xxxxxxxxxx" autocomplete="tel" />
    </div>

    <div>
        <label for="kata_sandi" class="label">Kata sandi <span class="required">*</span></label>
        <div class="relative">
            <input type="password" id="kata_sandi" name="kata_sandi" placeholder="Minimal 8 karakter, berisi huruf dan angka" required autocomplete="new-password"
                   class="input pr-12 @error('kata_sandi') input-error @enderror">
            <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-lg p-2 text-muted hover:text-primary" data-toggle-password="kata_sandi" aria-label="Tampilkan kata sandi">
                <x-icon name="eye" class="h-5 w-5" data-ikon-lihat />
                <x-icon name="eye-off" class="hidden h-5 w-5" data-ikon-sembunyi />
            </button>
        </div>
        @error('kata_sandi') <p class="field-error">{{ $message }}</p> @enderror
    </div>
    <x-field name="kata_sandi_confirmation" type="password" label="Konfirmasi kata sandi" required placeholder="Ulangi kata sandi" autocomplete="new-password" />

    @if ($isPekerja)
        <div class="space-y-4 rounded-2xl border border-line bg-canvas p-5">
            <div class="flex items-center justify-between gap-3">
                <p class="inline-flex items-center gap-2 font-semibold text-ink">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-primary"><x-icon name="briefcase" class="h-4 w-4" /></span>
                    {{ ProfilPekerja::KATEGORI_SINGKAT[$kategori] }}
                </p>
                <a href="{{ route('register.pekerja') }}" class="text-sm font-medium text-primary hover:underline">Ubah kategori</a>
            </div>
            <p class="text-sm text-muted">
                @if ($wajibSertifikat)
                    Setelah mendaftar, Anda akan diarahkan untuk mengunggah KTP dan sertifikat pelatihan.
                @else
                    Registrasi biasa, tidak memerlukan sertifikat pelatihan. Cukup unggah KTP setelah masuk.
                @endif
            </p>
            <x-field name="deskripsi_profil" type="textarea" :label="$labelPengalaman" placeholder="Jelaskan pengalaman dan lama bekerja Anda" />
            <x-field name="keterampilan" :label="'Keterampilan'" :placeholder="$contohKeterampilan" help="Pisahkan dengan koma." />
            <x-field name="alamat" label="Domisili" required placeholder="Contoh: Jakarta Selatan" />
        </div>
    @endif

    <div>
        <label class="flex items-start gap-3 text-sm text-muted">
            <input type="checkbox" name="terms" value="1" @checked(old('terms')) required class="mt-1 h-4 w-4 rounded accent-primary">
            <span>Saya menyetujui Syarat &amp; Ketentuan serta Kebijakan Privasi Temukerja dan bersedia data saya diverifikasi oleh PT Shaka Abadi Nusantara.</span>
        </label>
        @error('terms') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="btn-primary btn-lg w-full">
        @if (! $isPekerja) Daftar sebagai Klien @elseif ($wajibSertifikat) Simpan dan Lanjutkan ke Dokumen @else Simpan dan Daftar @endif
    </button>
</form>

<div class="mt-8 flex flex-col items-center justify-between gap-3 text-sm sm:flex-row">
    <a href="{{ $isPekerja ? route('register.pekerja') : route('register') }}" class="inline-flex items-center gap-1.5 text-muted hover:text-primary"><x-icon name="arrow-left" class="h-4 w-4" /> Kembali</a>
    <span class="text-muted">Sudah memiliki akun? <a href="{{ route('login') }}" class="font-semibold text-primary">Masuk</a></span>
</div>
@endsection
