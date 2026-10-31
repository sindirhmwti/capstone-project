{{--
    Kartu pekerja untuk katalog (admin & klien).
    Parameter: $profil (ProfilPekerja + pengguna), $link, $skor (persen|null), $favorit (bool|null), $tampilStatus (bool)
--}}
@php
    [$warnaKategori, $pita] = [
        'art' => ['brand', 'from-primary to-primary-accent'],
        'baby_sitter' => ['amber', 'from-sun to-amber-300'],
        'perawat_lansia' => ['blue', 'from-sky-500 to-sky-300'],
    ][$profil->kategori_pekerjaan] ?? ['gray', 'from-gray-400 to-gray-300'];
    $skor = $skor ?? null;
    $favorit = $favorit ?? null;
    // Chip kriteria utama (hanya bila data kriteria sudah dimuat, tanpa query tambahan); jika tidak, pakai keterampilan
    $chip = $profil->relationLoaded('kriteria')
        ? $profil->kriteria->where('pivot.nilai_kriteria', 'ya')->take(3)->pluck('nama_kriteria')->all()
        : [];
    $chip = $chip ?: array_slice($profil->daftarKeterampilan(), 0, 3);
@endphp

<article class="card card-hover reveal group relative flex flex-col overflow-hidden rounded-3xl">
    <span class="h-1.5 bg-gradient-to-r {{ $pita }}" aria-hidden="true"></span>
    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-start gap-4">
            <x-avatar :name="$profil->pengguna->nama" size="lg" />
            <div class="min-w-0 flex-1">
                <h3 class="truncate text-lg font-extrabold text-ink">{{ $profil->pengguna->nama }}</h3>
                <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                    <x-badge :color="$warnaKategori" :dot="false">{{ $profil->labelKategori() }}</x-badge>
                    @if (! empty($tampilStatus))
                        <x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700"><x-icon name="shield-check" class="h-4 w-4" /> Terverifikasi</span>
                    @endif
                </div>
            </div>
            @if (! is_null($favorit))
                <form method="POST" action="{{ $favorit ? route('client.favorites.destroy', $profil) : route('client.favorites.store', $profil) }}" data-no-loading>
                    @csrf
                    @if ($favorit) @method('DELETE') @endif
                    <button type="submit" data-favorit class="flex h-10 w-10 items-center justify-center rounded-full transition-colors {{ $favorit ? 'bg-red-50 text-red-500 hover:bg-red-100' : 'bg-canvas text-gray-400 hover:bg-red-50 hover:text-red-400' }}"
                            aria-label="{{ $favorit ? 'Hapus dari favorit' : 'Tambah ke favorit' }}" title="{{ $favorit ? 'Hapus dari favorit' : 'Simpan ke favorit' }}">
                        <x-icon name="heart" class="h-5 w-5 {{ $favorit ? 'fill-current' : '' }}" />
                    </button>
                </form>
            @endif
        </div>

        <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
            <p class="flex items-center gap-2 rounded-xl bg-canvas px-3 py-2 font-semibold text-ink"><x-icon name="briefcase" class="h-4 w-4 text-primary" /> {{ $profil->lama_pengalaman }} thn</p>
            <p class="flex items-center gap-2 rounded-xl bg-canvas px-3 py-2 font-semibold text-ink"><x-icon name="cake" class="h-4 w-4 text-primary" /> {{ $profil->hitungUsia() ? $profil->hitungUsia() . ' thn' : '-' }}</p>
            <p class="col-span-2 flex min-w-0 items-center gap-2 rounded-xl bg-canvas px-3 py-2 font-semibold text-ink"><x-icon name="map-pin" class="h-4 w-4 text-primary" /><span class="truncate">{{ $profil->alamat ?: '-' }}</span></p>
        </div>

        @if ($chip)
            <div class="mt-3 flex flex-wrap gap-1.5">
                @foreach ($chip as $k)
                    <span class="inline-flex items-center gap-1 rounded-full bg-primary-soft px-2.5 py-1 text-xs font-semibold text-primary-dark"><x-icon name="check" class="h-3 w-3" stroke="3" /> {{ $k }}</span>
                @endforeach
            </div>
        @endif

        @if (! is_null($skor))
            <div class="mt-4 rounded-2xl bg-sun-soft p-3.5">
                <div class="mb-2 flex items-center justify-between text-sm">
                    <span class="flex items-center gap-1.5 font-bold text-ink"><x-icon name="sparkles" class="h-4 w-4 text-sun-dark" /> Skor kecocokan</span>
                    <span class="text-lg font-extrabold text-primary">{{ number_format($skor, 1, ',', '.') }}%</span>
                </div>
                <div class="h-2.5 overflow-hidden rounded-full bg-white" role="progressbar" aria-label="Skor kecocokan" aria-valuenow="{{ $skor }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="skor-bar h-2.5 rounded-full bg-gradient-to-r from-primary-accent to-primary" style="--skor: {{ min(100, $skor) }}%"></div>
                </div>
            </div>
        @endif

        <div class="mt-auto pt-4">
            <a href="{{ $link }}" class="btn-primary w-full">Lihat Detail <x-icon name="arrow-right" class="h-5 w-5" /></a>
        </div>
    </div>
</article>
