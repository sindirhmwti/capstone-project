@extends($layout)
@section('title', 'Edit Profil')
@section('content')

@include('components.page-header', ['crumb' => $crumb . ' / Edit Profil', 'title' => 'Edit Profil', 'subtitle' => 'Perbarui informasi akun Anda. Kolom bertanda * wajib diisi.'])

<form method="POST" action="{{ route($routeUpdate) }}" class="grid gap-6 xl:grid-cols-3" novalidate>
    @csrf
    @method('PUT')

    <div class="space-y-6 xl:col-span-2">
        <section class="card reveal">
            <div class="card-head">
                <h2 class="card-title flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-soft text-primary"><x-icon name="user" /></span> {{ $pengguna->isAdmin() ? 'Informasi admin' : 'Informasi pribadi' }}</h2>
            </div>
            <div class="card-body grid gap-5 md:grid-cols-2">
                <x-field name="nama" label="Nama lengkap" icon="user" required :value="$pengguna->nama" autocomplete="name" />
                <x-field name="email" type="email" label="Email" icon="mail" required :value="$pengguna->email" autocomplete="email" />
                <x-field name="no_telepon" type="tel" label="Nomor telepon" icon="phone" required :value="$pengguna->no_telepon" autocomplete="tel" />
            </div>
        </section>

        <section class="card card-body reveal [&>div]:border-t-0 [&>div]:pt-0">
            @include('components.form-kata-sandi')
        </section>
    </div>

    <aside class="space-y-6 xl:sticky xl:top-28 xl:h-fit">
        <div class="card reveal overflow-hidden">
            <div class="bg-gradient-to-br from-primary-dark to-primary p-6 text-center text-white">
                <x-avatar :name="$pengguna->nama" size="xl" class="ring-4 ring-white/30" />
                <p class="mt-3 text-lg font-extrabold">{{ $pengguna->nama }}</p>
                <p class="text-sm text-white/75">{{ $pengguna->isAdmin() ? 'Admin' : 'Klien' }}</p>
            </div>
            <div class="space-y-3 p-5 text-[15px] text-muted">
                <p class="flex items-center gap-2"><x-icon name="mail" class="h-5 w-5 text-primary" /> <span class="truncate">{{ $pengguna->email }}</span></p>
                <p class="flex items-center gap-2"><x-icon name="calendar" class="h-5 w-5 text-primary" /> Terdaftar sejak {{ $pengguna->tanggal_dibuat?->translatedFormat('d F Y') }}</p>
            </div>
        </div>
        <div class="card card-body reveal space-y-3">
            <button type="submit" class="btn-primary btn-lg w-full"><x-icon name="check" class="h-5 w-5" /> Simpan Perubahan</button>
            <a href="{{ route($pengguna->routeDashboard()) }}" class="btn-secondary w-full">Batal</a>
        </div>
    </aside>
</form>

@endsection
