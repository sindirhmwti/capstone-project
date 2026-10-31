{{-- Toast pesan sukses / error / info (muncul di pojok kanan atas lalu hilang otomatis) --}}
@php
    $gaya = [
        'success' => ['border-green-200', 'text-green-600', 'check-circle', 'Berhasil'],
        'error' => ['border-red-200', 'text-red-600', 'alert-circle', 'Gagal'],
        'info' => ['border-sky-200', 'text-sky-600', 'info', 'Informasi'],
    ];
@endphp
<div id="toast-area" class="pointer-events-none fixed inset-x-4 top-4 z-[70] flex flex-col items-end gap-3 sm:left-auto sm:right-6 sm:w-96 print:hidden" aria-live="polite">
    @foreach ($gaya as $jenis => [$border, $warnaIkon, $ikon, $judul])
        @if (session($jenis))
            <div class="toast toast-hide pointer-events-auto flex w-full items-start gap-3 rounded-2xl border bg-white p-4 shadow-lift {{ $border }}" data-toast role="{{ $jenis === 'error' ? 'alert' : 'status' }}">
                <x-icon :name="$ikon" class="mt-0.5 h-5 w-5 {{ $warnaIkon }}" />
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-ink">{{ $judul }}</p>
                    <p class="text-sm text-muted">{{ session($jenis) }}</p>
                </div>
                <button type="button" class="rounded-lg p-1 text-muted hover:bg-canvas hover:text-ink" data-toast-close aria-label="Tutup pesan">
                    <x-icon name="x" class="h-4 w-4" />
                </button>
            </div>
        @endif
    @endforeach
</div>
