@extends('layouts.admin')
@section('title', 'Laporan Penyaluran')
@section('content')

@include('components.page-header', ['crumb' => 'Admin / Laporan Penyaluran', 'title' => 'Laporan Penyaluran'])

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-5">
        <div>
            <h3 class="font-semibold text-[#172B23]">Laporan penyaluran pekerja</h3>
            <p class="text-xs text-[#52645B]">Penyaluran dihitung dari pemesanan berstatus <strong>Disetujui</strong> dan <strong>Selesai</strong>.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <form method="GET" class="flex flex-col sm:flex-row gap-2">
                <label class="flex items-center gap-2 text-sm text-[#52645B]">
                    Periode laporan
                    <input type="month" name="periode" value="{{ $periode }}" class="px-3 py-2 border border-gray-200 rounded-xl text-sm text-[#172B23]">
                </label>
                <select name="kategori" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white">
                    <option value="">Semua kategori</option>
                    @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                        <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-2 border border-[#176B4D] text-[#176B4D] text-sm font-semibold rounded-xl hover:bg-[#E4F2E8]">Tampilkan</button>
                @if ($periode || request('kategori'))
                    <a href="{{ route('admin.reports') }}" class="px-3 py-2 text-sm text-[#52645B] hover:underline self-center">Semua periode</a>
                @endif
            </form>
            <a href="{{ route('admin.reports.print', request()->only('periode', 'kategori')) }}" target="_blank"
               class="px-4 py-2 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38] text-center {{ $penyaluran->isEmpty() ? 'pointer-events-none opacity-50' : '' }}">
                Cetak Laporan
            </a>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-5">
        <div class="bg-[#E4F2E8] rounded-xl p-4">
            <p class="text-2xl font-bold text-[#104C38]">{{ $penyaluran->count() }}</p>
            <p class="text-xs text-[#52645B]">Total penyaluran · {{ $labelPeriode }}</p>
        </div>
        @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
            <div class="bg-[#F5F7F5] rounded-xl p-4">
                <p class="text-2xl font-bold text-[#172B23]">{{ $perKategori[$kode] ?? 0 }}</p>
                <p class="text-xs text-[#52645B]">{{ $label }}</p>
            </div>
        @endforeach
        <div class="bg-[#F5F7F5] rounded-xl p-4">
            <p class="text-2xl font-bold text-[#172B23]">{{ $perStatus['selesai'] ?? 0 }}</p>
            <p class="text-xs text-[#52645B]">Selesai · {{ $perStatus['disetujui'] ?? 0 }} berjalan</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-[#52645B] bg-[#F5F7F5]">
                    <th class="px-3 py-2.5 font-medium rounded-l-lg">Nama pekerja</th>
                    <th class="px-3 py-2.5 font-medium">Jenis pekerjaan</th>
                    <th class="px-3 py-2.5 font-medium">Klien</th>
                    <th class="px-3 py-2.5 font-medium">Lokasi</th>
                    <th class="px-3 py-2.5 font-medium">Status</th>
                    <th class="px-3 py-2.5 font-medium rounded-r-lg">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penyaluran as $o)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-3 py-3"><p class="font-medium">{{ $o->profilPekerja->pengguna->nama }}</p><p class="text-xs text-[#52645B]">{{ $o->kode() }}</p></td>
                        <td class="px-3 py-3">{{ $o->profilPekerja->labelKategori() }}</td>
                        <td class="px-3 py-3">{{ $o->klien->nama }}</td>
                        <td class="px-3 py-3">{{ $o->lokasi_kerja ?: $o->profilPekerja->alamat }}</td>
                        <td class="px-3 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $o->warnaStatus() }}">{{ $o->labelStatus() }}</span></td>
                        <td class="px-3 py-3 text-[#52645B]">{{ $o->tanggal_pemesanan->translatedFormat('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-3 py-10 text-center text-[#52645B]">Data penyaluran tidak tersedia untuk {{ strtolower($labelPeriode) }}.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
