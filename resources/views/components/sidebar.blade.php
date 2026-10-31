{{--
    Sidebar dashboard bersama untuk Admin, Klien, dan Pekerja: latar hijau tua, menu aktif jelas,
    profil pengguna di bawah. Drawer di HP, bisa diciutkan di desktop.
    Parameter: $labelPeran, $menu = [[label, route, pola aktif, ikon, badge?], ...]
--}}
@php $pengguna = auth()->user(); @endphp

<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-primary-darker text-white transition-transform duration-300 ease-out lg:translate-x-0 print:hidden"
       aria-label="Menu utama">
    <div class="bg-grid-light pointer-events-none absolute inset-0 opacity-60"></div>

    {{-- Logo + tombol ciutkan --}}
    <div class="relative flex h-20 shrink-0 items-center justify-between gap-2 px-5">
        <span data-label><x-logo variant="light" size="h-10" /></span>
        <span class="hidden [.sidebar-ciut_&]:lg:inline-flex"><x-logo variant="light" :text="false" size="h-10" /></span>
        <button type="button" class="hidden rounded-xl p-2 text-white/70 transition-colors hover:bg-white/10 hover:text-white lg:inline-flex [.sidebar-ciut_&]:lg:hidden" data-sidebar-collapse aria-label="Ciutkan menu" title="Ciutkan menu">
            <x-icon name="panel-left" class="h-5 w-5" />
        </button>
        <button type="button" class="rounded-xl p-2 text-white/70 hover:bg-white/10 hover:text-white lg:hidden" data-sidebar-close aria-label="Tutup menu">
            <x-icon name="x" class="h-6 w-6" />
        </button>
    </div>

    {{-- Menu --}}
    <p class="relative mt-2 px-7 text-[11px] font-bold uppercase tracking-[0.16em] text-mint/80" data-label>Menu {{ $labelPeran }}</p>
    <nav class="relative mt-3 min-h-0 flex-1 space-y-1 overflow-y-auto px-4 pb-4 scrollbar-hide">
        @foreach ($menu as $item)
            @php
                [$label, $namaRoute, $pola, $ikonKey] = $item;
                $badge = $item[4] ?? 0;
                $aktif = request()->routeIs(...(array) $pola);
            @endphp
            <a href="{{ route($namaRoute) }}" title="{{ $label }}" data-nav-link
               class="group relative flex items-center gap-3.5 rounded-2xl px-4 py-3 text-[15px] font-semibold transition-colors duration-150
                      {{ $aktif ? 'bg-white text-primary-darker shadow-lift' : 'text-white/75 hover:bg-white/10 hover:text-white' }}"
               @if ($aktif) aria-current="page" @endif>
                <x-icon :name="$ikonKey" class="h-[22px] w-[22px] {{ $aktif ? 'text-primary' : 'text-white/60 group-hover:text-white' }}" />
                <span class="flex-1 truncate" data-label>{{ $label }}</span>
                @if ($badge > 0)
                    <span data-nav-badge class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-sun px-1.5 text-xs font-extrabold text-ink">{{ $badge > 9 ? '9+' : $badge }}</span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- Profil pengguna + keluar --}}
    <div class="relative shrink-0 border-t border-white/10 p-4">
        <button type="button" class="mb-2 hidden w-full items-center justify-center rounded-xl p-2 text-white/70 hover:bg-white/10 hover:text-white [.sidebar-ciut_&]:lg:flex" data-sidebar-collapse aria-label="Lebarkan menu" title="Lebarkan menu">
            <x-icon name="panel-left" class="h-5 w-5 rotate-180" />
        </button>
        <div class="flex items-center gap-3 rounded-2xl bg-white/[0.07] p-3 [.sidebar-ciut_&]:lg:justify-center [.sidebar-ciut_&]:lg:bg-transparent [.sidebar-ciut_&]:lg:p-0">
            <x-avatar :name="$pengguna?->nama ?? 'Pengguna'" size="md" class="ring-white/20" />
            <div class="min-w-0 flex-1 leading-tight" data-label>
                <p class="truncate text-[15px] font-bold text-white">{{ $pengguna?->nama }}</p>
                <p class="mt-0.5 truncate text-xs text-white/60">{{ $pengguna?->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" data-confirm="Keluar dari akun Anda?" data-label>
                @csrf
                <button type="submit" class="rounded-xl p-2 text-white/70 transition-colors hover:bg-red-500/20 hover:text-red-200" data-tip="Keluar" aria-label="Keluar dari akun">
                    <x-icon name="log-out" class="h-5 w-5" />
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Latar gelap saat menu dibuka di HP --}}
<div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-ink/50 opacity-0 backdrop-blur-sm transition-opacity duration-300 lg:hidden" data-sidebar-close aria-hidden="true"></div>
