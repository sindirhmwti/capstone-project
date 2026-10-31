@extends('layouts.admin')
@section('title', 'Kelola Pemesanan')
@section('content')

@include('components.page-header', ['crumb' => 'Admin / Kelola Pemesanan', 'title' => 'Kelola Pemesanan'])

<div class="flex flex-wrap gap-2 mb-5">
    @foreach (['semua' => 'Semua', 'menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'disetujui' => 'Disetujui', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'dibatalkan' => 'Dibatalkan'] as $kode => $label)
        <a href="{{ route('admin.orders', array_filter(['status' => $kode, 'cari' => request('cari')])) }}"
           class="px-3 py-1.5 rounded-lg text-sm border {{ $status === $kode ? 'bg-[#E4F2E8] border-[#176B4D]/30 text-[#176B4D] font-semibold' : 'bg-white border-gray-200 text-[#52645B] hover:bg-gray-50' }}">
            {{ $label }} <span class="ml-1 text-xs">{{ $kode === 'semua' ? $jumlah->sum() : ($jumlah[$kode] ?? 0) }}</span>
        </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <div>
            <h3 class="font-semibold text-[#172B23]">Pemesanan dari Klien</h3>
            <p class="text-xs text-[#52645B]">Periksa dan tindak lanjuti pengajuan pemesanan.</p>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama klien / pekerja..." class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
            <button class="px-4 py-2 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Cari</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-[#52645B] bg-[#F5F7F5]">
                    <th class="px-3 py-2.5 font-medium rounded-l-lg">Pesanan</th>
                    <th class="px-3 py-2.5 font-medium">Klien</th>
                    <th class="px-3 py-2.5 font-medium">Pekerja</th>
                    <th class="px-3 py-2.5 font-medium">Tanggal pengajuan</th>
                    <th class="px-3 py-2.5 font-medium">Jadwal</th>
                    <th class="px-3 py-2.5 font-medium">Status</th>
                    <th class="px-3 py-2.5 font-medium rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesanan as $o)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-3 py-3 font-medium">{{ $o->kode() }}</td>
                        <td class="px-3 py-3">{{ $o->klien->nama }}</td>
                        <td class="px-3 py-3">{{ $o->profilPekerja->pengguna->nama }}<br><span class="text-xs text-[#52645B]">{{ $o->profilPekerja->labelKategori() }}</span></td>
                        <td class="px-3 py-3 text-[#52645B]">{{ $o->tanggal_pemesanan->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-3 text-[#52645B]">{{ $o->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-3 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->warnaStatus() }}">{{ $o->labelStatus() }}</span></td>
                        <td class="px-3 py-3"><a href="{{ route('admin.orders.show', $o) }}" class="text-[#176B4D] font-medium hover:underline">{{ $o->statusBerikutnyaAdmin() ? 'Kelola' : 'Detail' }} →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-3 py-10 text-center text-[#52645B]">Tidak ada pemesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $pesanan->links() }}</div>
</div>

@endsection
