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
        
        // Ambil role user
        $userRole = Auth::user()->role;
         $allowedRoles = explode('|', $roles);

        // Cek apakah role user ada dalam daftar yang diperbolehkan
        if (!in_array($userRole, $allowedRoles)) {
            return redirect('/home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return $next($request);
    }
}