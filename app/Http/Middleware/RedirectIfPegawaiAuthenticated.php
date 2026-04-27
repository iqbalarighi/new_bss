<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfPegawaiAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('pegawai')->check()) {
            return redirect()->route('absen');
        }

        return $next($request);
    }
}