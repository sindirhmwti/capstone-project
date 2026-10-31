{{--
    Banner sambutan berwarna untuk halaman utama dashboard.
    Pemakaian: <x-banner-sambutan eyebrow="..." :title="..." text="..." illustration="sambutan"> tombol aksi </x-banner-sambutan>
--}}
@props(['eyebrow' => null, 'title', 'text' => null, 'illustration' => 'sambutan'])

<section {{ $attributes->merge(['class' => 'reveal relative mb-6 overflow-hidden rounded-3xl bg-gradient-to-br from-primary-darker via-primary-dark to-primary text-white lg:mb-8']) }}>
    <img src="{{ asset('images/photos/ruang-keluarga.webp') }}" alt="" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-25 mix-blend-luminosity" loading="lazy" width="1440" height="810">
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-primary-darker via-primary-dark/90 to-primary/60"></div>
    <div class="bg-grid-light pointer-events-none absolute inset-0"></div>
    <div class="pointer-events-none absolute -right-16 -top-16 h-72 w-72 rounded-full bg-primary-accent/30 blur-2xl"></div>
    <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-sun/15 blur-2xl"></div>
    <div class="relative grid items-center gap-6 p-6 sm:p-8 md:grid-cols-[1fr_auto] lg:p-10">
        <div class="max-w-2xl">
            @if ($eyebrow)
                <p class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.14em] text-mint ring-1 ring-white/15">
                    <span class="h-2 w-2 rounded-full bg-sun"></span>{{ $eyebrow }}
                </p>
            @endif
            <h1 class="mt-4 text-2xl font-extrabold leading-tight text-white sm:text-3xl lg:text-[2.1rem]">{{ $title }}</h1>
            @if ($text)
                <p class="mt-3 text-base text-white/80 sm:text-lg">{{ $text }}</p>
            @endif
            @if (trim($slot))
                <div class="mt-6 flex flex-wrap gap-3">{{ $slot }}</div>
            @endif
        </div>
        <img src="{{ asset('images/illustrations/' . $illustration . '.svg') }}" alt="" class="hidden h-48 w-auto md:block lg:h-56" width="300" height="240">
    </div>
</section>
