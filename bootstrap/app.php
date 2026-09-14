<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
        // 👇 2. MASUKIN KODE INI DI SINI 👇
        $exceptions->render(function (TokenMismatchException $e, $request) {
            return redirect()->back()->withInput()->with('error', 'Sesi Anda telah kedaluwarsa karena terlalu lama diam. Silakan coba login kembali.');
        });
        
    })->create();