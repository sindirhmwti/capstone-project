@extends('layouts.worker')
@section('title', 'Beranda Pekerja')
@section('content')

@include('components.page-header', ['crumb' => 'Calon Pekerja / Beranda', 'title' => 'Beranda Pekerja'])

{{-- Sambutan --}}
<div class="reveal relative mb-6 overflow-hidden rounded-2xl bg-primary p-6 text-white sm:p-8">
    <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/5"></div>
    <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-mint">{{ $profil->labelKategori() }}</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Selamat datang, {{ $pengguna->nama }}!</h2>
            <p class="mt-2 max-w-lg text-base text-white/80">Lengkapi profil agar informasi keahlian Anda tetap sesuai dan mudah ditemukan klien.</p>
        </div>
        <div class="w-full shrink-0 rounded-2xl bg-white/10 p-4 md:w-64">
            <div class="flex items-center justify-between text-sm">
                <span class="text-white/80">Kelengkapan profil</span>
                <span class="text-lg font-bold">{{ $profileCompletion }}%</span>
            </div>
            <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-white/20">
                <div class="h-2.5 rounded-full bg-mint transition-all duration-700" style="width: {{ $profileCompletion }}%"></div>
            </div>
        </div>
    </div>
</div>

{{-- Ringkasan --}}
<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <x-stat icon="user-check" label="Status akun" :value="ucfirst($pengguna->status_akun)" tone="brand" />
    <div class="card reveal flex items-start gap-4 p-5">
        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><x-icon name="shield-check" class="h-6 w-6" /></span>
        <div>
            <p class="text-sm text-muted">Status verifikasi</p>
            <div class="mt-1.5"><x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge></div>
        </div>
    </div>
    <x-stat icon="file-text" label="Dokumen diunggah" :value="$stats['documents_uploaded'] . ' / ' . count($profil->dokumenWajib())" tone="blue" :href="route('worker.verification')" />
    <x-stat icon="bell" label="Notifikasi baru" :value="$stats['notifications']" tone="rose" :href="route('worker.notifications')" />
</div>

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Langkah kelengkapan --}}
    <div class="card card-body reveal lg:col-span-2">
        <h2 class="card-title">Langkah melengkapi profil</h2>
        <p class="mt-1 text-sm text-muted">Selesaikan semua langkah agar profil Anda dapat diverifikasi admin.</p>
        <ol class="mt-5 space-y-3">
            @foreach ($profileSections as $i => $section)
                <li class="flex items-center gap-4 rounded-xl border p-3.5 {{ $section['done'] ? 'border-green-200 bg-green-50/50' : 'border-line' }}">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold {{ $section['done'] ? 'bg-green-600 text-white' : 'bg-canvas text-muted ring-1 ring-line' }}">
                        @if ($section['done']) <x-icon name="check" class="h-4 w-4" /> @else {{ $i + 1 }} @endif
                    </span>
                    <span class="flex-1 text-sm font-medium {{ $section['done'] ? 'text-ink' : 'text-muted' }}">{{ $section['label'] }}</span>
                    @if ($section['done'])
                        <span class="text-sm font-semibold text-green-700">Selesai</span>
                    @else
                        <a href="{{ $section['link'] }}" class="btn-outline btn-sm">Lengkapi</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>

    {{-- Notifikasi terbaru --}}
    <div class="card card-body reveal">
        <div class="flex items-center justify-between">
            <h2 class="card-title">Notifikasi terbaru</h2>
            <a href="{{ route('worker.notifications') }}" class="text-sm font-medium text-primary hover:underline">Lihat semua</a>
        </div>
        @forelse ($notifikasiTerbaru as $n)
            <div class="flex gap-3 border-b border-line py-3.5 last:border-0">
                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $n->sudah_dibaca ? 'bg-gray-300' : 'bg-primary-accent' }}"></span>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-ink">{{ $n->judul }}</p>
                    <p class="text-xs text-muted">{{ $n->tanggal_dibuat->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <x-empty-state illustration="notifikasi" title="Belum ada notifikasi" compact />
        @endforelse
    </div>
</div>

{{-- Status verifikasi --}}
@php
    $kartu = [
        'belum_lengkap' => ['border-line bg-white', 'info', 'text-primary', 'Lengkapi data untuk verifikasi', 'Profil Anda belum tampil di katalog klien. Lengkapi profil, kriteria khusus, dan dokumen, lalu ajukan verifikasi.'],
        'menunggu' => ['border-amber-200 bg-amber-50', 'clock', 'text-amber-600', 'Menunggu verifikasi admin', 'Dokumen Anda sedang diperiksa admin. Anda akan mendapat notifikasi setelah proses selesai.'],
        'terverifikasi' => ['border-green-200 bg-green-50', 'shield-check', 'text-green-600', 'Profil terverifikasi', 'Profil Anda sudah diverifikasi dan tampil di katalog pekerja untuk klien.'],
        'ditolak' => ['border-red-200 bg-red-50', 'alert-circle', 'text-red-600', 'Verifikasi ditolak', 'Perbaiki data sesuai catatan admin, lalu ajukan verifikasi kembali.'],
    ][$profil->status_verifikasi];
@endphp
<div class="reveal mt-6 flex flex-col gap-4 rounded-2xl border p-5 sm:flex-row sm:items-center sm:p-6 {{ $kartu[0] }}">
    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white {{ $kartu[2] }}"><x-icon :name="$kartu[1]" class="h-6 w-6" /></span>
    <div class="flex-1">
        <h3 class="text-base font-bold text-ink">{{ $kartu[3] }}</h3>
        <p class="mt-0.5 text-sm text-muted">{{ $kartu[4] }}</p>
        @if ($profil->bisaDiajukan() && $profil->catatan_verifikasi)
            <p class="mt-2 text-sm text-red-700"><strong>Catatan admin:</strong> {{ $profil->catatan_verifikasi }}</p>
        @endif
    </div>
    <a href="{{ route('worker.verification') }}" class="btn-primary">Lihat status verifikasi</a>
</div>

@endsection
