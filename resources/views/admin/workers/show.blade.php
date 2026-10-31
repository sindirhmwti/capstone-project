@extends('layouts.admin')
@section('title', 'Detail Akun Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Admin / Kelola Akun Pekerja / Detail',
    'title' => 'Detail Akun Pekerja',
    'subtitle' => 'Data profil, dokumen, dan riwayat pemesanan pekerja.',
    'aksi' => '<a href="' . route('admin.workers') . '" class="btn-secondary">Kembali ke daftar</a>',
])

<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        @include('components.detail-pekerja', ['tampilKontak' => true, 'tampilDokumen' => true])

        <div class="card">
            <div class="border-b border-line px-5 py-4"><h2 class="card-title">Riwayat pemesanan</h2></div>
            @forelse ($profil->pemesanan->sortByDesc('tanggal_pemesanan') as $o)
                <a href="{{ route('admin.orders.show', $o) }}" class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-5 py-3.5 text-sm transition-colors last:border-0 hover:bg-canvas">
                    <span><span class="font-semibold text-ink">{{ $o->kode() }}</span> <span class="text-muted">· {{ $o->klien->nama }} · {{ $o->tanggal_pemesanan->format('d/m/Y') }}</span></span>
                    <x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge>
                </a>
            @empty
                <x-empty-state illustration="pesanan" title="Belum ada pemesanan untuk pekerja ini." compact />
            @endforelse
        </div>
    </div>

    <aside class="space-y-6">
        <form method="POST" action="{{ route('admin.workers.update', $profil) }}" class="card card-body space-y-4" novalidate>
            @csrf
            @method('PUT')
            <h2 class="card-title">Kelola data akun</h2>
            <x-field name="nama" label="Nama lengkap" required :value="$profil->pengguna->nama" />
            <x-field name="email" type="email" label="Email" required :value="$profil->pengguna->email" />
            <x-field name="no_telepon" type="tel" label="Nomor telepon" required :value="$profil->pengguna->no_telepon" />
            <x-field name="status_akun" type="select" label="Status akun" required :value="$profil->pengguna->status_akun" :options="['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif']"
                     help="Akun nonaktif tidak dapat login dan tidak tampil di katalog klien." />
            <button type="submit" class="btn-primary w-full">Simpan Perubahan</button>
        </form>

        <div class="card card-body text-sm">
            <h2 class="card-title mb-4">Verifikasi</h2>
            <div class="flex items-center justify-between"><span class="text-muted">Status</span><x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge></div>
            @if ($profil->verifikator)
                <p class="mt-3 text-muted">Diperiksa oleh <strong class="text-ink">{{ $profil->verifikator->nama }}</strong>, {{ $profil->tanggal_verifikasi?->translatedFormat('d F Y H:i') }}</p>
            @endif
            @if ($profil->catatan_verifikasi)
                <p class="mt-3 rounded-lg bg-canvas px-3 py-2 text-ink">{{ $profil->catatan_verifikasi }}</p>
            @endif
            @if ($profil->status_verifikasi === 'menunggu')
                <a href="{{ route('admin.verification.show', $profil) }}" class="btn-primary mt-4 w-full">Periksa Data Pekerja</a>
            @endif
        </div>
    </aside>
</div>

@endsection
