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
@section('lebar', 'max-w-2xl')
@section('panel-judul', $isPekerja ? 'Tawarkan keahlian Anda secara profesional.' : 'Temukan pekerja yang cocok untuk keluarga Anda.')
@section('panel-teks', $isPekerja ? 'Lengkapi data akun, lalu profil dan dokumen Anda akan diperiksa admin sebelum tampil di katalog.' : 'Pilih kriteria khusus rumah Anda dan dapatkan rekomendasi pekerja dengan skor kecocokan.')

@section('content')
@include('components.langkah-daftar', ['aktif' => 3])

<h1 class="text-3xl font-extrabold text-ink sm:text-4xl">{{ $judul }}</h1>
<p class="mt-2 text-lg text-muted">Isi data diri Anda. Kolom bertanda <span class="required">*</span> wajib diisi.</p>

@if ($errors->any())
    <x-alert type="danger" class="mt-6">
        Data registrasi belum valid. Periksa kembali kolom yang ditandai.
        @error('peran') <br>{{ $message }} @enderror
        @error('kategori_pekerjaan') <br>{{ $message }} @enderror
    </x-alert>
@endif

<form method="POST" action="{{ route('register.post') }}" class="mt-8 space-y-6" novalidate>
    @csrf
    <input type="hidden" name="peran" value="{{ $peran }}">
    @if ($isPekerja)
        <input type="hidden" name="kategori_pekerjaan" value="{{ $kategori }}">
    @endif

    <div class="space-y-5 rounded-3xl border border-line p-5 sm:p-6">
    <p class="flex items-center gap-2.5 text-lg font-bold text-ink"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary-soft text-primary"><x-icon name="user" /></span> Data akun</p>
    <x-field name="nama" label="Nama lengkap" icon="user" required placeholder="Masukkan nama lengkap Anda" autocomplete="name" />
    <div class="grid gap-5 sm:grid-cols-2">
        <x-field name="email" type="email" label="Alamat email" icon="mail" required placeholder="nama@email.com" autocomplete="email" />
        <x-field name="no_telepon" type="tel" label="Nomor WhatsApp" icon="message" required placeholder="08xxxxxxxxxx" autocomplete="tel" inputmode="tel" help="Kode verifikasi 4 digit akan dikirim ke WhatsApp ini." />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <x-field name="kata_sandi" type="password" label="Kata sandi" icon="lock" required placeholder="Min. 8 karakter, huruf & angka" autocomplete="new-password" />
        <x-field name="kata_sandi_confirmation" type="password" label="Konfirmasi kata sandi" icon="lock" required placeholder="Ulangi kata sandi" autocomplete="new-password" />
    </div>
    </div>

    @if ($isPekerja)
        <div class="space-y-5 rounded-3xl border border-line bg-mint-soft p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3">
                <p class="inline-flex items-center gap-2.5 text-lg font-bold text-ink">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white text-primary"><x-icon name="briefcase" /></span>
                    Profil {{ ProfilPekerja::KATEGORI_SINGKAT[$kategori] }}
                </p>
                <a href="{{ route('register.pekerja') }}" class="text-[15px] font-bold text-primary hover:underline">Ubah kategori</a>
            </div>
            <p class="text-[15px] text-muted">
                @if ($wajibSertifikat)
                    Setelah mendaftar, Anda akan diarahkan untuk mengunggah KTP dan sertifikat pelatihan.
                @else
                    Registrasi biasa, tidak memerlukan sertifikat pelatihan. Cukup unggah KTP setelah masuk.
                @endif
            </p>
            <x-field name="deskripsi_profil" type="textarea" :label="$labelPengalaman" placeholder="Jelaskan pengalaman dan lama bekerja Anda" />
            <x-field name="keterampilan" :label="'Keterampilan'" :placeholder="$contohKeterampilan" help="Pisahkan dengan koma." />
            <x-field name="alamat" label="Domisili" icon="map-pin" required placeholder="Contoh: Jakarta Selatan" />
        </div>
    @endif

    <div>
        <label class="flex cursor-pointer items-start gap-3 rounded-2xl bg-canvas p-4 text-[15px] text-muted">
            <input type="checkbox" name="terms" value="1" @checked(old('terms')) required class="mt-0.5 h-5 w-5 shrink-0 rounded accent-primary">
            <span>Saya menyetujui Syarat &amp; Ketentuan serta Kebijakan Privasi temukerja dan bersedia data saya diverifikasi oleh PT Shaka Abadi Nusantara.</span>
        </label>
        @error('terms') <p class="field-error"><x-icon name="alert-circle" class="h-4 w-4" /> {{ $message }}</p> @enderror
    </div>

    <button type="submit" class="btn-primary btn-lg w-full">
        @if (! $isPekerja) Daftar sebagai Klien @elseif ($wajibSertifikat) Simpan dan Lanjutkan ke Dokumen @else Simpan dan Daftar @endif
    </button>
</form>

<div class="mt-8 flex flex-col items-center justify-between gap-3 text-[15px] sm:flex-row">
    <a href="{{ $isPekerja ? route('register.pekerja') : route('register') }}" class="inline-flex items-center gap-1.5 font-semibold text-muted hover:text-primary"><x-icon name="arrow-left" class="h-5 w-5" /> Kembali</a>
    <span class="text-muted">Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-primary">Masuk</a></span>
</div>
@endsection
