{{--
    Bagian "Hasil Interview" di detail pesanan admin. Variabel: $pemesanan (jadwalInterview sudah dimuat).
    - Pesanan disetujui & hasil belum dicatat: tombol "Cocok - Salurkan Pekerja" / "Tidak Cocok" (+ modal).
    - Hasil sudah dicatat: ringkasan hasil + tombol "Kabari via WhatsApp" untuk klien & pekerja.
--}}
@php
    $jadwal = $pemesanan->jadwalInterview;
    $bisaDicatat = $jadwal && $pemesanan->status_pemesanan === 'disetujui' && ! $jadwal->hasilSudahDicatat();
@endphp

@if ($bisaDicatat)
    @php $sudahLewat = $jadwal->tanggal_interview->lte(now(\App\Models\JadwalInterview::ZONA)->startOfDay()); @endphp
    <div class="card overflow-hidden" id="hasil-interview">
        <div class="card-head">
            <div>
                <h2 class="card-title flex items-center gap-2"><x-icon name="award" class="h-5 w-5 text-primary" /> Hasil Interview</h2>
                <p class="text-sm text-muted">
                    {{ $sudahLewat ? 'Interview telah dijadwalkan pada ' . $jadwal->labelTanggal() . '. Catat hasilnya di sini.' : 'Catat hasil setelah interview ' . $jadwal->labelTanggal() . ' selesai.' }}
                </p>
            </div>
        </div>
        <div class="card-body grid gap-3 sm:grid-cols-2">
            <button type="button" class="btn btn-lg w-full bg-green-600 text-white shadow-sm hover:bg-green-700" data-modal-buka="modal-cocok">
                <x-icon name="check-circle" class="h-5 w-5" /> Cocok - Salurkan Pekerja
            </button>
            <button type="button" class="btn-danger btn-lg w-full" data-modal-buka="modal-tidak-cocok">
                <x-icon name="x-circle" class="h-5 w-5" /> Tidak Cocok
            </button>
            <p class="text-helper sm:col-span-2">
                <strong>Cocok:</strong> pesanan menjadi Selesai, pekerja tersalurkan dan tidak tampil di katalog; pesanan lain untuk pekerja ini dibatalkan otomatis.
                <strong>Tidak cocok:</strong> pesanan dibatalkan, pekerja tetap tampil di katalog.
            </p>
        </div>
    </div>

    {{-- Modal: Cocok --}}
    <div id="modal-cocok" class="modal-tutup fixed inset-0 z-[80] hidden items-end justify-center p-4 sm:items-center print:hidden" role="dialog" aria-modal="true" aria-labelledby="modal-cocok-judul"
         data-modal-form @if ($errors->hasAny(['tanggal_mulai_kerja', 'catatan_cocok'])) data-modal-otomatis @endif>
        <div class="modal-latar absolute inset-0 bg-ink/50 backdrop-blur-sm" data-modal-tutup></div>
        <div class="modal-kotak relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-lift sm:p-7">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700"><x-icon name="check-circle" class="h-6 w-6" /></span>
            <h2 id="modal-cocok-judul" class="mt-4 text-xl font-extrabold text-ink">Cocok - Salurkan Pekerja</h2>
            <p class="mt-1 text-[15px] text-muted">{{ $pemesanan->profilPekerja->pengguna->nama }} akan tersalurkan ke {{ $pemesanan->klien->nama }} dan tidak lagi tampil di katalog.</p>
            <form method="POST" action="{{ route('admin.orders.interview.match', $pemesanan) }}" class="mt-5 space-y-4" novalidate>
                @csrf
                @php
                    $usulanMulai = $pemesanan->tanggal_mulai && $pemesanan->tanggal_mulai->gte($jadwal->tanggal_interview) ? $pemesanan->tanggal_mulai : $jadwal->tanggal_interview;
                @endphp
                <x-field name="tanggal_mulai_kerja" type="date" label="Tanggal mulai kerja" required
                         :value="$usulanMulai->format('Y-m-d')" :min="$jadwal->tanggal_interview->format('Y-m-d')"
                         help="Diisi otomatis dari jadwal mulai yang diminta klien bila sesuai." />
                <x-field name="catatan_cocok" type="textarea" label="Catatan (opsional)" rows="3" maxlength="500" placeholder="Contoh: Kontrak 1 tahun, masa percobaan 1 bulan." />
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <button type="button" class="btn-secondary" data-modal-tutup>Batal</button>
                    <button type="submit" class="btn bg-green-600 text-white hover:bg-green-700"><x-icon name="check" class="h-5 w-5" /> Salurkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Tidak cocok --}}
    <div id="modal-tidak-cocok" class="modal-tutup fixed inset-0 z-[80] hidden items-end justify-center p-4 sm:items-center print:hidden" role="dialog" aria-modal="true" aria-labelledby="modal-tidak-cocok-judul"
         data-modal-form @if ($errors->has('alasan_tidak_cocok')) data-modal-otomatis @endif>
        <div class="modal-latar absolute inset-0 bg-ink/50 backdrop-blur-sm" data-modal-tutup></div>
        <div class="modal-kotak relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-lift sm:p-7">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-700"><x-icon name="x-circle" class="h-6 w-6" /></span>
            <h2 id="modal-tidak-cocok-judul" class="mt-4 text-xl font-extrabold text-ink">Hasil Interview Tidak Cocok</h2>
            <p class="mt-1 text-[15px] text-muted">Pesanan {{ $pemesanan->kode() }} akan dibatalkan. {{ $pemesanan->profilPekerja->pengguna->nama }} tetap tampil di katalog.</p>
            <form method="POST" action="{{ route('admin.orders.interview.nomatch', $pemesanan) }}" class="mt-5 space-y-4" novalidate>
                @csrf
                <x-field name="alasan_tidak_cocok" type="textarea" label="Alasan" required rows="3" maxlength="500" placeholder="Contoh: Klien membutuhkan pekerja yang bisa menginap." />
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <button type="button" class="btn-secondary" data-modal-tutup>Batal</button>
                    <button type="submit" class="btn-danger"><x-icon name="x" class="h-5 w-5" /> Simpan Hasil</button>
                </div>
            </form>
        </div>
    </div>
