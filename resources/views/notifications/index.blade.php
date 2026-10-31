@extends($layout)
@section('title', 'Notifikasi')
@section('content')

@include('components.page-header', [
    'crumb' => $crumb . ' / Notifikasi',
    'title' => 'Notifikasi',
    'subtitle' => $jumlahBelumDibaca > 0 ? 'Anda memiliki ' . $jumlahBelumDibaca . ' notifikasi yang belum dibaca.' : 'Semua notifikasi sudah dibaca.',
])

@php
    // Ikon & warna ditentukan dari isi judul agar jenis pemberitahuan mudah dikenali
    $gayaNotif = function ($n) {
        $j = mb_strtolower($n->judul);
        return match (true) {
            str_contains($j, 'tolak') || str_contains($j, 'batal') => ['x-circle', 'bg-red-100 text-red-600'],
            str_contains($j, 'verifikasi') || str_contains($j, 'dokumen') => ['shield-check', 'bg-primary-soft text-primary'],
            str_contains($j, 'pesan') => ['clipboard', 'bg-sky-100 text-sky-600'],
            str_contains($j, 'akun') => ['user', 'bg-violet-100 text-violet-600'],
            default => ['bell', 'bg-sun-soft text-sun-dark'],
        };
    };
@endphp

<div class="grid gap-6 xl:grid-cols-3">
    <div class="xl:col-span-2">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg font-extrabold text-ink">Aktivitas terbaru</h2>
            @if ($jumlahBelumDibaca > 0)
                <form method="POST" action="{{ route($prefix . '.notifications.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-secondary btn-sm"><x-icon name="check" class="h-4 w-4" /> Tandai semua dibaca</button>
                </form>
            @endif
        </div>

        @if ($notifikasi->isEmpty())
            <div class="card"><x-empty-state illustration="notifikasi" title="Belum terdapat notifikasi" message="Pemberitahuan tentang verifikasi dan pesanan akan muncul di sini." /></div>
        @else
            <div class="space-y-3">
                @foreach ($notifikasi as $n)
                    @php [$ikon, $warna] = $gayaNotif($n); @endphp
                    <article class="reveal relative flex items-start gap-4 rounded-3xl border p-4 transition-colors sm:p-5 {{ $n->sudah_dibaca ? 'border-line bg-white' : 'border-primary/25 bg-mint-soft shadow-soft' }}">
                        @unless ($n->sudah_dibaca)
                            <span class="absolute left-0 top-6 h-10 w-1.5 rounded-r-full bg-primary" aria-hidden="true"></span>
                        @endunless
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $warna }}"><x-icon :name="$ikon" class="h-6 w-6" /></span>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-base {{ $n->sudah_dibaca ? 'font-semibold text-muted' : 'font-extrabold text-ink' }}">{{ $n->judul }}</p>
                                @unless ($n->sudah_dibaca) <span class="rounded-full bg-primary px-2 py-0.5 text-[11px] font-bold text-white">Baru</span> @endunless
                            </div>
                            <p class="mt-1 text-[15px] text-muted">{{ $n->isi_notifikasi }}</p>
                            <p class="mt-2 flex items-center gap-1.5 text-sm text-muted" title="{{ $n->tanggal_dibuat->translatedFormat('d F Y H:i') }}"><x-icon name="clock" class="h-4 w-4" /> {{ $n->tanggal_dibuat->diffForHumans() }}</p>
                        </div>
                        @unless ($n->sudah_dibaca)
                            <form method="POST" action="{{ route($prefix . '.notifications.read', $n) }}" data-no-loading>
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="icon-btn" data-tip="Tandai dibaca" aria-label="Tandai dibaca"><x-icon name="check" class="h-5 w-5" /></button>
                            </form>
                        @endunless
                    </article>
                @endforeach
            </div>
            @if ($notifikasi->hasPages())
                <div class="mt-6">{{ $notifikasi->links() }}</div>
            @endif
        @endif
    </div>

    {{-- Ringkasan --}}
    <aside class="space-y-6 xl:sticky xl:top-28 xl:h-fit">
        <div class="card reveal overflow-hidden">
            <div class="bg-gradient-to-br from-primary-dark to-primary p-6 text-white">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-sun"><x-icon name="bell" class="h-6 w-6" /></span>
                <p class="mt-4 text-4xl font-extrabold" data-count="{{ $jumlahBelumDibaca }}">{{ $jumlahBelumDibaca }}</p>
                <p class="text-white/80">notifikasi belum dibaca</p>
            </div>
            <div class="space-y-3 p-5 text-[15px] text-muted">
                <p class="flex items-start gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary-soft text-primary"><x-icon name="shield-check" class="h-4 w-4" /></span> Kabar verifikasi dan dokumen</p>
                <p class="flex items-start gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-600"><x-icon name="clipboard" class="h-4 w-4" /></span> Perubahan status pesanan</p>
                <p class="flex items-start gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600"><x-icon name="x-circle" class="h-4 w-4" /></span> Penolakan atau pembatalan</p>
            </div>
        </div>
    </aside>
</div>

@endsection
