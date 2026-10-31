{{-- Footer publik: berlatar hijau sangat tua, kaya informasi --}}
<footer id="hubungi-kami" class="scroll-mt-20 bg-primary-deep text-white">
    <div class="container-page grid gap-12 py-16 lg:grid-cols-12 lg:py-20">
        <div class="lg:col-span-4">
            <x-logo variant="light" size="h-10" />
            <p class="mt-5 max-w-sm text-base leading-relaxed text-white/70">
                temukerja adalah layanan pencarian dan pemesanan ART, Baby Sitter, dan Perawat Lansia milik
                <strong class="font-semibold text-white">PT Shaka Abadi Nusantara</strong>. Kami menghubungkan kebutuhan keluarga dengan pekerja yang sudah diverifikasi.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="btn-sun btn-sm">Daftar Sekarang</a>
                <a href="{{ route('login') }}" class="btn-outline-light btn-sm">Masuk</a>
            </div>
        </div>

        @php
            $kolom = [
                'Layanan' => [
                    'Asisten Rumah Tangga' => route('workers.index', ['category' => 'ART']),
                    'Baby Sitter' => route('workers.index', ['category' => 'Baby Sitter']),
                    'Perawat Lansia' => route('workers.index', ['category' => 'Perawat Lansia']),
                    'Katalog Pekerja' => route('workers.index'),
                ],
                'Perusahaan' => [
                    'Tentang Kami' => url('/#tentang-kami'),
                    'Cara Kerja' => url('/#cara-pemesanan'),
                    'Kenapa temukerja' => url('/#keunggulan'),
                    'Daftar sebagai Pekerja' => route('register.pekerja'),
                ],
                'Bantuan' => [
                    'Pertanyaan Umum' => url('/#faq'),
                    'Masuk Akun' => route('login'),
                    'Daftar sebagai Klien' => route('register.klien'),
                ],
            ];
        @endphp

        <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-5">
            @foreach ($kolom as $judul => $isi)
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-[0.14em] text-mint">{{ $judul }}</h3>
                    <ul class="mt-5 space-y-3">
                        @foreach ($isi as $label => $href)
                            <li><a href="{{ $href }}" class="text-[15px] text-white/75 transition-colors hover:text-white">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="lg:col-span-3">
            <h3 class="text-sm font-bold uppercase tracking-[0.14em] text-mint">Kantor Kami</h3>
            <ul class="mt-5 space-y-4 text-[15px] text-white/75">
                <li class="flex gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-sun"><x-icon name="map-pin" /></span>
                    <span>Komplek P&amp;K Jl. Melur No. 183, Cipondoh, Kota Tangerang, Banten</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-sun"><x-icon name="building" /></span>
                    <span>PT Shaka Abadi Nusantara</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-sun"><x-icon name="phone" /></span>
                    <a href="tel:+6281200000000" class="hover:text-white">+62 812-0000-0000</a>
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 text-sun"><x-icon name="mail" /></span>
                    <a href="mailto:info@temukerja.id" class="hover:text-white">info@temukerja.id</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-page flex flex-col gap-2 py-6 text-sm text-white/60 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} PT Shaka Abadi Nusantara. Hak cipta dilindungi.</p>
            <p>temukerja — Menghubungkan kebutuhan dengan keahlian.</p>
        </div>
    </div>
</footer>
