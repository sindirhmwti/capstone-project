<nav class="sticky top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <div class="flex-shrink-0">
                <a href="{{ url('/') }}" class="flex items-center">
                    <span class="text-xl font-bold text-[#176B4D]">Temu</span><span class="text-xl font-bold text-[#172B23]">kerja</span>
                </a>
            </div>

            {{-- Center Nav Links (Desktop) --}}
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ url('/') }}" class="text-sm font-medium text-[#172B23] hover:text-[#176B4D] transition-colors duration-150">Beranda</a>
                <a href="{{ url('/#tentang-kami') }}" class="text-sm font-medium text-[#172B23] hover:text-[#176B4D] transition-colors duration-150">Tentang Kami</a>
                <a href="{{ url('/#cara-pemesanan') }}" class="text-sm font-medium text-[#172B23] hover:text-[#176B4D] transition-colors duration-150">Cara Pemesanan</a>
                <a href="{{ route('workers.index') }}" class="text-sm font-medium text-[#172B23] hover:text-[#176B4D] transition-colors duration-150">Katalog Pekerja</a>
                <a href="{{ url('/#hubungi-kami') }}" class="text-sm font-medium text-[#172B23] hover:text-[#176B4D] transition-colors duration-150">Hubungi Kami</a>
            </div>

            {{-- Right Buttons (Desktop) --}}
            <div class="hidden md:flex items-center space-x-3">
                @auth
                    <a href="{{ route(auth()->user()->routeDashboard()) }}"
                       class="inline-flex items-center px-4 py-2 bg-[#176B4D] text-white text-sm font-medium rounded-lg hover:bg-[#104C38] transition-colors duration-150">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-4 py-2 border border-[#176B4D] text-[#176B4D] text-sm font-medium rounded-lg hover:bg-[#E4F2E8] transition-colors duration-150">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-4 py-2 bg-[#176B4D] text-white text-sm font-medium rounded-lg hover:bg-[#104C38] transition-colors duration-150">
                        Daftar Sekarang
                    </a>
                @endauth
            </div>

            {{-- Mobile Hamburger --}}
            <div class="flex md:hidden">
                <button id="mobile-menu-toggle"
                        type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-[#172B23] hover:text-[#176B4D] hover:bg-[#E4F2E8] focus:outline-none transition-colors duration-150"
                        aria-controls="mobile-menu"
                        aria-expanded="false">
                    <span class="sr-only">Buka menu</span>
                    {{-- Hamburger Icon --}}
                    <svg id="hamburger-icon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    {{-- Close Icon --}}
                    <svg id="close-icon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
        <div class="px-4 pt-3 pb-4 space-y-1">
            <a href="{{ url('/') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium text-[#172B23] hover:text-[#176B4D] hover:bg-[#E4F2E8] transition-colors duration-150">
                Beranda
            </a>
            <a href="{{ url('/#tentang-kami') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium text-[#172B23] hover:text-[#176B4D] hover:bg-[#E4F2E8] transition-colors duration-150">
                Tentang Kami
            </a>
            <a href="{{ url('/#cara-pemesanan') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium text-[#172B23] hover:text-[#176B4D] hover:bg-[#E4F2E8] transition-colors duration-150">
                Cara Pemesanan
            </a>
            <a href="{{ route('workers.index') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium text-[#172B23] hover:text-[#176B4D] hover:bg-[#E4F2E8] transition-colors duration-150">
                Katalog Pekerja
            </a>
            <a href="{{ url('/#hubungi-kami') }}"
               class="block px-3 py-2 rounded-md text-sm font-medium text-[#172B23] hover:text-[#176B4D] hover:bg-[#E4F2E8] transition-colors duration-150">
                Hubungi Kami
            </a>
        </div>
        <div class="px-4 pb-4 flex flex-col space-y-2 border-t border-gray-100 pt-3">
            @auth
                <a href="{{ route(auth()->user()->routeDashboard()) }}"
                   class="w-full text-center px-4 py-2 bg-[#176B4D] text-white text-sm font-medium rounded-lg hover:bg-[#104C38] transition-colors duration-150">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="w-full text-center px-4 py-2 border border-[#176B4D] text-[#176B4D] text-sm font-medium rounded-lg hover:bg-[#E4F2E8] transition-colors duration-150">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="w-full text-center px-4 py-2 bg-[#176B4D] text-white text-sm font-medium rounded-lg hover:bg-[#104C38] transition-colors duration-150">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </div>
</nav>

<script>
    (function () {
        const toggle = document.getElementById('mobile-menu-toggle');
        const menu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');

        if (toggle && menu) {
            toggle.addEventListener('click', function () {
                const isOpen = !menu.classList.contains('hidden');

                if (isOpen) {
                    menu.classList.add('hidden');
                    hamburgerIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                } else {
                    menu.classList.remove('hidden');
                    hamburgerIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });
        }
    })();
</script>
