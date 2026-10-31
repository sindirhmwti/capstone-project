{{--
    Navbar halaman publik. Parameter opsional $transparan (true di beranda): transparan di atas hero hijau,
    lalu berubah putih berbayang saat halaman di-scroll.
    Tamu melihat tombol Masuk + Daftar Sekarang; pengguna yang sudah login melihat menu profil.
--}}
@php
    $tautan = [
        'Beranda' => url('/'),
        'Layanan' => url('/#layanan'),
        'Cara Kerja' => url('/#cara-pemesanan'),
        'Tentang Kami' => url('/#tentang-kami'),
        'Katalog Pekerja' => route('workers.index'),
        'Hubungi Kami' => url('/#hubungi-kami'),
    ];
    $pengguna = auth()->user();
    if ($pengguna) {
        $routeProfil = $pengguna->isAdmin() ? 'admin.profile' : ($pengguna->isKlien() ? 'client.profile' : 'worker.profile');
        $labelPeran = $pengguna->isAdmin() ? 'Admin' : ($pengguna->isKlien() ? 'Klien' : 'Calon Pekerja');
        $namaSingkat = \Illuminate\Support\Str::limit(\Illuminate\Support\Str::before(trim($pengguna->nama) . ' ', ' '), 14, '…');
    }
@endphp

<header class="sticky top-0 z-50 border-b border-line bg-white/95 backdrop-blur" data-navbar @if (! empty($transparan)) data-transparan @endif>
    <nav class="container-page flex h-20 items-center justify-between gap-6 lg:h-24" aria-label="Navigasi utama">
        <x-logo size="h-11" />

        <div class="hidden items-center gap-1 xl:flex">
            @foreach ($tautan as $label => $href)
                <a href="{{ $href }}" class="nav-ubah whitespace-nowrap rounded-full px-3.5 py-2 text-[15px] font-semibold text-ink/80 transition-colors hover:bg-primary-soft hover:text-primary-dark 2xl:px-4 2xl:text-base">{{ $label }}</a>
            @endforeach
        </div>

        <div class="flex items-center gap-2">
            @auth
                {{-- Menu profil (desktop) --}}
                <details class="group relative hidden lg:block" data-menu-profil>
                    <summary class="nav-ubah flex cursor-pointer items-center gap-2.5 rounded-full border border-line/80 py-1.5 pl-1.5 pr-3.5 text-ink transition-colors hover:bg-primary-soft" aria-label="Menu akun">
                        <x-avatar :name="$pengguna->nama" size="sm" class="ring-0" />
                        <span class="max-w-36 truncate text-[15px] font-bold">{{ $namaSingkat }}</span>
                        <x-icon name="chevron-down" class="h-4 w-4 transition-transform duration-200 group-open:rotate-180" />
                    </summary>
                    <div class="animate-fade-up absolute right-0 mt-3 w-72 overflow-hidden rounded-2xl border border-line bg-white p-2 text-ink shadow-lift">
                        <div class="flex items-center gap-3 border-b border-line px-3 pb-3 pt-2">
                            <x-avatar :name="$pengguna->nama" size="md" />
                            <div class="min-w-0">
                                <p class="truncate font-bold">{{ $pengguna->nama }}</p>
                                <p class="truncate text-sm text-muted">{{ $labelPeran }} · {{ $pengguna->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route($pengguna->routeDashboard()) }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] font-semibold hover:bg-canvas"><x-icon name="grid" class="h-5 w-5 text-primary" /> Dashboard</a>
                        <a href="{{ route($routeProfil) }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] font-semibold hover:bg-canvas"><x-icon name="user" class="h-5 w-5 text-primary" /> Profil Saya</a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-line pt-1">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] font-semibold text-red-600 hover:bg-red-50"><x-icon name="log-out" class="h-5 w-5" /> Keluar</button>
                        </form>
                    </div>
                </details>
            @else
                <a href="{{ route('login') }}" class="nav-masuk btn-outline hidden lg:inline-flex">Masuk</a>
                <a href="{{ route('register') }}" class="nav-daftar btn-primary hidden lg:inline-flex">Daftar Sekarang</a>
            @endauth

            <button type="button" class="nav-ubah -mr-2 rounded-full p-2.5 text-ink transition-colors hover:bg-primary-soft xl:hidden" data-menu-toggle aria-controls="menu-publik" aria-expanded="false" aria-label="Buka menu">
                <x-icon name="menu" class="h-7 w-7" />
            </button>
        </div>
    </nav>
</header>

{{-- Panel menu HP / tablet: slide dari kanan --}}
<div id="menu-publik-latar" class="panel-latar panel-tutup fixed inset-0 z-[60] hidden bg-ink/50 backdrop-blur-sm xl:hidden" data-menu-close aria-hidden="true"></div>
<aside id="menu-publik" class="panel-menu panel-tutup fixed inset-y-0 right-0 z-[61] hidden w-[86%] max-w-sm overflow-y-auto bg-white shadow-lift xl:hidden" aria-label="Menu">
    <div class="flex h-20 items-center justify-between border-b border-line px-5">
        <x-logo size="h-10" />
        <button type="button" class="rounded-full p-2.5 text-muted hover:bg-canvas hover:text-ink" data-menu-close aria-label="Tutup menu">
            <x-icon name="x" class="h-6 w-6" />
        </button>
    </div>

    @auth
        <div class="flex items-center gap-3 border-b border-line bg-mint-soft px-5 py-4">
            <x-avatar :name="$pengguna->nama" size="md" />
            <div class="min-w-0">
                <p class="truncate font-bold text-ink">{{ $pengguna->nama }}</p>
                <p class="truncate text-sm text-muted">{{ $labelPeran }}</p>
            </div>
        </div>
    @endauth

    <nav class="space-y-1 p-4">
        @foreach ($tautan as $label => $href)
            <a href="{{ $href }}" class="flex items-center justify-between rounded-2xl px-4 py-3.5 text-lg font-semibold text-ink hover:bg-primary-soft hover:text-primary-dark">
                {{ $label }} <x-icon name="chevron-right" class="h-5 w-5 text-muted/60" />
            </a>
        @endforeach
    </nav>

    <div class="grid gap-3 border-t border-line p-5">
        @auth
            <a href="{{ route($pengguna->routeDashboard()) }}" class="btn-primary w-full"><x-icon name="grid" class="h-5 w-5" /> Dashboard</a>
            <a href="{{ route($routeProfil) }}" class="btn-secondary w-full"><x-icon name="user" class="h-5 w-5" /> Profil Saya</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-danger w-full"><x-icon name="log-out" class="h-5 w-5" /> Keluar</button>
            </form>
        @else
            <a href="{{ route('register') }}" class="btn-primary w-full">Daftar Sekarang</a>
            <a href="{{ route('login') }}" class="btn-outline w-full">Masuk</a>
        @endauth
    </div>
</aside>
