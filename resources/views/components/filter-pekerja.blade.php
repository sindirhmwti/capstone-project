{{-- Bar pencarian katalog: kata kunci, kategori, lokasi (+ status untuk admin) --}}
<form method="GET" action="{{ $action }}" class="card mb-6 flex flex-col gap-3 p-3 lg:flex-row lg:items-center" role="search">
    <label class="relative flex-1">
        <span class="sr-only">Cari nama atau keahlian</span>
        <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-muted" />
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau keahlian pekerja..." class="input pl-11">
    </label>
    <select name="kategori" class="input lg:w-48" aria-label="Kategori">
        <option value="">Semua kategori</option>
        @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
            <option value="{{ $kode }}" @selected(request('kategori') === $kode)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="lokasi" class="input lg:w-48" aria-label="Lokasi">
        <option value="">Semua lokasi</option>
        @foreach ($daftarLokasi as $lokasi)
            <option value="{{ $lokasi }}" @selected(request('lokasi') === $lokasi)>{{ $lokasi }}</option>
        @endforeach
    </select>
    @if (! empty($filterStatus))
        <select name="status" class="input lg:w-52" aria-label="Status verifikasi">
            <option value="">Semua status</option>
            @foreach (\App\Models\ProfilPekerja::STATUS_VERIFIKASI as $kode => $label)
                <option value="{{ $kode }}" @selected(request('status') === $kode)>{{ $label }}</option>
            @endforeach
        </select>
    @endif
    <button type="submit" class="btn-primary"><x-icon name="search" class="h-4 w-4" /> Cari</button>
</form>
