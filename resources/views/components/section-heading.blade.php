{{--
    Judul bagian halaman publik: label kecil, judul besar, dan deskripsi.
    Pemakaian: <x-section-heading eyebrow="Layanan" title="..." lead="..." align="center" :light="true" />
--}}
@props(['eyebrow' => null, 'title', 'lead' => null, 'align' => 'center', 'light' => false])

<div {{ $attributes->merge(['class' => 'reveal ' . ($align === 'center' ? 'mx-auto max-w-3xl text-center' : 'max-w-2xl')]) }}>
    @if ($eyebrow)
        <p class="eyebrow {{ $light ? 'text-mint' : '' }}">
            <span class="h-1.5 w-6 rounded-full {{ $light ? 'bg-sun' : 'bg-primary-accent' }}"></span>{{ $eyebrow }}
        </p>
    @endif
    <h2 class="section-title mt-3 {{ $light ? 'text-white' : '' }}">{!! $title !!}</h2>
    @if ($lead)
        <p class="section-lead mt-4 {{ $light ? 'text-white/80' : '' }}">{{ $lead }}</p>
    @endif
    {{ $slot }}
</div>
