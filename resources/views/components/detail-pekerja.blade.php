{{--
    Detail profil pekerja: header profil besar + tab (Tentang, Kriteria, Dokumen).
    Parameter: $profil, $semuaKriteria, $nilaiKriteria, $tampilKontak (bool), $tampilDokumen (bool), $sorotKriteria (array id kriteria yang dicari klien)
--}}
@php
    $sorotKriteria = $sorotKriteria ?? [];
    $info = [
        ['briefcase', 'Kategori pekerjaan', $profil->labelKategori()],
        ['cake', 'Usia', $profil->hitungUsia() ? $profil->hitungUsia() . ' tahun' : null],
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
    $jumlahYa = collect($nilaiKriteria ?? [])->filter(fn ($v) => $v === 'ya')->count();
@endphp

<div class="card overflow-hidden" data-tabs>
    {{-- Kepala profil besar --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-primary-darker via-primary-dark to-primary px-5 pb-6 pt-7 text-white sm:px-8">
        <div class="bg-grid-light pointer-events-none absolute inset-0"></div>
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-primary-accent/30 blur-2xl"></div>
        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center">
            <x-avatar :name="$profil->pengguna->nama" size="2xl" class="ring-4 ring-white/25" />
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-mint">{{ $profil->labelKategori() }}</span>
                    <x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge>
                </div>
                <h2 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">{{ $profil->pengguna->nama }}</h2>
                <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-[15px] text-white/85">
                    <span class="flex items-center gap-1.5"><x-icon name="map-pin" class="h-5 w-5 text-sun" /> {{ $profil->alamat ?: '-' }}</span>
                    <span class="flex items-center gap-1.5"><x-icon name="briefcase" class="h-5 w-5 text-sun" /> {{ $profil->lama_pengalaman }} tahun pengalaman</span>
                    @if ($profil->hitungUsia())
                        <span class="flex items-center gap-1.5"><x-icon name="cake" class="h-5 w-5 text-sun" /> {{ $profil->hitungUsia() }} tahun</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tab --}}
    <div class="flex gap-2 overflow-x-auto border-b border-line px-5 py-3 scrollbar-hide sm:px-6" role="tablist" aria-label="Bagian profil">
        <button type="button" class="pill pill-active" data-tab="tentang" role="tab" aria-selected="true"><x-icon name="user" class="h-4 w-4" /> Tentang</button>
        <button type="button" class="pill" data-tab="kriteria" role="tab" aria-selected="false"><x-icon name="list-checks" class="h-4 w-4" /> Kriteria <span class="pill-count">{{ $jumlahYa }}</span></button>
        @if (! empty($tampilDokumen))
            <button type="button" class="pill" data-tab="dokumen" role="tab" aria-selected="false"><x-icon name="file-text" class="h-4 w-4" /> Dokumen</button>
        @endif
    </div>

    {{-- Panel: Tentang (info, keterampilan, pengalaman) --}}
    <div class="space-y-7 p-5 sm:p-6" data-tab-panel="tentang" role="tabpanel">
        <dl class="grid gap-3 sm:grid-cols-2">
            @foreach ($info as [$ikon, $label, $nilai])
                <div class="flex items-center gap-3 rounded-2xl bg-canvas p-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-primary shadow-sm"><x-icon :name="$ikon" class="h-5 w-5" /></span>
                    <div class="min-w-0">
                        <dt class="text-xs font-semibold text-muted">{{ $label }}</dt>
                        <dd class="truncate text-[15px] font-bold text-ink">{{ $nilai ?: '-' }}</dd>
                    </div>
                </div>
            @endforeach
        </dl>

        <section>
            <h3 class="card-title mb-3">Keterampilan</h3>
            <div class="flex flex-wrap gap-2">
                @forelse ($profil->daftarKeterampilan() as $k)
                    <span class="rounded-full bg-primary-soft px-3.5 py-1.5 text-[15px] font-semibold text-primary-dark">{{ $k }}</span>
                @empty
                    <span class="text-[15px] text-muted">-</span>
                @endforelse
            </div>
        </section>

        <section>
            <h3 class="card-title mb-2">Pengalaman &amp; informasi pendukung</h3>
            <p class="whitespace-pre-line rounded-2xl bg-canvas p-4 text-[15px] leading-relaxed text-muted">{{ $profil->deskripsi_profil ?: '-' }}</p>
        </section>
    </div>

    {{-- Panel: Kriteria khusus --}}
    <div class="hidden p-5 sm:p-6" data-tab-panel="kriteria" role="tabpanel">
        @if (empty($nilaiKriteria))
            <x-empty-state illustration="kosong" title="Kriteria khusus belum diisi" compact />
        @else
            <div class="grid gap-2.5 sm:grid-cols-2">
                @foreach ($semuaKriteria as $k)
                    @php
                        $ya = ($nilaiKriteria[$k->id_kriteria] ?? null) === 'ya';
                        $dicari = in_array($k->id_kriteria, $sorotKriteria);
                    @endphp
                    <div class="flex items-center justify-between gap-2 rounded-2xl border-2 px-3.5 py-3 text-[15px]
                                {{ $dicari ? ($ya ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50') : 'border-transparent bg-canvas' }}">
                        <span class="flex items-center gap-2.5 font-medium {{ $ya ? 'text-ink' : 'text-muted' }}">
                            <x-icon :name="$ya ? 'check-circle' : 'x-circle'" class="h-5 w-5 {{ $ya ? 'text-green-600' : 'text-gray-400' }}" />
                            {{ $k->nama_kriteria }}
                        </span>
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $ya ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">{{ $ya ? 'Ya' : 'Tidak' }}</span>
                    </div>
                @endforeach
            </div>
            @if ($sorotKriteria)
                <p class="text-helper mt-3">Kotak berbingkai adalah kriteria yang Anda cari — hijau berarti sesuai, merah berarti tidak sesuai.</p>
            @endif
        @endif
    </div>

    {{-- Panel: Dokumen --}}
    @if (! empty($tampilDokumen))
        <div class="hidden space-y-2.5 p-5 sm:p-6" data-tab-panel="dokumen" role="tabpanel">
            @foreach ($profil->dokumenWajib() as $jenis)
                @php $dok = $profil->dokumenTerbaru($jenis); @endphp
                <div class="flex flex-wrap items-center justify-between gap-2 rounded-2xl bg-canvas px-4 py-3.5 text-[15px]">
                    <span class="flex items-center gap-2.5 font-semibold"><x-icon name="file-text" class="h-5 w-5 text-primary" /> {{ \App\Models\DokumenPekerja::JENIS[$jenis] }}</span>
                    @if ($dok)
                        <span class="flex items-center gap-3">
                            <x-badge :status="$dok->status_verifikasi">{{ $dok->labelStatus() }}</x-badge>
                            <a href="{{ $dok->url() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 font-bold text-primary hover:underline">Lihat <x-icon name="external-link" class="h-4 w-4" /></a>
                        </span>
                    @else
                        <x-badge color="red">Belum diunggah</x-badge>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
