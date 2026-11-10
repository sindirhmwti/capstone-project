{{-- Ringkasan jadwal interview. Variabel: $jadwal (JadwalInterview), opsional $ringkas (bool) --}}
@php $ringkas = $ringkas ?? false; @endphp
<dl class="grid gap-2.5 {{ $ringkas ? 'sm:grid-cols-2' : '' }} text-[15px]">
    <div class="flex items-start gap-3 rounded-2xl bg-canvas px-4 py-3">
        <x-icon name="calendar" class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
        <div class="min-w-0"><dt class="text-xs font-semibold uppercase tracking-wide text-muted">Tanggal</dt><dd class="font-bold text-ink">{{ $jadwal->labelTanggal() }}</dd></div>
    </div>
    <div class="flex items-start gap-3 rounded-2xl bg-canvas px-4 py-3">
        <x-icon name="clock" class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
        <div class="min-w-0"><dt class="text-xs font-semibold uppercase tracking-wide text-muted">Waktu</dt><dd class="font-bold text-ink">{{ $jadwal->labelWaktu() }}</dd></div>
    </div>
    <div class="flex items-start gap-3 rounded-2xl bg-canvas px-4 py-3">
        <x-icon name="briefcase" class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
        <div class="min-w-0"><dt class="text-xs font-semibold uppercase tracking-wide text-muted">Metode</dt><dd class="font-bold text-ink">{{ $jadwal->labelMetode() }}</dd></div>
    </div>
    <div class="flex items-start gap-3 rounded-2xl bg-canvas px-4 py-3">
        <x-icon :name="$jadwal->isOnline() ? 'external-link' : 'map-pin'" class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
        <div class="min-w-0">
            <dt class="text-xs font-semibold uppercase tracking-wide text-muted">{{ $jadwal->labelLokasi() }}</dt>
            <dd class="break-words font-bold text-ink">
                @if ($jadwal->isOnline())
                    <a href="{{ $jadwal->lokasi_atau_link }}" target="_blank" rel="noopener noreferrer" class="break-all text-primary underline-offset-2 hover:underline">{{ $jadwal->lokasi_atau_link }}</a>
                @else
                    {{ $jadwal->lokasi_atau_link }}
                @endif
            </dd>
        </div>
    </div>
    @if ($jadwal->catatan)
        <div class="flex items-start gap-3 rounded-2xl bg-canvas px-4 py-3 {{ $ringkas ? 'sm:col-span-2' : '' }}">
            <x-icon name="file-text" class="mt-0.5 h-5 w-5 shrink-0 text-primary" />
            <div class="min-w-0"><dt class="text-xs font-semibold uppercase tracking-wide text-muted">Catatan</dt><dd class="whitespace-pre-line text-ink">{{ $jadwal->catatan }}</dd></div>
        </div>
    @endif
</dl>
