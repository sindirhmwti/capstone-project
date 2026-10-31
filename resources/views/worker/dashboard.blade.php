@extends('layouts.worker')
@section('title', 'Dashboard Pekerja')
@section('content')

<div class="min-h-screen bg-[#F5F7F5] p-6">

    {{-- Welcome Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#172B23]">Halo, {{ $user['name'] }}! 👋</h1>
        <p class="text-[#52645B] mt-1">Kelola profil dan pantau status verifikasi Anda di sini.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Card 1: Status Akun --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-[#E4F2E8] rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-[#176B4D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <p class="text-sm text-[#52645B] mb-1">Status Akun</p>
            @if($stats['account_status'] === 'Aktif')
                <span class="inline-flex items-center px-2.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                    ● {{ $stats['account_status'] }}
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                    {{ $stats['account_status'] }}
                </span>
            @endif
        </div>

        {{-- Card 2: Status Verifikasi --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9a12.02 12.02 0 00-.382-3.016z" />
                </svg>
            </div>
            <p class="text-sm text-[#52645B] mb-1">Status Verifikasi</p>
            @if($stats['verification_status'] === 'Terverifikasi')
                <span class="inline-flex items-center px-2.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                    ✓ {{ $stats['verification_status'] }}
                </span>
            @elseif($stats['verification_status'] === 'Menunggu')
                <span class="inline-flex items-center px-2.5 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">
                    ⏳ {{ $stats['verification_status'] }}
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                    {{ $stats['verification_status'] }}
                </span>
            @endif
        </div>

        {{-- Card 3: Dokumen --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-2xl font-bold text-[#172B23]">{{ $stats['documents_uploaded'] }}</p>
            <p class="text-xs text-[#52645B] mt-0.5">Dokumen</p>
        </div>

        {{-- Card 4: Notifikasi --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.9-4.524M9 11v3.159a2 2 0 01-.595 1.436L7 17h5m-1 4a2 2 0 01-4 0" />
                </svg>
            </div>
            <p class="text-2xl font-bold text-[#172B23]">{{ $stats['notifications'] }}</p>
            <p class="text-xs text-[#52645B] mt-0.5">Notifikasi Baru</p>
        </div>

    </div>

    {{-- Profile Completion Card --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-[#172B23]">Kelengkapan Profil</h2>
            <span class="text-2xl font-bold text-[#176B4D]">{{ $profileCompletion }}%</span>
        </div>

        {{-- Progress Bar --}}
        <div class="bg-gray-200 rounded-full h-3 mb-6">
            <div class="bg-[#176B4D] h-3 rounded-full transition-all duration-500"
                 style="width: {{ $profileCompletion }}%"></div>
        </div>

        {{-- Profile Sections List --}}
        <ul class="space-y-3">
            @foreach($profileSections as $section)
            <li class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($section['done'])
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm text-[#172B23]">{{ $section['label'] }}</span>
                    @else
                        <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm text-[#52645B]">{{ $section['label'] }}</span>
                    @endif
                </div>
                @if(!$section['done'])
                    <a href="{{ $section['link'] ?? '#' }}"
                       class="text-xs font-medium text-[#176B4D] hover:text-[#104C38] hover:underline transition-colors">
                        Lengkapi →
                    </a>
                @else
                    <span class="text-xs text-green-600 font-medium">Selesai</span>
                @endif
            </li>
            @endforeach
        </ul>
    </div>

    {{-- Verification Status Card --}}
    @if($user['category'] === 'Baby Sitter' || $user['category'] === 'Perawat Lansia')
    <div class="border-2 border-amber-300 bg-amber-50 rounded-2xl p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="text-3xl flex-shrink-0">⏳</div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-lg font-semibold text-amber-800">Menunggu Verifikasi Admin</h3>
                    <span class="px-2.5 py-0.5 bg-amber-200 text-amber-800 text-xs font-bold rounded-full tracking-wide">
                        MENUNGGU VERIFIKASI
                    </span>
                </div>
                <p class="text-sm text-amber-700 leading-relaxed">
                    Dokumen Anda sedang dalam proses peninjauan oleh tim admin.
                    Kami akan menghubungi Anda setelah proses selesai.
                </p>
            </div>
        </div>
    </div>
    @elseif($user['category'] === 'ART')
    <div class="border-2 border-green-300 bg-green-50 rounded-2xl p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="text-3xl flex-shrink-0">✅</div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-lg font-semibold text-green-800">Profil Aktif</h3>
                    <span class="px-2.5 py-0.5 bg-green-200 text-green-800 text-xs font-bold rounded-full tracking-wide">
                        PROFIL AKTIF
                    </span>
                </div>
                <p class="text-sm text-green-700 leading-relaxed">
                    Profil Anda telah aktif dan dapat dilihat oleh klien.
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Action Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">

        {{-- Lengkapi Profil --}}
        <a href="#"
           class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:border-[#176B4D] hover:shadow-md transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 bg-[#E4F2E8] rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#176B4D] transition-colors">
                <svg class="w-6 h-6 text-[#176B4D] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-[#172B23]">Lengkapi Profil</p>
                <p class="text-xs text-[#52645B] mt-0.5">Isi informasi diri Anda</p>
            </div>
            <svg class="w-5 h-5 text-gray-300 ml-auto group-hover:text-[#176B4D] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        {{-- Upload Dokumen --}}
        <a href="#"
           class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:border-[#176B4D] hover:shadow-md transition-all flex items-center gap-4 group">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 transition-colors">
                <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-[#172B23]">Upload Dokumen</p>
                <p class="text-xs text-[#52645B] mt-0.5">Unggah dokumen identitas Anda</p>
            </div>
            <svg class="w-5 h-5 text-gray-300 ml-auto group-hover:text-[#176B4D] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        {{-- Lihat Notifikasi --}}
        <a href="#"
           class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:border-[#176B4D] hover:shadow-md transition-all flex items-center gap-4 group sm:col-span-2 lg:col-span-1">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-red-500 transition-colors">
                <svg class="w-6 h-6 text-red-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.9-4.524M9 11v3.159a2 2 0 01-.595 1.436L7 17h5m-1 4a2 2 0 01-4 0" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-[#172B23]">Lihat Notifikasi</p>
                <p class="text-xs text-[#52645B] mt-0.5">Pantau pembaruan terbaru Anda</p>
            </div>
            <svg class="w-5 h-5 text-gray-300 ml-auto group-hover:text-[#176B4D] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

    </div>

</div>

@endsection
