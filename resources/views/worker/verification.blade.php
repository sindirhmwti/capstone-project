@extends('layouts.worker')
@section('title', 'Status Verifikasi')
@section('content')

@php use App\Models\DokumenPekerja; @endphp

@include('components.page-header', ['crumb' => 'Calon Pekerja / Status Verifikasi', 'title' => 'Status Verifikasi', 'subtitle' => 'Unggah dokumen persyaratan lalu ajukan verifikasi ke admin.'])

<div class="card reveal mb-6">
    <div class="card-head">
        <div>
            <h2 class="card-title">Tahapan verifikasi</h2>
            <p class="text-sm text-muted">Posisi profil Anda saat ini dalam proses pemeriksaan admin.</p>
        </div>
        <x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge>
    </div>
    <div class="card-body">@include('components.timeline-verifikasi', ['status' => $profil->status_verifikasi])</div>
</div>

<div class="grid gap-6 xl:grid-cols-3">
    <div class="space-y-6 xl:col-span-2">
        <div class="card card-body">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="card-title">Dokumen {{ $profil->labelKategori() }}</h2>
                    <p class="mt-1 text-sm text-muted">
                        {{ $profil->wajibSertifikat() ? 'Wajib KTP dan sertifikat pelatihan.' : 'Wajib KTP. ART tidak memerlukan sertifikat profesi.' }}
                    </p>
                </div>
                <x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge>
            </div>

            @if ($profil->bisaDiajukan() && $profil->catatan_verifikasi)
                <x-alert type="danger" title="Catatan pemeriksaan admin" class="mt-5">{{ $profil->catatan_verifikasi }}</x-alert>
            @endif

            <div class="mt-6 space-y-4">
                @foreach ($profil->dokumenWajib() as $jenis)
                    @php $dok = $profil->dokumenTerbaru($jenis); @endphp
                    <div class="rounded-3xl border border-line p-4 sm:p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $dok ? 'bg-primary-soft text-primary' : 'bg-canvas text-muted' }}"><x-icon name="file-text" class="h-6 w-6" /></span>
                                <div>
                                    <p class="text-lg font-bold text-ink">{{ DokumenPekerja::JENIS[$jenis] }} <span class="required">*</span></p>
                                    <p class="text-sm text-muted">{{ $jenis === 'ktp' ? 'Foto atau pindaian KTP yang masih berlaku.' : 'Sertifikat pelatihan ' . strtolower($profil->labelKategori()) . '.' }}</p>
                                </div>
                            </div>
                            @if ($dok)
                                <div class="flex items-center gap-3">
                                    <x-badge :status="$dok->status_verifikasi">{{ $dok->labelStatus() }}</x-badge>
                                    <a href="{{ $dok->url() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-sm font-medium text-primary hover:underline">Lihat <x-icon name="external-link" class="h-3.5 w-3.5" /></a>
                                </div>
                            @else
                                <x-badge color="gray">Belum diunggah</x-badge>
                            @endif
                        </div>

                        @if ($profil->bisaDiajukan())
                            <form method="POST" action="{{ route('worker.verification.upload') }}" enctype="multipart/form-data"
                                  class="mt-4 flex flex-col gap-3 rounded-2xl border-2 border-dashed border-primary/25 bg-mint-soft p-4 transition-colors hover:border-primary/50 sm:flex-row sm:items-center">
                                @csrf
                                <input type="hidden" name="jenis_dokumen" value="{{ $jenis }}">
                                <span class="hidden h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-primary shadow-sm sm:flex"><x-icon name="upload" class="h-6 w-6" /></span>
                                <div class="min-w-0 flex-1">
                                    <label class="inline-flex cursor-pointer items-center gap-3">
                                        <input type="file" name="berkas" accept=".pdf,.jpg,.jpeg,.png" required class="peer sr-only" data-file-input
                                               aria-label="Pilih berkas {{ DokumenPekerja::JENIS[$jenis] }}">
                                        <span class="rounded-full bg-white px-4 py-2.5 text-[15px] font-bold text-primary shadow-sm ring-1 ring-line transition-colors hover:bg-primary-soft peer-focus-visible:ring-4 peer-focus-visible:ring-primary/20">Pilih berkas</span>
                                        <span class="truncate text-sm text-muted" data-file-name>Belum ada berkas dipilih</span>
                                    </label>
                                    <span class="text-helper mt-1.5 block">PDF, JPG, atau PNG · maksimal 2 MB</span>
                                </div>
                                <button type="submit" class="btn-primary btn-sm">{{ $dok ? 'Ganti berkas' : 'Unggah' }}</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <p class="mt-5 flex items-center gap-2 text-sm text-muted"><x-icon name="lock" class="h-4 w-4" /> Dokumen hanya dapat dilihat oleh Anda dan admin, tidak ditampilkan di katalog.</p>

            @if ($profil->bisaDiajukan())
                <div class="mt-6 border-t border-line pt-6">
                    @unless ($siapDiajukan)
                        <x-alert type="warning" class="mb-4">Data belum lengkap. Lengkapi semua syarat di samping sebelum mengajukan verifikasi.</x-alert>
                    @endunless
                    <form method="POST" action="{{ route('worker.verification.submit') }}">
                        @csrf
                        <button type="submit" @disabled(! $siapDiajukan) class="btn-primary btn-lg w-full">
                            <x-icon name="send" class="h-5 w-5" />
                            {{ $profil->status_verifikasi === 'ditolak' ? 'Ajukan Ulang Verifikasi' : 'Kirim untuk Verifikasi' }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- Ringkasan status --}}
    <aside class="card card-body h-fit xl:sticky xl:top-28">
        <h2 class="card-title">Status Anda</h2>
        <ul class="mt-4 space-y-3">
            <li class="flex items-center justify-between text-[15px]">
                <span class="text-muted">Status akun</span><x-badge :status="$pengguna->status_akun">{{ ucfirst($pengguna->status_akun) }}</x-badge>
            </li>
            @foreach ($syarat as $label => $ok)
                <li class="flex items-center gap-2.5 rounded-xl bg-canvas p-2.5 text-[15px]">
                    <x-icon :name="$ok ? 'check-circle' : 'x-circle'" class="h-5 w-5 {{ $ok ? 'text-green-600' : 'text-gray-300' }}" />
                    <span class="{{ $ok ? 'text-ink' : 'text-muted' }}">{{ $label }}</span>
                </li>
            @endforeach
            <li class="flex items-center justify-between border-t border-line pt-3 text-sm">
                <span class="text-muted">Tampil di katalog</span>
                <span class="font-semibold {{ $profil->status_verifikasi === 'terverifikasi' && $pengguna->isAktif() ? 'text-green-700' : 'text-muted' }}">{{ $profil->status_verifikasi === 'terverifikasi' && $pengguna->isAktif() ? 'Ya' : 'Belum' }}</span>
            </li>
            @if ($profil->tanggal_verifikasi)
                <li class="flex items-center justify-between text-sm">
                    <span class="text-muted">Diperiksa pada</span><span class="font-medium">{{ $profil->tanggal_verifikasi->translatedFormat('d M Y H:i') }}</span>
                </li>
            @endif
        </ul>
        <div class="mt-5 space-y-2">
            @unless ($syarat['Data pribadi & profil lengkap'])
                <a href="{{ route('worker.profile.edit') }}" class="btn-secondary btn-sm w-full">Lengkapi profil</a>
            @endunless
            @unless ($syarat['Kriteria khusus sudah diisi'])
                <a href="{{ route('worker.criteria') }}" class="btn-secondary btn-sm w-full">Isi kriteria khusus</a>
            @endunless
        </div>
        <p class="text-helper mt-4">Membuat akun dan mengunggah dokumen tidak sama dengan persetujuan verifikasi.</p>
    </aside>
</div>

@endsection
