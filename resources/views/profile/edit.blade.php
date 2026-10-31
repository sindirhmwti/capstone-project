@extends($layout)
@section('title', 'Edit Profil')
@section('content')

@include('components.page-header', ['crumb' => $crumb . ' / Edit Profil', 'title' => 'Edit Profil', 'subtitle' => 'Perbarui informasi akun Anda. Kolom bertanda * wajib diisi.'])

<form method="POST" action="{{ route($routeUpdate) }}" class="card card-body max-w-4xl space-y-6" novalidate>
    @csrf
    @method('PUT')

    <div class="flex items-center gap-4 border-b border-line pb-5">
        <x-avatar :name="$pengguna->nama" size="lg" />
        <div>
            <p class="card-title">{{ $pengguna->isAdmin() ? 'Informasi admin' : 'Informasi pribadi' }}</p>
            <p class="text-sm text-muted">{{ $pengguna->isAdmin() ? 'Admin' : 'Klien' }} · terdaftar sejak {{ $pengguna->tanggal_dibuat?->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <x-field name="nama" label="Nama lengkap" required :value="$pengguna->nama" autocomplete="name" />
        <x-field name="email" type="email" label="Email" required :value="$pengguna->email" autocomplete="email" />
        <x-field name="no_telepon" type="tel" label="Nomor telepon" required :value="$pengguna->no_telepon" autocomplete="tel" />
    </div>

    @include('components.form-kata-sandi')

    <div class="flex flex-col-reverse gap-3 border-t border-line pt-5 sm:flex-row sm:justify-end">
        <a href="{{ route($pengguna->routeDashboard()) }}" class="btn-secondary">Batal</a>
        <button type="submit" class="btn-primary">Simpan Perubahan</button>
    </div>
</form>

@endsection
