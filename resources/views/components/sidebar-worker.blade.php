<aside id="sidebar-worker"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-[#176B4D] text-white flex flex-col
              transform -translate-x-full transition-transform duration-300 ease-in-out
              lg:translate-x-0 lg:static lg:inset-auto lg:z-auto">

    {{-- Sidebar Header / Logo --}}
    <div class="flex items-center justify-between h-16 px-5 border-b border-white/10 flex-shrink-0">
        <a href="{{ route('worker.dashboard') }}" class="flex items-center">
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

        {{-- Dashboard --}}
        <a href="{{ route('worker.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('worker.dashboard') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Dashboard
        </a>

        {{-- Profil Pekerja --}}
        <a href="{{ \Illuminate\Support\Facades\Route::has('worker.profile') ? route('worker.profile') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('worker.profile*') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Profil Pekerja
        </a>

        {{-- Status Verifikasi --}}
        <a href="{{ \Illuminate\Support\Facades\Route::has('worker.verification') ? route('worker.verification') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('worker.verification*') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Status Verifikasi
        </a>

        {{-- Dokumen Saya --}}
        <a href="{{ \Illuminate\Support\Facades\Route::has('worker.documents') ? route('worker.documents') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('worker.documents*') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            Dokumen Saya
        </a>

        {{-- Notifikasi --}}
        <a href="{{ \Illuminate\Support\Facades\Route::has('worker.notifications') ? route('worker.notifications') : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-white/90 hover:bg-[#104C38] hover:text-white transition-colors duration-150
                  {{ request()->routeIs('worker.notifications*') ? 'bg-[#104C38] text-white' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Notifikasi
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
        const sidebar  = document.getElementById('sidebar-worker');
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
