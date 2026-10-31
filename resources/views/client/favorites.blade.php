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
    <p class="mb-5 inline-flex items-center gap-2 rounded-full bg-rose-50 px-4 py-2 text-[15px] font-bold text-rose-600"><x-icon name="heart" class="h-5 w-5 fill-current" /> {{ $favorit->count() }} pekerja tersimpan</p>
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        @foreach ($favorit as $f)
            @php $p = $f->profilPekerja; $tersedia = $p->status_verifikasi === 'terverifikasi' && $p->pengguna->isAktif(); @endphp
            <div class="flex flex-col">
                @include('components.worker-card', ['profil' => $p, 'link' => route('client.workers.show', $p), 'favorit' => true, 'tampilStatus' => ! $tersedia])
                @unless ($tersedia)
                    <p class="mt-2 flex items-center gap-1.5 rounded-xl bg-red-50 px-3 py-2 text-sm font-medium text-red-700"><x-icon name="alert-circle" class="h-4 w-4" /> Pekerja ini sedang tidak tersedia di katalog.</p>
                @endunless
            </div>
        @endforeach
    </div>
@endif

@endsection
