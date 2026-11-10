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
            @php
                $p = $f->profilPekerja;
                $tersalurkan = $p->penyaluranAktif !== null;
                $tersedia = $p->status_verifikasi === 'terverifikasi' && $p->pengguna->isAktif() && ! $tersalurkan;
            @endphp
            <div class="flex flex-col {{ $tersalurkan ? 'opacity-90' : '' }}">
                @include('components.worker-card', ['profil' => $p, 'link' => route('client.workers.show', $p), 'favorit' => true, 'tampilStatus' => ! $tersedia && ! $tersalurkan])
                @if ($tersalurkan)
                    <div class="mt-2 flex items-center justify-between gap-2 rounded-xl bg-gray-100 px-3 py-2">
                        <x-badge color="gray">Sudah Tersalurkan</x-badge>
                        <button type="button" class="btn-secondary btn-sm cursor-not-allowed opacity-60" disabled aria-disabled="true" title="Pekerja ini sudah tersalurkan dan tidak tersedia"><x-icon name="clipboard" class="h-4 w-4" /> Pesan</button>
                    </div>
                @elseif (! $tersedia)
                    <p class="mt-2 flex items-center gap-1.5 rounded-xl bg-red-50 px-3 py-2 text-sm font-medium text-red-700"><x-icon name="alert-circle" class="h-4 w-4" /> Pekerja ini sedang tidak tersedia di katalog.</p>
                @endif
            </div>
        @endforeach
    </div>
@endif

@endsection
