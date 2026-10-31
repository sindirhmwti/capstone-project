{{-- Bar pencarian katalog: kata kunci, kategori, lokasi (+ status untuk admin) --}}
@php $sel = 'px-3 py-2.5 border border-gray-200 rounded-xl bg-white text-sm text-[#172B23] focus:outline-none focus:ring-2 focus:ring-[#176B4D]'; @endphp
<form method="GET" action="{{ $action }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 flex flex-col lg:flex-row gap-3 mb-6">
    <div class="flex items-center gap-2 flex-1 px-3 border border-gray-200 rounded-xl">
        <svg class="w-4 h-4 text-[#52645B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau keahlian pekerja..." class="flex-1 py-2.5 text-sm outline-none bg-transparent">
    </div>
    <select name="kategori" class="{{ $sel }}">
        <option value="">Semua Kategori</option>
        @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
            <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="lokasi" class="{{ $sel }}">
        <option value="">Semua lokasi</option>
        @foreach ($daftarLokasi as $lokasi)
            <option value="{{ $lokasi }}" @selected(request('lokasi') === $lokasi)>{{ $lokasi }}</option>
        @endforeach
    </select>
    @if (! empty($filterStatus))
        <select name="status" class="{{ $sel }}">
            <option value="">Semua status</option>
            @foreach (\App\Models\ProfilPekerja::STATUS_VERIFIKASI as $kode => $label)
                <option value="{{ $kode }}" @selected(request('status') === $kode)>{{ $label }}</option>
            @endforeach
        </select>
    @endif
    {{ $slot ?? '' }}
    <button type="submit" class="px-5 py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38] flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
        Cari Pekerja
    </button>
</form>
