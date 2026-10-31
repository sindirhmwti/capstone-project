@include('components.sidebar', [
    'labelPeran' => 'Calon Pekerja',
    'menu' => [
        ['Beranda Pekerja', 'worker.dashboard', 'worker.dashboard', 'grid'],
        ['Profil Pekerja', 'worker.profile', 'worker.profile', 'id-card'],
        ['Edit Profil', 'worker.profile.edit', 'worker.profile.edit', 'user'],
        ['Kriteria Khusus', 'worker.criteria', 'worker.criteria*', 'list-checks'],
        ['Status Verifikasi', 'worker.verification', 'worker.verification*', 'shield-check'],
        ['Notifikasi', 'worker.notifications', 'worker.notifications*', 'bell', auth()->user()->jumlahNotifikasiBelumDibaca()],
    ],
])
