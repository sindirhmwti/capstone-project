@extends($layout)
@section('title', 'Notifikasi')
@section('content')

@include('components.page-header', ['crumb' => $crumb . ' / Notifikasi', 'title' => 'Notifikasi'])

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="font-semibold text-[#172B23]">Aktivitas terbaru</h3>
            <p class="text-xs text-[#52645B]">{{ $jumlahBelumDibaca }} notifikasi belum dibaca</p>
        </div>
        @if ($jumlahBelumDibaca > 0)
            <form method="POST" action="{{ route($prefix . '.notifications.read-all') }}">
                @csrf
                @method('PATCH')
                <button class="text-sm text-[#176B4D] font-medium hover:underline">Tandai semua dibaca</button>
            </form>
        @endif
    </div>

    @forelse ($notifikasi as $n)
        <div class="flex items-start gap-4 py-4 border-b border-gray-100 last:border-0 {{ $n->sudah_dibaca ? '' : 'bg-[#F5F7F5]/60 -mx-3 px-3 rounded-lg' }}">
            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 {{ $n->sudah_dibaca ? 'bg-gray-100 text-gray-400' : 'bg-[#E4F2E8] text-[#176B4D]' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm {{ $n->sudah_dibaca ? 'text-[#52645B]' : 'font-semibold text-[#172B23]' }}">{{ $n->judul }}</p>
                <p class="text-sm text-[#52645B] mt-0.5">{{ $n->isi_notifikasi }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $n->tanggal_dibuat->diffForHumans() }} · {{ $n->sudah_dibaca ? 'Sudah dibaca' : 'Belum dibaca' }}</p>
            </div>
            @unless ($n->sudah_dibaca)
                <form method="POST" action="{{ route($prefix . '.notifications.read', $n) }}">
                    @csrf
                    @method('PATCH')
                    <button class="text-xs text-[#176B4D] hover:underline whitespace-nowrap" title="Tandai dibaca">● Tandai dibaca</button>
                </form>
            @endunless
        </div>
    @empty
        <p class="py-10 text-center text-sm text-[#52645B]">Belum terdapat notifikasi.</p>
    @endforelse

    <div class="mt-5">{{ $notifikasi->links() }}</div>
</div>

@endsection
