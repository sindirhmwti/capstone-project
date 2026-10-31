{{--
    Kartu statistik dashboard berwarna dengan angka berjalan (count-up).
    Pemakaian: <x-stat icon="users" label="Total pekerja" :value="12" tone="brand" :href="route(...)" note="..." />
--}}
@props(['icon', 'label', 'value' => 0, 'tone' => 'brand', 'href' => null, 'note' => null])

@php
    [$latar, $ikonBg, $garis] = [
        'brand' => ['bg-primary-soft', 'bg-primary text-white', 'from-primary to-primary-accent'],
        'amber' => ['bg-sun-soft', 'bg-sun text-ink', 'from-sun to-amber-300'],
        'blue' => ['bg-sky-50', 'bg-sky-500 text-white', 'from-sky-500 to-sky-300'],
        'rose' => ['bg-rose-50', 'bg-rose-500 text-white', 'from-rose-500 to-rose-300'],
        'violet' => ['bg-violet-50', 'bg-violet-500 text-white', 'from-violet-500 to-violet-300'],
    ][$tone] ?? ['bg-primary-soft', 'bg-primary text-white', 'from-primary to-primary-accent'];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => "reveal group relative flex flex-col overflow-hidden rounded-3xl $latar p-4 sm:p-6" . ($href ? ' transition duration-300 hover:-translate-y-1 hover:shadow-lift' : '')]) }}>
    <div class="relative flex items-start justify-between">
        <span class="flex h-10 w-10 items-center justify-center rounded-2xl shadow-sm sm:h-12 sm:w-12 {{ $ikonBg }}"><x-icon :name="$icon" class="h-5 w-5 sm:h-6 sm:w-6" /></span>
        @if ($href)
            <span class="hidden h-9 w-9 items-center justify-center rounded-full bg-white/70 sm:flex text-muted transition-all group-hover:bg-white group-hover:text-primary">
                <x-icon name="arrow-right" class="h-5 w-5 transition-transform group-hover:translate-x-0.5" />
            </span>
        @endif
    </div>
    @if (is_numeric($value))
        <p class="relative mt-4 text-3xl font-extrabold leading-none text-ink sm:mt-5 sm:text-4xl" data-count="{{ $value }}">{{ $value }}</p>
    @else
        <p class="relative mt-5 text-xl font-extrabold leading-tight text-ink">{{ $value }}</p>
    @endif
    <p class="relative mt-2 text-sm font-bold leading-snug text-ink/80 sm:text-[15px]">{{ $label }}</p>
    @if ($note)
        <p class="relative mt-0.5 text-sm text-muted">{{ $note }}</p>
    @endif
    <span class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r {{ $garis }}"></span>
</{{ $tag }}>
