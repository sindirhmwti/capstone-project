@extends('layouts.worker')
@section('title', 'Edit Profil')
@section('content')

@include('components.page-header', ['crumb' => 'Calon Pekerja / Edit Profil', 'title' => 'Edit Profil', 'subtitle' => 'Perbarui data diri dan pengalaman kerja Anda. Kolom bertanda * wajib diisi.'])

<form method="POST" action="{{ route('worker.profile.update') }}" class="card card-body max-w-4xl space-y-6" novalidate>
    @csrf
    @method('PUT')

    <div class="flex items-center gap-4 border-b border-line pb-5">
        <x-avatar :name="$pengguna->nama" size="lg" />
        <div>
            <p class="card-title">Informasi pribadi</p>
            <p class="text-sm text-muted">{{ $profil->labelKategori() }} · {{ $pengguna->email }}</p>
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <x-field name="nama" label="Nama lengkap" required :value="$pengguna->nama" />
        <x-field name="email" type="email" label="Email" required :value="$pengguna->email" />
        <x-field name="no_telepon" type="tel" label="Nomor telepon" required :value="$pengguna->no_telepon" />
        <x-field name="alamat" label="Lokasi / domisili" required :value="$profil->alamat" placeholder="Contoh: Jakarta Selatan" />
        <x-field name="tanggal_lahir" type="date" label="Tanggal lahir" required :value="$profil->tanggal_lahir?->toDateString()" help="Usia minimal 18 tahun." />
        <x-field name="jenis_kelamin" type="select" label="Jenis kelamin" required :value="$profil->jenis_kelamin" :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']" />
        <x-field name="keterampilan" label="Keterampilan" required :value="$profil->keterampilan" placeholder="Contoh: Memasak, Mencuci" help="Pisahkan dengan koma." />
        <x-field name="lama_pengalaman" type="number" min="0" max="50" :label="'Lama pengalaman ' . $profil->labelKategori() . ' (tahun)'" required :value="$profil->lama_pengalaman" />
        <x-field class="md:col-span-2" name="deskripsi_profil" type="textarea" rows="4" label="Pengalaman & informasi pendukung" :value="$profil->deskripsi_profil" placeholder="Ceritakan pengalaman dan keahlian Anda" />
    </div>

    @include('components.form-kata-sandi')

    <div class="flex flex-col-reverse gap-3 border-t border-line pt-5 sm:flex-row sm:justify-end">
        <a href="{{ route('worker.profile') }}" class="btn-secondary">Batal</a>
        <button type="submit" class="btn-primary">Simpan Perubahan</button>
    </div>
</form>

@endsection
