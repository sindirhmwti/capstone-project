@extends('layouts.admin')
@section('title', 'Verifikasi Pekerja')
@section('content')

@include('components.page-header', ['crumb' => 'Admin / Verifikasi Pekerja', 'title' => 'Verifikasi Pekerja'])

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">
        <div>
            <h3 class="font-semibold text-[#172B23]">Verifikasi Data Pekerja</h3>
            <p class="text-xs text-[#52645B]">Baby Sitter dan Perawat Lansia memerlukan sertifikat pelatihan. ART tidak memerlukan sertifikat profesi khusus (cukup KTP).</p>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <select name="kategori" onchange="this.form.submit()" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white">
                <option value="">Semua Kategori</option>
                @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                    <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Tab status --}}
    <div class="flex flex-wrap gap-2 mb-5">
        @foreach (['menunggu' => 'Menunggu', 'terverifikasi' => 'Terverifikasi', 'belum_lengkap' => 'Belum Lengkap', 'ditolak' => 'Ditolak', 'semua' => 'Semua'] as $kode => $label)
            <a href="{{ route('admin.verification', array_filter(['status' => $kode, 'kategori' => request('kategori')])) }}"
               class="px-3 py-1.5 rounded-lg text-sm border {{ $status === $kode ? 'bg-[#E4F2E8] border-[#176B4D]/30 text-[#176B4D] font-semibold' : 'border-gray-200 text-[#52645B] hover:bg-gray-50' }}">
                {{ $label }}
                <span class="ml-1 text-xs">{{ $kode === 'semua' ? $jumlah->sum() : ($jumlah[$kode] ?? 0) }}</span>
            </a>
        @endforeach
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-[#52645B] bg-[#F5F7F5]">
                    <th class="px-3 py-2.5 font-medium rounded-l-lg">Nama pekerja</th>
                    <th class="px-3 py-2.5 font-medium">Kategori pekerjaan</th>
                    <th class="px-3 py-2.5 font-medium">Tanggal pengajuan</th>
                    <th class="px-3 py-2.5 font-medium">Status verifikasi</th>
                    <th class="px-3 py-2.5 font-medium">Dokumen pendukung</th>
                    <th class="px-3 py-2.5 font-medium rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pekerja as $p)
                    @php $tanggal = $p->dokumen->max('tanggal_unggah'); @endphp
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-3 py-3">
                            <p class="font-medium text-[#172B23]">{{ $p->pengguna->nama }}</p>
                            <p class="text-xs text-[#52645B]">Akun {{ $p->pengguna->status_akun }} · {{ $p->cekKelengkapanProfil() ? 'Profil lengkap' : 'Profil belum lengkap' }}</p>
                        </td>
                        <td class="px-3 py-3">{{ $p->labelKategori() }}</td>
                        <td class="px-3 py-3 text-[#52645B]">{{ $tanggal ? $tanggal->format('d/m/Y H:i') : '-' }}</td>
                        <td class="px-3 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $p->warnaStatus() }}">{{ $p->labelStatus() }}</span></td>
                        <td class="px-3 py-3">
                            @foreach ($p->dokumenWajib() as $jenis)
                                @php $dok = $p->dokumenTerbaru($jenis); @endphp
                                @if ($dok)
                                    <a href="{{ $dok->url() }}" target="_blank" class="block text-[#176B4D] hover:underline text-xs">{{ $dok->labelJenis() }} →</a>
                                @else
                                    <span class="block text-xs text-red-600">{{ \App\Models\DokumenPekerja::JENIS[$jenis] }}: belum ada</span>
                                @endif
                            @endforeach
                        </td>
                        <td class="px-3 py-3">
                            <a href="{{ route('admin.verification.show', $p) }}" class="text-[#176B4D] font-medium hover:underline">
                                {{ $p->status_verifikasi === 'menunggu' ? 'Periksa' : 'Lihat Detail' }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-3 py-8 text-center text-[#52645B]">Tidak ada data pekerja dengan status ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $pekerja->links() }}</div>
</div>

@endsection
