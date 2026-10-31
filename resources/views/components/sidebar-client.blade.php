@include('components.sidebar', [
    'labelPeran' => 'Klien',
    'menu' => [
        ['Beranda', 'client.dashboard', 'client.dashboard', 'grid'],
        ['Katalog Pekerja', 'client.workers', ['client.workers*', 'client.orders.create'], 'search'],
        ['Favorit Saya', 'client.favorites', 'client.favorites*', 'heart'],
        ['Riwayat Pesanan', 'client.orders', ['client.orders', 'client.orders.show'], 'clock'],
        ['Notifikasi', 'client.notifications', 'client.notifications*', 'bell', auth()->user()->jumlahNotifikasiBelumDibaca()],
        ['Edit Profil', 'client.profile', 'client.profile*', 'user'],
    ],
])
