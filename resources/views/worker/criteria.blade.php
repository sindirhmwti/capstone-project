@extends('layouts.worker')
@section('title', 'Kriteria Khusus')
@section('content')

@include('components.page-header', [
    'crumb' => 'Calon Pekerja / Kriteria Khusus',
    'title' => 'Kriteria Khusus',
    'subtitle' => 'Jawab sesuai kondisi Anda. Data ini dipakai untuk mencocokkan Anda dengan kebutuhan klien.',
])

<form method="POST" action="{{ route('worker.criteria.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <x-alert type="info" title="Jawab dengan jujur">Contoh: jika Anda memiliki fobia atau alergi kucing, pilih <strong>Tidak</strong> pada "Nyaman dengan kucing". Jawaban yang jujur membantu mengurangi ketidakcocokan.</x-alert>

    <div class="grid gap-6 2xl:grid-cols-2">
    @foreach ($kelompokKriteria as $kategori => $daftar)
        <fieldset class="card card-body reveal">
            <legend class="sr-only">{{ \App\Models\Kriteria::KATEGORI[$kategori] ?? $kategori }}</legend>
            <h2 class="card-title flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-soft text-primary"><x-icon name="list-checks" /></span>{{ \App\Models\Kriteria::KATEGORI[$kategori] ?? $kategori }}</h2>
            <div class="mt-3 divide-y divide-line">
                @foreach ($daftar as $k)
                    @php $nilai = old('kriteria.' . $k->id_kriteria, $nilaiKriteria[$k->id_kriteria] ?? null); @endphp
                    <div class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-base font-bold text-ink">{{ $k->nama_kriteria }}</p>
                            <p class="text-sm text-muted">{{ $k->deskripsi }}</p>
                        </div>
                        <div class="grid shrink-0 grid-cols-2 gap-2 sm:w-52" role="radiogroup" aria-label="{{ $k->nama_kriteria }}">
                            @foreach (['ya' => ['Ya', 'check'], 'tidak' => ['Tidak', 'x']] as $v => [$label, $ikon])
                                <label class="cursor-pointer">
                                    <input type="radio" name="kriteria[{{ $k->id_kriteria }}]" value="{{ $v }}" class="peer sr-only" @checked($nilai === $v)>
                                    <span class="flex items-center justify-center gap-1.5 rounded-full border-2 border-line px-4 py-2.5 text-[15px] font-bold text-muted transition-colors
                                                 hover:border-primary/40 peer-focus-visible:ring-4 peer-focus-visible:ring-primary/20
                                                 {{ $v === 'ya' ? 'peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white' : 'peer-checked:border-gray-500 peer-checked:bg-gray-600 peer-checked:text-white' }}">
                                        <x-icon :name="$ikon" class="h-4 w-4" /> {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </fieldset>
    @endforeach
    </div>

    <div class="sticky bottom-4 z-10 flex flex-col-reverse gap-3 rounded-3xl border border-line bg-white/95 p-4 shadow-lift backdrop-blur sm:flex-row sm:items-center sm:justify-end">
        <p class="flex items-center gap-2 text-[15px] text-muted sm:mr-auto"><x-icon name="info" class="h-5 w-5 text-primary" /> Semua kriteria wajib dijawab.</p>
        <a href="{{ route('worker.profile') }}" class="btn-secondary">Batal</a>
        <button type="submit" class="btn-primary"><x-icon name="check" class="h-5 w-5" /> Simpan Kriteria</button>
    </div>
</form>

@endsection
