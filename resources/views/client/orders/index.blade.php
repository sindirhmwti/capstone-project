@extends('layouts.client')
@section('title', 'Riwayat Pesanan')
@section('content')

@include('components.page-header', ['crumb' => 'Klien / Riwayat Pesanan', 'title' => 'Riwayat Pesanan'])

<div class="flex flex-wrap gap-2 mb-5">
    @foreach (['semua' => 'Semua', 'menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'disetujui' => 'Disetujui', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'dibatalkan' => 'Dibatalkan'] as $kode => $label)
        <a href="{{ route('client.orders', ['status' => $kode]) }}"
           class="px-3 py-1.5 rounded-lg text-sm border {{ $status === $kode ? 'bg-[#E4F2E8] border-[#176B4D]/30 text-[#176B4D] font-semibold' : 'bg-white border-gray-200 text-[#52645B] hover:bg-gray-50' }}">
            {{ $label }} <span class="ml-1 text-xs">{{ $kode === 'semua' ? $jumlah->sum() : ($jumlah[$kode] ?? 0) }}</span>
        </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="mb-4">
        <h3 class="font-semibold text-[#172B23]">Pesanan Anda</h3>
        <p class="text-xs text-[#52645B]">Hanya pengajuan milik akun klien ini yang ditampilkan.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-[#52645B] bg-[#F5F7F5]">
                    <th class="px-3 py-2.5 font-medium rounded-l-lg">Pesanan / Pekerja</th>
                    <th class="px-3 py-2.5 font-medium">Jenis pekerjaan</th>
                    <th class="px-3 py-2.5 font-medium">Tanggal pengajuan</th>
                    <th class="px-3 py-2.5 font-medium">Jadwal pekerjaan</th>
                    <th class="px-3 py-2.5 font-medium">Status</th>
                    <th class="px-3 py-2.5 font-medium rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesanan as $o)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-3 py-3">
                            <p class="font-medium text-[#172B23]">{{ $o->profilPekerja->pengguna->nama }}</p>
                            <p class="text-xs text-[#52645B]">{{ $o->kode() }}</p>
                        </td>
                        <td class="px-3 py-3">{{ $o->profilPekerja->labelKategori() }}</td>
                        <td class="px-3 py-3 text-[#52645B]">{{ $o->tanggal_pemesanan->format('d/m/Y') }}</td>
                        <td class="px-3 py-3 text-[#52645B]">{{ $o->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-3 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->warnaStatus() }}">{{ $o->labelStatus() }}</span></td>
                        <td class="px-3 py-3"><a href="{{ route('client.orders.show', $o) }}" class="text-[#176B4D] font-medium hover:underline">Lihat Detail →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-3 py-10 text-center text-[#52645B]">
                        Belum terdapat riwayat pesanan{{ $status !== 'semua' ? ' dengan status ini' : '' }}.
                        <a href="{{ route('client.workers') }}" class="text-[#176B4D] hover:underline ml-1">Cari pekerja →</a>
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $pesanan->links() }}</div>
</div>

@endsection
