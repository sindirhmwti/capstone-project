{{--
    Sidebar dashboard bersama untuk Admin, Klien, dan Pekerja.
    Parameter: $sidebarId, $beranda (route), $labelPeran, $menu = [[label, route, pola aktif, ikon path, badge?], ...]
--}}
@php
    $ikon = [
        'grid' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
        'home' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'search' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
        'list' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
        'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'shield' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        'chart' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'bell' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'id' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'heart' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
        'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'check' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        'doc' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
    ];
@endphp

<aside id="{{ $sidebarId }}"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-[#176B4D] text-white flex flex-col
              transform -translate-x-full transition-transform duration-300 ease-in-out
              lg:translate-x-0 lg:static lg:inset-auto lg:z-auto print:hidden">

    {{-- Logo --}}
    <div class="flex items-center justify-between h-16 px-5 border-b border-white/10 flex-shrink-0">
        <a href="{{ route($beranda) }}" class="flex items-center">
            <span class="text-xl font-bold text-white tracking-tight">TemuKerja</span>
        </a>
        <button id="sidebar-close" type="button" class="lg:hidden text-white/70 hover:text-white focus:outline-none" aria-label="Tutup sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Info peran --}}
    <div class="px-5 pt-4">
        <p class="text-[10px] uppercase tracking-widest text-white/50">Ruang Kerja</p>
        <div class="mt-2 flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2">
            <svg class="w-4 h-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <div class="leading-tight">
                <p class="text-[10px] text-white/60">Anda masuk sebagai</p>
                <p class="text-sm font-semibold">{{ $labelPeran }}</p>
            </div>
        </div>
        <p class="text-[10px] uppercase tracking-widest text-white/50 mt-5">Menu Utama</p>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-1">
        @foreach ($menu as $item)
            @php
                [$label, $namaRoute, $pola, $ikonKey] = $item;
                $badge = $item[4] ?? 0;
                $aktif = request()->routeIs($pola);
            @endphp
            <a href="{{ \Illuminate\Support\Facades\Route::has($namaRoute) ? route($namaRoute) : '#' }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ $aktif ? 'bg-[#104C38] text-white' : 'text-white/90 hover:bg-[#104C38] hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ikon[$ikonKey] ?? $ikon['doc'] }}"/>
                </svg>
                <span class="flex-1">{{ $label }}</span>
                @if ($badge > 0)
                    <span class="min-w-5 h-5 px-1.5 inline-flex items-center justify-center rounded-full bg-white text-[#176B4D] text-[11px] font-bold">{{ $badge > 9 ? '9+' : $badge }}</span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="flex-shrink-0 px-3 py-4 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-300 hover:bg-red-900/30 hover:text-red-200 transition-colors duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar dari akun
            </button>
        </form>
    </div>
</aside>

{{-- Overlay untuk layar kecil --}}
<div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/50 hidden lg:hidden" aria-hidden="true"></div>

<script>
    (function () {
        const toggle   = document.getElementById('sidebar-toggle');
        const sidebar  = document.getElementById(@json($sidebarId));
        const overlay  = document.getElementById('sidebar-overlay');
        const closeBtn = document.getElementById('sidebar-close');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        // Header dimuat setelah sidebar, jadi pasang event setelah DOM siap
        document.addEventListener('DOMContentLoaded', function () {
            const tombol = document.getElementById('sidebar-toggle');
            if (tombol) tombol.addEventListener('click', openSidebar);
        });
        if (toggle)   toggle.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay)  overlay.addEventListener('click', closeSidebar);
    })();
</script>
