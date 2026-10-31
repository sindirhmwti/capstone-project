@extends('layouts.worker')
@section('title', 'Beranda Pekerja')
@section('content')

@include('components.page-header', ['crumb' => 'Calon Pekerja / Beranda Pekerja', 'title' => 'Beranda Pekerja'])

{{-- Sambutan --}}
<div class="bg-gradient-to-r from-[#176B4D] to-[#104C38] rounded-2xl p-6 sm:p-8 mb-6 text-white">
    <h1 class="text-2xl sm:text-3xl font-bold mb-1">Selamat datang, {{ $pengguna->nama }}!</h1>
    <p class="text-green-100">{{ $profil->labelKategori() }} · Lengkapi profil agar informasi keahlian Anda tetap sesuai.</p>
</div>

{{-- Ringkasan --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="w-10 h-10 bg-[#E4F2E8] rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-[#176B4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
        </div>
        <p class="text-sm text-[#52645B] mb-1">Status Akun</p>
        <span class="inline-flex items-center px-2.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">● {{ ucfirst($pengguna->status_akun) }}</span>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9a12.02 12.02 0 00-.382-3.016z" /></svg>
        </div>
        <p class="text-sm text-[#52645B] mb-1">Status Verifikasi</p>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $profil->warnaStatus() }}">{{ $profil->labelStatus() }}</span>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
        </div>
        <p class="text-2xl font-bold text-[#172B23]">{{ $stats['documents_uploaded'] }} / {{ count($profil->dokumenWajib()) }}</p>
        <p class="text-xs text-[#52645B] mt-0.5">Dokumen Diunggah</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.9-4.524M9 11v3.159a2 2 0 01-.595 1.436L7 17h5m-1 4a2 2 0 01-4 0" /></svg>
        </div>
        <p class="text-2xl font-bold text-[#172B23]">{{ $stats['notifications'] }}</p>
        <p class="text-xs text-[#52645B] mt-0.5">Notifikasi Baru</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Kelengkapan profil --}}
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-[#172B23]">Kelengkapan Profil</h2>
            <span class="text-2xl font-bold text-[#176B4D]">{{ $profileCompletion }}%</span>
        </div>
        <div class="bg-gray-200 rounded-full h-3 mb-6">
            <div class="bg-[#176B4D] h-3 rounded-full transition-all duration-500" style="width: {{ $profileCompletion }}%"></div>
        </div>
        <ul class="space-y-3">
            @foreach ($profileSections as $section)
                <li class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 {{ $section['done'] ? 'bg-green-100' : 'bg-gray-100' }}">
                            <svg class="w-3.5 h-3.5 {{ $section['done'] ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-sm {{ $section['done'] ? 'text-[#172B23]' : 'text-[#52645B]' }}">{{ $section['label'] }}</span>
                    </div>
                    @if (! $section['done'])
                        <a href="{{ $section['link'] }}" class="text-xs font-medium text-[#176B4D] hover:text-[#104C38] hover:underline">Lengkapi →</a>
                    @else
                        <span class="text-xs text-green-600 font-medium">Selesai</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Notifikasi terbaru --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-[#172B23]">Notifikasi</h2>
            @if (Route::has('worker.notifications'))
                <a href="{{ route('worker.notifications') }}" class="text-xs font-medium text-[#176B4D] hover:underline">Lihat semua</a>
            @endif
        </div>
        @forelse ($notifikasiTerbaru as $n)
            <div class="py-3 border-b border-gray-100 last:border-0">
                <p class="text-sm font-medium text-[#172B23]">{{ $n->judul }}</p>
                <p class="text-xs text-[#52645B] mt-0.5">{{ $n->tanggal_dibuat->diffForHumans() }}</p>
            </div>
        @empty
            <p class="text-sm text-[#52645B]">Belum terdapat notifikasi.</p>
        @endforelse
    </div>
</div>

{{-- Status verifikasi --}}
@php
    $kartu = [
        'belum_lengkap' => ['border-gray-300 bg-white', 'text-[#172B23]', 'Lengkapi Data untuk Verifikasi', 'Profil Anda belum tampil di katalog klien. Lengkapi profil, kriteria khusus, dan dokumen, lalu ajukan verifikasi.'],
        'menunggu' => ['border-amber-300 bg-amber-50', 'text-amber-800', 'Menunggu Verifikasi Admin', 'Dokumen Anda sedang dalam proses peninjauan oleh admin. Anda akan mendapat notifikasi setelah proses selesai.'],
        'terverifikasi' => ['border-green-300 bg-green-50', 'text-green-800', 'Profil Terverifikasi', 'Profil Anda telah diverifikasi dan dapat dilihat oleh klien di katalog pekerja.'],
        'ditolak' => ['border-red-300 bg-red-50', 'text-red-800', 'Verifikasi Ditolak', 'Perbaiki data sesuai catatan admin, lalu ajukan verifikasi kembali.'],
    ][$profil->status_verifikasi];
@endphp
<div class="border-2 rounded-2xl p-6 mt-6 {{ $kartu[0] }}">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1">
            <h3 class="text-lg font-semibold {{ $kartu[1] }}">{{ $kartu[2] }}</h3>
            <p class="text-sm text-[#52645B] mt-1">{{ $kartu[3] }}</p>
            @if ($profil->bisaDiajukan() && $profil->catatan_verifikasi)
                <p class="text-sm text-red-700 mt-2"><strong>Catatan admin:</strong> {{ $profil->catatan_verifikasi }}</p>
            @endif
        </div>
        <a href="{{ route('worker.verification') }}" class="inline-flex justify-center px-5 py-2.5 bg-[#176B4D] text-white text-sm font-semibold rounded-xl hover:bg-[#104C38]">Lihat Status Verifikasi</a>
    </div>
</div>

{{-- Aksi cepat --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
    <a href="{{ route('worker.profile') }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:border-[#176B4D] hover:shadow-md transition-all flex items-center gap-4 group">
        <div class="w-12 h-12 bg-[#E4F2E8] rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#176B4D] transition-colors">
            <svg class="w-6 h-6 text-[#176B4D] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
        </div>
        <div>
            <p class="font-semibold text-[#172B23]">Profil Pekerja</p>
            <p class="text-xs text-[#52645B] mt-0.5">Isi dan perbarui informasi Anda</p>
        </div>
    </a>
    <a href="{{ Route::has('worker.notifications') ? route('worker.notifications') : '#' }}" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:border-[#176B4D] hover:shadow-md transition-all flex items-center gap-4 group">
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-red-500 transition-colors">
            <svg class="w-6 h-6 text-red-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.9-4.524M9 11v3.159a2 2 0 01-.595 1.436L7 17h5m-1 4a2 2 0 01-4 0" /></svg>
        </div>
        <div>
            <p class="font-semibold text-[#172B23]">Notifikasi</p>
            <p class="text-xs text-[#52645B] mt-0.5">Lihat aktivitas terbaru Anda</p>
        </div>
    </a>
</div>

@endsection
