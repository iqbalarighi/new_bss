<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {   
        if (!$request->user() || $request->user()->role != $role) {
            return redirect('/home'); // Redirect ke halaman utama jika tidak punya akses
        }
        return $next($request);
    }
}