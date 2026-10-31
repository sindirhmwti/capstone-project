@extends('layouts.admin')
@section('title', 'Katalog Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Admin / Katalog Pekerja',
    'title' => 'Katalog Pekerja',
    'subtitle' => 'Seluruh calon pekerja yang terdaftar beserta status verifikasinya.',
])

@include('components.filter-pekerja', ['action' => route('admin.catalog'), 'filterStatus' => true])

<div class="mb-5 flex items-center justify-between">
    <p class="flex items-center gap-2.5 text-lg font-extrabold text-ink">Daftar pekerja <span class="rounded-full bg-white px-3 py-1 text-sm font-bold text-muted ring-1 ring-line">{{ $pekerja->total() }} pekerja</span></p>
    @if (request()->hasAny(['cari', 'kategori', 'lokasi', 'status']))
        <a href="{{ route('admin.catalog') }}" class="text-sm font-medium text-primary hover:underline">Hapus filter</a>
    @endif
</div>

@if ($pekerja->isEmpty())
    <div class="card"><x-empty-state illustration="pencarian" title="Pekerja yang sesuai tidak ditemukan." message="Ubah kata kunci atau filter pencarian." :action="route('admin.catalog')" action-label="Tampilkan semua" /></div>
@else
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
        @foreach ($pekerja as $p)
            @include('components.worker-card', ['profil' => $p, 'link' => route('admin.workers.show', $p), 'tampilStatus' => true])
        @endforeach
    </div>
    <div class="mt-8">{{ $pekerja->links() }}</div>
@endif

@endsection
