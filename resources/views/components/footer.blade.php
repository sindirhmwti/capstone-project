<footer id="hubungi-kami" class="bg-[#104C38] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Column 1: Brand --}}
            <div class="space-y-4">
                <div>
                    <span class="text-xl font-bold text-white">Temu</span><span class="text-xl font-bold text-[#20A66A]">Kerja</span>
                </div>
                <p class="text-sm text-gray-300 leading-relaxed">
                    Platform pencarian pekerja rumah tangga terpercaya untuk kebutuhan rumah tangga Anda.
                </p>
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} TemuKerja. Semua hak dilindungi.
                </p>
            </div>

            {{-- Column 2: Navigasi --}}
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Navigasi</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/') }}" class="text-sm text-gray-300 hover:text-white hover:underline transition-colors duration-150">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('workers.index') }}" class="text-sm text-gray-300 hover:text-white hover:underline transition-colors duration-150">
                            Katalog Pekerja
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#tentang-kami') }}" class="text-sm text-gray-300 hover:text-white hover:underline transition-colors duration-150">
                            Tentang Kami
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#cara-pemesanan') }}" class="text-sm text-gray-300 hover:text-white hover:underline transition-colors duration-150">
                            Cara Pemesanan
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 3: Informasi --}}
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Informasi</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/#tentang-kami') }}" class="text-sm text-gray-300 hover:text-white hover:underline transition-colors duration-150">
                            Tentang Kami
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#cara-pemesanan') }}" class="text-sm text-gray-300 hover:text-white hover:underline transition-colors duration-150">
                            Cara Pemesanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#layanan') }}" class="text-sm text-gray-300 hover:text-white hover:underline transition-colors duration-150">
                            Layanan Kami
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#pekerja-unggulan') }}" class="text-sm text-gray-300 hover:text-white hover:underline transition-colors duration-150">
                            Pekerja Unggulan
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 4: Kontak --}}
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Kontak</h3>
                <ul class="space-y-3">
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        {{-- Email icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#20A66A] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:info@temukerja.id" class="hover:text-white transition-colors duration-150">info@temukerja.id</a>
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        {{-- Phone icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#20A66A] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:+6281200000000" class="hover:text-white transition-colors duration-150">+62 812-0000-0000</a>
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-300">
                        {{-- Instagram icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#20A66A] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 3h10a4 4 0 014 4v10a4 4 0 01-4 4H7a4 4 0 01-4-4V7a4 4 0 014-4zm5 5a4 4 0 100 8 4 4 0 000-8zm4.5-.5a1 1 0 110 2 1 1 0 010-2z"/>
                        </svg>
                        <a href="https://instagram.com/temukerja.id" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-150">@temukerja.id</a>
                    </li>
                </ul>
            </div>

        </div>

        {{-- Divider --}}
        <div class="mt-10 border-t border-white/10 pt-6 text-center">
            <p class="text-xs text-gray-400">
                &copy; {{ date('Y') }} TemuKerja &mdash; Dibuat dengan ❤ untuk keluarga Indonesia.
            </p>
        </div>
    </div>
</footer>
