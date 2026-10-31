@extends('layouts.client')
@section('title', 'Ajukan Pemesanan')
@section('content')

@include('components.page-header', [
    'crumb' => 'Klien / Katalog Pekerja / Ajukan Pemesanan',
    'title' => 'Ajukan Pemesanan',
    'subtitle' => 'Isi informasi pemesanan. Persetujuan akhir dilakukan oleh admin yayasan.',
])

<div class="grid gap-6 xl:grid-cols-3">
    <form method="POST" action="{{ route('client.orders.store', $profil) }}" class="card card-body space-y-7 xl:col-span-2" novalidate>
        @csrf
        <div class="grid gap-5 md:grid-cols-2">
            <x-field name="tanggal_mulai" type="date" label="Jadwal mulai kerja" required :min="now()->toDateString()" />
            <x-field name="lokasi_kerja" label="Lokasi pekerjaan" icon="map-pin" required placeholder="Contoh: Jakarta Utara" />
        </div>
        <x-field name="catatan_klien" type="textarea" rows="4" label="Keterangan kebutuhan" required
                 placeholder="Contoh: saya membutuhkan ART yang bisa membantu mengurus rumah 3 lantai, ada hewan peliharaan kucing..." />

        <fieldset>
            <legend class="label">Kriteria khusus yang dibutuhkan</legend>
            <p class="text-helper mb-3">Terisi otomatis dari filter pencarian terakhir Anda. Tanda <x-icon name="alert-circle" class="inline h-3.5 w-3.5 text-red-500" /> berarti pekerja ini tidak memenuhi kriteria tersebut.</p>
            <div class="grid gap-2 sm:grid-cols-2">
                @foreach ($kelompokKriteria->flatten() as $k)
                    @php $dipilih = in_array($k->id_kriteria, old('kriteria', $idKriteria)); $ya = ($nilaiKriteria[$k->id_kriteria] ?? null) === 'ya'; @endphp
                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 border-line px-3.5 py-3 text-[15px] font-medium transition-colors hover:border-primary/40 has-[:checked]:border-primary has-[:checked]:bg-primary-soft">
                        <input type="checkbox" name="kriteria[]" value="{{ $k->id_kriteria }}" class="h-5 w-5 rounded accent-primary" @checked($dipilih)>
                        <span class="flex-1">{{ $k->nama_kriteria }}</span>
                        @unless ($ya) <x-icon name="alert-circle" class="h-4 w-4 text-red-500" title="Pekerja tidak memenuhi kriteria ini" /> @endunless
                    </label>
                @endforeach
            </div>
            @error('kriteria') <p class="field-error">{{ $message }}</p> @enderror
        </fieldset>

        <div class="flex flex-col-reverse gap-3 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <a href="{{ route('client.workers.show', $profil) }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary btn-lg"><x-icon name="send" class="h-5 w-5" /> Kirim Pengajuan Pemesanan</button>
        </div>
    </form>

    <aside class="space-y-5 xl:sticky xl:top-28 xl:h-fit">
        <div class="card bg-mint-soft p-5">
            <p class="eyebrow">Pekerja dipilih</p>
            <div class="mt-3 flex items-center gap-3">
                <x-avatar :name="$profil->pengguna->nama" size="lg" />
                <div class="min-w-0">
                    <p class="truncate font-bold text-ink">{{ $profil->pengguna->nama }}</p>
                    <p class="text-sm text-muted">{{ $profil->labelKategori() }} · {{ $profil->alamat }}</p>
                    <p class="text-sm text-muted">{{ $profil->lama_pengalaman }} tahun pengalaman</p>
                </div>
            </div>
        </div>
        <div class="card p-5">
            <h2 class="card-title">Alur pemesanan</h2>
            <ol class="mt-4 space-y-3 text-[15px]">
                @foreach (['Pengajuan diterima dengan status Menunggu.', 'Admin memeriksa dan memproses pesanan.', 'Anda mendapat notifikasi saat pesanan disetujui atau ditolak.', 'Pesanan dapat dibatalkan selama berstatus Menunggu atau Diproses.'] as $i => $teks)
                    <li class="flex gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary text-xs font-extrabold text-white">{{ $i + 1 }}</span>
                        <span class="text-muted">{{ $teks }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </aside>
</div>

@endsection
