{{-- Riwayat perubahan status pesanan (tabel riwayat_status_pemesanan) --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h3 class="font-semibold text-[#172B23] mb-4">Riwayat status</h3>
    <ol class="relative border-l-2 border-[#E4F2E8] ml-2 space-y-5">
        @foreach ($pemesanan->riwayatStatus as $r)
            <li class="ml-5">
                <span class="absolute -left-[9px] w-4 h-4 rounded-full border-2 border-white {{ $loop->last ? 'bg-[#176B4D]' : 'bg-[#20A66A]/40' }}"></span>
                <div class="flex flex-wrap items-center gap-2">
                    @if ($r->status_sebelumnya)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ \App\Models\Pemesanan::warna($r->status_sebelumnya) }}">{{ \App\Models\Pemesanan::STATUS[$r->status_sebelumnya] ?? $r->status_sebelumnya }}</span>
                        <span class="text-xs text-[#52645B]">→</span>
                    @endif
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ \App\Models\Pemesanan::warna($r->status_baru) }}">{{ \App\Models\Pemesanan::STATUS[$r->status_baru] ?? $r->status_baru }}</span>
                </div>
                <p class="text-xs text-[#52645B] mt-1">{{ $r->tanggal_perubahan->translatedFormat('d F Y, H:i') }} · oleh {{ $r->pengubah?->nama ?? 'Sistem' }}</p>
                @if ($r->catatan_perubahan)
                    <p class="text-sm text-[#172B23] mt-1">{{ $r->catatan_perubahan }}</p>
                @endif
            </li>
        @endforeach
    </ol>
</div>
