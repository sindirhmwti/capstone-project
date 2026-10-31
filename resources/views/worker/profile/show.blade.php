@extends('layouts.worker')
@section('title', 'Profil Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Calon Pekerja / Profil Pekerja',
    'title' => 'Profil Pekerja',
    'subtitle' => 'Informasi ini dipakai admin untuk verifikasi dan klien untuk pencocokan.',
    'aksi' => '<a href="' . route('worker.profile.edit') . '" class="btn-primary">Edit Profil</a>',
])

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        @include('components.detail-pekerja', ['tampilKontak' => true, 'tampilDokumen' => false])
    </div>

    <div class="space-y-6">
        <div class="card card-body reveal">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="card-title">Dokumen</h2>
                <a href="{{ route('worker.verification') }}" class="text-sm font-medium text-primary hover:underline">Kelola</a>
            </div>
            <div class="space-y-2.5">
                @foreach ($profil->dokumenWajib() as $jenis)
                    @php $dok = $profil->dokumenTerbaru($jenis); @endphp
                    <div class="flex items-center justify-between gap-2 rounded-xl border border-line p-3 text-sm">
                        <span class="flex items-center gap-2 font-medium"><x-icon name="file-text" class="h-4 w-4 text-primary" /> {{ \App\Models\DokumenPekerja::JENIS[$jenis] }}</span>
                        @if ($dok)
                            <a href="{{ $dok->url() }}" target="_blank" rel="noopener"><x-badge :status="$dok->status_verifikasi">{{ $dok->labelStatus() }}</x-badge></a>
                        @else
                            <x-badge color="red">Belum diunggah</x-badge>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card card-body reveal">
            <h2 class="card-title">Ubah data cepat</h2>
            <div class="mt-4 space-y-2">
                <a href="{{ route('worker.profile.edit') }}" class="btn-secondary w-full justify-start"><x-icon name="pencil" class="h-4 w-4 text-primary" /> Edit data pribadi</a>
                <a href="{{ route('worker.criteria') }}" class="btn-secondary w-full justify-start"><x-icon name="list-checks" class="h-4 w-4 text-primary" /> Ubah kriteria khusus</a>
                <a href="{{ route('worker.verification') }}" class="btn-secondary w-full justify-start"><x-icon name="upload" class="h-4 w-4 text-primary" /> Unggah dokumen</a>
            </div>
        </div>
    </div>
</div>

@endsection
