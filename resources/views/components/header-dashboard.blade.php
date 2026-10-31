@props(['role' => 'admin'])

@php
    $titles = [
        'admin'  => 'Admin Panel',
        'client' => 'Dashboard Klien',
        'worker' => 'Dashboard Pekerja',
    ];
    $pageTitle = $titles[$role] ?? 'Dashboard';
@endphp

<header class="sticky top-0 z-40 h-16 w-full bg-white border-b border-gray-200 shadow-sm flex items-center">
    <div class="flex items-center justify-between w-full px-4 sm:px-6">

        {{-- Left: Hamburger + Page Title --}}
        <div class="flex items-center gap-3">
            {{-- Sidebar Toggle (Mobile) --}}
            <button id="sidebar-toggle"
                    type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-[#52645B] hover:text-[#176B4D] hover:bg-[#E4F2E8] focus:outline-none transition-colors duration-150 lg:hidden"
                    aria-label="Toggle sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page Title --}}
            <h1 class="text-base sm:text-lg font-semibold text-[#172B23]">{{ $pageTitle }}</h1>
        </div>

        {{-- Right: Notification Bell + User Avatar --}}
        <div class="flex items-center gap-3 sm:gap-4">

            {{-- Notification Bell --}}
            <div class="relative">
                <button type="button"
                        class="relative inline-flex items-center justify-center p-2 rounded-full text-[#52645B] hover:text-[#176B4D] hover:bg-[#E4F2E8] focus:outline-none transition-colors duration-150"
                        aria-label="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    {{-- Badge --}}
                    <span class="absolute top-1 right-1 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full leading-none">
                        2
                    </span>
                </button>
            </div>

            {{-- User Avatar + Name --}}
            <div class="flex items-center gap-2">
                {{-- Avatar Circle --}}
                <div class="w-8 h-8 rounded-full bg-[#E4F2E8] border-2 border-[#176B4D] flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-bold text-[#176B4D] leading-none">US</span>
                </div>
                {{-- User Name (hidden on very small screens) --}}
                <span class="hidden sm:block text-sm font-medium text-[#172B23]">Pengguna</span>
            </div>

        </div>
    </div>
</header>
