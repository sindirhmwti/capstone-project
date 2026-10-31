@php
    $tautan = [
        'Beranda' => url('/'),
        'Layanan' => url('/#layanan'),
        'Cara Kerja' => url('/#cara-pemesanan'),
        'Tentang Kami' => url('/#tentang-kami'),
        'Katalog Pekerja' => route('workers.index'),
        'Hubungi Kami' => url('/#hubungi-kami'),
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-line/70 bg-white/90 backdrop-blur">
    <nav class="container-page flex h-[72px] items-center justify-between gap-6" aria-label="Navigasi utama">
        <x-logo />

        <div class="hidden items-center gap-1 lg:flex">
            @foreach ($tautan as $label => $href)
                <a href="{{ $href }}" class="rounded-lg px-3 py-2 text-sm font-medium text-muted transition-colors hover:bg-primary-soft hover:text-primary-dark">{{ $label }}</a>
            @endforeach
        </div>

        <div class="hidden items-center gap-2 lg:flex">
            @auth
                <a href="{{ route(auth()->user()->routeDashboard()) }}" class="btn-primary">
                    <x-icon name="grid" class="h-4 w-4" /> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar Sekarang</a>
            @endauth
        </div>

        <button type="button" class="-mr-2 rounded-lg p-2 text-ink hover:bg-primary-soft lg:hidden" data-menu-toggle aria-controls="menu-publik" aria-expanded="false" aria-label="Buka menu">
            <x-icon name="menu" class="h-6 w-6" data-ikon-buka />
            <x-icon name="x" class="hidden h-6 w-6" data-ikon-tutup />
        </button>
    </nav>

    {{-- Menu HP --}}
    <div id="menu-publik" class="hidden border-t border-line bg-white lg:hidden">
        <div class="container-page space-y-1 py-4">
            @foreach ($tautan as $label => $href)
                <a href="{{ $href }}" class="block rounded-xl px-3 py-3 text-base font-medium text-ink hover:bg-primary-soft">{{ $label }}</a>
            @endforeach
            <div class="grid grid-cols-2 gap-2 border-t border-line pt-4">
                @auth
                    <a href="{{ route(auth()->user()->routeDashboard()) }}" class="btn-primary col-span-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</header>
