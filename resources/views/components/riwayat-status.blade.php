{{-- Riwayat perubahan status pesanan (tabel riwayat_status_pemesanan) --}}
<div class="card card-body">
    <h2 class="card-title mb-5 flex items-center gap-2"><x-icon name="clock" class="h-5 w-5 text-primary" /> Riwayat status</h2>
    <ol class="relative ml-2 space-y-6 border-l-2 border-primary-soft">
        @foreach ($pemesanan->riwayatStatus as $r)
            <li class="relative pl-6">
                <span class="absolute -left-[9px] top-1 h-4 w-4 rounded-full border-[3px] border-white {{ $loop->last ? 'bg-primary ring-4 ring-primary/15' : 'bg-mint' }}"></span>
                <div class="flex flex-wrap items-center gap-2">
                    @if ($r->status_sebelumnya)
                        <x-badge :status="$r->status_sebelumnya" :dot="false">{{ \App\Models\Pemesanan::STATUS[$r->status_sebelumnya] ?? $r->status_sebelumnya }}</x-badge>
                        <x-icon name="arrow-right" class="h-4 w-4 text-muted" />
                    @endif
                    <x-badge :status="$r->status_baru">{{ \App\Models\Pemesanan::STATUS[$r->status_baru] ?? $r->status_baru }}</x-badge>
                </div>
                <p class="mt-1.5 text-sm text-muted"><span title="{{ $r->tanggal_perubahan->translatedFormat('d F Y, H:i') }}">{{ $r->tanggal_perubahan->diffForHumans() }}</span> · {{ $r->tanggal_perubahan->translatedFormat('d M Y, H:i') }} · oleh {{ $r->pengubah?->nama ?? 'Sistem' }}</p>
                @if ($r->catatan_perubahan)
                    <p class="mt-2 rounded-xl bg-canvas px-3.5 py-2.5 text-[15px] text-ink">{{ $r->catatan_perubahan }}</p>
                @endif
            </li>
        @endforeach
    </ol>
</div>
