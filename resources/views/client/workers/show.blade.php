@extends('layouts.client')
@section('title', 'Detail Profil Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Katalog Pekerja / Detail',
    'title' => 'Detail Profil Pekerja',
    'subtitle' => 'Periksa keahlian dan kriteria khusus sebelum mengajukan pemesanan.',
    'aksi' => '<a href="' . route('client.workers') . '" class="btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg> Kembali ke katalog</a>',
])

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        @include('components.detail-pekerja', ['tampilKontak' => false, 'tampilDokumen' => false, 'sorotKriteria' => $idKriteria])
    </div>

    <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
        <div class="card card-body">
            @if ($skor)
                <p class="text-sm font-medium text-muted">Skor kecocokan</p>
                <p class="mt-1 text-4xl font-extrabold text-primary">{{ number_format($skor['skor'], 1, ',', '.') }}%</p>
                <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-primary-soft" role="progressbar" aria-label="Skor kecocokan" aria-valuenow="{{ $skor['skor'] }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="h-2.5 rounded-full bg-primary" style="width: {{ $skor['skor'] }}%"></div>
                </div>
                <p class="mt-2 text-sm text-muted">Memenuhi <strong class="text-ink">{{ $skor['cocok'] }} dari {{ $skor['total'] }}</strong> kriteria yang Anda cari.</p>
            @else
                <div class="flex gap-3 text-sm text-muted">
                    <x-icon name="sparkles" class="h-5 w-5 text-primary" />
                    <p>Pilih kriteria khusus di katalog untuk melihat skor kecocokan pekerja ini.</p>
                </div>
            @endif

            <div class="mt-5 grid grid-cols-2 gap-3 text-center">
                <div class="rounded-xl bg-canvas p-3">
                    <p class="text-xl font-bold text-ink">{{ $profil->lama_pengalaman }} th</p>
                    <p class="text-xs text-muted">Pengalaman</p>
                </div>
                <div class="rounded-xl bg-canvas p-3">
                    <p class="text-xl font-bold text-ink">{{ $jumlahPenyaluran }}</p>
                    <p class="text-xs text-muted">Penyaluran</p>
                </div>
            </div>

            <div class="mt-5 space-y-2.5">
                <a href="{{ route('client.orders.create', $profil) }}" class="btn-primary btn-lg w-full"><x-icon name="clipboard" class="h-5 w-5" /> Ajukan Pemesanan</a>
                <form method="POST" action="{{ $favorit ? route('client.favorites.destroy', $profil) : route('client.favorites.store', $profil) }}">
                    @csrf
                    @if ($favorit) @method('DELETE') @endif
                    <button type="submit" class="{{ $favorit ? 'btn-danger' : 'btn-outline' }} w-full">
                        <x-icon name="heart" class="h-5 w-5 {{ $favorit ? 'fill-current' : '' }}" /> {{ $favorit ? 'Hapus dari Favorit' : 'Simpan ke Favorit' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="flex gap-3 rounded-2xl bg-primary-soft p-5 text-sm text-primary-darker">
            <x-icon name="shield-check" class="h-6 w-6 text-primary" />
            <div>
                <p class="font-semibold">Data terverifikasi admin</p>
                <p class="mt-0.5 text-primary-dark/80">Dokumen identitas{{ $profil->wajibSertifikat() ? ' dan sertifikat pelatihan' : '' }} pekerja telah diperiksa. Kontak pekerja diberikan admin setelah pesanan disetujui.</p>
            </div>
        </div>
    </aside>
</div>

@endsection
