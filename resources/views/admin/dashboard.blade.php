@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')

@include('components.page-header', [
    'crumb' => 'Admin / Dashboard Admin',
    'title' => 'Dashboard Admin',
    'subtitle' => 'Selamat datang kembali, ' . auth()->user()->nama . ' · ' . now()->translatedFormat('l, d F Y'),
])

{{-- Ringkasan --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    @foreach ([
        ['Total Pekerja', $stats['total_workers'], $stats['verified_workers'] . ' tampil di katalog', 'bg-[#176B4D]', 'M17 20h5v-2a4 4 0 00-5-5M9 20H4v-2a4 4 0 015-5m6 0a4 4 0 10-8 0 4 4 0 008 0zm6-10a4 4 0 11-8 0 4 4 0 018 0z', route('admin.workers')],
        ['Menunggu Verifikasi', $stats['pending_verify'], 'Perlu ditinjau', 'bg-amber-500', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', route('admin.verification')],
        ['Pemesanan Masuk', $stats['incoming_orders'], 'Menunggu / diproses', 'bg-blue-600', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', Route::has('admin.orders') ? route('admin.orders') : '#'],
        ['Total Klien', $stats['total_clients'], $stats['placements_month'] . ' penyaluran bulan ini', 'bg-purple-600', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', Route::has('admin.reports') ? route('admin.reports') : '#'],
    ] as [$label, $nilai, $ket, $warna, $ikon, $link])
        <a href="{{ $link }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="{{ $warna }} rounded-xl p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ikon }}" /></svg>
                </div>
                <span class="text-xs font-medium bg-gray-100 text-[#52645B] px-2 py-1 rounded-full">{{ $ket }}</span>
            </div>
            <p class="text-3xl font-bold text-[#172B23]">{{ $nilai }}</p>
            <p class="text-sm text-[#52645B] mt-1">{{ $label }}</p>
        </a>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Menunggu verifikasi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-[#172B23]">Verifikasi Pekerja</h2>
            <a href="{{ route('admin.verification') }}" class="text-xs text-[#176B4D] font-medium hover:underline">Lihat semua</a>
        </div>
        @forelse ($pendingVerifications as $p)
            <a href="{{ route('admin.verification.show', $p) }}" class="flex items-center gap-3 py-3 border-b border-gray-100 last:border-0 hover:bg-[#F5F7F5] -mx-2 px-2 rounded-lg">
                <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold">{{ $p->pengguna->inisial() }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-[#172B23] truncate">{{ $p->pengguna->nama }}</p>
                    <p class="text-xs text-[#52645B]">{{ $p->labelKategori() }}</p>
                </div>
                <span class="text-xs text-[#176B4D] font-medium">Periksa →</span>
            </a>
        @empty
            <p class="text-sm text-[#52645B]">Tidak ada data yang menunggu verifikasi.</p>
        @endforelse

        <div class="mt-5 pt-4 border-t border-gray-100">
            <p class="text-xs font-semibold text-[#52645B] uppercase tracking-wider mb-2">Pekerja per kategori</p>
            @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                <div class="flex justify-between text-sm py-1">
                    <span>{{ $label }}</span><span class="font-semibold">{{ $perKategori[$kode] ?? 0 }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Pemesanan terbaru --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-[#172B23]">Pemesanan Terbaru</h2>
            @if (Route::has('admin.orders'))
                <a href="{{ route('admin.orders') }}" class="text-xs text-[#176B4D] font-medium hover:underline">Kelola pemesanan</a>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-[#52645B] bg-[#F5F7F5]">
                        <th class="px-3 py-2 font-medium rounded-l-lg">Pesanan</th>
                        <th class="px-3 py-2 font-medium">Klien</th>
                        <th class="px-3 py-2 font-medium">Pekerja</th>
                        <th class="px-3 py-2 font-medium">Tanggal</th>
                        <th class="px-3 py-2 font-medium rounded-r-lg">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $o)
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="px-3 py-3 font-medium">{{ $o->kode() }}</td>
                            <td class="px-3 py-3">{{ $o->klien->nama }}</td>
                            <td class="px-3 py-3">{{ $o->profilPekerja->pengguna->nama }} <span class="text-xs text-[#52645B]">({{ $o->profilPekerja->labelKategori() }})</span></td>
                            <td class="px-3 py-3 text-[#52645B]">{{ $o->tanggal_pemesanan->format('d/m/Y') }}</td>
                            <td class="px-3 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->warnaStatus() }}">{{ $o->labelStatus() }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-3 py-6 text-center text-[#52645B]">Belum ada pemesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
