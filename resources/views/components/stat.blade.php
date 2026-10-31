{{--
    Kartu statistik dashboard dengan angka berjalan (count-up).
    Pemakaian: <x-stat icon="users" label="Total pekerja" :value="12" tone="brand" :href="route(...)" note="..." />
--}}
@props(['icon', 'label', 'value' => 0, 'tone' => 'brand', 'href' => null, 'note' => null])

@php
    $warna = [
        'brand' => 'bg-primary-soft text-primary',
        'amber' => 'bg-amber-50 text-amber-600',
        'blue' => 'bg-sky-50 text-sky-600',
        'rose' => 'bg-rose-50 text-rose-500',
        'violet' => 'bg-violet-50 text-violet-600',
    ][$tone] ?? 'bg-primary-soft text-primary';
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => 'card reveal group flex flex-col p-5' . ($href ? ' card-hover' : '')]) }}>
    <div class="flex items-start justify-between">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $warna }}"><x-icon :name="$icon" class="h-5 w-5" /></span>
        @if ($href)
            <x-icon name="arrow-right" class="h-5 w-5 text-muted/40 transition-all group-hover:translate-x-0.5 group-hover:text-primary" />
        @endif
    </div>
    @if (is_numeric($value))
        <p class="mt-4 text-3xl font-extrabold leading-none text-ink" data-count="{{ $value }}">{{ $value }}</p>
    @else
        <p class="mt-4 text-xl font-bold leading-tight text-ink">{{ $value }}</p>
    @endif
    <p class="mt-1.5 text-sm font-medium text-muted">{{ $label }}</p>
    @if ($note)
        <p class="mt-0.5 text-xs text-muted/90">{{ $note }}</p>
    @endif
</{{ $tag }}>
