{{--
    Kartu pekerja untuk katalog (admin & klien).
    Parameter: $profil (ProfilPekerja + pengguna), $link, $skor (persen|null), $favorit (bool|null), $tampilStatus (bool)
--}}
@php
    $warnaKategori = [
        'art' => 'bg-green-100 text-green-700',
        'baby_sitter' => 'bg-blue-100 text-blue-700',
        'perawat_lansia' => 'bg-purple-100 text-purple-700',
    ][$profil->kategori_pekerjaan] ?? 'bg-gray-100 text-gray-700';
    $skor = $skor ?? null;
    $favorit = $favorit ?? null;
@endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-5 flex flex-col">
    <div class="flex items-start justify-between gap-2 mb-3">
        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#176B4D] to-[#20A66A] flex items-center justify-center text-white font-bold flex-shrink-0">
            {{ $profil->pengguna->inisial() }}
        </div>
        <div class="flex items-center gap-2">
            @if (! empty($tampilStatus))
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $profil->warnaStatus() }}">{{ $profil->labelStatus() }}</span>
            @else
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">✓ Terverifikasi</span>
            @endif
            @if (! is_null($favorit))
                <form method="POST" action="{{ $favorit ? route('client.favorites.destroy', $profil) : route('client.favorites.store', $profil) }}">
                    @csrf
                    @if ($favorit) @method('DELETE') @endif
                    <button type="submit" class="p-1 rounded-full hover:bg-red-50" aria-label="{{ $favorit ? 'Hapus dari favorit' : 'Tambah ke favorit' }}" title="{{ $favorit ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
                        <svg class="w-5 h-5 {{ $favorit ? 'text-red-500' : 'text-gray-300 hover:text-red-400' }}" viewBox="0 0 24 24" fill="{{ $favorit ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <p class="font-semibold text-[#172B23]">{{ $profil->pengguna->nama }}</p>
    <span class="inline-flex w-fit mt-1 text-xs font-semibold px-2 py-0.5 rounded-full {{ $warnaKategori }}">{{ $profil->labelKategori() }}</span>

    <div class="mt-3 space-y-1 text-sm text-[#52645B]">
        <p class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $profil->alamat ?: '-' }}
        </p>
        <p class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#176B4D]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            {{ $profil->lama_pengalaman }} tahun pengalaman
        </p>
    </div>

    <div class="flex flex-wrap gap-1.5 mt-3">
        @foreach (array_slice($profil->daftarKeterampilan(), 0, 3) as $k)
            <span class="text-[11px] px-2 py-0.5 rounded-md bg-[#F5F7F5] text-[#52645B] border border-gray-100">{{ $k }}</span>
        @endforeach
    </div>

    @if (! is_null($skor))
        <div class="mt-4">
            <div class="flex items-center justify-between text-xs mb-1">
                <span class="text-[#52645B]">Skor kecocokan</span>
                <span class="font-bold text-[#176B4D]">{{ number_format($skor, 1, ',', '.') }}%</span>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-2 bg-[#176B4D] rounded-full" style="width: {{ min(100, $skor) }}%"></div>
            </div>
        </div>
    @endif

    <a href="{{ $link }}" class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-sm font-medium text-[#176B4D] hover:text-[#104C38]">
        Lihat Profil <span>↗</span>
    </a>
</div>
