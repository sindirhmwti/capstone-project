@extends($layout)
@section('title', 'Notifikasi')
@section('content')

@include('components.page-header', [
    'crumb' => $crumb . ' / Notifikasi',
    'title' => 'Notifikasi',
    'subtitle' => $jumlahBelumDibaca > 0 ? 'Anda memiliki ' . $jumlahBelumDibaca . ' notifikasi yang belum dibaca.' : 'Semua notifikasi sudah dibaca.',
])

<div class="card max-w-4xl">
    <div class="flex items-center justify-between border-b border-line px-5 py-4 sm:px-6">
        <h2 class="card-title">Aktivitas terbaru</h2>
        @if ($jumlahBelumDibaca > 0)
            <form method="POST" action="{{ route($prefix . '.notifications.read-all') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-ghost btn-sm"><x-icon name="check" class="h-4 w-4" /> Tandai semua dibaca</button>
            </form>
        @endif
    </div>

    @forelse ($notifikasi as $n)
        <div class="flex items-start gap-4 border-b border-line px-5 py-4 last:border-0 sm:px-6 {{ $n->sudah_dibaca ? '' : 'bg-primary-soft/40' }}">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $n->sudah_dibaca ? 'bg-canvas text-muted' : 'bg-primary text-white' }}">
                <x-icon name="bell" class="h-5 w-5" />
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-base {{ $n->sudah_dibaca ? 'font-medium text-muted' : 'font-semibold text-ink' }}">{{ $n->judul }}</p>
                <p class="mt-0.5 text-sm text-muted">{{ $n->isi_notifikasi }}</p>
                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-muted"><x-icon name="clock" class="h-3.5 w-3.5" /> {{ $n->tanggal_dibuat->diffForHumans() }}</p>
            </div>
            @unless ($n->sudah_dibaca)
                <form method="POST" action="{{ route($prefix . '.notifications.read', $n) }}" data-no-loading>
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-ghost btn-sm whitespace-nowrap" title="Tandai dibaca">Tandai dibaca</button>
                </form>
            @endunless
        </div>
    @empty
        <x-empty-state illustration="notifikasi" title="Belum terdapat notifikasi" message="Pemberitahuan tentang verifikasi dan pesanan akan muncul di sini." />
    @endforelse
</div>

@if ($notifikasi->hasPages())
    <div class="mt-6 max-w-4xl">{{ $notifikasi->links() }}</div>
@endif

@endsection
