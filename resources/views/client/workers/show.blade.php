@extends('layouts.client')
@section('title', 'Detail Profil Pekerja')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Katalog Pekerja / Detail',
    'title' => 'Detail Profil Pekerja',
    'aksi' => '<a href="' . (url()->previous() !== url()->current() ? url()->previous() : route('client.workers')) . '" class="px-4 py-2 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-white">← Kembali</a>',
])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        @include('components.detail-pekerja', ['tampilKontak' => false, 'tampilDokumen' => false, 'sorotKriteria' => $idKriteria])
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            @if ($skor)
                <p class="text-sm text-[#52645B]">Skor kecocokan</p>
                <p class="text-4xl font-bold text-[#176B4D] mt-1">{{ number_format($skor['skor'], 1, ',', '.') }}%</p>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden mt-2">
                    <div class="h-2 bg-[#176B4D] rounded-full" style="width: {{ $skor['skor'] }}%"></div>
                </div>
                <p class="text-xs text-[#52645B] mt-2">Memenuhi {{ $skor['cocok'] }} dari {{ $skor['total'] }} kriteria yang Anda cari.</p>
            @else
                <p class="text-sm text-[#52645B]">Pilih kriteria khusus di katalog untuk melihat skor kecocokan pekerja ini.</p>
            @endif

            <div class="grid grid-cols-2 gap-3 mt-5 text-center">
                <div class="bg-[#F5F7F5] rounded-xl p-3">
                    <p class="text-lg font-bold text-[#172B23]">{{ $profil->lama_pengalaman }} th</p>
                    <p class="text-xs text-[#52645B]">Pengalaman</p>
                </div>
                <div class="bg-[#F5F7F5] rounded-xl p-3">
                    <p class="text-lg font-bold text-[#172B23]">{{ $jumlahPenyaluran }}</p>
                    <p class="text-xs text-[#52645B]">Penyaluran</p>
                </div>
            </div>

            <div class="space-y-2 mt-5">
                @if (Route::has('client.orders.create'))
                    <a href="{{ route('client.orders.create', $profil) }}" class="block text-center py-3 bg-[#176B4D] text-white font-semibold rounded-xl hover:bg-[#104C38]">Ajukan Pemesanan</a>
                @endif
                <form method="POST" action="{{ $favorit ? route('client.favorites.destroy', $profil) : route('client.favorites.store', $profil) }}">
                    @csrf
                    @if ($favorit) @method('DELETE') @endif
                    <button class="w-full py-3 border rounded-xl text-sm font-semibold {{ $favorit ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-[#176B4D] text-[#176B4D] hover:bg-[#E4F2E8]' }}">
                        {{ $favorit ? '♥ Hapus dari Favorit' : '♡ Simpan ke Favorit' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-[#E4F2E8] rounded-2xl p-5 text-sm text-[#104C38]">
            <p class="font-semibold mb-1">Data terverifikasi admin</p>
            <p class="text-xs">Dokumen identitas{{ $profil->wajibSertifikat() ? ' dan sertifikat pelatihan' : '' }} pekerja telah diperiksa oleh admin PT Shaka Abadi Nusantara. Kontak pekerja diberikan oleh admin setelah pesanan disetujui.</p>
        </div>
    </div>
</div>

@endsection
