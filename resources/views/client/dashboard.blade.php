@extends('layouts.client')
@section('title', 'Dashboard Klien')
@section('content')

<div class="min-h-screen bg-[#F5F7F5] p-6">

    {{-- Hero Welcome Card --}}
    <div class="bg-gradient-to-r from-[#176B4D] to-[#104C38] rounded-2xl p-8 mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="text-white">
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">
                Selamat Datang, {{ $user['name'] }}! 👋
            </h1>
            <p class="text-green-200 mb-6 max-w-md">
                Temukan pekerja terbaik untuk kebutuhan rumah tangga Anda.
            </p>
            <a href="#"
               class="inline-flex items-center gap-2 bg-white text-[#176B4D] font-semibold px-5 py-2.5 rounded-xl hover:bg-green-50 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                </svg>
                Cari Pekerja Sekarang
            </a>
        </div>
        <div class="hidden sm:flex items-center justify-center flex-shrink-0">
            <svg class="w-36 h-36 opacity-20" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="80" r="40" fill="white"/>
                <ellipse cx="100" cy="160" rx="70" ry="40" fill="white"/>
                <circle cx="60" cy="90" r="25" fill="white" opacity="0.6"/>
                <circle cx="140" cy="90" r="25" fill="white" opacity="0.6"/>
            </svg>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Favorit --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.27 2 8.5A5.5 5.5 0 017.5 3c1.74 0 3.41.81 4.5 2.08A5.98 5.98 0 0116.5 3 5.5 5.5 0 0122 8.5c0 3.77-3.4 6.86-8.55 11.53L12 21.35z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#172B23]">{{ $stats['favorites'] }}</p>
            <p class="text-xs text-[#52645B] mt-0.5">Favorit</p>
        </div>

        {{-- Pesanan Aktif --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#172B23]">{{ $stats['active_orders'] }}</p>
            <p class="text-xs text-[#52645B] mt-0.5">Pesanan Aktif</p>
        </div>

        {{-- Riwayat --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#172B23]">{{ $stats['history'] }}</p>
            <p class="text-xs text-[#52645B] mt-0.5">Riwayat</p>
        </div>

        {{-- Notifikasi --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-9.9-4.524M9 11v3.159a2 2 0 01-.595 1.436L7 17h5m-1 4a2 2 0 01-4 0" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#172B23]">{{ $stats['notifications'] }}</p>
            <p class="text-xs text-[#52645B] mt-0.5">Notifikasi</p>
        </div>

    </div>

    {{-- Rekomendasi Pekerja --}}
    <div class="mt-8">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-[#172B23]">Rekomendasi Pekerja</h2>
            <p class="text-sm text-[#52645B] mt-0.5">Dipilihkan khusus untuk Anda</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($recommendedWorkers as $worker)
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md hover:border-[#176B4D] transition-all">
                {{-- Avatar --}}
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-[#E4F2E8] flex items-center justify-center">
                        <span class="text-lg font-bold text-[#176B4D]">
                            {{ strtoupper(substr($worker['name'], 0, 1)) }}{{ strtoupper(substr(strstr($worker['name'], ' '), 1, 1)) }}
                        </span>
                    </div>
                </div>
                {{-- Name --}}
                <h3 class="font-semibold text-[#172B23] text-center mb-2">{{ $worker['name'] }}</h3>
                {{-- Category Badge --}}
                <div class="flex justify-center mb-3">
                    @if($worker['category'] === 'ART')
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">ART</span>
                    @elseif($worker['category'] === 'Baby Sitter')
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Baby Sitter</span>
                    @elseif($worker['category'] === 'Perawat Lansia')
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">Perawat Lansia</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">{{ $worker['category'] }}</span>
                    @endif
                </div>
                {{-- Rating --}}
                <div class="flex items-center justify-center gap-1 mb-2">
                    <span class="text-yellow-400 text-sm">⭐</span>
                    <span class="text-sm font-semibold text-[#172B23]">{{ $worker['rating'] }}</span>
                    <span class="text-xs text-[#52645B]">/5.0</span>
                </div>
                {{-- Location --}}
                <p class="text-xs text-[#52645B] text-center mb-4">📍 {{ $worker['location'] }}</p>
                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button class="flex-1 py-2 px-2 border border-[#176B4D] text-[#176B4D] text-xs font-medium rounded-xl hover:bg-[#E4F2E8] transition-colors">
                        ♥ Favorit
                    </button>
                    <a href="#" class="flex-1 py-2 px-2 bg-[#176B4D] text-white text-xs font-medium rounded-xl hover:bg-[#104C38] transition-colors text-center">
                        Lihat Profil
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-10 text-[#52645B]">
                <p class="text-sm">Belum ada rekomendasi pekerja saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Pesanan Terbaru --}}
    <div class="mt-8 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-[#172B23]">Pesanan Terbaru</h2>
            <a href="#" class="text-sm font-medium text-[#176B4D] hover:text-[#104C38] transition-colors">Lihat Semua →</a>
        </div>

        @php
            $recentOrders = $recentOrders ?? [];
            $dummyOrders = [
                ['name' => 'Siti Rahayu', 'category' => 'ART', 'status' => 'Aktif', 'date' => '28 Okt 2025'],
                ['name' => 'Dewi Anggraeni', 'category' => 'Baby Sitter', 'status' => 'Selesai', 'date' => '20 Okt 2025'],
            ];
            $displayOrders = count($recentOrders) > 0 ? $recentOrders : $dummyOrders;
        @endphp

        @if(count($displayOrders) === 0)
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M12 12h.01M12 16h.01" />
            </svg>
            <p class="text-[#172B23] font-medium">Belum ada pesanan</p>
            <p class="text-[#52645B] text-sm mt-1 mb-5">Anda belum memiliki riwayat pesanan.</p>
            <a href="#"
               class="inline-flex items-center gap-2 bg-[#176B4D] text-white font-medium px-5 py-2.5 rounded-xl hover:bg-[#104C38] transition-colors">
                Mulai Cari Pekerja
            </a>
        </div>
        @else
        <ul class="divide-y divide-gray-50">
            @foreach($displayOrders as $order)
            <li class="flex items-center justify-between py-4 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#E4F2E8] flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-[#176B4D]">
                            {{ strtoupper(substr($order['name'], 0, 1)) }}{{ strtoupper(substr(strstr($order['name'], ' '), 1, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-medium text-[#172B23] text-sm">{{ $order['name'] }}</p>
                        <p class="text-xs text-[#52645B]">{{ $order['category'] }} · {{ $order['date'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($order['status'] === 'Aktif')
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Aktif</span>
                    @elseif($order['status'] === 'Selesai')
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Selesai</span>
                    @elseif($order['status'] === 'Dibatalkan')
                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">Dibatalkan</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">{{ $order['status'] }}</span>
                    @endif
                    <a href="#"
                       class="text-xs font-medium bg-[#E4F2E8] text-[#176B4D] px-3 py-1.5 rounded-lg hover:bg-[#176B4D] hover:text-white transition-colors">
                        Detail
                    </a>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>

</div>

@endsection
