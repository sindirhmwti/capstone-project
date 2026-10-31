@props(['role' => 'admin'])

@php
    $labelPeran = ['admin' => 'Admin', 'client' => 'Klien', 'worker' => 'Calon Pekerja'][$role] ?? '';
    $pengguna = auth()->user();
    $jumlahNotif = $pengguna?->jumlahNotifikasiBelumDibaca() ?? 0;
    $routeProfil = $role === 'worker' ? 'worker.profile' : $role . '.profile';
@endphp

<header class="sticky top-0 z-30 flex h-16 items-center border-b border-line bg-white/90 backdrop-blur print:hidden">
    <div class="flex w-full items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">

        <div class="flex min-w-0 items-center gap-2">
            <button type="button" class="-ml-2 rounded-lg p-2 text-muted hover:bg-canvas hover:text-ink lg:hidden" data-sidebar-open aria-label="Buka menu" aria-controls="sidebar">
                <x-icon name="menu" class="h-6 w-6" />
            </button>
            <x-logo :text="false" size="h-8" class="lg:hidden" />
            <nav class="hidden min-w-0 items-center gap-2 text-sm sm:flex" aria-label="Breadcrumb">
                <a href="{{ route(auth()->user()->routeDashboard()) }}" class="text-muted hover:text-primary">Ruang kerja</a>
                <x-icon name="chevron-right" class="h-4 w-4 text-muted/60" />
                <span class="truncate font-semibold text-ink">@yield('title', 'Dashboard')</span>
            </nav>
        </div>

        <div class="flex items-center gap-1 sm:gap-3">
            <a href="{{ route($role . '.notifications') }}"
               class="relative rounded-full p-2.5 text-muted transition-colors hover:bg-primary-soft hover:text-primary"
               aria-label="Notifikasi{{ $jumlahNotif ? ', ' . $jumlahNotif . ' belum dibaca' : '' }}">
                <x-icon name="bell" />
                @if ($jumlahNotif > 0)
                    <span class="absolute right-1 top-1 inline-flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                        {{ $jumlahNotif > 9 ? '9+' : $jumlahNotif }}
                    </span>
                @endif
            </a>

            <span class="hidden h-8 w-px bg-line sm:block"></span>

            <a href="{{ route($routeProfil) }}" class="flex items-center gap-2.5 rounded-xl p-1 pr-2 transition-colors hover:bg-canvas" title="Profil saya">
                <x-avatar :name="$pengguna?->nama ?? 'Pengguna'" size="sm" />
                <span class="hidden leading-tight sm:block">
                    <span class="block max-w-40 truncate text-sm font-semibold text-ink">{{ $pengguna?->nama }}</span>
                    <span class="block text-xs text-muted">{{ $labelPeran }}</span>
                </span>
            </a>
        </div>
    </div>
</header>
