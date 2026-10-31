{{--
    Sidebar dashboard bersama untuk Admin, Klien, dan Pekerja (sesuai mockup: latar putih, menu aktif hijau lembut).
    Parameter: $labelPeran, $menu = [[label, route, pola aktif, ikon, badge?], ...]
--}}
<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-line bg-white transition-transform duration-300 ease-out lg:translate-x-0 print:hidden"
       aria-label="Menu utama">

    {{-- Logo --}}
    <div class="flex h-16 shrink-0 items-center justify-between px-6">
        <x-logo />
        <button type="button" class="rounded-lg p-2 text-muted hover:bg-canvas hover:text-ink lg:hidden" data-sidebar-close aria-label="Tutup menu">
            <x-icon name="x" />
        </button>
    </div>

    {{-- Ruang kerja --}}
    <div class="px-4 pt-3">
        <p class="px-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-muted">Ruang Kerja</p>
        <div class="mt-2 flex items-center gap-3 rounded-xl border border-line bg-canvas px-3 py-2.5">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-soft text-primary"><x-icon name="briefcase" class="h-[18px] w-[18px]" /></span>
            <div class="leading-tight">
                <p class="text-xs text-muted">Anda masuk sebagai</p>
                <p class="text-sm font-semibold text-ink">{{ $labelPeran }}</p>
            </div>
        </div>
    </div>

    {{-- Menu --}}
    <p class="mt-6 px-6 text-[11px] font-semibold uppercase tracking-[0.14em] text-muted">Menu Utama</p>
    <nav class="mt-2 flex-1 space-y-1 overflow-y-auto px-4 pb-4">
        @foreach ($menu as $item)
            @php
                [$label, $namaRoute, $pola, $ikonKey] = $item;
                $badge = $item[4] ?? 0;
                $aktif = request()->routeIs(...(array) $pola);
            @endphp
            <a href="{{ route($namaRoute) }}"
               class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-150
                      {{ $aktif ? 'bg-primary-soft text-primary-dark' : 'text-muted hover:bg-canvas hover:text-ink' }}"
               @if ($aktif) aria-current="page" @endif>
                <x-icon :name="$ikonKey" class="h-5 w-5 {{ $aktif ? 'text-primary' : 'text-muted/80 group-hover:text-ink' }}" />
                <span class="flex-1">{{ $label }}</span>
                @if ($badge > 0)
                    <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1.5 text-[11px] font-bold text-white">{{ $badge > 9 ? '9+' : $badge }}</span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- Kartu bawah + keluar --}}
    <div class="shrink-0 space-y-3 border-t border-line p-4">
        <div class="rounded-xl bg-primary-soft p-4">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-primary"><x-icon name="check" class="h-4 w-4" /></span>
            <p class="mt-2 text-sm font-semibold text-primary-dark">Pekerja tepat, hasil hebat.</p>
            <p class="text-xs text-muted">Temukan tenaga berpengalaman untuk setiap kebutuhan Anda.</p>
        </div>
        <form method="POST" action="{{ route('logout') }}" data-confirm="Keluar dari akun?">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50">
                <x-icon name="log-out" />
                Keluar dari akun
            </button>
        </form>
    </div>
</aside>

{{-- Latar gelap saat menu dibuka di HP --}}
<div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-ink/40 opacity-0 transition-opacity duration-300 lg:hidden" data-sidebar-close aria-hidden="true"></div>
