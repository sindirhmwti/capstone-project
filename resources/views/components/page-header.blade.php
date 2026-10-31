{{-- Judul halaman dashboard: breadcrumb kecil, judul, keterangan, dan aksi utama di kanan --}}
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between lg:mb-8">
    <div class="min-w-0">
        <p class="eyebrow">{{ $crumb }}</p>
        <h1 class="page-title mt-1.5">{{ $title }}</h1>
        <p class="mt-1.5 max-w-2xl text-sm text-muted">{{ $subtitle ?? 'Kelola informasi dan aktivitas Anda dalam satu tempat.' }}</p>
    </div>
    @isset($aksi)
        <div class="flex shrink-0 flex-wrap items-center gap-2 print:hidden">{!! $aksi !!}</div>
    @endisset
</div>
