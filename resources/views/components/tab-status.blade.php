{{-- Tab filter status pesanan. Parameter: $route, $status (aktif), $jumlah (koleksi per status), $extra (parameter query tambahan) --}}
@php
    $tab = ['semua' => 'Semua', 'menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'disetujui' => 'Disetujui', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'dibatalkan' => 'Dibatalkan'];
@endphp
<nav class="scrollbar-hide -mx-4 mb-5 flex gap-2 overflow-x-auto px-4 sm:mx-0 sm:flex-wrap sm:px-0" aria-label="Filter status">
    @foreach ($tab as $kode => $label)
        <a href="{{ route($route, array_filter(array_merge(['status' => $kode], $extra ?? []))) }}"
           class="pill shrink-0 {{ $status === $kode ? 'pill-active' : '' }}" @if ($status === $kode) aria-current="page" @endif>
            {{ $label }} <span class="pill-count">{{ $kode === 'semua' ? $jumlah->sum() : ($jumlah[$kode] ?? 0) }}</span>
        </a>
    @endforeach
</nav>
