@extends('layouts.worker')
@section('title', 'Status Verifikasi')
@section('content')

@php use App\Models\DokumenPekerja; @endphp

@include('components.page-header', ['crumb' => 'Calon Pekerja / Status Verifikasi', 'title' => 'Status Verifikasi'])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-[#172B23]">Verifikasi Dokumen {{ $profil->labelKategori() }}</h3>
                    <p class="text-sm text-[#52645B]">Dokumen pendukung akan diperiksa oleh Admin.</p>
                </div>
                <svg class="w-6 h-6 text-[#176B4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>

            <div class="bg-[#F5F7F5] rounded-xl px-4 py-3 mb-5">
                <p class="font-medium text-[#172B23]">{{ $pengguna->nama }}</p>
                <p class="text-xs text-[#52645B]">Kategori {{ $profil->labelKategori() }} · Akun {{ ucfirst($pengguna->status_akun) }}</p>
            </div>

            @if ($profil->bisaDiajukan() && $profil->catatan_verifikasi)
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-5">
                    <strong>Catatan pemeriksaan admin:</strong> {{ $profil->catatan_verifikasi }}
                </div>
            @endif

            {{-- Dokumen wajib --}}
            <div class="space-y-4">
                @foreach ($profil->dokumenWajib() as $jenis)
                    @php $dok = $profil->dokumenTerbaru($jenis); @endphp
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <div>
                                <p class="font-medium text-[#172B23] text-sm">
                                    {{ DokumenPekerja::JENIS[$jenis] }} <span class="text-red-500">*</span>
                                </p>
                                <p class="text-xs text-[#52645B]">
                                    {{ $jenis === 'ktp' ? 'Foto / pindaian KTP yang masih berlaku.' : 'Sertifikat pelatihan ' . strtolower($profil->labelKategori()) . ' (wajib).' }}
                                </p>
                            </div>
                            @if ($dok)
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $dok->warnaStatus() }}">{{ $dok->labelStatus() }}</span>
                                    <a href="{{ $dok->url() }}" target="_blank" class="text-xs text-[#176B4D] font-medium hover:underline">Lihat berkas</a>
                                </div>
                            @else
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Belum diunggah</span>
                            @endif
                        </div>

                        @if ($profil->bisaDiajukan())
                            <form method="POST" action="{{ route('worker.verification.upload') }}" enctype="multipart/form-data"
                                  class="flex flex-col sm:flex-row gap-3 items-start sm:items-center bg-[#F5F7F5] border border-dashed border-gray-300 rounded-xl p-3">
                                @csrf
                                <input type="hidden" name="jenis_dokumen" value="{{ $jenis }}">
                                <input type="file" name="berkas" accept=".pdf,.jpg,.jpeg,.png" required
                                       class="text-sm text-[#52645B] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-white file:text-[#176B4D] file:font-medium">
                                <button type="submit" class="px-4 py-2 bg-white border border-[#176B4D] text-[#176B4D] text-sm font-semibold rounded-lg hover:bg-[#E4F2E8]">
                                    {{ $dok ? 'Ganti' : 'Unggah' }}
                                </button>
                            </form>
                            <p class="text-[11px] text-[#52645B] mt-1">PDF, JPG, PNG · Maksimal 2 MB</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <p class="text-xs text-[#52645B] mt-5">Admin akan memeriksa dokumen Anda sebelum memberikan status terverifikasi. Dokumen tidak ditampilkan pada katalog publik.</p>

            @if ($profil->bisaDiajukan())
                @unless ($siapDiajukan)
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 text-sm mt-4">
                        Data belum lengkap. Pengajuan verifikasi belum dapat dikirim.
                    </div>
                @endunless
                <form method="POST" action="{{ route('worker.verification.submit') }}" class="mt-4">
                    @csrf
                    <button type="submit" @disabled(! $siapDiajukan)
                            class="w-full py-3 bg-[#176B4D] text-white font-semibold rounded-xl hover:bg-[#104C38] disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ $profil->status_verifikasi === 'ditolak' ? 'Ajukan Ulang Verifikasi' : 'Kirim untuk Verifikasi' }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Status --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 h-fit">
        <h3 class="font-semibold text-[#172B23] mb-4">Status Anda</h3>
        <dl class="space-y-4 text-sm">
            <div>
                <dt class="text-xs text-[#52645B]">Status akun</dt>
                <dd class="font-medium">{{ ucfirst($pengguna->status_akun) }}</dd>
            </div>
            @foreach ($syarat as $label => $ok)
                <div>
                    <dt class="text-xs text-[#52645B]">{{ $label }}</dt>
                    <dd class="font-medium {{ $ok ? 'text-green-700' : 'text-red-600' }}">{{ $ok ? 'Sudah' : 'Belum' }}</dd>
                </div>
            @endforeach
            <div>
                <dt class="text-xs text-[#52645B]">Kesediaan katalog</dt>
                <dd class="font-medium">{{ $profil->status_verifikasi === 'terverifikasi' && $pengguna->isAktif() ? 'Ditampilkan' : 'Belum ditampilkan' }}</dd>
            </div>
            <div>
                <dt class="text-xs text-[#52645B]">Status verifikasi</dt>
                <dd><span class="inline-flex mt-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $profil->warnaStatus() }}">{{ $profil->labelStatus() }}</span></dd>
            </div>
            @if ($profil->tanggal_verifikasi)
                <div>
                    <dt class="text-xs text-[#52645B]">Diperiksa pada</dt>
                    <dd class="font-medium">{{ $profil->tanggal_verifikasi->translatedFormat('d F Y H:i') }}</dd>
                </div>
            @endif
        </dl>
        <p class="text-xs text-[#52645B] mt-5">Pembuatan akun dan unggah dokumen tidak sama dengan persetujuan verifikasi.</p>
        @unless ($syarat['Data pribadi & profil lengkap'])
            <a href="{{ route('worker.profile.edit') }}" class="block mt-4 text-sm text-[#176B4D] font-medium hover:underline">Lengkapi profil →</a>
        @endunless
        @unless ($syarat['Kriteria khusus sudah diisi'])
            <a href="{{ route('worker.criteria') }}" class="block mt-2 text-sm text-[#176B4D] font-medium hover:underline">Isi kriteria khusus →</a>
        @endunless
    </div>
</div>

@endsection
