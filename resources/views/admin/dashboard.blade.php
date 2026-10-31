@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')

<div class="min-h-screen bg-[#F5F7F5] p-6">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#172B23]">Dashboard Admin</h1>
        <p class="text-[#52645B] mt-1">Selamat datang kembali, Admin 👋</p>
        <p class="text-sm text-[#52645B] mt-1">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Card 1: Total Pekerja --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-[#176B4D] rounded-xl p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-5-5M9 20H4v-2a4 4 0 015-5m6 0a4 4 0 10-8 0 4 4 0 008 0zm6-10a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-1 rounded-full">+12% bulan ini</span>
            </div>
            <p class="text-3xl font-bold text-[#172B23]">{{ $stats['total_workers'] }}</p>
            <p class="text-sm text-[#52645B] mt-1">Total Pekerja</p>
        </div>

        {{-- Card 2: Menunggu Verifikasi --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-amber-500 rounded-xl p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium bg-amber-100 text-amber-700 px-2 py-1 rounded-full">Perlu ditinjau</span>
            </div>
            <p class="text-3xl font-bold text-[#172B23]">{{ $stats['pending_verify'] }}</p>
            <p class="text-sm text-[#52645B] mt-1">Menunggu Verifikasi</p>
        </div>

        {{-- Card 3: Total Klien --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-600 rounded-xl p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a4 4 0 00-5-5M9 20H4v-2a4 4 0 015-5m0 0a4 4 0 108 0 4 4 0 00-8 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2 py-1 rounded-full">+8% bulan ini</span>
            </div>
            <p class="text-3xl font-bold text-[#172B23]">{{ $stats['total_clients'] }}</p>
            <p class="text-sm text-[#52645B] mt-1">Total Klien</p>
        </div>

        {{-- Card 4: Pesanan Aktif --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-[#20A66A] rounded-xl p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-1 rounded-full">Sedang berjalan</span>
            </div>
            <p class="text-3xl font-bold text-[#172B23]">{{ $stats['active_orders'] }}</p>
            <p class="text-sm text-[#52645B] mt-1">Pesanan Aktif</p>
        </div>

    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-[#172B23]">Aktivitas Terbaru</h2>
            <a href="#" class="text-sm font-medium text-[#176B4D] hover:text-[#104C38] transition-colors">Lihat Semua →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-3 px-2 text-[#52645B] font-medium">Nama Pekerja</th>
                        <th class="text-left py-3 px-2 text-[#52645B] font-medium">Kategori</th>
                        <th class="text-left py-3 px-2 text-[#52645B] font-medium">Status</th>
                        <th class="text-left py-3 px-2 text-[#52645B] font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentActivities as $activity)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[#E4F2E8] flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-[#176B4D]">
                                        {{ strtoupper(substr($activity['name'], 0, 1)) }}{{ strtoupper(substr(strstr($activity['name'], ' '), 1, 1)) }}
                                    </span>
                                </div>
                                <span class="font-medium text-[#172B23]">{{ $activity['name'] }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-2">
                            <span class="px-2 py-1 bg-[#E4F2E8] text-[#176B4D] rounded-full text-xs font-medium">
                                {{ $activity['category'] }}
                            </span>
                        </td>
                        <td class="py-3 px-2">
                            @if($activity['status'] === 'Menunggu')
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">Menunggu</span>
                            @elseif($activity['status'] === 'Terverifikasi')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Terverifikasi</span>
                            @elseif($activity['status'] === 'Aktif')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Aktif</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">{{ $activity['status'] }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-2 text-[#52645B]">{{ $activity['date'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-[#52645B]">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span class="text-sm">Belum ada aktivitas terbaru.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Verifikasi Menunggu --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mt-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-[#172B23]">Verifikasi Menunggu</h2>
                <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-1 rounded-full">
                    {{ count($pendingVerifications) }}
                </span>
            </div>
            <a href="#" class="text-sm font-medium text-[#176B4D] hover:text-[#104C38] transition-colors">Lihat Semua →</a>
        </div>

        @if(count($pendingVerifications) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($pendingVerifications as $pending)
            <div class="border border-gray-100 rounded-2xl p-5 hover:border-[#176B4D] hover:shadow-sm transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-[#E4F2E8] flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-bold text-[#176B4D]">
                            {{ strtoupper(substr($pending['name'], 0, 1)) }}{{ strtoupper(substr(strstr($pending['name'], ' '), 1, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-semibold text-[#172B23]">{{ $pending['name'] }}</p>
                        <span class="px-2 py-0.5 bg-[#E4F2E8] text-[#176B4D] rounded-full text-xs font-medium">
                            {{ $pending['category'] }}
                        </span>
                    </div>
                </div>
                <p class="text-xs text-[#52645B] mb-4">
                    Diajukan: {{ $pending['date'] }}
                </p>
                <div class="flex gap-2">
                    <button class="flex-1 py-2 px-3 border border-red-400 text-red-500 text-sm font-medium rounded-xl hover:bg-red-50 transition-colors">
                        Tolak
                    </button>
                    <button class="flex-1 py-2 px-3 bg-[#176B4D] text-white text-sm font-medium rounded-xl hover:bg-[#104C38] transition-colors">
                        Verifikasi
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-[#172B23] font-medium">Semua Bersih!</p>
            <p class="text-[#52645B] text-sm mt-1">Tidak ada verifikasi yang menunggu saat ini.</p>
        </div>
        @endif
    </div>

</div>

@endsection
