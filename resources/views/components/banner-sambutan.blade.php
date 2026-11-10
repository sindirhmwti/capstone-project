{{--
    Banner sambutan berwarna untuk halaman utama dashboard, dengan foto asli di sisi kanan.
    Pemakaian: <x-banner-sambutan eyebrow="..." :title="..." text="..." foto="hero-keluarga" posisi-foto="object-center"> tombol aksi </x-banner-sambutan>
    - foto        : nama file di public/images/photos (tanpa .webp)
    - posisi-foto : kelas object-position agar wajah tidak terpotong (mis. object-top untuk foto potret)
    - label-foto  : teks kecil pada kartu melayang di atas foto
--}}
@props([
    'eyebrow' => null, 'title', 'text' => null,
    'foto' => 'hero-keluarga', 'posisiFoto' => 'object-center', 'labelFoto' => 'Pekerja diperiksa admin yayasan',
    'illustration' => null, // tidak dipakai lagi; dibiarkan agar pemanggilan lama tetap berjalan
])

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

        {{-- Foto asli (Unsplash/Pexels, lihat public/images/photos/CREDITS.md) --}}
        <div class="relative hidden md:block md:mr-2" aria-hidden="true">
            <div class="absolute -inset-2 rotate-3 rounded-[1.75rem] bg-white/10 ring-1 ring-white/15"></div>
            <div class="relative h-48 w-64 overflow-hidden rounded-3xl shadow-2xl ring-4 ring-white/25 lg:h-56 lg:w-80">
                <img src="{{ asset('images/photos/' . $foto . '.webp') }}" alt="" class="h-full w-full {{ $posisiFoto }} object-cover transition-transform duration-700 hover:scale-105" loading="lazy" width="640" height="448">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-primary-darker/45 via-transparent to-transparent"></div>
            </div>
            @if ($labelFoto)
                <div class="melayang-2 absolute -bottom-3 -left-6 flex items-center gap-2 rounded-2xl bg-white px-3 py-2 text-xs font-bold text-ink shadow-lift">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-soft text-primary"><x-icon name="shield-check" class="h-4 w-4" /></span>
                    {{ $labelFoto }}
                </div>
            @endif
            <span class="melayang-1 absolute -right-2 -top-2 h-6 w-6 rounded-full bg-sun ring-4 ring-sun/30"></span>
        </div>
    </div>
</section>
