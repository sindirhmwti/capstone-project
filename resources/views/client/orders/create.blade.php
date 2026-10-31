@extends('layouts.client')
@section('title', 'Ajukan Pemesanan')
@section('content')

@php $input = 'w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#176B4D]'; @endphp

@include('components.page-header', [
    'crumb' => 'Klien / Katalog Pekerja / Ajukan Pemesanan',
    'title' => 'Ajukan Pemesanan',
    'subtitle' => 'Isi informasi pemesanan. Persetujuan akhir dilakukan oleh admin yayasan.',
])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <form method="POST" action="{{ route('client.orders.store', $profil) }}" class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5" novalidate>
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium mb-1.5">Jadwal mulai kerja <span class="text-red-500">*</span></label>
                <input id="tanggal_mulai" type="date" name="tanggal_mulai" min="{{ now()->toDateString() }}" value="{{ old('tanggal_mulai') }}" class="{{ $input }}">
                @error('tanggal_mulai') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="lokasi_kerja" class="block text-sm font-medium mb-1.5">Lokasi pekerjaan <span class="text-red-500">*</span></label>
                <input id="lokasi_kerja" name="lokasi_kerja" value="{{ old('lokasi_kerja') }}" placeholder="Contoh: Jakarta Utara" class="{{ $input }}">
                @error('lokasi_kerja') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="catatan_klien" class="block text-sm font-medium mb-1.5">Keterangan kebutuhan <span class="text-red-500">*</span></label>
            <textarea id="catatan_klien" name="catatan_klien" rows="4" placeholder="Contoh: saya membutuhkan ART yang bisa membantu mengurus rumah 3 lantai, ada hewan peliharaan kucing..." class="{{ $input }}">{{ old('catatan_klien') }}</textarea>
            @error('catatan_klien') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <p class="text-sm font-medium mb-1">Kriteria khusus yang dibutuhkan</p>
            <p class="text-xs text-[#52645B] mb-3">Terisi otomatis dari filter pencarian terakhir Anda. Tanda <span class="text-red-600 font-semibold">!</span> berarti pekerja ini tidak memenuhi kriteria tersebut.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-1">
                @foreach ($kelompokKriteria->flatten() as $k)
                    @php $dipilih = in_array($k->id_kriteria, old('kriteria', $idKriteria)); $ya = ($nilaiKriteria[$k->id_kriteria] ?? null) === 'ya'; @endphp
                    <label class="flex items-center gap-2 text-sm py-1 cursor-pointer">
                        <input type="checkbox" name="kriteria[]" value="{{ $k->id_kriteria }}" class="accent-[#176B4D]" @checked($dipilih)>
                        <span>{{ $k->nama_kriteria }}</span>
                        @unless ($ya) <span class="text-red-600 font-semibold" title="Pekerja tidak memenuhi kriteria ini">!</span> @endunless
                    </label>
                @endforeach
            </div>
            @error('kriteria') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('client.workers.show', $profil) }}" class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-[#52645B] hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Kirim Pengajuan Pemesanan</button>
        </div>
    </form>

    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs text-[#52645B] uppercase tracking-wider">Pekerja dipilih</p>
            <div class="flex items-center gap-3 mt-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#176B4D] to-[#20A66A] flex items-center justify-center text-white font-bold">{{ $profil->pengguna->inisial() }}</div>
                <div>
                    <p class="font-semibold">{{ $profil->pengguna->nama }}</p>
                    <p class="text-xs text-[#52645B]">{{ $profil->labelKategori() }} · {{ $profil->alamat }} · {{ $profil->lama_pengalaman }} th</p>
                </div>
            </div>
        </div>
        <div class="bg-[#E4F2E8] rounded-2xl p-5 text-xs text-[#104C38] space-y-1">
            <p class="font-semibold text-sm mb-1">Alur pemesanan</p>
            <p>1. Pengajuan diterima dengan status <strong>Menunggu</strong>.</p>
            <p>2. Admin memeriksa dan memproses pesanan.</p>
            <p>3. Anda mendapat notifikasi saat pesanan disetujui / ditolak.</p>
            <p>4. Pesanan dapat dibatalkan selama berstatus Menunggu atau Diproses.</p>
        </div>
    </div>
</div>

@endsection
