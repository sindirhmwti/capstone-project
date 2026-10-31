{{-- Judul halaman dashboard: breadcrumb kecil, judul, dan keterangan (sesuai mockup) --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
    <div>
        <p class="text-[10px] font-semibold tracking-widest uppercase text-[#52645B]">{{ $crumb }}</p>
        <h2 class="text-2xl font-bold text-[#172B23] mt-1">{{ $title }}</h2>
        <p class="text-sm text-[#52645B] mt-1">{{ $subtitle ?? 'Kelola informasi dan aktivitas Anda dalam satu tempat.' }}</p>
    </div>
    @isset($aksi)
        <div class="flex items-center gap-2 print:hidden">{!! $aksi !!}</div>
    @endisset
</div>
