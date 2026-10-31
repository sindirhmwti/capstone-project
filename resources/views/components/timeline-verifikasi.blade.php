{{--
    Timeline status verifikasi pekerja: Belum lengkap → Menunggu → Terverifikasi / Ditolak.
    Parameter: $status (belum_lengkap | menunggu | terverifikasi | ditolak), $vertikal (bool, opsional)
--}}
@php
    $urutan = ['belum_lengkap' => 0, 'menunggu' => 1, 'terverifikasi' => 2, 'ditolak' => 2];
    $posisi = $urutan[$status] ?? 0;
    $ditolak = $status === 'ditolak';
    $langkah = [
        ['Belum lengkap', 'Lengkapi profil, kriteria, dan dokumen', 'pencil'],
        ['Menunggu verifikasi', 'Admin memeriksa data dan dokumen', 'hourglass'],
        $ditolak ? ['Ditolak', 'Perbaiki sesuai catatan admin', 'x'] : ['Terverifikasi', 'Profil tampil di katalog klien', 'shield-check'],
    ];
    $vertikal = $vertikal ?? false;
@endphp

<ol class="{{ $vertikal ? '' : 'grid gap-0 sm:grid-cols-3' }}" aria-label="Tahapan verifikasi">
    @foreach ($langkah as $i => [$judul, $teks, $ikon])
        @php
            $lewat = $i < $posisi;
            $kini = $i === $posisi;
            if ($kini) {
                $warna = $ditolak && $i === 2 ? 'bg-red-500 text-white ring-red-100' : ($status === 'terverifikasi' ? 'bg-green-600 text-white ring-green-100' : 'bg-sun text-ink ring-sun-soft');
            } else {
                $warna = $lewat ? 'bg-primary text-white ring-primary-soft' : 'bg-white text-muted ring-line';
            }
            $garis = $vertikal
                ? 'left-[18px] top-11 h-[calc(100%-2.75rem)] w-1'
                : 'left-[18px] top-11 h-[calc(100%-2.75rem)] w-1 sm:left-[calc(50%+1.75rem)] sm:top-[18px] sm:h-1 sm:w-[calc(100%-3.5rem)]';
        @endphp
        <li class="relative flex gap-4 pb-6 last:pb-0 {{ $vertikal ? '' : 'sm:flex-col sm:items-center sm:pb-0 sm:text-center' }}" @if ($kini) aria-current="step" @endif>
            @unless ($loop->last)
                <span class="absolute rounded-full {{ $garis }} {{ $lewat ? 'bg-primary' : 'bg-line' }}" aria-hidden="true"></span>
            @endunless
            <span class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full ring-4 {{ $warna }}">
                @if ($lewat) <x-icon name="check" class="h-5 w-5" stroke="3" /> @else <x-icon :name="$ikon" class="h-5 w-5" /> @endif
            </span>
            <div class="{{ $vertikal ? 'pt-1.5' : 'pt-1.5 sm:px-3 sm:pt-0' }}">
                <p class="text-[15px] font-bold {{ $kini || $lewat ? 'text-ink' : 'text-muted' }}">{{ $judul }}</p>
                <p class="text-sm text-muted">{{ $teks }}</p>
            </div>
        </li>
    @endforeach
</ol>
