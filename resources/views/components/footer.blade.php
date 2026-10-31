<footer id="hubungi-kami" class="bg-primary-darker text-white">
    <div class="container-page grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <x-logo variant="light" />
            <p class="mt-4 max-w-xs text-sm leading-relaxed text-white/70">
                Platform pencocokan ART, Baby Sitter, dan Perawat Lansia milik PT Shaka Abadi Nusantara.
                Menghubungkan kebutuhan keluarga dengan keahlian yang tepat.
            </p>
        </div>

        <div class="lg:col-span-2">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Navigasi</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                @foreach (['Beranda' => url('/'), 'Layanan' => url('/#layanan'), 'Cara Kerja' => url('/#cara-pemesanan'), 'Katalog Pekerja' => route('workers.index')] as $label => $href)
                    <li><a href="{{ $href }}" class="text-white/70 transition-colors hover:text-white">{{ $label }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="lg:col-span-2">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Akun</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                <li><a href="{{ route('login') }}" class="text-white/70 transition-colors hover:text-white">Masuk</a></li>
                <li><a href="{{ route('register') }}" class="text-white/70 transition-colors hover:text-white">Daftar sebagai Klien</a></li>
                <li><a href="{{ route('register') }}" class="text-white/70 transition-colors hover:text-white">Daftar sebagai Pekerja</a></li>
            </ul>
        </div>

        <div class="lg:col-span-4">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-white">Hubungi Kami</h3>
            <ul class="mt-4 space-y-3 text-sm text-white/70">
                <li class="flex gap-3"><x-icon name="map-pin" class="mt-0.5 h-5 w-5 text-mint" /> Komplek P&amp;K Jl. Melur No. 183, Cipondoh Indah, Kota Tangerang, Banten</li>
                <li class="flex gap-3"><x-icon name="phone" class="h-5 w-5 text-mint" /> <a href="tel:+6281200000000" class="hover:text-white">+62 812-0000-0000</a></li>
                <li class="flex gap-3"><x-icon name="mail" class="h-5 w-5 text-mint" /> <a href="mailto:info@temukerja.id" class="hover:text-white">info@temukerja.id</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="container-page flex flex-col gap-2 py-5 text-xs text-white/55 sm:flex-row sm:justify-between">
            <span>&copy; {{ date('Y') }} PT Shaka Abadi Nusantara. Semua hak dilindungi.</span>
            <span>Temukerja — Kebutuhan bertemu keahlian.</span>
        </div>
    </div>
</footer>
