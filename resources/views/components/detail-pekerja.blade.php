{{--
    Detail profil pekerja.
    Parameter: $profil, $semuaKriteria, $nilaiKriteria, $tampilKontak (bool), $tampilDokumen (bool), $sorotKriteria (array id kriteria yang dicari klien)
--}}
@php
    $sorotKriteria = $sorotKriteria ?? [];
    $info = [
        ['briefcase', 'Kategori pekerjaan', $profil->labelKategori()],
        ['user', 'Usia', $profil->hitungUsia() ? $profil->hitungUsia() . ' tahun' : null],
        ['user', 'Jenis kelamin', $profil->jenis_kelamin],
        ['clock', 'Lama pengalaman', $profil->lama_pengalaman . ' tahun'],
        ['map-pin', 'Domisili', $profil->alamat],
    ];
    if (! empty($tampilKontak)) {
        $info[] = ['mail', 'Email', $profil->pengguna->email];
        $info[] = ['phone', 'Nomor telepon', $profil->pengguna->no_telepon];
        $info[] = ['calendar', 'Tanggal lahir', $profil->tanggal_lahir?->translatedFormat('d F Y')];
        $info[] = ['calendar', 'Terdaftar sejak', $profil->pengguna->tanggal_dibuat?->translatedFormat('d F Y')];
    }
@endphp

<div class="card overflow-hidden">
    {{-- Kepala profil --}}
    <div class="relative bg-gradient-to-r from-primary-soft to-white px-5 pb-5 pt-6 sm:px-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <x-avatar :name="$profil->pengguna->nama" size="xl" />
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-ink sm:text-2xl">{{ $profil->pengguna->nama }}</h2>
                <p class="mt-0.5 text-sm text-muted">{{ $profil->labelKategori() }} · {{ $profil->alamat ?: '-' }}</p>
                <div class="mt-2"><x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge></div>
            </div>
        </div>
    </div>

    <div class="space-y-7 p-5 sm:p-6">
        <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
            @foreach ($info as [$ikon, $label, $nilai])
                <div class="flex gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-canvas text-primary"><x-icon :name="$ikon" class="h-4 w-4" /></span>
                    <div class="min-w-0">
                        <dt class="text-xs text-muted">{{ $label }}</dt>
                        <dd class="truncate text-sm font-semibold text-ink">{{ $nilai ?: '-' }}</dd>
                    </div>
                </div>
            @endforeach
        </dl>

        <section>
            <h3 class="card-title mb-2.5">Keterampilan</h3>
            <div class="flex flex-wrap gap-2">
                @forelse ($profil->daftarKeterampilan() as $k)
                    <span class="rounded-full bg-primary-soft px-3 py-1 text-sm font-medium text-primary-dark">{{ $k }}</span>
                @empty
                    <span class="text-sm text-muted">-</span>
                @endforelse
            </div>
        </section>

        <section>
            <h3 class="card-title mb-2">Pengalaman &amp; informasi pendukung</h3>
            <p class="whitespace-pre-line text-sm leading-relaxed text-muted">{{ $profil->deskripsi_profil ?: '-' }}</p>
        </section>

        <section>
            <h3 class="card-title mb-3">Kriteria khusus</h3>
            @if (empty($nilaiKriteria))
                <p class="text-sm text-muted">Kriteria khusus belum diisi.</p>
            @else
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach ($semuaKriteria as $k)
                        @php
                            $ya = ($nilaiKriteria[$k->id_kriteria] ?? null) === 'ya';
                            $dicari = in_array($k->id_kriteria, $sorotKriteria);
                        @endphp
                        <div class="flex items-center justify-between gap-2 rounded-xl border px-3 py-2.5 text-sm
                                    {{ $dicari ? ($ya ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50') : 'border-line' }}">
                            <span class="flex items-center gap-2 {{ $ya ? 'text-ink' : 'text-muted' }}">
                                <x-icon :name="$ya ? 'check-circle' : 'x-circle'" class="h-4 w-4 {{ $ya ? 'text-green-600' : 'text-gray-400' }}" />
                                {{ $k->nama_kriteria }}
                            </span>
                            <span class="text-xs font-semibold {{ $ya ? 'text-green-700' : 'text-gray-500' }}">{{ $ya ? 'Ya' : 'Tidak' }}</span>
                        </div>
                    @endforeach
                </div>
                @if ($sorotKriteria)
                    <p class="text-helper mt-2.5">Kotak berwarna adalah kriteria yang Anda cari — hijau berarti sesuai, merah berarti tidak sesuai.</p>
                @endif
            @endif
        </section>

        @if (! empty($tampilDokumen))
            <section>
                <h3 class="card-title mb-3">Dokumen persyaratan</h3>
                <div class="space-y-2">
                    @foreach ($profil->dokumenWajib() as $jenis)
                        @php $dok = $profil->dokumenTerbaru($jenis); @endphp
                        <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-line px-3 py-2.5 text-sm">
                            <span class="flex items-center gap-2 font-medium"><x-icon name="file-text" class="h-4 w-4 text-primary" /> {{ \App\Models\DokumenPekerja::JENIS[$jenis] }}</span>
                            @if ($dok)
                                <span class="flex items-center gap-3">
                                    <x-badge :status="$dok->status_verifikasi">{{ $dok->labelStatus() }}</x-badge>
                                    <a href="{{ $dok->url() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 font-medium text-primary hover:underline">Lihat <x-icon name="external-link" class="h-3.5 w-3.5" /></a>
                                </span>
                            @else
                                <x-badge color="red">Belum diunggah</x-badge>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
