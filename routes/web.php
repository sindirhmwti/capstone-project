<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Worker\DashboardController as WorkerDashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Katalog Pekerja
Route::get('/workers', function () {
    return view('public.workers.index');
})->name('workers.index');

// Authentication - GET
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

Route::get('/register/select-role', [AuthController::class, 'showSelectRole'])
    ->name('register.select-role');

// Authentication - POST
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // TODO: Tambahkan middleware auth.admin
});


/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/

Route::prefix('client')->name('client.')->group(function () {

    Route::get('/dashboard', [ClientDashboardController::class, 'index'])
        ->name('dashboard');

    // TODO: Tambahkan middleware auth.client
});


/*
|--------------------------------------------------------------------------
| Worker Routes
|--------------------------------------------------------------------------
*/

Route::prefix('worker')->name('worker.')->group(function () {

    Route::get('/dashboard', [WorkerDashboardController::class, 'index'])
        ->name('dashboard');

    // TODO: Tambahkan middleware auth.worker
});
