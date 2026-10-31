{{--
    Badge status dengan warna konsisten di seluruh sistem.
    Pemakaian: <x-badge :status="$pesanan->status_pemesanan">{{ $pesanan->labelStatus() }}</x-badge>
    atau <x-badge color="green">Terverifikasi</x-badge>
--}}
@props(['status' => null, 'color' => null, 'dot' => true])

@php
    // Pemetaan status (pekerja, dokumen, pesanan, akun) ke warna
    $peta = [
        'terverifikasi' => 'green', 'valid' => 'green', 'disetujui' => 'green', 'aktif' => 'green',
        'selesai' => 'brand',
        'menunggu' => 'amber',
        'diproses' => 'blue',
        'ditolak' => 'red', 'tidak_valid' => 'red',
        'belum_lengkap' => 'gray', 'dibatalkan' => 'gray', 'nonaktif' => 'gray',
    ];
    $kelas = [
        'green' => 'bg-green-50 text-green-700 ring-green-600/20',
        'brand' => 'bg-primary-soft text-primary-dark ring-primary/20',
        'amber' => 'bg-amber-50 text-amber-800 ring-amber-600/25',
        'blue' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
        'red' => 'bg-red-50 text-red-700 ring-red-600/20',
        'gray' => 'bg-gray-100 text-gray-700 ring-gray-500/20',
    ];
    $warnaTitik = [
        'green' => 'bg-green-500', 'brand' => 'bg-primary', 'amber' => 'bg-amber-500',
        'blue' => 'bg-sky-500', 'red' => 'bg-red-500', 'gray' => 'bg-gray-400',
    ];
    $c = $color ?? ($peta[$status] ?? 'gray');
@endphp

<span {{ $attributes->merge(['class' => 'badge ring-1 ring-inset ' . ($kelas[$c] ?? $kelas['gray'])]) }}>
    @if ($dot)<span class="h-1.5 w-1.5 rounded-full {{ $warnaTitik[$c] ?? 'bg-gray-400' }}"></span>@endif
    {{ $slot }}
</span>
