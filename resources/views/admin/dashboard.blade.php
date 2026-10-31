@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')

<x-banner-sambutan :eyebrow="'Admin · ' . now()->translatedFormat('l, d F Y')" :title="'Selamat datang, ' . auth()->user()->nama"
                   text="Ringkasan administrasi penyaluran pekerja: verifikasi, pemesanan, dan laporan dalam satu tempat.">
    <a href="{{ route('admin.verification') }}" class="btn-sun"><x-icon name="shield-check" class="h-5 w-5" /> Periksa Verifikasi</a>
    <a href="{{ route('admin.orders') }}" class="btn-outline-light">Kelola Pemesanan</a>
</x-banner-sambutan>

{{-- Ringkasan --}}
<div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
    <x-stat icon="users" label="Total pekerja" :value="$stats['total_workers']" tone="brand" :href="route('admin.workers')" :note="$stats['verified_workers'] . ' tampil di katalog'" />
    <x-stat icon="shield-check" label="Menunggu verifikasi" :value="$stats['pending_verify']" tone="amber" :href="route('admin.verification')" note="Perlu ditinjau" />
    <x-stat icon="clipboard" label="Pemesanan masuk" :value="$stats['incoming_orders']" tone="blue" :href="route('admin.orders')" note="Menunggu / diproses" />
    <x-stat icon="user" label="Total klien" :value="$stats['total_clients']" tone="violet" :href="route('admin.reports')" :note="$stats['placements_month'] . ' penyaluran bulan ini'" />
</div>

<div class="grid gap-6 xl:grid-cols-3">
    {{-- Menunggu verifikasi --}}
    <div class="card reveal flex flex-col">
        <div class="card-head">
            <h2 class="card-title">Verifikasi pekerja</h2>
            <a href="{{ route('admin.verification') }}" class="text-sm font-bold text-primary hover:underline">Lihat semua</a>
        </div>
        <div class="flex-1">
            @forelse ($pendingVerifications as $p)
                <a href="{{ route('admin.verification.show', $p) }}" class="flex items-center gap-3 border-b border-line px-5 py-3.5 transition-colors last:border-0 hover:bg-canvas">
                    <x-avatar :name="$p->pengguna->nama" size="sm" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-ink">{{ $p->pengguna->nama }}</p>
                        <p class="text-xs text-muted">{{ $p->labelKategori() }}</p>
                    </div>
                    <span class="btn-primary btn-sm">Periksa</span>
                </a>
            @empty
                <x-empty-state illustration="kosong" title="Tidak ada antrean verifikasi" message="Semua data pekerja sudah diperiksa." compact />
            @endforelse
        </div>
        <div class="border-t border-line px-5 py-4">
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-muted">Pekerja per kategori</p>
            @php $total = max(1, $perKategori->sum()); @endphp
            @foreach (\App\Models\ProfilPekerja::KATEGORI_SINGKAT as $kode => $label)
                @php $n = $perKategori[$kode] ?? 0; @endphp
                <div class="mb-2.5 last:mb-0">
                    <div class="mb-1 flex justify-between text-sm"><span class="text-ink">{{ $label }}</span><span class="font-semibold">{{ $n }}</span></div>
                    <div class="h-2 overflow-hidden rounded-full bg-canvas"><div class="skor-bar h-2 rounded-full bg-primary-accent" style="--skor: {{ round($n / $total * 100) }}%"></div></div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Pemesanan terbaru --}}
    <div class="card reveal xl:col-span-2">
        <div class="card-head">
            <h2 class="card-title">Pemesanan terbaru</h2>
            <a href="{{ route('admin.orders') }}" class="text-sm font-bold text-primary hover:underline">Kelola pemesanan</a>
        </div>
        @if ($recentOrders->isEmpty())
            <x-empty-state illustration="pesanan" title="Belum ada pemesanan" compact />
        @else
            <div class="table-wrap">
                <table class="table-base">
                    <thead><tr><th>Pesanan</th><th>Klien</th><th>Pekerja</th><th>Tanggal</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach ($recentOrders as $o)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $o) }}" class="font-semibold text-primary hover:underline">{{ $o->kode() }}</a></td>
                                <td>{{ $o->klien->nama }}</td>
                                <td>{{ $o->profilPekerja->pengguna->nama }}<span class="block text-xs text-muted">{{ $o->profilPekerja->labelKategori() }}</span></td>
                                <td class="text-muted">{{ $o->tanggal_pemesanan->format('d/m/Y') }}</td>
                                <td><x-badge :status="$o->status_pemesanan">{{ $o->labelStatus() }}</x-badge></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
