<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek 1: Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }
        if (Auth::user()->role !== 'admin') {
            abort(403, 'AKSES DITOLAK: ANDA BUKAN ADMIN');
        }

        return $next($request);
    }
}