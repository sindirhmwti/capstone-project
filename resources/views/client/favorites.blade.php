@extends('layouts.client')
@section('title', 'Favorit Saya')
@section('content')

@include('components.page-header', ['crumb' => 'Klien / Favorit Saya', 'title' => 'Favorit Saya'])

<div class="flex items-center justify-between mb-4">
    <p class="font-semibold text-[#172B23]">Pekerja favorit Anda <span class="text-xs font-normal text-[#52645B] ml-1">{{ $favorit->count() }} pekerja</span></p>
    <a href="{{ route('client.workers') }}" class="text-xs text-[#176B4D] hover:underline">Cari pekerja lain →</a>
</div>

@if ($favorit->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
        <p class="font-semibold text-[#172B23]">Belum terdapat pekerja yang disimpan sebagai favorit.</p>
        <p class="text-sm text-[#52645B] mt-1">Tekan ikon ♡ pada katalog pekerja untuk menyimpan pekerja yang Anda minati.</p>
        <a href="{{ route('client.workers') }}" class="inline-block mt-4 px-5 py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Buka Katalog Pekerja</a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach ($favorit as $f)
            @php $p = $f->profilPekerja; $tersedia = $p->status_verifikasi === 'terverifikasi' && $p->pengguna->isAktif(); @endphp
            <div class="relative">
                @include('components.worker-card', ['profil' => $p, 'link' => route('client.workers.show', $p), 'favorit' => true, 'tampilStatus' => ! $tersedia])
                @unless ($tersedia)
                    <p class="text-xs text-red-600 mt-1">Pekerja ini sedang tidak tersedia di katalog.</p>
                @endunless
            </div>
        @endforeach
    </div>
    <p class="text-xs text-[#52645B] mt-4">Menampilkan {{ $favorit->count() }} pekerja. Status verifikasi tampil pada setiap profil.</p>
@endif

@endsection
