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

    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 text-sm">
        Contoh: jika Anda memiliki fobia atau alergi kucing, pilih <strong>Tidak</strong> pada "Nyaman dengan kucing".
        Jawaban yang jujur membantu mengurangi ketidakcocokan dan pengembalian pekerja.
    </div>

    @foreach ($kelompokKriteria as $kategori => $daftar)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-[#172B23] mb-4">{{ \App\Models\Kriteria::KATEGORI[$kategori] ?? $kategori }}</h3>
            <div class="divide-y divide-gray-100">
                @foreach ($daftar as $k)
                    @php $nilai = old('kriteria.' . $k->id_kriteria, $nilaiKriteria[$k->id_kriteria] ?? null); @endphp
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <p class="text-sm font-medium text-[#172B23]">{{ $k->nama_kriteria }}</p>
                            <p class="text-xs text-[#52645B]">{{ $k->deskripsi }}</p>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            @foreach (['ya' => 'Ya', 'tidak' => 'Tidak'] as $v => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="kriteria[{{ $k->id_kriteria }}]" value="{{ $v }}" class="sr-only peer" @checked($nilai === $v)>
                                    <span class="inline-block px-4 py-1.5 rounded-lg border text-sm border-gray-200 text-[#52645B]
                                                 peer-checked:bg-[#176B4D] peer-checked:text-white peer-checked:border-[#176B4D] hover:border-[#176B4D]">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="flex justify-end gap-3">
        <a href="{{ route('worker.profile') }}" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-gray-50">Batal</a>
        <button type="submit" class="px-5 py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Simpan Kriteria</button>
    </div>
</form>

@endsection
