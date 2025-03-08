<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if (!Auth::guard($guard)->check()) {
            // Jika user mencoba akses /karyawan/* dan belum login, redirect ke login karyawan
            if ($guard === 'pegawai') {
                return redirect()->route('pegawai.login');
            }

            // Redirect default ke login user biasa
            return redirect()->route('login');
        }

        return $next($request);
    }
}