@elseif ($jadwal && $jadwal->hasilSudahDicatat())
    @php
        $cocok = $jadwal->hasil_interview === 'cocok';
        $penyaluran = $pemesanan->penyaluran;
    @endphp
    <div class="card overflow-hidden" id="hasil-interview">
        <div class="card-head flex-wrap">
            <h2 class="card-title flex items-center gap-2"><x-icon name="award" class="h-5 w-5 text-primary" /> Hasil Interview</h2>
            <x-badge :color="$cocok ? 'green' : 'red'">{{ $jadwal->labelHasil() }}</x-badge>
        </div>
        <div class="card-body space-y-4">
            <div class="rounded-2xl p-4 text-[15px] {{ $cocok ? 'bg-green-50 text-green-900 ring-1 ring-green-200' : 'bg-red-50 text-red-900 ring-1 ring-red-200' }}">
                @if ($cocok)
                    <p class="font-bold">Pekerja tersalurkan{{ $penyaluran ? ' mulai ' . $penyaluran->labelTanggalMulai() : '' }}.</p>
                    @if ($penyaluran)
                        <p class="mt-1">Status penyaluran: <strong>{{ $penyaluran->labelStatus() }}</strong>@if ($penyaluran->tanggal_berakhir) · berakhir {{ $penyaluran->tanggal_berakhir->locale('id')->translatedFormat('d F Y') }}@endif</p>
                    @endif
                @else
                    <p class="font-bold">Hasil interview tidak cocok, pesanan dibatalkan.</p>
                @endif
                @if ($jadwal->catatan_hasil)
                    <p class="mt-1"><span class="font-semibold">{{ $cocok ? 'Catatan' : 'Alasan' }}:</span> {{ $jadwal->catatan_hasil }}</p>
                @endif
                <p class="mt-2 text-sm opacity-80">Dicatat {{ \App\Models\JadwalInterview::tampilWib($jadwal->hasil_dicatat_pada) }}{{ $jadwal->pencatatHasil ? ' oleh ' . $jadwal->pencatatHasil->nama : '' }}</p>
            </div>

            <div>
                <p class="mb-2 text-xs font-bold uppercase tracking-wide text-muted">Kabari via WhatsApp</p>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach (\App\Models\JadwalInterview::PIHAK as $pihak => $labelPihak)
                        @php $orang = $jadwal->penerima($pihak); $link = $jadwal->linkHasil($pihak); @endphp
                        @if ($link)
                            <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="btn w-full bg-[#1DA851] text-white shadow-sm hover:bg-[#178A43]">
                                <x-ikon-whatsapp class="h-5 w-5" /> Kabari {{ $labelPihak }} ({{ \Illuminate\Support\Str::words($orang->nama, 2, '') }})
                            </a>
                        @else
                            <button type="button" class="btn w-full cursor-not-allowed bg-gray-100 text-gray-500" disabled aria-disabled="true">
                                <x-ikon-whatsapp class="h-5 w-5" /> {{ $labelPihak }}: Nomor WhatsApp belum tersedia
                            </button>
                        @endif
                    @endforeach
                </div>
                @if ($cocok && $penyaluran?->isAktif())
                    <a href="{{ route('admin.placements', ['cari' => $pemesanan->profilPekerja->pengguna->nama]) }}" class="mt-3 inline-flex items-center gap-1.5 text-sm font-bold text-primary hover:underline">Kelola di menu Penyaluran <x-icon name="arrow-right" class="h-4 w-4" /></a>
                @endif
            </div>
        </div>
    </div>
@endif
