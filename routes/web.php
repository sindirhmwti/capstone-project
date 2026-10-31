<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PekerjaController as AdminPekerjaController;
use App\Http\Controllers\Admin\PemesananController as AdminPemesananController;
use App\Http\Controllers\Admin\VerifikasiController as AdminVerifikasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\FavoritController as ClientFavoritController;
use App\Http\Controllers\Client\KatalogController as ClientKatalogController;
use App\Http\Controllers\Client\PemesananController as ClientPemesananController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfilAkunController;
use App\Http\Controllers\Worker\DashboardController as WorkerDashboardController;
use App\Http\Controllers\Worker\KriteriaController as WorkerKriteriaController;
use App\Http\Controllers\Worker\ProfilController as WorkerProfilController;
use App\Http\Controllers\Worker\VerifikasiController as WorkerVerifikasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Katalog pekerja hanya untuk klien yang sudah login (tamu diarahkan ke login)
Route::get('/workers', [HomeController::class, 'katalog'])->name('workers.index');

/*
|--------------------------------------------------------------------------
| Autentikasi (khusus tamu)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registrasi hanya untuk Klien dan Calon Pekerja (admin dibuat lewat seeder / database)
    Route::get('/register', [AuthController::class, 'showSelectRole'])->name('register');
    Route::get('/register/klien', [AuthController::class, 'showRegisterKlien'])->name('register.klien');
    Route::get('/register/pekerja', [AuthController::class, 'showSelectCategory'])->name('register.pekerja');
    Route::get('/register/pekerja/{kategori}', [AuthController::class, 'showRegisterPekerja'])
        ->whereIn('kategori', ['art', 'baby_sitter', 'perawat_lansia'])
        ->name('register.pekerja.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Berkas dokumen pekerja (hanya admin dan pemilik dokumen)
Route::get('/dokumen/{dokumen}', [DokumenController::class, 'show'])->middleware('auth')->name('dokumen.show');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'peran:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Katalog & kelola akun pekerja
    Route::get('/catalog', [AdminPekerjaController::class, 'katalog'])->name('catalog');
    Route::get('/workers', [AdminPekerjaController::class, 'index'])->name('workers');
    Route::get('/workers/{pekerja}', [AdminPekerjaController::class, 'show'])->name('workers.show');
    Route::put('/workers/{pekerja}', [AdminPekerjaController::class, 'update'])->name('workers.update');
    Route::patch('/workers/{pekerja}/status', [AdminPekerjaController::class, 'ubahStatus'])->name('workers.status');

    // Verifikasi data & dokumen pekerja
    Route::get('/verification', [AdminVerifikasiController::class, 'index'])->name('verification');
    Route::get('/verification/{pekerja}', [AdminVerifikasiController::class, 'show'])->name('verification.show');
    Route::post('/verification/{pekerja}', [AdminVerifikasiController::class, 'proses'])->name('verification.process');

    // Kelola pemesanan dari klien
    Route::get('/orders', [AdminPemesananController::class, 'index'])->name('orders');
    Route::get('/orders/{pemesanan}', [AdminPemesananController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{pemesanan}/status', [AdminPemesananController::class, 'updateStatus'])->name('orders.status');

    // Laporan penyaluran
    Route::get('/reports', [AdminLaporanController::class, 'index'])->name('reports');
    Route::get('/reports/print', [AdminLaporanController::class, 'cetak'])->name('reports.print');

    // Notifikasi
    Route::get('/notifications', [NotifikasiController::class, 'index'])->name('notifications');
    Route::patch('/notifications/read-all', [NotifikasiController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('/notifications/{notifikasi}', [NotifikasiController::class, 'read'])->name('notifications.read');

    // Edit profil akun
    Route::get('/profile', [ProfilAkunController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfilAkunController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Klien
|--------------------------------------------------------------------------
*/
Route::prefix('client')->name('client.')->middleware(['auth', 'peran:klien'])->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    // Katalog, filter, dan rekomendasi pekerja
    Route::get('/workers', [ClientKatalogController::class, 'index'])->name('workers');
    Route::get('/workers/{pekerja}', [ClientKatalogController::class, 'show'])->name('workers.show');

    // Favorit
    Route::get('/favorites', [ClientFavoritController::class, 'index'])->name('favorites');
    Route::post('/favorites/{pekerja}', [ClientFavoritController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{pekerja}', [ClientFavoritController::class, 'destroy'])->name('favorites.destroy');

    // Pemesanan
    Route::get('/workers/{pekerja}/order', [ClientPemesananController::class, 'create'])->name('orders.create');
    Route::post('/workers/{pekerja}/order', [ClientPemesananController::class, 'store'])->name('orders.store');
    Route::get('/orders', [ClientPemesananController::class, 'index'])->name('orders');
    Route::get('/orders/{pemesanan}', [ClientPemesananController::class, 'show'])->name('orders.show');
    Route::post('/orders/{pemesanan}/cancel', [ClientPemesananController::class, 'cancel'])->name('orders.cancel');

    // Notifikasi
    Route::get('/notifications', [NotifikasiController::class, 'index'])->name('notifications');
    Route::patch('/notifications/read-all', [NotifikasiController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('/notifications/{notifikasi}', [NotifikasiController::class, 'read'])->name('notifications.read');

    // Edit profil akun
    Route::get('/profile', [ProfilAkunController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfilAkunController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Calon Pekerja
|--------------------------------------------------------------------------
*/
Route::prefix('worker')->name('worker.')->middleware(['auth', 'peran:pekerja'])->group(function () {
    Route::get('/dashboard', [WorkerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [WorkerProfilController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [WorkerProfilController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [WorkerProfilController::class, 'update'])->name('profile.update');

    Route::get('/criteria', [WorkerKriteriaController::class, 'edit'])->name('criteria');
    Route::put('/criteria', [WorkerKriteriaController::class, 'update'])->name('criteria.update');

    Route::get('/verification', [WorkerVerifikasiController::class, 'index'])->name('verification');
    Route::post('/verification/documents', [WorkerVerifikasiController::class, 'unggah'])->name('verification.upload');
    Route::post('/verification/submit', [WorkerVerifikasiController::class, 'ajukan'])->name('verification.submit');

    // Notifikasi
    Route::get('/notifications', [NotifikasiController::class, 'index'])->name('notifications');
    Route::patch('/notifications/read-all', [NotifikasiController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('/notifications/{notifikasi}', [NotifikasiController::class, 'read'])->name('notifications.read');
});
