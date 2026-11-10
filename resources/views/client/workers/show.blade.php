@extends('layouts.client')
@section('title', 'Detail Profil Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Katalog Pekerja / Detail',
    'title' => 'Detail Profil Pekerja',
    'subtitle' => 'Periksa keahlian dan kriteria khusus sebelum mengajukan pemesanan.',
    'aksi' => '<a href="' . route('client.workers') . '" class="btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg> Kembali ke katalog</a>',
])

@if ($tersalurkan)
    <x-alert type="info" title="Pekerja ini sudah tersalurkan dan tidak tersedia" class="mb-6">
        {{ $profil->pengguna->nama }} sedang bekerja di klien lain sehingga tidak dapat dipesan. Silakan pilih pekerja lain di katalog.
    </x-alert>
@endif

<div class="grid gap-6 xl:grid-cols-3">
    <div class="xl:col-span-2">
        @include('components.detail-pekerja', ['tampilKontak' => false, 'tampilDokumen' => false, 'sorotKriteria' => $idKriteria])
    </div>

    {{-- Kartu aksi "Pesan Pekerja" (sticky) --}}
    <aside class="space-y-6 xl:sticky xl:top-28 xl:self-start">
        <div class="card overflow-hidden">
            <div class="bg-sun-soft p-5 sm:p-6">
                @if ($skor)
                    <p class="flex items-center gap-2 text-sm font-bold text-ink"><x-icon name="sparkles" class="h-5 w-5 text-sun-dark" /> Skor kecocokan</p>
                    <p class="mt-2 text-5xl font-extrabold text-primary">{{ number_format($skor['skor'], 1, ',', '.') }}%</p>
                    <div class="mt-4 h-3 overflow-hidden rounded-full bg-white" role="progressbar" aria-label="Skor kecocokan" aria-valuenow="{{ $skor['skor'] }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="skor-bar h-3 rounded-full bg-gradient-to-r from-primary-accent to-primary" style="--skor: {{ min(100, $skor['skor']) }}%"></div>
                    </div>
                    <p class="mt-3 text-[15px] text-muted">Memenuhi <strong class="text-ink">{{ $skor['cocok'] }} dari {{ $skor['total'] }}</strong> kriteria yang Anda cari.</p>
                @else
                    <div class="flex gap-3 text-[15px] text-muted">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-sun-dark"><x-icon name="sparkles" /></span>
                        <p>Pilih kriteria khusus di katalog untuk melihat skor kecocokan pekerja ini.</p>
                    </div>
                @endif
            </div>

            <div class="p-5 sm:p-6">
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="rounded-2xl bg-canvas p-4">
                        <p class="text-2xl font-extrabold text-ink">{{ $profil->lama_pengalaman }} th</p>
                        <p class="text-sm text-muted">Pengalaman</p>
                    </div>
                    <div class="rounded-2xl bg-canvas p-4">
                        <p class="text-2xl font-extrabold text-ink">{{ $jumlahPenyaluran }}</p>
                        <p class="text-sm text-muted">Penyaluran</p>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    @if ($tersalurkan)
                        <span class="btn-lg flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-2xl bg-gray-100 font-bold text-gray-500" aria-disabled="true"><x-icon name="lock" class="h-5 w-5" /> Sudah Tersalurkan</span>
                    @else
                        <a href="{{ route('client.orders.create', $profil) }}" class="btn-primary btn-lg w-full"><x-icon name="clipboard" class="h-5 w-5" /> Pesan Pekerja</a>
                    @endif
                    <form method="POST" action="{{ $favorit ? route('client.favorites.destroy', $profil) : route('client.favorites.store', $profil) }}">
                        @csrf
                        @if ($favorit) @method('DELETE') @endif
                        <button type="submit" class="{{ $favorit ? 'btn-danger' : 'btn-outline' }} w-full">
                            <x-icon name="heart" class="h-5 w-5 {{ $favorit ? 'fill-current' : '' }}" /> {{ $favorit ? 'Hapus dari Favorit' : 'Simpan ke Favorit' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex gap-3 rounded-3xl bg-primary-soft p-5 text-[15px] text-primary-darker">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-primary"><x-icon name="shield-check" /></span>
            <div>
                <p class="font-bold">Data terverifikasi admin</p>
                <p class="mt-0.5 text-primary-dark/80">Dokumen identitas{{ $profil->wajibSertifikat() ? ' dan sertifikat pelatihan' : '' }} pekerja telah diperiksa. Kontak pekerja diberikan admin setelah pesanan disetujui.</p>
            </div>
        </div>
    </aside>
</div>

@endsection
