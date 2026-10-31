@extends('layouts.admin')
@section('title', 'Detail Pemesanan')
@section('content')

@php $p = $pemesanan->profilPekerja; @endphp

@include('components.page-header', [
    'crumb' => 'Admin / Kelola Pemesanan / Detail',
    'title' => 'Pesanan ' . $pemesanan->kode(),
    'subtitle' => 'Diajukan ' . $pemesanan->tanggal_pemesanan->translatedFormat('d F Y, H:i') . ' oleh ' . $pemesanan->klien->nama,
    'aksi' => '<a href="' . route('admin.orders') . '" class="btn-secondary">Kembali</a>',
])

<div class="grid gap-6 xl:grid-cols-3">
    <div class="space-y-6 xl:col-span-2">
        <div class="card card-body">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="card-title">Detail permintaan</h2>
                <x-badge :status="$pemesanan->status_pemesanan">{{ $pemesanan->labelStatus() }}</x-badge>
            </div>
            <dl class="divide-y divide-line text-[15px]">
                <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">Klien</dt><dd class="font-medium text-ink">{{ $pemesanan->klien->nama }}<span class="block font-normal text-muted">{{ $pemesanan->klien->email }} · {{ $pemesanan->klien->no_telepon }}</span></dd></div>
                <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">Jadwal mulai</dt><dd class="font-medium">{{ $pemesanan->tanggal_mulai?->translatedFormat('d F Y') ?? '-' }}</dd></div>
                <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">Lokasi pekerjaan</dt><dd class="font-medium">{{ $pemesanan->lokasi_kerja ?: '-' }}</dd></div>
                <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">Keterangan klien</dt><dd class="whitespace-pre-line">{{ $pemesanan->catatan_klien ?: '-' }}</dd></div>
                @if ($pemesanan->status_pemesanan === 'dibatalkan')
                    <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">Dibatalkan oleh</dt><dd>{{ $pemesanan->pembatal?->nama ?? '-' }}, {{ $pemesanan->tanggal_pembatalan?->translatedFormat('d F Y H:i') }}</dd></div>
                    <div class="grid gap-1 py-3 sm:grid-cols-[200px_1fr]"><dt class="text-muted">Alasan pembatalan</dt><dd>{{ $pemesanan->alasan_pembatalan }}</dd></div>
                @endif
            </dl>

            <h3 class="card-title mb-3 mt-6">Kesesuaian kriteria yang diminta</h3>
            @if ($pemesanan->kriteria->isEmpty())
                <p class="text-sm text-muted">Klien tidak memilih kriteria khusus.</p>
            @else
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach ($pemesanan->kriteria as $k)
                        @php $ya = ($nilaiKriteria[$k->id_kriteria] ?? null) === 'ya'; @endphp
                        <div class="flex items-center justify-between gap-2 rounded-2xl border-2 px-3.5 py-3 text-[15px] {{ $ya ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                            <span class="flex items-center gap-2"><x-icon :name="$ya ? 'check-circle' : 'x-circle'" class="h-4 w-4 {{ $ya ? 'text-green-600' : 'text-red-500' }}" /> {{ $k->nama_kriteria }}</span>
                            <span class="text-xs font-semibold {{ $ya ? 'text-green-700' : 'text-red-600' }}">{{ $ya ? 'Sesuai' : 'Tidak sesuai' }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @include('components.riwayat-status')
    </div>

    <aside class="space-y-6 xl:sticky xl:top-28 xl:h-fit">
        <div class="card bg-mint-soft p-5">
            <p class="eyebrow">Calon pekerja terkait</p>
            <div class="mt-3 flex items-center gap-3">
                <x-avatar :name="$p->pengguna->nama" size="lg" />
                <div class="min-w-0">
                    <p class="truncate font-bold text-ink">{{ $p->pengguna->nama }}</p>
                    <p class="text-sm text-muted">{{ $p->labelKategori() }} · {{ $p->alamat }}</p>
                    <p class="text-sm text-muted">{{ $p->pengguna->no_telepon }}</p>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
                <x-badge :status="$p->status_verifikasi">{{ $p->labelStatus() }}</x-badge>
                <x-badge :status="$p->pengguna->status_akun">Akun {{ $p->pengguna->status_akun }}</x-badge>
            </div>
            <a href="{{ route('admin.workers.show', $p) }}" class="btn-secondary btn-sm mt-4 w-full">Lihat detail pekerja</a>
        </div>

        @if ($pemesanan->statusBerikutnyaAdmin())
            <form method="POST" action="{{ route('admin.orders.status', $pemesanan) }}" class="card card-body space-y-4 ring-2 ring-primary/15" data-confirm="Simpan perubahan status pesanan?">
                @csrf
                @method('PATCH')
                <h2 class="card-title flex items-center gap-2"><x-icon name="pencil" class="text-primary" /> Tindak lanjut pesanan</h2>
                <x-field name="status_pemesanan" type="select" label="Ubah status menjadi" required
                         :options="collect($pemesanan->statusBerikutnyaAdmin())->mapWithKeys(fn ($s) => [$s => \App\Models\Pemesanan::STATUS[$s]])->all()" />
                <x-field name="catatan" type="textarea" label="Catatan / alasan" placeholder="Wajib diisi bila ditolak atau dibatalkan" />
                <button type="submit" class="btn-primary btn-lg w-full"><x-icon name="check" class="h-5 w-5" /> Simpan Status</button>
                <p class="text-helper">Klien akan menerima notifikasi. Pekerja juga diberi tahu saat pesanan disetujui, selesai, atau dibatalkan.</p>
            </form>
        @else
            <div class="flex gap-3 rounded-3xl bg-canvas p-5 text-[15px] text-muted">
                <x-icon name="lock" class="h-5 w-5" />
                <p>Pesanan sudah berstatus final (<strong class="text-ink">{{ $pemesanan->labelStatus() }}</strong>) dan tidak dapat diubah.</p>
            </div>
        @endif
    </aside>
</div>

@endsection
