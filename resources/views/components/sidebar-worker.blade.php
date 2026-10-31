@include('components.sidebar', [
    'sidebarId' => 'sidebar-worker',
    'beranda' => 'worker.dashboard',
    'labelPeran' => 'Calon Pekerja',
    'menu' => [
        ['Beranda Pekerja', 'worker.dashboard', 'worker.dashboard', 'grid'],
        ['Profil Pekerja', 'worker.profile', 'worker.profile', 'id'],
        ['Edit Profil', 'worker.profile.edit', 'worker.profile.edit', 'user'],
        ['Kriteria Khusus', 'worker.criteria', 'worker.criteria*', 'check'],
        ['Status Verifikasi', 'worker.verification', 'worker.verification*', 'shield'],
        ['Notifikasi', 'worker.notifications', 'worker.notifications*', 'bell', auth()->user()->jumlahNotifikasiBelumDibaca()],
    ],
])
