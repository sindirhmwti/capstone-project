{{--
    Detail profil pekerja.
    Parameter: $profil, $semuaKriteria, $nilaiKriteria, $tampilKontak (bool), $tampilDokumen (bool), $sorotKriteria (array id kriteria yang dicari klien)
--}}
@php
    $sorotKriteria = $sorotKriteria ?? [];
@endphp
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center gap-4 pb-5 border-b border-gray-100 mb-5">
        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#176B4D] to-[#20A66A] flex items-center justify-center text-xl font-bold text-white">{{ $profil->pengguna->inisial() }}</div>
        <div>
            <p class="font-semibold text-[#172B23] text-xl">{{ $profil->pengguna->nama }}</p>
            <p class="text-sm text-[#52645B]">{{ $profil->labelKategori() }} · {{ $profil->alamat ?: '-' }}</p>
            <span class="inline-flex mt-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $profil->warnaStatus() }}">{{ $profil->labelStatus() }}</span>
        </div>
    </div>

    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
        @php
            $info = [
                'Kategori pekerjaan' => $profil->labelKategori(),
                'Usia' => $profil->hitungUsia() ? $profil->hitungUsia() . ' tahun' : null,
                'Jenis kelamin' => $profil->jenis_kelamin,
                'Lama pengalaman' => $profil->lama_pengalaman . ' tahun',
                'Domisili' => $profil->alamat,
            ];
            if (! empty($tampilKontak)) {
                $info += [
                    'Email' => $profil->pengguna->email,
                    'Nomor telepon' => $profil->pengguna->no_telepon,
                    'Tanggal lahir' => $profil->tanggal_lahir?->translatedFormat('d F Y'),
                    'Terdaftar sejak' => $profil->pengguna->tanggal_dibuat?->translatedFormat('d F Y'),
                ];
            }
        @endphp
        @foreach ($info as $label => $nilai)
            <div>
                <dt class="text-[#52645B]">{{ $label }}</dt>
                <dd class="font-medium text-[#172B23] mt-0.5">{{ $nilai ?: '-' }}</dd>
            </div>
        @endforeach
    </dl>

    <h3 class="font-semibold text-[#172B23] mt-6 mb-2">Keterampilan</h3>
    <div class="flex flex-wrap gap-2">
        @forelse ($profil->daftarKeterampilan() as $k)
            <span class="px-3 py-1 rounded-full bg-[#E4F2E8] text-[#176B4D] text-xs font-medium">{{ $k }}</span>
        @empty
            <span class="text-sm text-[#52645B]">-</span>
        @endforelse
    </div>

    <h3 class="font-semibold text-[#172B23] mt-6 mb-2">Pengalaman &amp; informasi pendukung</h3>
    <p class="text-sm text-[#52645B] leading-relaxed whitespace-pre-line">{{ $profil->deskripsi_profil ?: '-' }}</p>

    <h3 class="font-semibold text-[#172B23] mt-6 mb-3">Kriteria khusus</h3>
    @if (empty($nilaiKriteria))
        <p class="text-sm text-[#52645B]">Kriteria khusus belum diisi.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @foreach ($semuaKriteria as $k)
                @php
                    $ya = ($nilaiKriteria[$k->id_kriteria] ?? null) === 'ya';
                    $dicari = in_array($k->id_kriteria, $sorotKriteria);
                @endphp
                <div class="flex items-center justify-between gap-2 text-sm px-3 py-2 rounded-lg border {{ $dicari ? ($ya ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50') : 'border-gray-100' }}">
                    <span>{{ $k->nama_kriteria }}</span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $ya ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $ya ? 'Ya' : 'Tidak' }}</span>
                </div>
            @endforeach
        </div>
        @if ($sorotKriteria)
            <p class="text-xs text-[#52645B] mt-2">Kotak berwarna adalah kriteria yang Anda cari (hijau = sesuai, merah = tidak sesuai).</p>
        @endif
    @endif

    @if (! empty($tampilDokumen))
        <h3 class="font-semibold text-[#172B23] mt-6 mb-3">Dokumen persyaratan</h3>
        <div class="space-y-2">
            @foreach ($profil->dokumenWajib() as $jenis)
                @php $dok = $profil->dokumenTerbaru($jenis); @endphp
                <div class="flex items-center justify-between text-sm px-3 py-2 rounded-lg border border-gray-100">
                    <span>{{ \App\Models\DokumenPekerja::JENIS[$jenis] }}</span>
                    @if ($dok)
                        <span class="flex items-center gap-3">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $dok->warnaStatus() }}">{{ $dok->labelStatus() }}</span>
                            <a href="{{ $dok->url() }}" target="_blank" class="text-xs text-[#176B4D] font-medium hover:underline">Lihat dokumen →</a>
                        </span>
                    @else
                        <span class="text-xs text-red-600">Belum diunggah</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
