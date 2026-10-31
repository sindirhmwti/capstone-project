{{-- Judul halaman dashboard: label kecil, judul, keterangan, dan aksi utama di kanan --}}
<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between lg:mb-8">
    <div class="min-w-0">
        <p class="eyebrow"><span class="h-1.5 w-5 rounded-full bg-primary-accent"></span>{{ $crumb }}</p>
        <h1 class="page-title mt-2">{{ $title }}</h1>
        <p class="mt-2 max-w-3xl text-base text-muted">{{ $subtitle ?? 'Kelola informasi dan aktivitas Anda dalam satu tempat.' }}</p>
    </div>
    @isset($aksi)
        <div class="flex shrink-0 flex-wrap items-center gap-2 print:hidden">{!! $aksi !!}</div>
    @endisset
</div>
