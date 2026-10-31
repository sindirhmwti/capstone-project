{{-- Topbar dashboard: judul halaman, pencarian (klien & admin), notifikasi, dan menu profil --}}
@props(['role' => 'admin'])

@php
    $labelPeran = ['admin' => 'Admin', 'client' => 'Klien', 'worker' => 'Calon Pekerja'][$role] ?? '';
    $pengguna = auth()->user();
    $jumlahNotif = $pengguna?->jumlahNotifikasiBelumDibaca() ?? 0;
    $routeProfil = $role === 'worker' ? 'worker.profile.edit' : $role . '.profile';
    // Pencarian cepat diarahkan ke halaman pencarian yang sudah ada
    $cari = ['client' => [route('client.workers'), 'Cari pekerja berdasarkan nama atau lokasi...'], 'admin' => [route('admin.workers'), 'Cari akun pekerja...']][$role] ?? null;
@endphp

<header class="sticky top-0 z-30 border-b border-line bg-white/95 backdrop-blur print:hidden">
    <div class="flex h-20 items-center gap-3 px-4 sm:px-6 lg:px-10">
        <button type="button" class="-ml-1 rounded-xl p-2.5 text-ink hover:bg-canvas lg:hidden" data-sidebar-open aria-label="Buka menu" aria-controls="sidebar">
            <x-icon name="menu" class="h-6 w-6" />
        </button>

        <div class="min-w-0 flex-1">
            <nav class="hidden items-center gap-1.5 text-sm text-muted sm:flex" aria-label="Breadcrumb">
                <a href="{{ route($pengguna->routeDashboard()) }}" class="hover:text-primary">{{ $labelPeran }}</a>
                <x-icon name="chevron-right" class="h-4 w-4 text-muted/50" />
                <span class="truncate">@yield('title', 'Dashboard')</span>
            </nav>
            <p class="truncate text-lg font-extrabold text-ink sm:text-xl">@yield('title', 'Dashboard')</p>
        </div>

        @if ($cari)
            <form action="{{ $cari[0] }}" method="GET" class="hidden w-full max-w-sm xl:block" role="search">
                <label class="flex items-center gap-2.5 rounded-full border border-line bg-canvas px-4 transition focus-within:border-primary focus-within:bg-white focus-within:ring-4 focus-within:ring-primary/15">
                    <x-icon name="search" class="h-5 w-5 text-muted" />
                    <span class="sr-only">Pencarian</span>
                    <input type="search" name="cari" value="{{ request('cari') }}" placeholder="{{ $cari[1] }}" class="w-full bg-transparent py-3 text-[15px] outline-none placeholder:text-gray-400">
                </label>
            </form>
        @endif

        <div class="flex items-center gap-1 sm:gap-2">
            <a href="{{ route($role . '.notifications') }}"
               class="relative flex h-11 w-11 items-center justify-center rounded-full text-muted transition-colors hover:bg-primary-soft hover:text-primary"
               aria-label="Notifikasi{{ $jumlahNotif ? ', ' . $jumlahNotif . ' belum dibaca' : '' }}" data-tip="Notifikasi">
                <x-icon name="bell" class="h-6 w-6" />
                @if ($jumlahNotif > 0)
                    <span class="absolute right-1 top-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[11px] font-bold text-white ring-2 ring-white animate-denyut">
                        {{ $jumlahNotif > 9 ? '9+' : $jumlahNotif }}
                    </span>
                @endif
            </a>

            <details class="group relative">
                <summary class="flex cursor-pointer items-center gap-2.5 rounded-full p-1 pr-2 transition-colors hover:bg-canvas sm:pr-3" aria-label="Menu profil">
                    <x-avatar :name="$pengguna?->nama ?? 'Pengguna'" size="md" />
                    <span class="hidden leading-tight md:block">
                        <span class="block max-w-40 truncate text-[15px] font-bold text-ink">{{ $pengguna?->nama }}</span>
                        <span class="block text-xs font-medium text-muted">{{ $labelPeran }}</span>
                    </span>
                    <x-icon name="chevron-down" class="hidden h-4 w-4 text-muted transition-transform group-open:rotate-180 md:block" />
                </summary>
                <div class="animate-fade-up absolute right-0 mt-2 w-64 overflow-hidden rounded-2xl border border-line bg-white p-2 shadow-lift">
                    <div class="border-b border-line px-3 pb-3 pt-2">
                        <p class="truncate font-bold text-ink">{{ $pengguna?->nama }}</p>
                        <p class="truncate text-sm text-muted">{{ $pengguna?->email }}</p>
                    </div>
                    <a href="{{ route($routeProfil) }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] font-medium text-ink hover:bg-canvas"><x-icon name="user" class="h-5 w-5 text-muted" /> Edit profil</a>
                    <a href="{{ route($role . '.notifications') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] font-medium text-ink hover:bg-canvas"><x-icon name="bell" class="h-5 w-5 text-muted" /> Notifikasi</a>
                    <form method="POST" action="{{ route('logout') }}" data-confirm="Keluar dari akun Anda?">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-[15px] font-semibold text-red-600 hover:bg-red-50"><x-icon name="log-out" class="h-5 w-5" /> Keluar</button>
                    </form>
                </div>
            </details>
        </div>
    </div>
</header>
