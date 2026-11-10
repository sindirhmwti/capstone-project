{{--
    Modal form jadwal interview (dipakai untuk "Setujui" dan "Ubah Jadwal & Kirim Ulang").
    Variabel: $idModal, $aksi (URL), $metodeHttp ('POST' | 'PUT'), $judul, $keterangan, $teksTombol, $jadwal (JadwalInterview|null)
--}}
@php

    $kolomJadwal = ['tanggal_interview', 'waktu_interview', 'metode', 'lokasi_atau_link', 'catatan_interview'];
    $bukaOtomatis = $errors->hasAny($kolomJadwal);
    $metodeAwal = old('metode', $jadwal?->metode ?? 'offline');
@endphp

<div id="{{ $idModal }}" class="modal-tutup fixed inset-0 z-[80] hidden items-end justify-center p-4 sm:items-center print:hidden"
     role="dialog" aria-modal="true" aria-labelledby="{{ $idModal }}-judul" data-modal-form @if ($bukaOtomatis) data-modal-otomatis @endif>
    <div class="modal-latar absolute inset-0 bg-ink/50 backdrop-blur-sm" data-modal-tutup></div>
    <div class="modal-kotak relative max-h-[92vh] w-full max-w-xl overflow-y-auto rounded-3xl bg-white shadow-lift">
        <div class="sticky top-0 z-10 flex items-start gap-3 border-b border-line bg-white/95 px-6 py-5 backdrop-blur">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-primary-soft text-primary"><x-icon name="calendar" class="h-6 w-6" /></span>
            <div class="min-w-0 flex-1">
                <h2 id="{{ $idModal }}-judul" class="text-xl font-extrabold text-ink">{{ $judul }}</h2>
                <p class="mt-0.5 text-sm text-muted">{{ $keterangan }}</p>
            </div>
            <button type="button" class="rounded-xl p-2 text-muted hover:bg-canvas hover:text-ink" data-modal-tutup aria-label="Tutup"><x-icon name="x" class="h-5 w-5" /></button>
        </div>

        <form method="POST" action="{{ $aksi }}" class="space-y-5 px-6 py-6" novalidate>
            @csrf
            @if ($metodeHttp !== 'POST') @method($metodeHttp) @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <x-field name="tanggal_interview" type="date" label="Tanggal interview" required
                         :value="$jadwal?->tanggal_interview?->format('Y-m-d')" :min="now(\App\Models\JadwalInterview::ZONA)->toDateString()" />
                <x-field name="waktu_interview" type="time" label="Waktu (WIB)" required :value="$jadwal?->labelJam()" />
            </div>

            <fieldset>
                <legend class="label">Metode <span class="required">*</span></legend>
                <div class="grid grid-cols-2 gap-3">
                    @foreach (['offline' => ['map-pin', 'Offline', 'Tatap muka di lokasi'], 'online' => ['external-link', 'Online', 'Video call / meeting']] as $nilai => [$ikon, $label, $sub])
                        <label class="cursor-pointer">
                            <input type="radio" name="metode" value="{{ $nilai }}" class="peer sr-only" data-metode-pilih @checked($metodeAwal === $nilai)>
                            <span class="flex items-center gap-3 rounded-2xl border-2 border-line bg-white p-3.5 transition peer-checked:border-primary peer-checked:bg-primary-soft peer-focus-visible:ring-4 peer-focus-visible:ring-primary/20">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-canvas text-primary"><x-icon :name="$ikon" class="h-5 w-5" /></span>
                                <span><span class="block font-bold text-ink">{{ $label }}</span><span class="block text-xs text-muted">{{ $sub }}</span></span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('metode') <p class="field-error"><x-icon name="alert-circle" class="h-4 w-4" /> {{ $message }}</p> @enderror
            </fieldset>

            <div data-lokasi-wadah>
                <x-field name="lokasi_atau_link" :label="$metodeAwal === 'online' ? 'Link meeting' : 'Lokasi interview'" required maxlength="255"
                         :value="$jadwal?->lokasi_atau_link"
                         :placeholder="$metodeAwal === 'online' ? 'https://meet.google.com/abc-defg-hij' : 'Contoh: Kantor PT Shaka Abadi Nusantara, Jl. ...'"
                         data-lokasi-input />
            </div>

            <x-field name="catatan_interview" type="textarea" label="Catatan (opsional)" rows="3" maxlength="1000"
                     :value="$jadwal?->catatan" placeholder="Contoh: Mohon datang 10 menit lebih awal dan melapor ke resepsionis." />

            <div class="flex items-start gap-3 rounded-2xl bg-mint-soft px-4 py-3 text-sm text-primary-darker">
                <x-ikon-whatsapp class="mt-0.5 h-5 w-5" />
                <p>Setelah disimpan, kirim undangan ke klien dan calon pekerja lewat tombol <strong>Kirim Undangan via WhatsApp</strong> di panel interview.</p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2">
                <button type="button" class="btn-secondary" data-modal-tutup>Batal</button>
                <button type="submit" class="btn-primary"><x-icon name="check" class="h-5 w-5" /> {{ $teksTombol }}</button>
            </div>
        </form>
    </div>
</div>
