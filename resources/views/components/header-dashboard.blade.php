@props(['role' => 'admin'])

@php
    $titles = [
        'admin'  => 'Admin Panel',
        'client' => 'Dashboard Klien',
        'worker' => 'Dashboard Pekerja',
    ];
    $labelPeran = [
        'admin'  => 'Admin',
        'client' => 'Klien',
        'worker' => 'Calon Pekerja',
    ];
    $pageTitle = $titles[$role] ?? 'Dashboard';
    $pengguna = auth()->user();
    $jumlahNotif = $pengguna?->jumlahNotifikasiBelumDibaca() ?? 0;
    $routeNotif = $role . '.notifications';
    $routeProfil = $role . '.profile';
@endphp

<header class="sticky top-0 z-40 h-16 w-full bg-white border-b border-gray-200 shadow-sm flex items-center print:hidden">
    <div class="flex items-center justify-between w-full px-4 sm:px-6">

        {{-- Kiri: tombol sidebar + judul --}}
        <div class="flex items-center gap-3">
            <button id="sidebar-toggle"
                    type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-[#52645B] hover:text-[#176B4D] hover:bg-[#E4F2E8] focus:outline-none transition-colors duration-150 lg:hidden"
                    aria-label="Buka sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="text-base sm:text-lg font-semibold text-[#172B23]">@yield('title', $pageTitle)</h1>
        </div>

        {{-- Kanan: notifikasi + pengguna --}}
        <div class="flex items-center gap-3 sm:gap-4">

            <a href="{{ \Illuminate\Support\Facades\Route::has($routeNotif) ? route($routeNotif) : '#' }}"
               class="relative inline-flex items-center justify-center p-2 rounded-full text-[#52645B] hover:text-[#176B4D] hover:bg-[#E4F2E8] focus:outline-none transition-colors duration-150"
               aria-label="Notifikasi">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if ($jumlahNotif > 0)
                    <span class="absolute top-1 right-1 inline-flex items-center justify-center min-w-4 h-4 px-1 text-[10px] font-bold text-white bg-red-500 rounded-full leading-none">
                        {{ $jumlahNotif > 9 ? '9+' : $jumlahNotif }}
                    </span>
                @endif
            </a>

            <a href="{{ \Illuminate\Support\Facades\Route::has($routeProfil) ? route($routeProfil) : '#' }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-[#E4F2E8] border-2 border-[#176B4D] flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-bold text-[#176B4D] leading-none">{{ $pengguna?->inisial() ?? 'U' }}</span>
                </div>
                <div class="hidden sm:block leading-tight">
                    <p class="text-sm font-medium text-[#172B23]">{{ $pengguna?->nama ?? 'Pengguna' }}</p>
                    <p class="text-[11px] text-[#52645B]">{{ $labelPeran[$role] ?? '' }}</p>
                </div>
            </a>

        </div>
    </div>
</header>
