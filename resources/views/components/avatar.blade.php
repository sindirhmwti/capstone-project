{{--
    Avatar inisial nama dengan latar warna lembut (pengganti foto profil).
    Warna dipilih tetap berdasarkan nama agar setiap orang konsisten.
    Pemakaian: <x-avatar :name="$nama" size="lg" />
--}}
@props(['name' => '', 'size' => 'md'])

@php
    $kata = preg_split('/\s+/', trim($name)) ?: [];
    $inisial = mb_strtoupper(mb_substr($kata[0] ?? '', 0, 1) . mb_substr($kata[1] ?? '', 0, 1)) ?: 'U';
    $palet = [
        'bg-primary-soft text-primary-dark',
        'bg-amber-100 text-amber-800',
        'bg-sky-100 text-sky-800',
        'bg-rose-100 text-rose-800',
        'bg-violet-100 text-violet-800',
        'bg-teal-100 text-teal-800',
    ];
    $warna = $palet[abs(crc32($name)) % count($palet)];
    $ukuran = [
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-11 w-11 text-sm',
        'lg' => 'h-14 w-14 text-lg',
        'xl' => 'h-20 w-20 text-2xl',
        '2xl' => 'h-24 w-24 text-3xl',
    ][$size] ?? 'h-11 w-11 text-sm';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex shrink-0 items-center justify-center rounded-full font-bold ring-2 ring-white $warna $ukuran"]) }} aria-hidden="true">{{ $inisial }}</span>
