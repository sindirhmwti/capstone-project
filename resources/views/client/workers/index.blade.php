@extends('layouts.client')
@section('title', 'Katalog Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Katalog Pekerja',
    'title' => 'Temukan pekerja yang tepat',
    'subtitle' => 'Pilih kategori dan kriteria khusus rumah Anda, lalu sistem mengurutkan pekerja berdasarkan skor kecocokan.',
])

<div class="grid gap-6 lg:grid-cols-[320px_1fr] xl:grid-cols-[340px_1fr]">
    {{-- Panel filter (kiri) --}}
    <aside class="lg:sticky lg:top-28 lg:h-fit">
        <details class="group card overflow-hidden lg:[&>summary]:hidden" open data-filter-panel>
            <summary class="flex cursor-pointer items-center justify-between gap-3 px-5 py-4 text-base font-bold text-ink">
                <span class="flex items-center gap-2"><x-icon name="sliders" class="h-5 w-5 text-primary" /> Filter pencarian
                    @if ($idKriteria) <span class="rounded-full bg-primary px-2 py-0.5 text-xs text-white">{{ count($idKriteria) }}</span> @endif
                </span>
                <x-icon name="chevron-down" class="h-5 w-5 text-muted transition-transform group-open:rotate-180" />
            </summary>

            <form method="GET" action="{{ route('client.workers') }}" class="space-y-6 border-t border-line p-5 lg:border-t-0" role="search">
                <input type="hidden" name="cari_kriteria" value="1">

                <div>
                    <label for="cari" class="label">Kata kunci</label>
                    <div class="relative">
                        <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-muted/70" />
                        <input type="text" id="cari" name="cari" value="{{ request('cari') }}" placeholder="Nama, keahlian, lokasi..." class="input pl-11">
                    </div>
                </div>

                <fieldset>
                    <legend class="label">Kategori</legend>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['' => 'Semua'] + \App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                            <label>
                                <input type="radio" name="kategori" value="{{ $kode }}" class="peer sr-only" @checked((string) request('kategori', '') === (string) $kode)>
                                <span class="chip">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div>
                    <label for="lokasi" class="label">Lokasi</label>
                    <select id="lokasi" name="lokasi" class="input">
                        <option value="">Semua lokasi</option>
                        @foreach ($daftarLokasi as $lokasi)
                            <option value="{{ $lokasi }}" @selected(request('lokasi') === $lokasi)>{{ $lokasi }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="border-t border-line pt-5">
                    <p class="label flex items-center gap-2"><x-icon name="sparkles" class="h-5 w-5 text-sun-dark" /> Kriteria khusus</p>
                    <div class="space-y-4">
                        @foreach ($kelompokKriteria as $kategori => $daftar)
                            <fieldset>
                                <legend class="mb-2 text-xs font-bold uppercase tracking-wider text-muted">{{ \App\Models\Kriteria::KATEGORI[$kategori] ?? $kategori }}</legend>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($daftar as $k)
                                        <label title="{{ $k->deskripsi }}">
                                            <input type="checkbox" name="kriteria[]" value="{{ $k->id_kriteria }}" class="peer sr-only" @checked(in_array($k->id_kriteria, $idKriteria))>
                                            <span class="chip text-[13px]">{{ $k->nama_kriteria }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach
                    </div>
                </div>

                <label class="flex cursor-pointer items-start gap-3 rounded-2xl bg-canvas p-3.5 text-sm">
                    <input type="checkbox" name="wajib_semua" value="1" class="mt-0.5 h-5 w-5 shrink-0 rounded accent-primary" @checked($wajibSemua)>
                    <span>Hanya tampilkan pekerja yang memenuhi <strong>semua</strong> kriteria</span>
                </label>

                <div class="grid grid-cols-[auto_1fr] gap-2">
                    <a href="{{ route('client.workers', ['cari_kriteria' => 1]) }}" class="btn-secondary px-4" data-tip="Reset filter" aria-label="Reset filter"><x-icon name="rotate-ccw" class="h-5 w-5" /></a>
                    <button type="submit" class="btn-primary"><x-icon name="search" class="h-5 w-5" /> Cari Pekerja</button>
                </div>
            </form>
        </details>
    </aside>

    {{-- Hasil (kanan) --}}
    <section class="min-w-0">
        <div class="mb-5 flex flex-col gap-3 rounded-3xl bg-white p-4 shadow-soft ring-1 ring-line sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <div class="flex flex-wrap items-center gap-2.5">
                <p class="text-lg font-extrabold text-ink">Pekerja untuk Anda</p>
                <span class="rounded-full bg-canvas px-3 py-1 text-sm font-bold text-muted">{{ $pekerja->total() }} pekerja</span>
                @if ($idKriteria) <x-badge color="amber"><x-icon name="sparkles" class="h-3.5 w-3.5" /> Diurutkan berdasarkan skor kecocokan</x-badge> @endif
            </div>
            @if (request()->hasAny(['cari', 'kategori', 'lokasi', 'kriteria']))
                <a href="{{ route('client.workers', ['cari_kriteria' => 1]) }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-primary hover:underline"><x-icon name="x" class="h-4 w-4" /> Hapus semua filter</a>
            @endif
        </div>

        @if ($pekerja->isEmpty())
            <div class="card">
                <x-empty-state illustration="pencarian" title="Pekerja yang sesuai tidak ditemukan"
                    message="Coba kurangi kriteria, ubah kategori atau lokasi, atau hilangkan centang “memenuhi semua kriteria” agar sistem menampilkan pekerja yang paling mendekati."
                    :action="route('client.workers', ['cari_kriteria' => 1])" action-label="Tampilkan semua pekerja" />
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 2xl:grid-cols-3">
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
            <x-alert type="info" title="Tentang skor kecocokan" class="mt-6">Dihitung dengan algoritma <em>Cosine Similarity</em> antara kriteria yang Anda pilih dan kriteria pekerja. Semakin mendekati 100%, semakin mirip profil pekerja dengan kebutuhan Anda.</x-alert>
        @endif
    </section>
</div>

@endsection
