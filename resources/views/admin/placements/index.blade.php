@extends('layouts.admin')
@section('title', 'Penyaluran')
@section('content')

@include('components.page-header', [
    'crumb' => 'Admin / Penyaluran',
    'title' => 'Penyaluran Pekerja',
    'subtitle' => 'Pekerja yang tersalurkan setelah hasil interview cocok. Selama aktif, pekerja tidak tampil di katalog klien.',
])

{{-- Tab status --}}
<div class="mb-5 flex flex-wrap gap-2" role="tablist" aria-label="Filter status penyaluran">
    @foreach (['semua' => 'Semua', 'aktif' => 'Aktif', 'berakhir' => 'Berakhir'] as $kode => $label)
        @php $n = $kode === 'semua' ? $jumlah->sum() : ($jumlah[$kode] ?? 0); @endphp
        <a href="{{ route('admin.placements', array_filter(['status' => $kode, 'cari' => request('cari')])) }}"
           class="pill {{ $status === $kode ? 'pill-active' : '' }}" role="tab" aria-selected="{{ $status === $kode ? 'true' : 'false' }}">
            {{ $label }} <span class="rounded-full px-2 py-0.5 text-xs {{ $status === $kode ? 'bg-white/20' : 'bg-canvas' }}">{{ $n }}</span>
        </a>
    @endforeach
</div>

<div class="card overflow-hidden">
    <form method="GET" class="flex flex-col gap-3 border-b border-line p-4 sm:flex-row sm:p-5" role="search">
        <input type="hidden" name="status" value="{{ $status }}">
        <label class="relative flex-1">
            <span class="sr-only">Cari nama pekerja atau klien</span>
            <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-muted" />
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama pekerja atau klien..." class="input pl-11">
        </label>
        <button type="submit" class="btn-primary"><x-icon name="search" class="h-5 w-5" /> Cari</button>
    </form>

    @if ($penyaluran->isEmpty())
        <x-empty-state illustration="kosong" title="Belum ada data penyaluran." message="Penyaluran terbentuk saat admin mencatat hasil interview &quot;Cocok&quot; pada detail pesanan." />
    @else
        <div class="table-wrap">
            <table class="table-base">
                <thead><tr><th>Pekerja</th><th>Kategori</th><th>Klien</th><th>Mulai kerja</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @foreach ($penyaluran as $s)
                        @php $p = $s->profilPekerja; @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-avatar :name="$p->pengguna->nama" :src="$p->pengguna->urlFotoProfil()" size="md" />
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.workers.show', $p) }}" class="font-semibold text-ink hover:text-primary hover:underline">{{ $p->pengguna->nama }}</a>
                                        <a href="{{ route('admin.orders.show', $s->pemesanan) }}" class="block text-xs text-muted hover:underline">{{ $s->pemesanan->kode() }}</a>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $p->labelKategori() }}</td>
                            <td>{{ $s->klien->nama }}</td>
                            <td class="text-muted">
                                {{ $s->tanggal_mulai->locale('id')->translatedFormat('d M Y') }}
                                @if ($s->tanggal_berakhir)<span class="block text-xs">s.d. {{ $s->tanggal_berakhir->locale('id')->translatedFormat('d M Y') }}</span>@endif
                            </td>
                            <td><x-badge :color="$s->isAktif() ? 'blue' : 'gray'">{{ $s->labelStatus() }}</x-badge></td>
                            <td class="text-right">
                                @if ($s->isAktif())
                                    <button type="button" class="btn-secondary btn-sm" data-modal-buka="modal-akhiri"
                                            data-modal-aksi="{{ route('admin.placements.end', $s) }}" data-modal-id="{{ $s->id_penyaluran }}"
                                            data-modal-nama="{{ $p->pengguna->nama }}" data-modal-min="{{ $s->tanggal_mulai->format('Y-m-d') }}">
                                        <x-icon name="x-circle" class="h-4 w-4" /> Akhiri Penyaluran
                                    </button>
                                @else
                                    <span class="text-xs text-muted" title="{{ $s->catatan }}">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@if ($penyaluran->hasPages())
    <div class="mt-6">{{ $penyaluran->links() }}</div>
@endif

{{-- Modal "Akhiri Penyaluran" (satu modal untuk semua baris; aksi diisi oleh JavaScript) --}}
@php
    $idLama = old('id_penyaluran');
    $aksiLama = $idLama ? route('admin.placements.end', $idLama) : '#';
@endphp
<div id="modal-akhiri" class="modal-tutup fixed inset-0 z-[80] hidden items-end justify-center p-4 sm:items-center print:hidden" role="dialog" aria-modal="true" aria-labelledby="modal-akhiri-judul"
     data-modal-form @if ($idLama && $errors->hasAny(['tanggal_berakhir', 'catatan_berakhir'])) data-modal-otomatis @endif>
    <div class="modal-latar absolute inset-0 bg-ink/50 backdrop-blur-sm" data-modal-tutup></div>
    <div class="modal-kotak relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-lift sm:p-7">
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sun-soft text-sun-dark"><x-icon name="calendar" class="h-6 w-6" /></span>
        <h2 id="modal-akhiri-judul" class="mt-4 text-xl font-extrabold text-ink">Akhiri Penyaluran</h2>
        <p class="mt-1 text-[15px] text-muted">Masa kerja <strong class="text-ink" data-modal-isi-nama>{{ old('nama_pekerja', 'pekerja') }}</strong> akan diakhiri dan pekerja otomatis tampil kembali di katalog.</p>
        <form method="POST" action="{{ $aksiLama }}" class="mt-5 space-y-4" novalidate>
            @csrf
            @method('PATCH')
            <input type="hidden" name="id_penyaluran" value="{{ $idLama }}" data-modal-isi-id>
            <input type="hidden" name="nama_pekerja" value="{{ old('nama_pekerja') }}" data-modal-isi-nama-input>
            <x-field name="tanggal_berakhir" type="date" label="Tanggal berakhir" required :value="now(\App\Models\JadwalInterview::ZONA)->toDateString()"
                     :max="now(\App\Models\JadwalInterview::ZONA)->toDateString()" data-modal-isi-min />
            <x-field name="catatan_berakhir" type="textarea" label="Catatan (opsional)" rows="3" maxlength="500" placeholder="Contoh: Kontrak selesai / pekerja berhenti atas permintaan sendiri." />
            <div class="grid grid-cols-2 gap-3 pt-1">
                <button type="button" class="btn-secondary" data-modal-tutup>Batal</button>
                <button type="submit" class="btn-primary"><x-icon name="check" class="h-5 w-5" /> Akhiri Penyaluran</button>
            </div>
        </form>
    </div>
</div>

@endsection
