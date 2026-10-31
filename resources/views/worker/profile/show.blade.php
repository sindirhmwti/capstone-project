@extends('layouts.worker')
@section('title', 'Profil Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Calon Pekerja / Profil Pekerja',
    'title' => 'Profil Pekerja',
    'aksi' => '<a href="' . route('worker.profile.edit') . '" class="px-4 py-2 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Edit Profil</a>',
])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-4 pb-5 border-b border-gray-100 mb-5">
            <div class="w-14 h-14 rounded-full bg-[#E4F2E8] flex items-center justify-center text-lg font-bold text-[#176B4D]">{{ $pengguna->inisial() }}</div>
            <div>
                <p class="font-semibold text-[#172B23] text-lg">{{ $pengguna->nama }}</p>
                <p class="text-sm text-[#52645B]">{{ $profil->labelKategori() }}
                    <span class="ml-2 inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $profil->warnaStatus() }}">{{ $profil->labelStatus() }}</span>
                </p>
            </div>
        </div>

        <h3 class="font-semibold text-[#172B23] mb-3">Informasi pribadi</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            @foreach ([
                'Email' => $pengguna->email,
                'Nomor telepon' => $pengguna->no_telepon,
                'Tanggal lahir' => $profil->tanggal_lahir ? $profil->tanggal_lahir->translatedFormat('d F Y') . ' (' . $profil->hitungUsia() . ' tahun)' : null,
                'Jenis kelamin' => $profil->jenis_kelamin,
                'Domisili' => $profil->alamat,
                'Lama pengalaman' => $profil->lama_pengalaman . ' tahun',
            ] as $label => $nilai)
                <div>
                    <dt class="text-[#52645B]">{{ $label }}</dt>
                    <dd class="font-medium text-[#172B23] mt-0.5">{{ $nilai ?: '— belum diisi —' }}</dd>
                </div>
            @endforeach
        </dl>

        <h3 class="font-semibold text-[#172B23] mt-6 mb-2">Keterampilan</h3>
        <div class="flex flex-wrap gap-2">
            @forelse ($profil->daftarKeterampilan() as $k)
                <span class="px-3 py-1 rounded-full bg-[#E4F2E8] text-[#176B4D] text-xs font-medium">{{ $k }}</span>
            @empty
                <span class="text-sm text-[#52645B]">— belum diisi —</span>
            @endforelse
        </div>

        <h3 class="font-semibold text-[#172B23] mt-6 mb-2">Pengalaman &amp; informasi pendukung</h3>
        <p class="text-sm text-[#52645B] leading-relaxed whitespace-pre-line">{{ $profil->deskripsi_profil ?: '— belum diisi —' }}</p>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-[#172B23]">Kriteria khusus</h3>
                <a href="{{ route('worker.criteria') }}" class="text-xs text-[#176B4D] font-medium hover:underline">Ubah</a>
            </div>
            @if (empty($nilaiKriteria))
                <p class="text-sm text-[#52645B]">Kriteria khusus belum diisi.</p>
            @else
                <ul class="space-y-2 text-sm">
                    @foreach ($semuaKriteria as $k)
                        <li class="flex items-center justify-between gap-2">
                            <span class="text-[#172B23]">{{ $k->nama_kriteria }}</span>
                            @if (($nilaiKriteria[$k->id_kriteria] ?? null) === 'ya')
                                <span class="text-xs font-semibold text-green-700 bg-green-100 px-2 py-0.5 rounded-full">Ya</span>
                            @else
                                <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">Tidak</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-[#172B23]">Dokumen</h3>
                <a href="{{ route('worker.verification') }}" class="text-xs text-[#176B4D] font-medium hover:underline">Kelola</a>
            </div>
            @foreach ($profil->dokumenWajib() as $jenis)
                @php $dok = $profil->dokumenTerbaru($jenis); @endphp
                <div class="flex items-center justify-between py-2 text-sm">
                    <span>{{ \App\Models\DokumenPekerja::JENIS[$jenis] }}</span>
                    @if ($dok)
                        <a href="{{ $dok->url() }}" target="_blank" class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $dok->warnaStatus() }}">{{ $dok->labelStatus() }}</a>
                    @else
                        <span class="text-xs text-red-600">Belum diunggah</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
