@extends('layouts.client')
@section('title', 'Katalog Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Katalog Pekerja',
    'title' => 'Temukan pekerja yang tepat',
    'subtitle' => 'Temukan ART, Baby Sitter, dan Perawat Lansia sesuai kebutuhan keluarga.',
])

{{-- Pencarian & filter --}}
<form method="GET" action="{{ route('client.workers') }}" class="card mb-6 p-3 sm:p-4" role="search">
    <input type="hidden" name="cari_kriteria" value="1">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
        <label class="relative flex-1">
            <span class="sr-only">Cari nama atau keahlian</span>
            <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-muted" />
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau keahlian pekerja..." class="input pl-11">
        </label>
        <select name="kategori" class="input lg:w-48" aria-label="Kategori">
            <option value="">Semua kategori</option>
            @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="lokasi" class="input lg:w-48" aria-label="Lokasi">
            <option value="">Semua lokasi</option>
            @foreach ($daftarLokasi as $lokasi)
                <option value="{{ $lokasi }}" @selected(request('lokasi') === $lokasi)>{{ $lokasi }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary"><x-icon name="search" class="h-4 w-4" /> Cari Pekerja</button>
    </div>

    <details class="group mt-3 rounded-xl border border-line bg-canvas/60 open:bg-white" @if ($idKriteria) open @endif>
        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3 text-sm font-semibold text-ink [&::-webkit-details-marker]:hidden">
            <span class="inline-flex items-center gap-2">
                <x-icon name="sliders" class="h-5 w-5 text-primary" /> Filter kriteria khusus
                @if ($idKriteria) <span class="rounded-full bg-primary px-2 py-0.5 text-xs text-white">{{ count($idKriteria) }} dipilih</span> @endif
            </span>
            <x-icon name="chevron-down" class="h-5 w-5 text-muted transition-transform group-open:rotate-180" />
        </summary>
        <div class="border-t border-line px-4 pb-4 pt-4">
            <div class="grid gap-x-6 gap-y-5 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($kelompokKriteria as $kategori => $daftar)
                    <fieldset>
                        <legend class="mb-2 text-xs font-semibold uppercase tracking-wider text-muted">{{ \App\Models\Kriteria::KATEGORI[$kategori] ?? $kategori }}</legend>
                        <div class="space-y-1">
                            @foreach ($daftar as $k)
                                <label class="flex cursor-pointer items-start gap-2.5 rounded-lg px-2 py-1.5 text-sm hover:bg-canvas" title="{{ $k->deskripsi }}">
                                    <input type="checkbox" name="kriteria[]" value="{{ $k->id_kriteria }}" class="mt-0.5 h-4 w-4 rounded accent-primary" @checked(in_array($k->id_kriteria, $idKriteria))>
                                    <span>{{ $k->nama_kriteria }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @endforeach
            </div>
            <div class="mt-4 flex flex-col gap-3 border-t border-line pt-4 sm:flex-row sm:items-center sm:justify-between">
                <label class="flex cursor-pointer items-center gap-2.5 text-sm">
                    <input type="checkbox" name="wajib_semua" value="1" class="h-4 w-4 rounded accent-primary" @checked($wajibSemua)>
                    Hanya tampilkan pekerja yang memenuhi <strong>semua</strong> kriteria
                </label>
                <div class="flex gap-2">
                    <a href="{{ route('client.workers', ['cari_kriteria' => 1]) }}" class="btn-secondary btn-sm"><x-icon name="rotate-ccw" class="h-4 w-4" /> Reset</a>
                    <button type="submit" class="btn-primary btn-sm">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </details>
</form>

<div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
    <p class="flex flex-wrap items-center gap-2 text-base font-semibold text-ink">
        Pekerja untuk Anda <span class="text-sm font-normal text-muted">{{ $pekerja->total() }} pekerja</span>
        @if ($idKriteria) <x-badge color="brand"><x-icon name="sparkles" class="h-3.5 w-3.5" /> Diurutkan berdasarkan skor kecocokan</x-badge> @endif
    </p>
    @if (request()->hasAny(['cari', 'kategori', 'lokasi', 'kriteria']))
        <a href="{{ route('client.workers', ['cari_kriteria' => 1]) }}" class="text-sm font-medium text-primary hover:underline">Hapus semua filter</a>
    @endif
</div>

@if ($pekerja->isEmpty())
    <div class="card">
        <x-empty-state illustration="pencarian" title="Pekerja yang sesuai tidak ditemukan"
            message="Coba kurangi kriteria, ubah kategori atau lokasi, atau hilangkan centang “memenuhi semua kriteria” agar sistem menampilkan pekerja yang paling mendekati."
            :action="route('client.workers', ['cari_kriteria' => 1])" action-label="Tampilkan semua pekerja" />
    </div>
@else
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($pekerja as $item)
            @include('components.worker-card', [
                'profil' => $item['profil'],
                'link' => route('client.workers.show', $item['profil']),
                'skor' => $item['skor'],
                'favorit' => in_array($item['profil']->id_pekerja, $idFavorit),
            ])
        @endforeach
    </div>
    <div class="mt-8">{{ $pekerja->links() }}</div>
@endif

@if ($idKriteria)
    <div class="mt-6 flex gap-3 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3.5 text-sm text-sky-900">
        <x-icon name="info" class="mt-0.5 h-5 w-5 text-sky-600" />
        <p><strong>Tentang skor kecocokan:</strong> dihitung dengan algoritma <em>Cosine Similarity</em> antara kriteria yang Anda pilih dan kriteria pekerja. Semakin mendekati 100%, semakin mirip profil pekerja dengan kebutuhan Anda.</p>
    </div>
@endif

@endsection
