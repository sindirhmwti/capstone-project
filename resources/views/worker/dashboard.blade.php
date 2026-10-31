@extends('layouts.worker')
@section('title', 'Beranda Pekerja')
@section('content')

<x-banner-sambutan :eyebrow="$profil->labelKategori()" :title="'Selamat datang, ' . $pengguna->nama . '!'"
                   text="Lengkapi profil agar keahlian Anda mudah ditemukan klien setelah diverifikasi admin.">
    <a href="{{ route('worker.profile.edit') }}" class="btn-sun"><x-icon name="pencil" class="h-5 w-5" /> Lengkapi Profil</a>
    <a href="{{ route('worker.verification') }}" class="btn-outline-light">Status Verifikasi</a>
</x-banner-sambutan>

{{-- Ringkasan --}}
<div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4">
    <x-stat icon="target" label="Kelengkapan profil" :value="$profileCompletion . '%'" tone="brand" :href="route('worker.profile')" :note="'Dari ' . count($profileSections) . ' langkah'" />
    <x-stat icon="file-text" label="Dokumen diunggah" :value="$stats['documents_uploaded']" tone="blue" :href="route('worker.verification')" :note="'Wajib ' . count($profil->dokumenWajib()) . ' dokumen'" />
    <x-stat icon="bell" label="Notifikasi baru" :value="$stats['notifications']" tone="rose" :href="route('worker.notifications')" note="Belum dibaca" />
    <x-stat icon="hand-heart" label="Penyaluran" :value="$stats['orders']" tone="amber" note="Pesanan disetujui / selesai" />
</div>

<div class="grid gap-6 xl:grid-cols-3">
    {{-- Kartu progres kelengkapan profil --}}
    <div class="card reveal overflow-hidden xl:col-span-2">
        <div class="grid md:grid-cols-[240px_1fr]">
            <div class="flex flex-col items-center justify-center gap-3 bg-mint-soft p-6 text-center">
                @php $keliling = 2 * pi() * 52; @endphp
                <div class="relative h-36 w-36">
                    <svg viewBox="0 0 120 120" class="h-36 w-36 -rotate-90" aria-hidden="true">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#FFFFFF" stroke-width="12"/>
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#246C54" stroke-width="12" stroke-linecap="round"
                                stroke-dasharray="{{ $keliling }}" stroke-dashoffset="{{ $keliling * (1 - $profileCompletion / 100) }}"/>
                    </svg>
                    <span class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-extrabold text-ink">{{ $profileCompletion }}%</span>
                        <span class="text-xs font-semibold text-muted">lengkap</span>
                    </span>
                </div>
                <p class="text-[15px] font-bold text-ink">Kelengkapan profil</p>
                <x-badge :status="$profil->status_verifikasi">{{ $profil->labelStatus() }}</x-badge>
            </div>
            <div class="p-5 sm:p-6">
                <h2 class="card-title">Checklist kelengkapan</h2>
                <p class="mt-1 text-[15px] text-muted">Selesaikan semua langkah agar profil Anda dapat diverifikasi admin.</p>
                <ol class="mt-5 space-y-2.5">
                    @foreach ($profileSections as $i => $section)
                        <li class="flex items-center gap-3.5 rounded-2xl p-3 {{ $section['done'] ? 'bg-green-50' : 'bg-canvas' }}">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-extrabold {{ $section['done'] ? 'bg-green-600 text-white' : 'bg-white text-muted ring-1 ring-line' }}">
                                @if ($section['done']) <x-icon name="check" class="h-5 w-5" stroke="3" /> @else {{ $i + 1 }} @endif
                            </span>
                            <span class="flex-1 text-[15px] font-semibold {{ $section['done'] ? 'text-ink' : 'text-muted' }}">{{ $section['label'] }}</span>
                            @if ($section['done'])
                                <span class="text-sm font-bold text-green-700">Selesai</span>
                            @else
                                <a href="{{ $section['link'] }}" class="btn-primary btn-sm">Lengkapi</a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

    {{-- Notifikasi terbaru --}}
    <div class="card reveal flex flex-col">
        <div class="card-head">
            <h2 class="card-title">Notifikasi terbaru</h2>
            <a href="{{ route('worker.notifications') }}" class="text-sm font-bold text-primary hover:underline">Lihat semua</a>
        </div>
        <div class="flex-1 px-5 py-2 sm:px-6">
            @forelse ($notifikasiTerbaru as $n)
                <div class="flex gap-3 border-b border-line py-3.5 last:border-0">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $n->sudah_dibaca ? 'bg-canvas text-muted' : 'bg-primary-soft text-primary' }}"><x-icon name="bell" class="h-5 w-5" /></span>
                    <div class="min-w-0">
                        <p class="text-[15px] font-semibold text-ink">{{ $n->judul }}</p>
                        <p class="text-sm text-muted">{{ $n->tanggal_dibuat->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <x-empty-state illustration="notifikasi" title="Belum ada notifikasi" message="Kabar verifikasi dan pesanan akan muncul di sini." compact />
            @endforelse
        </div>
    </div>
</div>

{{-- Timeline status verifikasi --}}
<div class="card reveal mt-6">
    <div class="card-head">
        <div>
            <h2 class="card-title">Status verifikasi</h2>
            <p class="text-sm text-muted">Tahapan pemeriksaan profil Anda oleh admin.</p>
        </div>
        <a href="{{ route('worker.verification') }}" class="btn-primary btn-sm">Lihat detail</a>
    </div>
    <div class="card-body">
        @include('components.timeline-verifikasi', ['status' => $profil->status_verifikasi])
        @if ($profil->bisaDiajukan() && $profil->catatan_verifikasi)
            <x-alert type="danger" title="Catatan admin" class="mt-6">{{ $profil->catatan_verifikasi }}</x-alert>
        @endif
    </div>
</div>

@endsection
