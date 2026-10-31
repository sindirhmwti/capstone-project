@extends('layouts.client')
@section('title', 'Favorit Saya')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Favorit Saya',
    'title' => 'Favorit Saya',
    'subtitle' => 'Pekerja yang Anda simpan untuk dipertimbangkan kembali.',
    'aksi' => '<a href="' . route('client.workers') . '" class="btn-outline">Cari pekerja lain</a>',
])

@if ($favorit->isEmpty())
    <div class="card">
        <x-empty-state illustration="favorit" title="Belum terdapat pekerja yang disimpan sebagai favorit."
            message="Tekan ikon hati pada kartu pekerja di katalog untuk menyimpan pekerja yang Anda minati."
            :action="route('client.workers')" action-label="Buka Katalog Pekerja" />
    </div>
@else
    <p class="mb-4 text-sm text-muted">{{ $favorit->count() }} pekerja tersimpan</p>
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($favorit as $f)
            @php $p = $f->profilPekerja; $tersedia = $p->status_verifikasi === 'terverifikasi' && $p->pengguna->isAktif(); @endphp
            <div class="flex flex-col">
                @include('components.worker-card', ['profil' => $p, 'link' => route('client.workers.show', $p), 'favorit' => true, 'tampilStatus' => ! $tersedia])
                @unless ($tersedia)
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-red-600"><x-icon name="alert-circle" class="h-4 w-4" /> Pekerja ini sedang tidak tersedia di katalog.</p>
                @endunless
            </div>
        @endforeach
    </div>
@endif

@endsection
