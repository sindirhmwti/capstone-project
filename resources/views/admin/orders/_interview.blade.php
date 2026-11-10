{{-- Panel "Undangan & Konfirmasi Interview" di detail pesanan admin (saat status disetujui). Variabel: $pemesanan --}}
@php
    $jadwal = $pemesanan->jadwalInterview;
    $gayaStatus = [
        'menunggu' => ['bg-amber-50 text-amber-800 ring-amber-600/25', 'bg-amber-500'],
        'hadir' => ['bg-green-50 text-green-700 ring-green-600/20', 'bg-green-500'],
        'minta_ubah' => ['bg-orange-50 text-orange-700 ring-orange-600/25', 'bg-orange-500'],
        'tidak_merespons' => ['bg-red-50 text-red-700 ring-red-600/20', 'bg-red-500'],
    ];
@endphp

<div class="card overflow-hidden" id="jadwal-interview">
    <div class="card-head flex-wrap">
        <div>
            <h2 class="card-title flex items-center gap-2"><x-ikon-whatsapp class="h-5 w-5 text-[#1DA851]" /> Undangan &amp; Konfirmasi Interview</h2>
            @if ($jadwal?->pernahDiubah())
                <p class="text-sm text-muted">Jadwal diubah {{ \App\Models\JadwalInterview::tampilWib($jadwal->jadwal_diubah_pada) }}, undangan berikutnya diawali "PERUBAHAN JADWAL INTERVIEW".</p>
            @else
                <p class="text-sm text-muted">Kirim undangan lewat WhatsApp, lalu catat jawaban yang Anda terima.</p>
            @endif
        </div>
        <button type="button" class="btn-secondary btn-sm" data-modal-buka="modal-jadwal">
            <x-icon name="{{ $jadwal ? 'pencil' : 'calendar' }}" class="h-4 w-4" /> {{ $jadwal ? 'Ubah Jadwal' : 'Buat Jadwal Interview' }}
        </button>
    </div>

    <div class="card-body">
        @if (! $jadwal)
            <x-alert type="info" title="Belum ada jadwal interview">
                Pesanan ini disetujui sebelum fitur undangan interview tersedia. Buat jadwal agar undangan bisa dikirim via WhatsApp.
            </x-alert>
        @else
            @include('interview._ringkasan', ['jadwal' => $jadwal, 'ringkas' => true])

            <div class="mt-5 grid gap-4 lg:grid-cols-2">
                @foreach (\App\Models\JadwalInterview::PIHAK as $pihak => $labelPihak)
                    @php
                        $orang = $jadwal->penerima($pihak);
                        $status = $jadwal->konfirmasi($pihak);
                        $dikirim = $jadwal->undanganDikirimPada($pihak);
                        $perluUlang = $jadwal->perluDihubungiUlang($pihak);
                        $linkUndangan = $jadwal->linkUndangan($pihak);
                        [$kelasBadge, $kelasTitik] = $gayaStatus[$status] ?? $gayaStatus['menunggu'];
                        $kunciCatatan = 'catatan_' . $pihak;
                    @endphp
                    <div class="flex flex-col rounded-2xl border-2 p-4 {{ $perluUlang || $status === 'tidak_merespons' ? 'border-red-200 bg-red-50/40' : ($status === 'hadir' ? 'border-green-200 bg-green-50/40' : 'border-line bg-white') }}" id="interview-{{ $pihak }}">
                        {{-- a. Identitas & status --}}
                        <div class="flex items-start justify-between gap-2">
                            <x-avatar :name="$orang->nama" :src="$orang->urlFotoProfil()" size="lg" />
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold uppercase tracking-[0.14em] text-primary">{{ $labelPihak }}</p>
                                <p class="mt-0.5 truncate font-bold text-ink">{{ $orang->nama }}</p>
                                <p class="flex items-center gap-1.5 text-sm text-muted"><x-icon name="phone" class="h-4 w-4" /> {{ \App\Support\WhatsApp::tampil($orang->no_telepon) }}</p>
                            </div>
                            <span class="badge shrink-0 ring-1 ring-inset {{ $kelasBadge }}"><span class="h-1.5 w-1.5 rounded-full {{ $kelasTitik }}"></span>{{ $jadwal->labelKonfirmasi($pihak) }}</span>
                        </div>

                        {{-- d. Perlu dihubungi ulang --}}
                        @if ($perluUlang)
                            <p class="mt-2"><x-badge color="red">Perlu dihubungi ulang</x-badge></p>
                        @endif

                        @if ($jadwal->catatanKonfirmasi($pihak))
                            <p class="mt-3 rounded-xl px-3 py-2.5 text-sm ring-1 {{ $status === 'minta_ubah' ? 'bg-orange-50 text-orange-900 ring-orange-200' : 'bg-canvas text-ink ring-line' }}">
                                <span class="font-semibold">Catatan:</span> {{ $jadwal->catatanKonfirmasi($pihak) }}
                            </p>
                        @endif

                        <dl class="mt-3 space-y-1.5 text-sm text-muted">
                            <div class="flex items-center gap-2"><x-icon name="send" class="h-4 w-4 shrink-0" />
                                <dd>{!! $dikirim ? 'Undangan dikirim pada <strong class="text-ink">' . e(\App\Models\JadwalInterview::tampilWib($dikirim)) . '</strong>' : 'Undangan belum dikirim' !!}</dd>
                            </div>
                            @if ($jadwal->dikonfirmasiPada($pihak))
                                <div class="flex items-center gap-2"><x-icon name="check-circle" class="h-4 w-4 shrink-0" /><dd>Jawaban dicatat {{ \App\Models\JadwalInterview::tampilWib($jadwal->dikonfirmasiPada($pihak)) }}</dd></div>
                            @endif
                        </dl>

                        {{-- b. Kirim undangan via WhatsApp (e. nonaktif bila nomor kosong) --}}
                        <div class="mt-4">
                            @if ($linkUndangan)
                                <form method="POST" action="{{ route('admin.orders.interview.invite', [$pemesanan, $pihak]) }}" target="_blank" data-kirim-wa data-no-loading>
                                    @csrf
                                    <button type="submit" class="btn w-full bg-[#1DA851] text-white shadow-sm hover:bg-[#178A43]">
                                        <x-ikon-whatsapp class="h-5 w-5" /> {{ $dikirim ? 'Kirim Ulang via WhatsApp' : 'Kirim Undangan via WhatsApp' }}
                                    </button>
                                </form>
                                <details class="group mt-2">
                                    <summary class="cursor-pointer list-none text-sm font-semibold text-primary hover:underline [&::-webkit-details-marker]:hidden">
                                        <x-icon name="chevron-down" class="inline h-4 w-4 transition-transform group-open:rotate-180" /> Lihat isi pesan
                                    </summary>
                                    <pre class="mt-2 max-h-64 overflow-auto whitespace-pre-wrap rounded-xl bg-canvas p-3 font-sans text-[13px] leading-relaxed text-ink">{{ $jadwal->pesanUndangan($pihak) }}</pre>
                                </details>
                            @else
                                <button type="button" class="btn w-full cursor-not-allowed bg-gray-100 text-gray-500" disabled aria-disabled="true">
                                    <x-ikon-whatsapp class="h-5 w-5" /> Nomor WhatsApp belum tersedia
                                </button>
                            @endif
                        </div>

                        {{-- c. Catat jawaban dari WhatsApp --}}
                        <div class="mt-4 border-t border-line pt-4">
                            <p class="mb-2 text-xs font-bold uppercase tracking-wide text-muted">Catat jawaban</p>
                            <div class="flex flex-wrap gap-2">
                                @if ($status !== 'hadir')
                                    <form method="POST" action="{{ route('admin.orders.interview.mark', [$pemesanan, $pihak]) }}" data-confirm="Tandai {{ $orang->nama }} HADIR interview?">
                                        @csrf
                                        <input type="hidden" name="status" value="hadir">
                                        <button type="submit" class="btn-secondary btn-sm border-green-300 text-green-700 hover:bg-green-50"><x-icon name="check" class="h-4 w-4" /> Tandai Hadir</button>
                                    </form>
                                @endif
                                @if ($status !== 'tidak_merespons')
                                    <form method="POST" action="{{ route('admin.orders.interview.mark', [$pemesanan, $pihak]) }}" data-confirm="Tandai {{ $orang->nama }} TIDAK MERESPONS?">
                                        @csrf
                                        <input type="hidden" name="status" value="tidak_merespons">
                                        <button type="submit" class="btn-secondary btn-sm border-red-200 text-red-700 hover:bg-red-50"><x-icon name="x-circle" class="h-4 w-4" /> Tandai Tidak Merespons</button>
                                    </form>
                                @endif
                            </div>
                            <details class="group mt-2" @if ($errors->has($kunciCatatan)) open @endif>
                                <summary class="btn-secondary btn-sm cursor-pointer list-none border-orange-200 text-orange-700 hover:bg-orange-50 [&::-webkit-details-marker]:hidden">
                                    <x-icon name="calendar" class="h-4 w-4" /> Tandai Minta Ubah Jadwal <x-icon name="chevron-down" class="h-4 w-4 transition-transform group-open:rotate-180" />
                                </summary>
                                <form method="POST" action="{{ route('admin.orders.interview.mark', [$pemesanan, $pihak]) }}" class="animate-fade-up mt-2 space-y-3 rounded-xl border border-line bg-white p-3" novalidate>
                                    @csrf
                                    <input type="hidden" name="status" value="minta_ubah">
                                    <x-field :name="$kunciCatatan" :id="'catatan-' . $pihak" type="textarea" label="Catatan / alasan dari WhatsApp" required rows="2" maxlength="500"
                                             :value="$status === 'minta_ubah' ? $jadwal->catatanKonfirmasi($pihak) : null" placeholder="Contoh: Berhalangan, minta diundur ke Jumat pagi." />
                                    <button type="submit" class="btn-primary btn-sm w-full">Simpan Minta Ubah Jadwal</button>
                                </form>
                            </details>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="text-helper mt-4">
                Tombol WhatsApp membuka wa.me di tab baru dengan pesan terisi otomatis, lalu tekan <strong>Kirim</strong> di WhatsApp.
                Tanda merah muncul bila status masih Menunggu lebih dari {{ \App\Models\JadwalInterview::BATAS_TANPA_RESPONS_JAM }} jam sejak undangan dikirim.
            </p>
        @endif
    </div>
</div>
