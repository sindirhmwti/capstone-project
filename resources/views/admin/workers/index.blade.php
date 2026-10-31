@extends('layouts.admin')
@section('title', 'Kelola Akun Pekerja')
@section('content')

@include('components.page-header', ['crumb' => 'Admin / Kelola Akun Pekerja', 'title' => 'Kelola Akun Pekerja'])

@php $sel = 'px-3 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#176B4D]'; @endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
        <div>
            <h3 class="font-semibold text-[#172B23]">Daftar Akun Pekerja</h3>
            <p class="text-xs text-[#52645B]">Kelola data dan status akun calon pekerja.</p>
        </div>
        <form method="GET" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / email..." class="{{ $sel }}">
            <select name="kategori" class="{{ $sel }}">
                <option value="">Semua kategori</option>
                @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                    <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status_akun" class="{{ $sel }}">
                <option value="">Semua status akun</option>
                <option value="aktif" @selected(request('status_akun') === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(request('status_akun') === 'nonaktif')>Nonaktif</option>
            </select>
            <button class="px-4 py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Terapkan</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-[#52645B] bg-[#F5F7F5]">
                    <th class="px-3 py-2.5 font-medium rounded-l-lg">Nama pekerja</th>
                    <th class="px-3 py-2.5 font-medium">Identitas akun</th>
                    <th class="px-3 py-2.5 font-medium">Kategori</th>
                    <th class="px-3 py-2.5 font-medium">Lokasi</th>
                    <th class="px-3 py-2.5 font-medium">Verifikasi</th>
                    <th class="px-3 py-2.5 font-medium">Status</th>
                    <th class="px-3 py-2.5 font-medium rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pekerja as $p)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-3 py-3">
                            <p class="font-medium text-[#172B23]">{{ $p->pengguna->nama }}</p>
                            <p class="text-xs text-[#52645B]">PK-{{ str_pad($p->id_pekerja, 3, '0', STR_PAD_LEFT) }}</p>
                        </td>
                        <td class="px-3 py-3 text-[#52645B]">{{ $p->pengguna->email }}<br><span class="text-xs">{{ $p->pengguna->no_telepon }}</span></td>
                        <td class="px-3 py-3">{{ $p->labelKategori() }}</td>
                        <td class="px-3 py-3">{{ $p->alamat ?: '-' }}</td>
                        <td class="px-3 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $p->warnaStatus() }}">{{ $p->labelStatus() }}</span></td>
                        <td class="px-3 py-3">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $p->pengguna->isAktif() ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">{{ ucfirst($p->pengguna->status_akun) }}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <a href="{{ route('admin.workers.show', $p) }}" class="text-[#176B4D] font-medium hover:underline mr-3">Detail</a>
                            <form method="POST" action="{{ route('admin.workers.status', $p) }}" class="inline"
                                  onsubmit="return confirm('{{ $p->pengguna->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ addslashes($p->pengguna->nama) }}?')">
                                @csrf
                                @method('PATCH')
                                <button class="font-medium hover:underline {{ $p->pengguna->isAktif() ? 'text-red-600' : 'text-[#176B4D]' }}">
                                    {{ $p->pengguna->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-3 py-8 text-center text-[#52645B]">Akun pekerja tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $pekerja->links() }}</div>
</div>

@endsection
