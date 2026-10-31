@extends('layouts.worker')
@section('title', 'Edit Profil')
@section('content')

@include('components.page-header', ['crumb' => 'Calon Pekerja / Edit Profil', 'title' => 'Edit Profil', 'subtitle' => 'Perbarui data diri dan pengalaman kerja Anda. Kolom bertanda * wajib diisi.'])

<form method="POST" action="{{ route('worker.profile.update') }}" class="grid gap-6 xl:grid-cols-3" novalidate>
    @csrf
    @method('PUT')

    <div class="space-y-6 xl:col-span-2">
        {{-- Data diri --}}
        <section class="card reveal">
            <div class="card-head">
                <h2 class="card-title flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-soft text-primary"><x-icon name="user" /></span> Data diri</h2>
            </div>
            <div class="card-body grid gap-5 md:grid-cols-2">
                <x-field name="nama" label="Nama lengkap" icon="user" required :value="$pengguna->nama" />
                <x-field name="email" type="email" label="Email" icon="mail" required :value="$pengguna->email" />
                <x-field name="no_telepon" type="tel" label="Nomor telepon" icon="phone" required :value="$pengguna->no_telepon" />
                <x-field name="tanggal_lahir" type="date" label="Tanggal lahir" required :value="$profil->tanggal_lahir?->toDateString()" help="Usia minimal 18 tahun." />
                <x-field name="jenis_kelamin" type="select" label="Jenis kelamin" required :value="$profil->jenis_kelamin" :options="['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan']" />
                <x-field name="alamat" label="Lokasi / domisili" icon="map-pin" required :value="$profil->alamat" placeholder="Contoh: Jakarta Selatan" />
            </div>
        </section>

        {{-- Pengalaman & keahlian --}}
        <section class="card reveal">
            <div class="card-head">
                <h2 class="card-title flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sun-soft text-sun-dark"><x-icon name="briefcase" /></span> Pengalaman & keahlian</h2>
            </div>
            <div class="card-body grid gap-5 md:grid-cols-2">
                <x-field name="keterampilan" label="Keterampilan" required :value="$profil->keterampilan" placeholder="Contoh: Memasak, Mencuci" help="Pisahkan dengan koma." />
                <x-field name="lama_pengalaman" type="number" min="0" max="50" :label="'Lama pengalaman ' . $profil->labelKategori() . ' (tahun)'" required :value="$profil->lama_pengalaman" />
                <x-field class="md:col-span-2" name="deskripsi_profil" type="textarea" rows="5" label="Pengalaman & informasi pendukung" :value="$profil->deskripsi_profil" placeholder="Ceritakan pengalaman dan keahlian Anda" />
            </div>
        </section>

        {{-- Keamanan --}}
        <section class="card card-body reveal [&>div]:border-t-0 [&>div]:pt-0">
            @include('components.form-kata-sandi')
        </section>
    </div>

    {{-- Ringkasan + simpan --}}
    <aside class="space-y-6 xl:sticky xl:top-28 xl:h-fit">
        <div class="card reveal overflow-hidden">
            <div class="bg-gradient-to-br from-primary-dark to-primary p-6 text-center text-white">
                <x-avatar :name="$pengguna->nama" size="xl" class="ring-4 ring-white/30" />
                <p class="mt-3 text-lg font-extrabold">{{ $pengguna->nama }}</p>
                <p class="text-sm text-white/75">{{ $profil->labelKategori() }}</p>
            </div>
            <div class="space-y-3 p-5 text-[15px]">
                <p class="flex items-center justify-between"><span class="text-muted">Status verifikasi</span><x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge></p>
                <p class="flex items-center gap-2 text-muted"><x-icon name="mail" class="h-5 w-5 text-primary" /> <span class="truncate">{{ $pengguna->email }}</span></p>
            </div>
        </div>
        <div class="card card-body reveal space-y-3">
            <button type="submit" class="btn-primary btn-lg w-full"><x-icon name="check" class="h-5 w-5" /> Simpan Perubahan</button>
            <a href="{{ route('worker.profile') }}" class="btn-secondary w-full">Batal</a>
            <p class="text-helper text-center">Perubahan data dapat memengaruhi hasil pencocokan dengan klien.</p>
        </div>
    </aside>
</form>

@endsection
