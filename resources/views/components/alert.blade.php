{{--
    Kotak pemberitahuan di dalam halaman.
    Pemakaian: <x-alert type="warning" title="Judul">Isi pesan</x-alert>   (type: info | success | warning | danger)
--}}
@props(['type' => 'info', 'title' => null])

@php
    [$kelas, $ikon] = [
        'info' => ['border-sky-200 bg-sky-50 text-sky-900', 'info'],
        'success' => ['border-green-200 bg-green-50 text-green-900', 'check-circle'],
        'warning' => ['border-amber-200 bg-sun-soft text-amber-900', 'alert-circle'],
        'danger' => ['border-red-200 bg-red-50 text-red-900', 'x-circle'],
    ][$type] ?? ['border-sky-200 bg-sky-50 text-sky-900', 'info'];
@endphp

<div {{ $attributes->merge(['class' => "flex gap-3 rounded-2xl border px-4 py-3.5 text-sm $kelas"]) }} role="{{ $type === 'danger' ? 'alert' : 'status' }}">
    <x-icon :name="$ikon" class="mt-0.5 h-5 w-5" />
    <div class="min-w-0 flex-1">
        @if ($title)<p class="font-bold">{{ $title }}</p>@endif
        <div class="{{ $title ? 'mt-0.5' : '' }} leading-relaxed">{{ $slot }}</div>
    </div>
</div>
