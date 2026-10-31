<aside id="sidebar-client"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-[#176B4D] text-white flex flex-col
              transform -translate-x-full transition-transform duration-300 ease-in-out
              lg:translate-x-0 lg:static lg:inset-auto lg:z-auto">

    {{-- Sidebar Header / Logo --}}
    <div class="flex items-center justify-between h-16 px-5 border-b border-white/10 flex-shrink-0">
        <a href="{{ route('client.dashboard') }}" class="flex items-center">
            <span class="text-xl font-bold text-white tracking-tight">TemuKerja</span>
        </a>
        {{-- Close button (mobile only) --}}
        <button id="sidebar-close"
                type="button"
                class="lg:hidden text-white/70 hover:text-white focus:outline-none"
                aria-label="Tutup sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

        {{-- Beranda --}}
        <a href="{{ route('client.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('client.dashboard') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Beranda
        </a>

        {{-- Katalog Pekerja --}}
        <a href="{{ route('workers.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('client.catalog*') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Katalog Pekerja
        </a>

        {{-- Favorit Saya --}}
        <a href="{{ \Illuminate\Support\Facades\Route::has('client.favorites') ? route('client.favorites') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('client.favorites*') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Favorit Saya
        </a>

        {{-- Riwayat Pesanan --}}
        <a href="{{ \Illuminate\Support\Facades\Route::has('client.orders') ? route('client.orders') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('client.orders*') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Riwayat Pesanan
        </a>

        {{-- Notifikasi --}}
        <a href="{{ \Illuminate\Support\Facades\Route::has('client.notifications') ? route('client.notifications') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('client.notifications*') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Notifikasi
        </a>

        {{-- Edit Profil --}}
        <a href="{{ \Illuminate\Support\Facades\Route::has('client.profile') ? route('client.profile') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('client.profile*') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Edit Profil
        </a>

    </nav>

    {{-- Bottom: Logout --}}
    <div class="flex-shrink-0 px-3 py-4 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-300 hover:bg-red-900/30 hover:text-red-200 transition-colors duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- Overlay for mobile --}}
<div id="sidebar-overlay"
     class="fixed inset-0 z-40 bg-black/50 hidden lg:hidden"
     aria-hidden="true"></div>

<script>
    (function () {
        const toggle   = document.getElementById('sidebar-toggle');
        const sidebar  = document.getElementById('sidebar-client');
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

        if (toggle)   toggle.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay)  overlay.addEventListener('click', closeSidebar);
    })();
</script>
