@extends('layouts.admin')
@section('title', 'Katalog Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Admin / Katalog Pekerja',
    'title' => 'Katalog Pekerja',
    'subtitle' => 'Seluruh calon pekerja yang terdaftar beserta status verifikasinya.',
])

<div class="bg-gradient-to-r from-[#E4F2E8] to-white rounded-2xl border border-[#176B4D]/10 p-6 mb-6">
    <p class="text-[10px] font-semibold tracking-widest uppercase text-[#176B4D]">● Bantuan untuk rumah dan keluarga</p>
    <h3 class="text-2xl font-bold text-[#104C38] mt-2">Kebutuhan Anda,<br>keahlian mereka.</h3>
    <p class="text-sm text-[#52645B] mt-2">Pantau data calon pekerja sebelum dilakukan pengelolaan atau verifikasi lebih lanjut.</p>
</div>

@include('components.filter-pekerja', ['action' => route('admin.catalog'), 'filterStatus' => true])

<div class="flex items-center justify-between mb-4">
    <p class="font-semibold text-[#172B23]">Daftar pekerja <span class="text-xs font-normal text-[#52645B] ml-1">{{ $pekerja->total() }} pekerja</span></p>
    @if (request()->hasAny(['cari', 'kategori', 'lokasi', 'status']))
        <a href="{{ route('admin.catalog') }}" class="text-xs text-[#176B4D] hover:underline">Reset filter ↻</a>
    @endif
</div>

@if ($pekerja->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center text-[#52645B]">Pekerja yang sesuai tidak ditemukan.</div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach ($pekerja as $p)
            @include('components.worker-card', ['profil' => $p, 'link' => route('admin.workers.show', $p), 'tampilStatus' => true])
        @endforeach
    </div>
    <div class="mt-6">{{ $pekerja->links() }}</div>
@endif

@endsection
