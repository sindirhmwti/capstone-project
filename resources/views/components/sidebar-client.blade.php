@include('components.sidebar', [
    'sidebarId' => 'sidebar-client',
    'beranda' => 'client.dashboard',
    'labelPeran' => 'Klien',
    'menu' => [
        ['Beranda', 'client.dashboard', 'client.dashboard', 'grid'],
        ['Katalog Pekerja', 'client.workers', 'client.workers*', 'search'],
        ['Favorit Saya', 'client.favorites', 'client.favorites*', 'heart'],
        ['Riwayat Pesanan', 'client.orders', 'client.orders*', 'clock'],
        ['Notifikasi', 'client.notifications', 'client.notifications*', 'bell', auth()->user()->jumlahNotifikasiBelumDibaca()],
        ['Edit Profil', 'client.profile', 'client.profile*', 'user'],
    ],
])
