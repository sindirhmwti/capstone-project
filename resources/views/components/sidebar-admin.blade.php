@include('components.sidebar', [
    'sidebarId' => 'sidebar-admin',
    'beranda' => 'admin.dashboard',
    'labelPeran' => 'Admin',
    'menu' => [
        ['Dashboard Admin', 'admin.dashboard', 'admin.dashboard', 'grid'],
        ['Katalog Pekerja', 'admin.catalog', 'admin.catalog*', 'list'],
        ['Kelola Akun Pekerja', 'admin.workers', 'admin.workers*', 'users'],
        ['Verifikasi Pekerja', 'admin.verification', 'admin.verification*', 'shield'],
        ['Kelola Pemesanan', 'admin.orders', 'admin.orders*', 'clipboard'],
        ['Laporan Penyaluran', 'admin.reports', 'admin.reports*', 'chart'],
        ['Notifikasi', 'admin.notifications', 'admin.notifications*', 'bell', auth()->user()->jumlahNotifikasiBelumDibaca()],
        ['Edit Profil', 'admin.profile', 'admin.profile*', 'user'],
    ],
])
