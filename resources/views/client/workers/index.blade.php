@extends('layouts.client')
@section('title', 'Katalog Pekerja')
@section('content')

@php $sel = 'px-3 py-2.5 border border-gray-200 rounded-xl bg-white text-sm text-[#172B23] focus:outline-none focus:ring-2 focus:ring-[#176B4D]'; @endphp

@include('components.page-header', [
    'crumb' => 'Klien / Katalog Pekerja',
    'title' => 'Temukan pekerja yang tepat',
    'subtitle' => 'Temukan ART, Baby Sitter, dan Perawat Lansia sesuai kebutuhan keluarga.',
])

{{-- Hero --}}
<div class="relative overflow-hidden bg-gradient-to-r from-[#E4F2E8] to-white rounded-2xl border border-[#176B4D]/10 p-6 mb-6">
    <p class="text-[10px] font-semibold tracking-widest uppercase text-[#176B4D]">● Bantuan untuk rumah dan keluarga</p>
    <h3 class="text-2xl font-bold text-[#104C38] mt-2">Kebutuhan Anda,<br>keahlian mereka.</h3>
    <p class="text-sm text-[#52645B] mt-2 max-w-md">Pilih kriteria khusus rumah Anda — sistem akan mengurutkan pekerja berdasarkan skor kecocokan.</p>
    <div class="flex flex-wrap gap-4 mt-3 text-xs text-[#52645B]">
        <span>✓ Status profil transparan</span><span>✓ Sesuai keahlian</span><span>✓ Diverifikasi admin</span>
    </div>
</div>

{{-- Pencarian & filter --}}
<form method="GET" action="{{ route('client.workers') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6 space-y-4">
    <input type="hidden" name="cari_kriteria" value="1">
    <div class="flex flex-col lg:flex-row gap-3">
        <div class="flex items-center gap-2 flex-1 px-3 border border-gray-200 rounded-xl">
            <svg class="w-4 h-4 text-[#52645B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau keahlian pekerja..." class="flex-1 py-2.5 text-sm outline-none bg-transparent">
        </div>
        <select name="kategori" class="{{ $sel }}">
            <option value="">Semua Kategori</option>
            @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="lokasi" class="{{ $sel }}">
            <option value="">Semua lokasi</option>
            @foreach ($daftarLokasi as $lokasi)
                <option value="{{ $lokasi }}" @selected(request('lokasi') === $lokasi)>{{ $lokasi }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38] flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
            Cari Pekerja
        </button>
    </div>

    <details class="border-t border-gray-100 pt-4" @if ($idKriteria) open @endif>
        <summary class="cursor-pointer text-sm font-semibold text-[#176B4D] select-none">
            Filter kriteria khusus {{ $idKriteria ? '(' . count($idKriteria) . ' dipilih)' : '' }}
        </summary>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mt-4">
            @foreach ($kelompokKriteria as $kategori => $daftar)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#52645B] mb-2">{{ \App\Models\Kriteria::KATEGORI[$kategori] ?? $kategori }}</p>
                    @foreach ($daftar as $k)
                        <label class="flex items-start gap-2 text-sm py-1 cursor-pointer">
                            <input type="checkbox" name="kriteria[]" value="{{ $k->id_kriteria }}" class="mt-0.5 accent-[#176B4D]" @checked(in_array($k->id_kriteria, $idKriteria))>
                            <span title="{{ $k->deskripsi }}">{{ $k->nama_kriteria }}</span>
                        </label>
                    @endforeach
                </div>
            @endforeach
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-4 pt-4 border-t border-gray-100">
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" name="wajib_semua" value="1" class="accent-[#176B4D]" @checked($wajibSemua)>
                Hanya tampilkan pekerja yang memenuhi <strong>semua</strong> kriteria
            </label>
            <div class="flex gap-2">
                <a href="{{ route('client.workers', ['cari_kriteria' => 1]) }}" class="px-4 py-2 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-gray-50">Reset</a>
                <button type="submit" class="px-4 py-2 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Terapkan Filter</button>
            </div>
        </div>
    </details>
</form>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
    <p class="font-semibold text-[#172B23]">
        Pekerja untuk Anda <span class="text-xs font-normal text-[#52645B] ml-1">{{ $pekerja->total() }} pekerja</span>
        @if ($idKriteria) <span class="ml-2 text-xs font-medium px-2 py-0.5 rounded-full bg-[#E4F2E8] text-[#176B4D]">Diurutkan berdasarkan skor kecocokan</span> @endif
    </p>
    @if (request()->hasAny(['cari', 'kategori', 'lokasi', 'kriteria']))
        <a href="{{ route('client.workers', ['cari_kriteria' => 1]) }}" class="text-xs text-[#176B4D] hover:underline">Reset semua filter ↻</a>
    @endif
</div>

@if ($pekerja->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
        <p class="font-semibold text-[#172B23]">Pekerja yang sesuai tidak ditemukan.</p>
        <p class="text-sm text-[#52645B] mt-1">Coba kurangi kriteria, ubah kategori/lokasi, atau hilangkan centang "memenuhi semua kriteria" agar sistem menampilkan pekerja yang paling mendekati.</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach ($pekerja as $item)
            @include('components.worker-card', [
                'profil' => $item['profil'],
                'link' => route('client.workers.show', $item['profil']),
                'skor' => $item['skor'],
                'favorit' => in_array($item['profil']->id_pekerja, $idFavorit),
            ])
        @endforeach
    </div>
    <div class="mt-6">{{ $pekerja->links() }}</div>
@endif

@if ($idKriteria)
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-xs text-blue-800">
        <strong>Tentang skor kecocokan:</strong> dihitung dengan algoritma <em>Cosine Similarity</em> antara vektor kriteria yang Anda pilih
        (1 = dipilih, 0 = tidak) dan vektor kriteria pekerja (1 = ya, 0 = tidak). Semakin mendekati 100%, semakin mirip profil pekerja dengan kebutuhan Anda.
    </div>
@endif

@endsection
