@extends('layouts.admin')
@section('title', 'Pemeriksaan Dokumen')
@section('content')

@include('components.page-header', [
    'crumb' => 'Admin / Verifikasi Pekerja / Pemeriksaan',
    'title' => 'Pemeriksaan Dokumen ' . $profil->labelKategori(),
    'subtitle' => $profil->pengguna->nama . ' · ' . $profil->labelKategori(),
    'aksi' => '<a href="' . route('admin.verification') . '" class="px-4 py-2 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-white">← Kembali</a>',
])

<div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
    <div class="lg:col-span-3">
        @include('components.detail-pekerja', ['tampilKontak' => true, 'tampilDokumen' => false])
    </div>

    <div class="lg:col-span-2 space-y-6">
        <form method="POST" action="{{ route('admin.verification.process', $profil) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            @csrf
            <h3 class="font-semibold text-[#172B23]">Dokumen persyaratan</h3>

            @foreach ($profil->dokumenWajib() as $jenis)
                @php $dok = $profil->dokumenTerbaru($jenis); @endphp
                <div class="border border-gray-200 rounded-xl p-3">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium">{{ \App\Models\DokumenPekerja::JENIS[$jenis] }}</p>
                        @if ($dok)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $dok->warnaStatus() }}">{{ $dok->labelStatus() }}</span>
                        @endif
                    </div>
                    @if ($dok)
                        {{-- Pratinjau dokumen --}}
                        @if ($dok->isGambar())
                            <a href="{{ $dok->url() }}" target="_blank"><img src="{{ $dok->url() }}" alt="{{ $dok->labelJenis() }}" class="w-full max-h-56 object-contain bg-[#F5F7F5] rounded-lg"></a>
                        @else
                            <iframe src="{{ $dok->url() }}" class="w-full h-56 rounded-lg bg-[#F5F7F5]" title="{{ $dok->labelJenis() }}"></iframe>
                        @endif
                        <div class="flex items-center justify-between mt-2">
                            <a href="{{ $dok->url() }}" target="_blank" class="text-xs text-[#176B4D] hover:underline">Buka di tab baru ↗</a>
                            <span class="text-[11px] text-[#52645B]">Diunggah {{ $dok->tanggal_unggah->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($profil->status_verifikasi === 'menunggu')
                            <div class="flex gap-4 mt-2 text-sm">
                                <label class="flex items-center gap-1.5"><input type="radio" name="dokumen[{{ $dok->id_dokumen }}]" value="valid" class="accent-[#176B4D]" @checked(old('dokumen.' . $dok->id_dokumen, 'valid') === 'valid')> Valid</label>
                                <label class="flex items-center gap-1.5"><input type="radio" name="dokumen[{{ $dok->id_dokumen }}]" value="tidak_valid" class="accent-red-600" @checked(old('dokumen.' . $dok->id_dokumen) === 'tidak_valid')> Tidak valid</label>
                            </div>
                        @endif
                    @else
                        <p class="text-xs text-red-600">Dokumen belum diunggah.</p>
                    @endif
                </div>
            @endforeach

            @if ($profil->status_verifikasi === 'menunggu')
                <div>
                    <label for="catatan" class="block text-sm font-medium mb-1.5">Catatan pemeriksaan</label>
                    <textarea id="catatan" name="catatan" rows="3" placeholder="Jelaskan perbaikan yang diperlukan (wajib bila diminta perbaikan / ditolak)"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#176B4D]">{{ old('catatan') }}</textarea>
                    @error('catatan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <button name="keputusan" value="perbaikan" class="py-2.5 border border-amber-400 text-amber-700 text-sm font-semibold rounded-xl hover:bg-amber-50"
                            onclick="return confirm('Minta pekerja memperbaiki data/dokumen?')">Minta Perbaikan</button>
                    <button name="keputusan" value="setujui" class="py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]"
                            onclick="return confirm('Setujui verifikasi pekerja ini? Profil akan tampil di katalog klien.')">Setujui</button>
                    <button name="keputusan" value="tolak" class="py-2.5 border border-red-300 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50"
                            onclick="return confirm('Tolak verifikasi pekerja ini?')">Tolak</button>
                </div>
            @else
                <div class="bg-[#F5F7F5] rounded-xl p-4 text-sm">
                    <p>Status saat ini: <span class="font-semibold">{{ $profil->labelStatus() }}</span></p>
                    @if ($profil->verifikator)
                        <p class="text-[#52645B] mt-1">Diperiksa oleh {{ $profil->verifikator->nama }} pada {{ $profil->tanggal_verifikasi?->translatedFormat('d F Y H:i') }}</p>
                    @endif
                    @if ($profil->catatan_verifikasi)
                        <p class="text-[#52645B] mt-1">Catatan: {{ $profil->catatan_verifikasi }}</p>
                    @endif
                    <p class="text-xs text-[#52645B] mt-2">Pemeriksaan hanya dapat dilakukan saat pekerja berstatus "Menunggu Verifikasi".</p>
                </div>
            @endif
        </form>
    </div>
</div>

@endsection
