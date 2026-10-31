{{--
    Kartu pekerja untuk katalog (admin & klien).
    Parameter: $profil (ProfilPekerja + pengguna), $link, $skor (persen|null), $favorit (bool|null), $tampilStatus (bool)
--}}
@php
    $warnaKategori = ['art' => 'brand', 'baby_sitter' => 'amber', 'perawat_lansia' => 'blue'][$profil->kategori_pekerjaan] ?? 'gray';
    $skor = $skor ?? null;
    $favorit = $favorit ?? null;
@endphp

<article class="card card-hover reveal flex flex-col p-5">
    <div class="flex items-start gap-3.5">
        <x-avatar :name="$profil->pengguna->nama" size="lg" />
        <div class="min-w-0 flex-1">
            <h3 class="truncate text-base font-bold text-ink">{{ $profil->pengguna->nama }}</h3>
            <div class="mt-1 flex flex-wrap items-center gap-1.5">
                <x-badge :color="$warnaKategori" :dot="false">{{ $profil->labelKategori() }}</x-badge>
                @if (! empty($tampilStatus))
                    <x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge>
                @else
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700"><x-icon name="shield-check" class="h-4 w-4" /> Terverifikasi</span>
                @endif
            </div>
        </div>
        @if (! is_null($favorit))
            <form method="POST" action="{{ $favorit ? route('client.favorites.destroy', $profil) : route('client.favorites.store', $profil) }}" data-no-loading>
                @csrf
                @if ($favorit) @method('DELETE') @endif
                <button type="submit" class="rounded-full p-2 transition-colors {{ $favorit ? 'text-red-500 hover:bg-red-50' : 'text-gray-300 hover:bg-red-50 hover:text-red-400' }}"
                        aria-label="{{ $favorit ? 'Hapus dari favorit' : 'Tambah ke favorit' }}" title="{{ $favorit ? 'Hapus dari favorit' : 'Simpan ke favorit' }}">
                    <x-icon name="heart" class="h-5 w-5 {{ $favorit ? 'fill-current' : '' }}" />
                </button>
            </form>
        @endif
    </div>

    <div class="mt-4 space-y-1.5 text-sm text-muted">
        <p class="flex min-w-0 items-center gap-2"><x-icon name="map-pin" class="h-4 w-4 text-primary" /><span class="truncate">{{ $profil->alamat ?: '-' }}</span></p>
        <p class="flex items-center gap-2"><x-icon name="briefcase" class="h-4 w-4 text-primary" />{{ $profil->lama_pengalaman }} tahun pengalaman</p>
    </div>

    @if ($profil->daftarKeterampilan())
        <div class="mt-3 flex flex-wrap gap-1.5">
            @foreach (array_slice($profil->daftarKeterampilan(), 0, 3) as $k)
                <span class="rounded-lg bg-canvas px-2 py-1 text-xs text-muted ring-1 ring-line">{{ $k }}</span>
            @endforeach
        </div>
    @endif

    @if (! is_null($skor))
        <div class="mt-4 rounded-xl bg-primary-soft/60 p-3">
            <div class="mb-1.5 flex items-center justify-between text-sm">
                <span class="font-medium text-primary-dark">Skor kecocokan</span>
                <span class="font-bold text-primary">{{ number_format($skor, 1, ',', '.') }}%</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-white" role="progressbar" aria-label="Skor kecocokan" aria-valuenow="{{ $skor }}" aria-valuemin="0" aria-valuemax="100">
                <div class="h-2 rounded-full bg-primary" style="width: {{ min(100, $skor) }}%"></div>
            </div>
        </div>
    @endif

    <div class="mt-auto pt-4">
        <a href="{{ $link }}" class="btn-secondary w-full">Lihat Profil <x-icon name="arrow-right" class="h-4 w-4" /></a>
    </div>
</article>
