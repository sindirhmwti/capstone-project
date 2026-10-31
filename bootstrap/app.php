<?php

use App\Http\Middleware\CekPeran;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'peran' => CekPeran::class,
        ]);

        // Tamu yang membuka halaman terproteksi diarahkan ke halaman login
        $middleware->redirectGuestsTo(fn () => route('login'));

        // Pengguna yang sudah login dan membuka halaman tamu diarahkan ke dashboard sesuai peran
        $middleware->redirectUsersTo(fn (Request $request) => route($request->user()->routeDashboard()));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
