@extends('layouts.admin')
@section('title', 'Pemeriksaan Dokumen')
@section('content')

@include('components.page-header', [
    'crumb' => 'Admin / Verifikasi Pekerja / Pemeriksaan',
    'title' => 'Pemeriksaan Dokumen ' . $profil->labelKategori(),
    'subtitle' => $profil->pengguna->nama . ' · periksa data dan dokumen, lalu tetapkan hasil verifikasi.',
    'aksi' => '<a href="' . route('admin.verification') . '" class="btn-secondary">Kembali</a>',
])

<div class="grid gap-6 xl:grid-cols-5">
    <div class="xl:col-span-3">
        @include('components.detail-pekerja', ['tampilKontak' => true, 'tampilDokumen' => false])
    </div>

    <div class="xl:col-span-2">
        <form method="POST" action="{{ route('admin.verification.process', $profil) }}" class="card card-body space-y-5 xl:sticky xl:top-24">
            @csrf
            <div class="flex items-center justify-between">
                <h2 class="card-title">Dokumen persyaratan</h2>
                <x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge>
            </div>

            @foreach ($profil->dokumenWajib() as $jenis)
                @php $dok = $profil->dokumenTerbaru($jenis); @endphp
                <div class="rounded-2xl border border-line p-3">
                    <div class="mb-2.5 flex items-center justify-between gap-2">
                        <p class="flex items-center gap-2 text-sm font-semibold"><x-icon name="file-text" class="h-4 w-4 text-primary" /> {{ \App\Models\DokumenPekerja::JENIS[$jenis] }}</p>
                        @if ($dok) <x-badge :status="$dok->status_verifikasi">{{ $dok->labelStatus() }}</x-badge> @endif
                    </div>
                    @if ($dok)
                        @if ($dok->isGambar())
                            <a href="{{ $dok->url() }}" target="_blank" rel="noopener"><img src="{{ $dok->url() }}" alt="{{ $dok->labelJenis() }} {{ $profil->pengguna->nama }}" class="max-h-60 w-full rounded-xl bg-canvas object-contain"></a>
                        @else
                            <iframe src="{{ $dok->url() }}" class="h-60 w-full rounded-xl bg-canvas" title="{{ $dok->labelJenis() }}" loading="lazy"></iframe>
                        @endif
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-1 text-xs">
                            <a href="{{ $dok->url() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 font-medium text-primary hover:underline">Buka <x-icon name="external-link" class="h-3.5 w-3.5" /></a>
                            <span class="text-muted">Diunggah {{ $dok->tanggal_unggah->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($profil->status_verifikasi === 'menunggu')
                            <div class="mt-3 grid grid-cols-2 gap-2" role="radiogroup" aria-label="Status {{ $dok->labelJenis() }}">
                                @foreach (['valid' => ['Valid', 'check', 'peer-checked:border-green-600 peer-checked:bg-green-600'], 'tidak_valid' => ['Tidak valid', 'x', 'peer-checked:border-red-600 peer-checked:bg-red-600']] as $v => [$label, $ikon, $warna])
                                    <label class="cursor-pointer">
                                        <input type="radio" name="dokumen[{{ $dok->id_dokumen }}]" value="{{ $v }}" class="peer sr-only" @checked(old('dokumen.' . $dok->id_dokumen, 'valid') === $v)>
                                        <span class="flex items-center justify-center gap-1.5 rounded-lg border border-line px-3 py-2 text-sm font-semibold text-muted transition-colors peer-checked:text-white peer-focus-visible:ring-4 peer-focus-visible:ring-primary/20 {{ $warna }}">
                                            <x-icon :name="$ikon" class="h-4 w-4" /> {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <p class="flex items-center gap-1.5 text-sm text-red-600"><x-icon name="x-circle" class="h-4 w-4" /> Dokumen belum diunggah.</p>
                    @endif
                </div>
            @endforeach

            @if ($profil->status_verifikasi === 'menunggu')
                <x-field name="catatan" type="textarea" label="Catatan pemeriksaan" placeholder="Jelaskan perbaikan yang diperlukan (wajib bila diminta perbaikan atau ditolak)" />
                <div class="grid gap-2 sm:grid-cols-3 xl:grid-cols-1 2xl:grid-cols-3">
                    <button type="submit" name="keputusan" value="setujui" class="btn-primary" data-confirm="Setujui verifikasi? Profil akan tampil di katalog klien."><x-icon name="check" class="h-4 w-4" /> Setujui</button>
                    <button type="submit" name="keputusan" value="perbaikan" class="btn border border-amber-300 bg-white text-amber-800 hover:bg-amber-50" data-confirm="Minta pekerja memperbaiki data/dokumen?">Minta Perbaikan</button>
                    <button type="submit" name="keputusan" value="tolak" class="btn-danger" data-confirm="Tolak verifikasi pekerja ini?"><x-icon name="x" class="h-4 w-4" /> Tolak</button>
                </div>
            @else
                <div class="rounded-xl bg-canvas p-4 text-sm">
                    <p class="text-muted">Status saat ini: <strong class="text-ink">{{ $profil->labelStatus() }}</strong></p>
                    @if ($profil->verifikator)
                        <p class="mt-1 text-muted">Diperiksa oleh {{ $profil->verifikator->nama }} pada {{ $profil->tanggal_verifikasi?->translatedFormat('d F Y H:i') }}</p>
                    @endif
                    @if ($profil->catatan_verifikasi)
                        <p class="mt-1 text-muted">Catatan: {{ $profil->catatan_verifikasi }}</p>
                    @endif
                    <p class="text-helper mt-2">Pemeriksaan hanya dapat dilakukan saat pekerja berstatus "Menunggu Verifikasi".</p>
                </div>
            @endif
        </form>
    </div>
</div>

@endsection
