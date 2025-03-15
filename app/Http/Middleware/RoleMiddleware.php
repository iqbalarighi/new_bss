<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles): Response
    {   
        $allowedRoles = explode(',', $roles); 

        $userRole = Auth::user()->role;

        if (!in_array($userRole, $allowedRoles)) {
            return redirect('/home'); // Redirect ke halaman utama jika tidak punya akses
        }
        return $next($request);
    }
}